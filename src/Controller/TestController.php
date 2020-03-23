<?php

namespace App\Controller;

use App\Controller\AppController;
class TestController extends AppController{
	

public function index(){
print_r($this->request->params['pass']);

$this->set("name","shubhendu");
$this->set("last","kumar");

	$d=array("author"=>"rrk","nash"=>"sarv");
	$this->set("data",$d);



}


public function ren(){
$this->autoRender=false;


}

}


?>