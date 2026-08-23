document.addEventListener('DOMContentLoaded', function () {

    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector(
        'input[name="password_confirmation"]'
    );

    if (!passwordInput || !confirmPasswordInput) {
        return;
    }

    confirmPasswordInput.addEventListener('input', function () {

        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity(
                'Passwords do not match.'
            );
        } else {
            confirmPasswordInput.setCustomValidity('');
        }

    });

});