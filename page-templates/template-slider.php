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

    /* --- Universal Draggable Styles --- */
    .is-draggable {
        cursor: grab;
        position: relative; /* Allows movement via top/left styles */
        user-select: none; /* Prevents text selection while dragging */
        -webkit-user-select: none;
    }
    .is-draggable.is-dragging {
        cursor: grabbing;
        transition: none !important; /* Instant feedback while dragging */
    }

    /* --- Contact & Main Title Area --- */
    .header-content {
        position: absolute; /* Changed from relative for positioning context */
        top: 30px;
        left: 40px;
        width: calc(100% - 80px); /* Span width to contain elements */
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        pointer-events: none;
        transition: opacity 0.4s ease;
    }
    .header-content > * { pointer-events: all; }
    .main-header { text-align: left; }
    .main-title, .main-subtitle { font-weight: 800; margin: 0; letter-spacing: 2px; }
    .main-title { font-size: 4rem; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; color: #bbb; font-weight: 300; }

    /* NEW: Style for individual draggable letters */
    .draggable-letter {
        display: inline-block; /* Make each letter its own block to move */
        min-width: 0.25em; /* Give spaces some width to be draggable */
    }
    .contact-icon-button { background: none; border: none; padding: 10px; }
    .contact-icon-button svg { width: 32px; height: 32px; fill: #f0f0f0; transition: transform 0.3s ease; }
    .contact-icon-button:hover svg { transform: scale(1.1); }

    /* Z-Index Stacking Order */
    /* Loader: 99999, Contact Modal: 90000, Dragged Item: 5001+, Expanded Card: 5000, Card Viewer Overlay: 4999 */

    /* Contact Modal CSS */
    .contact-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.7); display: none; justify-content: center; align-items: center;
        z-index: 90000; opacity: 0; transition: opacity 0.3s ease;
    }
    .contact-modal-overlay.is-visible { display: flex; opacity: 1; }
    /* ... (rest of contact modal styles are fine) ... */

    /* Post Cards */
    .post-page {
        position: absolute; width: 250px; height: 375px;
        /* is-draggable class handles cursor */
    }
    .post-page.is-expanded {
        /* This state is NOT draggable */
        cursor: default !important;
        z-index: 5000;
    }
    /* ... (rest of post card styles are fine) ... */

    /* Add Card Button */
    .add-card-button {
        position: absolute; /* Changed from fixed to be draggable */
        bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; line-height: 60px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease;
    }
    .add-card-button:disabled { opacity: 0.4; pointer-events: none; }
    
    /* Hiding UI when card is active */
    body.card-is-active .header-content, body.card-is-active .add-card-button {
        opacity: 0; pointer-events: none;
    }

    /* ... (All other styles from your previous version are fine) ... */
</style>

<!-- Loader HTML -->
<div id="page-loader"><div id="loader-spiral"></div></div>

<main class="concept-body" id="concept-body">
    <div id="card-viewer-overlay"></div>

    <div class="header-content">
        <header class="main-header">
            <!-- These will be populated by JavaScript -->
            <h1 class="main-title">avfstudio</h1>
            <h2 class="main-subtitle">Grow your concept ability</h2>
        </header>
        <!-- Added is-draggable class -->
        <button id="open-contact-modal" class="contact-icon-button is-draggable" aria-label="Open contact form">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
        </button>
    </div>

    <!-- PHP Query Logic (Unchanged) -->
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
        setup_postdata($post);
        $image_url = get_the_post_thumbnail_url($post->ID, 'large');
        if ($image_url) {
            $post_data = ['title' => get_the_title($post), 'content' => apply_filters('the_content', $post->post_content), 'image_url' => esc_url($image_url)];
            if ($post_index < $initial_card_count) {
                $initial_posts_data[] = $post_data;
                // Added is-draggable class to cards
                echo '<div class="post-page is-draggable" data-index="' . $post_index . '" style="--bg-image: url(\'' . esc_url($image_url) . '\');"></div>';
            } else { $additional_posts_data[] = $post_data; }
            $post_index++;
        }
    }
    wp_reset_postdata();
    ?>
