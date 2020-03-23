<?php
namespace App\Controller;


use App\Controller\AppController;
use App\Controller\Services\EmailService;
use App\Model\Table\AppritiatespostsTables;
use App\Model\Table\CategorysTables;
use App\Model\Table\NotificationsTables;
use App\Model\Table\PostsTables;
use App\Model\Table\ProfilesTables;
use App\Model\Table\TrafficsTables;
use App\Model\Table\TransactionsTables;
use Cake\Network\Email\Email;
use Cake\Routing\Router;
use Cake\Mailer;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;



class AdminController extends AppController{
    public $base_url;

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->layout("adminlayout");
        $this->base_url=Router::url("/",true);
        $connection = ConnectionManager::get('default');
        // $this->table=TableRegistry::get("user");
        $this->loadModel('Files');
        $this->Setting=$this->loadModel("setting");
        $this->Wallet=$this->loadModel("Wallet");
        $this->Posts=$this->loadModel(PostsTables::class);
        $this->Categories=$this->loadModel(CategorysTables::class);
        $this->Appritiate=$this->loadModel(AppritiatespostsTables::class);
        $this->Traffic=$this->loadModel(TrafficsTables::class);

        $this->Profile=$this->loadModel(ProfilesTables::class);
        $this->Notification=$this->loadModel(NotificationsTables::class);
        $this->Transaction=$this->loadModel(TransactionsTables::class);
        $session = $this->getRequest()->getSession();
        $this->authorize();
        $email=$session->read('user');
        if($email== null ){
            $this->Flash->set('Please Login', [
                'element' => 'success'
            ]);
            header("location:http://localhost/cake/cake/login");


exit;

        }
        $this->Users=$this->loadModel("User");
        $dataobj=$this->Users->find("all")->where(["email"=>$email])->toList();
        $userid=$dataobj[0]['id'];

        $this->set("adminame",$dataobj[0]['f_name']);

$totaltraf=0;
$totalappri=0;
$post=$this->Posts->findAllByUserId($userid)->toArray();
foreach ($post as $p){
    $total = $this->Appritiate->find()->where(['post_id'=>$p['id'] ])->count();
    $traffic = $this->Traffic->find()->where(['post_id'=>$p['id'] ])->toArray();
foreach ($traffic as $t){
    $totaltraf=$totaltraf+$t['count'];

}
$totalappri=$totalappri+$total;



}
        $settingtrafficcost=$this->Setting->findByName('per_traffic_cost')->toArray();
        $appritiatecost=$this->Setting->findByName('per_appritiate_cost')->toArray();
        $trcost=$settingtrafficcost[0]['value'];
        $apcost=$appritiatecost[0]['value'];


        $notifications=$this->Notification->findAllByUserId($userid)->toArray();

        $notificationsall=$this->Notification->findAllByUserId(0)->toArray();
        //array_push($notifications,$notificationsall);
        //var_dump($notifications);exit;
        $this->set("earning",$this->getcost()['totalearn']);
        $this->set("traffic",$totaltraf);
        $this->set("appritiate",$totalappri);
        $this->set("notification",$notifications);

