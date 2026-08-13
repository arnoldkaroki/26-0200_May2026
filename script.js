// ============================================================
// QUICKBITE FOOD DELIVERY
// MAIN JAVASCRIPT
// ============================================================


// ═══════════════════════════════════════════════════════════
// MODAL FUNCTIONS
// ═══════════════════════════════════════════════════════════

// Show a modal
function openModal(id) {
    var modal = document.getElementById(id);

    if (modal) {
        modal.classList.add('active');
    }
}


// Hide a modal
function closeModal(id) {
    var modal = document.getElementById(id);

    if (modal) {
        modal.classList.remove('active');
    }
}


// ═══════════════════════════════════════════════════════════
// PROMOTIONAL SOUND
// ═══════════════════════════════════════════════════════════

function playPromoSound() {

    try {

        var AudioCtx = window.AudioContext || window.webkitAudioContext;

        if (!AudioCtx) return;

        var ctx = new AudioCtx();

        if (ctx.state === 'suspended') {
            ctx.resume();
        }

        // QuickBite two-note notification sound
        var notes = [
            {
                freq: 880,
                start: 0,
                duration: 0.18
            },
            {
                freq: 1175,
                start: 0.16,
                duration: 0.30
            }
        ];

        notes.forEach(function(note) {

            var oscillator = ctx.createOscillator();
            var gain = ctx.createGain();

            oscillator.type = 'sine';
            oscillator.frequency.value = note.freq;

            var time = ctx.currentTime + note.start;

            gain.gain.setValueAtTime(0.0001, time);

            gain.gain.exponentialRampToValueAtTime(
                0.25,
                time + 0.02
            );

            gain.gain.exponentialRampToValueAtTime(
                0.0001,
                time + note.duration
            );

            oscillator.connect(gain);
            gain.connect(ctx.destination);

            oscillator.start(time);
            oscillator.stop(time + note.duration + 0.02);

        });

    } catch (error) {

        console.warn(
            'QuickBite promotional sound could not play:',
            error
        );

    }

}


// Show promotional popup
function showPromo() {

    openModal('promo-modal');

    playPromoSound();

}


// ═══════════════════════════════════════════════════════════
// WELCOME & PROMOTIONAL POPUPS
// ═══════════════════════════════════════════════════════════

window.addEventListener('load', function() {

    // Show welcome message after 1.5 seconds
    setTimeout(function() {

        if (document.getElementById('welcome-modal')) {
            openModal('welcome-modal');
        }

    }, 1500);


    // Show promotional offer after 8 seconds
    setTimeout(function() {

        var welcomeModal =
            document.getElementById('welcome-modal');

        if (!welcomeModal ||
            !welcomeModal.classList.contains('active')) {

            showPromo();

        } else {

            // Wait until the welcome popup is closed
            setTimeout(function() {
                showPromo();
            }, 3000);

        }

    }, 8000);

});


// ═══════════════════════════════════════════════════════════
// CLOSE MODAL WHEN CLICKING OUTSIDE
// ═══════════════════════════════════════════════════════════

document.addEventListener('DOMContentLoaded', function() {

    document
        .querySelectorAll('.modal-overlay')
        .forEach(function(modal) {

            modal.addEventListener('click', function(event) {

                if (event.target === modal) {
                    modal.classList.remove('active');
                }

            });

        });

});


// ═══════════════════════════════════════════════════════════
// CLOSE MODALS WITH ESCAPE KEY
// ═══════════════════════════════════════════════════════════

document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {

        document
            .querySelectorAll('.modal-overlay.active')
            .forEach(function(modal) {

                modal.classList.remove('active');

            });

    }

});


// ═══════════════════════════════════════════════════════════
// SIGN-UP FORM VALIDATION
// ═══════════════════════════════════════════════════════════


// Validate email
function isValidEmail(email) {

    var emailPattern =
        /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    return emailPattern.test(email);

}


// Validate name
function isValidName(name) {

    var namePattern = /^[a-zA-Z\s]{2,}$/;

    return namePattern.test(name.trim());

}


