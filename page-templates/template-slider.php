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
        background-color: #2b2b2b; /* Fallback */
    }

    .concept-body {
        height: 100vh;
        width: 100vw;
        position: relative;
        background-color: #333;
        background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%);
        color: #f0f0f0;
    }

    /* --- Header & Contact --- */
    .header-content {
        position: relative;
        z-index: 1000;
        padding: 30px 40px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        pointer-events: none;
    }
    .header-content > * {
        pointer-events: all;
    }
    .main-header { text-align: left; }
    .main-title { font-size: 4rem; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; font-weight: 300; margin: 0; color: #bbb; }
    .contact-icon-button { background: none; border: none; cursor: pointer; padding: 10px; }
    .contact-icon-button svg { width: 32px; height: 32px; fill: #f0f0f0; transition: transform 0.3s ease; }
    .contact-icon-button:hover svg { transform: scale(1.1); }

    /* --- Post Cards (Tabletop Style) --- */
    .post-page {
        position: absolute;
        width: 250px;
        height: 375px;
        cursor: grab;
        background-image: var(--bg-image);
        background-size: cover;
        background-position: center;
        border: 2px solid white;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        opacity: 0;
        transform: scale(0.5);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease, box-shadow 0.3s ease;
    }
    .post-page.is-visible {
        opacity: 1;
        transform: scale(1) rotate(var(--r, 0deg));
    }
    .post-page:hover {
        box-shadow: 0 15px 45px rgba(0,0,0,0.5);
        transform: scale(1.03) rotate(var(--r, 0deg));
    }
    .post-page.is-dragging {
        cursor: grabbing;
        box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        transform: scale(1.05) rotate(var(--r, 0deg));
        transition: none;
    }

    /* --- Add Post Button --- */
    .add-post-button {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #fff;
        color: #333;
        border-radius: 50%;
        border: none;
        font-size: 2.5rem;
        line-height: 60px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        cursor: pointer;
        z-index: 1001;
        transition: transform 0.3s ease, background-color 0.3s ease;
    }
    .add-post-button:hover {
        transform: scale(1.1);
    }
    .add-post-button:disabled {
        background-color: #aaa;
        cursor: not-allowed;
        transform: scale(1);
    }

    /* --- Contact Modal (Unchanged) --- */
    .contact-modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); display: none; justify-content: center; align-items: center; z-index: 10000; opacity: 0; transition: opacity 0.3s ease; }
    .contact-modal-overlay.is-visible { display: flex; opacity: 1; }
    .contact-modal-content { background: #fff; color: #333; padding: 40px; border-radius: 8px; width: 90%; max-width: 500px; position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transform: scale(0.95); transition: transform 0.3s ease; }
    .contact-modal-overlay.is-visible .contact-modal-content { transform: scale(1); }
    .contact-modal-content h3 { margin-top: 0; margin-bottom: 20px; }
    .contact-modal-content .close-button { position: absolute; top: 10px; right: 15px; font-size: 2rem; font-weight: 300; color: #888; background: none; border: none; cursor: pointer; }
    .contact-modal-content input, .contact-modal-content textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; box-sizing: border-box; }
    .contact-modal-content textarea { min-height: 120px; resize: vertical; }
    .contact-modal-content .captcha-group { display: flex; align-items: center; margin-bottom: 20px; }
    .contact-modal-content .captcha-group label { margin-right: 10px; white-space: nowrap; }
    .contact-modal-content button[type="submit"] { width: 100%; padding: 12px; background-color: #333; color: #fff; border: none; border-radius: 4px; font-size: 1.1rem; cursor: pointer; transition: background-color 0.3s ease; }
    .contact-modal-content button[type="submit"]:hover { background-color: #555; }

    /* --- Responsive Adjustments --- */
    @media (max-width: 768px) {
        .header-content { flex-direction: column-reverse; align-items: center; padding: 20px; }
        .main-header { text-align: center; margin-top: 15px; }
        .main-title { font-size: 2.5rem; }
        .main-subtitle { font-size: 1.2rem; }
        .post-page { width: 200px; height: 300px; }
        .add-post-button { bottom: 20px; right: 20px; width: 50px; height: 50px; font-size: 2rem; line-height: 50px; }
    }
</style>

<main class="concept-body" id="concept-body">

    <!-- Header Content: Title and Contact Button -->
    <div class="header-content">
        <header class="main-header">
            <h1 class="main-title">avfstudio</h1>
            <h2 class="main-subtitle">Concept power</h2>
        </header>
        <button id="open-contact-modal" class="contact-icon-button" aria-label="Open contact form">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
        </button>
    </div>

    <!-- "+" Button to Add New Posts -->
    <button id="add-post-button" class="add-post-button" title="Add a new post">+</button>

    <?php
    // Fetch ALL available posts and prepare them for JavaScript
    $all_posts_data = [];
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1, // Fetch ALL posts
        'post_status'    => 'publish',
        'meta_query'     => array(
            array('key' => '_thumbnail_id') // Ensure post has a featured image
        )
    );
    $all_posts_query = new WP_Query($args);

    if ($all_posts_query->have_posts()) :
        while ($all_posts_query->have_posts()) : $all_posts_query->the_post();
            $all_posts_data[] = [
                'url' => get_the_permalink(),
                'imageUrl' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            ];
        endwhile;
        wp_reset_postdata();
    endif;
    ?>
</main>

<!-- Contact Modal -->
<div id="contact-modal" class="contact-modal-overlay"><!-- ...modal content... --></div>

<script>
    // Pass all post data from PHP to JavaScript in a clean way
    const allPosts = <?php echo json_encode($all_posts_data); ?>;

document.addEventListener('DOMContentLoaded', function() {
    
    // --- Contact Modal Logic (unchanged) ---
    // ... (This logic remains the same)
    const openModalBtn=document.getElementById('open-contact-modal');const closeModalBtn=document.getElementById('close-contact-modal');const contactModal=document.getElementById('contact-modal');const captchaQ1=document.getElementById('captcha-q1');const captchaQ2=document.getElementById('captcha-q2');const captchaInput=document.getElementById('captcha-input');let captchaAnswer=7;function showModal(){const n1=Math.floor(Math.random()*5)+1;const n2=Math.floor(Math.random()*5)+1;captchaQ1.textContent=n1;captchaQ2.textContent=n2;captchaAnswer=n1+n2;captchaInput.value='';contactModal.classList.add('is-visible')}function hideModal(){contactModal.classList.remove('is-visible')}if(openModalBtn){openModalBtn.addEventListener('click',showModal)}if(closeModalBtn){closeModalBtn.addEventListener('click',hideModal)}if(contactModal){contactModal.addEventListener('click',function(e){if(e.target===contactModal)hideModal()});const form=document.getElementById('contact-form');if(form)form.addEventListener('submit',function(e){e.preventDefault();const statusDiv=document.getElementById('form-status');if(parseInt(captchaInput.value,10)!==captchaAnswer){statusDiv.textContent='Incorrect captcha answer.';statusDiv.style.color='red';return}statusDiv.textContent='Sending...';statusDiv.style.color='blue';setTimeout(()=>{statusDiv.textContent='Thank you!';statusDiv.style.color='green';setTimeout(hideModal,2000)},1500)})}


    // --- Card Layout and Interaction Logic ---
    const container = document.getElementById('concept-body');
    const addPostBtn = document.getElementById('add-post-button');
    let currentPostIndex = 0;
    let highestZ = 0;
    let isMobile = window.innerWidth <= 768;

    /**
     * Creates and adds a single post card to the screen.
     */
    function createCard(postData, index) {
        const card = document.createElement('a');
        card.href = postData.url;
        card.className = 'post-page';
        card.style.setProperty('--bg-image', `url('${postData.imageUrl}')`);
        card.dataset.index = index;
        container.appendChild(card);
        return card;
    }

    /**
     * Updates the state of the "+" button (enabled/disabled).
     */
    function updateAddButtonState() {
        if (currentPostIndex >= allPosts.length) {
            addPostBtn.disabled = true;
        } else {
            addPostBtn.disabled = false;
        }
    }

    /**
     * Sets up the initial random layout of cards.
     */
    function initializeLayout() {
        const baseCardCount = 4;
        const extraCardsPerPixel = 1 / 30; // 1 card every 30px
        const baseWidth = 480; // Width at which we start adding more cards
        
        const cardsToShow = Math.min(
            allPosts.length,
            baseCardCount + Math.floor(Math.max(0, window.innerWidth - baseWidth) * extraCardsPerPixel)
        );

        for (let i = 0; i < cardsToShow; i++) {
            if (currentPostIndex >= allPosts.length) break;

            const card = createCard(allPosts[currentPostIndex], currentPostIndex);
            
            // Wait for the card to be rendered to get its dimensions
            setTimeout(() => {
                const cardWidth = card.offsetWidth;
                const cardHeight = card.offsetHeight;
                const viewportWidth = window.innerWidth;
                const viewportHeight = window.innerHeight;

                // On mobile, expand the placement area to go off-screen
                const xMargin = isMobile ? -cardWidth / 2 : 40;
                const yMargin = isMobile ? -cardHeight / 3 : 40;

                const minX = xMargin;
                const maxX = viewportWidth - cardWidth - xMargin;
                const minY = yMargin;
                const maxY = viewportHeight - cardHeight - yMargin;
                
                const randomX = Math.floor(Math.random() * (maxX - minX + 1)) + minX;
                const randomY = Math.floor(Math.random() * (maxY - minY + 1)) + minY;
                const randomRot = Math.random() * 20 - 10;

                card.style.left = `${randomX}px`;
                card.style.top = `${randomY}px`;
                card.style.setProperty('--r', `${randomRot}deg`);
                card.style.zIndex = currentPostIndex + 1;

                setTimeout(() => card.classList.add('is-visible'), 100 + i * 80);
            }, 0);

            currentPostIndex++;
            highestZ = currentPostIndex;
        }
        updateAddButtonState();
    }
    
    // Add Post Button Click Handler
    addPostBtn.addEventListener('click', () => {
        if (currentPostIndex >= allPosts.length) return;

        const card = createCard(allPosts[currentPostIndex], currentPostIndex);
        
        setTimeout(() => {
            const cardWidth = card.offsetWidth;
            const cardHeight = card.offsetHeight;

            // Position new card in the middle of the screen
            const centerX = window.innerWidth / 2 - cardWidth / 2;
            const centerY = window.innerHeight / 2 - cardHeight / 2;
            const randomRot = Math.random() * 20 - 10;
            
            card.style.left = `${centerX}px`;
            card.style.top = `${centerY}px`;
            card.style.setProperty('--r', `${randomRot}deg`);
            highestZ++;
            card.style.zIndex = highestZ;

            setTimeout(() => card.classList.add('is-visible'), 50);
        }, 0);

        currentPostIndex++;
        updateAddButtonState();
    });

    // --- Drag-and-Drop Functionality ---
    let activeCard = null;
    let isDragging = false;
    let startX, startY, initialX, initialY;

    function dragStart(e) {
        if (e.target.classList.contains('post-page')) {
            activeCard = e.target;
            isDragging = false; // Reset on every pressdown

            highestZ++;
            activeCard.style.zIndex = highestZ;
            
            if (e.type === 'touchstart') {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            } else {
                e.preventDefault(); // Prevent browser's default image drag
                startX = e.clientX;
                startY = e.clientY;
            }

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
        
        // This is where we decide if it's a drag or a click
        if (!isDragging) {
            // After moving a few pixels, we classify it as a drag
            const moveThreshold = 5;
            let currentX = (e.type === 'touchmove') ? e.touches[0].clientX : e.clientX;
            let currentY = (e.type === 'touchmove') ? e.touches[0].clientY : e.clientY;
            if (Math.abs(currentX - startX) > moveThreshold || Math.abs(currentY - startY) > moveThreshold) {
                isDragging = true;
                activeCard.classList.add('is-dragging');
            }
        }
        
        if (isDragging) {
            e.preventDefault();
            let currentX = (e.type === 'touchmove') ? e.touches[0].clientX : e.clientX;
            let currentY = (e.type === 'touchmove') ? e.touches[0].clientY : e.clientY;
            activeCard.style.left = `${initialX + (currentX - startX)}px`;
            activeCard.style.top = `${initialY + (currentY - startY)}px`;
        }
    }

    function dragEnd(e) {
        if (!activeCard) return;

        document.removeEventListener('mousemove', dragging);
        document.removeEventListener('touchmove', dragging);
        document.removeEventListener('mouseup', dragEnd);
        document.removeEventListener('touchend', dragEnd);

        // **CRITICAL FIX**: Only navigate if it was NOT a drag
        if (!isDragging) {
            window.location.href = activeCard.href;
        }

        activeCard.classList.remove('is-dragging');
        activeCard = null;
    }

    container.addEventListener('mousedown', dragStart);
    container.addEventListener('touchstart', dragStart, { passive: false });
    
    // Kick everything off
    window.onload = initializeLayout;
});
</script>

<?php
get_footer(); 
?>