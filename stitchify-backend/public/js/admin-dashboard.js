const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function filterUsers() {
    const q = document.getElementById('userSearch').value.toLowerCase();
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    document.querySelectorAll('.user-row').forEach(row => {
        const matchSearch = !q || row.dataset.name.includes(q) || row.dataset.email.includes(q);
        const matchRole = !role || row.dataset.role === role;
        const matchStatus = !status || row.dataset.active === status;
        row.style.display = (matchSearch && matchRole && matchStatus) ? '' : 'none';
    });
}

function filterOrders() {
    const q = document.getElementById('orderSearch').value.toLowerCase();
    const status = document.getElementById('orderStatusFilter').value;
    document.querySelectorAll('.order-row').forEach(row => {
        const matchSearch = !q || row.dataset.num.includes(q) || row.dataset.customer.includes(q);
        const matchStatus = !status || row.dataset.status === status;
        row.style.display = (matchSearch && matchStatus) ? '' : 'none';
    });
}


document.querySelectorAll('.sidebar-menu a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

const sections = document.querySelectorAll('.content-section, .top-bar');
const navLinks = document.querySelectorAll('.sidebar-menu a[data-section]');

function updateActiveLink() {
    let current = '';
    sections.forEach(s => {
        if (window.scrollY >= s.offsetTop - 100) current = s.getAttribute('id');
    });
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('data-section') === current) link.classList.add('active');
    });
}
window.addEventListener('scroll', updateActiveLink);
updateActiveLink();

function toggleNotif() {
    const dd = document.getElementById('notifDropdown');
    dd.style.display = dd.style.display === 'block' ? 'none' : 'block';
    if (dd.style.display === 'block') loadNotifications();
}

document.addEventListener('click', function (e) {
    const bell = document.querySelector('[onclick="toggleNotif()"]');
    const dd = document.getElementById('notifDropdown');
    if (bell && dd && !bell.contains(e.target) && !dd.contains(e.target)) {
        dd.style.display = 'none';
    }
});

function loadNotifications() {
    fetch('/notifications/latest', {
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
    })
        .then(r => r.json())
        .then(data => {
            const list = document.getElementById('notifList');
            if (data.notifications && data.notifications.length > 0) {
                list.innerHTML = data.notifications.map(n => `
        <div style="padding:12px 16px; border-bottom:1px solid #f0f0f0;
                    font-size:13px; background:${n.is_read ? 'white' : '#e8f4fd'}">
          <div style="font-weight:600; color:#1b2a4a">${n.title}</div>
          <div style="color:#666; font-size:12px">${n.message}</div>
          <div style="color:#aaa; font-size:11px; margin-top:3px">${n.time}</div>
        </div>
      `).join('');
            } else {
                list.innerHTML = '<div style="padding:25px; text-align:center; color:#aaa;"><i class="fas fa-check-circle fa-2x mb-2 d-block" style="color:#ccc"></i>No new notifications</div>';
            }
        }).catch(() => {});
}

function markAllRead(e) {
    e.preventDefault();
    fetch('/notifications/read-all', {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
    }).then(() => { updateNotifBadge(); loadNotifications(); });
}

function updateNotifBadge() {
    fetch('/notifications/unread-count', {
        headers: { 'Accept': 'application/json' }
    })
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('bellBadge');
            if (data.count > 0) {
                badge.textContent = data.count > 9 ? '9+' : data.count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }).catch(() => {});
}

updateNotifBadge();
setInterval(updateNotifBadge, 30000);