        $this->set("title","Dashboard");
    }

    public function authorize(){

        $session = $this->getRequest()->getSession();
        $uid=$session->read('user');
        if($uid== null ){
            $this->Flash->set('Please Login', [
                'element' => 'success'
            ]);

            $this->redirect(array("controller" => "Main",
                "action" => "login"),302);
            return false;

        }
return true;

    }


    public function getcost(){
        $trafficost=$this->Setting->find('all')->where(["context"=>"traffic","name"=>"per_traffic_cost"])->toArray()[0]['value'];
        $postcost=$this->Setting->find('all')->where(["context"=>"post","name"=>"post_per_cost"])->toArray()[0]['value'];
        $appricost=$this->Setting->find('all')->where(["context"=>"appritiatecost","name"=>"per_appritiate_cost"])->toArray()[0]['value'];
$postearning=0;
        $session = $this->getRequest()->getSession();
        $email=$session->read('user');
        $this->Users=$this->loadModel("User");
        $dataobj=$this->Users->find("all")->where(["email"=>$email])->toList();
        $userid=$dataobj[0]['id'];


            $data=$this->Wallet->find('all')->where(["user_id"=>$userid])->toArray();
        foreach ($data as $d){
            if($d['type']==1){
                $postearning=$postearning+$d['cost'];
            }else{
                $postearning=$postearning-$d['cost'];
            }

        }
//var_dump($postearning);exit;
$totaltraf=0;
        $totalappri=0;

        $post=$this->Posts->findAllByUserId($userid)->toArray();
        foreach ($post as $p){
            $total = $this->Appritiate->find()->where(['post_id'=>$p['id'] ])->count();
            $traffic = $this->Traffic->find()->where(['post_id'=>$p['id'] ])->toArray();
            foreach ($traffic as $t){
                $totaltraf=$totaltraf+$t['count']*$trafficost;

            }
            $totalappri=$totalappri+$total*$appricost;



        }
$totalearning=$postearning+$totaltraf+$totalappri;

$tran=$this->Transaction->find('all')->where(['user_id'=>$userid,'status'=>1])->toArray();
$redemmedamount=0;
foreach ($tran as $t){
    $redemmedamount=$redemmedamount+$t['cost'];

}

$redemlimit=$totalearning-$redemmedamount;

    //    $redeemearn=

        return ['postearn'=>$postearning,'trafficearn'=>$totaltraf,'appritiateearn'=>$totalappri,'totalearn'=>$totalearning,'redemlimit'=>$redemlimit];




    }


    public function transaction(){



        $session = $this->getRequest()->getSession();
        $email=$session->read('user');
        $this->Users=$this->loadModel("User");
        $dataobj=$this->Users->find("all")->where(["email"=>$email])->toList();
        $userid=$dataobj[0]['id'];
        $transaction=$this->Transaction->findByUserId($userid)->order(['id'=>'DESC'])->toArray();
        $st=0;
      //  var_dump($transaction[0]['status']);exit;

        $this->set("st",$st);
        $this->set("data",$transaction);

        if($this->request->is("post")) {

$data=$this->request->data();
            if($transaction[0]['status']==0){
                $this->Flash->set('Your Last Payment Is pending', [
                    'element' => 'success'
                ]);
                return;
            }

            $thresholdprice=100;

          if($data['money']<$thresholdprice){

              $this->redirect(array("controller" => "Admin",
                  "action" => "transaction"));


              $this->Flash->set('Minimum 100 Can be transfer', [
                  'element' => 'success'
              ]);
              return ;
          }
$totalprice=$this->getcost()['redemlimit'];;
if($data['money']>$totalprice){
    $this->Flash->set('You can transfer '.$totalprice, [
        'element' => 'success'
    ]);
    return ;

}
            $date=date("Y-m-d");

            $tr=$this->Transaction->newEntity();
$tr->user_id=$userid;
$tr->cost=$data['money'];
$tr->transaction_id=rand(1000,2000);
$tr->date=$date;
$tr->status=0;
$this->Transaction->save($tr);



            $this->Flash->set('You Request Under Process ,we will contact You  '.$totalprice, [
                'element' => 'success'
            ]);
            return ;


          //     var_dump($data);exit;


        }



        }

