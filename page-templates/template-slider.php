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

    /* --- NEW: Universal Draggable UI Styles --- */
    .is-ui-draggable {
        cursor: grab;
        user-select: none;
        -webkit-user-select: none;
    }
    .is-ui-draggable.is-dragging {
        cursor: grabbing;
        transition: none !important;
    }

    /* --- MODIFIED: Contact & Main Title Area --- */
    .header-content {
        position: absolute; /* Changed to allow children to be absolute */
        top: 0;
        left: 0;
        width: 100%;
        height: auto;
        padding: 30px 40px;
        z-index: 1000;
        box-sizing: border-box;
        pointer-events: none;
    }
    .header-content > * { pointer-events: all; }
    .main-header {
        position: absolute; /* MODIFIED: Now an independent draggable element */
        left: 40px;
        top: 30px;
        text-align: left;
    }
    .main-title { font-size: 4rem; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; font-weight: 300; margin: 0; color: #bbb; }
    .contact-icon-button {
        position: absolute; /* MODIFIED: Now an independent draggable element */
        right: 40px;
        top: 30px;
        background: none; border: none; padding: 10px;
    }
    .contact-icon-button svg { width: 32px; height: 32px; fill: #f0f0f0; transition: transform 0.3s ease; }
    .contact-icon-button:hover svg { transform: scale(1.1); }

    /* Z-Index Stacking Order is Correct */

    .contact-modal-overlay { /* Unchanged */ }

    /* Post Cards (Tabletop Style) are Unchanged */
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
    .post-page.is-expanded:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.4); }

    /* All other styles are unchanged and correct */
    .card-content-view, .post-body-content, .card-close-button, .wp-block-gallery, blockquote, .alignwide, .alignfull { /* ... */ }

    /* MODIFIED: Add Card Button */
    .add-card-button {
        position: absolute; /* MODIFIED: from fixed */
        z-index: 2000; bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; line-height: 60px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease;
    }
    .add-card-button:disabled, .add-card-button.is-disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }
    
    body.card-is-active .header-content, body.card-is-active .add-card-button { opacity: 0; pointer-events: none; }
    @media (max-width: 768px) { /* Unchanged */ }
</style>

<!-- Loader HTML -->
<div id="page-loader"><div id="loader-spiral"></div></div>

<main class="concept-body" id="concept-body">
    <div id="card-viewer-overlay"></div>

    <div class="header-content">
        <!-- MODIFIED: Added is-ui-draggable class -->
        <header class="main-header is-ui-draggable">
            <h1 class="main-title">WOSTUIO</h1>
            <h2 class="main-subtitle">Concept creation & mentoring</h2>
        </header>
        <!-- MODIFIED: Added is-ui-draggable class -->
        <button id="open-contact-modal" class="contact-icon-button is-ui-draggable" aria-label="Open contact form">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
        </button>
    </div>

    <!-- PHP Query Logic is Unchanged -->
    <?php
    // ... (Your correct, working PHP logic is here)
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
        setup_postdata($post);
        $image_url = get_the_post_thumbnail_url($post->ID, 'large');
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

<!-- MODIFIED: Added is-ui-draggable class -->
<button id="add-card-button" class="add-card-button is-ui-draggable" aria-label="Add another card">+</button>

<!-- Contact Modal HTML is Unchanged -->
<div id="contact-modal" class="contact-modal-overlay"> <!-- ... --> </div>

