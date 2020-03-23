

<?= $this->Html->css("/css/contact.css"); ?>

<?= $this->Html->css("/css/contact_responsive.css"); ?>


	<div class="home">
		<div class="home_background parallax-window" data-parallax="scroll" data-image-src="<?=  $baseurl; ?>webroot/img/regular.jpg" data-speed="0.8"></div>
		<div class="home_content">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 offset-lg-3">
						<!-- Post Comment -->
						<div class="post_comment">
							<div class="contact_form_container">
								<form action="" method="Post">
									<input type="text" name="name" class="contact_input contact_input_name" placeholder="Your Name" required="required">
									<input type="email" name="email" class="contact_input contact_input_email" placeholder="Your Email" required="required">
									<textarea class="contact_text" name="mes" placeholder="Your Message" required="required"></textarea>
									<button type="submit" class="contact_button">Send Message</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
