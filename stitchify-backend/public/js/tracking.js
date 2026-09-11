document.addEventListener('DOMContentLoaded', function() {
    const statusUrl = document.querySelector('meta[name="status-url"]').content;
    const currentStatus = document.querySelector('meta[name="current-status"]').content;
    const progressBar = document.getElementById('progressBar');
    const lastUpdated = document.getElementById('lastUpdated');
    const refreshIcon = document.getElementById('refreshIcon');

    window.refreshStatus = async function() {
        refreshIcon.classList.add('fa-spin');

        try {
            const res = await fetch(statusUrl);
            const data = await res.json();

            if (data.found) {
                progressBar.style.width = data.progress + '%';
                lastUpdated.textContent = 'Last updated: ' + new Date().toLocaleTimeString();

                if (data.status !== currentStatus) {
                    location.reload();
                }
            }
        } catch (err) {
            console.error('Status refresh failed:', err);
        } finally {
            refreshIcon.classList.remove('fa-spin');
        }
    };

    setInterval(refreshStatus, 60000);
});