public function profile(){
        $this->authorize();
    $session = $this->getRequest()->getSession();
    $email=$session->read('user');
    $this->Users=$this->loadModel("User");
    $dataobj=$this->Users->find("all")->where(["email"=>$email])->toList();
    $userid=$dataobj[0]['id'];


    $pdata=$this->Profile->findByUserId($userid)->toArray();

  //$uid=$pdata[0]['user_id'];


if(!$pdata==null) {
    $pimage=$pdata[0]['image'];
    $pcompany=$pdata[0]['company'];
    $phobby=$pdata[0]['hobbies'];
    $this->set("image", $pimage);
    $this->set("hobby", $phobby);
    $this->set("company", $pcompany);
    $this->set("image", $pimage);
    $this->set("phone",$pdata[0]['phone']);
}else{
    $this->set("image", "");
    $this->set("hobby", "");
    $this->set("company", "");
    $this->set("image", "");
    $this->set("phone", "");
}

  $this->set("name",$dataobj[0]['f_name']);
  $this->set("email",$dataobj[0]['email']);
  $this->set("userid",$userid);


    if($this->request->is("post")) {




        $date=date("Y-m-d");






        $data = $this->request->data;
$upload=0;
        if(! $_FILES['file']['name']=='' && ! $_FILES['file']['tmp_name']==''){
$upload=1;
           // var_dump("asdadasd");exit;
        }

//var_dump($data);exit;

if($upload==1){

    $fileName =rand(1,20000).".jpg";

    $path = $_FILES['file']['name'];
    $imageFileType = pathinfo($path, PATHINFO_EXTENSION);



    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {

        $this->Flash->set('Select Only Image Format.', [
            'element' => 'error'
        ]);

        return;

    }

    if (!file_exists('upload/'.$userid.'/profile')) {
        mkdir('upload/'.$userid.'/profile', 0777, true);
    }

    $uploadPath ='upload/'.$userid.'/profile/';
    $uploadFile = $uploadPath.$fileName;


    move_uploaded_file($this->request->data['file']['tmp_name'],$uploadFile);


}else{
    $fileName='defaultprofile.jpg';
}

        $pdata=$this->Profile->findByUserId($userid)->toArray();

if($pdata==null){

    $pobj2=$this->Profile->newEntity();

}else{

    $pobj2=$this->Profile->findByUserId($userid)->first();

}
//var_dump($pobj2);exit;


        $pobj2->gender = $data['gender'];
        $pobj2->company = $data['company'];
        $pobj2->date = date("Y-m-d H:i:s");
        $pobj2->user_id=$userid;

        $pobj2->hobbies=$data['hobby'];

        $pobj2->image=$fileName;
        $pobj2->phone=$data['phone'];
        //var_dump($userid);exit;

        if ($this->Profile->save($pobj2)) {

            $this->redirect(array("controller" => "Admin",
                "action" => "profile"));


                $this->Flash->set('Data Updated', [
                    'element' => 'success'
                ]);

            }else{
            $this->redirect(array("controller" => "Admin",
                "action" => "profile"));


            $this->Flash->set('Please Try Again After Sometime.', [
                    'element' => 'error'
                ]);
            }








    }






    $this->set("baseurl",$this->base_url);




}

    public function index(){

        $this->set("title","demo title");
        $this->set("baseurl",$this->base_url);

    }


    public function logout(){
        $session = $this->getRequest()->getSession();


        $session->destroy();

        $this->redirect(array("controller" => "Main",
            "action" => "login"));

        return;

    }



public function wallet($param = null){
   $paraid=$this->request->query('id');
    $this->set("title","demo title");

    $session = $this->getRequest()->getSession();
    $email=$session->read('user');
    $this->Users=$this->loadModel("User");
    $dataobj=$this->Users->find("all")->where(["email"=>$email])->toList();
    $userid=$dataobj[0]['id'];

if(!$paraid==null){
    $data=$this->Wallet->find('all')->where(["post_id"=>$paraid])->order(['id'=>'DESC'])->toArray();
}else{
    $data=$this->Wallet->find('all')->where(["user_id"=>$userid])->order(['id'=>'DESC'])->toArray();
}


$ardata=array(
    "data"=>$data
);

    $this->set("baseurl",$this->base_url);
    $this->set("data",$ardata);



}

function viewpost(){
       // var_dump($this->Posts);
    $this->authorize();
    $session = $this->getRequest()->getSession();
    $email=$session->read('user');
    $this->Users=$this->loadModel("User");
    $dataobj=$this->Users->find("all")->where(["email"=>$email])->toList();
    $userid=$dataobj[0]['id'];
    $data=$this->Posts->find('all')->where(["user_id"=>$userid])->contain(['category'])->toArray();

//var_dump($data);exit;

    $this->set("baseurl",$this->base_url);
    $this->set("data",$data);


}

