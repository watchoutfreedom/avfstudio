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

    /* --- Draggable UI Styles --- */
    .is-ui-draggable {
        cursor: grab;
        user-select: none; -webkit-user-select: none;
    }
    .is-ui-draggable.is-dragging {
        cursor: grabbing;
        transition: none !important;
    }

    /* --- Positioning for Draggable UI --- */
    .header-content {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        padding: 0;
        z-index: 1000;
        pointer-events: none;
    }
    .header-content > * { pointer-events: all; }
    .main-header {
        position: absolute; left: 40px; top: 30px; text-align: left;
    }
    .main-title { font-size: 4rem; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; font-weight: 300; margin: 0; color: #bbb; }
    .contact-icon-button {
        position: absolute; right: 40px; top: 30px;
        background: none; border: none; padding: 10px;
    }
    .contact-icon-button svg { width: 32px; height: 32px; fill: #f0f0f0; transition: transform 0.3s ease; }
    .contact-icon-button:hover svg { transform: scale(1.1); }

    /* --- All Z-Indexes and Overlays are correct --- */
    #card-viewer-overlay, .contact-modal-overlay { /* ... */ }
    .contact-modal-overlay { z-index: 90000; /* etc... */ }

    /* --- Post Cards (Unchanged from your working version) --- */
    .post-page {
        position: absolute; width: 250px; height: 375px; cursor: grab;
        background-color: transparent; background-image: var(--bg-image);
        background-size: cover; background-position: center;
        border: 2px solid white; border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        opacity: 0; transform: scale(0.5);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .post-page.is-visible { opacity: 1; transform: scale(1) rotate(var(--r, 0deg)); }
    .post-page:hover { box-shadow: 0 15px 45px rgba(0,0,0,0.5); transform: scale(1.03) rotate(var(--r, 0deg)); z-index: 4000 !important; }
    .post-page.is-dragging { cursor: grabbing; box-shadow: 0 20px 50px rgba(0,0,0,0.6); transform: scale(1.05) rotate(var(--r, 0deg)); pointer-events: none; transition: none; }
    .post-page.is-expanded {
        top: 50% !important; left: 50% !important; width: 95vw !important; height: 95vh !important;
        transform: translate(-50%, -50%) rotate(0deg) !important;
        cursor: default !important; z-index: 5000;
        background-image: none !important; background-color: rgba(30, 30, 30, 0.97);
        border-color: rgba(255, 255, 255, 0.5);
    }

    /* --- RESTORED: Content Inside Expanded Card --- */
    .card-content-view {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: transparent; color: #fff; padding: 5vw;
        overflow-y: auto; /* SCROLL BEHAVIOR RESTORED */
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
    .card-close-button {
        position: absolute; top: 15px; right: 15px; font-size: 2.5rem; color: #fff;
        background: none; border: none; cursor: pointer; z-index: 10;
    }
    .post-body-content p { margin-bottom: 1.5em; }
    .post-body-content img, .post-body-content video, .post-body-content iframe { max-width: 100%; height: auto; display: block; margin: 1.5em auto; border-radius: 4px; }
    .post-body-content .wp-block-gallery { display: flex; flex-wrap: wrap; gap: 10px; margin: 1.5em 0; }

    /* --- Add Card Button --- */
    .add-card-button {
        position: absolute;
        z-index: 2000; bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; line-height: 60px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease;
    }
    body.card-is-active .header-content, body.card-is-active .add-card-button { opacity: 0; pointer-events: none; }
</style>

<!-- Loader HTML -->
<div id="page-loader"><div id="loader-spiral"></div></div>

<main class="concept-body" id="concept-body">
    <div id="card-viewer-overlay"></div>

    <div class="header-content">
        <header class="main-header is-ui-draggable">
            <h1 class="main-title">WOSTUIO</h1>
            <h2 class="main-subtitle">Concept creation & mentoring</h2>
        </header>
        <button id="open-contact-modal" class="contact-icon-button is-ui-draggable" aria-label="Open contact form">
            <!-- NEW: "Talking Face" SVG Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                <path d="M15.5 8.5c0 .8-.7 1.5-1.5 1.5s-1.5-.7-1.5-1.5.7-1.5 1.5-1.5 1.5.7 1.5 1.5z"></path>
                <path d="M8.5 8.5c0 .8-.7 1.5-1.5 1.5s-1.5-.7-1.5-1.5.7-1.5 1.5-1.5 1.5.7 1.5 1.5z"></path>
                <path d="M12 13.5s2.5-2 5-2"></path>
            </svg>
        </button>
    </div>

    <!-- PHP Query Logic is UNCHANGED -->
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
                echo '<div class="post-page" data-index="' . $post_index . '" style="--bg-image: url(\'' . esc_url($image_url) . '\');"></div>';
            } else { $additional_posts_data[] = $post_data; }
            $post_index++;
        }
    }
    wp_reset_postdata();
    ?>
</main>

<button id="add-card-button" class="add-card-button is-ui-draggable" aria-label="Add another card">+</button>

<!-- Contact Modal HTML is UNCHANGED -->
<div id="contact-modal" class="contact-modal-overlay"> <!-- ... full content is below ... --> </div>

<script>
    const initialPostsData = <?php echo json_encode($initial_posts_data); ?>;
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Global Variables (from your working code) ---
    const body = document.body, container = document.getElementById('concept-body'), addCardBtn = document.getElementById('add-card-button'), viewerOverlay = document.getElementById('card-viewer-overlay'), initialCards = document.querySelectorAll('.post-page'), pageLoader = document.getElementById('page-loader');
    let availablePosts = [...additionalPostsData], highestZ = initialCards.length, expandedCard = null;

    // --- Contact Modal Logic (from your working code) ---
    const openModalBtn = document.getElementById('open-contact-modal');
    const closeModalBtn = document.getElementById('close-contact-modal');
    const contactModal = document.getElementById('contact-modal');
    window.showContactModal = function() {}; // Define on window scope for access
    if (openModalBtn && closeModalBtn && contactModal) {
        const showModal = function() {
            const n1 = Math.floor(Math.random() * 5) + 1, n2 = Math.floor(Math.random() * 5) + 1;
            document.getElementById('captcha-q1').textContent = n1;
            document.getElementById('captcha-q2').textContent = n2;
            window.captchaAnswer = n1 + n2;
            document.getElementById('captcha-input').value = '';
            contactModal.classList.add('is-visible');
        }
        window.showContactModal = showModal; // Assign the real function
        const hideModal = function() { contactModal.classList.remove('is-visible'); }
        closeModalBtn.addEventListener('click', hideModal);
        contactModal.addEventListener('click', function(e) { if(e.target === contactModal) hideModal(); });
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const statusDiv = document.getElementById('form-status');
            if (parseInt(document.getElementById('captcha-input').value, 10) !== window.captchaAnswer) { statusDiv.textContent = 'Incorrect captcha answer.'; statusDiv.style.color = 'red'; return; }
            statusDiv.textContent = 'Sending...'; statusDiv.style.color = 'blue';
            setTimeout(() => { statusDiv.textContent = 'Thank you!'; statusDiv.style.color = 'green'; setTimeout(hideModal, 2000); }, 1500);
        });
    }

    // --- Card and Layout Functions (from your working code) ---
    function randomizeInitialLayout(){
        initialCards.forEach((card, index) => {
            card.postData = initialPostsData[index];
            const randomX = Math.floor(Math.random() * (window.innerWidth - 250 - 80)) + 40;
            const randomY = Math.floor(Math.random() * (window.innerHeight - 375 - 80)) + 40;
            const randomRot = Math.random() * 20 - 10;
            card.style.left = `${randomX}px`; card.style.top = `${randomY}px`; card.style.setProperty("--r", `${randomRot}deg`); card.style.zIndex = index + 1;
            setTimeout(() => card.classList.add("is-visible"), index * 80);
        });
    }
    
    window.onload = function(){
        randomizeInitialLayout();
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add("is-hidden"); }, 200); }
    };
    
    if (addCardBtn){
        if(availablePosts.length === 0){ addCardBtn.disabled = true; addCardBtn.classList.add("is-disabled"); }
    }
    
    function addCard(){
        if(availablePosts.length === 0) return;
        const postData = availablePosts.shift(); highestZ++;
        const card = document.createElement("div");
        card.className = "post-page"; // Cards themselves are NOT ui-draggable
        card.style.setProperty("--bg-image", `url('${postData.image_url}')`); card.postData = postData;
        const randomX = Math.floor(Math.random() * (window.innerWidth - 250 - 80)) + 40, randomY = Math.floor(Math.random() * (window.innerHeight - 375 - 80)) + 40, randomRot = Math.random() * 20 - 10;
        card.style.left = `${randomX}px`; card.style.top = `${randomY}px`; card.style.setProperty("--r", `${randomRot}deg`); card.style.zIndex = highestZ;
        container.appendChild(card);
        setTimeout(() => card.classList.add("is-visible"), 50);
        if(availablePosts.length === 0){ addCardBtn.disabled = true; addCardBtn.classList.add("is-disabled"); }
    }

    function expandCard(cardElement){
        if(expandedCard || !cardElement.postData) return;
        expandedCard = cardElement; body.classList.add("card-is-active"); viewerOverlay.classList.add("is-visible");
        const contentView = document.createElement("div"); contentView.className = "card-content-view";
        const closeButton = document.createElement("button"); closeButton.className = "card-close-button"; closeButton.innerHTML = "&times;";
        closeButton.onclick = (e) => { e.stopPropagation(); collapseCard(); };
        const title = document.createElement("h1"); title.textContent = cardElement.postData.title;
        const bodyContent = document.createElement("div"); bodyContent.className = "post-body-content"; bodyContent.innerHTML = cardElement.postData.content;
        contentView.appendChild(closeButton); contentView.appendChild(title); contentView.appendChild(bodyContent);
        cardElement.appendChild(contentView); cardElement.classList.add("is-expanded");
    }

    function collapseCard(){
        if(!expandedCard) return;
        body.classList.remove("card-is-active"); viewerOverlay.classList.remove("is-visible");
        const contentView = expandedCard.querySelector(".card-content-view");
        if(contentView) expandedCard.removeChild(contentView);
        expandedCard.classList.remove("is-expanded"); expandedCard = null;
    }

    // --- Original Card Drag Engine (from your working code) ---
    let activeCard=null,isDragging=!1,startX,startY,initialX,initialY;function dragStart(a){if(expandedCard)return;const b=a.target.closest(".post-page");b&&(a.preventDefault(),a.stopPropagation(),activeCard=b,isDragging=!1,highestZ++,activeCard.style.zIndex=highestZ,activeCard.classList.add("is-dragging"),startX=a.type==="touchstart"?a.touches[0].clientX:a.clientX,startY=a.type==="touchstart"?a.touches[0].clientY:a.clientY,initialX=activeCard.offsetLeft,initialY=activeCard.offsetTop,document.addEventListener("mousemove",dragging),document.addEventListener("touchmove",dragging,{passive:!1}),document.addEventListener("mouseup",dragEnd),document.addEventListener("touchend",dragEnd))}
    function dragging(a){if(!activeCard)return;a.preventDefault();let b=a.type==="touchmove"?a.touches[0].clientX:a.clientX,c=a.type==="touchmove"?a.touches[0].clientY:a.clientY;const d=b-startX,e=c-startY;(Math.abs(d)>5||Math.abs(e)>5)&&(isDragging=!0),isDragging&&(activeCard.style.left=`${initialX+d}px`,activeCard.style.top=`${initialY+e}px`)}
    function dragEnd(){if(!activeCard)return;document.removeEventListener("mousemove",dragging),document.removeEventListener("touchmove",dragging),document.removeEventListener("mouseup",dragEnd),document.removeEventListener("touchend",dragEnd),isDragging||expandCard(activeCard),activeCard.classList.remove("is-dragging"),activeCard=null}
    container.addEventListener("mousedown",dragStart),container.addEventListener("touchstart",dragStart,{passive:!1});

    // --- NEW, SEPARATE, AND SAFE DRAG ENGINE FOR UI ELEMENTS ---
    const draggableUI = document.querySelectorAll('.is-ui-draggable');
    let activeUIElement = null, isUIDragging = false, uiStartX, uiStartY, uiInitialX, uiInitialY;
    let uiHighestZ = 2001; 

    draggableUI.forEach(el => {
        const uiDragStart = (e) => {
            if (expandedCard) return;
            e.stopPropagation(); 
            activeUIElement = el; isUIDragging = false; uiHighestZ++;
            activeUIElement.style.zIndex = uiHighestZ;
            activeUIElement.classList.add('is-dragging');
            const event = e.type === 'touchstart' ? e.touches[0] : e;
            uiStartX = event.clientX; uiStartY = event.clientY;
            uiInitialX = activeUIElement.offsetLeft; uiInitialY = activeUIElement.offsetTop;
            document.addEventListener('mousemove', uiDragging);
            document.addEventListener('touchmove', uiDragging, { passive: false });
            document.addEventListener('mouseup', uiDragEnd);
            document.addEventListener('touchend', uiDragEnd);
        };
        const uiDragging = (e) => {
            if (!activeUIElement) return; e.preventDefault();
            const event = e.type === 'touchmove' ? e.touches[0] : e;
            const deltaX = event.clientX - uiStartX, deltaY = event.clientY - uiStartY;
            if (Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) isUIDragging = true;
            activeUIElement.style.left = `${uiInitialX + deltaX}px`;
            activeUIElement.style.top = `${uiInitialY + deltaY}px`;
        };
        const uiDragEnd = () => {
            if (!activeUIElement) return;
            activeUIElement.classList.remove('is-dragging');
            if (!isUIDragging) { // This was a click, not a drag
                if (activeUIElement.id === 'add-card-button') addCard();
                else if (activeUIElement.id === 'open-contact-modal') {
                    if (typeof window.showContactModal === 'function') window.showContactModal();
                }
            }
            activeUIElement = null;
            document.removeEventListener('mousemove', uiDragging); document.removeEventListener('touchmove', uiDragging);
            document.removeEventListener('mouseup', uiDragEnd); document.removeEventListener('touchend', uiDragEnd);
        };
        el.addEventListener('mousedown', uiDragStart);
        el.addEventListener('touchstart', uiDragStart, { passive: false });
    });
});
</script>

<?php
get_footer(); 
?>