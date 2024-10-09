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

        // Clone last section for horizontal looping (if needed)
        if ( $index === 0 ) {
            // Clone of last section
            // Fetch the last section's posts
            $last_section = end( $sections );
            // Fetch posts from the last category
            $last_args = array(
                'category_name'  => $last_section['slug'],
                'post_status'    => 'publish',
                'posts_per_page' => -1,
            );
            $last_posts = new WP_Query( $last_args );
            ?>
            <div class="vertical-section" id="section-last-clone">
                <?php
                // Output cloned last section's images
                if ( $last_posts->have_posts() ) {
                    $last_posts_array = $last_posts->posts;
                    $last_image = array_pop( $last_posts_array );
                    // Clone of last image
                    setup_postdata( $last_image );
                    $image_url = get_the_post_thumbnail_url( $last_image->ID, 'full' );
                    ?>
                    <div class="image-container">
                        <a href="<?php echo get_permalink( $last_image->ID ); ?>" class="image-link">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title( $last_image->ID ) ); ?>">
                            <div class="image-title"><?php echo esc_html( get_the_title( $last_image->ID ) ); ?></div>
                        </a>
                    </div>
                    <?php
                    wp_reset_postdata();
                    // Output original images
                    foreach ( $last_posts->posts as $post ) {
                        setup_postdata( $post );
                        $image_url = get_the_post_thumbnail_url( $post->ID, 'full' );
                        ?>
                        <div class="image-container">
                            <a href="<?php the_permalink(); ?>" class="image-link">
                                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>">
                                <?php if ( $post === reset( $last_posts->posts ) ) : ?>
                                    <div class="title-overlay"><?php echo esc_html( $last_section['title'] ); ?></div>
                                    <?php if ( $last_section['subtitle'] ) : ?>
                                        <div class="subtitle-overlay"><?php echo esc_html( $last_section['subtitle'] ); ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div class="image-title"><?php the_title(); ?></div>
                            </a>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                    // Clone of first image
                    $first_image = reset( $last_posts_array );
                    setup_postdata( $first_image );
                    $image_url = get_the_post_thumbnail_url( $first_image->ID, 'full' );
                    ?>
                    <div class="image-container">
                        <a href="<?php echo get_permalink( $first_image->ID ); ?>" class="image-link">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title( $first_image->ID ) ); ?>">
                            <div class="image-title"><?php echo esc_html( get_the_title( $first_image->ID ) ); ?></div>
                        </a>
                    </div>
                    <?php
                    wp_reset_postdata();
                }
                ?>
                <div class="contact-message">Contact</div>
            </div>
            <?php
            wp_reset_postdata();
        }

        // Original sections
        ?>
        <div class="vertical-section" id="section-<?php echo esc_attr( $index + 1 ); ?>">
            <?php
            // Fetch posts for vertical looping clones
            if ( $posts->have_posts() ) {
                $posts_array = $posts->posts;
                $last_image = array_pop( $posts_array );
                // Clone of last image
                setup_postdata( $last_image );
                $image_url = get_the_post_thumbnail_url( $last_image->ID, 'full' );
                ?>
                <div class="image-container">
                    <a href="<?php echo get_permalink( $last_image->ID ); ?>" class="image-link">
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title( $last_image->ID ) ); ?>">
                        <div class="image-title"><?php echo esc_html( get_the_title( $last_image->ID ) ); ?></div>
                    </a>
                </div>
                <?php
                wp_reset_postdata();
                // Output original images
                foreach ( $posts->posts as $post ) {
                    setup_postdata( $post );
                    $image_url = get_the_post_thumbnail_url( $post->ID, 'full' );
                    ?>
                    <div class="image-container">
                        <a href="<?php the_permalink(); ?>" class="image-link">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php if ( $post === reset( $posts->posts ) ) : ?>
                                <div class="title-overlay"><?php echo esc_html( $section['title'] ); ?></div>
                                <?php if ( $section['subtitle'] ) : ?>
                                    <div class="subtitle-overlay"><?php echo esc_html( $section['subtitle'] ); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="image-title"><?php the_title(); ?></div>
                        </a>
                    </div>
                    <?php
                }
                wp_reset_postdata();
                // Clone of first image
                $first_image = reset( $posts_array );
                setup_postdata( $first_image );
                $image_url = get_the_post_thumbnail_url( $first_image->ID, 'full' );
                ?>
                <div class="image-container">
                    <a href="<?php echo get_permalink( $first_image->ID ); ?>" class="image-link">
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title( $first_image->ID ) ); ?>">
                        <div class="image-title"><?php echo esc_html( get_the_title( $first_image->ID ) ); ?></div>
                    </a>
                </div>
                <?php
                wp_reset_postdata();
            }
            ?>
            <div class="contact-message">Contact</div>
        </div>
        <?php
    }

    // Clone of first section for horizontal looping
    // Fetch posts from the first category
    $first_section = $sections[0];
    $first_args = array(
        'category_name'  => $first_section['slug'],
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    );
    $first_posts = new WP_Query( $first_args );
    ?>
    <div class="vertical-section" id="section-first-clone">
        <?php
        if ( $first_posts->have_posts() ) {
            $posts_array = $first_posts->posts;
            $last_image = array_pop( $posts_array );
            // Clone of last image
            setup_postdata( $last_image );
            $image_url = get_the_post_thumbnail_url( $last_image->ID, 'full' );
            ?>
            <div class="image-container">
                <a href="<?php echo get_permalink( $last_image->ID ); ?>" class="image-link">
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title( $last_image->ID ) ); ?>">
                    <div class="image-title"><?php echo esc_html( get_the_title( $last_image->ID ) ); ?></div>
                </a>
            </div>
            <?php
            wp_reset_postdata();
            // Output original images
            foreach ( $first_posts->posts as $post ) {
                setup_postdata( $post );
                $image_url = get_the_post_thumbnail_url( $post->ID, 'full' );
                ?>
                <div class="image-container">
                    <a href="<?php the_permalink(); ?>" class="image-link">
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php if ( $post === reset( $first_posts->posts ) ) : ?>
                            <div class="title-overlay"><?php echo esc_html( $first_section['title'] ); ?></div>
                            <?php if ( $first_section['subtitle'] ) : ?>
                                <div class="subtitle-overlay"><?php echo esc_html( $first_section['subtitle'] ); ?></div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="image-title"><?php the_title(); ?></div>
                    </a>
                </div>
                <?php
            }
            wp_reset_postdata();
            // Clone of first image
            $first_image = reset( $posts_array );
            setup_postdata( $first_image );
            $image_url = get_the_post_thumbnail_url( $first_image->ID, 'full' );
            ?>
            <div class="image-container">
                <a href="<?php echo get_permalink( $first_image->ID ); ?>" class="image-link">
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title( $first_image->ID ) ); ?>">
                    <div class="image-title"><?php echo esc_html( get_the_title( $first_image->ID ) ); ?></div>
                </a>
            </div>
            <?php
            wp_reset_postdata();
        }
        ?>
        <div class="contact-message">Contact</div>
    </div>
