function filterTailors() {
    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    const availableOnly = document.getElementById('availableOnly').checked;
    const items = document.querySelectorAll('.tailor-item');

    let visibleCount = 0;

    items.forEach(item => {
        const name = item.dataset.name;
        const city = item.dataset.city;
        const spec = item.dataset.spec;
        const slots = parseInt(item.dataset.slots);

        const matchSearch = !query ||
            name.includes(query) ||
            city.includes(query) ||
            spec.includes(query);

        const matchAvailable = !availableOnly || slots > 0;

        if (matchSearch && matchAvailable) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    document.getElementById('resultsCount').textContent =
        visibleCount + ' tailor(s) found';

    document.getElementById('noResults').style.display =
        visibleCount === 0 ? 'block' : 'none';
}