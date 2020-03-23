
<?= $this->Html->css("post.css"); ?>

<?= $this->Html->css("post_responsive.css"); ?>


<style>

.btn-liquid {
	display: inline-block;
	position: relative;
	width: 100%;
	height: 60px;

	border-radius: 27px;

	color: #fff;
	font: 700 14px/60px "Droid Sans", sans-serif;
	letter-spacing: 0.05em;
	text-align: center;
	text-decoration: none;
	text-transform: uppercase;
	color:white;
	cursor:pointer;
}

.btn-liquid .inner {
	position: relative;

	z-index: 2;
}

.btn-liquid canvas {
	position: absolute;
	top: -50px;
	right: -50px;
	bottom: -50px;
	left: -50px;

	z-index: 1;
}

</style>

<script>
$(function() {
	// Vars
	var pointsA = [],
		pointsB = [],
		$canvas = null,
		canvas = null,
		context = null,
		vars = null,
		points = 8,
		viscosity = 20,
		mouseDist = 70,
		damping = 0.05,
		showIndicators = false;
		mouseX = 0,
		mouseY = 0,
		relMouseX = 0,
		relMouseY = 0,
		mouseLastX = 0,
		mouseLastY = 0,
		mouseDirectionX = 0,
		mouseDirectionY = 0,
		mouseSpeedX = 0,
		mouseSpeedY = 0;

	/**
	 * Get mouse direction
	 */
	function mouseDirection(e) {
		if (mouseX < e.pageX)
			mouseDirectionX = 1;
		else if (mouseX > e.pageX)
			mouseDirectionX = -1;
		else
			mouseDirectionX = 0;

		if (mouseY < e.pageY)
			mouseDirectionY = 1;
		else if (mouseY > e.pageY)
			mouseDirectionY = -1;
		else
			mouseDirectionY = 0;

		mouseX = e.pageX;
		mouseY = e.pageY;

		relMouseX = (mouseX - $canvas.offset().left);
		relMouseY = (mouseY - $canvas.offset().top);
	}
	$(document).on('mousemove', mouseDirection);

	/**
	 * Get mouse speed
	 */
	function mouseSpeed() {
		mouseSpeedX = mouseX - mouseLastX;
		mouseSpeedY = mouseY - mouseLastY;

		mouseLastX = mouseX;
		mouseLastY = mouseY;

		setTimeout(mouseSpeed, 50);
	}
	mouseSpeed();

	/**
	 * Init button
	 */
	function initButton() {
		// Get button
		var button = $('.btn-liquid');
		var buttonWidth = button.width();
		var buttonHeight = button.height();

		// Create canvas
		$canvas = $('<canvas></canvas>');
		button.append($canvas);

		canvas = $canvas.get(0);
		canvas.width = buttonWidth+100;
		canvas.height = buttonHeight+50;
		context = canvas.getContext('2d');

		// Add points

		var x = buttonHeight/2;
		for(var j = 1; j < points; j++) {
			addPoints((x+((buttonWidth-buttonHeight)/points)*j), 0);
		}
		addPoints(buttonWidth-buttonHeight/5, 0);
		addPoints(buttonWidth+buttonHeight/10, buttonHeight/2);
		addPoints(buttonWidth-buttonHeight/5, buttonHeight);
		for(var j = points-1; j > 0; j--) {
			addPoints((x+((buttonWidth-buttonHeight)/points)*j), buttonHeight);
		}
		addPoints(buttonHeight/5, buttonHeight);

		addPoints(-buttonHeight/10, buttonHeight/2);
		addPoints(buttonHeight/5, 0);
		// addPoints(x, 0);
		// addPoints(0, buttonHeight/2);

		// addPoints(0, buttonHeight/2);
		// addPoints(buttonHeight/4, 0);

		// Start render
		renderCanvas();
	}

	/**
	 * Add points
	 */
	function addPoints(x, y) {
		pointsA.push(new Point(x, y, 1));
		pointsB.push(new Point(x, y, 2));
	}

	/**
	 * Point
	 */
	function Point(x, y, level) {
	  this.x = this.ix = 50+x;
	  this.y = this.iy = 50+y;
	  this.vx = 0;
	  this.vy = 0;
	  this.cx1 = 0;
	  this.cy1 = 0;
	  this.cx2 = 0;
	  this.cy2 = 0;
	  this.level = level;
	}

	Point.prototype.move = function() {
		this.vx += (this.ix - this.x) / (viscosity*this.level);
		this.vy += (this.iy - this.y) / (viscosity*this.level);

		var dx = this.ix - relMouseX,
			dy = this.iy - relMouseY;
		var relDist = (1-Math.sqrt((dx * dx) + (dy * dy))/mouseDist);

		// Move x
		if ((mouseDirectionX > 0 && relMouseX > this.x) || (mouseDirectionX < 0 && relMouseX < this.x)) {
			if (relDist > 0 && relDist < 1) {
				this.vx = (mouseSpeedX / 4) * relDist;
			}
		}
		this.vx *= (1 - damping);
		this.x += this.vx;

		// Move y
		if ((mouseDirectionY > 0 && relMouseY > this.y) || (mouseDirectionY < 0 && relMouseY < this.y)) {
			if (relDist > 0 && relDist < 1) {
				this.vy = (mouseSpeedY / 4) * relDist;
			}
		}
		this.vy *= (1 - damping);
		this.y += this.vy;
	};


	/**
	 * Render canvas
	 */
	function renderCanvas() {
		// rAF
		rafID = requestAnimationFrame(renderCanvas);

		// Clear scene
		context.clearRect(0, 0, $canvas.width(), $canvas.height());
		context.fillStyle = '#fff';
		context.fillRect(0, 0, $canvas.width(), $canvas.height());

		// Move points
		for (var i = 0; i <= pointsA.length - 1; i++) {
			pointsA[i].move();
			pointsB[i].move();
		}

		// Create dynamic gradient
		var gradientX = Math.min(Math.max(mouseX - $canvas.offset().left, 0), $canvas.width());
		var gradientY = Math.min(Math.max(mouseY - $canvas.offset().top, 0), $canvas.height());
		var distance = Math.sqrt(Math.pow(gradientX - $canvas.width()/2, 2) + Math.pow(gradientY - $canvas.height()/2, 2)) / Math.sqrt(Math.pow($canvas.width()/2, 2) + Math.pow($canvas.height()/2, 2));

		var gradient = context.createRadialGradient(gradientX, gradientY, 300+(300*distance), gradientX, gradientY, 0);
		gradient.addColorStop(0, '#102ce5');
		gradient.addColorStop(1, '#E406D6');

		// Draw shapes
		var groups = [pointsA, pointsB]

		for (var j = 0; j <= 1; j++) {
			var points = groups[j];

			if (j == 0) {
				// Background style
				context.fillStyle = '#1CE2D8';
			} else {
				// Foreground style
				context.fillStyle = gradient;
			}

			context.beginPath();
			context.moveTo(points[0].x, points[0].y);

			for (var i = 0; i < points.length; i++) {
				var p = points[i];
				var nextP = points[i + 1];
				var val = 30*0.552284749831;

				if (nextP != undefined) {
					// if (nextP.ix > p.ix && nextP.iy < p.iy) {
					// 	p.cx1 = p.x;
					// 	p.cy1 = p.y-val;
					// 	p.cx2 = nextP.x-val;
					// 	p.cy2 = nextP.y;
					// } else if (nextP.ix > p.ix && nextP.iy > p.iy) {
					// 	p.cx1 = p.x+val;
					// 	p.cy1 = p.y;
					// 	p.cx2 = nextP.x;
					// 	p.cy2 = nextP.y-val;
					// }  else if (nextP.ix < p.ix && nextP.iy > p.iy) {
					// 	p.cx1 = p.x;
					// 	p.cy1 = p.y+val;
					// 	p.cx2 = nextP.x+val;
					// 	p.cy2 = nextP.y;
					// } else if (nextP.ix < p.ix && nextP.iy < p.iy) {
					// 	p.cx1 = p.x-val;
					// 	p.cy1 = p.y;
					// 	p.cx2 = nextP.x;
					// 	p.cy2 = nextP.y+val;
					// } else {

						p.cx1 = (p.x+nextP.x)/2;
						p.cy1 = (p.y+nextP.y)/2;
						p.cx2 = (p.x+nextP.x)/2;
						p.cy2 = (p.y+nextP.y)/2;

						context.bezierCurveTo(p.x, p.y, p.cx1, p.cy1, p.cx1, p.cy1);
					// 	continue;
					// }

					// context.bezierCurveTo(p.cx1, p.cy1, p.cx2, p.cy2, nextP.x, nextP.y);
				} else {
nextP = points[0];
						p.cx1 = (p.x+nextP.x)/2;
						p.cy1 = (p.y+nextP.y)/2;

						context.bezierCurveTo(p.x, p.y, p.cx1, p.cy1, p.cx1, p.cy1);
				}
			}

			// context.closePath();
			context.fill();
		}

		if (showIndicators) {
			// Draw points
			context.fillStyle = '#000';
			context.beginPath();
			for (var i = 0; i < pointsA.length; i++) {
				var p = pointsA[i];

				context.rect(p.x - 1, p.y - 1, 2, 2);
			}
			context.fill();

			// Draw controls
			context.fillStyle = '#f00';
			context.beginPath();
			for (var i = 0; i < pointsA.length; i++) {
				var p = pointsA[i];

				context.rect(p.cx1 - 1, p.cy1 - 1, 2, 2);
				context.rect(p.cx2 - 1, p.cy2 - 1, 2, 2);
			}
			context.fill();
		}
	}

	// Init
	initButton();
});

