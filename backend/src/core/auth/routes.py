from flask import Blueprint, request, make_response, url_for, redirect, current_app, jsonify
import requests
import datetime
import xmltodict
from xml.etree import ElementTree as ET
import json
from itsdangerous import TimedJSONWebSignatureSerializer as Serializer

from ..gn_meta import routes as gn_meta
from ..users import routes as users

from ...utils.utilssqlalchemy import json_resp
from ...utils import utilsrequests

from flask_sqlalchemy import SQLAlchemy
db = SQLAlchemy()

### Module d'identificiation provisoire pour test du CAS INPN ###

routes = Blueprint('auth_cas', __name__)


@routes.route('/login', methods=['GET', 'POST'])
def loginCas():
    configCas = current_app.config['CAS']
    params = request.args
    if 'ticket' in params:
        base_url = current_app.config['API_ENDPOINT']+"/auth_cas/login"
        urlValidate = "%s?ticket=%s&service=%s"%(configCas['URL_VALIDATION'], params['ticket'], base_url)
        response = utilsrequests.get(urlValidate)
        user = None
        xmlDict = xmltodict.parse(response.content)
        resp = xmlDict['cas:serviceResponse']
        if 'cas:authenticationSuccess' in resp:
            user = resp['cas:authenticationSuccess']['cas:user']
        if user:
            WSUserUrl = "%s/%s/?verify=false"%(configCas['USER_WS']['URL'], user)

            response = utilsrequests.get(WSUserUrl, (configCas['USER_WS']['ID'], configCas['USER_WS']['PASSWORD'])) 
            
            infoUser = response.json()
            organismId = infoUser['codeOrganisme']
            organismName = infoUser['libelleLongOrganisme'] if infoUser['libelleLongOrganisme'] != None else 'Autre'
            userLogin = infoUser['login']
            userId = infoUser['id']
            ## Reconciliation avec base GeoNature
            if organismId:
                organism = {
                    "id_organisme":organismId,
                    "nom_organisme": organismName
                }
                resp = users.insertOrganism(organism)
                #r = utilsrequests.post(current_app.config['API_ENDPOINT']+'/users/organism', json = organism)

            user = {
                "id_role":userId,
                "identifiant":userLogin, 
                "nom_role": infoUser['nom'],
                "prenom_role": infoUser['prenom'],
                "id_organisme": organismId,
            }
            resp = users.insertRole(user)
            #r = utilsrequests.post(current_app.config['API_ENDPOINT']+'/users/role', json = user)
            ## push the user in the right group
            if organismId is None:
                # group socle 1
                users.insert_in_cor_role(20003, user['id_role'])
            else:
                # group socle 2
                users.insert_in_cor_role(20001, user['id_role'])
            user["id_application"] = current_app.config['ID_APPLICATION_GEONATURE']

            ## Creation of datasets
            gn_meta.post_jdd_from_user_id(userId, organismId)

            # creation de la Response
            response = make_response(redirect(current_app.config['URL_APPLICATION']))
            cookieExp = datetime.datetime.utcnow()
            expiration = current_app.config['COOKIE_EXPIRATION']
            cookieExp += datetime.timedelta(seconds=expiration)
            ## generation d'un token
            s = Serializer(current_app.config['SECRET_KEY'], expiration)
            token = s.dumps(user)
            response.set_cookie('token',
                                token,
                                expires=cookieExp)
            # User cookie
            
            currentUser = {
                'userName': userLogin,
                'userId': userId,
                'organismId': organismId if organismId else -1
            }
            response.set_cookie('currentUser',
                                    str(currentUser),
                                    expires=cookieExp)
            return response
        else:
            # redirect to inpn sss           
            return """<p> Echec de l'authentification. <p>
             <p> Deconnectez-vous du service INPN avant de retenter une connexion à GeoNature </p>
             <p> <a target="_blank" href="""+current_app.config['CAS']['URL_LOGOUT']+"""> Deconnexion </a> </p>
             <p> <a target="_blank" href="""+current_app.config['URL_APPLICATION']+"""> Retour vers GeoNature </a> </p>
             """
    return jsonify({'message': 'Authentification error'}, 500)