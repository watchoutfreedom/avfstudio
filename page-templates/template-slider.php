<?php
/**
 * Template Name: Concept Stack Template
 *
 * @package your-theme-name
 */

get_header(); 
?>

<style>


    /* --- NEW: Custom Font Declaration --- */
    @font-face {
        font-family: 'Airbnb Cereal App'; /* You can name this whatever you like */
        src: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/fonts/AirbnbCereal_W_Bd.otf') format('otf'),
            url('<?php echo get_stylesheet_directory_uri(); ?>/assets/fonts/AirbnbCereal_W_Md.otf') format('otf'),
            url('<?php echo get_stylesheet_directory_uri(); ?>/assets/fonts/AirbnbCereal_W_Bk.otf') format('otf'),
            url('<?php echo get_stylesheet_directory_uri(); ?>/assets/fonts/AirbnbCereal_W_Blk.otf') format('otf'),
            url('<?php echo get_stylesheet_directory_uri(); ?>/assets/fonts/AirbnbCereal_W_Lt.otf') format('otf'),
             url('<?php echo get_stylesheet_directory_uri(); ?>/assets/fonts/AirbnbCereal_W_XBd.otf') format('otf');


            /* Add more formats if you have them, e.g., .ttf */
        font-weight: 700; /* 'Bd' usually means Bold, which is 700 */
        font-style: normal;
        font-display: swap; /* Improves perceived performance */
    }


    /* --- Basic Setup & Background --- */
    html, body {
        height: 100%; width: 100%; margin: 0; padding: 0; overflow: hidden;
        font-family: 'Airbnb Cereal App', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; /* MODIFIED: Add the new font first */
    }

    .concept-body { height: 100vh; width: 100vw; position: relative; background-color: black; background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%); color: #f0f0f0; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    #page-loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%); display: flex; justify-content: center; align-items: center; z-index: 99999; transition: opacity 0.5s ease-out; }
    #page-loader.is-hidden { opacity: 0; pointer-events: none; }
    #loader-spiral { width: 60px; height: 60px; border: 5px solid transparent; border-top-color: #fff; border-radius: 50%; animation: spin 1s linear infinite; }
    .header-content { display: none; }
    #card-viewer-overlay { display: none; }
    .is-draggable { cursor: grab; user-select: none; -webkit-user-select: none; }
    .is-draggable.is-dragging { cursor: grabbing; transition: none !important; }
    .post-page { position: absolute; width: 250px; height: 375px; background-color: transparent; background-image: var(--bg-image); background-size: cover; background-position: center; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); opacity: 0; transform: scale(0.5); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .post-page.is-visible { opacity: 1; transform: scale(1) rotate(var(--r, 0deg)); }
    .post-page:hover { box-shadow: 0 15px 45px rgba(0,0,0,0.5); transform: scale(1.03) rotate(var(--r, 0deg)); z-index: 4000 !important; }
    .post-page.is-expanded { top: 50% !important; left: 50% !important; width: 95vw !important; height: 95vh !important; transform: translate(-50%, -50%) rotate(0deg) !important; cursor: default !important; z-index: 5000; border-color: rgba(255, 255, 255, 0.5); background-image: none !important; background-color: var(--expanded-bg, rgba(30, 30, 30, 0.97)); }
    .brand-card { background-color: #111; background-image: none !important; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px; text-align: center; }
    .brand-card h1 { color: white; margin: 0; letter-spacing: 1px; font-size: 2.5rem; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; }
    .brand-card h2 { color: #aaa; margin: 0; font-size: 0.9rem; font-weight: 300; }
    .propose-card { background-color: #fff; background-image: none !important; color: #111; display: flex; justify-content: center; align-items: center; text-align: center; padding: 20px; }
    .propose-card h3 { font-size: 1.5rem; font-weight: 600; margin: 0; }
    .propose-card.is-expanded h3 { display: none; }
    .card-content-view {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        color: var(--expanded-text-color, #fff);
        /* MODIFIED: More vertical padding, less horizontal */
        padding: 8vh 5vw; 
        overflow-y: auto;
        opacity: 0;
        transition: opacity 0.5s ease 0.3s;
        border-radius: 6px;
        box-sizing: border-box; /* Ensures padding is calculated correctly */
    }    
    
    .post-page.is-expanded .card-content-view { opacity: 1; }
    .card-content-view h1 { font-size: clamp(2rem, 5vw, 4.5rem); margin: 0 0 2rem 0; }
    .card-content-view .post-body-content { max-width: 850px; margin: 0 auto; font-size: clamp(1rem, 1.5vw, 1.1rem); line-height: 1.7; }
    .post-body-content p { max-width: 75ch; margin-left: auto; margin-right: auto; margin-bottom: 1.7em; }
    .post-body-content > p:first-of-type::first-letter { font-size: 4em; font-weight: bold; float: left; line-height: 0.8; margin-right: 0.1em; color: gray; }
    .post-body-content img { max-width: 100%; height: auto; display: block; margin: 2em auto; border-radius: 4px; box-shadow: 0 8px 25px rgba(0,0,0,0.3); filter: sepia(20%) brightness(95%); }
    .post-body-content .wp-block-gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); margin: 2.5em 0; }
    .post-body-content blockquote { max-width: 70ch; margin: 2.5em auto; padding: 1.5em 2em; font-size: 1.4em; font-style: italic; line-height: 1.4; background-color: rgba(255, 255, 255, 0.05); border: none; border-left: 4px solid #aaa; }
    .card-content-view .brand-content { 
        font-weight: bold; max-width: 850px; margin: 0 auto; text-align: center;  
        padding: 100px 20px;

    }
    /* --- NEW: Restored Brand Card Link Style --- */
    .card-content-view .brand-content a {
        display: inline-block; /* Allows padding, margins, and border to work correctly */
        margin-top: 30px;
        padding: 12px 24px;
        border: 1px solid #fff;
        border-radius: 30px; /* Creates the pill shape */
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .card-content-view .brand-content a:hover {
        background-color: #fff;
        color: #111; /* Inverts colors for a satisfying hover effect */
        transform: scale(1.05);
    }
    
    .card-close-button { position: fixed; top: 15px; right: 15px; font-size: 2.5rem; color: inherit; background: none; border: none; cursor: pointer; z-index: 10; }
    .propose-form-container { max-width: 850px; margin: 0 auto; text-align: left; }
    .propose-form-container h1 { color: #111; }
    .propose-form-container p { color: #666; margin-top: -15px; margin-bottom: 25px; font-size: 1rem; }
    .propose-form-container label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
    .propose-form-container input, .propose-form-container textarea { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; box-sizing: border-box; background-color: #f9f9f9; }
    .propose-form-container textarea { min-height: 150px; resize: vertical; }
    .propose-form-container .captcha-group { display: flex; align-items: center; margin-bottom: 20px; color: #333; }
    .propose-form-container button[type="submit"] { width: 100%; padding: 15px; background-color: #333; color: #fff; border: none; border-radius: 4px; font-size: 1.1rem; cursor: pointer; }
    .add-card-button { position: fixed; z-index: 2000; bottom: 40px; right: 40px; width: 60px; height: 60px; background-color: #f0f0f0; color: #333; border: none; border-radius: 50%; font-size: 3rem; line-height: 60px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease; cursor: pointer; 
    display: flex;
    justify-content: center;
    align-items: center;
    padding-bottom: 10px;
    padding-right: 10px;
    padding-left: 10px;
    padding-top: 5px;
    }
    .add-card-button.is-disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }


        /* --- NEW: Image Lightbox Styling --- */
    .image-lightbox-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 6000; /* Above the expanded card */
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        cursor: zoom-out;
    }
    .image-lightbox-overlay.is-visible {
        opacity: 1;
        pointer-events: all;
    }
    .image-lightbox-overlay img {
        display: block;
        max-height: 90vh;
        max-width: 90vw;
        box-shadow: 0 0 50px rgba(0,0,0,0.5);
        border-radius: 4px;
        width: 100%; /* On mobile, take up full width */
    }
    /* Desktop-specific sizing */
    @media (min-width: 769px) {

        .image-lightbox-overlay img {
            width: 50%; /* On desktop, take up 50% width */
        }

        .post-body-content {

              padding-top: 50px;

        }

        .card-content-view .brand-content {
       
            padding: 100px 20px;
        }
    }
</style>

<div id="page-loader"><div id="loader-spiral"></div></div>

<main class="concept-body" id="concept-body">
    <div id="image-lightbox" class="image-lightbox-overlay"></div>

    <div id="card-viewer-overlay"></div>
    <div class="header-content"></div>
    <!-- PHP is correct and unchanged -->
    <?php
    $initial_card_count = 10; $total_posts_to_fetch = 20; $all_posts_collection = []; $exclude_ids = [];
    $selected_tag = get_term_by('slug', 'selected', 'post_tag');
    if ($selected_tag) {
        $selected_args = ['post_type' => 'post', 'posts_per_page' => $total_posts_to_fetch, 'tag_id' => $selected_tag->term_id, 'post_status' => 'publish', 'meta_query' => [['key' => '_thumbnail_id']]];
        $selected_query = new WP_Query($selected_args);
        if ($selected_query->have_posts()) { foreach ($selected_query->get_posts() as $post) { $all_posts_collection[] = $post; $exclude_ids[] = $post->ID; } }
    }
    $remaining_needed = $total_posts_to_fetch - count($all_posts_collection);
    if ($remaining_needed > 0) {
        $random_args = ['post_type' => 'post', 'posts_per_page' => $remaining_needed, 'orderby' => 'rand', 'post__not_in' => $exclude_ids, 'post_status' => 'publish', 'meta_query' => [['key' => '_thumbnail_id']]];
        $random_query = new WP_Query($random_args);
        if ($random_query->have_posts()) { foreach($random_query->get_posts() as $post) { $all_posts_collection[] = $post; } }
    }
    $initial_posts_data = []; $additional_posts_data = []; $post_index = 0;
    foreach ($all_posts_collection as $post) {
        setup_postdata($post); $image_url = get_the_post_thumbnail_url($post->ID, 'large');
        if ($image_url) {
            $post_data = ['title' => get_the_title($post), 'content' => apply_filters('the_content', $post->post_content), 'image_url' => esc_url($image_url)];
            if ($post_index < $initial_card_count) {
                $initial_posts_data[] = $post_data;
                echo '<div class="post-page is-draggable" data-index="' . $post_index . '" style="--bg-image: url(\'' . esc_url($image_url) . '\');"></div>';
            } else { $additional_posts_data[] = $post_data; }
            $post_index++;
        }
    }
    wp_reset_postdata();
    ?>
</main>
<button id="add-card-button" class="add-card-button" aria-label="Add another card">+</button>
<!-- Contact modal div is no longer needed -->

<script>
    const initialPostsData = <?php echo json_encode($initial_posts_data); ?>;
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Global Variables ---
    const body = document.body, container = document.getElementById('concept-body'), addCardBtn = document.getElementById('add-card-button'), pageLoader = document.getElementById('page-loader');
    let availablePosts = [...(additionalPostsData || [])];
    let highestZ = 0, expandedCard = null, hasThrownFinalCard = false;
    let preloadLink = document.createElement('link'); // Preload element for the "next" card
    preloadLink.rel = 'preload';
    preloadLink.as = 'image';
    document.head.appendChild(preloadLink);

    // --- NEW: Preloading Engine ---

    // Function to preload a single image URL
    const preloadImage = (url) => {
        const img = new Image();
        img.src = url;
    };
    
    // Preload the very first "next up" post image, if it exists
    const preloadNextCardImage = () => {
        if (availablePosts.length > 0) {
            preloadLink.href = availablePosts[0].image_url;
        } else {
            preloadLink.removeAttribute('href'); // No more posts to preload
        }
    };
    
    // Proactively preload all images within a post's content
    const preloadPostContent = (postData) => {
        if (!postData || postData.preloaded) return; // Don't preload twice
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = postData.content;
        const images = tempDiv.querySelectorAll('img');
        images.forEach(img => preloadImage(img.src));
        
        postData.preloaded = true; // Mark as preloaded
    };

    // --- Core Function Definitions ---
    const createCard = (data) => { /* ... (Unchanged) ... */ };
    function randomizeInitialLayout(){ /* ... (Unchanged) ... */ }
    
    // MODIFIED: This function now triggers the next preload
    function addCardFromButton() {
        if (availablePosts.length > 0) {
            const postData = { type: 'post', ...availablePosts.shift() }; // Get current post
            const newCard = createCard(postData);
            // ... (positioning logic is unchanged)
            setTimeout(()=>newCard.classList.add("is-visible"),50);
            
            // IMPORTANT: Preload the *next* image in the stack
            preloadNextCardImage(); 

        } else if (!hasThrownFinalCard) {
            throwProposeCard();
            hasThrownFinalCard = true;
            addCardBtn.classList.add("is-disabled");
        }
    }

    function throwProposeCard(andExpand = false) { /* ... (Unchanged) ... */ }
    function expandCard(cardElement){ /* ... (Unchanged) ... */ }
    function collapseCard() { /* ... (Unchanged) ... */ }
    function setupProposeForm() { /* ... (Unchanged) ... */ }

    // --- MODIFIED: Unified Drag-and-Drop Engine with Hover Preloading ---
    let activeElement=null, isDragging=false, startX, startY, initialX, initialY;
    let hoverTimeout = null; // To manage hover intent

    function dragStart(e) {
        const target = e.target.closest(".is-draggable");
        if (!target || expandedCard) return;
        
        // When mouse goes down, clear any pending hover preload
        clearTimeout(hoverTimeout); 

        e.preventDefault(); e.stopPropagation();
        activeElement = target; isDragging = false; highestZ++;
        activeElement.style.zIndex = highestZ; activeElement.classList.add("is-dragging");
        startX = e.type === "touchstart" ? e.touches[0].clientX : e.clientX;
        startY = e.type === "touchstart" ? e.touches[0].clientY : e.clientY;
        initialX = activeElement.offsetLeft; initialY = activeElement.offsetTop;
        document.addEventListener("mousemove", dragging);
        document.addEventListener("touchmove", dragging, { passive: false });
        document.addEventListener("mouseup", dragEnd);
        document.addEventListener("touchend", dragEnd);
    }

    function dragging(e) { /* ... (Unchanged) ... */ }
    function dragEnd() { /* ... (Unchanged) ... */ }

    // --- NEW: Hover Listeners for Proactive Preloading ---
    container.addEventListener('mouseover', (e) => {
        const targetCard = e.target.closest(".post-page");
        if (targetCard && targetCard.cardData && targetCard.cardData.type === 'post') {
            // Wait 100ms to confirm user intent before preloading
            clearTimeout(hoverTimeout);
            hoverTimeout = setTimeout(() => {
                preloadPostContent(targetCard.cardData);
            }, 100);
        }
    });

    container.addEventListener('mouseout', () => {
        // When mouse leaves, cancel any pending preload
        clearTimeout(hoverTimeout);
    });

    // --- Event Listeners & Initial Calls ---
    window.onload = function(){
        randomizeInitialLayout();
        preloadNextCardImage(); // **INITIAL PRELOAD CALL**
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add("is-hidden"); }, 200); }
    };
    
    if (addCardBtn){
        addCardBtn.addEventListener('click', addCardFromButton);
        if(availablePosts.length === 0){ addCardBtn.classList.add("is-disabled"); }
    }
    
    // viewerOverlay is gone, but we still need a way to close expanded cards
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && expandedCard) {
            collapseCard();
        }
    });
    
    container.addEventListener("mousedown", dragStart);
    container.addEventListener("touchstart", dragStart, { passive: false });
});
</script>

<?php
get_footer(); 
?>