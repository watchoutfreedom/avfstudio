<?php get_header(); ?>    

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <article class="taller">
    <div class="taller__meta">
      <div class="taller__meta">Taller</div>
      <h1 class="taller__title"><?php the_title(); ?></h1>
    </div>
    <div class="taller__content main">
    <?php the_content("Sigue leyendo"); ?>     
    </div>
  
<?php endwhile; else: ?> 
	<?php include (TEMPLATEPATH . '/404.php'); ?>		
<?php endif; ?>
</article>

<?php get_footer(); ?>
