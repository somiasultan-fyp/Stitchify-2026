document.addEventListener('DOMContentLoaded', function() {
    const resendForm = document.getElementById('resendForm');
    const resendBtn = document.getElementById('resendBtn');

    if (resendForm && resendBtn) {
        resendForm.addEventListener('submit', function() {
            resendBtn.disabled = true;
            resendBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Sending...
            `;

            setTimeout(() => {
                resendBtn.disabled = false;
                resendBtn.innerHTML = `
                    <i class="fas fa-paper-plane me-2"></i> Resend Verification Email
                `;
            }, 60000);
        });
    }

    const logoutLink = document.getElementById('logoutLink');

    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();

            fetch('/logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
            .then(() => {
                window.location.href = '/login';
            })
            .catch(() => {
                window.location.href = '/login';
            });
        });
    }
});