</script>
	<!-- Home -->
<?php foreach ($data as $d){ ?>
	<div class="home">
		<div class="home_background parallax-window" data-parallax="scroll" data-image-src="<?=  $baseurl; ?>webroot/img/post.jpg" data-speed="0.8"></div>
		<div class="home_content">
			<div class="post_category trans_200"><a href="<?php	echo $this->Url->build([  "controller" => "Post",  "action" => "category", "id"=>  $d['cid'], ]); ?>" class="trans_200"><?= $d['category']; ?></a></div>
			<div class="post_title"><?= substr($d['title'],0,30); ?></div>
		</div>
	</div>
<?php } ?>
	<!-- Page Content -->

	<div class="page_content">
		<div class="container">
			<div class="row row-lg-eq-height">

				<!-- Post Content -->

				<div class="col-lg-9">
					<div class="post_content">


<?php foreach($data as $d){  ?>
						<!-- Top Panel -->
						<div class="post_panel post_panel_top d-flex flex-row align-items-center justify-content-start">
							<div class="author_image"><div><img src="<?=  $d['userimage']; ?>" alt=""></div></div>
							<div class="post_meta"><a href="#"><?= $d['username']; ?><a><span>Views: <?= $views; ?></span></div>
							<div class="post_share ml-sm-auto">
								<span>share</span>
								<ul class="post_share_list">
									<li class="post_share_item"><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
									<li class="post_share_item"><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
									<li class="post_share_item"><a href="#"><i class="fa fa-google" aria-hidden="true"></i></a></li>
								</ul>
							</div>
						</div>

						<!-- Post Body -->


						<div class="post_body">
								<figure>
								<img src="<?=  $d['image']; ?>" alt="">
								<figcaption><?= substr($d['title'],0,10); ?></figcaption>
							</figure>
							<div class="post_p"><?= html_entity_decode($d['content']); ?></div>


<br/>
<br/>
<br/>
<a id="appritiate" onclick="appri(<?php echo $d['id']; ?>);" class="btn-liquid">
		<span class="inner"><img style="width:76px;margin-left:-180px;" src="https://sc.mogicons.com/l/clapping-emoticon-272.png"> Appriciate  <?= $totalappritiate; ?></span>
</a>
<br/>
<div id="show" style="color:green;font-size:15px;"> </div>


						</div>





						<!-- Bottom Panel -->
						<div class="post_panel bottom_panel d-flex flex-row align-items-center justify-content-start">
							<div class="author_image"><div><img src="<?= $d['userimage']; ?>" alt=""></div></div>
							<div class="post_meta"><a href="#">Katy Liu</a><span>Sep 29, 2017 at 9:48 am</span></div>
							<div class="post_share ml-sm-auto">
								<span>share</span>
								<ul class="post_share_list">
									<li class="post_share_item"><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
									<li class="post_share_item"><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
									<li class="post_share_item"><a href="#"><i class="fa fa-google" aria-hidden="true"></i></a></li>
								</ul>
							</div>
						</div>
<?php } ?>
						<!-- Similar Posts -->
						<div class="similar_posts">
							<div class="grid clearfix">
