<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Order Form Stitchify</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
:root {
  --primary-bg: #212529;
  --accent-color: #1B2A4A;
  --copyright-bg: #575a5b;
  --text-white: #ffffff;
}
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background: #f0f2f5;
}
.registration-wrapper {
  width: 500px;
  max-width: 95%;
  background-color: var(--text-white);
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.5);
  overflow: hidden;
}
.logo-container {
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  padding: 30px;
  text-align: center;
  border-bottom: 3px solid var(--copyright-bg);
}
.logo-container h2 { color:#fff; font-size:22px; font-weight:700; margin-bottom:4px; }
.logo-container p { color:rgba(255,255,255,0.65); font-size:13px; }
.logo-image { width:120px; height:120px; margin:0 auto; display:block; object-fit:contain; border-radius:50%; padding:10px; box-shadow:0 5px 15px rgba(0,0,0,0.3); }
.form-body { padding: 36px 40px 30px; }
.registration-header { text-align:center; margin-bottom:24px; }
.registration-header h2 { color:var(--accent-color); font-weight:700; font-size:26px; margin-bottom:6px; }
.registration-header p { color:var(--copyright-bg); font-size:14px; }
.form-label { color:var(--primary-bg); font-weight:600; margin-bottom:8px; font-size:14px; }
.form-control, .form-select { border:2px solid #e0e0e0; border-radius:10px; padding:12px 15px; color:var(--primary-bg); background-color:#f8f9fa; transition:all 0.3s ease; font-size:15px; }
.form-control:focus, .form-select:focus { border-color:var(--accent-color); box-shadow:0 0 0 0.25rem rgba(14,24,48,0.15); background-color:var(--text-white); }
.form-control.is-invalid, .form-select.is-invalid { border-color:#dc3545; }
.section-divider { display:flex; align-items:center; gap:10px; margin:20px 0 16px; }
.section-divider span { font-size:12px; font-weight:700; color:var(--accent-color); letter-spacing:0.8px; text-transform:uppercase; white-space:nowrap; }
.section-divider::before, .section-divider::after { content:''; flex:1; height:1px; background:#e0e0e0; }
.meas-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:4px; }
.meas-item label { font-size:13px; font-weight:600; color:var(--primary-bg); margin-bottom:6px; display:block; }
.meas-item input { font-size:14px; }
.pay-locked-block { border:2px dashed #e0e0e0; border-radius:12px; padding:20px; text-align:center; background:#f8f9fa; margin-top:6px; }
.pay-locked-block .lock-icon { font-size:28px; color:var(--copyright-bg); }
.pay-locked-block p { color:var(--copyright-bg); font-size:13px; margin-top:8px; line-height:1.5; }
.info-block { border-radius:10px; padding:14px 16px; background:#E6F1FB; border:1.5px solid #B5D4F4; margin-top:6px; }
.info-block p { font-size:13px; color:#0C447C; line-height:1.6; }
.info-block strong { color:#042C53; }
.btn-custom { background:linear-gradient(135deg, var(--accent-color), var(--primary-bg)); color:var(--text-white); border:none; border-radius:10px; padding:14px; font-weight:600; font-size:16px; width:100%; transition:all 0.3s ease; margin-top:10px; text-transform:uppercase; letter-spacing:0.5px; cursor:pointer; }
.btn-custom:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(14,24,48,0.4); }
.error-text { color:#dc3545; font-size:0.82rem; margin-top:5px; display:block; min-height:16px; }
.row2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.appt-toggle-box {
  border-radius: 12px;
  border: 2px solid #e0e0e0;
  background: #f8f9fa;
  padding: 16px 18px;
  margin-bottom: 4px;
}
.appt-toggle-label {
  font-size: 14px;
  font-weight: 600;
  color: var(--primary-bg);
  margin-bottom: 12px;
  display: block;
}
.appt-options {
  display: flex;
  gap: 10px;
}
.appt-option {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 8px;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  padding: 10px 14px;
  cursor: pointer;
  background: #fff;
  transition: all 0.2s ease;
  font-size: 14px;
  font-weight: 500;
  color: var(--primary-bg);
  user-select: none;
}
.appt-option:hover { border-color: var(--accent-color); }
.appt-option.selected { border-color: #1B2A4A; background: #1B2A4A; color: #fff; }
.check-circle {
  width: 18px; height: 18px;
  border-radius: 50%;
  border: 2px solid #ccc;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  transition: all 0.2s;
  background: #fff;
}
.appt-option.selected .check-circle { border-color: #fff; background: #fff; }
.check-circle i { font-size: 10px; color: #1B2A4A; display: none; }
.appt-option.selected .check-circle i { display: block; }
.appt-booking-section {
  background: #EAF3DE;
  border: 1.5px solid #C0DD97;
  border-radius: 12px;
  padding: 16px 18px;
  margin-top: 12px;
  display: none;
}
.appt-booking-section .appt-title {
  font-size: 13px;
  font-weight: 700;
  color: #3B6D11;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.appt-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.appt-field label { font-size:13px; font-weight:600; color:var(--primary-bg); margin-bottom:6px; display:block; }
</style>
</head>
<body>

{{-- Tailor ID hidden --}}
<input type="hidden" id="tailorId" value="{{ $tailor->id }}">

<div class="registration-wrapper">
  <div class="logo-container">
    <img src="logo.png" alt="Stitchify" class="logo-image" onerror="this.style.display='none'">
    <h2>Stitchify</h2>
    <p>Place your order below</p>
  </div>

  <div class="form-body">
    <div class="registration-header">
      <h2>New Order</h2>
      <p>Fill in all details to place your order</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- CONTACT & DELIVERY -->
    <div class="section-divider"><span>Contact & Delivery</span></div>

    <div class="mb-3">
      <label class="form-label">Full Name *</label>
      <input id="cname" class="form-control" placeholder="Enter your full name" type="text">
      <span class="error-text" id="cnameErr"></span>
    </div>

    <div class="mb-3">
      <label class="form-label">Phone Number *</label>
      <input id="cphone" class="form-control" placeholder="+92 300 1234567" type="tel">
      <span class="error-text" id="cphoneErr"></span>
    </div>

    <div class="mb-3">
      <label class="form-label">Delivery Address *</label>
      <textarea id="caddr" class="form-control" rows="2" placeholder="House no, street, area, city"></textarea>
      <span class="error-text" id="caddrErr"></span>
    </div>

    <div class="mb-3">
      <label class="form-label">City *</label>
      <input id="ccity" class="form-control" placeholder="Lahore" type="text">
      <span class="error-text" id="ccityErr"></span>
    </div>

    <!-- GARMENT & ORDER -->
    <div class="section-divider"><span>Garment & Order</span></div>

    <div class="mb-3">
      <label class="form-label">Garment Type *</label>
      <select id="garment" class="form-select">
        <option value="">Select garment type</option>
        <option>Shalwar Kameez (Men)</option>
        <option>Shalwar Kameez (Women)</option>
        <option>Kurta</option>
        <option>Suit / Pant Coat</option>
        <option>Abaya</option>
        <option>Children's Dress</option>
        <option>Other</option>
      </select>
      <span class="error-text" id="garmentErr"></span>
    </div>

    <div class="mb-3">
      <label class="form-label">Special Instructions</label>
      <textarea id="notes" class="form-control" rows="2" placeholder="collar style, pocket type, embroidery detail, etc."></textarea>
      <div id="imagePreviewBox" style="display:none; margin-top:10px; flex-wrap:wrap; gap:8px;"></div>
      <label for="designImages" style="display:flex; align-items:center; gap:8px; margin-top:10px; padding:11px 15px; border:2px dashed #e0e0e0; border-radius:10px; background:#f8f9fa; cursor:pointer; transition:border-color 0.3s;">
        <i class="fas fa-image" style="color:#1B2A4A; font-size:18px;"></i>
        <span style="font-size:14px; color:#575a5b;" id="imageLabel">Upload design</span>
      </label>
      <input type="file" id="designImages" accept="image/*" multiple style="display:none;">
    </div>

    <!-- MEASUREMENTS -->
    <div class="section-divider"><span>Measurements</span></div>

    <div class="appt-toggle-box mb-2">
      <span class="appt-toggle-label">
        <i class="fas fa-ruler-combined" style="color:#1B2A4A; font-size:14px;"></i>
        &nbsp;Do you want to book appointment for measurement? *
      </span>
      <div class="appt-options">
        <div class="appt-option" id="btnYes" onclick="selectAppt('yes')">
          <div class="check-circle" id="circleYes"><i class="fas fa-check"></i></div>
          <span>Yes</span>
        </div>
        <div class="appt-option" id="btnNo" onclick="selectAppt('no')">
          <div class="check-circle" id="circleNo"><i class="fas fa-check"></i></div>
          <span>No</span>
        </div>
      </div>
      <span class="error-text" id="apptChoiceErr"></span>

      <div class="appt-booking-section" id="apptBookingSection">
        <div class="appt-title">
          <i class="fas fa-calendar-check"></i> Appointment Details
        </div>
        <div class="appt-row">
          <div class="appt-field">
            <label>Appointment Date *</label>
            <input id="apptDate" class="form-control" type="date">
            <span class="error-text" id="apptDateErr"></span>
          </div>
          <div class="appt-field">
            <label>Preferred Time *</label>
            <select id="apptTime" class="form-select">
              <option value="">Select time</option>
              <option>09:00 AM – 10:00 AM</option>
              <option>10:00 AM – 11:00 AM</option>
              <option>11:00 AM – 12:00 PM</option>
              <option>12:00 PM – 01:00 PM</option>
              <option>02:00 PM – 03:00 PM</option>
              <option>03:00 PM – 04:00 PM</option>
              <option>04:00 PM – 05:00 PM</option>
              <option>05:00 PM – 06:00 PM</option>
            </select>
            <span class="error-text" id="apptTimeErr"></span>
          </div>
        </div>
        <div style="margin-top:10px; background:#fff; border-radius:8px; padding:10px 12px; border:1px solid #C0DD97;">
          <p style="font-size:12px; color:#3B6D11; margin:0;">
            <i class="fas fa-info-circle"></i>
            &nbsp;Tailor will contact you further.
          </p>
        </div>
      </div>
    </div>

    <!-- Manual Measurements -->
    <div id="manualMeasSection" style="display:none;">
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Chest / Seena</label>
          <input id="mChest" class="form-control" type="number" placeholder="e.g. 40">
        </div>
        <div class="meas-item">
          <label>Waist / Kamar</label>
          <input id="mWaist" class="form-control" type="number" placeholder="e.g. 36">
        </div>
        <div class="meas-item">
          <label>Length / Lambai</label>
          <input id="mLength" class="form-control" type="number" placeholder="e.g. 46">
        </div>
        <div class="meas-item">
          <label>Shoulder / Kandha</label>
          <input id="mShoulder" class="form-control" type="number" placeholder="e.g. 18">
        </div>
        <div class="meas-item">
          <label>Sleeve / Baazu</label>
          <input id="mSleeve" class="form-control" type="number" placeholder="e.g. 25">
        </div>
        <div class="meas-item">
          <label>Neck / Gala</label>
          <input id="mNeck" class="form-control" type="number" placeholder="e.g. 15">
        </div>
      </div>
    </div>

    <!-- FABRIC DETAILS -->
    <div class="section-divider"><span>Fabric Details</span></div>

    <div class="mb-3">
      <label class="form-label">Fabric Name *</label>
      <input id="fabricName" class="form-control" placeholder="e.g. Lawn, Khaddar, Silk, Linen, Cotton" type="text">
      <span class="error-text" id="fabricNameErr"></span>
    </div>

    <div class="mb-3">
      <label class="form-label">Fabric Color *</label>
      <input id="fabricColorText" class="form-control" placeholder="e.g. Navy Blue, Off White, Dark Red" type="text">
      <span class="error-text" id="fabricColorErr"></span>
    </div>

    <!-- PAYMENT -->
    <div class="section-divider"><span>Payment</span></div>

    <div class="info-block mb-3">
      <p><strong>Payment details</strong> will be available after order accepted. <strong>JazzCash / Easypaisa</strong> options are available for this.</p>
    </div>

    <div class="pay-locked-block">
      <div class="lock-icon"><i class="fas fa-lock"></i></div>
      <p><strong>Payment Locked</strong><br>This section will be unlocked after tailor accepts the order.</p>
    </div>

    <button class="btn-custom" id="submitBtn" type="button">
      <i class="fas fa-check"></i> &nbsp;Submit Order
    </button>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
  let apptChoice = null;

  function selectAppt(val) {
    apptChoice = val;
    document.getElementById('apptChoiceErr').textContent = '';

    const btnYes = document.getElementById('btnYes');
    const btnNo  = document.getElementById('btnNo');

    btnYes.className = 'appt-option' + (val === 'yes' ? ' selected' : '');
    btnNo.className  = 'appt-option' + (val === 'no'  ? ' selected' : '');

    const apptSection   = document.getElementById('apptBookingSection');
    const manualSection = document.getElementById('manualMeasSection');

    if (val === 'yes') {
      apptSection.style.display   = 'block';
      manualSection.style.display = 'none';
    } else {
      apptSection.style.display   = 'none';
      manualSection.style.display = 'block';
    }
  }

  let uploadedFiles = [];

  function renderPreviews() {
    const previewBox = document.getElementById('imagePreviewBox');
    const label = document.getElementById('imageLabel');
    previewBox.innerHTML = '';
    previewBox.style.display = uploadedFiles.length ? 'flex' : 'none';

    uploadedFiles.forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = e => {
        const wrap = document.createElement('div');
        wrap.style.cssText = 'position:relative; width:72px; height:72px; flex-shrink:0;';
        wrap.innerHTML = `
          <img src="${e.target.result}" style="width:72px;height:72px;object-fit:cover;border-radius:8px;border:2px solid #e0e0e0;">
          <button onclick="removeImage(${index})" title="Remove" style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:#dc3545;color:#fff;border:none;cursor:pointer;font-size:11px;display:flex;align-items:center;justify-content:center;padding:0;line-height:1;">&#x2715;</button>
        `;
        previewBox.appendChild(wrap);
      };
      reader.readAsDataURL(file);
    });

    label.textContent = uploadedFiles.length > 0 ? uploadedFiles.length + ' picture(s) selected' : 'Upload design pictures (optional)';
  }

  function removeImage(index) {
    uploadedFiles.splice(index, 1);
    renderPreviews();
    document.getElementById('designImages').value = '';
  }

  document.getElementById('designImages').addEventListener('change', function () {
    const newFiles = Array.from(this.files);
    uploadedFiles = uploadedFiles.concat(newFiles);
    renderPreviews();
  });

  document.querySelector('label[for="designImages"]').addEventListener('mouseover', function() {
    this.style.borderColor = '#1B2A4A';
  });
  document.querySelector('label[for="designImages"]').addEventListener('mouseout', function() {
    this.style.borderColor = '#e0e0e0';
  });

  function validate() {
    let ok = true;

    const required = [
      ['cname',           'cnameErr',       'Full name is required'],
      ['cphone',          'cphoneErr',      'Phone number is required'],
      ['caddr',           'caddrErr',       'Delivery address is required'],
      ['ccity',           'ccityErr',       'City is required'],
      ['garment',         'garmentErr',     'Please select a garment type'],
      ['fabricName',      'fabricNameErr',  'Fabric name is required'],
      ['fabricColorText', 'fabricColorErr', 'Fabric color is required'],
    ];

    required.forEach(([id, errId, msg]) => {
      const el = document.getElementById(id);
      if (!el) return;
      const err = document.getElementById(errId);
      if (!el.value.trim()) {
        el.classList.add('is-invalid');
        err.textContent = msg;
        ok = false;
      } else {
        el.classList.remove('is-invalid');
        err.textContent = '';
      }
    });

    if (!apptChoice) {
      document.getElementById('apptChoiceErr').textContent = 'choose atleast 1 option';
      ok = false;
    } else if (apptChoice === 'yes') {
      const apptDate = document.getElementById('apptDate');
      const apptTime = document.getElementById('apptTime');
      if (!apptDate.value) {
        apptDate.classList.add('is-invalid');
        document.getElementById('apptDateErr').textContent = 'Appointment date required';
        ok = false;
      } else {
        apptDate.classList.remove('is-invalid');
        document.getElementById('apptDateErr').textContent = '';
      }
      if (!apptTime.value) {
        apptTime.classList.add('is-invalid');
        document.getElementById('apptTimeErr').textContent = 'Preferred time required';
        ok = false;
      } else {
        apptTime.classList.remove('is-invalid');
        document.getElementById('apptTimeErr').textContent = '';
      }
    }

    return ok;
  }

  document.getElementById('submitBtn').addEventListener('click', () => {
    if (!validate()) {
      const firstError = document.querySelector('.is-invalid');
      if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    const data = {
      name:    document.getElementById('cname').value.trim(),
      phone:   document.getElementById('cphone').value.trim(),
      address: document.getElementById('caddr').value.trim(),
      city:    document.getElementById('ccity').value.trim(),
      garment: document.getElementById('garment').value,
      notes:   document.getElementById('notes').value.trim(),
      measurementMethod: apptChoice === 'yes' ? 'appointment' : 'manual',
      appointment: apptChoice === 'yes' ? {
        date: document.getElementById('apptDate').value,
        time: document.getElementById('apptTime').value
      } : null,
      measurements: apptChoice === 'no' ? {
        chest:    document.getElementById('mChest').value,
        waist:    document.getElementById('mWaist').value,
        length:   document.getElementById('mLength').value,
        shoulder: document.getElementById('mShoulder').value,
        sleeve:   document.getElementById('mSleeve').value,
        neck:     document.getElementById('mNeck').value,
      } : null,
      fabric: {
        name:      document.getElementById('fabricName').value.trim(),
        colorName: document.getElementById('fabricColorText').value.trim(),
      }
    };

    // Laravel ko form submit karo
    const form = document.createElement('form');
    form.method  = 'POST';
    form.action  = '{{ route("order.place") }}';

    const fields = {
        '_token':               '{{ csrf_token() }}',
        'tailor_id':            document.getElementById('tailorId').value,
        'dress_type':           data.garment,
        'fabric_name':          data.fabric.name,
        'fabric_color':         data.fabric.colorName,
        'fabric_provided_by':   'customer',
        'delivery_type':        'home_delivery',
        'special_instructions': data.notes,
        'measurement_method':   data.measurementMethod,
        'chest':          data.measurements?.chest    ?? '',
        'waist':          data.measurements?.waist    ?? '',
        'shirt_length':   data.measurements?.length   ?? '',
        'shoulder':       data.measurements?.shoulder ?? '',
        'sleeve_length':  data.measurements?.sleeve   ?? '',
        'neck':           data.measurements?.neck     ?? '',
    };

    Object.entries(fields).forEach(([name, value]) => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = name;
        input.value = value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
  });
</script>
</body>
</html>
