
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
<h3 align="center" > Post Your Idea </h3>
<form id="post" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="exampleInputEmail1">Title</label>
    <input type="text" class="form-control" name="title" id="title" aria-describedby="emailHelp" placeholder="Head Line" required>
    <small id="emailHelp" class="form-text text-muted">Use Maximum Keywords.</small>
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Select Category</label>
   <select name="category" class="custom-select" required>
   <option>Select Category</option>
   <?php foreach($cat as $c){ ?>

      <option selected value="<?= $c->id; ?>"><?= $c->name; ?> </option>


     <?php } ?>
    </select>
</div>

  <div class="form-group">
    <label for="exampleInputPassword1">Meta Description</label>
    <input type="text" class="form-control" name="metades" id="metades" placeholder="Short Keyword descriptions" required>
  </div>

  <div class="custom-file">

    <input type="file" name="file" class="custom-file-input" id="customFile" required>
    <label class="custom-file-label" for="customFile">Choose Thumbnail</label>

  </div>


  <div class="form-group">
    <label for="exampleInputEmail1">Content</label>
  <textarea name="content" id="summernote" cols="30" rows="10" required>
      <h3 style="text-align: center; "><a href="http://www.jquery2dotnet.com" style="background-color: rgb(255, 255, 255); line-height: 1.428571429;">jquery2dotnet</a></h3>
   </textarea>
     <small id="emailHelp" class="form-text text-muted">Your Idea Should be greater than 100 characters.</small>
</div>



  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->

      <!-- End of Footer -->

<script>


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