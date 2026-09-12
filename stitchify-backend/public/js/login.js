const urlParams = new URLSearchParams(window.location.search);
const redirectTo = urlParams.get('redirect') || null;

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');

    if (!loginForm || !emailInput || !passwordInput) {
        return;
    }

    function validateEmail(email) {
        emailError.textContent = '';
        emailInput.classList.remove('is-invalid');

        if (!email) {
            emailError.textContent = 'Email address is required.';
            emailInput.classList.add('is-invalid');
            return false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            emailError.textContent = 'Please enter a valid email address.';
            emailInput.classList.add('is-invalid');
            return false;
        }

        return true;
    }

    emailInput.addEventListener('blur', () => {
        if (emailInput.value.trim()) {
            validateEmail(emailInput.value.trim());
        }
    });

    emailInput.addEventListener('input', () => {
        if (emailError.textContent) {
            validateEmail(emailInput.value.trim());
        }
    });

    if (togglePassword) {
        togglePassword.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            togglePassword.classList.toggle('fa-eye', !isPassword);
            togglePassword.classList.toggle('fa-eye-slash', isPassword);
        });
    }

    passwordInput.addEventListener('input', () => {
        const value = passwordInput.value;

        if (togglePassword) {
            togglePassword.classList.toggle('show', value.length > 0);

            if (value.length === 0) {
                passwordInput.type = 'password';
                togglePassword.classList.remove('fa-eye-slash');
                togglePassword.classList.add('fa-eye');
            }
        }

        passwordError.textContent = '';
        passwordInput.classList.remove('is-invalid');
    });

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = emailInput.value.trim();
        const password = passwordInput.value;
        const errorDiv = document.getElementById('loginError');
        const submitBtn = loginForm.querySelector('button[type="submit"]');

        errorDiv.textContent = '';
        errorDiv.style.display = 'none';

        const isEmailValid = validateEmail(email);

        let isPasswordValid = true;

        if (!password) {
            passwordError.textContent = 'Password is required.';
            passwordInput.classList.add('is-invalid');
            isPasswordValid = false;
        }

        if (!isEmailValid || !isPasswordValid) {
            const firstInvalid = document.querySelector('.is-invalid');

            if (firstInvalid) {
                firstInvalid.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span> Logging in...';

        try {
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content');

            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    email,
                    password,
                    redirect: redirectTo
                })
            });

            const result = await response.json();

            if (result.success) {
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span> Redirecting...';

                window.location.href = result.redirect;
            } else {
                errorDiv.textContent =
                    result.message || 'Invalid credentials.';

                errorDiv.style.display = 'block';

                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Login';
            }
        } catch (err) {
            errorDiv.textContent =
                'Something went wrong. Please try again.';

            errorDiv.style.display = 'block';

            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Login';
        }
    });

    const forgotSubmitBtn = document.getElementById('forgotSubmitBtn');
    const forgotEmailInput = document.getElementById('forgotEmail');
    const forgotSuccessBox = document.getElementById('forgotSuccessBox');
    const forgotErrorBox = document.getElementById('forgotErrorBox');

    if (forgotSubmitBtn && forgotEmailInput) {
        forgotSubmitBtn.addEventListener('click', async () => {
            const email = forgotEmailInput.value.trim();

            forgotSuccessBox.style.display = 'none';
            forgotErrorBox.style.display = 'none';
            forgotEmailInput.classList.remove('is-invalid');

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!email || !emailRegex.test(email)) {
                forgotErrorBox.textContent =
                    'Please enter a valid email address.';

                forgotErrorBox.style.display = 'block';
                forgotEmailInput.classList.add('is-invalid');

                return;
            }

            forgotSubmitBtn.disabled = true;
            forgotSubmitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span> Sending...';

            try {
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content');

                const response = await fetch('/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        email
                    })
                });

                const result = await response.json();

                if (result.success) {
                    forgotSuccessBox.textContent = result.message;
                    forgotSuccessBox.style.display = 'block';
                    forgotEmailInput.value = '';
                } else {
                    forgotErrorBox.textContent =
                        result.message || 'Unable to send reset link.';

                    forgotErrorBox.style.display = 'block';
                }
            } catch (err) {
                forgotErrorBox.textContent =
                    'Something went wrong. Please try again.';

                forgotErrorBox.style.display = 'block';
            } finally {
                forgotSubmitBtn.disabled = false;
                forgotSubmitBtn.innerHTML =
                    '<i class="fas fa-paper-plane"></i> Send Reset Link';
            }
        });
    }
});