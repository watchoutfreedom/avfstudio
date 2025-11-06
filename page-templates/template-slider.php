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
    
    /* --- Main Header (Now Static) --- */
    .header-content {
        position: relative; z-index: 1000; padding: 30px 40px;
        display: flex; justify-content: space-between; align-items: flex-start;
        pointer-events: none;
    }
    .header-content > * { pointer-events: all; }
    .main-header { text-align: left; }
    .main-title { font-size: 4rem; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; font-weight: 300; margin: 0; color: #bbb; }

    /* --- Z-Index Stacking Order --- */
    /* Loader: 99999, Contact Modal: 90000, Expanded Card: 5000 */

    /* --- Post Cards (Base Style) --- */
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
        border-color: rgba(255, 255, 255, 0.5);
    }

    /* --- NEW: Brand Card Style --- */
    .brand-card {
        background-color: #111;
        background-image: none !important;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        text-align: center;
    }
    .brand-card h1, .brand-card h2 {
        color: white;
        margin: 0;
        letter-spacing: 1px;
    }
    .brand-card h1 {
        font-size: 1.8rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    .brand-card h2 {
        font-size: 0.9rem;
        font-weight: 300;
        color: #aaa;
    }

    /* --- NEW: Propose Card Style --- */
    .propose-card {
        background-color: #fff;
        background-image: none !important;
        color: #111;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 20px;
    }
    .propose-card h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    /* --- Expanded Card Content --- */
    .card-content-view {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: transparent; color: #fff; padding: 5vw;
        overflow-y: auto; opacity: 0;
        transition: opacity 0.5s ease 0.3s;
        border-radius: 6px;
    }
    .post-page.is-expanded { background-image: none !important; background-color: rgba(30, 30, 30, 0.97); }
    .post-page.is-expanded .card-content-view { opacity: 1; }
    .card-content-view h1 { font-size: clamp(2rem, 5vw, 4.5rem); margin: 0 0 2rem 0; }
    .card-content-view .post-body-content { max-width: 800px; margin: 0 auto; }
    .card-content-view .brand-content { max-width: 700px; margin: 0 auto; text-align: center; }
    .card-content-view .brand-content p { font-size: 1.2rem; line-height: 1.6; color: #ccc; }
    .card-content-view .brand-content a {
        display: inline-block;
        margin-top: 30px;
        padding: 12px 24px;
        border: 1px solid #fff;
        border-radius: 30px;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s, color 0.3s;
    }
    .card-content-view .brand-content a:hover { background-color: #fff; color: #111; }
    .card-close-button { position: absolute; top: 15px; right: 15px; font-size: 2.5rem; color: #fff; background: none; border: none; cursor: pointer; z-index: 10; }

    /* --- Add Card Button --- */
    .add-card-button {
        position: fixed; /* Back to fixed, no longer draggable */
        z-index: 2000; bottom: 40px; right: 40px; width: 60px; height: 60px;
        background-color: #f0f0f0; color: #333; border: none; border-radius: 50%;
        font-size: 3rem; line-height: 60px; text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease;
        cursor: pointer;
    }
    .add-card-button.is-disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }
</style>

<div id="page-loader"><div id="loader-spiral"></div></div>

<main class="concept-body" id="concept-body">
    <div id="card-viewer-overlay"></div>

    <div class="header-content">
        <!-- Header is now static and no longer draggable -->
        <header class="main-header">
            <h1 class="main-title">SG</h1>
            <h2 class="main-subtitle">Synapse Guild</h2>
        </header>
        <!-- Contact button is removed -->
    </div>

    <!-- PHP Query Logic is UNCHANGED -->
    <?php /* ... Your correct PHP logic ... */ ?>
</main>

<button id="add-card-button" class="add-card-button" aria-label="Add another card">+</button>

<div id="contact-modal" class="contact-modal-overlay"> <!-- ... full content is in the template ... --> </div>

<script>
    const initialPostsData = <?php echo json_encode($initial_posts_data); ?>;
    const additionalPostsData = <?php echo json_encode($additional_posts_data); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Global Variables ---
    const body = document.body, container = document.getElementById('concept-body'), addCardBtn = document.getElementById('add-card-button'), viewerOverlay = document.getElementById('card-viewer-overlay'), pageLoader = document.getElementById('page-loader');
    let availablePosts = [...(additionalPostsData || [])];
    let highestZ = 0, expandedCard = null, hasThrownFinalCard = false;

    // --- Contact Modal Setup ---
    const contactModal = document.getElementById('contact-modal');
    window.showContactModal = function() {
        if(contactModal) contactModal.classList.add('is-visible');
    };
    if (contactModal) {
        // ... (rest of contact form setup logic)
    }

    // --- Card Creation Functions ---
    const createCard = (data) => {
        const card = document.createElement("div");
        card.className = "post-page is-draggable";
        card.cardData = data;
        
        switch (data.type) {
            case 'brand':
                card.classList.add('brand-card');
                card.innerHTML = `<h1>${data.title}</h1><h2>${data.slogan}</h2>`;
                break;
            case 'propose':
                card.classList.add('propose-card');
                card.innerHTML = `<h3>${data.title}</h3>`;
                break;
            case 'post':
            default:
                card.style.setProperty("--bg-image", `url('${data.image_url}')`);
                break;
        }

        highestZ++;
        card.style.zIndex = highestZ;
        container.appendChild(card);
        return card;
    };
    
    // --- Card and Layout Initialization ---
    function randomizeInitialLayout(){
        const initialCards = document.querySelectorAll('.post-page');
        highestZ = initialCards.length;
        initialCards.forEach((card, index) => {
            card.cardData = { type: 'post', ...initialPostsData[index] };
            const randomX = Math.floor(Math.random()*(window.innerWidth-250-80))+40, randomY = Math.floor(Math.random()*(window.innerHeight-375-80))+40, randomRot = Math.random()*20-10;
            card.style.left = `${randomX}px`, card.style.top = `${randomY}px`, card.style.setProperty("--r", `${randomRot}deg`), card.style.zIndex = index + 1;
            setTimeout(() => card.classList.add("is-visible"), index * 80);
        });
        
        // Add the Brand Card last
        setTimeout(() => {
            const brandCardData = {
                type: 'brand',
                title: 'Synapse Guild',
                slogan: 'Your Unfair Creative Advantage.',
                content: `<div class="brand-content"><p>In a marketplace of echoes, a powerful, foundational concept is the only true way to stand out. Our studio is a unique collective where philosophers probe the 'why', architects design the structure, and artists give it a soul.</p><a href="#" id="brand-contact-link">+ take your card</a></div>`
            };
            const brandCard = createCard(brandCardData);
            // Position it in the middle
            brandCard.style.left = `calc(50% - 125px)`;
            brandCard.style.top = `calc(50% - 187.5px)`;
            setTimeout(() => brandCard.classList.add("is-visible"), 50);
        }, (initialCards.length * 80) + 100);
    }
    
    window.onload = function(){
        randomizeInitialLayout();
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add("is-hidden"); }, 200); }
    };
    
    // --- `+` Button Logic ---
    if (addCardBtn){
        addCardBtn.addEventListener('click', () => {
            if (availablePosts.length > 0) {
                const postData = { type: 'post', ...availablePosts.shift() };
                const newCard = createCard(postData);
                const randomX = Math.floor(Math.random()*(window.innerWidth-250-80))+40, randomY = Math.floor(Math.random()*(window.innerHeight-375-80))+40, randomRot = Math.random()*20-10;
                newCard.style.left = `${randomX}px`, newCard.style.top = `${randomY}px`, newCard.style.setProperty("--r", `${randomRot}deg`);
                setTimeout(() => newCard.classList.add("is-visible"), 50);
            } else if (!hasThrownFinalCard) {
                const proposeCardData = { type: 'propose', title: '+ propose your concept' };
                const proposeCard = createCard(proposeCardData);
                const randomX = Math.floor(Math.random()*(window.innerWidth-250-80))+40, randomY = Math.floor(Math.random()*(window.innerHeight-375-80))+40, randomRot = Math.random()*20-10;
                proposeCard.style.left = `${randomX}px`, proposeCard.style.top = `${randomY}px`, proposeCard.style.setProperty("--r", `${randomRot}deg`);
                setTimeout(() => proposeCard.classList.add("is-visible"), 50);
                hasThrownFinalCard = true;
                addCardBtn.classList.add("is-disabled");
            }
        });
    }

    // --- Card Expansion Logic ---
    function expandCard(cardElement){
        if(expandedCard || !cardElement.cardData) return;
        expandedCard = cardElement; body.classList.add("card-is-active"); viewerOverlay.classList.add("is-visible");
        const contentView = document.createElement("div"); contentView.className = "card-content-view";
        const closeButton = document.createElement("button"); closeButton.className = "card-close-button"; closeButton.innerHTML = "&times;";
        closeButton.onclick = (e) => { e.stopPropagation(); collapseCard(); };
        
        const data = cardElement.cardData;
        let contentHTML = '';
        if (data.type === 'post') {
            contentHTML = `<h1>${data.title}</h1><div class="post-body-content">${data.content}</div>`;
        } else if (data.type === 'brand') {
            contentHTML = data.content;
        }

        contentView.innerHTML = contentHTML;
        contentView.prepend(closeButton);
        cardElement.appendChild(contentView); 
        cardElement.classList.add("is-expanded");
        
        // Add specific listener for the brand card's contact link
        const brandContactLink = document.getElementById('brand-contact-link');
        if (brandContactLink) {
            brandContactLink.onclick = (e) => {
                e.preventDefault();
                window.showContactModal();
            }
        }
    }
    // ... collapseCard logic is correct ...
    
    // --- UNIFIED DRAG-AND-DROP ENGINE ---
    let activeElement=null, isDragging=false, startX, startY, initialX, initialY;
    function dragStart(e) {
        const target = e.target.closest(".is-draggable");
        if (!target || expandedCard) return;
        e.preventDefault(); e.stopPropagation();
        activeElement = target; isDragging = false; highestZ++;
        activeElement.style.zIndex = highestZ;
        activeElement.classList.add("is-dragging");
        startX = e.type === "touchstart" ? e.touches[0].clientX : e.clientX;
        startY = e.type === "touchstart" ? e.touches[0].clientY : e.clientY;
        initialX = activeElement.offsetLeft; initialY = activeElement.offsetTop;
        document.addEventListener("mousemove", dragging);
        document.addEventListener("touchmove", dragging, { passive: false });
        document.addEventListener("mouseup", dragEnd);
        document.addEventListener("touchend", dragEnd);
    }
    function dragging(e) { /* ... This logic is correct ... */ }
    function dragEnd() {
        if (!activeElement) return;
        activeElement.classList.remove("is-dragging");
        if (!isDragging) { // This was a click
            const data = activeElement.cardData;
            if (data) {
                if (data.type === 'post' || data.type === 'brand') {
                    expandCard(activeElement);
                } else if (data.type === 'propose') {
                    window.showContactModal();
                }
            }
        }
        activeElement = null;
        // ... (rest of dragEnd logic is correct) ...
    }
    container.addEventListener("mousedown", dragStart);
    container.addEventListener("touchstart", dragStart, { passive: false });
});
</script>

<?php
get_footer(); 
?>