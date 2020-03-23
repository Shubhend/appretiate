
<?= $this->Html->css("/css/sb-admin-2.css"); ?>

<?= $this->Html->css("post.css"); ?>

<?= $this->Html->css("post_responsive.css"); ?>
	<div class="home">
		<div class="home_background parallax-window" data-parallax="scroll" data-image-src="<?=  $baseurl; ?>webroot/img/post.jpg" data-speed="0.8"></div>
		<div class="home_content">
			<div class="post_category trans_200"><a href="category.html" class="trans_200">sport</a></div>
			<div class="post_title">How Did van Gogh’s Turbulent Mind Depict One of the Most Complex Concepts in Physics?</div>
		</div>
	</div>



<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->

<?= $this->Flash->render(); ?>
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                  <form class="user" method="post">

                    <?php if($forform==1){ ?>

                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="New Password">
                    </div>

                    <button type="submit" class="btn btn-primary btn-user btn-block">
Change Password
                    </button>
                    <?php }else{ ?>
                     <div class="form-group">
                      <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                    </div>

                    <button type="submit" class="btn btn-primary btn-user btn-block">
                       Send Verification Link
                    </button>

<?php } ?>





                  </form>
                  <hr>

                  <div class="text-center">
                    <a class="small" href="<?php  echo $this->Url->build(array('controller' => 'Main', 'action' => 'register'), true); ?>">Create an Account!</a>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