// Validate Kenyan/international phone number
function isValidPhone(phone) {

    var cleanedPhone =
        phone.replace(/\s/g, '');

    var phonePattern =
        /^\+?[0-9]{10,13}$/;

    return phonePattern.test(cleanedPhone);

}


// ═══════════════════════════════════════════════════════════
// SHOW FIELD ERROR
// ═══════════════════════════════════════════════════════════

function showError(fieldId, message) {

    var field = document.getElementById(fieldId);
    var error = document.getElementById(fieldId + '-error');

    if (field) {
        field.classList.add('invalid');
    }

    if (error) {
        error.textContent = message;
        error.classList.add('show');
    }

}


// Clear field error
function clearError(fieldId) {

    var field = document.getElementById(fieldId);
    var error = document.getElementById(fieldId + '-error');

    if (field) {
        field.classList.remove('invalid');
    }

    if (error) {
        error.classList.remove('show');
    }

}


// ═══════════════════════════════════════════════════════════
// SIGN-UP FORM
// ═══════════════════════════════════════════════════════════

document.addEventListener('DOMContentLoaded', function() {

    var emailInput =
        document.getElementById('signup-email');

    if (emailInput) {

        emailInput.addEventListener('blur', function() {

            var email =
                emailInput.value.trim();

            if (email === '') {

                clearError('signup-email');

            } else if (!isValidEmail(email)) {

                showError(
                    'signup-email',
                    'Please enter a valid email address.'
                );

            } else {

                clearError('signup-email');

            }

        });

    }


    var signupForm =
        document.getElementById('signup-form');

    if (!signupForm) return;


    signupForm.addEventListener('submit', function(event) {

        event.preventDefault();


        var name =
            document.getElementById('signup-name').value.trim();

        var email =
            document.getElementById('signup-email').value.trim();

        var phone =
            document.getElementById('signup-phone').value.trim();

        var gender =
            document.getElementById('signup-gender').value;


        var valid = true;


        // Name
        if (!isValidName(name)) {

            showError(
                'signup-name',
                'Please enter a valid name.'
            );

            valid = false;

        } else {

            clearError('signup-name');

        }


        // Email
        if (!isValidEmail(email)) {

            showError(
                'signup-email',
                'Please enter a valid email address.'
            );

            valid = false;

        } else {

            clearError('signup-email');

        }


        // Phone
        if (!isValidPhone(phone)) {

            showError(
                'signup-phone',
                'Please enter a valid phone number.'
            );

            valid = false;

        } else {

            clearError('signup-phone');

        }


        // Gender
        if (gender === '') {

            showError(
                'signup-gender',
                'Please select your gender.'
            );

            valid = false;

        } else {

            clearError('signup-gender');

        }


        if (!valid) return;


        var submitButton =
            signupForm.querySelector(
                'button[type="submit"]'
            );


        var originalText =
            submitButton.textContent;

        submitButton.disabled = true;
        submitButton.textContent = 'Creating...';


        // Send registration to PHP backend
        fetch('backend/signup.php', {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json'
            },

            body: JSON.stringify({

                name: name,
                email: email,
                phone: phone,
                gender: gender

            })

        })


        .then(function(response) {

            return response.json()
                .then(function(data) {

                    return {
                        status: response.status,
                        data: data
                    };

                });

        })


        .then(function(result) {

            var data = result.data;


            // Server-side validation errors
            if (result.status === 400 && data.errors) {

                Object.keys(data.errors)
                    .forEach(function(field) {

                        showError(
                            'signup-' + field,
                            data.errors[field]
                        );

                    });

                return;

            }


            // Other error
            if (!data.ok) {

                showError(
                    'signup-email',
                    data.message ||
                    'Registration failed. Please try again.'
                );

                return;

            }


            // Successful registration
            var successMessage =
                document.getElementById(
                    'signup-success'
                );


            if (successMessage) {

                successMessage.innerHTML =
                    '🍔 Welcome <strong>' +
                    name +
                    '</strong>! Your QuickBite account has been created successfully.';

                successMessage.classList.add('show');

            }


            signupForm.reset();


            setTimeout(function() {

                closeModal('signup-modal');

                if (successMessage) {
                    successMessage.classList.remove('show');
                }

            }, 3000);

        })


        .catch(function(error) {

            console.error(
                'Registration request failed:',
                error
            );

            showError(
                'signup-email',
                'Could not connect to the server.'
            );

        })


        .finally(function() {

            submitButton.disabled = false;
            submitButton.textContent = originalText;

        });

    });

});


