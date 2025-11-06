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
        display: none;
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
        pointer-events: none; /* Allows clicks to go through to posts below */
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

    /* --- Post Stack --- */
    .post-stack-container {
        position: relative;
        width: 80%;
        max-width: 600px;
        height: 300px; /* Adjust height as needed */
        margin-top: 20px;
    }

    .post-page {
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 100%;
        height: 150px; /* Visual height of the page card */
        background: #fff;
        color: #333;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.2rem;
        font-weight: 600;
        text-align: center;
        padding: 20px;
        box-sizing: border-box;
        text-decoration: none;
        
        /* Staggering and layering logic */
        transform-origin: bottom center;
        z-index: calc(10 - var(--i));
        transform: translateX(-50%) translateY(calc(var(--i) * 15px)) scale(calc(1 - var(--i) * 0.03));
        transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .post-page-title {
        max-width: 90%;
    }

    /* --- Desktop Hover Interaction --- */
    @media (hover: hover) and (pointer: fine) {
        .post-stack-container:hover .post-page {
            transform: translateX(-50%) translateY(calc(var(--i) * 15px - 100px)) scale(calc(1 - var(--i) * 0.03));
        }

        .post-page:hover {
            transform: translateX(-50%) translateY(-120px) scale(1.05) !important;
            z-index: 20 !important;
            cursor: pointer;
        }
    }

    /* --- Mobile Touch Interaction --- */
    .post-page.is-active {
        transform: translateX(-50%) translateY(-140px) scale(1.05);
        z-index: 20;
    }
    
    /* --- Contact Modal --- */
    .contact-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: none; /* Initially hidden */
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .contact-modal-overlay.is-visible {
        display: flex;
        opacity: 1;
    }
    
    .contact-modal-content {
        background: #fff;
        color: #333;
        padding: 40px;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        position: relative;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }

    .contact-modal-overlay.is-visible .contact-modal-content {
        transform: scale(1);
    }

    .contact-modal-content h3 {
        margin-top: 0;
        margin-bottom: 20px;
    }

    .contact-modal-content .close-button {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 2rem;
        font-weight: 300;
        color: #888;
        background: none;
        border: none;
        cursor: pointer;
    }
    
    .contact-modal-content input,
    .contact-modal-content textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        box-sizing: border-box;
    }

    .contact-modal-content textarea {
        min-height: 120px;
        resize: vertical;
    }

    .contact-modal-content .captcha-group {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .contact-modal-content .captcha-group label {
        margin-right: 10px;
        white-space: nowrap;
    }

    .contact-modal-content button[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .contact-modal-content button[type="submit"]:hover {
        background-color: #555;
    }

    /* --- Responsive Adjustments --- */
    @media (max-width: 768px) {
        .main-title {
            font-size: 2.5rem;
        }
        .main-subtitle {
            font-size: 1.2rem;
        }
        .contact-icon-wrapper {
            top: 15px;
            right: 15px;
        }
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
            'orderby'        => 'rand', // Key for random selection
            'post_status'    => 'publish',
        );

        $random_posts = new WP_Query($args);

        if ($random_posts->have_posts()) :
            $index = 0;
            // Note: We reverse the array to have the first post at the bottom of the stack (highest index)
            $posts_array = array_reverse($random_posts->posts);
            foreach ($posts_array as $post) :
                setup_postdata($post);
                ?>
                <a href="<?php the_permalink(); ?>" 
                   class="post-page" 
                   style="--i: <?php echo $index; ?>;" 
                   data-index="<?php echo $index; ?>">
                   <span class="post-page-title"><?php the_title(); ?></span>
                </a>
                <?php
                $index++;
            endforeach;
            wp_reset_postdata();
        else :
            ?>
            <div class="post-page" style="--i: 0;">
                <span class="post-page-title">No posts found.</span>
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
            
            <!-- 
                NOTE FOR DEVELOPER: This is a simple client-side "CAPTCHA".
                For a real website, replace this with a server-side solution 
                like Google reCAPTCHA or a WordPress plugin (e.g., Contact Form 7 with its integrations).
            -->
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

    // --- Contact Modal Logic ---
    const openModalBtn = document.getElementById('open-contact-modal');
    const closeModalBtn = document.getElementById('close-contact-modal');
    const contactModal = document.getElementById('contact-modal');
    
    const captchaQ1 = document.getElementById('captcha-q1');
    const captchaQ2 = document.getElementById('captcha-q2');
    const captchaInput = document.getElementById('captcha-input');
    let captchaAnswer = 7;

    function showModal() {
        // Randomize simple captcha
        const n1 = Math.floor(Math.random() * 5) + 1;
        const n2 = Math.floor(Math.random() * 5) + 1;
        captchaQ1.textContent = n1;
        captchaQ2.textContent = n2;
        captchaAnswer = n1 + n2;
        captchaInput.value = '';

        contactModal.classList.add('is-visible');
    }

    function hideModal() {
        contactModal.classList.remove('is-visible');
    }

    openModalBtn.addEventListener('click', showModal);
    closeModalBtn.addEventListener('click', hideModal);
    contactModal.addEventListener('click', function(e) {
        if (e.target === contactModal) {
            hideModal();
        }
    });

    // Simple form handler placeholder
    document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const statusDiv = document.getElementById('form-status');
        if (parseInt(captchaInput.value, 10) !== captchaAnswer) {
            statusDiv.textContent = 'Incorrect captcha answer. Please try again.';
            statusDiv.style.color = 'red';
            return;
        }

        // Here you would typically use AJAX to send the form data to a WordPress endpoint
        statusDiv.textContent = 'Sending... (This is a demo)';
        statusDiv.style.color = 'blue';
        
        setTimeout(() => {
            statusDiv.textContent = 'Thank you for your message!';
            statusDiv.style.color = 'green';
            setTimeout(hideModal, 2000);
        }, 1500);
    });

    // --- Mobile Post Stack Interaction ---
    const postStack = document.getElementById('post-stack');
    if (!postStack) return;

    const pages = Array.from(postStack.querySelectorAll('.post-page')).reverse(); // Match DOM order (top is index 0)
    if (pages.length === 0) return;

    let currentIndex = 0;
    let touchStartY = 0;
    let isDragging = false;
    const swipeThreshold = 50; // Minimum pixels to be considered a swipe

    function updateActivePage() {
        pages.forEach((page, index) => {
            if (index === currentIndex) {
                page.classList.add('is-active');
            } else {
                page.classList.remove('is-active');
            }
        });
    }

    postStack.addEventListener('touchstart', function(e) {
        // We only care about touches directly on the stack
        if (e.target.closest('.post-page')) {
            touchStartY = e.touches[0].clientY;
            isDragging = true;
        }
    }, { passive: true });

    postStack.addEventListener('touchmove', function(e) {
        if (!isDragging) return;
        // Optionally, you could add visual feedback during the drag here
    }, { passive: true });

    postStack.addEventListener('touchend', function(e) {
        if (!isDragging) return;
        isDragging = false;
        
        const touchEndY = e.changedTouches[0].clientY;
        const deltaY = touchEndY - touchStartY;

        // Check for a swipe
        if (Math.abs(deltaY) > swipeThreshold) {
            if (deltaY < 0) { // Swipe Up
                currentIndex = Math.min(currentIndex + 1, pages.length - 1);
            } else { // Swipe Down
                currentIndex = Math.max(currentIndex - 1, 0);
            }
            updateActivePage();
        } else {
            // It's a Tap - "touch up opens the post"
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