const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let currentAcceptId = null;
let currentRejectId = null;

function showToast(msg, type = 'success') {
  const toast = document.getElementById('mainToast');
  const toastMsg = document.getElementById('toastMsg');
  toastMsg.textContent = msg;
  toast.className = `toast align-items-center text-white border-0 bg-${type}`;
  new bootstrap.Toast(toast, { delay: 3000 }).show();
}

function openAcceptModal(orderId, orderNum) {
  currentAcceptId = orderId;
  document.getElementById('acceptOrderNum').textContent = '#' + orderNum;
  document.getElementById('acceptPrice').value = '';
  document.getElementById('acceptDays').value = '';
  new bootstrap.Modal(document.getElementById('acceptModal')).show();
}

async function confirmAccept() {
  const price = document.getElementById('acceptPrice').value;
  const days = document.getElementById('acceptDays').value;

  if (!price || price < 1) {
    document.getElementById('acceptPrice').classList.add('is-invalid');
    return;
  }
  if (!days || days < 1) {
    document.getElementById('acceptDays').classList.add('is-invalid');
    return;
  }

  document.getElementById('acceptPrice').classList.remove('is-invalid');
  document.getElementById('acceptDays').classList.remove('is-invalid');

  const btn = document.getElementById('confirmAcceptBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Accepting...';

  try {
    const res = await fetch(`/tailor/order/${currentAcceptId}/accept`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
      },
      body: JSON.stringify({ price, delivery_days: days }),
    });

    const data = await res.json();

    if (data.success) {
      bootstrap.Modal.getInstance(document.getElementById('acceptModal')).hide();

      const card = document.getElementById(`pending-card-${currentAcceptId}`);
      if (card) card.remove();

      showToast('Order accepted. Notification sent to customer.', 'success');

      setTimeout(() => location.reload(), 1500);
    } else {
      showToast(data.message || 'Something went wrong.', 'danger');
    }
  } catch (err) {
    showToast('Server error. Try again.', 'danger');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-check me-1"></i> Accept Order';
  }
}

function openRejectModal(orderId, orderNum) {
  currentRejectId = orderId;
  document.getElementById('rejectOrderNum').textContent = '#' + orderNum;
  document.getElementById('rejectReason').value = '';
  new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

async function confirmReject() {
  const reason = document.getElementById('rejectReason').value.trim();

  if (!reason) {
    document.getElementById('rejectReason').classList.add('is-invalid');
    return;
  }
  document.getElementById('rejectReason').classList.remove('is-invalid');

  try {
    const res = await fetch(`/tailor/order/${currentRejectId}/reject`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
      },
      body: JSON.stringify({ rejection_reason: reason }),
    });

    const data = await res.json();

    if (data.success) {
      bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();

      const card = document.getElementById(`pending-card-${currentRejectId}`);
      if (card) card.remove();

      showToast('Order rejected. Notification sent to customer.', 'warning');
      setTimeout(() => location.reload(), 1500);
    } else {
      showToast(data.message || 'Something went wrong.', 'danger');
    }
  } catch (err) {
    showToast('Server error. Try again.', 'danger');
  }
}

