const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let currentAcceptId = null;
let currentRejectId = null;

function showToast(msg, type = 'success') {
    const toast = document.getElementById('mainToast');
    const toastMsg = document.getElementById('toastMsg');

    if (!toast || !toastMsg) return;

    toastMsg.textContent = msg;
    toast.className = `toast align-items-center text-white border-0 bg-${type}`;

    new bootstrap.Toast(toast, {
        delay: 3000
    }).show();
}

function openAcceptModal(orderId, orderNum) {
    currentAcceptId = orderId;

    document.getElementById('acceptOrderNum').textContent = '#' + orderNum;
    document.getElementById('acceptPrice').value = '';
    document.getElementById('acceptDays').value = '';

    document.getElementById('acceptPrice').classList.remove('is-invalid');
    document.getElementById('acceptDays').classList.remove('is-invalid');

    new bootstrap.Modal(
        document.getElementById('acceptModal')
    ).show();
}

async function confirmAccept() {
    const priceInput = document.getElementById('acceptPrice');
    const daysInput = document.getElementById('acceptDays');

    const price = priceInput.value;
    const days = daysInput.value;

    if (!price || Number(price) < 1) {
        priceInput.classList.add('is-invalid');
        return;
    }

    if (!days || Number(days) < 1 || Number(days) > 60) {
        daysInput.classList.add('is-invalid');
        return;
    }

    priceInput.classList.remove('is-invalid');
    daysInput.classList.remove('is-invalid');

    const btn = document.getElementById('confirmAcceptBtn');

    btn.disabled = true;
    btn.innerHTML =
        '<span class="spinner-border spinner-border-sm me-1"></span> Accepting...';

    try {
        const response = await fetch(
            `/tailor/orders/${currentAcceptId}/accept`,
        {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
           'X-Requested-With': 'XMLHttpRequest'
         },
          body: JSON.stringify({
          price: price,
          delivery_days: days
          })
         });

        const data = await response.json();

        if (response.ok && data.success) {
            const modal = bootstrap.Modal.getInstance(
                document.getElementById('acceptModal')
            );

        if (modal) {
            modal.hide();
        }

        const card = document.getElementById(
            `pending-card-${currentAcceptId}`
        );

        if (card) {
            card.remove();
        }

        showToast(
            'Order accepted. Notification sent to customer.',
            'success'
        );

        setTimeout(() => {
            location.reload();
         }, 1000);
        } else {
        showToast(
            data.message || 'Unable to accept order.',
            'danger'
        );
        }
    } 
      catch (error) {
        showToast(
          'Server error. Please try again.',
          'danger'
        );
    } 
      finally {
        btn.disabled = false;
        btn.innerHTML =
          '<i class="fas fa-check me-1"></i> Accept Order';
    }
}

function openRejectModal(orderId, orderNum) {
    currentRejectId = orderId;

    document.getElementById('rejectOrderNum').textContent =
        '#' + orderNum;

    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectReason').classList.remove('is-invalid');

    new bootstrap.Modal(
        document.getElementById('rejectModal')
    ).show();
}

async function confirmReject() {
    const reasonInput = document.getElementById('rejectReason');
    const reason = reasonInput.value.trim();

    if (!reason) {
        reasonInput.classList.add('is-invalid');
        return;
    }

    reasonInput.classList.remove('is-invalid');

    const btn = document.getElementById('confirmRejectBtn');

    btn.disabled = true;
    btn.innerHTML =
        '<span class="spinner-border spinner-border-sm me-1"></span> Rejecting...';

    try {
        const response = await fetch(
            `/tailor/orders/${currentRejectId}/reject`,
        {
         method: 'PATCH',
         headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
         },
         body: JSON.stringify({
         rejection_reason: reason
         })
       }
        );

        const data = await response.json();

        if (response.ok && data.success) {
            const modal = bootstrap.Modal.getInstance(
                document.getElementById('rejectModal')
            );

            if (modal) {
                modal.hide();
            }

            const card = document.getElementById(
                `pending-card-${currentRejectId}`
            );

            if (card) {
                card.remove();
            }

            showToast(
                'Order rejected. Notification sent to customer.',
                'warning'
            );

            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(
                data.message || 'Unable to reject order.',
                'danger'
            );
        }
    } catch (error) {
        showToast(
            'Server error. Please try again.',
            'danger'
        );
    } finally {
        btn.disabled = false;
        btn.innerHTML =
            '<i class="fas fa-times me-1"></i> Reject Order';
    }
}

