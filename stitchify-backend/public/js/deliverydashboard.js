function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

function toggleNotif() {
    document.getElementById('notifDropdown').classList.toggle('show');
}

function markAllRead(event) {
    event.preventDefault();
    document.getElementById('bellBadge').style.display = 'none';
}

document.addEventListener('click', function (event) {
    const bellWrapper = document.querySelector('.bell-wrapper');
    if (bellWrapper && !bellWrapper.contains(event.target)) {
        document.getElementById('notifDropdown').classList.remove('show');
    }
});

document.querySelectorAll('.sidebar-menu a[data-section]').forEach(function (link) {
    link.addEventListener('click', function () {
        document.querySelectorAll('.sidebar-menu a').forEach(function (item) {
            item.classList.remove('active');
        });
        link.classList.add('active');
    });
});