<div class="sponsor">
<?php 	
  $args = array(
    'post_type' => 'media',
    'posts_per_page' => -1
  );

  $medias = get_posts($args);
  if ($medias): 
    foreach($medias as $media):  setup_postdata( $media );?>
      <div class="sponsor__card">
        <div class="sponsor__content">
          <a href="<?php echo get_field("url",$media->ID) ?>" class="sponsor__link" target="_blank">
            <img class="sponsor__img" src="<?php echo get_the_post_thumbnail_url($media->ID) ?>" alt="<?php echo $media->post_title ?>" >
            <h5 class="sponsor__title">
              <?php echo $media->post_title ?>
            </h5>
            </a>
            </div>
      </div>
    <?php endforeach;
    wp_reset_postdata();
  endif;?>
</div> <!-- sponsor -->
