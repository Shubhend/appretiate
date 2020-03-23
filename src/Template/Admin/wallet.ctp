
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
            <h1 class="h3 mb-0 text-gray-800">Wallet History</h1>
             </div>


<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Type</th>
            <th>Content</th>
             <th>Cost</th>
              <th>Date</th>
        </tr>
    </thead>
    <tbody>

<?php

foreach($data['data'] as $data):
if($data['type']==1){
$type="Credit";
}else{
$type="Debit";
}
?>
        <tr>
            <td><?php echo $type ?></td>
            <td><?php echo $data['content']; ?></td>
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