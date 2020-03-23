<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class CategorysTables extends Table{

    public function initialize(array $config)
    {
        $this->setTable('category');

        $this->setPrimaryKey('id');

        $this->belongsTo('Post', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER'
        ]);

    }
}

?>