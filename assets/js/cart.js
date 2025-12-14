// Cart page JS: update quantity, remove item, clear cart
(function(){
    'use strict';

    function postAction(action, productId, quantity) {
        const formData = new FormData();
        formData.append('action', action);
        if (productId !== undefined) formData.append('product_id', productId);
        if (quantity !== undefined) formData.append('quantity', quantity);

        return fetch('cart_actions.php', {
            method: 'POST',
            body: formData
        }).then(r => r.json());
    }

    window.updateQuantity = function(productId, quantity) {
        quantity = parseInt(quantity, 10);
        if (isNaN(quantity) || quantity < 1) {
            // treat as remove
            if (!confirm('Số lượng là 0. Bạn có muốn xóa sản phẩm này khỏi giỏ hàng?')) return;
            postAction('remove', productId).then(data => {
                if (data.success) {
                    if (typeof updateCartBadge === 'function') updateCartBadge(data.cart_count);
                    showNotification(data.message);
                    location.reload();
                } else {
                    showNotification(data.message, 'error');
                }
            }).catch(e => {
                console.error(e);
                showNotification('Có lỗi xảy ra', 'error');
            });
            return;
        }

        postAction('update', productId, quantity).then(data => {
            if (data.success) {
                if (typeof updateCartBadge === 'function') updateCartBadge(data.cart_count);
                showNotification(data.message);
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        }).catch(e => {
            console.error(e);
            showNotification('Có lỗi xảy ra', 'error');
        });
    };

    window.removeFromCart = function(productId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) return;
        postAction('remove', productId).then(data => {
            if (data.success) {
                if (typeof updateCartBadge === 'function') updateCartBadge(data.cart_count);
                showNotification(data.message);
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        }).catch(e => {
            console.error(e);
            showNotification('Có lỗi xảy ra', 'error');
        });
    };

    window.clearCart = function() {
        if (!confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) return;
        postAction('clear').then(data => {
            if (data.success) {
                if (typeof updateCartBadge === 'function') updateCartBadge(data.cart_count);
                showNotification(data.message);
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        }).catch(e => {
            console.error(e);
            showNotification('Có lỗi xảy ra', 'error');
        });
    };

})();
