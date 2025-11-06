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
        overflow: hidden; /* Prevents scrollbars on the body */
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .concept-body {
        height: 100vh;
        width: 100vw;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        /* "lmarena colors" style gradient */
        background-color: #333;
        background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%);
        color: #f0f0f0;
        position: relative;
    }
    
    /* --- Initial Loading Animation State --- */
    .concept-body.is-loading .post-page {
        transform: translateX(-50%) translateY(500px) scale(0.8);
    }

    /* --- Contact Icon --- */
    .contact-icon-wrapper {
        position: absolute;
        top: 30px;
        right: 40px;
        z-index: 100;
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

    /* --- Main Title & Subtitle --- */
    .main-header {
        text-align: center;
        margin-bottom: 20px;
        position: relative;
        z-index: 10;
        pointer-events: none; /* Allows clicks/hovers to go through to posts below */
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

    /* --- Post Stack (Slide Projector Style) --- */
    .post-stack-container {
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 90%;
        max-width: 400px;
        height: 500px; /* Container height for positioning context */
        transform: translateX(-50%);
        pointer-events: none; /* Container doesn't capture events */
    }

    .post-page {
        /* Card setup */
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 280px;
        height: 420px; /* Portrait aspect ratio, like a slide */
        pointer-events: all; /* Cards capture events */
        text-decoration: none;
        
        /* Image styling */
        background-image: var(--bg-image);
        background-size: cover;
        background-position: center;
        
        /* Visuals */
        border: 2px solid white;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        
        /* Staggering and layering logic */
        transform-origin: bottom center;
        z-index: calc(10 - var(--i));
        
        /* Resting State: Staggered and half-hidden at the bottom */
        transform: translateX(-50%) translateY(calc(320px - var(--i) * 20px)) scale(calc(1 - var(--i) * 0.05));
        
        /* Animation: Smooth transitions for transform and filter */
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), 
                    filter 0.5s ease;
        transition-delay: calc(var(--i) * 80ms); /* Staggered entry animation */
    }
    
    .post-page:after { /* Add a subtle title overlay */
        content: attr(data-title);
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 20px 15px;
        box-sizing: border-box;
        text-align: center;
        color: white;
        font-size: 1rem;
        font-weight: 600;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    /* --- Desktop Hover Interaction --- */
    @media (hover: hover) and (pointer: fine) {
        .post-page:hover {
            /* Hover State: Moves up fully into view and scales */
            transform: translateX(-50%) translateY(-20px) scale(1.05) !important;
            z-index: 20 !important;
            cursor: pointer;
            filter: brightness(1.1); /* Subtle brightening */
        }
        .post-page:hover:after {
            opacity: 1;
        }
    }

    /* --- Mobile Touch Interaction --- */
    .post-page.is-active {
        /* Active State (for mobile): Moves up fully into view and scales */
        transform: translateX(-50%) translateY(-20px) scale(1.05);
        z-index: 20;
    }
    .post-page.is-active:after {
        opacity: 1;
    }
    
    /* --- Contact Modal (No changes) --- */
    .contact-modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); display: none; justify-content: center; align-items: center; z-index: 1000; opacity: 0; transition: opacity 0.3s ease; }
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
        .main-title { font-size: 2.5rem; }
        .main-subtitle { font-size: 1.2rem; }
        .contact-icon-wrapper { top: 15px; right: 15px; }
        .post-page { width: 240px; height: 360px; transform: translateX(-50%) translateY(calc(280px - var(--i) * 15px)) scale(calc(1 - var(--i) * 0.05));}
        .post-page.is-active, .post-page:hover { transform: translateX(-50%) translateY(-10px) scale(1.05) !important; }
    }
</style>

<main class="concept-body">

    <!-- Contact Icon Button -->
    <div class="contact-icon-wrapper">
        <button id="open-contact-modal" class="contact-icon-button" aria-label="Open contact form">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
            </svg>
        </button>
    </div>

    <!-- Main Title Area -->
    <header class="main-header">
        <h1 class="main-title">avfstudio</h1>
        <h2 class="main-subtitle">Concept power</h2>
    </header>

    <!-- Post Stack -->
    <div class="post-stack-container" id="post-stack">
        <?php
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 10,
            'orderby'        => 'rand',
            'post_status'    => 'publish',
        );

        $random_posts = new WP_Query($args);

        if ($random_posts->have_posts()) :
            $index = 0;
            // Reverse the array so the first post is visually at the bottom (higher index)
            $posts_array = array_reverse($random_posts->posts);
            foreach ($posts_array as $post) :
                setup_postdata($post);

                // Only include posts that have a featured image
                if (has_post_thumbnail()) :
                    // Use 'large' for a good balance of quality and size
                    $image_url = get_the_post_thumbnail_url($post->ID, 'large');
                    ?>
                    <a href="<?php the_permalink(); ?>" 
                       class="post-page"
                       data-title="<?php the_title_attribute(); ?>"
                       style="--i: <?php echo $index; ?>; --bg-image: url('<?php echo esc_url($image_url); ?>');" 
                       data-index="<?php echo $index; ?>">
                    </a>
                    <?php
                    $index++;
                endif; // End if has_post_thumbnail
            endforeach;
            wp_reset_postdata();
        else :
            ?>
            <div class="post-page" style="--i: 0; background: #555; display:flex; align-items:center; justify-content:center; color:white; padding: 20px; text-align:center;">
                No posts with featured images were found.
            </div>
        <?php
        endif;
        ?>
    </div>

</main>

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


<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- Initial "Slide Up" Animation ---
    const body = document.querySelector('.concept-body');
    if (body) {
        // Set initial state for animation
        body.classList.add('is-loading');
        // Remove loading state after a tiny delay to trigger CSS transition
        setTimeout(() => {
            body.classList.remove('is-loading');
        }, 100);
    }
    
    // --- Contact Modal Logic (No changes) ---
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

    // --- Mobile Post Stack Interaction (No changes) ---
    const postStack = document.getElementById('post-stack');
    if (!postStack) return;

    const pages = Array.from(postStack.querySelectorAll('.post-page')).reverse();
    if (pages.length === 0) return;

    let currentIndex = 0;
    let touchStartY = 0;
    let isDragging = false;
    const swipeThreshold = 50;

    function updateActivePage() {
        pages.forEach((page, index) => {
            page.classList.toggle('is-active', index === currentIndex);
        });
    }

    postStack.addEventListener('touchstart', function(e) {
        if (e.target.closest('.post-page')) {
            touchStartY = e.touches[0].clientY;
            isDragging = true;
        }
    }, { passive: true });

    postStack.addEventListener('touchend', function(e) {
        if (!isDragging) return;
        isDragging = false;
        
        const touchEndY = e.changedTouches[0].clientY;
        const deltaY = touchEndY - touchStartY;

        if (Math.abs(deltaY) > swipeThreshold) {
            if (deltaY < 0) { // Swipe Up
                currentIndex = Math.min(currentIndex + 1, pages.length - 1);
            } else { // Swipe Down
                currentIndex = Math.max(currentIndex - 1, 0);
            }
            updateActivePage();
        } else { // Tap
            const activePage = pages[currentIndex];
            if (activePage && e.target.closest('.post-page') === activePage) {
                window.location.href = activePage.href;
            }
        }
    });

    // Initialize the first page as active
    updateActivePage();
});
</script>

<?php
get_footer(); 
?>