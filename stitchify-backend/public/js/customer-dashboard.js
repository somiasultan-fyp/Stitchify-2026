document.addEventListener('DOMContentLoaded', function () {
    updateNotifBadge();

    setInterval(updateNotifBadge, 30000);

    const anchors = document.querySelectorAll('.sidebar-menu a[href^="#"]');

    anchors.forEach(function (anchor) {
        anchor.addEventListener('click', function (event) {
            event.preventDefault();

            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);

            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    updateActiveLink();

    window.addEventListener('scroll', updateActiveLink);
});

function toggleNotif() {
    const dropdown = document.getElementById('notifDropdown');

    if (!dropdown) {
        return;
    }

    dropdown.classList.toggle('show');

    if (dropdown.classList.contains('show')) {
        loadNotifications();
    }
}

document.addEventListener('click', function (event) {
    const wrapper = document.querySelector('.bell-wrapper');
    const dropdown = document.getElementById('notifDropdown');

    if (!wrapper || !dropdown) {
        return;
    }

    if (!wrapper.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');

    return token ? token.getAttribute('content') : '';
}

function loadNotifications() {
    const list = document.getElementById('notifList');

    if (!list) {
        return;
    }

    fetch('/notifications/latest', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        }
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Failed to load notifications');
            }

            return response.json();
        })
        .then(function (data) {
            if (data.notifications && data.notifications.length > 0) {
                list.innerHTML = data.notifications.map(function (notification) {
                    return `
                        <div class="notif-item ${notification.is_read ? '' : 'unread'}">
                            <div class="notif-title">${escapeHtml(notification.title || '')}</div>
                            <div class="notif-msg">${escapeHtml(notification.message || '')}</div>
                            <div class="notif-time">${escapeHtml(notification.time || '')}</div>
                        </div>
                    `;
                }).join('');
            } else {
                list.innerHTML = `
                    <div class="notif-empty">
                        <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                        No new notifications
                    </div>
                `;
            }
        })
        .catch(function () {
            list.innerHTML = `
                <div class="notif-empty">
                    Unable to load notifications
                </div>
            `;
        });
}

function markAllRead(event) {
    event.preventDefault();

    fetch('/notifications/read-all', {
        method: 'PATCH',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        }
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Failed to mark notifications as read');
            }

            updateNotifBadge();
            loadNotifications();
        })
        .catch(function () {
            loadNotifications();
        });
}

function updateNotifBadge() {
    const badge = document.getElementById('bellBadge');

    if (!badge) {
        return;
    }

    fetch('/notifications/unread-count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        }
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Failed to load notification count');
            }

            return response.json();
        })
        .then(function (data) {
            const count = Number(data.count || 0);

            if (count > 0) {
                badge.textContent = count > 9 ? '9+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(function () {
            badge.style.display = 'none';
        });
}

function updateActiveLink() {
    const sections = document.querySelectorAll('.content-section, .top-bar');
    const navLinks = document.querySelectorAll('.sidebar-menu a[data-section]');

    let current = '';

    sections.forEach(function (section) {
        const sectionTop = section.offsetTop - 100;

        if (window.scrollY >= sectionTop) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(function (link) {
        link.classList.remove('active');

        if (link.getAttribute('data-section') === current) {
            link.classList.add('active');
        }
    });
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value;

    return div.innerHTML;
}