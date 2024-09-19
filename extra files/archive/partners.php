
	<div class="background-image--middle3 parallax--sponsors parallax position-relative" >
		<div class="container-lg">
			<div class="row border-top">
				<div class="col-md-12">
					<h2>Partners '24</h2>
				</div>
			</div>
		</div>
	</div>
	<div class="container-lg sponsors hotizontal-slider__section hotizontal-slider__container ponentes__section">
		<div class="hotizontal-slider__container">
			<div id="partners" class="wrapper6">
				<?php 
					
					$args = array(
						'post_type' => 'partners',
						'posts_per_page' => -1
					);
					$sponsors = get_posts($args);
					if ($sponsors): 	
						foreach($sponsors as $sponsor):  setup_postdata( $sponsor );?>
					<div class="slide box6">
						<div class="single mb-4">
							<img class="img-fluid img-thumbnail wob-thumbnail" src="<?php echo get_the_post_thumbnail_url($sponsor->ID) ?>" alt="" class="img-fluid">
							<h5>
								<a href="<?php echo get_field("url",$sponsor->ID) ?>" class="text-decoration-none text-dark" target="_blank">
								<h5>
									<?php echo $sponsor->post_title ?>
								</h5>
								</a>
							</h5>
						</div>
					</div>
				<?php endforeach;
					wp_reset_postdata();
				endif;?>
			</div>
			<div class="col-md-12 mx-auto my-auto px-5  py-5">
				<a href="mailto:sponsor@wobitcoin.org" target="_blank" class="button my-button button__over-orange">Unirme como Partner'24</a class="button">
			</div>
		</div>
	</div>
	*/?>