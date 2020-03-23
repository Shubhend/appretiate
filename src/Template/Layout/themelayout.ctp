<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->Html->charset(); ?>

<title><?php echo $title; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Demo project">
<meta name="viewport" content="width=device-width, initial-scale=1"><?= $this->Html->css("bootstrap4/bootstrap.min.css"); ?>
<?= $this->Html->css("https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"); ?>

<?= $this->Html->css("/css/plugins/OwlCarousel2-2.2.1/owl.carousel.css"); ?>
<?= $this->Html->css("/css/plugins/OwlCarousel2-2.2.1/owl.theme.default.css"); ?>
<?= $this->Html->css("/css/plugins/OwlCarousel2-2.2.1/animate.css"); ?>
<?= $this->Html->css("/css/plugins/jquery.mb.YTPlayer-3.1.12/jquery.mb.YTPlayer.css"); ?>

<?= $this->Html->css("sb-admin-2.min.css"); ?>
<?= $this->Html->css("main_styles.css"); ?>

<?= $this->Html->css("responsive.css"); ?>

<?= $this->Html->css("/css/bootstrap4/bootstrap.min.css"); ?>
<?= $this->Html->script("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"); ?>



<style>

.hidden{
display:none;

}



</style>

<script>
var input = document.getElementById("search");
input.addEventListener("keyup", function(event) {
  if (event.keyCode === 13) {
   event.preventDefault();
  $("#searchform").submit();
  }
});

</script>
</head>
<body>

<div class="super_container">

	<!-- Header -->

	<header class="header">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="header_content d-flex flex-row align-items-center justify-content-start">
						<div class="logo"><a href="#">avision</a></div>
						<nav class="main_nav">
							<ul>
								<li class="active"><a href="<?php	echo $this->Url->build([ "controller" => "Main", "action" => "index" ]); ?>">Home</a></li>
								<li><a href="<?php	echo $this->Url->build([  "controller" => "Main", "action" => "Contact" ]); ?>">Contact</a></li>
								<li><a href="<?php	echo $this->Url->build([  "controller" => "Main", "action" => "login"]); ?>">Login</a></li>
								<li><a href="#">Lifestyle</a></li>
								<li><a href="#">Video</a></li>
								<li><a href="contact.html">Contact</a></li>
							</ul>
						</nav>
						<div class="search_container ml-auto">
							<div class="weather">
								<div class="temperature">+10°</div>
								<img class="weather_icon" src="<?=  $baseurl; ?>webroot/img/cloud.png" alt="">
							</div>
							<form id="searchform" action="<?php	echo $this->Url->build([  "controller" => "Appreciate", "action" => "search"]); ?>" method="GET">
								<input id="search" name="search" class="header_search_input" required="required" placeholder="Type to Search...">
								<img class="header_search_icon"  src="<?=  $baseurl; ?>webroot/img/search.png" alt="">
							</form>

						</div>
						<div class="hamburger ml-auto menu_mm">
							<i class="fa fa-bars trans_200 menu_mm" aria-hidden="true"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	<!-- Menu -->

	<div class="menu d-flex flex-column align-items-end justify-content-start text-right menu_mm trans_400">
		<div class="menu_close_container"><div class="menu_close"><div></div><div></div></div></div>
		<div class="logo menu_mm"><a href="#">Avision</a></div>
		<div class="search">
			<form action="#">
				<input type="search" class="header_search_input menu_mm" required="required" placeholder="Type to Search...">
				<img class="header_search_icon menu_mm" src="<?=  $baseurl; ?>webroot/img/search_2.png" alt="">
			</form>
		</div>
		<nav class="menu_nav">
			<ul class="menu_mm">
				<li class="menu_mm"><a href="index.html">home</a></li>
				<li class="menu_mm"><a href="#">Fashion</a></li>
				<li class="menu_mm"><a href="#">Gadgets</a></li>
				<li class="menu_mm"><a href="#">Lifestyle</a></li>
				<li class="menu_mm"><a href="#">Video</a></li>
				<li class="menu_mm"><a href="contact.html">Contact</a></li>
			</ul>
		</nav>
	</div>

    <?= $this->Flash->render() ?>

        <?= $this->fetch('content') ?>

	<footer class="footer">
		<div class="container">
			<div class="row row-lg-eq-height">
				<div class="col-lg-9 order-lg-1 order-2">
					<div class="footer_content">
						<div class="footer_logo"><a href="#">avision</a></div>
						<div class="footer_social">
							<ul>
								<li class="footer_social_facebook"><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
								<li class="footer_social_twitter"><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
								<li class="footer_social_pinterest"><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
								<li class="footer_social_vimeo"><a href="#"><i class="fa fa-vimeo" aria-hidden="true"></i></a></li>
								<li class="footer_social_instagram"><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
								<li class="footer_social_google"><a href="#"><i class="fa fa-google" aria-hidden="true"></i></a></li>
							</ul>
						</div>
						<div class="copyright"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></div>
					</div>
				</div>
				<div class="col-lg-3 order-lg-2 order-1">
					<div class="subscribe">
						<div class="subscribe_background"></div>
						<div class="subscribe_content">
							<div class="subscribe_title">Subscribe</div>
							<form action="#">
								<input type="email" class="sub_input" placeholder="Your Email" required="required">
								<button class="sub_button">
									<svg version="1.1" id="link_arrow_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
										 width="19px" height="13px" viewBox="0 0 19 13" enable-background="new 0 0 19 13" xml:space="preserve">
										<polygon fill="#FFFFFF" points="12.475,0 11.061,0 17.081,6.021 0,6.021 0,7.021 17.038,7.021 11.06,13 12.474,13 18.974,6.5 "/>
									</svg>
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
</div>


<?= $this->Html->script("/js/jquery.mb.YTPlayer.js"); ?>
<?= $this->Html->script("/js/bootstrap/popper.js"); ?>
<?= $this->Html->script("/js/bootstrap/bootstrap.min.js"); ?>
<?= $this->Html->script("/js/owl.carousel.js"); ?>

<?= $this->Html->script("/js/easing.js"); ?>

<?= $this->Html->script("/js/masonry.js"); ?>
<?= $this->Html->script("/js/custom.js"); ?>

<?= $this->Html->script("/js/parallax.min.js"); ?>
<?= $this->Html->script("/js/post.js"); ?>
</body>
</html>
