const profileImageInput = document.getElementById('profileImageInput');
const avatarPreview = document.getElementById('avatarPreview');
const defaultAvatarUrl = document.getElementById('avatarPreview').dataset.default;
const removePhotoBtn = document.getElementById('removePhotoBtn');
const removePhotoFlag = document.getElementById('removePhotoFlag');

profileImageInput.addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function (e) {
    avatarPreview.src = e.target.result;
  };
  reader.readAsDataURL(file);

  if (removePhotoFlag) {
    removePhotoFlag.value = '0';
  }
  if (removePhotoBtn) {
    removePhotoBtn.style.display = '';
  }
});

if (removePhotoBtn) {
  removePhotoBtn.addEventListener('click', function () {
    avatarPreview.src = defaultAvatarUrl;
    removePhotoFlag.value = '1';
    profileImageInput.value = '';
    removePhotoBtn.style.display = 'none';
  });
}