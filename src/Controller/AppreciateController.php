<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\AppritiatespostsTables;
use App\Model\Table\CategorysTables;
use App\Model\Table\ContactsTables;
use App\Model\Table\NotificationsTables;
use App\Model\Table\PostsTables;
use App\Model\Table\ProfilesTables;
use App\Model\Table\TrafficsTables;
use App\Model\Table\TransactionsTables;
use Cake\Routing\Router;


class AppreciateController extends AppController{
    public $base_url;

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->layout("themelayout");
        $this->base_url=Router::url("/",true);
        $this->Users=$this->loadModel("User");
        $this->Setting=$this->loadModel("setting");
        $this->Contact=$this->loadModel(ContactsTables::class);
        $this->Post=$this->loadModel(PostsTables::class);


        $this->Setting=$this->loadModel("setting");
        $this->Wallet=$this->loadModel("Wallet");
        $this->Posts=$this->loadModel(PostsTables::class);
        $this->Categories=$this->loadModel(CategorysTables::class);
        $this->Appritiate=$this->loadModel(AppritiatespostsTables::class);
        $this->Traffic=$this->loadModel(TrafficsTables::class);

        $this->Profile=$this->loadModel(ProfilesTables::class);
        $this->Notification=$this->loadModel(NotificationsTables::class);
        $this->Transaction=$this->loadModel(TransactionsTables::class);
        $this->Category=$this->loadModel(CategorysTables::class);

        $ip='';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

// Use JSON encoded string and converts
// it into a PHP variable
        $ipdat = @json_decode(file_get_contents(
            "http://www.geoplugin.net/json.gp?ip=" . $ip));


//$ipd=["country"=>$ipdat->geoplugin_countryName,"city"=>$ipdat->geoplugin_city,"city"=>$ipdat->geoplugin_city];

//$this->set("ip",$ipd);
//var_dump($ip);exit;
        $this->set("title","demo title");
        $this->set("baseurl",$this->base_url);

    }

    function getsession(){

        $send=array("error"=>"","msg"=>"");
        $session = $this->getRequest()->getSession();
        $email=$session->read('user');
        $this->Users=$this->loadModel("User");

        $dataobj=$this->Users->find("all")->where(["email"=>$email])->toList();
        if($dataobj=='' || $dataobj==null){

            $send["error"]=2;
            $send['msg']='Please Login';

            $this->Flash->set(' Please Login.', [
                'element' => 'error'
            ]);



            echo json_encode($send);

            exit;
        }

        $userid=$dataobj[0]['id'];
        return $userid;
    }




    public function search(){

        $search=$this->request->query('search');

        $relatedpost=[];

        $query=$this->Post->find('all', array('conditions'=>array('title LIKE'=>'%'.$search.'%' )))->toArray();
        $d=[];
        foreach ($query as $l){


            $d['cid']=$l['category_id'];
            $d['category']=$this->Category->find("all")->where(["id"=>$l['category_id']])->toArray()[0]['name'];
            $d['username']=$this->Users->find("all")->where(["id"=>$l['user_id']])->toArray()[0]['f_name'];
            $d['date']=date_format($l['create_date'],'l jS \of F Y ');
            $d['title']=$l['title'];

            if($l['thumbnail']=='' || $l['thumbnail']=='defaultprofile.jpg'){
                $d['image']=$this->base_url."defaultprofile.jpg";

            }else{
                $d['image']=$this->base_url."webroot/upload/".$l['user_id']."/".$l['thumbnail'];
            }


            $d['id']=$l['id'];


            $d['content']=$this->removehtml($l['content']);


            array_push($relatedpost,$d);


        }

        $category=$this->Category->find('all')->limit(5)->toArray();
        $morecategory=$this->Category->find('all')->toArray();

        $this->set("morecategory",$morecategory);

        $this->set("category",$category);
        // var_dump($query);exit;
        $this->set("latestpost",$relatedpost);


    }

    public function removehtml($c){
        $c= trim(preg_replace('/[\t\n\r\s]+/', ' ', $c));
        $c = substr($c, 0, 100);
        return $c=strip_tags($c);

    }

}


?>