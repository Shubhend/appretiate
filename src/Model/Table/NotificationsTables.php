<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class NotificationsTables extends Table{

    public function initialize(array $config)
    {
        $this->setTable('notification');

        $this->setPrimaryKey('id');


    }

}

?>