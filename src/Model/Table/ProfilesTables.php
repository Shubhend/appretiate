<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ProfilesTables extends Table{

    public function initialize(array $config)
    {

        $this->setTable('profile');
        $this->setPrimaryKey('id');
        $this->setDisplayField('id');
        $this->addBehavior('Timestamp');






    }

}

?>