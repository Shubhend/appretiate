<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TrafficsTables extends Table{

    public function initialize(array $config)
    {

        parent::initialize($config);


        $this->setTable('traffic');
        $this->setPrimaryKey('id');
        $this->setDisplayField('id');
        $this->addBehavior('Timestamp');






    }

}

?>