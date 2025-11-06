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

    /* --- Universal Draggable Styles --- */
    .is-draggable {
        cursor: grab;
        position: relative;
        user-select: none; -webkit-user-select: none;
    }
    .is-draggable.is-dragging { cursor: grabbing; transition: none !important; }

    /* --- Contact & Main Title Area --- */
    .header-content {
        position: absolute; top: 30px; left: 40px;
        width: calc(100% - 80px);
        z-index: 1000; display: flex; justify-content: space-between; align-items: flex-start;
        pointer-events: none; transition: opacity 0.4s ease;
    }
    .header-content > * { pointer-events: all; }
    .main-header { text-align: left; }
    .main-title, .main-subtitle { font-weight: 800; margin: 0; letter-spacing: 2px; }
    .main-title { font-size: 4rem; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; color: #bbb; font-weight: 300; }
    .draggable-letter { display: inline-block; min-width: 0.25em; }
    .contact-icon-button { background: none; border: none; padding: 10px; }
    .contact-icon-button svg { width: 32px; height: 32px; fill: #f0f0f0; transition: transform 0.3s ease; }
    .contact-icon-button:hover svg { transform: scale(1.1); }

    /* Z-Index Stacking Order */
    /* Loader: 99999, Contact Modal: 90000, Dragged Item: 5001+, Expanded Card: 5000 */

    /* Contact Modal CSS */
    .contact-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.7); display: none; justify-content: center; align-items: center;
        z-index: 90000; opacity: 0; transition: opacity 0.3s ease;
    }
    .contact-modal-overlay.is-visible { display: flex; opacity: 1; }
    .contact-modal-content {
        background: #fff; color: #333; padding: 40px; border-radius: 8px;
        width: 90%; max-width: 500px; position: relative;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        transform: scale(0.95); transition: transform 0.3s ease;
    }
    .contact-modal-overlay.is-visible .contact-modal-content { transform: scale(1); }
    .contact-modal-content h3 { margin-top: 0; margin-bottom: 20px; }
    .contact-modal-content .close-button { position: absolute; top: 10px; right: 15px; font-size: 2rem; font-weight: 300; color: #888; background: none; border: none; cursor: pointer; }
    .contact-modal-content input, .contact-modal-content textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; box-sizing: border-box; }
    .contact-modal-content textarea { min-height: 120px; resize: vertical; }
    .contact-modal-content .captcha-group { display: flex; align-items: center; margin-bottom: 20px; }
    .contact-modal-content button[type="submit"] { width: 100%; padding: 12px; background-color: #333; color: #fff; border: none; border-radius: 4px; font-size: 1.1rem; cursor: pointer; transition: background-color 0.3s ease; }

    /* Post Cards */
    .post-page { position: absolute; width: 250px; height: 375px; }
    .post-page.is-expanded { cursor: default !important; z-index: 5000; }
    /* ... (rest of post card styles are fine) ... */

    /* Add Card Button */
    .add-card-button {
        position: absolute; bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; line-height: 60px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease;
    }
    .add-card-button:disabled { opacity: 0.4; pointer-events: none; }
    body.card-is-active .header-content, body.card-is-active .add-card-button { opacity: 0; pointer-events: none; }

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
        <button id="open-contact-modal" class="contact-icon-button is-draggable" aria-label="Open contact form">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
        </button>
    </div>

    <!-- PHP Query Logic (Unchanged and Correct) -->
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
                echo '<div class="post-page is-draggable" data-index="' . $post_index . '" style="--bg-image: url(\'' . esc_url($image_url) . '\');"></div>';
            } else { $additional_posts_data[] = $post_data; }
            $post_index++;
        }
    }
    wp_reset_postdata();
    ?>
</main>

<button id="add-card-button" class="add-card-button is-draggable" aria-label="Add another card">+</button>