function post(){

$this->authorize();
    $this->set("title","demo title");
    $this->set("baseurl",$this->base_url);
$cat=$this->Categories->find('all')->toArray();
    $this->set("cat",$cat);

    if($this->request->is("post")) {



        $setting=$this->Setting->find('all')->where(["context"=>"post","name"=>"post_upload_limit"])->toArray();

        $postcost=$this->Setting->find('all')->where(["context"=>"post","name"=>"post_per_cost"])->toArray()[0]['value'];


        $date=date("Y-m-d");


        $post=$this->Posts->findAllByCreateDate($date)->toArray();

      $todayspost=sizeof($post);

      if($todayspost > $setting[0]['value']){

          $this->Flash->set('Today Post Limit Has Finish, You can post tomorrow.', [
              'element' => 'error'
          ]);

          return;

        }





        $data = $this->request->data;

        $fileName =  strip_tags(substr(preg_replace("/ /", '_',$data['title']).rand(1,20),0,10)).".jpg";

        $path = $_FILES['file']['name'];
        $imageFileType = pathinfo($path, PATHINFO_EXTENSION);



        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {

            $this->Flash->set('Select Only Image Format.', [
                'element' => 'error'
            ]);

            return;

        }


        $session = $this->getRequest()->getSession();
        $email=$session->read('user');
        $this->Users=$this->loadModel("User");
        $dataobj=$this->Users->find("all")->where(["email"=>$email])->toList();
        $userid=$dataobj[0]['id'];




        if (!file_exists('upload/'.$userid)) {
            mkdir('upload/'.$userid, 0777, true);
        }

        $uploadPath ='upload/'.$userid.'/';
        $uploadFile = $uploadPath.$fileName;


            if(move_uploaded_file($this->request->data['file']['tmp_name'],$uploadFile)){
                $uploadData = $this->Posts->newEntity();


                $uploadData->title = $data['title'];
                $uploadData->meta_des = $data['metades'];
               $uploadData->create_date = date("Y-m-d H:i:s");
                $uploadData->modify_date = date("Y-m-d H:i:s");
                $uploadData->content=htmlentities($data['content']);
                $uploadData->blacklist=0;
                $uploadData->category_id=$data['category'];
                $uploadData->user_id=$userid;
                $uploadData->seen=0;
                $uploadData->thumbnail=$fileName;
                $uploadData->price=$postcost;
                if ($this->Posts->save($uploadData)) {

                    $wallet = $this->Wallet->newEntity();
                    $wallet->type=1;
                    $wallet->user_id=$userid;
                    $wallet->post_id=$uploadData->id;
                    $wallet->content="Post Credit ";
                    $wallet->cost=$postcost;
                    $wallet->date=$date;
                    $this->Wallet->save($wallet);

                    $this->redirect(array("controller" => "Admin",
                        "action" => "viewpost"));


                    $this->Flash->set('Post Uploaded, You Have Got Reward .', [
                        'element' => 'success'
                    ]);

                }else{
                    $this->Flash->set('Please Try Again After Sometime.', [
                        'element' => 'error'
                    ]);
                }

            }else{
                $this->Flash->set('Unable to Upload Status.', [
                    'element' => 'error'
                ]);

                return;


            }






    }


}

