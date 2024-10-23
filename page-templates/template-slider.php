<?php
/**
 * Template Name: Slider Template
 */
get_header();
?>

<!-- Map Toggle Button -->
<button id="map-toggle" class="map-toggle">Map</button>



<div class="horizontal-container" id="horizontal-container">
    <?php
    // Define the categories or sections
    $sections = array(
        array(
            'slug' => 'home',
            'title' => '',
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
    ?>
    
    <!-- Map Container -->
    <div id="site-map" class="site-map hidden">
        <div class="map-container">
            <?php foreach ($sections as $index => $section): ?>
                <div class="map-column">
                    <div class="category-name"><?php echo esc_html($section['title']); ?></div>
                    <?php
                    $args = array(
                        'category_name' => $section['slug'],
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                    );
                    $posts = new WP_Query($args);

                    if ($posts->have_posts()):
                        foreach ($posts->posts as $post):
                            setup_postdata($post);
                            $image_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');
                            ?>
                            <div class="map-slide" data-target="section-<?php echo esc_attr($index + 1); ?>">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
                            </div>
                        <?php endforeach; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
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
                            <a href="<?php echo get_permalink( $post->ID ); ?>" class="view-more" style="color: black;">View More</a>
                        </div>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            }
            ?>
            <div class="contact-message"></div>
        </div>
        <?php
    }
    ?>
</div>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        const mapToggle = document.getElementById('map-toggle');
        const siteMap = document.getElementById('site-map');

        // Toggle map visibility
        mapToggle.addEventListener('click', function () {
            siteMap.classList.toggle('hidden'); // Properly toggle the hidden class
        });

        // Handle map slide click to scroll to the corresponding section and image
        document.querySelectorAll('.map-slide').forEach(slide => {
            slide.addEventListener('click', function () {
                const targetSectionId = this.getAttribute('data-target'); // Get section ID
                const targetSection = document.getElementById(targetSectionId);

                if (targetSection) {
                    // First scroll to the horizontal section (if applicable)
                    targetSection.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'start' });

                    // After scrolling to the section, scroll vertically to the clicked image within that section
                    const imageSrc = this.querySelector('img').src; // Get the image source from the clicked map slide
                    const targetImage = targetSection.querySelector(`img[src="${imageSrc}"]`); // Find the matching image in the section

                    if (targetImage) {
                        // Scroll to the target image within the section
                        targetImage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }

                    // Hide the map after clicking on an image
                    siteMap.classList.add('hidden');
                }
            });
        });
    });

    // JavaScript to handle click events on posts
    document.querySelectorAll('.image-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            const imageContainer = this.closest('.image-container');
            const image = imageContainer.querySelector('img');
            const title = imageContainer.querySelector('.image-title');
            const postContent = imageContainer.querySelector('.post-content');

            // Toggle visibility
            if (postContent.style.display === 'block') {
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
            } else {
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
            }
        });
    });

    // JavaScript to handle closing the post content
    document.querySelectorAll('.post-content').forEach(content => {
        // Close when clicking on the close button
        const closeButton = content.querySelector('.close-content');
        closeButton.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event from bubbling up to the content div

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

        // Close when clicking anywhere on the post content div
        content.addEventListener('click', function(e) {
            const postContent = this;
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
        link.addEventListener('click', function() {
            saveScrollPosition(); // Save the scroll position before navigating
        });
    });


</script>


<?php
get_footer();
?>