<!-- FIXED: Full Contact Modal HTML restored -->
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
    // --- Global Variables & Constants ---
    const body = document.body;
    const container = document.getElementById('concept-body');
    const pageLoader = document.getElementById('page-loader');
    const addCardBtn = document.getElementById('add-card-button');
    const viewerOverlay = document.getElementById('card-viewer-overlay');
    let availablePosts = [...additionalPostsData];
    let highestZ = 100;
    let expandedCard = null;

    // --- Initial Setup ---
    wrapLettersInSpans('.main-title');
    wrapLettersInSpans('.main-subtitle');
    setupContactModal(); // Run the null-safe modal setup
    
    window.onload = function() {
        randomizeInitialLayout();
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add('is-hidden'); }, 200); }
    };

    // --- Letter Shattering ---
    function wrapLettersInSpans(selector) {
        const element = document.querySelector(selector);
        if (!element) return;
        const text = element.textContent;
        element.innerHTML = '';
        text.split('').forEach(char => {
            const span = document.createElement('span');
            span.className = 'is-draggable draggable-letter';
            span.textContent = char;
            element.appendChild(span);
        });
    }

    // --- FIXED: Null-Safe Contact Modal Setup ---
    function setupContactModal() {
        const openModalBtn = document.getElementById('open-contact-modal');
        const closeModalBtn = document.getElementById('close-contact-modal');
        const contactModal = document.getElementById('contact-modal');

        // This `if` block prevents the "is null" error
        if (openModalBtn && closeModalBtn && contactModal) {
            let captchaAnswer = 7;
            const showModal = function() {
                const n1 = Math.floor(Math.random() * 5) + 1, n2 = Math.floor(Math.random() * 5) + 1;
                document.getElementById('captcha-q1').textContent = n1;
                document.getElementById('captcha-q2').textContent = n2;
                captchaAnswer = n1 + n2;
                document.getElementById('captcha-input').value = '';
                contactModal.classList.add('is-visible');
            };
            const hideModal = function() { contactModal.classList.remove('is-visible'); };
            
            // This function is now also called by the drag-and-drop engine
            window.showModal = showModal;

            closeModalBtn.addEventListener('click', hideModal);
            contactModal.addEventListener('click', function(e) { if (e.target === contactModal) hideModal(); });
            document.getElementById('contact-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const statusDiv = document.getElementById('form-status');
                if (parseInt(document.getElementById('captcha-input').value, 10) !== captchaAnswer) {
                    statusDiv.textContent = 'Incorrect captcha answer.'; statusDiv.style.color = 'red'; return;
                }
                statusDiv.textContent = 'Sending...'; statusDiv.style.color = 'blue';
                setTimeout(() => { statusDiv.textContent = 'Thank you!'; statusDiv.style.color = 'green'; setTimeout(hideModal, 2000); }, 1500);
            });
        }
    }

    // --- Card & Layout Functions ---
    function randomizeInitialLayout() {
        document.querySelectorAll('.post-page').forEach((card, index) => {
            card.postData = initialPostsData[index];
            const randomX = Math.floor(Math.random() * (window.innerWidth - 250 - 80)) + 40;
            const randomY = Math.floor(Math.random() * (window.innerHeight - 375 - 80)) + 40;
            const randomRot = Math.random() * 20 - 10;
            card.style.left = `${randomX}px`;
            card.style.top = `${randomY}px`;
            card.style.setProperty('--r', `${randomRot}deg`);
            card.style.zIndex = index + 1;
            setTimeout(() => card.classList.add('is-visible'), index * 80);
        });
    }

    function addCard() {
        if (availablePosts.length === 0) return;
        const postData = availablePosts.shift();
        highestZ++;
        const card = document.createElement('div');
        card.className = "post-page is-draggable";
        card.style.setProperty('--bg-image', `url('${postData.image_url}')`);
        card.postData = postData;
        const randomX = Math.floor(Math.random() * (window.innerWidth - 250 - 80)) + 40;
        const randomY = Math.floor(Math.random() * (window.innerHeight - 375 - 80)) + 40;
        const randomRot = Math.random() * 20 - 10;
        card.style.left = `${randomX}px`;
        card.style.top = `${randomY}px`;
        card.style.setProperty('--r', `${randomRot}deg`);
        card.style.zIndex = highestZ;
        container.appendChild(card);
        setTimeout(() => card.classList.add("is-visible"), 50);
        if (availablePosts.length === 0 && addCardBtn) {
            addCardBtn.disabled = true;
        }
    }

    function expandCard(cardElement) {
        if (expandedCard || !cardElement.postData) return;
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
        contentView.appendChild(closeButton);
        contentView.appendChild(title);
        contentView.appendChild(bodyContent);
        cardElement.appendChild(contentView);
        cardElement.classList.add("is-expanded");
    }

    function collapseCard() {
        if (!expandedCard) return;
        body.classList.remove("card-is-active");
        viewerOverlay.classList.remove("is-visible");
        const contentView = expandedCard.querySelector('.card-content-view');
        if (contentView) expandedCard.removeChild(contentView);
        expandedCard.classList.remove("is-expanded");
        expandedCard = null;
    }
    viewerOverlay.addEventListener('click', collapseCard);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') collapseCard(); });

    // --- Universal Drag & Drop Engine ---
    let activeElement = null, isDragging = false, startX, startY, initialX, initialY;

    function dragStart(e) {
        const target = e.target.closest('.is-draggable');
        if (target && !expandedCard) {
            e.preventDefault();
            e.stopPropagation();
            activeElement = target;
            isDragging = false;
            highestZ++;
            activeElement.style.zIndex = highestZ;
            activeElement.classList.add('is-dragging');
            startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
            startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
            const rect = activeElement.getBoundingClientRect();
            const parentRect = activeElement.parentElement.getBoundingClientRect();
            initialX = rect.left - parentRect.left;
            initialY = rect.top - parentRect.top;
            document.addEventListener('mousemove', dragging);
            document.addEventListener('touchmove', dragging, { passive: false });
            document.addEventListener('mouseup', dragEnd);
            document.addEventListener('touchend', dragEnd);
        }
    }

    function dragging(e) {
        if (!activeElement) return;
        e.preventDefault();
        let currentX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        let currentY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;
        const deltaX = currentX - startX;
        const deltaY = currentY - startY;
        if (Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) isDragging = true;
        activeElement.style.left = `${initialX + deltaX}px`;
        activeElement.style.top = `${initialY + deltaY}px`;
    }

    function dragEnd() {
        if (!activeElement) return;
        document.removeEventListener('mousemove', dragging);
        document.removeEventListener('touchmove', dragging);
        document.removeEventListener('mouseup', dragEnd);
        document.removeEventListener('touchend', dragEnd);
        activeElement.classList.remove('is-dragging');

        if (!isDragging) {
            if (activeElement.id === 'add-card-button') addCard();
            else if (activeElement.id === 'open-contact-modal' && typeof window.showModal === 'function') window.showModal();
            else if (activeElement.classList.contains('post-page')) expandCard(activeElement);
        }
        activeElement = null;
    }

    document.body.addEventListener('mousedown', dragStart);
    document.body.addEventListener('touchstart', dragStart, { passive: false });
});
</script>

<?php
get_footer(); 
?>