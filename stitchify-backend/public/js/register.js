const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_+{}:"<>?~]).{8,}$/;

function clearStep1Errors() {
  document.getElementById('nameError').textContent = '';
  document.getElementById('emailError').textContent = '';
  document.getElementById('phoneError').textContent = '';
  document.getElementById('pwError').textContent = '';
  document.getElementById('s1_name').classList.remove('is-invalid');
  document.getElementById('s1_email').classList.remove('is-invalid');
  document.getElementById('s1_phone').classList.remove('is-invalid');
  document.getElementById('s1_password').classList.remove('is-invalid');
}

function goToStep2() {
  clearStep1Errors();

  const nameField     = document.getElementById('s1_name');
  const emailField    = document.getElementById('s1_email');
  const phoneField    = document.getElementById('s1_phone');
  const passwordField = document.getElementById('s1_password');

  const name     = nameField.value.trim();
  const email    = emailField.value.trim();
  const phone    = phoneField.value.trim();
  const password = passwordField.value;

  let isValid = true;

  if (!name) {
    document.getElementById('nameError').textContent = 'Full name is required.';
    nameField.classList.add('is-invalid');
    isValid = false;
  }

  if (!email || !email.includes('@') || !email.includes('.')) {
    document.getElementById('emailError').textContent = 'A valid email address is required.';
    emailField.classList.add('is-invalid');
    isValid = false;
  }

  if (!phone) {
    document.getElementById('phoneError').textContent = 'Phone number is required.';
    phoneField.classList.add('is-invalid');
    isValid = false;
  }

  if (!passwordRegex.test(password)) {
    document.getElementById('pwError').textContent = 'Password must be at least 8 characters with letters, numbers & special characters (!@#$%^&*)';
    passwordField.classList.add('is-invalid');
    isValid = false;
  }

  if (!isValid) {
    const firstInvalid = document.querySelector('#step1Div .is-invalid');
    if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  document.getElementById('f_name').value     = name;
  document.getElementById('f_email').value    = email;
  document.getElementById('f_phone').value    = phone;
  document.getElementById('f_password').value = password;

  document.getElementById('step1Div').style.display = 'none';
  document.getElementById('step2Div').style.display = 'block';
}

function goToStep1() {
  document.getElementById('step2Div').style.display = 'none';
  document.getElementById('step1Div').style.display = 'block';
}

function toggleTailorFields() {
  const role = document.getElementById('role').value;
  document.getElementById('tailorFields').style.display = role === 'tailor' ? 'block' : 'none';
}

document.getElementById('registerForm').addEventListener('submit', function(e) {
  document.getElementById('roleError').textContent = '';
  document.getElementById('addressError').textContent = '';
  document.getElementById('categoryError').textContent = '';
  document.getElementById('slotError').textContent = '';
  document.getElementById('role').classList.remove('is-invalid');

  const role = document.getElementById('role').value;
  let isValid = true;

  if (!role) {
    document.getElementById('roleError').textContent = 'Please select your role.';
    document.getElementById('role').classList.add('is-invalid');
    isValid = false;
  }

  if (role === 'tailor') {
    const address  = document.getElementById('s2_address');
    const category = document.getElementById('s2_category');
    const slot     = document.getElementById('s2_slot');

    if (!address.value.trim()) {
      document.getElementById('addressError').textContent = 'Shop address is required.';
      address.classList.add('is-invalid');
      isValid = false;
    }
    if (!category.value) {
      document.getElementById('categoryError').textContent = 'Please select a specialization.';
      category.classList.add('is-invalid');
      isValid = false;
    }
    if (!slot.value || slot.value < 1) {
      document.getElementById('slotError').textContent = 'Slot capacity is required.';
      slot.classList.add('is-invalid');
      isValid = false;
    }
  }

  if (!isValid) {
    e.preventDefault();
    const firstInvalid = document.querySelector('#step2Div .is-invalid');
    if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
});

const toggle = document.getElementById('togglePassword');
const pwd    = document.getElementById('s1_password');
toggle.addEventListener('click', () => {
  if (pwd.type === 'password') {
    pwd.type = 'text';
    toggle.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    pwd.type = 'password';
    toggle.classList.replace('fa-eye-slash', 'fa-eye');
  }
});