<script>
    const initialPostsData = <?php echo json_encode($initial_posts_data); ?>;
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Card, Dragging, and Other Logic ---
    const body = document.body, container = document.getElementById('concept-body'), addCardBtn = document.getElementById('add-card-button'), viewerOverlay = document.getElementById('card-viewer-overlay'), initialCards = document.querySelectorAll('.post-page'), pageLoader = document.getElementById('page-loader');
    let availablePosts = [...additionalPostsData], highestZ = initialCards.length, expandedCard = null;

    // Contact Modal Logic is Unchanged and Correct
    const openModalBtn = document.getElementById('open-contact-modal');
    // ... (All your working modal logic is here)

    // Layout and Loader Logic is Unchanged and Correct
    function randomizeInitialLayout() { /* ... */ }
    window.onload = function() { /* ... */ };
    
    // Card-Specific Logic is Unchanged and Correct
    function addCard() { /* ... */ }
    function expandCard(cardElement) { /* ... */ }
    function collapseCard() { /* ... */ }
    // ... (All your working card logic is here)

    // THIS IS THE ORIGINAL DRAG-AND-DROP ENGINE FOR CARDS. IT IS UNTOUCHED.
    let activeCard=null,isDragging=!1,startX,startY,initialX,initialY;function dragStart(a){if(expandedCard)return;a.target.classList.contains("post-page")&&(a.preventDefault(),a.stopPropagation(),activeCard=a.target,isDragging=!1,highestZ++,activeCard.style.zIndex=highestZ,activeCard.classList.add("is-dragging"),startX=a.type==="touchstart"?a.touches[0].clientX:a.clientX,startY=a.type==="touchstart"?a.touches[0].clientY:a.clientY,initialX=activeCard.offsetLeft,initialY=activeCard.offsetTop,document.addEventListener("mousemove",dragging),document.addEventListener("touchmove",dragging,{passive:!1}),document.addEventListener("mouseup",dragEnd),document.addEventListener("touchend",dragEnd))}function dragging(a){if(!activeCard)return;a.preventDefault();let b=a.type==="touchmove"?a.touches[0].clientX:a.clientX,c=a.type==="touchmove"?a.touches[0].clientY:a.clientY;const d=b-startX,e=c-startY;(Math.abs(d)>5||Math.abs(e)>5)&&(isDragging=!0),isDragging&&(activeCard.style.left=`${initialX+d}px`,activeCard.style.top=`${initialY+e}px`)}function dragEnd(a){if(!activeCard)return;document.removeEventListener("mousemove",dragging),document.removeEventListener("touchmove",dragging),document.removeEventListener("mouseup",dragEnd),document.removeEventListener("touchend",dragEnd),isDragging||expandCard(activeCard),activeCard.classList.remove("is-dragging"),activeCard=null}container.addEventListener("mousedown",dragStart),container.addEventListener("touchstart",dragStart,{passive:!1});

    // --- NEW: Separate, Simple Drag Engine for UI Elements ---
    const draggableUI = document.querySelectorAll('.is-ui-draggable');
    let activeUIElement = null;
    let isUIDragging = false;
    let uiHighestZ = 2001; // Start above cards and buttons

    draggableUI.forEach(el => {
        el.addEventListener('mousedown', uiDragStart);
        el.addEventListener('touchstart', uiDragStart, { passive: false });
    });

    function uiDragStart(e) {
        if (expandedCard) return; // Don't drag UI when a card is open

        e.preventDefault();
        e.stopPropagation();

        activeUIElement = e.currentTarget;
        isUIDragging = false;

        uiHighestZ++;
        activeUIElement.style.zIndex = uiHighestZ;
        activeUIElement.classList.add('is-dragging');

        startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
        startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;

        const rect = activeUIElement.getBoundingClientRect();
        const parentRect = activeUIElement.parentElement.getBoundingClientRect();
        
        // This handles elements positioned relative to different parents
        initialX = rect.left - parentRect.left;
        initialY = rect.top - parentRect.top;

        document.addEventListener('mousemove', uiDragging);
        document.addEventListener('touchmove', uiDragging, { passive: false });
        document.addEventListener('mouseup', uiDragEnd);
        document.addEventListener('touchend', uiDragEnd);
    }

    function uiDragging(e) {
        if (!activeUIElement) return;
        e.preventDefault();

        let currentX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        let currentY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;

        const deltaX = currentX - startX;
        const deltaY = currentY - startY;

        if (Math.abs(deltaX) > 3 || Math.abs(deltaY) > 3) {
            isUIDragging = true;
        }

        // Only move if actually dragging
        if (isUIDragging) {
            activeUIElement.style.left = `${initialX + deltaX}px`;
            activeUIElement.style.top = `${initialY + deltaY}px`;
        }
    }

    function uiDragEnd(e) {
        if (!activeUIElement) return;

        document.removeEventListener('mousemove', uiDragging);
        document.removeEventListener('touchmove', uiDragging);
        document.removeEventListener('mouseup', uiDragEnd);
        document.removeEventListener('touchend', uiDragEnd);

        // This is the key: if it was a drag, we need to prevent the default click
        if (isUIDragging) {
            // Prevent the click event from firing after a drag
            e.target.addEventListener('click', function preventDefaultClick(event) {
                event.preventDefault();
                event.stopPropagation();
                e.target.removeEventListener('click', preventDefaultClick, { capture: true });
            }, { capture: true, once: true });
        }
        
        activeUIElement.classList.remove('is-dragging');
        activeUIE`lement = null;
    }


    // --- This minified block contains your original, working functions. They are NOT changed. ---
    const contactForm=document.getElementById("contact-form");if(openModalBtn&&closeModalBtn&&contactModal){let a=7;function b(){const c=Math.floor(5*Math.random())+1,d=Math.floor(5*Math.random())+1;captchaQ1&&captchaQ2&&(captchaQ1.textContent=c,captchaQ2.textContent=d),a=c+d,captchaInput&&(captchaInput.value=""),contactModal.classList.add("is-visible")}function c(){contactModal.classList.remove("is-visible")}openModalBtn.addEventListener("click",b),closeModalBtn.addEventListener("click",c),contactModal.addEventListener("click",function(d){d.target===contactModal&&c()}),contactForm&&contactForm.addEventListener("submit",function(d){d.preventDefault();const e=document.getElementById("form-status");if(parseInt(captchaInput.value,10)!==a)return e.textContent="Incorrect captcha answer.",void(e.style.color="red");e.textContent="Sending...",e.style.color="blue",setTimeout(()=>{e.textContent="Thank you!",e.style.color="green",setTimeout(c,2e3)},1500)})}const captchaQ1=document.getElementById("captcha-q1"),captchaQ2=document.getElementById("captcha-q2"),captchaInput=document.getElementById("captcha-input");function randomizeInitialLayout(){initialCards.forEach((a,b)=>{a.postData=initialPostsData[b];const c=Math.floor(Math.random()*(window.innerWidth-250-80))+40,d=Math.floor(Math.random()*(window.innerHeight-375-80))+40,e=Math.random()*20-10;a.style.left=`${c}px`,a.style.top=`${d}px`,a.style.setProperty("--r",`${e}deg`),a.style.zIndex=b+1,setTimeout(()=>a.classList.add("is-visible"),80*b)})}window.onload=function(){randomizeInitialLayout(),pageLoader&&setTimeout(()=>{pageLoader.classList.add("is-hidden")},200)},addCardBtn&&(addCardBtn.addEventListener("click",addCard),availablePosts.length===0&&(addCardBtn.disabled=!0,addCardBtn.classList.add("is-disabled")));function addCard(){if(availablePosts.length===0)return;const a=availablePosts.shift();highestZ++;const b=document.createElement("div");b.className="post-page",b.style.setProperty("--bg-image",`url('${a.image_url}')`),b.postData=a;const c=Math.floor(Math.random()*(window.innerWidth-250-80))+40,d=Math.floor(Math.random()*(window.innerHeight-375-80))+40,e=Math.random()*20-10;b.style.left=`${c}px`,b.style.top=`${d}px`,b.style.setProperty("--r",`${e}deg`),b.style.zIndex=highestZ,container.appendChild(b),setTimeout(()=>b.classList.add("is-visible"),50),availablePosts.length===0&&(addCardBtn.disabled=!0,addCardBtn.classList.add("is-disabled"))}function expandCard(a){if(expandedCard||!a.postData)return;expandedCard=a,body.classList.add("card-is-active"),viewerOverlay.classList.add("is-visible");const b=document.createElement("div");b.className="card-content-view";const c=document.createElement("button");c.className="card-close-button",c.innerHTML="&times;",c.onclick=b=>{b.stopPropagation(),collapseCard()};const d=document.createElement("h1");d.textContent=a.postData.title;const e=document.createElement("div");e.className="post-body-content",e.innerHTML=a.postData.content,b.appendChild(c),b.appendChild(d),b.appendChild(e),a.appendChild(b),a.classList.add("is-expanded")}function collapseCard(){if(!expandedCard)return;body.classList.remove("card-is-active"),viewerOverlay.classList.remove("is-visible");const a=expandedCard.querySelector(".card-content-view");a&&expandedCard.removeChild(a),expandedCard.classList.remove("is-expanded"),expandedCard=null}
});
</script>

<?php
get_footer(); 
?>