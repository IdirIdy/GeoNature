import { Component, NgModule, OnInit, OnDestroy, Inject, ViewChild } from '@angular/core';
import { NavService } from './services/nav.service';
import {TranslateService} from '@ngx-translate/core';
import {Router, ActivatedRoute} from '@angular/router';
import { Subscription } from 'rxjs/Subscription';
import * as firebase from 'firebase';
import { AuthService } from './components/auth/auth.service';
import {AppConfigs} from '../conf/app.configs';
import 'rxjs/Rx';
import {MdSidenavModule, MdSidenav} from '@angular/material';
import { SideNavService } from './components/sidenav-items/sidenav.service';


@Component({
  selector: 'pnx-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
  providers: [{ provide: AppConfigs, useValue: AppConfigs }]
})

export class AppComponent implements OnInit, OnDestroy {
  public appName: string;
  private subscription: Subscription;
  @ViewChild('sidenav') public sidenav: MdSidenav;

  // tslint:disable-next-line:max-line-length
  constructor(private _navService: NavService,
          private translate: TranslateService,
          public authService: AuthService,
          private activatedRoute: ActivatedRoute,
          private _sideBarService :SideNavService) {
      _navService.gettingAppName.subscribe(ms => {
        this.appName = ms;
        
    });

    translate.addLangs(['en', 'fr', 'cn']);
    translate.setDefaultLang(AppConfigs.defaultLanguage);
    translate.use(AppConfigs.defaultLanguage);
    
  }


  ngOnInit() {
    // subscribe to router event
    this.subscription = this.activatedRoute.queryParams.subscribe(
      (param: any) => {
        const locale = param['locale'];
        if (locale !== undefined) {
            this.translate.use(locale);
        }
      });
    // init firebase
    firebase.initializeApp({
      apiKey: 'AIzaSyBHvJhaMQdEFI0kM6LNagcFTQQWiDFCsOo',
      authDomain: 'geonature-a568d.firebaseapp.com',
    });

    // init the sidenav instance in sidebar service
    this._sideBarService.setSideNav(this.sidenav);     
  }
  changeLanguage(lang) {
    this.translate.use(lang);
}

  closeSideBar(){
    this._sideBarService.sidenav.toggle();
  }

  ngOnDestroy() {
    // prevent memory leak by unsubscribing
    this.subscription.unsubscribe();
  }

}