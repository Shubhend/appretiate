
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->


          <!-- Content Row -->
<?php include('info.ctp'); ?>
          <!-- Content Row -->



          <!-- Content Row -->
          <div class="row">

            <!-- Content Column -->

<div class="container">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Your Post</h1>
             </div>



<div class="container m-t-md">
  <div class="row m-t-md">


<?php

foreach($data as $data):

?>


    <div class="col-xs-12 col-md-4" style="margin-bottom:10px;color:white;">
      <!-- Card -->
      <article class="card card-inverse animated fadeInLeft">
        <img class="img-responsive" style="filter:brightness(0.5);height:250px;" src="<?= $baseurl.'webroot/upload/'.$data['user_id'].'/'.$data['thumbnail']; ?>" />
        <div class="card-img-overlay">
          <h4 class="card-title"><?= substr($data['title'],0,20); ?></h4>
          <h6 class="text-muted"><?= $data['category']->name; ?></h6>
          <a href="<?php	echo $this->Url->build([  "controller" => "Admin", "action" => "editpost", 'id' => $data['id'] ]); ?>" class="btn btn-primary">Edit</a>
                     <a href="<?php	echo $this->Url->build([  "controller" => "Admin", "action" => "details", 'id' => $data['id']  ]); ?>" class="btn btn-primary">View Details</a>
                        <a href="<?php	echo $this->Url->build([  "controller" => "Post", "action" => "post", 'id' => $data['id']  ]); ?>" class="btn btn-primary">View Page</a>

        </div>
      </article><!-- .end Card -->
    </div>

   <?php endforeach; ?>

  </div><!-- .end Second row -->
</div>







        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->

      <!-- End of Footer -->

<script>
$(document).ready( function () {
    $('#table_id').DataTable();
} );

function validate(){
var er=true;
    var title = $('#title').val();
     var metades = $('#metades').val();
     var file = $('#customFile').val();
      var content = $('#summernote').val();
    if(title=='' || metades=='' || file=='' || content=='' ){
    alert("All Fields are Required");
return false;
er=false;
    }

    if(content.length<100){


    alert("content should be greater than 100 characters");
    return false;
    er=false;
    }
    if(er==true){
    return true;
    }
    else {

    return false;
     }
}

 $("#post").submit(function(e){

                if(validate()){
               return ;
                }
                 e.preventDefault();
            });




 $( document ).ready(function() {
      $('#summernote').summernote({height: 300});
    });

</script>