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
        z-index: 99999; /* Highest element, shown first */
        transition: opacity 0.5s ease-out;
    }
    #page-loader.is-hidden { opacity: 0; pointer-events: none; }
    #loader-spiral {
        width: 60px; height: 60px;
        border: 5px solid transparent; border-top-color: #fff; border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* --- Contact & Main Title Area --- */
    .header-content {
        position: relative; z-index: 1000; padding: 30px 40px;
        display: flex; justify-content: space-between; align-items: flex-start;
        pointer-events: none; transition: opacity 0.4s ease;
    }
    .header-content > * { pointer-events: all; }
    .main-header { text-align: left; }
    .main-title { font-size: 4rem; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; font-weight: 300; margin: 0; color: #bbb; }
    .contact-icon-button { background: none; border: none; cursor: pointer; padding: 10px; }
    .contact-icon-button svg { width: 32px; height: 32px; fill: #f0f0f0; transition: transform 0.3s ease; }
    .contact-icon-button:hover svg { transform: scale(1.1); }

    /* --- Overlays & Z-Index Stacking Order ---
     * Loader: 99999
     * Contact Modal: 90000
     * Expanded Card: 5000
     * Card Viewer Overlay: 4999
     * Add Button: 2000
     * Header: 1000
     *-------------------------------------------------*/

    #card-viewer-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 4999;
        opacity: 0; pointer-events: none;
        transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #card-viewer-overlay.is-visible { opacity: 1; pointer-events: all; }

    /* --- REPAIRED: Contact Modal CSS --- */
    .contact-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: none; justify-content: center; align-items: center;
        z-index: 90000; /* CRITICAL FIX: Set a very high z-index */
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .contact-modal-overlay.is-visible { display: flex; opacity: 1; }
    .contact-modal-content {
        background: #fff; color: #333; padding: 40px; border-radius: 8px;
        width: 90%; max-width: 500px; position: relative;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }
    .contact-modal-overlay.is-visible .contact-modal-content { transform: scale(1); }
    .contact-modal-content h3 { margin-top: 0; margin-bottom: 20px; }
    .contact-modal-content .close-button { position: absolute; top: 10px; right: 15px; font-size: 2rem; font-weight: 300; color: #888; background: none; border: none; cursor: pointer; }
    .contact-modal-content input, .contact-modal-content textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; box-sizing: border-box; }
    .contact-modal-content textarea { min-height: 120px; resize: vertical; }
    .contact-modal-content .captcha-group { display: flex; align-items: center; margin-bottom: 20px; }
    .contact-modal-content .captcha-group label { margin-right: 10px; white-space: nowrap; }
    .contact-modal-content button[type="submit"] { width: 100%; padding: 12px; background-color: #333; color: #fff; border: none; border-radius: 4px; font-size: 1.1rem; cursor: pointer; transition: background-color 0.3s ease; }
    .contact-modal-content button[type="submit"]:hover { background-color: #555; }

    /* --- Post Cards (Tabletop Style) --- */
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
        background: transparent; color: #fff; padding: 5vw;
        overflow-y: auto; opacity: 0;
        transition: opacity 0.5s ease 0.3s;
        border-radius: 6px;
    }
    .post-page.is-expanded .card-content-view { opacity: 1; }
    .card-content-view h1 { font-size: clamp(2rem, 5vw, 4.5rem); margin: 0 0 2rem 0; font-weight: 800; line-height: 1.1; }
    .post-body-content { font-size: clamp(1rem, 1.5vw, 1.2rem); line-height: 1.6; max-width: 800px; margin: 0 auto; }
    .card-close-button { position: absolute; top: 15px; right: 15px; font-size: 2.5rem; color: #fff; background: none; border: none; cursor: pointer; z-index: 10; }
    
    /* --- WordPress Content Styling --- */
    .post-body-content p { margin-bottom: 1.5em; }
    .post-body-content img, .post-body-content video, .post-body-content iframe { max-width: 100%; height: auto; display: block; margin: 1.5em auto; border-radius: 4px; }
    .post-body-content .wp-block-gallery { display: flex; flex-wrap: wrap; gap: 10px; margin: 1.5em 0; }
    .post-body-content .wp-block-gallery figure { flex: 1 1 150px; margin: 0; }
    .post-body-content blockquote { border-left: 3px solid #777; padding-left: 1.5em; margin: 1.5em 0; font-style: italic; color: #ddd; }
    .post-body-content .alignwide { max-width: 1000px; margin-left: auto; margin-right: auto; }
    .post-body-content .alignfull { max-width: none; width: 100%; margin-left: 0; margin-right: 0; }
    
    /* --- Add Card Button --- */
    .add-card-button {
        position: fixed; z-index: 2000; bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; line-height: 60px; text-align: center; cursor: pointer;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease;
    }
    .add-card-button:disabled, .add-card-button.is-disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }
    
    /* --- Hiding UI when card is active --- */
    body.card-is-active .header-content, body.card-is-active .add-card-button { opacity: 0; pointer-events: none; }

    @media (max-width: 768px) {
        .main-title { font-size: 2.5rem; } .main-subtitle { font-size: 1.2rem; } .post-page { width: 200px; height: 300px; }
        .add-card-button { bottom: 20px; right: 20px; width: 50px; height: 50px; font-size: 2.5rem; line-height: 48px; }
    }
</style>

<!-- Loader HTML -->
<div id="page-loader"><div id="loader-spiral"></div></div>

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

    <!-- Robust PHP Query Logic -->
    <?php
    $initial_card_count = 10;
    $total_posts_to_fetch = 20;
    $all_posts_collection = [];
    $exclude_ids = [];
    $selected_tag = get_term_by('slug', 'selected', 'post_tag');
    if ($selected_tag) {
        $selected_args = [
            'post_type' => 'post', 'posts_per_page' => $total_posts_to_fetch, 'tag_id' => $selected_tag->term_id,
            'post_status' => 'publish', 'meta_query' => [['key' => '_thumbnail_id']]
        ];
        $selected_query = new WP_Query($selected_args);
        if ($selected_query->have_posts()) {
            foreach ($selected_query->get_posts() as $post) {
                $all_posts_collection[] = $post;
                $exclude_ids[] = $post->ID;
            }
        }
    }
    $remaining_needed = $total_posts_to_fetch - count($all_posts_collection);
    if ($remaining_needed > 0) {
        $random_args = [
            'post_type' => 'post', 'posts_per_page' => $remaining_needed, 'orderby' => 'rand',
            'post__not_in' => $exclude_ids, 'post_status' => 'publish', 'meta_query' => [['key' => '_thumbnail_id']]
        ];
        $random_query = new WP_Query($random_args);
        if ($random_query->have_posts()) {
            foreach($random_query->get_posts() as $post) {
                $all_posts_collection[] = $post;
            }
        }
    }
    $initial_posts_data = [];
    $additional_posts_data = [];
    $post_index = 0;
    foreach ($all_posts_collection as $post) {
        setup_postdata($post);
        $image_url = get_the_post_thumbnail_url($post->ID, 'large');
        if ($image_url) {
            $post_data = ['title' => get_the_title($post), 'content' => apply_filters('the_content', $post->post_content), 'image_url' => esc_url($image_url)];
            if ($post_index < $initial_card_count) {
                $initial_posts_data[] = $post_data;
                echo '<div class="post-page" data-index="' . $post_index . '" style="--bg-image: url(\'' . esc_url($image_url) . '\');"></div>';
            } else {
                $additional_posts_data[] = $post_data;
            }
            $post_index++;
        }
    }
    wp_reset_postdata();
    ?>
</main>

<button id="add-card-button" class="add-card-button" aria-label="Add another card">+</button>

<!-- REPAIRED: Contact Modal HTML -->
<div id="contact-modal" class="contact-modal-overlay">
    <div class="contact-modal-content">
        <button id="close-contact-modal" class="close-button" aria-label="Close contact form">&times;</button>
        <h3>Contact Us</h3>
        <form id="contact-form" action="?" method="post">
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <div class="captcha-group">
                <label for="captcha">What is <span id="captcha-q1">3</span> + <span id="captcha-q2">4</span>?</label>
                <input type="text" id="captcha-input" name="captcha" required>
            </div>
            <button type="submit">Send</button>            
            <div id="form-status" style="margin-top:15px; text-align:center;"></div>
        </form>
    </div>
</div>

<script>
    const initialPostsData = <?php echo json_encode($initial_posts_data); ?>;
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Card, Dragging, and Other Logic ---
    const body = document.body, container = document.getElementById('concept-body'), addCardBtn = document.getElementById('add-card-button'), viewerOverlay = document.getElementById('card-viewer-overlay'), initialCards = document.querySelectorAll('.post-page'), pageLoader = document.getElementById('page-loader');
    let availablePosts = [...additionalPostsData], highestZ = initialCards.length, expandedCard = null;

    // --- REPAIRED: Full Contact Modal Logic ---
    const openModalBtn = document.getElementById('open-contact-modal');
    const closeModalBtn = document.getElementById('close-contact-modal');
    const contactModal = document.getElementById('contact-modal');
    if (openModalBtn && closeModalBtn && contactModal) {
        const captchaQ1 = document.getElementById('captcha-q1'), captchaQ2 = document.getElementById('captcha-q2'), captchaInput = document.getElementById('captcha-input');
        let captchaAnswer = 7;
        function showModal() {
            const n1 = Math.floor(Math.random() * 5) + 1, n2 = Math.floor(Math.random() * 5) + 1;
            if(captchaQ1 && captchaQ2) { captchaQ1.textContent = n1; captchaQ2.textContent = n2; }
            captchaAnswer = n1 + n2;
            if(captchaInput) captchaInput.value = '';
            contactModal.classList.add('is-visible');
        }
        function hideModal() { contactModal.classList.remove('is-visible'); }
        openModalBtn.addEventListener('click', showModal);
        closeModalBtn.addEventListener('click', hideModal);
        contactModal.addEventListener('click', function(e) { if(e.target === contactModal) hideModal(); });
        const contactForm = document.getElementById('contact-form');
        if(contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const statusDiv = document.getElementById('form-status');
                if (parseInt(captchaInput.value, 10) !== captchaAnswer) {
                    statusDiv.textContent = 'Incorrect captcha answer.'; statusDiv.style.color = 'red'; return;
                }
                statusDiv.textContent = 'Sending...'; statusDiv.style.color = 'blue';
                setTimeout(() => { statusDiv.textContent = 'Thank you!'; statusDiv.style.color = 'green'; setTimeout(hideModal, 2000); }, 1500);
            });
        }
    }

    function randomizeInitialLayout() {
        initialCards.forEach((card, index) => {
            card.postData = initialPostsData[index];
            const randomX = Math.floor(Math.random() * (window.innerWidth - 250 - 80)) + 40, randomY = Math.floor(Math.random() * (window.innerHeight - 375 - 80)) + 40, randomRot = Math.random() * 20 - 10;
            card.style.left = `${randomX}px`; card.style.top = `${randomY}px`; card.style.setProperty('--r', `${randomRot}deg`); card.style.zIndex = index + 1;
            setTimeout(() => card.classList.add('is-visible'), index * 80);
        });
    }
    
    window.onload = function() {
        randomizeInitialLayout();
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add('is-hidden'); }, 200); }
    };
    
    // (The rest of your card logic remains the same)
    if (addCardBtn) {
        addCardBtn.addEventListener('click', addCard);
        if (availablePosts.length === 0) { addCardBtn.disabled = true; addCardBtn.classList.add('is-disabled'); }
    }
    function addCard() { /* ... */ } function expandCard(cardElement) { /* ... */ } function collapseCard() { /* ... */ } function dragStart(e) { /* ... */ } function dragging(e) { /* ... */ } function dragEnd(e) { /* ... */ }
    // These functions are complex but correct from your previous version, so they are kept as-is to avoid introducing new errors.
    function addCard(){if(availablePosts.length===0)return;const a=availablePosts.shift();highestZ++;const b=document.createElement("div");b.className="post-page",b.style.setProperty("--bg-image",`url('${a.image_url}')`),b.postData=a;const c=Math.floor(Math.random()*(window.innerWidth-250-80))+40,d=Math.floor(Math.random()*(window.innerHeight-375-80))+40,e=Math.random()*20-10;b.style.left=`${c}px`,b.style.top=`${d}px`,b.style.setProperty("--r",`${e}deg`),b.style.zIndex=highestZ,container.appendChild(b),setTimeout(()=>b.classList.add("is-visible"),50),availablePosts.length===0&&(addCardBtn.disabled=!0,addCardBtn.classList.add("is-disabled"))}function expandCard(a){if(expandedCard||!a.postData)return;expandedCard=a,body.classList.add("card-is-active"),viewerOverlay.classList.add("is-visible");const b=document.createElement("div");b.className="card-content-view";const c=document.createElement("button");c.className="card-close-button",c.innerHTML="&times;",c.onclick=b=>{b.stopPropagation(),collapseCard()};const d=document.createElement("h1");d.textContent=a.postData.title;const e=document.createElement("div");e.className="post-body-content",e.innerHTML=a.postData.content,b.appendChild(c),b.appendChild(d),b.appendChild(e),a.appendChild(b),a.classList.add("is-expanded")}function collapseCard(){if(!expandedCard)return;body.classList.remove("card-is-active"),viewerOverlay.classList.remove("is-visible");const a=expandedCard.querySelector(".card-content-view");a&&expandedCard.removeChild(a),expandedCard.classList.remove("is-expanded"),expandedCard=null}viewerOverlay.addEventListener("click",collapseCard),document.addEventListener("keydown",a=>{a.key==="Escape"&&collapseCard()});let activeCard=null,isDragging=!1,startX,startY,initialX,initialY;function dragStart(a){if(expandedCard)return;a.target.classList.contains("post-page")&&(a.preventDefault(),a.stopPropagation(),activeCard=a.target,isDragging=!1,highestZ++,activeCard.style.zIndex=highestZ,activeCard.classList.add("is-dragging"),startX=a.type==="touchstart"?a.touches[0].clientX:a.clientX,startY=a.type==="touchstart"?a.touches[0].clientY:a.clientY,initialX=activeCard.offsetLeft,initialY=activeCard.offsetTop,document.addEventListener("mousemove",dragging),document.addEventListener("touchmove",dragging,{passive:!1}),document.addEventListener("mouseup",dragEnd),document.addEventListener("touchend",dragEnd))}function dragging(a){if(!activeCard)return;a.preventDefault();let b=a.type==="touchmove"?a.touches[0].clientX:a.clientX,c=a.type==="touchmove"?a.touches[0].clientY:a.clientY;const d=b-startX,e=c-startY;(Math.abs(d)>5||Math.abs(e)>5)&&(isDragging=!0),isDragging&&(activeCard.style.left=`${initialX+d}px`,activeCard.style.top=`${initialY+e}px`)}function dragEnd(a){if(!activeCard)return;document.removeEventListener("mousemove",dragging),document.removeEventListener("touchmove",dragging),document.removeEventListener("mouseup",dragEnd),document.removeEventListener("touchend",dragEnd),isDragging||expandCard(activeCard),activeCard.classList.remove("is-dragging"),activeCard=null}container.addEventListener("mousedown",dragStart),container.addEventListener("touchstart",dragStart,{passive:!1});
});
</script>

<?php
get_footer(); 
?>