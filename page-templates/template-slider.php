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

    /* --- NEW: Draggable UI Styles --- */
    .is-ui-draggable {
        cursor: grab;
        user-select: none; -webkit-user-select: none;
    }
    .is-ui-draggable.is-dragging {
        cursor: grabbing;
        transition: none !important; /* Instant feedback */
    }

    /* --- MODIFIED: Positioning for Draggable UI --- */
    .header-content {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        padding: 0;
        z-index: 1000;
        pointer-events: none;
    }
    .header-content > * { pointer-events: all; }
    
    .main-header {
        position: absolute;
        left: 40px; top: 30px; text-align: left;
    }
    .main-title { font-size: 4rem; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; font-weight: 300; margin: 0; color: #bbb; }
    
    .contact-icon-button {
        position: absolute;
        right: 40px; top: 30px;
        background: none; border: none; padding: 10px;
    }
    .contact-icon-button svg { width: 32px; height: 32px; fill: #f0f0f0; transition: transform 0.3s ease; }

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
    
    /* --- MODIFIED: Add Card Button --- */
    .add-card-button {
        position: absolute; /* MODIFIED */
        z-index: 2000; bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; line-height: 60px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease;
    }
    
    /* All other styles are unchanged */
    #card-viewer-overlay, .contact-modal-overlay, .card-content-view, .post-body-content, .card-close-button { /* ... */ }
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
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
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
<div id="contact-modal" class="contact-modal-overlay"> <!-- ... --> </div>

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
    window.showContactModal = function(){}; // Expose for the drag engine
    if (openModalBtn && closeModalBtn && contactModal) {
        const captchaQ1 = document.getElementById('captcha-q1'), captchaQ2 = document.getElementById('captcha-q2'), captchaInput = document.getElementById('captcha-input');
        let captchaAnswer = 7;
        const showModal = function() {
            const n1 = Math.floor(Math.random() * 5) + 1, n2 = Math.floor(Math.random() * 5) + 1;
            if(captchaQ1 && captchaQ2) { captchaQ1.textContent = n1; captchaQ2.textContent = n2; }
            captchaAnswer = n1 + n2;
            if(captchaInput) captchaInput.value = '';
            contactModal.classList.add('is-visible');
        }
        window.showContactModal = showModal;
        const hideModal = function() { contactModal.classList.remove('is-visible'); }
        closeModalBtn.addEventListener('click', hideModal);
        contactModal.addEventListener('click', function(e) { if(e.target === contactModal) hideModal(); });
        const contactForm = document.getElementById('contact-form');
        if(contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const statusDiv = document.getElementById('form-status');
                if (parseInt(captchaInput.value, 10) !== captchaAnswer) { statusDiv.textContent = 'Incorrect captcha answer.'; statusDiv.style.color = 'red'; return; }
                statusDiv.textContent = 'Sending...'; statusDiv.style.color = 'blue';
                setTimeout(() => { statusDiv.textContent = 'Thank you!'; statusDiv.style.color = 'green'; setTimeout(hideModal, 2000); }, 1500);
            });
        }
    }

    // --- Card and Layout Functions (from your working code) ---
    function randomizeInitialLayout(){
        initialCards.forEach((card, index) => {
            card.postData = initialPostsData[index];
            const randomX = Math.floor(Math.random() * (window.innerWidth - 250 - 80)) + 40;
            const randomY = Math.floor(Math.random() * (window.innerHeight - 375 - 80)) + 40;
            const randomRot = Math.random() * 20 - 10;
            card.style.left = `${randomX}px`;
            card.style.top = `${randomY}px`;
            card.style.setProperty("--r", `${randomRot}deg`);
            card.style.zIndex = index + 1;
            setTimeout(() => card.classList.add("is-visible"), index * 80);
        });
    }
    
    window.onload = function() {
        randomizeInitialLayout();
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add("is-hidden"); }, 200); }
    };
    
    if (addCardBtn) {
        if(availablePosts.length === 0) { addCardBtn.disabled = true; addCardBtn.classList.add("is-disabled"); }
    }
    
    function addCard() {
        if(availablePosts.length === 0) return;
        const postData = availablePosts.shift();
        highestZ++;
        const card = document.createElement("div");
        card.className = "post-page";
        card.style.setProperty("--bg-image", `url('${postData.image_url}')`);
        card.postData = postData;
        const randomX = Math.floor(Math.random() * (window.innerWidth - 250 - 80)) + 40;
        const randomY = Math.floor(Math.random() * (window.innerHeight - 375 - 80)) + 40;
        const randomRot = Math.random() * 20 - 10;
        card.style.left = `${randomX}px`; card.style.top = `${randomY}px`; card.style.setProperty("--r", `${randomRot}deg`); card.style.zIndex = highestZ;
        container.appendChild(card);
        setTimeout(() => card.classList.add("is-visible"), 50);
        if(availablePosts.length === 0) { addCardBtn.disabled = true; addCardBtn.classList.add("is-disabled"); }
    }

    function expandCard(cardElement) {
        if(expandedCard || !cardElement.postData) return;
        expandedCard = cardElement;
        body.classList.add("card-is-active");
        viewerOverlay.classList.add("is-visible");
        const contentView = document.createElement("div");
        contentView.className = "card-content-view";
        const closeButton = document.createElement("button");
        closeButton.className = "card-close-button";
        closeButton.innerHTML = "&times;";
        closeButton.onclick = (e) => { e.stopPropagation(); collapseCard(); };
        const title = document.createElement("h1");
        title.textContent = cardElement.postData.title;
        const bodyContent = document.createElement("div");
        bodyContent.className = "post-body-content";
        bodyContent.innerHTML = cardElement.postData.content;
        contentView.appendChild(closeButton); contentView.appendChild(title); contentView.appendChild(bodyContent);
        cardElement.appendChild(contentView);
        cardElement.classList.add("is-expanded");
    }

    function collapseCard() {
        if(!expandedCard) return;
        body.classList.remove("card-is-active");
        viewerOverlay.classList.remove("is-visible");
        const contentView = expandedCard.querySelector(".card-content-view");
        if(contentView) expandedCard.removeChild(contentView);
        expandedCard.classList.remove("is-expanded");
        expandedCard = null;
    }

    // --- Original Card Drag Engine (from your working code) ---
    let activeCard = null, isDragging = false, startX, startY, initialX, initialY;
    function dragStart(e) {
        if(expandedCard) return;
        const targetCard = e.target.closest(".post-page");
        if(targetCard) {
            e.preventDefault(); e.stopPropagation();
            activeCard = targetCard;
            isDragging = false;
            highestZ++;
            activeCard.style.zIndex = highestZ;
            activeCard.classList.add("is-dragging");
            startX = e.type === "touchstart" ? e.touches[0].clientX : e.clientX;
            startY = e.type === "touchstart" ? e.touches[0].clientY : e.clientY;
            initialX = activeCard.offsetLeft;
            initialY = activeCard.offsetTop;
            document.addEventListener("mousemove", dragging);
            document.addEventListener("touchmove", dragging, { passive: false });
            document.addEventListener("mouseup", dragEnd);
            document.addEventListener("touchend", dragEnd);
        }
    }
    function dragging(e) {
        if(!activeCard) return;
        e.preventDefault();
        let currentX = e.type === "touchmove" ? e.touches[0].clientX : e.clientX;
        let currentY = e.type === "touchmove" ? e.touches[0].clientY : e.clientY;
        const deltaX = currentX - startX; const deltaY = currentY - startY;
        if(Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) isDragging = true;
        if(isDragging) {
            activeCard.style.left = `${initialX + deltaX}px`;
            activeCard.style.top = `${initialY + deltaY}px`;
        }
    }
    function dragEnd() {
        if(!activeCard) return;
        document.removeEventListener("mousemove", dragging);
        document.removeEventListener("touchmove", dragging);
        document.removeEventListener("mouseup", dragEnd);
        document.removeEventListener("touchend", dragEnd);
        if(!isDragging) expandCard(activeCard);
        activeCard.classList.remove("is-dragging");
        activeCard = null;
    }
    container.addEventListener("mousedown", dragStart);
    container.addEventListener("touchstart", dragStart, { passive: false });

    // --- NEW, SEPARATE, AND SAFE DRAG ENGINE FOR UI ELEMENTS ---
    const draggableUI = document.querySelectorAll('.is-ui-draggable');
    let activeUIElement = null, isUIDragging = false, uiStartX, uiStartY, uiInitialX, uiInitialY;
    let uiHighestZ = 2001; 

    draggableUI.forEach(el => {
        const uiDragStart = (e) => {
            if (expandedCard) return;
            // Prevent card drag from firing
            e.stopPropagation(); 
            
            activeUIElement = el;
            isUIDragging = false;
            uiHighestZ++;
            activeUIElement.style.zIndex = uiHighestZ;
            activeUIElement.classList.add('is-dragging');
            const event = e.type === 'touchstart' ? e.touches[0] : e;
            uiStartX = event.clientX;
            uiStartY = event.clientY;
            uiInitialX = activeUIElement.offsetLeft;
            uiInitialY = activeUIElement.offsetTop;
            document.addEventListener('mousemove', uiDragging);
            document.addEventListener('touchmove', uiDragging, { passive: false });
            document.addEventListener('mouseup', uiDragEnd);
            document.addEventListener('touchend', uiDragEnd);
        };

        const uiDragging = (e) => {
            if (!activeUIElement) return;
            e.preventDefault();
            const event = e.type === 'touchmove' ? e.touches[0] : e;
            const deltaX = event.clientX - uiStartX;
            const deltaY = event.clientY - uiStartY;
            if (Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) {
                isUIDragging = true;
            }
            activeUIElement.style.left = `${uiInitialX + deltaX}px`;
            activeUIElement.style.top = `${uiInitialY + deltaY}px`;
        };

        const uiDragEnd = () => {
            if (!activeUIElement) return;
            activeUIElement.classList.remove('is-dragging');

            if (!isUIDragging) { // This was a click, not a drag
                if (activeUIElement.id === 'add-card-button') {
                    addCard();
                } else if (activeUIElement.id === 'open-contact-modal') {
                    if (typeof window.showContactModal === 'function') {
                        window.showContactModal();
                    }
                }
            }

            activeUIElement = null;
            document.removeEventListener('mousemove', uiDragging);
            document.removeEventListener('touchmove', uiDragging);
            document.removeEventListener('mouseup', uiDragEnd);
            document.removeEventListener('touchend', uiDragEnd);
        };

        el.addEventListener('mousedown', uiDragStart);
        el.addEventListener('touchstart', uiDragStart, { passive: false });
    });
});
</script>

<?php
get_footer(); 
?>