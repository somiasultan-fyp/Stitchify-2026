document.addEventListener('DOMContentLoaded', function() {
    const stripeKey = document.querySelector('meta[name="stripe-key"]').content;
    const orderId = document.querySelector('meta[name="order-id"]').content;
    const orderPrice = document.querySelector('meta[name="order-price"]').content;
    const processUrl = document.querySelector('meta[name="process-url"]').content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const stripe = Stripe(stripeKey);
    const elements = stripe.elements();

    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#212529',
                fontFamily: "'Segoe UI', sans-serif",
                '::placeholder': { color: '#aab7c4' },
            },
            invalid: { color: '#dc3545' },
        }
    });

    cardElement.mount('#cardElement');

    const cardElementWrap = document.getElementById('cardElementWrap');
    const errorDiv = document.getElementById('cardError');
    const payBtn = document.getElementById('payBtn');

    cardElement.on('focus', () => {
        cardElementWrap.classList.add('focused');
    });

    cardElement.on('blur', () => {
        cardElementWrap.classList.remove('focused');
    });

    cardElement.on('change', (event) => {
        errorDiv.textContent = event.error ? event.error.message : '';
    });

    window.processPayment = async function() {
        payBtn.disabled = true;
        payBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Processing...
        `;
        errorDiv.textContent = '';

        try {
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                errorDiv.textContent = error.message;
                payBtn.disabled = false;
                payBtn.innerHTML = `<i class="fas fa-lock"></i> Pay Rs. ${orderPrice}`;
                return;
            }

            const response = await fetch(processUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethod.id,
                }),
            });

            const data = await response.json();

            if (data.success) {
                payBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Redirecting...
                `;
                window.location.href = data.redirect;
            } else {
                errorDiv.textContent = data.message || 'Payment failed. Please try again.';
                payBtn.disabled = false;
                payBtn.innerHTML = `<i class="fas fa-lock"></i> Pay Rs. ${orderPrice}`;
            }

        } catch (err) {
            errorDiv.textContent = 'Something went wrong. Please try again.';
            payBtn.disabled = false;
            payBtn.innerHTML = `<i class="fas fa-lock"></i> Pay Rs. ${orderPrice}`;
        }
    };
});