async function updateStatus(orderId, newStatus, btn) {
  const labels = {
    in_progress: 'Stitching started.',
    ready: 'Order marked as ready.',
    dispatched: 'Order dispatched.',
  };

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

  try {
    const res = await fetch(`/tailor/order/${orderId}/status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
      },
      body: JSON.stringify({ status: newStatus }),
    });

    const data = await res.json();

    if (data.success) {
      showToast(labels[newStatus] || 'Status updated.', 'success');
      setTimeout(() => location.reload(), 1200);
    } else {
      showToast(data.message || 'Something went wrong.', 'danger');
      btn.disabled = false;
    }
  } catch (err) {
    showToast('Server error. Try again.', 'danger');
    btn.disabled = false;
  }
}

async function viewDetail(orderId) {
  document.getElementById('detailBody').innerHTML = `
    <div class="text-center py-4">
      <div class="spinner-border text-primary"></div>
      <p class="mt-2 text-muted">Loading...</p>
    </div>`;

  new bootstrap.Modal(document.getElementById('detailModal')).show();

  try {
    const res = await fetch(`/tailor/order/${orderId}/detail`);
    const data = await res.json();

    if (data.success) {
      const o = data.order;
      const m = o.measurement;

      document.getElementById('detailBody').innerHTML = `
        <div class="row g-3">
          <div class="col-md-6">
            <h6 class="fw-bold mb-3" style="color:var(--accent-color)">Order Info</h6>
            <table class="table table-borderless table-sm">
              <tr><th>Order #</th><td>${o.order_number}</td></tr>
              <tr><th>Customer</th><td>${o.customer_name}</td></tr>
              <tr><th>Phone</th><td>${o.customer_phone || '—'}</td></tr>
              <tr><th>Dress Type</th><td>${o.dress_type}</td></tr>
              <tr><th>Fabric Detail</th><td>${o.fabric_details || '—'}</td></tr>
              <tr><th>Delivery Type</th><td>${o.delivery_type}</td></tr>
              <tr><th>Special Note</th><td>${o.special_instructions || 'None'}</td></tr>
              <tr><th>Status</th><td><span class="badge bg-warning text-dark">${o.status}</span></td></tr>
              <tr><th>Price</th><td>${o.price ? 'Rs. ' + o.price : '—'}</td></tr>
              <tr><th>Expected</th><td>${o.expected_delivery_date || '—'}</td></tr>
              <tr><th>Date</th><td>${o.created_at}</td></tr>
            </table>
          </div>
          <div class="col-md-6">
            <h6 class="fw-bold mb-3" style="color:var(--accent-color)">Measurements (inches)</h6>
            ${m ? `
            <table class="table table-borderless table-sm">
              <tr><th>Chest</th><td>${m.chest || '—'}"</td></tr>
              <tr><th>Waist</th><td>${m.waist || '—'}"</td></tr>
              <tr><th>Hips</th><td>${m.hips || '—'}"</td></tr>
              <tr><th>Shoulder</th><td>${m.shoulder || '—'}"</td></tr>
              <tr><th>Sleeve Length</th><td>${m.sleeve_length || '—'}"</td></tr>
              <tr><th>Shirt Length</th><td>${m.shirt_length || '—'}"</td></tr>
              <tr><th>Trouser Length</th><td>${m.trouser_length || '—'}"</td></tr>
              <tr><th>Trouser Waist</th><td>${m.trouser_waist || '—'}"</td></tr>
              <tr><th>Neck</th><td>${m.neck || '—'}"</td></tr>
              ${m.additional_notes ? `<tr><th>Notes</th><td>${m.additional_notes}</td></tr>` : ''}
            </table>` : '<p class="text-muted">Measurements are not available.</p>'}
          </div>
        </div>`;
    }
  } catch (err) {
    document.getElementById('detailBody').innerHTML =
      '<p class="text-danger text-center">Detail could not be loaded.</p>';
  }
}

document.querySelectorAll('.sidebar-menu a[href^="#"]').forEach((anchor) => {
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
  sections.forEach((s) => {
    if (window.scrollY >= s.offsetTop - 100) current = s.getAttribute('id');
  });
  navLinks.forEach((link) => {
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
  const wrapper = document.querySelector('.bell-wrapper');
  if (wrapper && !wrapper.contains(e.target)) {
    document.getElementById('notifDropdown').style.display = 'none';
  }
});

function loadNotifications() {
  fetch('/notifications/latest', {
    headers: {
      'X-CSRF-TOKEN': CSRF,
      Accept: 'application/json',
    },
  })
    .then((r) => r.json())
    .then((data) => {
      const list = document.getElementById('notifList');
      if (data.notifications && data.notifications.length > 0) {
        list.innerHTML = data.notifications
          .map(
            (n) => `
                <div class="notif-item ${n.is_read ? '' : 'unread'}">
                    <div class="notif-item-title">${n.title}</div>
                    <div class="notif-item-message">${n.message}</div>
                    <div class="notif-item-time">${n.time}</div>
                </div>
            `
          )
          .join('');
      } else {
        list.innerHTML =
          '<div class="notif-empty"><i class="fas fa-check-circle fa-2x mb-2 d-block" style="color:#ccc"></i>No new notifications</div>';
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
      Accept: 'application/json',
    },
  }).then(() => {
    updateNotifBadge();
    loadNotifications();
  });
}

function updateNotifBadge() {
  fetch('/notifications/unread-count', {
    headers: { Accept: 'application/json' },
  })
    .then((r) => r.json())
    .then((data) => {
      const badge = document.getElementById('bellBadge');
      if (data.count > 0) {
        badge.textContent = data.count > 9 ? '9+' : data.count;
        badge.style.display = 'flex';
      } else {
        badge.style.display = 'none';
      }
    })
    .catch(() => {});
}

updateNotifBadge();
setInterval(updateNotifBadge, 30000);