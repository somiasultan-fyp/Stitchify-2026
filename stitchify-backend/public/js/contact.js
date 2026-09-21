const contactForm = document.getElementById('contactForm');
const contactSuccessBox = document.getElementById('contactSuccessBox');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

contactForm.addEventListener('submit', async (e) => {
  e.preventDefault();

  if (!contactForm.checkValidity()) {
    contactForm.classList.add('was-validated');
    return;
  }

  const submitBtn = contactForm.querySelector('button[type="submit"]');
  const originalBtnHtml = submitBtn.innerHTML;
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Sending...';

  const formData = {
    name: document.getElementById('fullName').value.trim(),
    email: document.getElementById('email').value.trim(),
    phone: document.getElementById('phone').value.trim(),
    subject: document.getElementById('subject').value,
    message: document.getElementById('message').value.trim(),
  };

  try {
    const response = await fetch('/contact/submit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify(formData),
    });

    const data = await response.json();

    if (response.ok && data.success) {
      contactSuccessBox.textContent = data.message;
      contactSuccessBox.style.display = 'block';
      contactSuccessBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
      contactForm.reset();
      contactForm.classList.remove('was-validated');
    } else {
      contactSuccessBox.textContent = data.message || 'Something went wrong. Please try again.';
      contactSuccessBox.style.display = 'block';
    }
  } catch (err) {
    contactSuccessBox.textContent = 'Connection failed. Please try again.';
    contactSuccessBox.style.display = 'block';
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalBtnHtml;
  }
});