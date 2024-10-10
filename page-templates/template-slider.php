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
            'title' => 'Welcome',
            'subtitle' => '',
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
                            <?php
                            // Display the first paragraph of the content as an excerpt
                            $content = apply_filters( 'the_content', $post->post_content );
                            preg_match('/<p>(.*?)<\/p>/', $content, $matches);
                            if (isset($matches[0])) {
                                echo $matches[0];
                            }
                            ?>
                            <a href="<?php echo get_permalink( $post->ID ); ?>" class="view-more" data-post-id="<?php echo $post->ID; ?>" style="color: black;">View More</a>
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
    // Function to save both horizontal and vertical scroll positions
    function saveScrollPosition() {
        const horizontalScroll = document.getElementById('horizontal-container').scrollLeft;
        const sections = document.querySelectorAll('.vertical-section');
        let verticalScroll = {};
        sections.forEach(section => {
            const sectionId = section.id;
            verticalScroll[sectionId] = section.scrollTop;
        });
        
        sessionStorage.setItem('horizontalScroll', horizontalScroll);
        sessionStorage.setItem('verticalScroll', JSON.stringify(verticalScroll));
    }

    // Function to restore both horizontal and vertical scroll positions
    function restoreScrollPosition() {
        const savedHorizontalScroll = sessionStorage.getItem('horizontalScroll');
        const savedVerticalScroll = JSON.parse(sessionStorage.getItem('verticalScroll'));

        if (savedHorizontalScroll !== null) {
            document.getElementById('horizontal-container').scrollLeft = savedHorizontalScroll;
        }

        if (savedVerticalScroll !== null) {
            Object.keys(savedVerticalScroll).forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (section) {
                    section.scrollTop = savedVerticalScroll[sectionId];
                }
            });
        }
    }

    // Call the restore function when the page loads
    window.addEventListener('load', restoreScrollPosition);

    // Handle "View More" link to save scroll position before navigating
    document.querySelectorAll('.view-more').forEach(link => {
        link.addEventListener('click', function(e) {
            saveScrollPosition(); // Save the scroll position
        });
    });
</script>

<?php
get_footer();
?>
