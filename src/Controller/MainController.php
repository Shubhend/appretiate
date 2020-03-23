<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Controller\Services\EmailService;
use App\Model\Table\AppritiatespostsTables;
use App\Model\Table\CategorysTables;
use App\Model\Table\ContactsTables;
use App\Model\Table\NotificationsTables;
use App\Model\Table\PostsTables;
use App\Model\Table\ProfilesTables;
use App\Model\Table\TrafficsTables;
use App\Model\Table\TransactionsTables;
use App\Model\Table\UsersTables;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use mysql_xdevapi\Session;
use Cake\Network\Session\DatabaseSession;
use Cake\Mailer\Email;



class MainController extends AppController{
public $base_url;
public $table;
public $Users;


public function initialize(){
	parent::initialize();
   // $this->table=TableRegistry::get("insertadd");
	$this->viewBuilder()->layout("themelayout");
	$this->base_url=Router::url("/",true);
    $connection = ConnectionManager::get('default');
  // $this->table=TableRegistry::get("user");
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


}

public function removehtml($c){
   $c= trim(preg_replace('/[\t\n\r\s]+/', ' ', $c));
    $c = substr($c, 0, 100);
return $c=strip_tags($c);

}

public function index(){

$this->set("title","demo title");
$this->set("baseurl",$this->base_url);
$latest=[];
    $lastpost=$this->Post->find('all')->order(['id'=>'DESC'])->limit(2)->toArray();
    $d=[];
foreach ($lastpost as $l){

    $d['category']=$this->Category->find("all")->where(["id"=>$l['category_id']])->toArray()[0]['name'];
$d['username']=$this->Users->find("all")->where(["id"=>$l['user_id']])->toArray()[0]['f_name'];
$d['date']=date_format($l['create_date'],'l jS \of F Y ');
$d['title']=$l['title'];

if($l['thumbnail']=='' || $l['thumbnail']=='defaultprofile.jpg'){
$d['image']=$this->base_url."upload/default.jpg";

}else{
$d['image']=$this->base_url."webroot/upload/".$l['user_id']."/".$l['thumbnail'];
}


$d['id']=$l['id'];


    $d['content']=$this->removehtml($l['content']);


    array_push($latest,$d);



}
$trendpost=[];
$trendcat=[];
$query=$this->Traffic->find('all')->order(['date'=>'DESC','count'=>'DESC'])->limit(2)->toArray();
foreach ($query as $q){

    $postobj=$this->Post->findById($q['post_id'])->contain(['category'])->first();


    $c=[];
    $c['id']=$postobj['category']['id'];
    $c['cname']=$postobj['category']['name'];
    $c['title']=$postobj['title'];
    $c['username']=$this->Users->find("all")->where(["id"=>$postobj['user_id']])->first()['f_name'];
    $c['postid']=$postobj['id'];
    $c['content']=$this->removehtml($postobj['content']);
    $c['date']=date_format($postobj['create_date'],'jS  F Y ');

    if($postobj['thumbnail']=='' || $postobj['thumbnail']=='defaultprofile.jpg'){
        $c['image']=$this->base_url."upload/default.jpg";

    }else{
        $c['image']=$this->base_url."webroot/upload/".$postobj['user_id']."/".$postobj['thumbnail'];
    }


    array_push($trendcat,$c);

    $d=[];

    $d['category']=$postobj['category']['name'];
    $d['username']=$this->Users->find("all")->where(["id"=>$postobj['user_id']])->first()['f_name'];
    $d['date']=date_format($postobj['create_date'],'jS  F Y ');
    $d['title']=$postobj['title'];

    if($postobj['thumbnail']=='' || $postobj['thumbnail']=='defaultprofile.jpg'){
        $d['image']=$this->base_url."upload/default.jpg";

    }else{
        $d['image']=$this->base_url."webroot/upload/".$postobj['user_id']."/".$postobj['thumbnail'];
    }


    $d['id']=$postobj['id'];


    $d['content']=$this->removehtml($postobj['content']);

array_push($trendpost,$d);

}


    $category=$this->Category->find('all')->limit(5)->toArray();
$morecategory=$this->Category->find('all')->toArray();


$allpost=[];
    $lastposted=$this->Post->find('all')->order(['id'=>'ASC'])->limit(2)->toArray();
    $d=[];
    foreach ($lastposted as $l){
        $d['category']=$this->Category->find("all")->where(["id"=>$l['category_id']])->first()['name'];
        $d['username']=$this->Users->find("all")->where(["id"=>$l['user_id']])->toArray()[0]['f_name'];
        $d['date']=date_format($l['create_date'],'jS  F Y ');
        $d['title']=$l['title'];

        if($l['thumbnail']=='' || $l['thumbnail']=='defaultprofile.jpg'){
            $d['image']=$this->base_url."defaultprofile.jpg";

        }else{
            $d['image']=$this->base_url."webroot/upload/".$l['user_id']."/".$l['thumbnail'];
        }


        $d['id']=$l['id'];


        $d['content']=$this->removehtml($l['content']);


        array_push($allpost,$d);



    }




    $query = $this->Appritiate->find()->group('post_id')->limit(5)->toArray();
    $rand=[];
    foreach ($query as $q){
        array_push($rand,$q['post_id']);

}
    $moreappritiate=[];
    for($i=0;$i<sizeof($rand)-1;$i++){
        $t=rand(0,sizeof($rand)-1);


        $lastposted=$this->Post->findById($rand[$t])->toArray();
        $d=[];
        foreach ($lastposted as $l){
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


            array_push($moreappritiate,$d);



        }



    }




//var_dump($moreappritiate);exit;
$this->set("moreappritiate",$moreappritiate);
    $this->set("trendpost",$trendpost);
$this->set("trendcat",$trendcat);
    $this->set("latestpost",$latest);

    $this->set("morecategory",$morecategory);

    $this->set("category",$category);
    $this->set("allpost",$allpost);






}

public function register(){


    $this->set("title","demo title");
    $this->set("baseurl",$this->base_url);

    if($this->request->is("post")) {

        $data=$this->request->data;
$email=$data['email'];
$password=$data['password'];
$name=$data['name'];

       $data=$this->Users->find("all")->where(["email"=>$email])->toList();
         //   $data=$this->Users->get(2);

      if(! $data==null){

            $this->Flash->set(' user already Registered.', [
                'element' => 'error'
            ]);
            $this->redirect(array("controller" => "Main",
                "action" => "register"));

            return;

        }

        $userobj=$this->Users->newEntity();
        //var_dump("Asdasf");exit;
        $userobj->email=$email;
        $userobj->password=$password;
        $userobj->f_name=$name;
        $userobj->create_date= (new \DateTime())->format('Y-m-d H:i:s');
        $userobj->token=$this->getRandom(40);
        $userobj->refer=$this->getRandom(5);
        $this->Users->save($userobj);
       // $id=$userobj->id;
        $session = $this->getRequest()->getSession();
        $session->write('user',$email);



       // $this->request->session->write('userid',$email);
        //$uid=$this->request->session->read('userid');
       // $tableins=$this->table->newEntity();
        //$tableins->


        $data=$this->Setting->find('all')->where(["context"=>"email"])->toArray();

        if($data[0]['value']){
            $email=new EmailService();
            $email->sendregistermail();

        }





        $this->Flash->set('This user Registered.', [
            'element' => 'success'
        ]);
        $this->redirect(array("controller" => "Main",
            "action" => "login"));

return;

    }



}