<?php foreach($relatedpost as $r){ ?>
								<!-- Small Card With Image -->
								<div class="card card_small_with_image grid-item">
									<img class="card-img-top" src="<?=  $r['image']; ?>" alt="https://unsplash.com/@jakobowens1">
									<div class="card-body">
										<div class="card-title card-title-small"><a href="<?php	echo $this->Url->build([  "controller" => "Post",  "action" => "post", "id"=>  $r['id'], ]); ?>"><?= substr($r['title'],0,20); ?></a></div>
										<small class="post_meta"><a href="#"><?= $r['username']; ?></a><span>Views <?= $views; ?></span></small>
									</div>
								</div>

						<?php } ?>

							</div>

							<!--
							<div class="post_comment">
								<div class="post_comment_title">Post Comment</div>
								<div class="row">
									<div class="col-xl-8">
										<div class="post_comment_form_container">
											<form action="#">
												<input type="text" class="comment_input comment_input_name" placeholder="Your Name" required="required">
												<input type="email" class="comment_input comment_input_email" placeholder="Your Email" required="required">
												<textarea class="comment_text" placeholder="Your Comment" required="required"></textarea>
												<button type="submit" class="comment_button">Post Comment</button>
											</form>
										</div>
									</div>
								</div>
							</div>


							<div class="comments">
								<div class="comments_title">Comments <span>(12)</span></div>
								<div class="row">
									<div class="col-xl-8">
										<div class="comments_container">
											<ul class="comment_list">
												<li class="comment">
													<div class="comment_body">
														<div class="comment_panel d-flex flex-row align-items-center justify-content-start">
															<div class="comment_author_image"><div><img src="<?=  $baseurl; ?>webroot/img/comment_author_1.jpg" alt=""></div></div>
															<small class="post_meta"><a href="#">Katy Liu</a><span>Sep 29, 2017 at 9:48 am</span></small>
															<button type="button" class="reply_button ml-auto">Reply</button>
														</div>
														<div class="comment_content">
															<p>Pick the yellow peach that looks like a sunset with its red, orange, and pink coat skin, peel it off with your teeth. Sink them into unripened.</p>
														</div>
													</div>


													<ul class="comment_list">
														<li class="comment">
															<div class="comment_body">
																<div class="comment_panel d-flex flex-row align-items-center justify-content-start">
																	<div class="comment_author_image"><div><img src="<?=  $baseurl; ?>webroot/img/comment_author_2.jpg" alt=""></div></div>
																	<small class="post_meta"><a href="#">Katy Liu</a><span>Sep 29, 2017 at 9:48 am</span></small>
																	<button type="button" class="reply_button ml-auto">Reply</button>
																</div>
																<div class="comment_content">
																	<p>Nulla facilisi. Aenean porttitor quis tortor id tempus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Vivamus molestie varius tincidunt. Vestibulum congue sed libero ac sodales.</p>
																</div>
															</div>


															<ul class="comment_list">

															</ul>
														</li>
													</ul>
												</li>
												<li class="comment">
													<div class="comment_body">
														<div class="comment_panel d-flex flex-row align-items-center justify-content-start">
															<div class="comment_author_image"><div><img src="<?=  $baseurl; ?>webroot/img/comment_author_1.jpg" alt=""></div></div>
															<small class="post_meta"><a href="#">Katy Liu</a><span>Sep 29, 2017 at 9:48 am</span></small>
															<button type="button" class="reply_button ml-auto">Reply</button>
														</div>
														<div class="comment_content">
															<p>Pick the yellow peach that looks like a sunset with its red, orange, and pink coat skin, peel it off with your teeth. Sink them into unripened.</p>
														</div>
													</div>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>-->
						</div>
					</div>
					<br/>
				<!--	<div class="load_more">
						<div id="load_more" class="load_more_button text-center trans_200">Load More</div>
					</div>-->
				</div>


				<!-- Sidebar -->

				<div class="col-lg-3">
					<div class="sidebar">
						<div class="sidebar_background"></div>

						<!-- Top Stories -->
