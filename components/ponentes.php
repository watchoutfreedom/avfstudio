<div class="ponentes">
    <?php 
    $args = array(
      'post_type' => 'ponente',
      'posts_per_page' => -1
    );
    $ponentes = get_posts($args);
    if ($ponentes): 
      foreach($ponentes as $ponente):  setup_postdata( $ponente );?>
      <div class="ponentes__card">
        <a class="ponentes__link" href="<?php echo get_field("url",$ponente->ID) ?>" class="text-decoration-none 	text-dark" target="_blank"></a>            
        <img class="ponentes__img" src="<?php echo get_the_post_thumbnail_url($ponente->ID) ?>" alt="<?php echo $ponente->post_title ?>" >
        <div class="ponentes__content">
            <div class="ponentes__title">
              <h5>
                <?php echo $ponente->post_title ?>
              </h5>
              <p>
                <?php echo $ponente->post_excerpt ?>
              </p>
            </div>
        </div>
    </div>
      <?php endforeach;
      wp_reset_postdata();
    endif;?>
</div> <!-- ponentes -->