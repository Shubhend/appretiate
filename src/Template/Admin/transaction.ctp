
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
            <h1 class="h3 mb-0 text-gray-800">Request for Money Transfer</h1>
             </div>

<form class="form-horizontal col-md-9 personal-info" method="post" action="">

  <div class="form-group">
            <label class="col-md-3 control-label">Contact No:</label>
            <div class="col-md-8">
              <input class="form-control" name="phone" type="Number" value="" placeholder="Contact No.">
            </div>
            <br/>

          </div>

  <div class="form-group">
            <label class="col-md-3 control-label">Price</label>
            <div class="col-md-8">
              <input class="form-control" name="money" type="text" value="" placeholder="Price">
            </div>
            <br/>

          </div>
   <small>we will contact you for your payment</small>
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-8">
              <input type="submit" class="btn btn-primary" value="Save Changes">

            </div>
          </div>
             </form>


<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Status</th>
            <th>Transaction ID</th>
             <th>Cost</th>
              <th>Date</th>
        </tr>
    </thead>
    <tbody>

<?php

foreach($data as $data):
if($data['status']==1){
$type="Complete";
}else{
$type="Pending";
}
?>
        <tr>
            <td><?php echo $type ?></td>
            <td><?php echo $data['transaction_id']; ?></td>
            <td>    <?php echo $data['cost']; ?>      </td>
<td><?php echo $data['date']; ?></td>

        </tr>
       <?php endforeach; ?>


    </tbody>
</table>



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