<div class="sidebar_title_container">
								<div class="sidebar_title">Top Stories</div>
								<!--<div class="sidebar_slider_nav">
									<div class="custom_nav_container sidebar_slider_nav_container">
										<div class="custom_prev custom_prev_top">
											<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
												 width="7px" height="12px" viewBox="0 0 7 12" enable-background="new 0 0 7 12" xml:space="preserve">
												<polyline fill="#bebebe" points="0,5.61 5.609,0 7,0 7,1.438 2.438,6 7,10.563 7,12 5.609,12 -0.002,6.39 "/>
											</svg>
										</div>
								        <ul id="custom_dots" class="custom_dots custom_dots_top">
											<li class="custom_dot custom_dot_top active"><span></span></li>
											<li class="custom_dot custom_dot_top"><span></span></li>
											<li class="custom_dot custom_dot_top"><span></span></li>
										</ul>
										<div class="custom_next custom_next_top">
											<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
												 width="7px" height="12px" viewBox="0 0 7 12" enable-background="new 0 0 7 12" xml:space="preserve">
												<polyline fill="#bebebe" points="6.998,6.39 1.389,12 -0.002,12 -0.002,10.562 4.561,6 -0.002,1.438 -0.002,0 1.389,0 7,5.61 "/>
											</svg>
										</div>
									</div>
								</div> -->
							</div>
	<div class="sidebar_section">
							<div class="advertising">
								<div class="advertising_background" style="background-image:url(<?=  $baseurl; ?>webroot/img/post_17.jpg)"></div>
								<div class="advertising_content d-flex flex-column align-items-start justify-content-end">
									<div class="advertising_perc">-15%</div>
									<div class="advertising_link"><a href="#">How Did van Gogh’s Turbulent Mind</a></div>
								</div>
							</div>
						</div>
							<div class="sidebar_section_content">

								<!-- Top Stories Slider -->
								<div class="sidebar_slider_container">
									<div class="owl-carousel owl-theme sidebar_slider_top">

										<!-- Top Stories Slider Item -->







										<!-- Top Stories Slider Item -->
										<div class="owl-item">

											<!-- Sidebar Post -->
											<div class="side_post">
												<a href="post.html">
													<div class="d-flex flex-row align-items-xl-center align-items-start justify-content-start">
														<div class="side_post_image"><div><img src="<?=  $baseurl; ?>webroot/img/top_1.jpg" alt=""></div></div>
														<div class="side_post_content">
															<div class="side_post_title">How Did van Gogh’s Turbulent Mind</div>
															<small class="post_meta">Katy Liu<span>Sep 29</span></small>
														</div>
													</div>
												</a>
											</div>

											<!-- Sidebar Post -->
											<div class="side_post">
												<a href="post.html">
													<div class="d-flex flex-row align-items-xl-center align-items-start justify-content-start">
														<div class="side_post_image"><div><img src="<?=  $baseurl; ?>webroot/img/top_3.jpg" alt=""></div></div>
														<div class="side_post_content">
															<div class="side_post_title">How Did van Gogh’s Turbulent Mind</div>
															<small class="post_meta">Katy Liu<span>Sep 29</span></small>
														</div>
													</div>
												</a>
											</div>

											<!-- Sidebar Post -->
											<div class="side_post">
												<a href="post.html">
													<div class="d-flex flex-row align-items-xl-center align-items-start justify-content-start">
														<div class="side_post_image"><div><img src="<?=  $baseurl; ?>webroot/img/top_3.jpg" alt=""></div></div>
														<div class="side_post_content">
															<div class="side_post_title">How Did van Gogh’s Turbulent Mind</div>
															<small class="post_meta">Katy Liu<span>Sep 29</span></small>
														</div>
													</div>
												</a>
											</div>

											<!-- Sidebar Post -->
											<div class="side_post">
												<a href="post.html">
													<div class="d-flex flex-row align-items-xl-center align-items-start justify-content-start">
														<div class="side_post_image"><div><img src="<?=  $baseurl; ?>webroot/img/top_4.jpg" alt=""></div></div>
														<div class="side_post_content">
															<div class="side_post_title">How Did van Gogh’s Turbulent Mind</div>
															<small class="post_meta">Katy Liu<span>Sep 29</span></small>
														</div>
													</div>
												</a>
											</div>

										</div>

									</div>
								</div>
							</div>
						</div>

						<!-- Advertising -->





						<!-- Advertising 2 -->

						<div class="sidebar_section">
							<div class="advertising_2">
								<div class="advertising_background" style="background-image:url(<?=  $baseurl; ?>webroot/img/post_18.jpg)"></div>
								<div class="advertising_2_content d-flex flex-column align-items-center justify-content-center">
									<div class="advertising_2_link"><a href="#">Turbulent <span>Mind</span></a></div>
								</div>
							</div>
						</div>

						<!-- Future Events -->



					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Footer -->

<script>

function appri(id){

 $.ajax({
                             type:'post',
                             url: '<?php	echo $this->Url->build([  "controller" => "Post", "action" => "giveappri" ]); ?>',
			     data:'id='+id,
			     dataType: 'json',
			     success:function(result){
			     //alert(result['error']);

if(result['error'] == 0 || result['error']== 1){
$("#show").html(result['msg']);

}

if(result['error'] == 2){
window.location.href='http://localhost/cake/cake/login/?postid=<?php echo $this->request->query('id'); ?>';

}


					}
			});











}


</script>


