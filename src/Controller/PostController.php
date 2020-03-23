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


class PostController extends AppController{
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


public function giveappri(){

    if($this->request->is("post")) {
$send=["error"=>0,"msg"=>""];
        $data=$this->request->data();

        $userid=$this->getsession();
        if($userid=='' || $userid==null){

            $send["error"]=2;
            $send['msg']='Please Login';

            $this->Flash->set(' Please Login.', [
                'element' => 'error'
            ]);

            $t=$this->redirect(array("controller" => "Main",
                "action" => "login"));


            echo json_encode($t);
            exit;
        }

        $sppriobj=$this->Appritiate->find()->where(['post_id'=>$data['id'],'user_id'=>$userid])->toArray();
        if(! $sppriobj== null){
            $send["error"]=1;
            $send['msg']='You Have Already Appriciated On This Post';

            echo json_encode($send);
            exit;


        }
        $date=date('Y-m-d');

        $appriobj=$this->Appritiate->newEntity();
        $appriobj->user_id=$userid;
        $appriobj->post_id=$data['id'];
        $appriobj->date=$date;
        $this->Appritiate->save($appriobj);

        $send["error"]=0;
        $send['msg']='We Never Forgot your Appreciation';

        echo json_encode($send);
        exit;

    }
}


    public function post(){
$title='';
        $id=$this->request->query('id');
       // var_dump($id);exit;
$views=0;
        $date=date("Y-m-d");

        $traf=$this->Traffic->find()->where(['post_id'=>$id,'date'=>$date])->limit(1)->toArray();
if($traf==null){

    $traobj=$this->Traffic->newEntity();
    $traobj->post_id=$id;
    $traobj->count=1;
    $traobj->date=$date;
    $this->Traffic->save($traobj);
}else{
    $c=$traf[0]['count'];
    $views=$c;
    $traf=$this->Traffic->findById($traf[0]['id'])->first();

$traf->count=$c+1;
$this->Traffic->save($traf);



}
$totalappri=$this->Appritiate->find()->where(['post_id'=>$id])->count();
$this->set("totalappritiate",$totalappri);


     //   var_dump($traf);exit;


$this->set("views",$views);


        $latest=[];
        $lastpost=$this->Post->findById($id)->limit(1)->toArray();
        $d=[];
        $p=[];
        $profile=[];
$title=$lastpost[0]['title'];

        foreach ($lastpost as $l){
            $title=$l['title'];
            $userobj=$this->Profile->findByUserId($l['user_id'])->first();
           // var_dump($userobj);
if(! $userobj==null){
    if($userobj['image']=='' || $userobj['image']=='defaultprofile.jpg'){
        $d['userimage']=$this->base_url."webroot/upload/default.jpg";

    }else{
        $d['userimage']=$this->base_url."webroot/upload/".$l['user_id']."/profile/".$userobj['image'];
    }
}else{
    $d['userimage']=$this->base_url."webroot/upload/default.jpg";
}

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


            $d['content']=$l['content'];


            array_push($latest,$d);



        }

$this->set("data",$latest);

$relatedpost=[];
      $query=$this->Post->find('all', array('conditions'=>array('title LIKE'=>'%'.$title.'%','category_id'=>$d['cid'])))->toArray();
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


    $d['content']=$l['content'];


    array_push($relatedpost,$d);


}
       // var_dump($query);exit;
        $this->set("relatedpost",$relatedpost);

        $this->set("title","demo title");
        $this->set("baseurl",$this->base_url);


    }


    public function category(){
        $this->set("title","demo title");
        $this->set("baseurl",$this->base_url);

        $search=$this->request->query('id');

        $relatedpost=[];

        $query=$this->Post->findAllByCategoryId($search)->toArray();

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