    public   function getRandom($c) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $c; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }




public function login(){
    $url=$this->request->query('postid');
    //var_dump($url);exit;

    $this->set("title","demo title");
    $this->set("baseurl",$this->base_url);
    $session = $this->getRequest()->getSession();
   $t= $session->read('user');
   if(! $t=="" && !$t==null ){

       $this->redirect(array("controller" => "Admin",
           "action" => "index"));

       return;
   }


    if($this->request->is("post")) {

        $data=$this->request->data;
        $email=$data['email'];
        $password=$data['password'];

        $data=$this->Users->find("all")->where(["email"=>$email,"password"=>$password])->toList();
        //   $data=$this->Users->get(2);

        if(! $data==null){

            $session = $this->getRequest()->getSession();
            $session->write('user',$email);
if(! $url==''){


    $this->redirect(array("controller" => "Post",
        "action" => "Post","id"=>$url));
    return ;
}


            $this->redirect(array("controller" => "Main",
                "action" => "login"));

            return;

        }else{


            $this->Flash->set(' Wrong password or username.', [
                'element' => 'success'
            ]);
            $this->redirect(array("controller" => "Main",
                "action" => "login"));

            return;

        }





        // $this->request->session->write('userid',$email);
        //$uid=$this->request->session->read('userid');
        // $tableins=$this->table->newEntity();
        //$tableins->



    }



}
public function forgot(){
    $this->set("title","demo title");
    $this->set("baseurl",$this->base_url);
    $token=$this->request->query('token');
    $forgform=0;
    if(! $token==null){
        $forgform=1;
    }
    $this->set("forform",$forgform);
    if($this->request->is("post")) {
        $data = $this->request->data;
if($forgform==0) {

    $userobj = $this->Users->find("all")->where(["email" => $data['email']])->first();
    if (!$userobj == null) {

        $token = $userobj['token'];
        $userl = '';

        //send mail
    } else {

        $this->Flash->set(' We are unable to find Your Email or wrong Email.', [
            'element' => 'error'
        ]);
        $this->redirect(array("controller" => "Main",
            "action" => "forgot"));

        return;

    }
}else{

    $userobj2 = $this->Users->find("all")->where(["token" => $token])->first();
    $userobj = $this->Users->findById($userobj2->id)->first();
    if($userobj2==null){
        $this->Flash->set(' Wrong User Token.', [
            'element' => 'error'
        ]);
        $this->redirect(array("controller" => "Main",
            "action" => "forgot"));

        return;
    }

    $userobj->password=$data['password'];
    $this->Users->save($userobj);
   // var_dump($userobj);exit;
    $session = $this->getRequest()->getSession();
    $session->write('user',$userobj2->email);

    $this->redirect(array("controller" => "Main",
        "action" => "login"));

    return;



}

       // var_dump($data);exit;
    }

        // var_dump($token);exit;



}

public function contact(){
    $this->set("title","demo title");
    $this->set("baseurl",$this->base_url);

    if($this->request->is("post")) {

        $data = $this->request->data;

        $contactobj=$this->Contact->newEntity();

$contactobj->name=$data['name'];
$contactobj->email=$data['email'];
$contactobj->mes=$data['mes'];
$this->Contact->save($contactobj);
        $this->Flash->set(' Thanks For Contact Us , We will Contact You Soon .', [
            'element' => 'success'
        ]);


    }

    }

}


?>