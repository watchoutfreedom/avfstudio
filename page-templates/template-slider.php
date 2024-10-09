<?php
/**
 * Template Name: Slider Template
 */
get_header();
?>

<div class="horizontal-container" id="horizontal-container">
    <?php
    // Define the categories or sections
    $sections = array(
        array(
            'slug' => 'home',
            'title' => 'AVFstudio',
            'subtitle' => 'Slide > or v to see more',
        ),
        array(
            'slug' => 'art',
            'title' => 'Art',
            'subtitle' => '',
        ),
        array(
            'slug' => 'architecture',
            'title' => 'Architecture',
            'subtitle' => '',
        ),
        array(
            'slug' => 'software',
            'title' => 'Software',
            'subtitle' => '',
        ),
        array(
            'slug' => 'wofreedom',
            'title' => 'WoFreedom',
            'subtitle' => '',
        ),
        array(
            'slug' => 'production-club',
            'title' => 'Production Club',
            'subtitle' => 'Lead architect and concept artist',
        ),
        array(
            'slug' => 'illustration',
            'title' => 'Illustration',
            'subtitle' => '',
        ),
    );

    // Loop through sections to generate vertical sliders
    foreach ( $sections as $index => $section ) {
        // Fetch posts from the category
        $args = array(
            'category_name'  => $section['slug'],
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );
        $posts = new WP_Query( $args );
        ?>
        <div class="vertical-section" id="section-<?php echo esc_attr( $index + 1 ); ?>">
            <?php
            if ( $posts->have_posts() ) {
                // Output original images
                foreach ( $posts->posts as $post ) {
                    setup_postdata( $post );
                    $image_url = get_the_post_thumbnail_url( $post->ID, 'full' );
                    ?>
                    <div class="image-container">
                        <a href="#" class="image-link">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php if ( $post === reset( $posts->posts ) ) : ?>
                                <div class="title-overlay"><?php echo esc_html( $section['title'] ); ?></div>
                                <?php if ( $section['subtitle'] ) : ?>
                                    <div class="subtitle-overlay"><?php echo esc_html( $section['subtitle'] ); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="image-title"><?php the_title(); ?></div>
                        </a>
                        <div class="post-content" style="color: black;">
                            <button class="close-content" style="color: black;">Close</button>
                            <?php echo apply_filters( 'the_content', $post->post_content ); ?>
                        </div>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            }
            ?>
            <div class="contact-message">hello at avfstdio dot com</div>
        </div>
        <?php
    }
    ?>
</div>

<script>
    // JavaScript to handle click events on posts
    document.querySelectorAll('.image-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            const imageContainer = this.closest('.image-container');
            const image = imageContainer.querySelector('img');
            const title = imageContainer.querySelector('.image-title');
            const postContent = imageContainer.querySelector('.post-content');

            // Hide the image and title
            image.style.display = 'none';
            title.style.display = 'none';

            // Hide overlays if present
            const titleOverlay = imageContainer.querySelector('.title-overlay');
            const subtitleOverlay = imageContainer.querySelector('.subtitle-overlay');
            if (titleOverlay) titleOverlay.style.display = 'none';
            if (subtitleOverlay) subtitleOverlay.style.display = 'none';

            // Show the post content
            postContent.style.display = 'block';
        });
    });

    // JavaScript to handle close button
    document.querySelectorAll('.close-content').forEach(button => {
        button.addEventListener('click', function() {
            const postContent = this.parentElement;
            const imageContainer = postContent.parentElement;
            const image = imageContainer.querySelector('img');
            const title = imageContainer.querySelector('.image-title');

            // Show the image and title
            image.style.display = '';
            title.style.display = '';

            // Show overlays if present
            const titleOverlay = imageContainer.querySelector('.title-overlay');
            const subtitleOverlay = imageContainer.querySelector('.subtitle-overlay');
            if (titleOverlay) titleOverlay.style.display = '';
            if (subtitleOverlay) subtitleOverlay.style.display = '';

            // Hide the post content
            postContent.style.display = 'none';
        });
    });

    
</script>

<?php
get_footer();
?>
