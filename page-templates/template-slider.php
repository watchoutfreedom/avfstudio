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
        overflow: hidden; /* VERY important for this layout */
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .concept-body {
        height: 100vh;
        width: 100vw;
        position: relative; /* All card positions are relative to this */
        background-color: black;
        background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%);
        color: #f0f0f0;
    }

    /* --- Contact & Main Title Area --- */
    .header-content {
        position: relative;
        z-index: 1000; /* Keep header content above the cards */
        padding: 30px 40px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        pointer-events: none; /* Allows clicks to pass through to cards below */
    }
    .header-content > * {
        pointer-events: all; /* Re-enable pointer events for children */
    }
    
    .main-header {
        text-align: left;
    }

    .main-title {
        font-size: 4rem;
        font-weight: 800;
        margin: 0;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .main-subtitle {
        font-size: 1.5rem;
        font-weight: 300;
        margin: 0;
        color: #bbb;
    }
    
    .contact-icon-button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 10px;
    }
    .contact-icon-button svg {
        width: 32px;
        height: 32px;
        fill: #f0f0f0;
        transition: transform 0.3s ease;
    }
    .contact-icon-button:hover svg {
        transform: scale(1.1);
    }

    /* --- Post Cards (Tabletop Style) --- */
    .post-page {
        position: absolute;
        width: 250px;
        height: 375px;
        cursor: grab;
        
        /* Image styling */
        background-image: var(--bg-image);
        background-size: cover;
        background-position: center;
        
        /* Visuals */
        border: 2px solid white;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);

        /* Initial state for entry animation */
        opacity: 0;
        transform: scale(0.5);
        
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
                    opacity 0.3s ease,
                    box-shadow 0.3s ease;
    }
    
    .post-page.is-visible {
        opacity: 1;
        transform: scale(1) rotate(var(--r, 0deg));
    }

    .post-page:hover {
        /* On hover, slightly lift the card */
        box-shadow: 0 15px 45px rgba(0,0,0,0.5);
        transform: scale(1.03) rotate(var(--r, 0deg));
    }
    
    .post-page.is-dragging {
        cursor: grabbing;
        box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        transform: scale(1.05) rotate(var(--r, 0deg));
        pointer-events: none;
        transition: none; /* Disable transition while dragging for instant feedback */
    }

    /* --- Add Card Button --- */
    .add-card-button {
        position: fixed;
        bottom: 40px;
        right: 40px;
        width: 60px;
        height: 60px;
        background-color: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 50%;
        font-size: 3rem;
        font-weight: 300;
        line-height: 60px; /* Vertically center the '+' */
        text-align: center;
        cursor: pointer;
        z-index: 2000;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        transition: transform 0.3s ease, background-color 0.3s ease, opacity 0.3s ease;
        -webkit-tap-highlight-color: transparent;
    }

    .add-card-button:hover {
        transform: scale(1.1);
        background-color: #fff;
    }

    .add-card-button:disabled,
    .add-card-button.is-disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: scale(0.9);
        pointer-events: none;
    }

    /* --- Contact Modal (No changes) --- */
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
        .add-card-button { bottom: 20px; right: 20px; width: 50px; height: 50px; font-size: 2.5rem; line-height: 48px; }
    }
</style>

<main class="concept-body" id="concept-body">

    <!-- Header Content: Title and Contact Button -->
    <div class="header-content">
        <header class="main-header">
            <h1 class="main-title">avfstudio</h1>
            <h2 class="main-subtitle">Grow your concept ability</h2>
        </header>
        <button id="open-contact-modal" class="contact-icon-button" aria-label="Open contact form">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
            </svg>
        </button>
    </div>

    <!-- PHP renders the INITIAL cards and prepares ADDITIONAL card data for JavaScript -->
    <?php
    $initial_card_count = 10;
    // Fetch more posts than we need initially, so the "+" button has a supply.
    // Let's fetch 20 total: 10 for the start, 10 for the button.
    $total_posts_to_fetch = 20; 

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $total_posts_to_fetch,
        'orderby'        => 'rand',
        'post_status'    => 'publish',
        'meta_query'     => array(
            array('key' => '_thumbnail_id') // Ensure post has a featured image
        )
    );
    $all_posts_query = new WP_Query($args);
    $additional_posts_data = [];
    $post_index = 0;

    if ($all_posts_query->have_posts()) :
        while ($all_posts_query->have_posts()) : $all_posts_query->the_post();
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');

            if ($image_url) { // Make sure we have an image
                // Logic to split the posts:
                // 1. First 10 (or $initial_card_count) are rendered as HTML.
                // 2. The rest are stored in an array for JavaScript.
                if ($post_index < $initial_card_count) {
                    // RENDER a card directly onto the page
                    ?>
                    <a href="<?php the_permalink(); ?>" 
                       class="post-page"
                       style="--bg-image: url('<?php echo esc_url($image_url); ?>');">
                    </a>
                    <?php
                } else {
                    // STORE data for later use by the "+" button
                    $additional_posts_data[] = [
                        'permalink' => get_the_permalink(),
                        'image_url' => esc_url($image_url),
                    ];
                }
                $post_index++;
            }
        endwhile;
        wp_reset_postdata();
    endif;
    ?>
</main>

<!-- Add Card Button -->
<button id="add-card-button" class="add-card-button" aria-label="Add another card">+</button>

<!-- Contact Modal -->
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