public function editpost(){
    $paraid=$this->request->query('id');
    if($paraid==null || $paraid==''){
        $this->redirect(array("controller" => "Admin",
            "action" => "viewpost"));

        return;
    }

    $data=$this->Posts->findById($paraid)->toArray();

    $this->set("title","demo title");
    $this->set("baseurl",$this->base_url);
    $this->set("data",$data);
    $cat=$this->Categories->find('all')->toArray();
    $this->set("cat",$cat);

    if($this->request->is("post")) {

/*

        $setting=$this->Setting->find('all')->where(["context"=>"post","name"=>"post_upload_limit"])->toArray();

        $postcost=$this->Setting->find('all')->where(["context"=>"post","name"=>"post_per_cost"])->toArray()[0]['value'];


        $date=date("Y-m-d");


        $post=$this->Posts->findAllByCreateDate($date)->toArray();

        $todayspost=sizeof($post);

        if($todayspost > $setting[0]['value']){

            $this->Flash->set('Today Post Limit Has Finish, You can post tomorrow.', [
                'element' => 'error'
            ]);

            return;

        }


*/


        $data = $this->request->data;

        $fileName = preg_replace("/ /", '_',$data['title']).rand(1,200).".jpg";

        $path = $_FILES['file']['name'];
        $imageFileType = pathinfo($path, PATHINFO_EXTENSION);



        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {

            $this->Flash->set('Select Only Image Format.', [
                'element' => 'error'
            ]);

            return;

        }


        $session = $this->getRequest()->getSession();
        $email=$session->read('user');
        $this->Users=$this->loadModel("User");
        $dataobj=$this->Users->find("all")->where(["email"=>$email])->toList();
        $userid=$dataobj[0]['id'];




        if (!file_exists('upload/'.$userid)) {
            mkdir('upload/'.$userid, 0777, true);
        }

        $uploadPath ='upload/'.$userid.'/';
        $uploadFile = $uploadPath.$fileName;


        if(move_uploaded_file($this->request->data['file']['tmp_name'],$uploadFile)){
            $uploadData = $this->Posts->findById($paraid)->first();


            $uploadData->title = $data['title'];
            $uploadData->meta_des = $data['metades'];
           // $uploadData->create_date = date("Y-m-d H:i:s");
            $uploadData->modify_date = date("Y-m-d H:i:s");
            $uploadData->content=htmlentities($data['content']);
           // $uploadData->blacklist=0;
            $uploadData->category_id=$data['category'];
            $uploadData->user_id=$userid;
            //$uploadData->seen=0;
            $uploadData->thumbnail=$fileName;
           // $uploadData->price=$postcost;
            if ($this->Posts->save($uploadData)) {
/*
                $wallet = $this->Wallet->newEntity();
                $wallet->type=1;
                $wallet->user_id=$userid;
                $wallet->post_id=$uploadData->id;
                $wallet->content="Post Credit ";
                $wallet->cost=$postcost;
                $wallet->date=$date;
                $this->Wallet->save($wallet);
*/




                $this->Flash->set('Post Updated .', [
                    'element' => 'success'
                ]);

                $this->redirect(array("controller" => "Admin",
                    "action" => "viewpost"));

                return;

            }else{
                $this->Flash->set('Please Try Again After Sometime.', [
                    'element' => 'error'
                ]);
            }

        }else{
            $this->Flash->set('Unable to Upload Status.', [
                'element' => 'error'
            ]);

            return;


        }






    }




}

public function details(){
    $paraid=$this->request->query('id');
    $this->set("title","demo title");
    $this->set("baseurl",$this->base_url);
    $session = $this->getRequest()->getSession();
    $email=$session->read('user');
    $this->Users=$this->loadModel("User");
    $dataobj=$this->Users->find("all")->where(["email"=>$email])->toList();
    $userid=$dataobj[0]['id'];
    $data=$this->Appritiate->findAllByPostId($paraid)->contain(['User','post'])->group(['date'])->toArray();
    $settingtrafficcost=$this->Setting->findByName('per_traffic_cost')->toArray();
    $appritiatecost=$this->Setting->findByName('per_appritiate_cost')->toArray();
    $trcost=$settingtrafficcost[0]['value'];
    $apcost=$appritiatecost[0]['value'];

    $records=[];

foreach ($data as $d){

    $dateup=date_format($d['date'],"Y-m-d");
    $total = $this->Appritiate->find()->where(['user_id' => $d['user_id'],'post_id'=>$d['post_id'],'date'=>$dateup])->count();
    $traffic = $this->Traffic->find()->where(['post_id'=>$d['post_id'],'date'=>$dateup])->toArray();
    if($traffic==null){
        $traffic=0;
    }else{
        $traffic=$traffic[0]['count'];
    }

    $t=["data"=>"","count"=>0];
$t["data"]=$d;
$t["count"]=$total;
$t["traffic"]=$traffic;
$p=$traffic*$trcost+$total*$apcost;

$t['profit']=$p;

array_push($records,$t);
  //  var_dump($total);exit;



}

//var_dump($records);exit;

      $this->set("data",$records);

}

}?>