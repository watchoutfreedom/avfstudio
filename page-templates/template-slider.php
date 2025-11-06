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
        background-color: #333;
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
        transition: none; /* Disable transition while dragging for instant feedback */
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
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
            </svg>
        </button>
    </div>

    <!-- Post Cards are injected here from PHP -->
    <?php
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 10,
        'orderby'        => 'rand',
        'post_status'    => 'publish',
        'meta_query'     => array(
            array('key' => '_thumbnail_id') // Ensure post has a featured image
        )
    );
    $random_posts = new WP_Query($args);

    if ($random_posts->have_posts()) :
        $index = 0;
        while ($random_posts->have_posts()) : $random_posts->the_post();
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            ?>
            <a href="<?php the_permalink(); ?>" 
               class="post-page"
               data-index="<?php echo $index; ?>"
               style="--bg-image: url('<?php echo esc_url($image_url); ?>');">
            </a>
            <?php
            $index++;
        endwhile;
        wp_reset_postdata();
    endif;
    ?>
</main>

<!-- Contact Modal -->
<div id="contact-modal" class="contact-modal-overlay">
    <!-- ... Modal content is unchanged ... -->
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
document.addEventListener('DOMContentLoaded', function() {

    // --- Contact Modal Logic (unchanged) ---
    // ... (This logic remains the same, it's safe to keep it collapsed for brevity)
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
    

    // --- Card Layout and Dragging Logic ---
    const container = document.getElementById('concept-body');
    const cards = document.querySelectorAll('.post-page');
    let highestZ = cards.length;

    // 1. Randomize Card Positions on Load
    function randomizeLayout() {
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;

        cards.forEach((card, index) => {
            const cardWidth = card.offsetWidth;
            const cardHeight = card.offsetHeight;

            // Define a "safe area" to avoid cards being completely off-screen
            const maxX = viewportWidth - cardWidth - 40; // 40px padding
            const maxY = viewportHeight - cardHeight - 40;
            const minX = 40;
            const minY = 40;
            
            // Random position and rotation
            const randomX = Math.floor(Math.random() * (maxX - minX + 1)) + minX;
            const randomY = Math.floor(Math.random() * (maxY - minY + 1)) + minY;
            const randomRot = Math.random() * 20 - 10; // -10 to +10 degrees

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
    
    // We use window.onload to ensure images are loaded and cards have dimensions
    window.onload = randomizeLayout;


    // 2. Drag-and-Drop Functionality
    let activeCard = null;
    let startX, startY, initialX, initialY;

    function dragStart(e) {
        if (e.target.classList.contains('post-page')) {
            e.preventDefault();
            activeCard = e.target;
            
            // Bring card to the top
            highestZ++;
            activeCard.style.zIndex = highestZ;
            activeCard.classList.add('is-dragging');

            // Get initial position
            if (e.type === 'touchstart') {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            } else {
                startX = e.clientX;
                startY = e.clientY;
            }

            // Get the card's current top/left
            initialX = activeCard.offsetLeft;
            initialY = activeCard.offsetTop;

            // Add move and end listeners to the whole document
            document.addEventListener('mousemove', dragging);
            document.addEventListener('touchmove', dragging, { passive: false });
            document.addEventListener('mouseup', dragEnd);
            document.addEventListener('touchend', dragEnd);
        }
    }

    function dragging(e) {
        if (!activeCard) return;
        e.preventDefault(); // Prevent scrolling on mobile

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

        // Update position
        activeCard.style.left = `${initialX + deltaX}px`;
        activeCard.style.top = `${initialY + deltaY}px`;
    }

    function dragEnd(e) {
        if (!activeCard) return;

        // Remove document-level listeners
        document.removeEventListener('mousemove', dragging);
        document.removeEventListener('touchmove', dragging);
        document.removeEventListener('mouseup', dragEnd);
        document.removeEventListener('touchend', dragEnd);

        activeCard.classList.remove('is-dragging');

        // Calculate actual movement distance
        const movedX = Math.abs(activeCard.offsetLeft - initialX);
        const movedY = Math.abs(activeCard.offsetTop - initialY);

        // Only trigger click action if movement is within 5px threshold
        if (movedX <= 5 && movedY <= 5) {
            window.location.href = activeCard.href;
        }

        activeCard = null;
    }

    // Attach initial event listener to the container
    container.addEventListener('mousedown', dragStart);
    container.addEventListener('touchstart', dragStart, { passive: false });
});
</script>

<?php
get_footer(); 
?>