const profileImageInput = document.getElementById('profileImageInput');
const avatarPreview = document.getElementById('avatarPreview');
const defaultAvatarUrl = document.getElementById('avatarPreview').dataset.default;

profileImageInput.addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function (e) {
    avatarPreview.src = e.target.result;
  };
  const removePhotoBtn = document.getElementById('removePhotoBtn');
  const removePhotoFlag = document.getElementById('removePhotoFlag');
  if (removePhotoBtn) {
  removePhotoBtn.addEventListener('click', function () {
    avatarPreview.src = defaultAvatarUrl;
    removePhotoFlag.value = '1';
    profileImageInput.value = '';
    removePhotoBtn.style.display = 'none';
  });
  }
  reader.readAsDataURL(file);
});