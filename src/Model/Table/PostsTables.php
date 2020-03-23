<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class PostsTables extends Table{

    public function initialize(array $config)
    {

parent::initialize($config);


        $this->belongsTo('category');
        $this->setTable('Post');
        $this->setPrimaryKey('id');
        $this->setDisplayField('id');
        $this->addBehavior('Timestamp');

        $this->hasOne('Categorys');





}
}

?>