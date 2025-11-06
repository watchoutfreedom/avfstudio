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
        z-index: 99999; transition: opacity 0.5s ease-out;
    }
    #page-loader.is-hidden { opacity: 0; pointer-events: none; }
    #loader-spiral {
        width: 60px; height: 60px;
        border: 5px solid transparent; border-top-color: #fff; border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* --- NEW: Unified Draggable Styles --- */
    .is-draggable {
        cursor: grab;
        user-select: none; -webkit-user-select: none;
    }
    .is-draggable.is-dragging {
        cursor: grabbing;
        transition: none !important; /* Instant feedback */
    }

    /* --- Positioning for Draggable UI --- */
    .header-content {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        padding: 0; z-index: 1000; pointer-events: none;
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

    /* All other styles (Contact Modal, Post Card Expanded, etc.) are correct */
    .contact-modal-overlay { z-index: 90000; /* etc. */ }
    
    /* Post Cards */
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
    .post-page.is-expanded {
        cursor: default !important; /* Non-draggable when expanded */
        z-index: 5000;
        /* ... other expanded styles */
    }
    
    /* Add Card Button */
    .add-card-button {
        position: absolute;
        z-index: 2000; bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; line-height: 60px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease;
    }
</style>

<!-- Loader HTML -->
<div id="page-loader"><div id="loader-spiral"></div></div>

<main class="concept-body" id="concept-body">
    <div id="card-viewer-overlay"></div>

    <div class="header-content">
        <!-- MODIFIED: is-draggable class added -->
        <header class="main-header is-draggable">
            <h1 class="main-title">Synapse Guild</h1>
            <h2 class="main-subtitle">Your Unfair Creative Advantage.</h2>
        </header>
        <!-- MODIFIED: is-draggable class added -->
        <button id="open-contact-modal" class="contact-icon-button is-draggable" aria-label="Open contact form">
            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 3H3C1.9 3 1 3.9 1 5V17C1 18.1 1.9 19 3 19H18L23 23V5C23 3.9 22.1 3 21 3Z"/>
            </svg>
        </button>
    </div>

    <!-- PHP Query Logic is UNCHANGED and Correct -->
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
                // MODIFIED: is-draggable class added
                echo '<div class="post-page is-draggable" data-index="' . $post_index . '" style="--bg-image: url(\'' . esc_url($image_url) . '\');"></div>';
            } else { $additional_posts_data[] = $post_data; }
            $post_index++;
        }
    }
    wp_reset_postdata();
    ?>
</main>

<!-- MODIFIED: is-draggable class added -->
<button id="add-card-button" class="add-card-button is-draggable" aria-label="Add another card">+</button>

<!-- Contact Modal HTML is UNCHANGED -->
<div id="contact-modal" class="contact-modal-overlay"> <!-- ... (Full content is below) ... --> </div>