<!-- This script tag passes the ADDITIONAL post data from PHP to JavaScript -->
<script>
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- Contact Modal Logic (unchanged) ---
    const openModalBtn = document.getElementById('open-contact-modal');
    const closeModalBtn = document.getElementById('close-contact-modal');
    const contactModal = document.getElementById('contact-modal');
    const captchaQ1 = document.getElementById('captcha-q1');
    const captchaQ2 = document.getElementById('captcha-q2');
    const captchaInput = document.getElementById('captcha-input');
    let captchaAnswer = 7;
    function showModal() { const n1=Math.floor(Math.random()*5)+1; const n2=Math.floor(Math.random()*5)+1; captchaQ1.textContent=n1; captchaQ2.textContent=n2; captchaAnswer=n1+n2; captchaInput.value=''; contactModal.classList.add('is-visible'); }
    function hideModal() { contactModal.classList.remove('is-visible'); }
    openModalBtn.addEventListener('click', showModal);
    closeModalBtn.addEventListener('click', hideModal);
    contactModal.addEventListener('click', function(e) { if(e.target===contactModal) hideModal(); });
    document.getElementById('contact-form').addEventListener('submit', function(e) { e.preventDefault(); const statusDiv=document.getElementById('form-status'); if(parseInt(captchaInput.value,10)!==captchaAnswer){ statusDiv.textContent='Incorrect captcha answer.'; statusDiv.style.color='red'; return; } statusDiv.textContent='Sending...'; statusDiv.style.color='blue'; setTimeout(()=>{ statusDiv.textContent='Thank you!'; statusDiv.style.color='green'; setTimeout(hideModal,2000);}, 1500); });
    
    
    // --- UPDATED Card Layout and Adding Logic ---
    const container = document.getElementById('concept-body');
    const addCardBtn = document.getElementById('add-card-button');
    const initialCards = document.querySelectorAll('.post-page');
    let availablePosts = [...additionalPostsData]; // Posts for the "+" button
    let highestZ = initialCards.length;

    // 1. Randomize INITIAL Card Positions on Load
    function randomizeInitialLayout() {
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;

        initialCards.forEach((card, index) => {
            const cardWidth = card.offsetWidth;
            const cardHeight = card.offsetHeight;

            const maxX = viewportWidth - cardWidth - 40;
            const maxY = viewportHeight - cardHeight - 40;
            const minX = 40;
            const minY = 40;
            
            const randomX = Math.floor(Math.random() * (maxX - minX + 1)) + minX;
            const randomY = Math.floor(Math.random() * (maxY - minY + 1)) + minY;
            const randomRot = Math.random() * 20 - 10;

            card.style.left = `${randomX}px`;
            card.style.top = `${randomY}px`;
            card.style.setProperty('--r', `${randomRot}deg`);
            card.style.zIndex = index + 1;

            // Animate them into view with a stagger
            setTimeout(() => {
                card.classList.add('is-visible');
            }, index * 80);
        });
    }
    
    // Use window.onload to ensure images are loaded and cards have dimensions
    window.onload = randomizeInitialLayout;

    // 2. Function to add a NEW card to the DOM from the `availablePosts` array
    function addCard() {
        if (availablePosts.length === 0) return; // Safety net
        
        const postData = availablePosts.shift(); // Get and remove the next post
        highestZ++;

        const card = document.createElement('a');
        card.href = postData.permalink;
        card.className = 'post-page';
        card.style.setProperty('--bg-image', `url('${postData.image_url}')`);

        const cardWidth = 250; const cardHeight = 375;
        const viewportWidth = window.innerWidth; const viewportHeight = window.innerHeight;
        const maxX = viewportWidth - cardWidth - 40; const maxY = viewportHeight - cardHeight - 40;
        const minX = 40; const minY = 40;
        const randomX = Math.floor(Math.random() * (maxX - minX + 1)) + minX;
        const randomY = Math.floor(Math.random() * (maxY - minY + 1)) + minY;
        const randomRot = Math.random() * 20 - 10;

        card.style.left = `${randomX}px`;
        card.style.top = `${randomY}px`;
        card.style.setProperty('--r', `${randomRot}deg`);
        card.style.zIndex = highestZ;

        container.appendChild(card);
        setTimeout(() => card.classList.add('is-visible'), 50);

        // Disable button if no more posts are available
        if (availablePosts.length === 0) {
            addCardBtn.disabled = true;
            addCardBtn.classList.add('is-disabled');
        }
    }

    // 3. Setup "+" Button Listener and Initial State
    if (addCardBtn) {
        addCardBtn.addEventListener('click', addCard);

        // Disable the button from the start if there are no additional posts
        if (availablePosts.length === 0) {
            addCardBtn.disabled = true;
            addCardBtn.classList.add('is-disabled');
        }
    }

    // 4. Drag-and-Drop Functionality (works for ALL cards, initial and new)
    let activeCard = null, isDragging = false, startX, startY, initialX, initialY;

    function dragStart(e) {
        if (e.target.classList.contains('post-page')) {
            e.preventDefault();
            e.stopPropagation();
            activeCard = e.target;
            isDragging = false;
            highestZ++;
            activeCard.style.zIndex = highestZ;
            activeCard.classList.add('is-dragging');

            if (e.type === 'touchstart') {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            } else {
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
        e.preventDefault();
        let currentX, currentY;
        if (e.type === 'touchmove') {
            currentX = e.touches[0].clientX;
            currentY = e.touches[0].clientY;
        } else {
            currentX = e.clientX;
            currentY = e.clientY;
        }
        const deltaX = currentX - startX;
        const deltaY = currentY - startY;

        if (Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) {
            isDragging = true;
        }
        
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

        if (!isDragging) {
            window.location.href = activeCard.href;
        }

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