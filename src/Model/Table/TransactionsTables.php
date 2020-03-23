<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TransactionsTables extends Table{

    public function initialize(array $config)
    {

        parent::initialize($config);


        $this->setTable('transaction');
        $this->setPrimaryKey('id');
        $this->setDisplayField('id');
        $this->addBehavior('Timestamp');






    }

}

?>