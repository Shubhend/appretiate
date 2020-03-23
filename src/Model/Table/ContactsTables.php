<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ContactsTables extends Table{

    public function initialize(array $config)
    {

        parent::initialize($config);


           $this->setTable('contact');
        $this->setPrimaryKey('id');
        $this->setDisplayField('id');
        $this->addBehavior('Timestamp');

        //  $this->hasOne('Users');
        //$this->hasOne('Posts');





    }
}

?>