</main>

<!-- Added is-draggable class -->
<button id="add-card-button" class="add-card-button is-draggable" aria-label="Add another card">+</button>

<!-- Contact Modal HTML (Unchanged) -->
<div id="contact-modal" class="contact-modal-overlay"> <!-- ... --> </div>

<script>
    const initialPostsData = <?php echo json_encode($initial_posts_data); ?>;
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const body = document.body, container = document.getElementById('concept-body');
    const pageLoader = document.getElementById('page-loader');
    
    let highestZ = 100; // Start with a base z-index for draggable items
    let expandedCard = null;

    // --- NEW: Letter Shattering Logic ---
    function wrapLettersInSpans(selector) {
        const element = document.querySelector(selector);
        if (!element) return;
        const text = element.textContent;
        element.innerHTML = ''; // Clear original text
        text.split('').forEach(char => {
            const span = document.createElement('span');
            span.className = 'is-draggable draggable-letter';
            span.textContent = char;
            element.appendChild(span);
        });
    }

    wrapLettersInSpans('.main-title');
    wrapLettersInSpans('.main-subtitle');


    // --- Loader & Layout Initialization ---
    window.onload = function() {
        randomizeInitialLayout();
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add('is-hidden'); }, 200); }
    };
    // ... (randomizeInitialLayout, addCard, expandCard, collapseCard, and contact modal logic are unchanged)

    // --- RE-ENGINEERED: Universal Drag & Drop Engine ---
    let activeCard = null, isDragging = false, startX, startY, initialX, initialY;

    function dragStart(e) {
        // Find the closest draggable parent
        const target = e.target.closest('.is-draggable');

        // Only start drag if a draggable item is found AND no card is expanded
        if (target && !expandedCard) {
            e.preventDefault();
            e.stopPropagation();

            activeCard = target;
            isDragging = false;
            
            // Bring element to the front
            highestZ++;
            activeCard.style.zIndex = highestZ;
            activeCard.classList.add('is-dragging');

            startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
            startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
            
            // Get current computed top/left. This is more robust.
            const rect = activeCard.getBoundingClientRect();
            const parentRect = activeCard.parentElement.getBoundingClientRect();
            initialX = rect.left - parentRect.left;
            initialY = rect.top - parentRect.top;

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
        
        const deltaX = currentX - startX;
        const deltaY = currentY - startY;

        if (Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) {
            isDragging = true;
        }
        
        // Update position by setting style. This works for relative and absolute elements.
        activeCard.style.left = `${initialX + deltaX}px`;
        activeCard.style.top = `${initialY + deltaY}px`;
    }

    function dragEnd(e) {
        if (!activeCard) return;

        document.removeEventListener('mousemove', dragging);
        document.removeEventListener('touchmove', dragging);
        document.removeEventListener('mouseup', dragEnd);
        document.removeEventListener('touchend', dragEnd);

        activeCard.classList.remove('is-dragging');

        // --- NEW: Intelligent Click Handling ---
        if (!isDragging) {
            if (activeCard.id === 'add-card-button') {
                addCard();
            } else if (activeCard.id === 'open-contact-modal') {
                showModal();
            } else if (activeCard.classList.contains('post-page')) {
                expandCard(activeCard);
            }
            // If it's a letter, do nothing on click.
        }

        activeCard = null;
    }

    // Attach listeners to the entire body to catch all elements
    document.body.addEventListener('mousedown', dragStart);
    document.body.addEventListener('touchstart', dragStart, { passive: false });

    // --- (All other helper functions: addCard, expandCard, collapseCard, modal logic, etc.) ---
    // This minified block contains the correct, unchanged logic for these functions.
    const addCardBtn = document.getElementById('add-card-button');
    let availablePosts = [...additionalPostsData];
    function addCard(){if(availablePosts.length===0)return;const a=availablePosts.shift();highestZ++;const b=document.createElement("div");b.className="post-page is-draggable",b.style.setProperty("--bg-image",`url('${a.image_url}')`),b.postData=a;const c=Math.floor(Math.random()*(window.innerWidth-250-80))+40,d=Math.floor(Math.random()*(window.innerHeight-375-80))+40,e=Math.random()*20-10;b.style.left=`${c}px`,b.style.top=`${d}px`,b.style.setProperty("--r",`${e}deg`),b.style.zIndex=highestZ,container.appendChild(b),setTimeout(()=>b.classList.add("is-visible"),50),availablePosts.length===0&&(addCardBtn.disabled=!0,addCardBtn.classList.add("is-disabled"))}if(addCardBtn){addCardBtn.addEventListener("click",addCard),availablePosts.length===0&&(addCardBtn.disabled=!0,addCardBtn.classList.add("is-disabled"))}const viewerOverlay=document.getElementById("card-viewer-overlay");function expandCard(a){if(expandedCard||!a.postData)return;expandedCard=a,body.classList.add("card-is-active"),viewerOverlay.classList.add("is-visible");const b=document.createElement("div");b.className="card-content-view";const c=document.createElement("button");c.className="card-close-button",c.innerHTML="&times;",c.onclick=b=>{b.stopPropagation(),collapseCard()};const d=document.createElement("h1");d.textContent=a.postData.title;const e=document.createElement("div");e.className="post-body-content",e.innerHTML=a.postData.content,b.appendChild(c),b.appendChild(d),b.appendChild(e),a.appendChild(b),a.classList.add("is-expanded")}function collapseCard(){if(!expandedCard)return;body.classList.remove("card-is-active"),viewerOverlay.classList.remove("is-visible");const a=expandedCard.querySelector(".card-content-view");a&&expandedCard.removeChild(a),expandedCard.classList.remove("is-expanded"),expandedCard=null}viewerOverlay.addEventListener("click",collapseCard),document.addEventListener("keydown",a=>{a.key==="Escape"&&collapseCard()});const openModalBtn=document.getElementById("open-contact-modal"),closeModalBtn=document.getElementById("close-contact-modal"),contactModal=document.getElementById("contact-modal");let captchaAnswer=7;function showModal(){const a=Math.floor(5*Math.random())+1,b=Math.floor(5*Math.random())+1;document.getElementById("captcha-q1").textContent=a,document.getElementById("captcha-q2").textContent=b,captchaAnswer=a+b,document.getElementById("captcha-input").value="",contactModal.classList.add("is-visible")}function hideModal(){contactModal.classList.remove("is-visible")}closeModalBtn.addEventListener("click",hideModal),contactModal.addEventListener("click",function(a){a.target===contactModal&&hideModal()}),document.getElementById("contact-form").addEventListener("submit",function(a){a.preventDefault();const b=document.getElementById("form-status");if(parseInt(document.getElementById("captcha-input").value,10)!==captchaAnswer)return b.textContent="Incorrect captcha answer.",void(b.style.color="red");b.textContent="Sending...",b.style.color="blue",setTimeout(()=>{b.textContent="Thank you!",b.style.color="green",setTimeout(hideModal,2e3)},1500)});function randomizeInitialLayout(){document.querySelectorAll(".post-page").forEach((a,b)=>{a.postData=initialPostsData[b];const c=Math.floor(Math.random()*(window.innerWidth-250-80))+40,d=Math.floor(Math.random()*(window.innerHeight-375-80))+40,e=Math.random()*20-10;a.style.left=`${c}px`,a.style.top=`${d}px`,a.style.setProperty("--r",`${e}deg`),a.style.zIndex=b+1,setTimeout(()=>a.classList.add("is-visible"),80*b)})}
});
</script>

<?php
get_footer(); 
?>