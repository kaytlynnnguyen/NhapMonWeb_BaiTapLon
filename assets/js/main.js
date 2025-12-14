// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterRadios = document.querySelectorAll('.sidebar input[type="radio"]');
    const productCards = document.querySelectorAll('.menu-products .product-card');
    
    filterRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            filterProducts();
        });
    });
    
    function filterProducts() {
        const loaiBanh = document.querySelector('input[name="loai-banh"]:checked').value;
        const dongSanPham = document.querySelector('input[name="dong-san-pham"]:checked').value;
        const mucGia = document.querySelector('input[name="muc-gia"]:checked').value;
        
        productCards.forEach(card => {
            let show = true;
            
            // Filter by type (loai-banh)
            if (loaiBanh !== 'all') {
                const cardType = card.getAttribute('data-type');
                if (cardType !== loaiBanh) {
                    show = false;
                }
            }
            
            // Filter by product line (dong-san-pham)
            if (show && dongSanPham !== 'all') {
                const cardLine = card.getAttribute('data-line');
                if (cardLine !== dongSanPham) {
                    show = false;
                }
            }
            
            // Filter by price (muc-gia)
            if (show && mucGia !== 'all') {
                const cardPrice = parseInt(card.getAttribute('data-price'));
                switch(mucGia) {
                    case 'under-50':
                        if (cardPrice >= 50000) show = false;
                        break;
                    case '50-100':
                        if (cardPrice < 50000 || cardPrice > 100000) show = false;
                        break;
                    case '100-200':
                        if (cardPrice < 100000 || cardPrice > 200000) show = false;
                        break;
                    case 'over-200':
                        if (cardPrice <= 200000) show = false;
                        break;
                }
            }
            
            // Show or hide card
            if (show) {
                card.style.display = 'block';
                card.style.animation = 'fadeIn 0.5s ease-out';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Add to cart functionality
function addToCart(productId, quantity = 1) {
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    
    fetch('cart_actions.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart badge
            updateCartBadge(data.cart_count);
            // Show notification
            showNotification(data.message);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra. Vui lòng thử lại!', 'error');
    });
}

/**
 * Update cart badge in header
 */
function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = count;
        if (count === 0) {
            badge.style.display = 'none';
        } else {
            badge.style.display = 'flex';
        }
    }
}

function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existing = document.querySelectorAll('.notification');
    existing.forEach(n => n.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background-color: ${type === 'error' ? '#ff4444' : '#FF69B4'};
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
        max-width: 300px;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

// Add CSS animations for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