</div>

<script>
    // JavaScript for seamless looping

    // Function to enable seamless vertical looping
    function enableVerticalLooping(section) {
        const images = section.querySelectorAll('.image-container');
        const totalImages = images.length;

        // Adjust scroll position to the first original image
        section.scrollTop = images[1].offsetTop;

        section.addEventListener('scroll', () => {
            const scrollTop = section.scrollTop;
            const firstImageOffset = images[1].offsetTop;
            const lastImageOffset = images[totalImages - 2].offsetTop;
            const totalScrollHeight = section.scrollHeight;
            const sectionHeight = section.clientHeight;

            // When scrolling up from the first original image
            if (scrollTop <= images[0].offsetTop) {
                // Jump to the clone of the first image at the bottom
                section.scrollTop = lastImageOffset;
            }

            // When scrolling down from the clone of the first image
            if (scrollTop >= images[totalImages - 1].offsetTop) {
                // Jump back to the first original image
                section.scrollTop = firstImageOffset;
            }
        });
    }

    // Apply vertical looping to each section
    document.querySelectorAll('.vertical-section').forEach(section => {
        enableVerticalLooping(section);
    });

    // Function to enable seamless horizontal looping
    function enableHorizontalLooping(container) {
        const sections = container.querySelectorAll('.vertical-section');
        const totalSections = sections.length;

        // Adjust scroll position to the first original section
        container.scrollLeft = sections[1].offsetLeft;

        container.addEventListener('scroll', () => {
            const scrollLeft = container.scrollLeft;
            const firstSectionOffset = sections[1].offsetLeft;
            const lastSectionOffset = sections[totalSections - 2].offsetLeft;
            const totalScrollWidth = container.scrollWidth;

            // When scrolling left from the first original section
            if (scrollLeft <= sections[0].offsetLeft) {
                // Jump to the clone of the first section at the end
                container.scrollLeft = lastSectionOffset;
            }

            // When scrolling right from the clone of the first section
            if (scrollLeft >= sections[totalSections - 1].offsetLeft) {
                // Jump back to the first original section
                container.scrollLeft = firstSectionOffset;
            }
        });
    }

    // Apply horizontal looping to the container
    const horizontalContainer = document.getElementById('horizontal-container');
    enableHorizontalLooping(horizontalContainer);
</script>


<?php
get_footer();
?>
