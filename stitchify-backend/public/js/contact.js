const contactForm = document.getElementById('contactForm');
const contactSuccessBox = document.getElementById('contactSuccessBox');

contactForm.addEventListener('submit', (e) => {
  e.preventDefault();

  if (!contactForm.checkValidity()) {
    contactForm.classList.add('was-validated');
    return;
  }

  const formData = {
    name: document.getElementById('fullName').value.trim(),
    email: document.getElementById('email').value.trim(),
    phone: document.getElementById('phone').value.trim(),
    subject: document.getElementById('subject').value,
    message: document.getElementById('message').value.trim(),
  };

  contactSuccessBox.textContent = 'Thank you for contacting us. We will get back to you soon.';
  contactSuccessBox.style.display = 'block';
  contactSuccessBox.scrollIntoView({ behavior: 'smooth', block: 'center' });

  contactForm.reset();
  contactForm.classList.remove('was-validated');
});