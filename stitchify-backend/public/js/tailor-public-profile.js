document.querySelectorAll('.portfolio-item').forEach((item) => {
  item.addEventListener('click', function () {
    const url = this.dataset.imageUrl;
    if (url) {
      window.open(url, '_blank');
    }
  });
});