
<style>
.im{

width: 100px;
    height: auto;
    border-radius: 100%;

}

</style>


<div class="container">
    <h1>Edit Profile</h1>
  	<hr>
	<div class="row">
      <!-- left column -->




      <!-- edit form column -->
      <div class="col-md-9 personal-info">

        <h3>Personal info</h3>

        <form method="Post" enctype="multipart/form-data" class="form-horizontal" role="form">
         <div class="form-group">
                <div class="text-center">
                  <img class="im" src="<?php echo $baseurl; ?><?php if(! $image=='' && ! $image=='defaultprofile.jpg'){ echo "webroot/upload/".$userid."/profile/".$image; }else{ echo "webroot/upload/default.jpg"; } ?> " alt="avatar">
                  <h6>Upload photo...</h6>

                  <input type="file" name="file" class="form-control">
                </div>
              </div>



          <div class="form-group">
            <label class="col-lg-3 control-label">First name:</label>
            <div class="col-lg-8">
              <input class="form-control" type="text" value="<?= $name; ?>" readonly>
            </div>
          </div>


  <div class="form-group">
            <label class="col-lg-3 control-label">Phone:</label>
            <div class="col-lg-8">
              <input class="form-control" type="Numbers" value="<?= $phone; ?>" name="phone">
            </div>
          </div>


          <div class="form-group">
            <label class="col-lg-3 control-label">Company:</label>
            <div class="col-lg-8">
              <input class="form-control" name="company" type="text" value="<?= $company; ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-lg-3 control-label">Email:</label>
            <div class="col-lg-8">
              <input class="form-control" type="text" value="<?= $email; ?>" readonly>
            </div>
          </div>

          <div class="form-group">
            <label class="col-lg-3 control-label">Gender:</label>
            <div class="col-lg-8">
              <div class="ui-select">
                <select id="user_time_zone" name="gender" class="form-control">
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                      <option value="Others">Others</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Hobbies:</label>
            <div class="col-md-8">
              <input class="form-control" name="hobby" type="text" value="<?= $hobby; ?>" placeholder="Cricket,Pubg,Reading">
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-8">
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <span></span>
              <input type="reset" class="btn btn-default" value="Cancel">
            </div>
          </div>
        </form>

      </div>
  </div>
</div>
<hr>