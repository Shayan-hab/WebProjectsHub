// State
let cart = [];
let coupon = null;
let useCapture = false;

// Coupons
const coupons = {
    SAVE10: 0.1, // 10% off
    FLAT20: 0.2  // 20% off
};

// DOM
const products = document.getElementById('products');
const cartList = document.getElementById('cartList');
const totalPrice = document.getElementById('totalPrice');
const couponCode = document.getElementById('couponCode');
const applyCoupon = document.getElementById('applyCoupon');
const couponStatus = document.getElementById('couponStatus');
const phaseToggle = document.getElementById('phaseToggle');
const phaseStatus = document.getElementById('phaseStatus');
const logOutput = document.getElementById('logOutput');
const jsCounter = document.getElementById('jsCounter');
const ctx = jsCounter.getContext('2d');

// Logging
function logEvent(msg) {
    const time = new Date().toLocaleString();
    logOutput.textContent += `[${time}] ${msg}\n`;
    logOutput.scrollTop = logOutput.scrollHeight;
}

function logPropagation(e, action) {
    const path = [];
    let el = e.target;
    while (el && el !== document) {
        path.push(`${el.tagName.toLowerCase()}${el.id ? `#${el.id}` : ''}`);
        el = el.parentElement;
    }
    logEvent(`${action} - Target: ${e.target.tagName.toLowerCase()}${e.target.id ? `#${e.target.id}` : ''}, Current: ${e.currentTarget.tagName.toLowerCase()}${e.currentTarget.id ? `#${e.currentTarget.id}` : ''}, Path: ${path.join(' -> ')}`);
}

// Canvas counter (applet fallback)
let itemCount = 0;
function updateCounter() {
    ctx.clearRect(0, 0, jsCounter.width, jsCounter.height);
    ctx.fillStyle = '#17a2b8';
    ctx.fillRect(0, 0, jsCounter.width, jsCounter.height);
    ctx.fillStyle = 'white';
    ctx.font = '20px Arial';
    ctx.textAlign = 'center';
    ctx.fillText(`Items: ${itemCount}`, jsCounter.width / 2, jsCounter.height / 2 + 5);
}

// Cart update
function renderCart() {
    cartList.innerHTML = '';
    let total = 0;
    itemCount = 0;

    cart.forEach(item => {
        itemCount += item.qty;
        const li = document.createElement('li');
        li.innerHTML = `${item.name} - $${item.price} x ${item.qty} <button class="remove-btn" data-id="${item.id}">Remove</button>`;
        cartList.appendChild(li);
        total += item.price * item.qty;
    });

    if (coupon && coupons[coupon]) {
        total *= (1 - coupons[coupon]);
    }

    totalPrice.textContent = total.toFixed(2);
    document.getElementById('cartCount').textContent = itemCount; // Update header cart count
    jsCounter.style.display = 'block';
    updateCounter();
}

// Add item
function addItem(id, name, price) {
    const item = cart.find(i => i.id === id);
    if (item) {
        item.qty += 1;
    } else {
        cart.push({ id, name, price: parseFloat(price), qty: 1 });
    }
    renderCart();
    logEvent(`Added ${name}`);
}

// Remove item
function removeItem(id, e) {
    e.stopPropagation();
    cart = cart.filter(i => i.id !== id);
    renderCart();
    logEvent(`Removed item ID ${id}`);
}

// Coupon
function handleCoupon() {
    const code = couponCode.value.trim().toUpperCase();
    if (coupons[code]) {
        coupon = code;
        couponStatus.textContent = `${code} applied (${coupons[code] * 100}% off)`;
        couponStatus.style.color = '#28a745';
        renderCart();
        logEvent(`Coupon ${code} applied`);
    } else {
        coupon = null;
        couponStatus.textContent = 'Invalid code';
        couponStatus.style.color = '#dc3545';
        renderCart();
        logEvent(`Invalid coupon ${code}`);
    }
    couponCode.value = '';
}

// Event setup
function setupEvents(capture) {
    // Clear old listeners
    products.removeEventListener('click', handleClick, true);
    products.removeEventListener('click', handleClick, false);
    cartList.removeEventListener('click', handleCartClick, true);
    cartList.removeEventListener('click', handleCartClick, false);
    applyCoupon.removeEventListener('click', handleCoupon, true);
    applyCoupon.removeEventListener('click', handleCoupon, false);

    // Add new
    products.addEventListener('click', handleClick, capture);
    cartList.addEventListener('click', handleCartClick, capture);
    applyCoupon.addEventListener('click', handleCoupon, capture);

    function handleClick(e) {
        if (e.target.classList.contains('add-btn')) {
            const item = e.target.closest('.item');
            addItem(item.dataset.id, item.dataset.name, item.dataset.price);
            logPropagation(e, 'Add Item');
        }
    }

    function handleCartClick(e) {
        if (e.target.classList.contains('remove-btn')) {
            removeItem(e.target.dataset.id, e);
            logPropagation(e, 'Remove Item');
        }
    }
}

// Toggle phase
phaseToggle.addEventListener('click', () => {
    useCapture = !useCapture;
    phaseToggle.textContent = `Switch to ${useCapture ? 'Bubbling' : 'Capturing'}`;
    phaseStatus.textContent = `Mode: ${useCapture ? 'Capturing' : 'Bubbling'}`;
    setupEvents(useCapture);
    logEvent(`Phase: ${useCapture ? 'Capturing' : 'Bubbling'}`);
});

// Init
setupEvents(useCapture);
logEvent('System initialized');