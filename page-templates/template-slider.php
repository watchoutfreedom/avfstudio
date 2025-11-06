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
        height: 100%; width: 100%; margin: 0; padding: 0; overflow: hidden;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    .concept-body {
        height: 100vh; width: 100vw; position: relative;
        background-color: black;
        background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%);
        color: #f0f0f0;
    }

    /* --- Page Loader --- */
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    #page-loader {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%);
        display: flex; justify-content: center; align-items: center;
        z-index: 99999;
        transition: opacity 0.5s ease-out;
    }
    #page-loader.is-hidden { opacity: 0; pointer-events: none; }
    #loader-spiral {
        width: 60px; height: 60px;
        border: 5px solid transparent; border-top-color: #fff; border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    /* --- Main Header (Static) --- */
    .header-content {
        position: relative; z-index: 1000; padding: 30px 40px;
        display: flex; justify-content: space-between; align-items: flex-start;
        pointer-events: none;
    }
    .header-content > * { pointer-events: all; }
    .main-header { text-align: left; }
    .main-title { font-size: 4rem; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; font-weight: 300; margin: 0; color: #bbb; }

    /* --- Z-Index Stacking Order --- */
    #card-viewer-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.8);
        opacity: 0; pointer-events: none;
        transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 4999;
    }
    #card-viewer-overlay.is-visible { opacity: 1; pointer-events: all; }

    /* --- Base Card Styles --- */
    .is-draggable {
        cursor: grab;
        user-select: none; -webkit-user-select: none;
    }
    .is-draggable.is-dragging {
        cursor: grabbing;
        transition: none !important;
    }
    .post-page {
        position: absolute; width: 250px; height: 375px;
        background-color: transparent; background-image: var(--bg-image);
        background-size: cover; background-position: center;
        border: 2px solid white; border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        opacity: 0; transform: scale(0.5);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .post-page.is-visible { opacity: 1; transform: scale(1) rotate(var(--r, 0deg)); }
    .post-page:hover { box-shadow: 0 15px 45px rgba(0,0,0,0.5); transform: scale(1.03) rotate(var(--r, 0deg)); z-index: 4000 !important; }
    .post-page.is-expanded {
        top: 50% !important; left: 50% !important; width: 95vw !important; height: 95vh !important;
        transform: translate(-50%, -50%) rotate(0deg) !important;
        cursor: default !important; z-index: 5000;
        border-color: rgba(255, 255, 255, 0.5);
    }

    /* --- Brand Card & Propose Card Styles (Unchanged) --- */
    .brand-card, .propose-card { /* ... */ }

    /* --- Expanded Card Content (THE EXPERT FIX) --- */
    .card-content-view {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: transparent; color: #fff;
        padding: 5vw; /* Padding on the outer container */
        overflow-y: auto; /* THIS ENABLES SCROLLING */
        opacity: 0;
        transition: opacity 0.5s ease 0.3s;
        border-radius: 6px;
    }
    .post-page.is-expanded { background-image: none !important; background-color: rgba(30, 30, 30, 0.97); }
    .post-page.is-expanded .card-content-view { opacity: 1; }

    /* Main Title inside expanded view */
    .card-content-view h1 {
        font-size: clamp(2rem, 5vw, 4.5rem); margin: 0 0 2rem 0;
    }

    /* This is the main content column for WordPress posts */
    .card-content-view .post-body-content {
        max-width: 850px; /* MODIFIED: As per your request */
        margin: 0 auto; /* Center the column */
        font-size: clamp(1rem, 1.5vw, 1.2rem);
        line-height: 1.6;
    }
    /* These rules PREVENT content from breaking the 850px layout, which allows scrolling */
    .post-body-content p { margin-bottom: 1.5em; }
    .post-body-content img,
    .post-body-content video,
    .post-body-content iframe {
        max-width: 100%; /* CRITICAL: Never be wider than the 850px container */
        height: auto;
        display: block;
        margin: 1.5em auto;
        border-radius: 4px;
    }

    /* Brand content styling is separate and correct */
    .card-content-view .brand-content { /* ... */ }
    
    .card-close-button {
        position: absolute; top: 15px; right: 15px; font-size: 2.5rem; color: #fff;
        background: none; border: none; cursor: pointer; z-index: 10;
    }

    /* --- Add Card Button (Unchanged) --- */
    .add-card-button {
        position: fixed; z-index: 2000; bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; line-height: 60px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease;
        cursor: pointer;
    }
    .add-card-button.is-disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }
</style>

<!-- The rest of your HTML, PHP, and JavaScript is UNCHANGED as it was working correctly. -->
<div id="page-loader"><div id="loader-spiral"></div></div>

<main class="concept-body" id="concept-body">
    <div id="card-viewer-overlay"></div>
    <div class="header-content">
        <header class="main-header">
            <h1 class="main-title">SG</h1>
            <h2 class="main-subtitle">Synapse Guild</h2>
        </header>
    </div>
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
<div id="contact-modal" class="contact-modal-overlay"> <!-- ... --> </div>
<script>
    const initialPostsData = <?php echo json_encode($initial_posts_data); ?>;
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const body = document.body, container = document.getElementById('concept-body'), addCardBtn = document.getElementById('add-card-button'), viewerOverlay = document.getElementById('card-viewer-overlay'), pageLoader = document.getElementById('page-loader');
    let availablePosts = [...(additionalPostsData || [])];
    let highestZ = 0, expandedCard = null, hasThrownFinalCard = false;
    const contactModal = document.getElementById('contact-modal');
    window.showContactModal = function() {
        if(contactModal) contactModal.classList.add('is-visible');
    };
    function randomizeInitialLayout(){
        document.querySelectorAll('.post-page').forEach((card, index) => {
            card.cardData = { type: 'post', ...initialPostsData[index] };
            const randomX = Math.floor(Math.random()*(window.innerWidth-250-80))+40, randomY = Math.floor(Math.random()*(window.innerHeight-375-80))+40, randomRot = Math.random()*20-10;
            card.style.left = `${randomX}px`, card.style.top = `${randomY}px`, card.style.setProperty("--r", `${randomRot}deg`), card.style.zIndex = index + 1;
            setTimeout(() => card.classList.add("is-visible"), index * 80);
        });
        highestZ = document.querySelectorAll('.post-page').length;
        setTimeout(() => {
            const brandCardData = { type: 'brand', title: 'Synapse Guild', slogan: 'Your Unfair Creative Advantage.', content: `<div class="brand-content"><p>In a marketplace of echoes, a powerful, foundational concept is the only true way to stand out. Our studio is a unique collective where philosophers probe the 'why', architects design the structure, and artists give it a soul.</p><a href="#" id="brand-contact-link">+ take your card</a></div>` };
            const brandCard = createCard(brandCardData);
            brandCard.style.left = `calc(50% - 125px)`; brandCard.style.top = `40%`; brandCard.style.setProperty('--r', '-2deg');
            setTimeout(() => brandCard.classList.add("is-visible"), 50);
        }, (highestZ * 80) + 100);
    }
    window.onload = function(){
        randomizeInitialLayout();
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add("is-hidden"); }, 200); }
    };
    if (addCardBtn){
        addCardBtn.addEventListener('click', () => {
            if (availablePosts.length > 0) {
                const postData = { type: 'post', ...availablePosts.shift() };
                const newCard = createCard(postData);
                const randomX=Math.floor(Math.random()*(window.innerWidth-250-80))+40,randomY=Math.floor(Math.random()*(window.innerHeight-375-80))+40,randomRot=Math.random()*20-10;
                newCard.style.left=`${randomX}px`,newCard.style.top=`${randomY}px`,newCard.style.setProperty("--r",`${randomRot}deg`);
                setTimeout(()=>newCard.classList.add("is-visible"),50);
            } else if (!hasThrownFinalCard) {
                const proposeCardData = { type: 'propose', title: '+ propose your concept' };
                const proposeCard = createCard(proposeCardData);
                const randomX=Math.floor(Math.random()*(window.innerWidth-250-80))+40,randomY=Math.floor(Math.random()*(window.innerHeight-375-80))+40,randomRot=Math.random()*20-10;
                proposeCard.style.left=`${randomX}px`,proposeCard.style.top=`${randomY}px`,proposeCard.style.setProperty("--r",`${randomRot}deg`);
                setTimeout(()=>proposeCard.classList.add("is-visible"),50);
                hasThrownFinalCard = true;
                addCardBtn.classList.add("is-disabled");
            }
        });
    }
    const createCard = (data) => {
        const card = document.createElement("div"); card.className = "post-page is-draggable"; card.cardData = data;
        switch (data.type) {
            case 'brand': card.classList.add('brand-card'); card.innerHTML = `<h1>${data.title}</h1><h2>${data.slogan}</h2>`; break;
            case 'propose': card.classList.add('propose-card'); card.innerHTML = `<h3>${data.title}</h3>`; break;
            default: card.style.setProperty("--bg-image", `url('${data.image_url}')`); break;
        }
        highestZ++; card.style.zIndex = highestZ; container.appendChild(card); return card;
    };
    function expandCard(cardElement){
        if(expandedCard || !cardElement.cardData) return;
        expandedCard = cardElement; body.classList.add("card-is-active"); viewerOverlay.classList.add("is-visible");
        const contentView = document.createElement("div"); contentView.className = "card-content-view";
        const closeButton = document.createElement("button"); closeButton.className = "card-close-button"; closeButton.innerHTML = "&times;";
        closeButton.onclick = (e) => { e.stopPropagation(); collapseCard(); };
        const data = cardElement.cardData; let contentHTML = '';
        if (data.type === 'post') { contentHTML = `<h1>${data.title}</h1><div class="post-body-content">${data.content}</div>`; }
        else if (data.type === 'brand') { contentHTML = data.content; }
        contentView.innerHTML = contentHTML; contentView.prepend(closeButton);
        cardElement.appendChild(contentView); cardElement.classList.add("is-expanded");
        const brandContactLink = document.getElementById('brand-contact-link');
        if (brandContactLink) { brandContactLink.onclick = (e) => { e.preventDefault(); window.showContactModal(); } }
    }
    function collapseCard() {
        if (!expandedCard) return;
        body.classList.remove("card-is-active"); viewerOverlay.classList.remove("is-visible");
        const contentView = expandedCard.querySelector(".card-content-view");
        if (contentView) expandedCard.removeChild(contentView);
        expandedCard.classList.remove("is-expanded"); expandedCard = null;
    }
    let activeElement=null, isDragging=false, startX, startY, initialX, initialY;
    function dragStart(e) {
        const target = e.target.closest(".is-draggable");
        if (!target || expandedCard) return;
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
    function dragging(e) {
        if (!activeElement) return; e.preventDefault();
        let currentX = e.type === "touchmove" ? e.touches[0].clientX : e.clientX;
        let currentY = e.type === "touchmove" ? e.touches[0].clientY : e.clientY;
        const deltaX = currentX - startX, deltaY = currentY - startY;
        if (Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) isDragging = true;
        activeElement.style.left = `${initialX + deltaX}px`;
        activeElement.style.top = `${initialY + deltaY}px`;
    }
    function dragEnd() {
        if (!activeElement) return;
        activeElement.classList.remove("is-dragging");
        if (!isDragging) {
            const data = activeElement.cardData;
            if (data) {
                if (data.type === 'post' || data.type === 'brand') expandCard(activeElement);
                else if (data.type === 'propose') window.showContactModal();
            }
        }
        activeElement = null;
        document.removeEventListener("mousemove", dragging);
        document.removeEventListener("touchmove", dragging);
        document.removeEventListener("mouseup", dragEnd);
        document.removeEventListener("touchend", dragEnd);
    }
    container.addEventListener("mousedown", dragStart);
    container.addEventListener("touchstart", dragStart, { passive: false });
});
</script>

<?php
get_footer(); 
?>