// ═══════════════════════════════════════════════════════════
// SHOPPING CART
// ═══════════════════════════════════════════════════════════


// QuickBite localStorage key
var CART_KEY = 'quickbite_cart';


// Delivery fee in Kenyan Shillings
var DELIVERY_FEE = 300;


// Free delivery for orders above this amount
var FREE_DELIVERY_THRESHOLD = 3000;


// ═══════════════════════════════════════════════════════════
// LOAD CART
// ═══════════════════════════════════════════════════════════

function loadCart() {

    try {

        return JSON.parse(
            localStorage.getItem(CART_KEY)
        ) || [];

    } catch (error) {

        return [];

    }

}


// Save cart
function saveCart(cart) {

    localStorage.setItem(
        CART_KEY,
        JSON.stringify(cart)
    );

    updateCartCount();

}


// Count total products
function cartItemCount() {

    return loadCart().reduce(
        function(total, item) {

            return total + item.qty;

        },
        0
    );

}


// Update cart badge
function updateCartCount() {

    var badge =
        document.getElementById('cart-count');

    if (badge) {
        badge.textContent = cartItemCount();
    }

}


// Format Kenyan currency
function formatKES(amount) {

    return 'KES ' +
        Number(amount).toLocaleString('en-KE');

}


// ═══════════════════════════════════════════════════════════
// ADD PRODUCT TO CART
// ═══════════════════════════════════════════════════════════

function addToCart(id, name, price) {

    var cart = loadCart();


    var existing =
        cart.find(function(item) {

            return item.id === id;

        });


    if (existing) {

        existing.qty += 1;

    } else {

        cart.push({

            id: id,
            name: name,
            price: price,
            qty: 1

        });

    }


    saveCart(cart);

}


// ═══════════════════════════════════════════════════════════
// CHANGE QUANTITY
// ═══════════════════════════════════════════════════════════

function setQty(id, quantity) {

    var cart = loadCart();


    var item =
        cart.find(function(product) {

            return product.id === id;

        });


    if (!item) return;


    item.qty = quantity;


    if (item.qty <= 0) {

        cart = cart.filter(function(product) {

            return product.id !== id;

        });

    }


    saveCart(cart);

    renderCart();

}


// ═══════════════════════════════════════════════════════════
// REMOVE ITEM
// ═══════════════════════════════════════════════════════════

function removeFromCart(id) {

    var cart =
        loadCart().filter(function(item) {

            return item.id !== id;

        });


    saveCart(cart);

    renderCart();

}


// ═══════════════════════════════════════════════════════════
// DISPLAY CART
// ═══════════════════════════════════════════════════════════

function renderCart() {

    var cart = loadCart();


    var itemsBox =
        document.getElementById('cart-items');

    var emptyBox =
        document.getElementById('cart-empty');

    var summary =
        document.getElementById('cart-summary');


    if (!itemsBox ||
        !emptyBox ||
        !summary) return;


    // Empty cart
    if (cart.length === 0) {

        itemsBox.innerHTML = '';

        emptyBox.style.display = 'block';

        summary.style.display = 'none';

        return;

    }


    emptyBox.style.display = 'none';

    summary.style.display = 'block';


    var html = '';

    var subtotal = 0;


    cart.forEach(function(item) {

        var lineTotal =
            item.price * item.qty;


        subtotal += lineTotal;


        html +=

            '<div class="cart-item">' +

                '<div class="cart-item-info">' +

                    '<strong>' +
                    escapeHtml(item.name) +
                    '</strong>' +

                    '<span>' +
                    formatKES(item.price) +
                    ' each</span>' +

                '</div>' +


                '<div class="cart-item-qty">' +

                    '<button onclick="setQty(' +
                    item.id + ',' +
                    (item.qty - 1) +
                    ')">−</button>' +

                    '<span>' +
                    item.qty +
                    '</span>' +

                    '<button onclick="setQty(' +
                    item.id + ',' +
                    (item.qty + 1) +
                    ')">+</button>' +

                '</div>' +


                '<div class="cart-item-total">' +
                formatKES(lineTotal) +
                '</div>' +


                '<button class="cart-item-remove" ' +
                'onclick="removeFromCart(' +
                item.id +
                ')" title="Remove">🗑</button>' +

            '</div>';

    });


    itemsBox.innerHTML = html;


    // Calculate delivery
    var delivery =
        subtotal >= FREE_DELIVERY_THRESHOLD
            ? 0
            : DELIVERY_FEE;


    document.getElementById(
        'cart-subtotal'
    ).textContent =
        formatKES(subtotal);


    document.getElementById(
        'cart-delivery'
    ).textContent =
        delivery === 0
            ? 'FREE'
            : formatKES(delivery);


    document.getElementById(
        'cart-total'
    ).textContent =
        formatKES(
            subtotal + delivery
        );

}