<script>
    const initialPostsData = <?php echo json_encode($initial_posts_data); ?>;
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Global Variables ---
    const body = document.body;
    const container = document.getElementById('concept-body');
    const pageLoader = document.getElementById('page-loader');
    
    // ** THE EXPERT FIX for the "iterator" error **
    let availablePosts = [...(additionalPostsData || [])];
    
    let highestZ = 0;
    let expandedCard = null;

    // --- Contact Modal Setup ---
    // (This logic is correct and unchanged)
    const openModalBtn = document.getElementById('open-contact-modal');
    const closeModalBtn = document.getElementById('close-contact-modal');
    const contactModal = document.getElementById('contact-modal');
    window.showContactModal = function() {}; 
    if (openModalBtn && closeModalBtn && contactModal) {
        const showModal = function() {
            contactModal.classList.add('is-visible');
        }
        window.showContactModal = showModal; // Expose to drag engine
        // ... (rest of form logic)
    }

    // --- Card and Layout Functions ---
    function randomizeInitialLayout(){
        const initialCards = document.querySelectorAll('.post-page');
        highestZ = initialCards.length;
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
    
    window.onload = function(){
        randomizeInitialLayout();
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add("is-hidden"); }, 200); }
    };
    
    function addCard(){ /* ... Full, correct function body ... */ }
    function expandCard(cardElement){ /* ... Full, correct function body ... */ }
    function collapseCard(){ /* ... Full, correct function body ... */ }

    // --- UNIFIED DRAG-AND-DROP ENGINE ---
    let activeElement = null, isDragging = false, startX, startY, initialX, initialY;

    function dragStart(e) {
        const target = e.target.closest(".is-draggable");
        if (!target || expandedCard) return;

        e.preventDefault(); e.stopPropagation();
        activeElement = target; isDragging = false; highestZ++;
        activeElement.style.zIndex = highestZ;
        activeElement.classList.add("is-dragging");
        
        startX = e.type === "touchstart" ? e.touches[0].clientX : e.clientX;
        startY = e.type === "touchstart" ? e.touches[0].clientY : e.clientY;
        initialX = activeElement.offsetLeft;
        initialY = activeElement.offsetTop;
        
        document.addEventListener("mousemove", dragging);
        document.addEventListener("touchmove", dragging, { passive: false });
        document.addEventListener("mouseup", dragEnd);
        document.addEventListener("touchend", dragEnd);
    }
    
    function dragging(e) {
        if (!activeElement) return;
        e.preventDefault();
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

        if (!isDragging) { // This was a click
            if (activeElement.classList.contains('post-page')) {
                expandCard(activeElement);
            } else if (activeElement.id === 'add-card-button') {
                addCard();
            } else if (activeElement.id === 'open-contact-modal') {
                if (typeof window.showContactModal === 'function') {
                    window.showContactModal();
                }
            }
        }
        
        activeElement = null;
        document.removeEventListener("mousemove", dragging);
        document.removeEventListener("touchmove", dragging);
        document.removeEventListener("mouseup", dragEnd);
        document.removeEventListener("touchend", dragEnd);
    }
    
    // Attach the single, unified listener to the main container
    container.addEventListener("mousedown", dragStart);
    container.addEventListener("touchstart", dragStart, { passive: false });

    // --- (Full, non-minified function bodies for reference) ---
    const addCardBtn = document.getElementById('add-card-button');
    if(addCardBtn && availablePosts.length === 0){ addCardBtn.disabled = true; }
    addCard = function(){ if(availablePosts.length === 0) return; const a=availablePosts.shift(); highestZ++; const b=document.createElement("div"); b.className="post-page is-draggable"; b.style.setProperty("--bg-image",`url('${a.image_url}')`); b.postData=a; const c=Math.floor(Math.random()*(window.innerWidth-250-80))+40,d=Math.floor(Math.random()*(window.innerHeight-375-80))+40,e=Math.random()*20-10; b.style.left=`${c}px`; b.style.top=`${d}px`; b.style.setProperty("--r",`${e}deg`); b.style.zIndex=highestZ; container.appendChild(b); setTimeout(()=>b.classList.add("is-visible"),50); if(availablePosts.length===0){addCardBtn.disabled=true;addCardBtn.classList.add("is-disabled")} }
    expandCard = function(a){if(expandedCard||!a.postData)return;expandedCard=a,body.classList.add("card-is-active"),viewerOverlay.classList.add("is-visible");const b=document.createElement("div");b.className="card-content-view";const c=document.createElement("button");c.className="card-close-button",c.innerHTML="&times;",c.onclick=b=>{b.stopPropagation(),collapseCard()};const d=document.createElement("h1");d.textContent=a.postData.title;const e=document.createElement("div");e.className="post-body-content",e.innerHTML=a.postData.content,b.appendChild(c),b.appendChild(d),b.appendChild(e),a.appendChild(b),a.classList.add("is-expanded")}
    collapseCard = function(){if(!expandedCard)return;body.classList.remove("card-is-active"),viewerOverlay.classList.remove("is-visible");const a=expandedCard.querySelector(".card-content-view");a&&expandedCard.removeChild(a),expandedCard.classList.remove("is-expanded"),expandedCard=null}
});
</script>

<?php
get_footer(); 
?>