<?php

/**
 * BaseCorBryoTaxon
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_station
 * @property integer $cd_nom
 * @property string $id_abondance
 * @property string $taxon_saisi
 * @property boolean $supprime
 * @property boolean $diffusable
 * @property TStationsBryo $TStationsBryo
 * @property Taxref $Taxref
 * @property BibAbondancesBryo $BibAbondancesBryo
 * 
 * @method integer           get()                  Returns the current record's "id_station" value
 * @method integer           get()                  Returns the current record's "cd_nom" value
 * @method string            get()                  Returns the current record's "id_abondance" value
 * @method string            get()                  Returns the current record's "taxon_saisi" value
 * @method boolean           get()                  Returns the current record's "supprime" value
 * @method boolean           get()                  Returns the current record's "diffusable" value
 * @method TStationsBryo     get()                  Returns the current record's "TStationsBryo" value
 * @method Taxref            get()                  Returns the current record's "Taxref" value
 * @method BibAbondancesBryo get()                  Returns the current record's "BibAbondancesBryo" value
 * @method CorBryoTaxon      set()                  Sets the current record's "id_station" value
 * @method CorBryoTaxon      set()                  Sets the current record's "cd_nom" value
 * @method CorBryoTaxon      set()                  Sets the current record's "id_abondance" value
 * @method CorBryoTaxon      set()                  Sets the current record's "taxon_saisi" value
 * @method CorBryoTaxon      set()                  Sets the current record's "supprime" value
 * @method CorBryoTaxon      set()                  Sets the current record's "diffusable" value
 * @method CorBryoTaxon      set()                  Sets the current record's "TStationsBryo" value
 * @method CorBryoTaxon      set()                  Sets the current record's "Taxref" value
 * @method CorBryoTaxon      set()                  Sets the current record's "BibAbondancesBryo" value
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCorBryoTaxon extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('bryophytes.cor_bryo_taxon');
        $this->hasColumn('id_station', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
        $this->hasColumn('cd_nom', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('id_abondance', 'string', 1, array(
             'type' => 'string',
             'length' => 1,
             ));
        $this->hasColumn('taxon_saisi', 'string', 150, array(
             'type' => 'string',
             'length' => 150,
             ));
        $this->hasColumn('supprime', 'boolean', 1, array(
             'type' => 'boolean',
             'length' => 1,
             ));
        $this->hasColumn('diffusable', 'boolean', 1, array(
             'type' => 'boolean',
             'length' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('TStationsBryo', array(
             'local' => 'id_station',
             'foreign' => 'id_station'));

        $this->hasOne('Taxref', array(
             'local' => 'cd_nom',
             'foreign' => 'cd_nom'));

        $this->hasOne('BibAbondancesBryo', array(
             'local' => 'id_abondance',
             'foreign' => 'id_abondance'));
    }
}