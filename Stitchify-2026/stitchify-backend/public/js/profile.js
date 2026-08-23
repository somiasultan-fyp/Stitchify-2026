const profileImageInput = document.getElementById('profileImageInput');
const avatarPreview = document.getElementById('avatarPreview');

profileImageInput.addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function (e) {
    avatarPreview.src = e.target.result;
  };
  reader.readAsDataURL(file);
});