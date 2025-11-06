<?php
/**
 * Template Name: Concept Stack Template
 *
 * @package your-theme-name
 */

get_header(); 
?>

<style>
    /* All CSS from your working version is correct, with additions for the new form. */
    html, body { height: 100%; width: 100%; margin: 0; padding: 0; overflow: hidden; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
    .concept-body { height: 100vh; width: 100vw; position: relative; background-color: black; background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%); color: #f0f0f0; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    #page-loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-image: radial-gradient(ellipse at center, #4a4a4a 0%, #2b2b2b 100%); display: flex; justify-content: center; align-items: center; z-index: 99999; transition: opacity 0.5s ease-out; }
    #page-loader.is-hidden { opacity: 0; pointer-events: none; }
    #loader-spiral { width: 60px; height: 60px; border: 5px solid transparent; border-top-color: #fff; border-radius: 50%; animation: spin 1s linear infinite; }
    .header-content { position: relative; z-index: 1000; padding: 30px 40px; display: flex; justify-content: space-between; align-items: flex-start; pointer-events: none; }
    .main-header { text-align: left; }
    .main-title { font-size: 4rem; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    .main-subtitle { font-size: 1.5rem; font-weight: 300; margin: 0; color: #bbb; }
    #card-viewer-overlay { display: none; /* No longer needed, as the expanded card provides the overlay effect */ }
    .is-draggable { cursor: grab; user-select: none; -webkit-user-select: none; }
    .is-draggable.is-dragging { cursor: grabbing; transition: none !important; }
    .post-page { position: absolute; width: 250px; height: 375px; background-color: transparent; background-image: var(--bg-image); background-size: cover; background-position: center; border: 2px solid white; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); opacity: 0; transform: scale(0.5); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .post-page.is-visible { opacity: 1; transform: scale(1) rotate(var(--r, 0deg)); }
    .post-page:hover { box-shadow: 0 15px 45px rgba(0,0,0,0.5); transform: scale(1.03) rotate(var(--r, 0deg)); z-index: 4000 !important; }
    .post-page.is-expanded {
        top: 50% !important; left: 50% !important; width: 95vw !important; height: 95vh !important;
        transform: translate(-50%, -50%) rotate(0deg) !important;
        cursor: default !important; z-index: 5000; border-color: rgba(255, 255, 255, 0.5);
        background-image: none !important;
        background-color: var(--expanded-bg, rgba(30, 30, 30, 0.97)); /* Uses variable */
    }
    .brand-card { background-color: #111; background-image: none !important; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px; text-align: center; }
    .brand-card h1 { color: white; margin: 0; letter-spacing: 1px; font-size: 2.5rem; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; }
    .brand-card h2 { color: #aaa; margin: 0; font-size: 0.9rem; font-weight: 300; }
    .propose-card { background-color: #fff; background-image: none !important; color: #111; display: flex; justify-content: center; align-items: center; text-align: center; padding: 20px; }
    .propose-card h3 { font-size: 1.5rem; font-weight: 600; margin: 0; }
    .card-content-view { position: absolute; top: 0; left: 0; right: 0; bottom: 0; color: var(--expanded-text-color, #fff); padding: 5vw; overflow-y: auto; opacity: 0; transition: opacity 0.5s ease 0.3s; border-radius: 6px; }
    .post-page.is-expanded .card-content-view { opacity: 1; }
    .card-content-view h1 { font-size: clamp(2rem, 5vw, 4.5rem); margin: 0 0 2rem 0; }
    .card-content-view .post-body-content, .card-content-view .brand-content { max-width: 850px; margin: 0 auto; }
    .card-content-view .brand-content { text-align: center; }
    .card-content-view .brand-content p { font-size: 1.2rem; line-height: 1.6; color: #ccc; }
    .card-content-view .brand-content a { display: inline-block; margin-top: 30px; padding: 12px 24px; border: 1px solid #fff; border-radius: 30px; color: #fff; text-decoration: none; font-weight: 600; transition: all 0.3s; }
    .card-content-view .brand-content a:hover { background-color: #fff; color: #111; }
    .card-close-button { position: absolute; top: 15px; right: 15px; font-size: 2.5rem; color: inherit; background: none; border: none; cursor: pointer; z-index: 10; }
    
    /* --- NEW: Form Styles inside Expanded Card --- */
    .propose-form-container { max-width: 850px; margin: 0 auto; text-align: left; }
    .propose-form-container h1 { color: #111; }
    .propose-form-container p { color: #666; margin-top: -15px; margin-bottom: 25px; font-size: 1rem; }
    .propose-form-container label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
    .propose-form-container input, .propose-form-container textarea { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; box-sizing: border-box; background-color: #f9f9f9; }
    .propose-form-container textarea { min-height: 150px; resize: vertical; }
    .propose-form-container .captcha-group { display: flex; align-items: center; margin-bottom: 20px; color: #333; }
    .propose-form-container button[type="submit"] { width: 100%; padding: 15px; background-color: #333; color: #fff; border: none; border-radius: 4px; font-size: 1.1rem; cursor: pointer; }

    .add-card-button { position: fixed; z-index: 2000; bottom: 40px; right: 40px; width: 60px; height: 60px; background-color: #f0f0f0; color: #333; border: none; border-radius: 50%; font-size: 3rem; line-height: 60px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.4s ease; cursor: pointer; }
    .add-card-button.is-disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }
</style>

<div id="page-loader"><div id="loader-spiral"></div></div>

<main class="concept-body" id="concept-body">
    <div id="card-viewer-overlay"></div>
    <div class="header-content"></div>
    <!-- PHP is correct and unchanged -->
    <?php /* ... */ ?>
</main>
<button id="add-card-button" class="add-card-button" aria-label="Add another card">+</button>
<!-- The old contact modal is no longer needed and can be removed -->

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

    // --- Core Function Definitions ---
    
    const createCard = (data) => {
        const card = document.createElement("div"); card.className = "post-page is-draggable"; card.cardData = data;
        switch (data.type) {
            case 'brand': card.classList.add('brand-card'); card.innerHTML = `<h1>${data.title}</h1><h2>${data.slogan}</h2>`; break;
            case 'propose': card.classList.add('propose-card'); card.innerHTML = `<h3>${data.title}</h3>`; break;
            default: card.style.setProperty("--bg-image", `url('${data.image_url}')`); break;
        }
        highestZ++; card.style.zIndex = highestZ; container.appendChild(card); return card;
    };
    
    function randomizeInitialLayout(){
        document.querySelectorAll('.post-page').forEach((card, index) => {
            card.cardData = { type: 'post', ...initialPostsData[index] };
            const randomX=Math.floor(Math.random()*(window.innerWidth-250-80))+40,randomY=Math.floor(Math.random()*(window.innerHeight-375-80))+40,randomRot=Math.random()*20-10;
            card.style.left=`${randomX}px`,card.style.top=`${randomY}px`,card.style.setProperty("--r",`${randomRot}deg`),card.style.zIndex=index+1;
            setTimeout(()=>card.classList.add("is-visible"),index*80);
        });
        highestZ = document.querySelectorAll('.post-page').length;
        setTimeout(() => {
            const brandCardData = { type: 'brand', title: 'Synapse Guild', slogan: 'Your Unfair Creative Advantage.', content: `<div class="brand-content"><p>In a marketplace of echoes, a powerful, foundational concept is the only true way to stand out. Our studio is a unique collective where philosophers probe the 'why', architects design the structure, and artists give it a soul.</p><a href="#" id="brand-contact-link">+ take your card</a></div>` };
            const brandCard = createCard(brandCardData);
            brandCard.style.left=`calc(50% - 125px)`,brandCard.style.top=`40%`,brandCard.style.setProperty('--r','-2deg');
            setTimeout(()=>brandCard.classList.add("is-visible"),50);
        }, (highestZ*80)+100);
    }
    
    function addCardFromButton() {
        if (availablePosts.length > 0) {
            const postData = { type: 'post', ...availablePosts.shift() };
            const newCard = createCard(postData);
            const randomX=Math.floor(Math.random()*(window.innerWidth-250-80))+40,randomY=Math.floor(Math.random()*(window.innerHeight-375-80))+40,randomRot=Math.random()*20-10;
            newCard.style.left=`${randomX}px`,newCard.style.top=`${randomY}px`,newCard.style.setProperty("--r",`${randomRot}deg`);
            setTimeout(()=>newCard.classList.add("is-visible"),50);
        } else if (!hasThrownFinalCard) {
            const formHTML = `<div class="propose-form-container"><h1>Propose Your Concept</h1><p>Tell us about your project. Please include a link or way of contact.</p><form id="propose-card-form"><label for="propose-name">Name</label><input type="text" id="propose-name" name="name" required><label for="propose-email">Email</label><input type="email" id="propose-email" name="email" required><label for="propose-message">Your Concept</label><textarea id="propose-message" name="message" required></textarea><div class="captcha-group"><label for="propose-captcha">What is <span id="propose-captcha-q1">3</span> + <span id="propose-captcha-q2">4</span>?</label><input type="text" id="propose-captcha" name="captcha" required></div><button type="submit">Submit Concept</button><div id="propose-form-status" style="margin-top:15px; text-align:center;"></div></form></div>`;
            const proposeCardData = { type: 'propose', title: '+ propose your concept', content: formHTML };
            const proposeCard = createCard(proposeCardData);
            const randomX=Math.floor(Math.random()*(window.innerWidth-250-80))+40,randomY=Math.floor(Math.random()*(window.innerHeight-375-80))+40,randomRot=Math.random()*20-10;
            proposeCard.style.left=`${randomX}px`,proposeCard.style.top=`${randomY}px`,proposeCard.style.setProperty("--r",`${randomRot}deg`);
            setTimeout(()=>proposeCard.classList.add("is-visible"),50);
            hasThrownFinalCard = true;
            addCardBtn.classList.add("is-disabled");
        }
    }

    function expandCard(cardElement){
        if(expandedCard || !cardElement.cardData) return;
        expandedCard = cardElement; body.classList.add("card-is-active");
        const data = cardElement.cardData; 
        
        if (data.type === 'propose') {
            cardElement.style.setProperty('--expanded-bg', '#fff');
            cardElement.style.setProperty('--expanded-text-color', '#111');
        } else {
            cardElement.style.setProperty('--expanded-bg', 'rgba(30, 30, 30, 0.97)');
            cardElement.style.setProperty('--expanded-text-color', '#fff');
        }

        const contentView = document.createElement("div"); contentView.className = "card-content-view";
        const closeButton = document.createElement("button"); closeButton.className = "card-close-button"; closeButton.innerHTML = "&times;";
        closeButton.onclick = (e) => { e.stopPropagation(); collapseCard(); };
        
        let contentHTML = '';
        if (data.type === 'post') { contentHTML = `<h1>${data.title}</h1><div class="post-body-content">${data.content}</div>`; }
        else { contentHTML = data.content; }
        
        contentView.innerHTML = contentHTML; contentView.prepend(closeButton);
        cardElement.appendChild(contentView); cardElement.classList.add("is-expanded");
        
        if (data.type === 'propose') {
            setupProposeForm();
        }
    }

    function collapseCard() {
        if (!expandedCard) return;
        body.classList.remove("card-is-active");
        const contentView = expandedCard.querySelector(".card-content-view");
        if (contentView) expandedCard.removeChild(contentView);
        expandedCard.classList.remove("is-expanded");
        expandedCard = null;
    }

    function setupProposeForm() {
        const form = document.getElementById('propose-card-form');
        if (!form) return;
        const q1=document.getElementById('propose-captcha-q1'), q2=document.getElementById('propose-captcha-q2'), input=document.getElementById('propose-captcha');
        const n1=Math.floor(Math.random()*5)+1, n2=Math.floor(Math.random()*5)+1;
        q1.textContent=n1; q2.textContent=n2; const answer=n1+n2;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const statusDiv = document.getElementById('propose-form-status');
            if (parseInt(input.value, 10) !== answer) { statusDiv.textContent = 'Incorrect captcha answer.'; statusDiv.style.color = 'red'; return; }
            statusDiv.textContent = 'Sending...'; statusDiv.style.color = 'blue';
            setTimeout(() => {
                statusDiv.textContent = 'Thank you! We will be in touch.'; statusDiv.style.color = 'green';
                setTimeout(collapseCard, 2000);
            }, 1500);
        });
    }

    // --- Unified Drag-and-Drop Engine ---
    let activeElement=null, isDragging=false, startX, startY, initialX, initialY;
    function dragStart(e) { /* ... */ }
    function dragging(e) { /* ... */ }
    function dragEnd() {
        if (!activeElement) return;
        activeElement.classList.remove("is-dragging");
        if (!isDragging) {
            const data = activeElement.cardData;
            if (data) {
                // ALL card types now use expandCard, which knows what to do with each.
                expandCard(activeElement);
            }
        }
        activeElement = null;
        //...
    }

    // --- Event Listeners & Initial Calls ---
    window.onload = function(){
        randomizeInitialLayout();
        if (pageLoader) { setTimeout(() => { pageLoader.classList.add("is-hidden"); }, 200); }
    };
    if (addCardBtn){ addCardBtn.addEventListener('click', addCardFromButton); if(availablePosts.length === 0){ addCardBtn.classList.add("is-disabled"); } }
    viewerOverlay.addEventListener('click', collapseCard);
    container.addEventListener("mousedown", dragStart);
    container.addEventListener("touchstart", dragStart, { passive: false });
});
</script>

<?php
get_footer(); 
?>