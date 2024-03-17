class ManageCart {
    _key = 'mcart';
    _subtotal = 0;

    add(productId, qty) {
        productId = parseInt(productId);
        qty = parseInt(qty);

        let cartItems = this._getItems();

        if (cartItems == null) {
            cartItems = { [productId]: qty }
        } else {
            if (cartItems[productId]) {
                cartItems[productId] += qty;
            } else {
                cartItems = {
                    ...cartItems,
                    [productId]: qty
                }
            }
        }

        localStorage.setItem(this._key, JSON.stringify(cartItems));
    }

    getQty(productId) {
        let cartItems = this._getItems();
        if (cartItems == null) return 0;

        if (!cartItems[productId]) return 0;

        return cartItems[productId];
    }

    manageQty(e, productId, qty, stock) {
        let currentQty = this.getQty(productId) ?? 0;
        let newQty = currentQty + qty;

        if (newQty > stock) {
            cuteToast({
                type: 'info',
                message: "Sorry! You can't add more quantity."
            })
            return;
        }

        if (newQty == 0) return;

        this.add(productId, qty);
        e.parentElement.querySelector('span').textContent = newQty;

        let pTag = e.parentElement.parentElement.previousElementSibling;
        pTag.querySelector('.qty').textContent = newQty;
        pTag.querySelector('.itemTotalPrice').textContent = pTag.querySelector('.itemPrice').textContent * newQty;
        this.updatePrice();
    }

    isInCart(productId) {
        let cartItems = this._getItems();

        if (cartItems != null) {
            if (cartItems[productId]) {
                return true;
            }
        }

        return false;
    }

    remove(productId) {
        let cartItems = this._getItems();
        if (cartItems != null) {
            delete cartItems[productId];
            localStorage.setItem(this._key, JSON.stringify(cartItems));
        }
        updatePrice();
    }

    empty() {
        localStorage.removeItem(this._key);
    }

    _getItems() {
        return JSON.parse(localStorage.getItem(this._key));
    }

    updatePrice() {
        let subtotalElement = document.getElementById('subtotal');
        let totalElement = document.getElementById('total');

        let items = document.getElementById('itemContainer').querySelectorAll('.itemTotalPrice');
        this._subtotal = 0;
        items.forEach(item => {
            this._subtotal += parseFloat(item.textContent);
        });

        subtotalElement.textContent = this._subtotal;
        totalElement.textContent = this._subtotal;
        
        document.getElementById('discount_code').value = '';
        document.getElementById('discount_amount').textContent = 0;
        document.getElementById('discount_msg').textContent = 0;
    }

    getSubTotal() {
        return this._subtotal;
    }
}

const mCart = new ManageCart();