// ═══════════════════════════════════════════════════════════
// OPEN CART
// ═══════════════════════════════════════════════════════════

function openCart() {

    renderCart();

    openModal('cart-modal');

}


// ═══════════════════════════════════════════════════════════
// GO TO CHECKOUT
// ═══════════════════════════════════════════════════════════

function goToCheckout() {

    if (loadCart().length === 0) {
        return;
    }


    closeModal('cart-modal');

    openModal('checkout-modal');

}


// ═══════════════════════════════════════════════════════════
// PRODUCT DISPLAY SETTINGS
// ═══════════════════════════════════════════════════════════

var DISPLAY = {

    availability: {

        in_stock: 'In Stock',

        limited: 'Limited Stock',

        out_of_stock: 'Out of Stock'

    },

    category: {

        burger: 'Burgers',

        pizza: 'Pizza',

        chicken: 'Chicken',

        sides: 'Sides & Snacks',

        dessert: 'Desserts',

        drinks: 'Drinks'

    }

};


// ═══════════════════════════════════════════════════════════
// SECURITY — ESCAPE HTML
// ═══════════════════════════════════════════════════════════

function escapeHtml(value) {

    return String(
        value == null ? '' : value
    )

    .replace(/&/g, '&amp;')

    .replace(/</g, '&lt;')

    .replace(/>/g, '&gt;')

    .replace(/"/g, '&quot;')

    .replace(/'/g, '&#39;');

}


// Format price
function priceLabel(amount) {

    return Number(amount)
        .toLocaleString('en-KE');

}


// ═══════════════════════════════════════════════════════════
// ADD TO CART BUTTON
// ═══════════════════════════════════════════════════════════

function addCartButton(product) {

    if (
        product.availability ===
        'out_of_stock'
    ) {

        return '<button class="add-cart" disabled>' +
               'Sold Out' +
               '</button>';

    }


    return (

        '<button class="add-cart" ' +

        'data-id="' +
        product.id +
        '" ' +

        'data-name="' +
        escapeHtml(product.name) +
        '" ' +

        'data-price="' +
        product.price +
        '">' +

        'Add to Cart 🛒' +

        '</button>'

    );

}


// ═══════════════════════════════════════════════════════════
// RENDER PRODUCTS FROM DATABASE
// ═══════════════════════════════════════════════════════════

function renderCatalogue(products) {

    var productBody =
        document.getElementById(
            'catalogue-tbody'
        );


    var productRows = '';


    products.forEach(function(product) {

        productRows +=

            '<tr>' +

                '<td>' +
                escapeHtml(product.name) +
                '</td>' +

                '<td>' +
                escapeHtml(
                    DISPLAY.category[
                        product.category
                    ] ||
                    product.category ||
                    '—'
                ) +
                '</td>' +

                '<td>' +
                formatKES(product.price) +
                '</td>' +

                '<td>' +
                (
                    DISPLAY.availability[
                        product.availability
                    ] ||
                    '—'
                ) +
                '</td>' +

                '<td>' +
                addCartButton(product) +
                '</td>' +

            '</tr>';

    });


    if (
        productBody &&
        productRows
    ) {

        productBody.innerHTML =
            productRows;

    }


    renderProductCards(products);

    renderBestSellers(products);

}


// ═══════════════════════════════════════════════════════════
// PRODUCT CARDS
// ═══════════════════════════════════════════════════════════

function renderProductCards(products) {

    var grid =
        document.getElementById(
            'product-cards'
        );


    if (!grid) return;


    var cards = '';


    products.forEach(function(product) {

        if (!product.image_url) {
            return;
        }


        cards +=

            '<div class="product-card">' +

                '<img src="' +
                escapeHtml(product.image_url) +
                '" alt="' +
                escapeHtml(product.name) +
                '">' +

                '<h3>' +
                escapeHtml(product.name) +
                '</h3>' +

                '<p class="product-price">' +
                'KES ' +
                priceLabel(product.price) +
                '</p>' +

                addCartButton(product) +

            '</div>';

    });


    if (cards) {
        grid.innerHTML = cards;
    }

}


// ═══════════════════════════════════════════════════════════
// BEST SELLERS
// ═══════════════════════════════════════════════════════════

function renderBestSellers(products) {

    var list =
        document.getElementById(
            'best-sellers'
        );


    if (!list) return;


    var items = products

        .filter(function(product) {

            return product.is_best_seller;

        })

        .map(function(product) {

            return '<li>' +
                escapeHtml(product.name) +
                '</li>';

        })

        .join('');


    if (items) {
        list.innerHTML = items;
    }

}


// ═══════════════════════════════════════════════════════════
// LOAD PRODUCTS FROM DATABASE
// ═══════════════════════════════════════════════════════════

function loadCatalogueFromDB() {

    fetch('backend/products.php')

        .then(function(response) {

            return response.json();

        })

        .then(function(data) {

            if (
                data &&
                data.ok &&
                data.products &&
                data.products.length
            ) {

                renderCatalogue(
                    data.products
                );

            }

        })

        .catch(function(error) {

            console.warn(
                'Could not load QuickBite products from database.',
                error
            );

        });

}


// ═══════════════════════════════════════════════════════════
// CART + CHECKOUT INITIALIZATION
// ═══════════════════════════════════════════════════════════

document.addEventListener(
    'DOMContentLoaded',
    function() {

        updateCartCount();


        // Add-to-cart buttons
        document.addEventListener(
            'click',
            function(event) {

                var button =
                    event.target.closest(
                        '.add-cart'
                    );


                if (
                    !button ||
                    button.disabled
                ) {
                    return;
                }


                var id =
                    parseInt(
                        button.getAttribute(
                            'data-id'
                        ),
                        10
                    );


                var name =
                    button.getAttribute(
                        'data-name'
                    );


                var price =
                    parseFloat(
                        button.getAttribute(
                            'data-price'
                        )
                    );


                addToCart(
                    id,
                    name,
                    price
                );


                // Confirmation
                var original =
                    button.textContent;


                button.textContent =
                    'Added ✓';


                button.disabled = true;


                setTimeout(
                    function() {

                        button.textContent =
                            original;

                        button.disabled =
                            false;

                    },
                    900
                );

            }
        );


        // Load products
        loadCatalogueFromDB();


        // ═══════════════════════════════════════════════════
        // CHECKOUT FORM
        // ═══════════════════════════════════════════════════

        var checkoutForm =
            document.getElementById(
                'checkout-form'
            );


        if (!checkoutForm) return;


        checkoutForm.addEventListener(
            'submit',
            function(event) {

                event.preventDefault();


                var name =
                    document.getElementById(
                        'checkout-name'
                    ).value.trim();


                var email =
                    document.getElementById(
                        'checkout-email'
                    ).value.trim();


                var phone =
                    document.getElementById(
                        'checkout-phone'
                    ).value.trim();


                var address =
                    document.getElementById(
                        'checkout-address'
                    ).value.trim();


                var time =
                    document.getElementById(
                        'checkout-time'
                    ).value;


                var promo =
                    document.getElementById(
                        'cart-promo-code'
                    ).value.trim();


                var valid = true;


                if (!isValidName(name)) {

                    showError(
                        'checkout-name',
                        'Please enter a valid name.'
                    );

                    valid = false;

                } else {

                    clearError('checkout-name');

                }


                if (!isValidEmail(email)) {

                    showError(
                        'checkout-email',
                        'Please enter a valid email.'
                    );

                    valid = false;

                } else {

                    clearError('checkout-email');

                }


                if (!isValidPhone(phone)) {

                    showError(
                        'checkout-phone',
                        'Please enter a valid phone number.'
                    );

                    valid = false;

                } else {

                    clearError('checkout-phone');

                }


                if (address.length < 6) {

                    showError(
                        'checkout-address',
                        'Please enter your delivery address.'
                    );

                    valid = false;

                } else {

                    clearError('checkout-address');

                }


                if (!valid) return;


                var cart = loadCart();


                if (cart.length === 0) {
                    return;
                }


                var orderError =
                    document.getElementById(
                        'checkout-order-error'
                    );


                if (orderError) {
                    orderError.style.display =
                        'none';
                }


                var submitButton =
                    checkoutForm.querySelector(
                        'button[type="submit"]'
                    );


                var originalText =
                    submitButton.textContent;


                submitButton.disabled = true;

                submitButton.textContent =
                    'Placing Order...';


                // Send order to PHP backend
                fetch('backend/order.php', {

                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/json'
                    },

                    body: JSON.stringify({

                        customer: {

                            name: name,

                            email: email,

                            phone: phone,

                            address: address,

                            delivery_time: time

                        },

                        items: cart.map(
                            function(item) {

                                return {

                                    id: item.id,

                                    quantity:
                                        item.qty

                                };

                            }
                        ),

                        promo_code: promo

                    })

                })


                .then(function(response) {

                    return response.json()
                        .then(function(data) {

                            return {

                                status:
                                    response.status,

                                data: data

                            };

                        });

                })


                .then(function(result) {

                    var data =
                        result.data;


                    if (
                        result.status === 400 &&
                        data.errors
                    ) {

                        Object.keys(
                            data.errors
                        ).forEach(
                            function(field) {

                                if (
                                    field ===
                                    'promo_code' ||
                                    field ===
                                    'items'
                                ) {

                                    orderError.textContent =
                                        data.errors[field];

                                    orderError.style.display =
                                        'block';

                                } else {

                                    showError(
                                        'checkout-' +
                                        field,
                                        data.errors[field]
                                    );

                                }

                            }
                        );

                        return;

                    }


                    if (!data.ok) {

                        orderError.textContent =
                            data.message ||
                            'Could not place your order. Please try again.';

                        orderError.style.display =
                            'block';

                        return;

                    }


                    // Successful order
                    var successMessage =
                        document.getElementById(
                            'checkout-success'
                        );


                    if (successMessage) {

                        successMessage.innerHTML =

                            '🍔 Thank you, <strong>' +
                            escapeHtml(name) +
                            '</strong>! ' +

                            'Your QuickBite order ' +

                            '<strong>#' +
                            data.order_id +
                            '</strong> has been placed.' +

                            '<br>Total: <strong>' +

                            formatKES(
                                data.total
                            ) +

                            '</strong>' +

                            (data.discount > 0

                                ? ' (' +
                                  formatKES(
                                      data.discount
                                  ) +
                                  ' discount)'

                                : '') +

                            '<br>We will deliver your food soon!';


                        successMessage.classList.add(
                            'show'
                        );

                    }


                    // Clear cart
                    saveCart([]);

                    checkoutForm.reset();

                    document.getElementById(
                        'cart-promo-code'
                    ).value = '';


                    setTimeout(
                        function() {

                            closeModal(
                                'checkout-modal'
                            );


                            if (successMessage) {

                                successMessage.classList
                                    .remove('show');

                            }

                        },
                        5000
                    );

                })


                .catch(function(error) {

                    console.error(
                        'Order request failed:',
                        error
                    );


                    if (orderError) {

                        orderError.textContent =
                            'Could not connect to the server.';

                        orderError.style.display =
                            'block';

                    }

                })


                .finally(function() {

                    submitButton.disabled =
                        false;

                    submitButton.textContent =
                        originalText;

                });

            }
        );

    }
);


// ═══════════════════════════════════════════════════════════
// CONTACT FORM
// ═══════════════════════════════════════════════════════════

document.addEventListener(
    'DOMContentLoaded',
    function() {

        var contactForm =
            document.getElementById(
                'contact-form'
            );


        if (!contactForm) return;


        contactForm.addEventListener(
            'submit',
            function(event) {

                event.preventDefault();


                var name =
                    document.getElementById(
                        'contact-name'
                    ).value.trim();


                var email =
                    document.getElementById(
                        'contact-email'
                    ).value.trim();


                var phone =
                    document.getElementById(
                        'contact-phone'
                    ).value.trim();


                var department =
                    document.getElementById(
                        'contact-department'
                    ).value;


                var message =
                    document.getElementById(
                        'contact-message'
                    ).value.trim();


                var valid = true;


                // Name
                if (!isValidName(name)) {

                    showError(
                        'contact-name',
                        'Please enter a valid name.'
                    );

                    valid = false;

                } else {

                    clearError(
                        'contact-name'
                    );

                }


                // Email
                if (!isValidEmail(email)) {

                    showError(
                        'contact-email',
                        'Please enter a valid email address.'
                    );

                    valid = false;

                } else {

                    clearError(
                        'contact-email'
                    );

                }


                // Phone
                if (
                    phone !== '' &&
                    !isValidPhone(phone)
                ) {

                    showError(
                        'contact-phone',
                        'Please enter a valid phone number.'
                    );

                    valid = false;

                } else {

                    clearError(
                        'contact-phone'
                    );

                }


                // Message
                if (message.length < 10) {

                    showError(
                        'contact-message',
                        'Please enter a message of at least 10 characters.'
                    );

                    valid = false;

                } else {

                    clearError(
                        'contact-message'
                    );

                }


                if (!valid) return;


                var submitButton =
                    contactForm.querySelector(
                        'button[type="submit"]'
                    );


                var originalText =
                    submitButton.textContent;


                submitButton.disabled =
                    true;

                submitButton.textContent =
                    'Sending...';


                // Send contact message
                fetch('backend/contact.php', {

                    method: 'POST',

                    headers: {

                        'Content-Type':
                            'application/json'

                    },

                    body: JSON.stringify({

                        name: name,

                        email: email,

                        phone: phone,

                        department:
                            department,

                        message: message

                    })

                })


                .then(function(response) {

                    return response.json()
                        .then(function(data) {

                            return {

                                status:
                                    response.status,

                                data: data

                            };

                        });

                })


                .then(function(result) {

                    var data =
                        result.data;


                    // Server validation errors
                    if (
                        result.status === 400 &&
                        data.errors
                    ) {

                        Object.keys(
                            data.errors
                        ).forEach(
                            function(field) {

                                showError(
                                    'contact-' +
                                    field,
                                    data.errors[field]
                                );

                            }
                        );

                        return;

                    }


                    // General error
                    if (!data.ok) {

                        showError(
                            'contact-email',
                            data.message ||
                            'Could not send your message.'
                        );

                        return;

                    }


                    // Success
                    var successMessage =
                        document.getElementById(
                            'contact-success'
                        );


                    if (successMessage) {

                        successMessage.innerHTML =

                            '🍔 Thank you, <strong>' +
                            escapeHtml(name) +
                            '</strong>! ' +

                            'Your message has been received. ' +

                            'The QuickBite team will get back to you soon.';


                        successMessage.classList.add(
                            'show'
                        );

                    }


                    contactForm.reset();


                    setTimeout(
                        function() {

                            if (successMessage) {

                                successMessage.classList
                                    .remove('show');

                            }

                        },
                        5000
                    );

                })


                .catch(function(error) {

                    console.error(
                        'Contact request failed:',
                        error
                    );


                    showError(
                        'contact-email',
                        'Could not connect to the server.'
                    );

                })


                .finally(function() {

                    submitButton.disabled =
                        false;

                    submitButton.textContent =
                        originalText;

                });

            }
        );

    }
);
