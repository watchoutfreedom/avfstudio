<?php get_header(); ?>    

<!-- Back Button -->
<div class="back-button-container" style="position: fixed; top: 20px; left: 20px;">
    <button onclick="window.history.back();" style="background-color: #333; color: #fff; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
        ← Back
    </button>
</div>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <article class="single">
    <div class="single__meta">
      <h1 class="single__title"><?php the_title(); ?></h1>
      <div class="single__info">
        <span><?php the_field('field_66a224bc1299d'); ?></span> ·
      </div>
    </div>
    <div class="single__description">
      <?php the_field('field_66a224e77d91a'); ?>
    </div>

    <div class="single__content main">
      <?php the_content("Sigue leyendo"); ?>     
    </div>
  
<?php endwhile; else: ?> 
	<?php include (TEMPLATEPATH . '/404.php'); ?>		
<?php endif; ?>
</article>

<?php get_footer(); ?>
