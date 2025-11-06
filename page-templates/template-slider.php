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
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .concept-body {
        height: 100vh;
        width: 100vw;
        position: relative;
        background-color: black;
        background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%);
        color: #f0f0f0;
    }

    /* --- Contact & Main Title Area --- */
    .header-content {
        position: relative;
        z-index: 1000;
        padding: 30px 40px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        pointer-events: none;
        transition: opacity 0.4s ease;
    }
    .header-content > * { pointer-events: all; }
    .main-header { text-align: left; }
    .main-title { font-size: 4rem; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; font-weight: 300; margin: 0; color: #bbb; }
    .contact-icon-button { background: none; border: none; cursor: pointer; padding: 10px; }
    .contact-icon-button svg { width: 32px; height: 32px; fill: #f0f0f0; transition: transform 0.3s ease; }
    .contact-icon-button:hover svg { transform: scale(1.1); }

    /* --- Overlay for Expanded Card View --- */
    #card-viewer-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.8);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #card-viewer-overlay.is-visible {
        opacity: 1;
        pointer-events: all;
    }

    /* --- Post Cards (Tabletop Style) --- */
    .post-page {
        position: absolute;
        width: 250px;
        height: 375px;
        cursor: grab;
        background-color: transparent; /* Start with transparent background */
        background-image: var(--bg-image);
        background-size: cover;
        background-position: center;
        border: 2px solid white;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        opacity: 0;
        transform: scale(0.5);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), 
                    opacity 0.4s ease,
                    box-shadow 0.3s ease,
                    width 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                    height 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                    top 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                    left 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                    background-color 0.4s ease, /* ADDED: For smooth transition */
                    border-color 0.4s ease;
    }
    
    .post-page.is-visible { opacity: 1; transform: scale(1) rotate(var(--r, 0deg)); }
    .post-page:hover { box-shadow: 0 15px 45px rgba(0,0,0,0.5); transform: scale(1.03) rotate(var(--r, 0deg)); z-index: 4000 !important; }
    .post-page.is-dragging { cursor: grabbing; box-shadow: 0 20px 50px rgba(0,0,0,0.6); transform: scale(1.05) rotate(var(--r, 0deg)); pointer-events: none; transition: none; }

    /* --- UPDATED: Expanded Card State --- */
    .post-page.is-expanded {
        top: 50% !important;
        left: 50% !important;
        width: 95vw !important;
        height: 95vh !important;
        transform: translate(-50%, -50%) rotate(0deg) !important;
        cursor: default !important;
        z-index: 5000;
        background-image: none !important; /* MODIFIED: Hide featured image */
        background-color: rgba(30, 30, 30, 0.97); /* ADDED: New background color */
        border-color: rgba(255, 255, 255, 0.5); /* ADDED: Softer border */
    }
    .post-page.is-expanded:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }

    /* --- Content Inside Expanded Card --- */
    .card-content-view {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: transparent;
        color: #fff;
        padding: 5vw;
        overflow-y: auto;
        opacity: 0;
        transition: opacity 0.5s ease 0.3s;
        border-radius: 6px;
    }
    .post-page.is-expanded .card-content-view {
        opacity: 1;
    }
    .card-content-view h1 {
        font-size: clamp(2rem, 5vw, 4.5rem);
        margin: 0 0 2rem 0;
        font-weight: 800;
        line-height: 1.1;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7);
    }
    .post-body-content {
        font-size: clamp(1rem, 1.5vw, 1.2rem);
        line-height: 1.6;
        max-width: 800px; /* MODIFIED: As per request */
        margin-left: auto;   /* Center the content column */
        margin-right: auto;
    }
    .post-body-content p { margin-bottom: 1.5em; }

    .card-close-button {
        position: absolute;
        top: 15px; right: 15px;
        font-size: 2.5rem; font-weight: 300;
        color: #fff; background: none; border: none;
        cursor: pointer; z-index: 10;
        opacity: 0.7;
        transition: opacity 0.3s, transform 0.3s;
        text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    }
    .card-close-button:hover { opacity: 1; transform: scale(1.1); }
    
    /* --- NEW: WordPress Content Styling (Inside Expanded Card) --- */
    .post-body-content img,
    .post-body-content video,
    .post-body-content iframe {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 1.5em auto;
        border-radius: 4px;
    }
    .post-body-content .wp-block-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 1.5em 0;
    }
    .post-body-content .wp-block-gallery figure {
        flex: 1 1 150px;
        margin: 0;
    }
    .post-body-content blockquote {
        border-left: 3px solid #777;
        padding-left: 1.5em;
        margin: 1.5em 0;
        font-style: italic;
        color: #ddd;
    }
    .post-body-content blockquote p {
        margin-bottom: 0;
    }
    /* Handle full and wide alignments within the card */
    .post-body-content .alignwide {
        max-width: 1000px; /* Let it be a bit wider than the text */
        margin-left: auto;
        margin-right: auto;
    }
    .post-body-content .alignfull {
        max-width: none;
        width: 100%; /* Fill the container it's in */
        margin-left: 0;
        margin-right: 0;
    }


    /* --- Add Card Button --- */
    .add-card-button {
        position: fixed;
        bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; font-weight: 300; line-height: 60px; text-align: center;
        cursor: pointer; z-index: 2000; box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        transition: all 0.4s ease;
    }
    .add-card-button:hover { transform: scale(1.1); background-color: #fff; }
    .add-card-button:disabled, .add-card-button.is-disabled { opacity: 0.4; cursor: not-allowed; transform: scale(0.9); pointer-events: none; }
    
    /* --- Hiding UI when card is active --- */
    body.card-is-active .header-content,
    body.card-is-active .add-card-button {
        opacity: 0;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .main-title { font-size: 2.5rem; }
        .main-subtitle { font-size: 1.2rem; }
        .post-page { width: 200px; height: 300px; }
        .add-card-button { bottom: 20px; right: 20px; width: 50px; height: 50px; font-size: 2.5rem; line-height: 48px; }
    }
</style>

<main class="concept-body" id="concept-body">
    <div id="card-viewer-overlay"></div>

    <div class="header-content">
        <header class="main-header">
            <h1 class="main-title">avfstudio</h1>
            <h2 class="main-subtitle">Grow your concept ability</h2>
        </header>
        <button id="open-contact-modal" class="contact-icon-button" aria-label="Open contact form">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
        </button>
    </div>

    <?php
    $initial_card_count = 10;
    $total_posts_to_fetch = 20; 

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $total_posts_to_fetch,
        'orderby'        => 'rand',
        'post_status'    => 'publish',
        'meta_query'     => array( array('key' => '_thumbnail_id') )
    );
    $all_posts_query = new WP_Query($args);
    
    $initial_posts_data = [];
    $additional_posts_data = [];
    $post_index = 0;

    if ($all_posts_query->have_posts()) :
        while ($all_posts_query->have_posts()) : $all_posts_query->the_post();
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            if ($image_url) {
                $post_data = [
                    'title'     => get_the_title(),
                    'content'   => apply_filters('the_content', get_the_content()),
                    'image_url' => esc_url($image_url),
                ];

                if ($post_index < $initial_card_count) {
                    $initial_posts_data[] = $post_data;
                    ?>
                    <div class="post-page"
                         data-index="<?php echo $post_index; ?>" 
                         style="--bg-image: url('<?php echo esc_url($image_url); ?>');">
                    </div>
                    <?php
                } else {
                    $additional_posts_data[] = $post_data;
                }
                $post_index++;
            }
        endwhile;
        wp_reset_postdata();
    endif;
    ?>
</main>

<button id="add-card-button" class="add-card-button" aria-label="Add another card">+</button>

<!-- Contact Modal and other elements remain unchanged -->
<!-- ... -->

<script>
    const initialPostsData = <?php echo json_encode($initial_posts_data); ?>;
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>

<script>
// The JavaScript remains unchanged as all modifications were handled by CSS.
document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    const container = document.getElementById('concept-body');
    const addCardBtn = document.getElementById('add-card-button');
    const viewerOverlay = document.getElementById('card-viewer-overlay');
    const initialCards = document.querySelectorAll('.post-page');
    
    let availablePosts = [...additionalPostsData];
    let highestZ = initialCards.length;
    let expandedCard = null;

    function randomizeInitialLayout() {
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
    
    window.onload = randomizeInitialLayout;

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