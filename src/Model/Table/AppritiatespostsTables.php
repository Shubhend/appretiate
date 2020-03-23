<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class AppritiatespostsTables extends Table{

    public function initialize(array $config)
    {

        parent::initialize($config);

        $this->belongsTo('user');

        $this->belongsTo('post');

        $this->setTable('Appritiatepost');
        $this->setPrimaryKey('id');
        $this->setDisplayField('id');
        $this->addBehavior('Timestamp');

     //  $this->hasOne('Users');
        //$this->hasOne('Posts');





    }
}

?>