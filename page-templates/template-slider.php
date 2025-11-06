<?php
/**
 * Template Name: Concept Stack Template
 *
 * @package your-theme-name
 */

get_header(); 
?>

<style>
    /* --- Basic Setup & Background --- */
    html, body {
        height: 100%; width: 100%; margin: 0; padding: 0;
        overflow: hidden;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    .concept-body {
        height: 100vh; width: 100vw; position: relative;
        background-color: black;
        background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%);
        color: #f0f0f0;
    }

    /* --- NEW: Page Loader --- */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    #page-loader {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99999;
        transition: opacity 0.5s ease;
    }
    #page-loader.is-hidden {
        opacity: 0;
        pointer-events: none;
    }
    .loader-sun-icon {
        width: 80px;
        height: 80px;
        animation: spin 2.5s linear infinite;
    }

    /* --- Contact & Main Title Area --- */
    .header-content {
        position: relative; z-index: 1000; padding: 30px 40px;
        display: flex; justify-content: space-between; align-items: flex-start;
        pointer-events: none;
        transition: opacity 0.4s ease;
    }
    .header-content > * { pointer-events: all; }
    .main-title { font-size: 4rem; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    /* ... other header styles are fine ... */

    /* --- Post Cards (Tabletop Style) --- */
    .post-page {
        position: absolute; width: 250px; height: 375px; cursor: grab;
        background-color: transparent;
        background-image: var(--bg-image);
        background-size: cover; background-position: center;
        border: 2px solid white; border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .post-page.is-visible { opacity: 1; transform: scale(1) rotate(var(--r, 0deg)); }
    .post-page:hover { box-shadow: 0 15px 45px rgba(0,0,0,0.5); transform: scale(1.03) rotate(var(--r, 0deg)); z-index: 4000 !important; }
    .post-page.is-dragging { cursor: grabbing; box-shadow: 0 20px 50px rgba(0,0,0,0.6); transform: scale(1.05) rotate(var(--r, 0deg)); pointer-events: none; transition: none; }

    /* --- Expanded Card State --- */
    .post-page.is-expanded {
        top: 50% !important; left: 50% !important;
        width: 95vw !important; height: 95vh !important;
        transform: translate(-50%, -50%) rotate(0deg) !important;
        cursor: default !important; z-index: 5000;
        background-image: none !important;
        background-color: rgba(30, 30, 30, 0.97);
        border-color: rgba(255, 255, 255, 0.5);
    }
    .post-page.is-expanded:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.4); }

    /* --- Content Inside Expanded Card --- */
    .card-content-view {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: transparent; color: #fff;
        padding: 5vw;
        overflow-y: auto;
        opacity: 0;
        transition: opacity 0.5s ease 0.3s;
        border-radius: 6px;
    }
    .post-page.is-expanded .card-content-view { opacity: 1; }
    .card-content-view h1 {
        font-size: clamp(2rem, 5vw, 4.5rem); margin: 0 0 2rem 0; font-weight: 800; line-height: 1.1;
    }
    .post-body-content {
        font-size: clamp(1rem, 1.5vw, 1.2rem); line-height: 1.6; max-width: 800px; margin: 0 auto;
    }
    .post-body-content p { margin-bottom: 1.5em; }

    /* --- WordPress Content Styling (Inside Expanded Card) --- */
    .post-body-content img, .post-body-content video, .post-body-content iframe { max-width: 100%; height: auto; display: block; margin: 1.5em auto; border-radius: 4px; }
    .post-body-content .wp-block-gallery { display: flex; flex-wrap: wrap; gap: 10px; margin: 1.5em 0; }
    .post-body-content .wp-block-gallery figure { flex: 1 1 150px; margin: 0; }
    .post-body-content blockquote { border-left: 3px solid #777; padding-left: 1.5em; margin: 1.5em 0; font-style: italic; color: #ddd; }
    .post-body-content .alignwide { max-width: 1000px; margin-left: auto; margin-right: auto; }
    .post-body-content .alignfull { max-width: none; width: 100%; }

    /* --- Add Card, Close Button, and other styles remain the same --- */
    /* ... (All other styles from previous version are fine) ... */
</style>

<!-- NEW: Loader HTML -->
<div id="page-loader">
    <svg class="loader-sun-icon" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="5"></circle>
        <line x1="12" y1="1" x2="12" y2="3"></line>
        <line x1="12" y1="21" x2="12" y2="23"></line>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
        <line x1="1" y1="12" x2="3" y2="12"></line>
        <line x1="21" y1="12" x2="23" y2="12"></line>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
    </svg>
</div>

<main class="concept-body" id="concept-body">
    <div id="card-viewer-overlay"></div>
    <div class="header-content">
        <!-- Header content is unchanged -->
    </div>

    <!-- REVISED: Advanced PHP Query Logic -->
    <?php
    $initial_card_count = 10;
    $total_posts_to_fetch = 20;
    
    $all_posts_collection = [];
    $exclude_ids = [];

    // --- Query 1: Get 'selected' posts first ---
    $selected_args = array(
        'post_type'      => 'post',
        'posts_per_page' => $total_posts_to_fetch, // Get up to 20 selected posts
        'tag'            => 'selected', // The tag slug
        'post_status'    => 'publish',
        'meta_query'     => array( array('key' => '_thumbnail_id') )
    );
    $selected_query = new WP_Query($selected_args);
    if ($selected_query->have_posts()) {
        while ($selected_query->have_posts()) {
            $selected_query->the_post();
            $all_posts_collection[] = get_post(get_the_ID()); // Add full post object
            $exclude_ids[] = get_the_ID(); // Store ID to exclude from next query
        }
    }
    wp_reset_postdata();

    // --- Query 2: Get remaining random posts ---
    $remaining_needed = $total_posts_to_fetch - count($all_posts_collection);
    if ($remaining_needed > 0) {
        $random_args = array(
            'post_type'      => 'post',
            'posts_per_page' => $remaining_needed,
            'orderby'        => 'rand',
            'post__not_in'   => $exclude_ids, // Crucial: avoid duplicates
            'post_status'    => 'publish',
            'meta_query'     => array( array('key' => '_thumbnail_id') )
        );
        $random_query = new WP_Query($random_args);
        if ($random_query->have_posts()) {
            while ($random_query->have_posts()) {
                $random_query->the_post();
                $all_posts_collection[] = get_post(get_the_ID());
            }
        }
        wp_reset_postdata();
    }

    // --- Now, process the combined collection ---
    $initial_posts_data = [];
    $additional_posts_data = [];
    $post_index = 0;

    foreach ($all_posts_collection as $post) {
        setup_postdata($post); // Setup post data for template tags to work
        $image_url = get_the_post_thumbnail_url($post->ID, 'large');
        if ($image_url) {
            $post_data = [
                'title'     => get_the_title($post),
                'content'   => apply_filters('the_content', $post->post_content),
                'image_url' => esc_url($image_url),
            ];

            if ($post_index < $initial_card_count) {
                $initial_posts_data[] = $post_data;
                ?>
                <div class="post-page" data-index="<?php echo $post_index; ?>" style="--bg-image: url('<?php echo esc_url($image_url); ?>');"></div>
                <?php
            } else {
                $additional_posts_data[] = $post_data;
            }
            $post_index++;
        }
    }
    wp_reset_postdata(); // Clean up
    ?>
</main>

<button id="add-card-button" class="add-card-button" aria-label="Add another card">+</button>

<!-- ... Contact Modal and other elements ... -->

<script>
    const initialPostsData = <?php echo json_encode($initial_posts_data); ?>;
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>

<script>
// The JavaScript logic is mostly the same, with one key addition in window.onload.
document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    const container = document.getElementById('concept-body');
    const addCardBtn = document.getElementById('add-card-button');
    const viewerOverlay = document.getElementById('card-viewer-overlay');
    const initialCards = document.querySelectorAll('.post-page');
    const pageLoader = document.getElementById('page-loader'); // Get the loader
    
    let availablePosts = [...additionalPostsData];
    let highestZ = initialCards.length;
    let expandedCard = null;

    function randomizeInitialLayout() {
        // This function is now responsible for laying out the cards, but not hiding the loader.
        initialCards.forEach((card, index) => {
            card.postData = initialPostsData[index];
            const cardWidth = 250; const cardHeight = 375;
            const randomX = Math.floor(Math.random() * (window.innerWidth - cardWidth - 80)) + 40;
            const randomY = Math.floor(Math.random() * (window.innerHeight - cardHeight - 80)) + 40;
            const randomRot = Math.random() * 20 - 10;
            card.style.left = `${randomX}px`;
            card.style.top = `${randomY}px`;
            card.style.setProperty('--r', `${randomRot}deg`);
            card.style.zIndex = index + 1;
            setTimeout(() => card.classList.add('is-visible'), index * 80);
        });
    }
    
    // UPDATED: window.onload now handles hiding the loader
    window.onload = function() {
        randomizeInitialLayout(); // Lay out the cards as before.
        
        // After the layout starts, hide the loader.
        if (pageLoader) {
            setTimeout(() => { // A tiny delay can make the transition feel smoother
                pageLoader.classList.add('is-hidden');
            }, 200);
        }
    };

    // The rest of the JavaScript (addCard, expandCard, collapseCard, drag logic) is unchanged.
    // ... (All other JS functions from previous version are fine) ...
    function addCard() {
        if (availablePosts.length === 0) return;
        const postData = availablePosts.shift();
        highestZ++;
        const card = document.createElement('div');
        card.className = 'post-page';
        card.style.setProperty('--bg-image', `url('${postData.image_url}')`);
        card.postData = postData;
        const cardWidth = 250; const cardHeight = 375;
        const randomX = Math.floor(Math.random() * (window.innerWidth - cardWidth - 80)) + 40;
        const randomY = Math.floor(Math.random() * (window.innerHeight - cardHeight - 80)) + 40;
        const randomRot = Math.random() * 20 - 10;
        card.style.left = `${randomX}px`;
        card.style.top = `${randomY}px`;
        card.style.setProperty('--r', `${randomRot}deg`);
        card.style.zIndex = highestZ;
        container.appendChild(card);
        setTimeout(() => card.classList.add('is-visible'), 50);
        if (availablePosts.length === 0) {
            addCardBtn.disabled = true;
        }
    }
    if (addCardBtn) {
        addCardBtn.addEventListener('click', addCard);
        if (availablePosts.length === 0) {
            addCardBtn.disabled = true;
        }
    }
    function expandCard(cardElement) {
        if (expandedCard || !cardElement.postData) return;
        expandedCard = cardElement;
        body.classList.add('card-is-active');
        viewerOverlay.classList.add('is-visible');
        const contentView = document.createElement('div');
        contentView.className = 'card-content-view';
        const closeButton = document.createElement('button');
        closeButton.className = 'card-close-button';
        closeButton.innerHTML = '&times;';
        closeButton.onclick = (e) => { e.stopPropagation(); collapseCard(); };
        const title = document.createElement('h1');
        title.textContent = cardElement.postData.title;
        const bodyContent = document.createElement('div');
        bodyContent.className = 'post-body-content';
        bodyContent.innerHTML = cardElement.postData.content;
        contentView.appendChild(closeButton);
        contentView.appendChild(title);
        contentView.appendChild(bodyContent);
        cardElement.appendChild(contentView);
        cardElement.classList.add('is-expanded');
    }
    function collapseCard() {
        if (!expandedCard) return;
        body.classList.remove('card-is-active');
        viewerOverlay.classList.remove('is-visible');
        const contentView = expandedCard.querySelector('.card-content-view');
        if (contentView) {
            expandedCard.removeChild(contentView);
        }
        expandedCard.classList.remove('is-expanded');
        expandedCard = null;
    }
    viewerOverlay.addEventListener('click', collapseCard);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') collapseCard(); });
    let activeCard = null, isDragging = false, startX, startY, initialX, initialY;
    function dragStart(e) {
        if (expandedCard) return;
        if (e.target.classList.contains('post-page')) {
            e.preventDefault(); e.stopPropagation();
            activeCard = e.target;
            isDragging = false;
            highestZ++;
            activeCard.style.zIndex = highestZ;
            activeCard.classList.add('is-dragging');
            startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
            startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
            initialX = activeCard.offsetLeft;
            initialY = activeCard.offsetTop;
            document.addEventListener('mousemove', dragging);
            document.addEventListener('touchmove', dragging, { passive: false });
            document.addEventListener('mouseup', dragEnd);
            document.addEventListener('touchend', dragEnd);
        }
    }
    function dragging(e) {
        if (!activeCard) return;
        e.preventDefault();
        let currentX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        let currentY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;
        const deltaX = currentX - startX; const deltaY = currentY - startY;
        if (Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) isDragging = true;
        if (isDragging) {
            activeCard.style.left = `${initialX + deltaX}px`;
            activeCard.style.top = `${initialY + deltaY}px`;
        }
    }
    function dragEnd(e) {
        if (!activeCard) return;
        document.removeEventListener('mousemove', dragging);
        document.removeEventListener('touchmove', dragging);
        document.removeEventListener('mouseup', dragEnd);
        document.removeEventListener('touchend', dragEnd);
        if (!isDragging) expandCard(activeCard);
        activeCard.classList.remove('is-dragging');
        activeCard = null;
    }
    container.addEventListener('mousedown', dragStart);
    container.addEventListener('touchstart', dragStart, { passive: false });
});
</script>

<?php
get_footer(); 
?>