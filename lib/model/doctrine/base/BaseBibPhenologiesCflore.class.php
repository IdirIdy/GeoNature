<?php

/**
 * BaseBibPhenologiesCflore
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_phenologie_cflore
 * @property string $nom_phenologie_cflore
 * @property Doctrine_Collection $TRelevesCflore
 * 
 * @method integer              get()                      Returns the current record's "id_phenologie_cflore" value
 * @method string               get()                      Returns the current record's "nom_phenologie_cflore" value
 * @method Doctrine_Collection  get()                      Returns the current record's "TRelevesCflore" collection
 * @method BibPhenologiesCflore set()                      Sets the current record's "id_phenologie_cflore" value
 * @method BibPhenologiesCflore set()                      Sets the current record's "nom_phenologie_cflore" value
 * @method BibPhenologiesCflore set()                      Sets the current record's "TRelevesCflore" collection
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBibPhenologiesCflore extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('contactflore.bib_phenologies_cflore');
        $this->hasColumn('id_phenologie_cflore', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('nom_phenologie_cflore', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('TRelevesCflore', array(
             'local' => 'id_phenologie_cflore',
             'foreign' => 'id_phenologie_cflore'));
    }
}