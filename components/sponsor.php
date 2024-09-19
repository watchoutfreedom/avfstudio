<div class="sponsor">
<?php 	
  $args = array(
    'post_type' => 'sponsor',
    'meta_value'    => 'Associate',
    'posts_per_page' => -1
  );

  $sponsors = get_posts($args);
  if ($sponsors): 
    foreach($sponsors as $sponsor):  setup_postdata( $sponsor );?>
      <div class="sponsor__cat"><?php echo get_field("category",$sponsor->ID) ?> sponsors</div>
      <div class="sponsor__card <?php echo get_field("category",$sponsor->ID) ?>">
        <div class="sponsor__content">
          <a href="<?php echo get_field("url",$sponsor->ID) ?>" class="sponsor__link" target="_blank">
            <img class="sponsor__img" src="<?php echo get_the_post_thumbnail_url($sponsor->ID) ?>" alt="<?php echo $sponsor->post_title ?>" >
            <h5 class="sponsor__title">
              <?php echo $sponsor->post_title ?>
            </h5>
            </a>
            </div>
      </div>
    <?php endforeach;
    wp_reset_postdata();
  endif;?>
</div> <!-- sponsor -->

<div class="sponsor">
<?php 	
  $args = array(
    'post_type' => 'sponsor',
    'meta_value'    => 'Premier',
    'posts_per_page' => -1
  );

  $sponsors = get_posts($args);
  if ($sponsors): 
    foreach($sponsors as $sponsor):  setup_postdata( $sponsor );?>
      <div class="sponsor__cat"><?php echo get_field("category",$sponsor->ID) ?> sponsors</div>
      <div class="sponsor__card <?php echo get_field("category",$sponsor->ID) ?>">        <div class="sponsor__content">
          <a href="<?php echo get_field("url",$sponsor->ID) ?>" class="sponsor__link" target="_blank">
            <img class="sponsor__img" src="<?php echo get_the_post_thumbnail_url($sponsor->ID) ?>" alt="<?php echo $sponsor->post_title ?>" >
            <h5 class="sponsor__title">
              <?php echo $sponsor->post_title ?>
            </h5>
            </a>
            </div>
      </div>
    <?php endforeach;
    wp_reset_postdata();
  endif;?>
</div> <!-- sponsor -->



<div class="sponsor">
<?php 	
  $args = array(
    'post_type' => 'sponsor',
    'meta_value'    => 'Platinum',
    'posts_per_page' => -1
  );

  $sponsors = get_posts($args);
  if ($sponsors): 
    foreach($sponsors as $sponsor):  setup_postdata( $sponsor );?>
      <div class="sponsor__cat"><?php echo get_field("category",$sponsor->ID) ?> sponsors</div>
      <div class="sponsor__card <?php echo get_field("category",$sponsor->ID) ?>">        <div class="sponsor__content">
          <a href="<?php echo get_field("url",$sponsor->ID) ?>" class="sponsor__link" target="_blank">
            <img class="sponsor__img" src="<?php echo get_the_post_thumbnail_url($sponsor->ID) ?>" alt="<?php echo $sponsor->post_title ?>" >
            <h5 class="sponsor__title">
              <?php echo $sponsor->post_title ?>
            </h5>
            </a>
            </div>
      </div>
    <?php endforeach;
    wp_reset_postdata();
  endif;?>
</div> <!-- sponsor -->



<div class="sponsor">
<?php 	
  $args = array(
    'post_type' => 'sponsor',
    'meta_value'    => 'Gold',
    'posts_per_page' => -1
  );

  $sponsors = get_posts($args);
  if ($sponsors): 
    foreach($sponsors as $sponsor):  setup_postdata( $sponsor );?>
      <div class="sponsor__cat"><?php echo get_field("category",$sponsor->ID) ?> sponsors</div>
      <div class="sponsor__card <?php echo get_field("category",$sponsor->ID) ?>">        <div class="sponsor__content">
          <a href="<?php echo get_field("url",$sponsor->ID) ?>" class="sponsor__link" target="_blank">
            <img class="sponsor__img" src="<?php echo get_the_post_thumbnail_url($sponsor->ID) ?>" alt="<?php echo $sponsor->post_title ?>" >
            <h5 class="sponsor__title">
              <?php echo $sponsor->post_title ?>
            </h5>
            </a>
            </div>
      </div>
    <?php endforeach;
    wp_reset_postdata();
  endif;?>
</div> <!-- sponsor -->



<div class="sponsor">
<?php 	
  $args = array(
    'post_type' => 'sponsor',
    'meta_value'    => 'Silver',
    'posts_per_page' => -1
  );

  $sponsors = get_posts($args);
  if ($sponsors): 
    foreach($sponsors as $sponsor):  setup_postdata( $sponsor );?>
      <div class="sponsor__cat"><?php echo get_field("category",$sponsor->ID) ?> sponsors</div>
      <div class="sponsor__card <?php echo get_field("category",$sponsor->ID) ?>">        <div class="sponsor__content">
          <a href="<?php echo get_field("url",$sponsor->ID) ?>" class="sponsor__link" target="_blank">
            <img class="sponsor__img" src="<?php echo get_the_post_thumbnail_url($sponsor->ID) ?>" alt="<?php echo $sponsor->post_title ?>" >
            <h5 class="sponsor__title">
              <?php echo $sponsor->post_title ?>
            </h5>
            </a>
            </div>
      </div>
    <?php endforeach;
    wp_reset_postdata();
  endif;?>
</div> <!-- sponsor -->