async function updateStatus(orderId, newStatus, btn) {
    const messages = {
        in_progress: 'Stitching started.',
        ready: 'Order marked as ready.',
        dispatched: 'Order dispatched.',
        delivered: 'Order delivered.'
    };

    const originalHtml = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML =
        '<span class="spinner-border spinner-border-sm"></span>';

    try {
        const response = await fetch(
            `/tailor/orders/${orderId}/status`,
            {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            }
        );

        const data = await response.json();

        if (response.ok && data.success) {
            btn.classList.add('d-none');

            const completedMessage = document.getElementById(
                `task-complete-${orderId}`
            );

        if (completedMessage) {
            completedMessage.textContent =
           `${messages[newStatus] || 'Task completed.'}`;
            completedMessage.classList.remove('d-none');
       }

        showToast(
            messages[newStatus] || 'Status updated.',
           'success'
         );

       setTimeout(() => {
            location.reload();
            }, 900);
        } else {
            showToast(
                data.message || 'Unable to update status.',
                'danger'
            );

            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    } catch (error) {
        showToast(
            'Server error. Please try again.',
            'danger'
        );

        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
}

async function viewDetail(orderId) {
    const detailBody = document.getElementById('detailBody');

    if (!detailBody) return;

    detailBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 text-muted">Loading...</p>
        </div>
    `;

    const modalElement = document.getElementById('detailModal');

    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    try {
        const response = await fetch(
            `/tailor/orders/${orderId}`,
          {
            method: 'GET',
            headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF
    },
        credentials: 'same-origin'
         }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
            detailBody.innerHTML =
                '<p class="text-danger text-center">Detail could not be loaded.</p>';
            return;
        }

        const o = data.order;
        const m = o.measurement;

    detailBody.innerHTML = `
       <div class="row g-3">
         <div class="col-md-6">
            <h6 class="fw-bold mb-3">
              Order Info
            </h6>
           <table class="table table-borderless table-sm">
           <tr>
            <th>Order #</th>
            <td>${o.order_number || '—'}</td>
          </tr>
          <tr>
            <th>Customer</th>
            <td>${o.recipient_name || o.customer_name || '—'}</td>
          </tr>
           <tr>
            <th>Phone</th>
            <td>${o.recipient_phone || o.customer_phone || '—'}</td>
           </tr>
           <tr>
            <th>Address</th>
            <td>${o.recipient_address || '—'}</td>
           </tr>
           <tr>
            <th>City</th>
            <td>${o.recipient_city || '—'}</td>
           </tr>
           <tr>
             <th>Dress Type</th>
             <td>${o.dress_type || '—'}</td>
           </tr>
           <tr>
             <th>Fabric</th>
             <td>${o.fabric_details || '—'}</td>
          </tr>
          <tr>
             <th>Delivery Type</th>
             <td>${o.delivery_type || '—'}</td>
          </tr>
          <tr>
             <th>Status</th>
             <td><span class="badge bg-warning text-dark">
            ${(o.status || '').replaceAll('_', ' ')}
            </span>
             </td>
          </tr>
          <tr>
             <th>Price</th>
             <td>${o.price ? 'Rs. ' + o.price : '—'}</td>
          </tr>
                        <tr>
                            <th>Expected Delivery</th>
                            <td>${o.expected_delivery_date || '—'}</td>
                        </tr>

                        <tr>
                            <th>Order Date</th>
                            <td>${o.created_at || '—'}</td>
                        </tr>

                        ${
                            o.design_image
                                ? `
                                    <tr>
                                        <th>Design Image</th>
                                        <td>
                                            <img
                                                src="${o.design_image}"
                                                style="max-width:150px;border-radius:8px;cursor:pointer;"
                                                onclick="window.open('${o.design_image}', '_blank')"
                                            >
                                        </td>
                                    </tr>
                                `
                                : ''
                        }

                        <tr>
                            <th>Special Notes</th>
                            <td>${o.special_instructions || '—'}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">
                        Measurements
                    </h6>

                    ${
                        m
                            ? `
                                <table class="table table-borderless table-sm">

                                    <tr>
                                        <th>Chest</th>
                                        <td>${m.chest || '—'}"</td>
                                    </tr>

                                    <tr>
                                        <th>Waist</th>
                                        <td>${m.waist || '—'}"</td>
                                    </tr>

                                    <tr>
                                        <th>Hips</th>
                                        <td>${m.hips || '—'}"</td>
                                    </tr>

                                    <tr>
                                        <th>Shoulder</th>
                                        <td>${m.shoulder || '—'}"</td>
                                    </tr>

                                    <tr>
                                        <th>Sleeve Length</th>
                                        <td>${m.sleeve_length || '—'}"</td>
                                    </tr>

                                    <tr>
                                        <th>Shirt Length</th>
                                        <td>${m.shirt_length || '—'}"</td>
                                    </tr>

                                    <tr>
                                        <th>Trouser Length</th>
                                        <td>${m.trouser_length || '—'}"</td>
                                    </tr>

                                    <tr>
                                        <th>Trouser Waist</th>
                                        <td>${m.trouser_waist || '—'}"</td>
                                    </tr>

                                    <tr>
                                        <th>Neck</th>
                                        <td>${m.neck || '—'}"</td>
                                    </tr>

                                    <tr>
                                        <th>Additional Notes</th>
                                        <td>${m.additional_notes || '—'}</td>
                                    </tr>

                                    <tr>
                                        <th>Details</th>
                                        <td>${m.details || '—'}</td>
                                    </tr>

                                </table>
                            `
                            : '<p class="text-muted">No measurements.</p>'
                    }
                </div>

            </div>
        `;
    } catch (error) {
        detailBody.innerHTML =
            '<p class="text-danger text-center">Detail could not be loaded.</p>';
    }
}

document.querySelectorAll(
    '.sidebar-menu a[href^="#"]'
).forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        const target = document.querySelector(
            this.getAttribute('href')
        );

        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        if (window.innerWidth <= 768) {
            const sidebar =
                document.getElementById('sidebar');

            if (sidebar) {
                sidebar.classList.remove('active');
            }
        }
    });
});

const sections = document.querySelectorAll(
    '.content-section, .top-bar'
);

const navLinks = document.querySelectorAll(
    '.sidebar-menu a[data-section]'
);

function updateActiveLink() {
    let current = '';

    sections.forEach((section) => {
        if (
            window.scrollY >=
            section.offsetTop - 100
        ) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach((link) => {
        link.classList.remove('active');

        if (
            link.getAttribute('data-section') ===
            current
        ) {
            link.classList.add('active');
        }
    });
}

window.addEventListener(
    'scroll',
    updateActiveLink
);

updateActiveLink();

function toggleNotif() {
    const dropdown =
        document.getElementById('notifDropdown');

    if (!dropdown) return;

    dropdown.style.display =
        dropdown.style.display === 'block'
            ? 'none'
            : 'block';

    if (dropdown.style.display === 'block') {
        loadNotifications();
    }
}

document.addEventListener(
    'click',
    function (e) {
        const wrapper =
            document.querySelector('.bell-wrapper');

        if (
            wrapper &&
            !wrapper.contains(e.target)
        ) {
            const dropdown =
                document.getElementById('notifDropdown');

            if (dropdown) {
                dropdown.style.display = 'none';
            }
        }
    }
);

function loadNotifications() {
    fetch('/notifications/latest', {
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json'
        }
    })
        .then((response) => response.json())
        .then((data) => {
            const list =
                document.getElementById('notifList');

            if (!list) return;

            if (
                data.notifications &&
                data.notifications.length > 0
            ) {
                list.innerHTML =
                    data.notifications
                        .map(
                            (notification) => `
                                <div class="notif-item ${
                                    notification.is_read
                                        ? ''
                                        : 'unread'
                                }">
                                    <div class="notif-item-title">
                                        ${notification.title}
                                    </div>

                                    <div class="notif-item-message">
                                        ${notification.message}
                                    </div>

                                    <div class="notif-item-time">
                                        ${notification.time}
                                    </div>
                                </div>
                            `
                        )
                        .join('');
            } else {
                list.innerHTML = `
                    <div class="notif-empty">
                        <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                        No new notifications
                    </div>
                `;
            }
        })
        .catch(() => {});
}

function markAllRead(e) {
    e.preventDefault();

    fetch('/notifications/read-all', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json'
        }
    })
        .then(() => {
            updateNotifBadge();
            loadNotifications();
        });
}

function updateNotifBadge() {
    fetch('/notifications/unread-count', {
        headers: {
            'Accept': 'application/json'
        }
    })
        .then((response) => response.json())
        .then((data) => {
            const badge =
                document.getElementById('bellBadge');

            if (!badge) return;

            if (data.count > 0) {
                badge.textContent =
                    data.count > 9
                        ? '9+'
                        : data.count;

                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(() => {});
}

function toggleSidebar() {
    const sidebar =
        document.getElementById('sidebar');

    if (sidebar) {
        sidebar.classList.toggle('active');
    }
    else{
        console.warn('Sidebar element not found in the DOM.');
    }
}

document.addEventListener(
    'click',
    function (e) {
        const sidebar =
            document.getElementById('sidebar');

        const toggle =
            document.querySelector('.menu-toggle');

        if (
            window.innerWidth <= 768 &&
            sidebar &&
            toggle &&
            !sidebar.contains(e.target) &&
            !toggle.contains(e.target) &&
            sidebar.classList.contains('active')
        ) {
            sidebar.classList.remove('active');
        }
    }
);

updateNotifBadge();

setInterval(
    updateNotifBadge,
    30000
);