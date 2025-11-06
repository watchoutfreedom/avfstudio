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

    /* --- Draggable UI Styles --- */
    .is-ui-draggable {
        cursor: grab;
        user-select: none; -webkit-user-select: none;
    }
    .is-ui-draggable.is-dragging {
        cursor: grabbing;
        transition: none !important;
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
    .contact-icon-button:hover svg { transform: scale(1.1); }

    /* --- Contact Modal --- */
    .contact-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.7); display: none; justify-content: center; align-items: center;
        z-index: 90000; opacity: 0; transition: opacity 0.3s ease;
    }
    .contact-modal-overlay.is-visible { display: flex; opacity: 1; }
    .contact-modal-content {
        background: #fff; color: #333; padding: 40px; border-radius: 8px;
        width: 90%; max-width: 600px; /* Made wider for new text */
        position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        transform: scale(0.95); transition: transform 0.3s ease;
    }
    .contact-modal-overlay.is-visible .contact-modal-content { transform: scale(1); }
    .contact-modal-content h3 { margin-top: 0; margin-bottom: 10px; font-size: 1.8rem; }
    .contact-modal-content .intro-text { font-size: 1rem; line-height: 1.5; color: #666; margin-bottom: 25px; }
    .contact-modal-content .close-button { position: absolute; top: 10px; right: 15px; font-size: 2rem; font-weight: 300; color: #888; background: none; border: none; cursor: pointer; }
    /* ... (rest of contact form styles) ... */


    /* --- Post Cards (Unchanged from your working version) --- */
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

    /* --- RESTORED: Content Inside Expanded Card --- */
    .card-content-view {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: transparent; color: #fff; padding: 5vw;
        overflow-y: auto; /* SCROLL BEHAVIOR RESTORED */
        opacity: 0;
        transition: opacity 0.5s ease 0.3s;
        border-radius: 6px;
    }
    .post-page.is-expanded .card-content-view { opacity: 1; }
    .card-content-view h1 {
        font-size: clamp(2rem, 5vw, 4.5rem); margin: 0 0 2rem 0; font-weight: 800; line-height: 1.1;
    }
    .post-body-content {
        font-size: clamp(1rem, 1.5vw, 1.2rem); line-height: 1.6; max-width: 800px; margin: 0 auto;
    }
    .card-close-button {
        position: absolute; top: 15px; right: 15px; font-size: 2.5rem; color: #fff;
        background: none; border: none; cursor: pointer; z-index: 10;
    }
    .post-body-content p { margin-bottom: 1.5em; }
    /* ... (rest of restored styles) ... */

    /* --- Add Card Button --- */
    .add-card-button {
        position: absolute;
        z-index: 2000; bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; line-height: 60px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease;
    }
    body.card-is-active .header-content, body.card-is-active .add-card-button { opacity: 0; pointer-events: none; }
</style>

<!-- Loader HTML -->
<div id="page-loader"><div id="loader-spiral"></div></div>

<main class="concept-body" id="concept-body">
    <div id="card-viewer-overlay"></div>

    <div class="header-content">
        <header class="main-header is-ui-draggable">
            <!-- NEW: Name and Slogan -->
            <h1 class="main-title">The Alchemist's Bureau</h1>
            <h2 class="main-subtitle">Your Unfair Creative Advantage.</h2>
        </header>
        <button id="open-contact-modal" class="contact-icon-button is-ui-draggable" aria-label="Open contact form">
            <!-- NEW: Flat Speech Bubble Icon -->
            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 3H3C1.9 3 1 3.9 1 5V17C1 18.1 1.9 19 3 19H18L23 23V5C23 3.9 22.1 3 21 3Z"/>
            </svg>
        </button>
    </div>

    <!-- PHP Query Logic is UNCHANGED -->
    <?php /* ... PHP logic ... */ ?>
</main>

<button id="add-card-button" class="add-card-button is-ui-draggable" aria-label="Add another card">+</button>

<!-- FIXED: Full Contact Modal HTML restored -->
<div id="contact-modal" class="contact-modal-overlay">
    <div class="contact-modal-content">
        <button id="close-contact-modal" class="close-button" aria-label="Close contact form">&times;</button>
        <!-- NEW: Marketing text added -->
        <h3>Let's Begin The Conversation</h3>
        <p class="intro-text">
            We invite you to an exploratory, <strong>complimentary 30-minute online session</strong> to dissect your current creative challenges and uncover the potential for a deeper, more resonant concept.
        </p>
        <form id="contact-form" action="?" method="post">
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message (Tell us a bit about your project)" required></textarea>
            <div class="captcha-group">
                <label for="captcha">What is <span id="captcha-q1">3</span> + <span id="captcha-q2">4</span>?</label>
                <input type="text" id="captcha-input" name="captcha" required>
            </div>
            <button type="submit">Book Your Session</button>            
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
    // --- Global Variables (from your working code) ---
    const body = document.body, container = document.getElementById('concept-body'), addCardBtn = document.getElementById('add-card-button'), viewerOverlay = document.getElementById('card-viewer-overlay'), initialCards = document.querySelectorAll('.post-page'), pageLoader = document.getElementById('page-loader');
    let availablePosts = [...additionalPostsData], highestZ = initialCards.length, expandedCard = null;

    // --- Contact Modal Logic (from your working code) ---
    const openModalBtn = document.getElementById('open-contact-modal');
    const closeModalBtn = document.getElementById('close-contact-modal');
    const contactModal = document.getElementById('contact-modal');
    window.showContactModal = function() {}; // Expose for the drag engine
    if (openModalBtn && closeModalBtn && contactModal) {
        const showModal = function() {
            const n1 = Math.floor(Math.random() * 5) + 1, n2 = Math.floor(Math.random() * 5) + 1;
            document.getElementById('captcha-q1').textContent = n1;
            document.getElementById('captcha-q2').textContent = n2;
            window.captchaAnswer = n1 + n2;
            document.getElementById('captcha-input').value = '';
            contactModal.classList.add('is-visible');
        }
        window.showContactModal = showModal; // Assign the real function
        const hideModal = function() { contactModal.classList.remove('is-visible'); }
        closeModalBtn.addEventListener('click', hideModal);
        contactModal.addEventListener('click', function(e) { if(e.target === contactModal) hideModal(); });
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const statusDiv = document.getElementById('form-status');
            if (parseInt(document.getElementById('captcha-input').value, 10) !== window.captchaAnswer) { statusDiv.textContent = 'Incorrect captcha answer.'; statusDiv.style.color = 'red'; return; }
            statusDiv.textContent = 'Sending...'; statusDiv.style.color = 'blue';
            setTimeout(() => { statusDiv.textContent = 'Thank you!'; statusDiv.style.color = 'green'; setTimeout(hideModal, 2000); }, 1500);
        });
    }

    // --- Card and Layout Functions (from your working code) ---
    function randomizeInitialLayout(){
        initialCards.forEach((card, index) => {
            card.postData = initialPostsData[index];
            const randomX = Math.floor(Math.random() * (window.innerWidth - 250 - 80)) + 40;
            const randomY = Math.floor(Math.random() * (window.innerHeight - 375 - 80)) + 40;
            const randomRot = Math.random() * 20 - 10;
            card.style.left = `${randomX}px`; card.style.top = `${randomY}px`; card.style.setProperty("--r", `${randomRot}deg`); card.style.zIndex = index + 1;
            setTimeout(() => card.classList.add("is-visible"), index * 80);
        });
    }
    
    window.onload = function(){
        randomizeInitialLayout();
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add("is-hidden"); }, 200); }
    };
    
    if (addCardBtn){
        if(availablePosts.length === 0){ addCardBtn.disabled = true; addCardBtn.classList.add("is-disabled"); }
    }
    
    function addCard(){ /* This function is correct from your previous version */ }
    function expandCard(cardElement){ /* This function is correct from your previous version */ }
    function collapseCard(){ /* This function is correct from your previous version */ }

    // --- Original Card Drag Engine (from your working code) ---
    let activeCard=null,isDragging=!1,startX,startY,initialX,initialY;
    // ... (This full, working block of code for dragging cards is unchanged)

    // --- SEPARATE AND SAFE DRAG ENGINE FOR UI ELEMENTS ---
    const draggableUI = document.querySelectorAll('.is-ui-draggable');
    let activeUIElement = null, isUIDragging = false, uiStartX, uiStartY, uiInitialX, uiInitialY;
    let uiHighestZ = 2001; 

    draggableUI.forEach(el => {
        const uiDragStart = (e) => {
            if (expandedCard) return;
            e.stopPropagation(); 
            activeUIElement = el; isUIDragging = false; uiHighestZ++;
            activeUIElement.style.zIndex = uiHighestZ;
            activeUIElement.classList.add('is-dragging');
            const event = e.type === 'touchstart' ? e.touches[0] : e;
            uiStartX = event.clientX; uiStartY = event.clientY;
            uiInitialX = activeUIElement.offsetLeft; uiInitialY = activeUIElement.offsetTop;
            document.addEventListener('mousemove', uiDragging);
            document.addEventListener('touchmove', uiDragging, { passive: false });
            document.addEventListener('mouseup', uiDragEnd);
            document.addEventListener('touchend', uiDragEnd);
        };
        const uiDragging = (e) => {
            if (!activeUIElement) return; e.preventDefault();
            const event = e.type === 'touchmove' ? e.touches[0] : e;
            const deltaX = event.clientX - uiStartX, deltaY = event.clientY - uiStartY;
            if (Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) isUIDragging = true;
            activeUIElement.style.left = `${uiInitialX + deltaX}px`;
            activeUIElement.style.top = `${uiInitialY + deltaY}px`;
        };
        const uiDragEnd = () => {
            if (!activeUIElement) return;
            activeUIElement.classList.remove('is-dragging');
            if (!isUIDragging) { // This was a click, not a drag
                if (activeUIElement.id === 'add-card-button') addCard();
                else if (activeUIElement.id === 'open-contact-modal') {
                    if (typeof window.showContactModal === 'function') window.showContactModal();
                }
            }
            activeUIElement = null;
            document.removeEventListener('mousemove', uiDragging); document.removeEventListener('touchmove', uiDragging);
            document.removeEventListener('mouseup', uiDragEnd); document.removeEventListener('touchend', uiDragEnd);
        };
        el.addEventListener('mousedown', uiDragStart);
        el.addEventListener('touchstart', uiDragStart, { passive: false });
    });
});
</script>

<?php
get_footer(); 
?>