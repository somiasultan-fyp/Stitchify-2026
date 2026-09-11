let deliveryChoice = null;

function selectDelivery(val) {
  deliveryChoice = val;
  document.getElementById('deliveryChoiceErr').textContent = '';
  document.getElementById('deliveryYes').className = 'toggle-option' + (val === 'yes' ? ' selected' : '');
  document.getElementById('deliveryNo').className  = 'toggle-option' + (val === 'no'  ? ' selected' : '');
  document.getElementById('deliveryYesNote').style.display = val === 'yes' ? 'block' : 'none';
  document.getElementById('deliveryNoNote').style.display  = val === 'no'  ? 'block' : 'none';
}

document.getElementById('garment').addEventListener('change', function() {
  document.querySelectorAll('.meas-section').forEach(el => el.style.display = 'none');
  document.getElementById('measDefault').style.display = 'none';
  document.getElementById('measErr').style.display = 'none';

  const val = this.value;
  if      (val === 'Shalwar Kameez (Men)')   document.getElementById('meas-shalwar-men').style.display   = 'block';
  else if (val === 'Shalwar Kameez (Women)') document.getElementById('meas-shalwar-women').style.display = 'block';
  else if (val === 'Kurta')                  document.getElementById('meas-kurta').style.display          = 'block';
  else if (val === 'Suit / Pant Coat')       document.getElementById('meas-suit').style.display           = 'block';
  else if (val === 'Abaya')                  document.getElementById('meas-abaya').style.display          = 'block';
  else if (val === "Children's Dress")       document.getElementById('meas-children').style.display       = 'block';
  else if (val === 'Other')                  document.getElementById('meas-other').style.display          = 'block';
  else                                       document.getElementById('measDefault').style.display         = 'block';
});

function getMeasurements() {
  const garment = document.getElementById('garment').value;
  if (garment === 'Shalwar Kameez (Men)') {
    return { chest: document.getElementById('sm_chest').value, length: document.getElementById('sm_length').value, shoulder: document.getElementById('sm_shoulder').value, sleeve: document.getElementById('sm_sleeve').value, neck: document.getElementById('sm_neck').value, waist: document.getElementById('sm_waist').value, shalwar_length: document.getElementById('sm_shalwar_length').value, hip: document.getElementById('sm_hip').value, shalwar_waist: document.getElementById('sm_shalwar_waist').value, paincha: document.getElementById('sm_paincha').value };
  } else if (garment === 'Shalwar Kameez (Women)') {
    return { chest: document.getElementById('sw_chest').value, waist: document.getElementById('sw_waist').value, hip: document.getElementById('sw_hip').value, length: document.getElementById('sw_length').value, shoulder: document.getElementById('sw_shoulder').value, sleeve: document.getElementById('sw_sleeve').value, neck: document.getElementById('sw_neck').value, daman: document.getElementById('sw_daman').value, shalwar_length: document.getElementById('sw_shalwar_length').value, shalwar_hip: document.getElementById('sw_shalwar_hip').value, shalwar_waist: document.getElementById('sw_shalwar_waist').value, paincha: document.getElementById('sw_paincha').value };
  } else if (garment === 'Kurta') {
    return { chest: document.getElementById('k_chest').value, length: document.getElementById('k_length').value, shoulder: document.getElementById('k_shoulder').value, sleeve: document.getElementById('k_sleeve').value, neck: document.getElementById('k_neck').value, waist: document.getElementById('k_waist').value, daman: document.getElementById('k_daman').value };
  } else if (garment === 'Suit / Pant Coat') {
    return { chest: document.getElementById('s_chest').value, waist: document.getElementById('s_waist').value, shoulder: document.getElementById('s_shoulder').value, sleeve: document.getElementById('s_sleeve').value, coat_length: document.getElementById('s_coat_length').value, neck: document.getElementById('s_neck').value, pant_length: document.getElementById('s_pant_length').value, pant_waist: document.getElementById('s_pant_waist').value, hip: document.getElementById('s_hip').value, thigh: document.getElementById('s_thigh').value, paincha: document.getElementById('s_paincha').value };
  } else if (garment === 'Abaya') {
    return { length: document.getElementById('a_length').value, chest: document.getElementById('a_chest').value, waist: document.getElementById('a_waist').value, hip: document.getElementById('a_hip').value, shoulder: document.getElementById('a_shoulder').value, sleeve_length: document.getElementById('a_sleeve_length').value, sleeve_width: document.getElementById('a_sleeve_width').value, neck: document.getElementById('a_neck').value, daman: document.getElementById('a_daman').value };
  } else if (garment === "Children's Dress") {
    return { age: document.getElementById('c_age').value, chest: document.getElementById('c_chest').value, length: document.getElementById('c_length').value, shoulder: document.getElementById('c_shoulder').value, sleeve: document.getElementById('c_sleeve').value, waist: document.getElementById('c_waist').value };
  } else if (garment === 'Other') {
    return { chest: document.getElementById('o_chest').value, waist: document.getElementById('o_waist').value, length: document.getElementById('o_length').value, shoulder: document.getElementById('o_shoulder').value, sleeve: document.getElementById('o_sleeve').value, hip: document.getElementById('o_hip').value, notes: document.getElementById('o_notes').value };
  }
  return {};
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
      wrap.innerHTML = `<img src="${e.target.result}" style="width:72px;height:72px;object-fit:cover;border-radius:8px;border:2px solid #e0e0e0;"><button onclick="removeImage(${index})" title="Remove" style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:#dc3545;color:#fff;border:none;cursor:pointer;font-size:11px;display:flex;align-items:center;justify-content:center;padding:0;line-height:1;">&#x2715;</button>`;
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
  uploadedFiles = uploadedFiles.concat(Array.from(this.files));
  renderPreviews();
});

document.querySelector('label[for="designImages"]').addEventListener('mouseover', function() { this.style.borderColor = '#1B2A4A'; });
document.querySelector('label[for="designImages"]').addEventListener('mouseout',  function() { this.style.borderColor = '#e0e0e0'; });

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
  if (!deliveryChoice) {
    document.getElementById('deliveryChoiceErr').textContent = 'Please select a delivery option';
    ok = false;
  }

  const visibleMeasSection = document.querySelector('.meas-section[style*="block"]');
  const measErrEl = document.getElementById('measErr');
  if (visibleMeasSection) {
    const measInputs = visibleMeasSection.querySelectorAll('input[type="number"], input[type="text"]');
    let measOk = true;
    measInputs.forEach(input => {
      if (!input.value.trim()) {
        input.classList.add('is-invalid');
        measOk = false;
      } else {
        input.classList.remove('is-invalid');
      }
    });
    if (!measOk) {
      measErrEl.textContent = 'Please fill in all measurement fields marked with *';
      measErrEl.style.display = 'block';
      ok = false;
    } else {
      measErrEl.style.display = 'none';
      measErrEl.textContent = '';
    }
  }
  return ok;
}

document.getElementById('submitBtn').addEventListener('click', async () => {
  if (!validate()) {
    const firstError = document.querySelector('.is-invalid') || document.getElementById('measErr');
    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  const measurements = getMeasurements();
  const formData = new FormData();
  const tailorId = document.querySelector('meta[name="tailor-id"]').content;

  formData.append('tailor_id', tailorId);
  formData.append('recipient_name', document.getElementById('cname').value.trim());
  formData.append('recipient_phone', document.getElementById('cphone').value.trim());
  formData.append('recipient_address', document.getElementById('caddr').value.trim());
  formData.append('recipient_city', document.getElementById('ccity').value.trim());
  formData.append('customer_name', document.getElementById('cname').value.trim());
  formData.append('customer_phone', document.getElementById('cphone').value.trim());
  formData.append('customer_address', document.getElementById('caddr').value.trim());
  formData.append('customer_city', document.getElementById('ccity').value.trim());
  formData.append('dress_type', document.getElementById('garment').value);
  formData.append('fabric_name', document.getElementById('fabricName').value.trim());
  formData.append('fabric_color', document.getElementById('fabricColorText').value.trim());
  formData.append('fabric_provided_by', 'customer');
  formData.append('special_instructions', document.getElementById('notes').value.trim());
  formData.append('delivery_type', deliveryChoice === 'yes' ? 'home_delivery' : 'pickup');
  formData.append('measurement_method', 'manual');
  formData.append('chest', measurements.chest || '');
  formData.append('waist', measurements.waist || measurements.shalwar_waist || '');
  formData.append('hips', measurements.hip || measurements.shalwar_hip || '');
  formData.append('shoulder', measurements.shoulder || '');
  formData.append('sleeve_length', measurements.sleeve || measurements.sleeve_length || '');
  formData.append('shirt_length', measurements.length || measurements.coat_length || '');
  formData.append('trouser_length', measurements.shalwar_length || measurements.pant_length || '');
  formData.append('trouser_waist', measurements.pant_waist || measurements.shalwar_waist || '');
  formData.append('neck', measurements.neck || '');

  if (uploadedFiles.length > 0) {
    formData.append('design_image', uploadedFiles[0]);
  }

  const submitBtn = document.getElementById('submitBtn');
  submitBtn.disabled = true;
  submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> &nbsp;Placing Order...`;

  try {
    const response = await fetch('/order/store', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      body: formData
    });

    const result = await response.json();

    if (response.ok && result.success) {
      const deliveryLine = deliveryChoice === 'yes'
        ? `<p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Delivery:</strong> Delivery service requested</p>`
        : `<p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Delivery:</strong> Self pickup / drop-off</p>`;

      const customerName = document.getElementById('cname').value.trim();
      const customerPhone = document.getElementById('cphone').value.trim();
      const customerGarment = document.getElementById('garment').value;
      const customerFabricName = document.getElementById('fabricName').value.trim();
      const customerFabricColor = document.getElementById('fabricColorText').value.trim();   
      const formBody = document.querySelector('.form-body');
      formBody.innerHTML = '';
      const success = document.createElement('div');
      success.style.cssText = 'text-align:center; padding: 30px 10px;';
      success.innerHTML = `
        <div style="width:70px;height:70px;background:linear-gradient(135deg,#1B2A4A,#212529);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
          <i class="fas fa-check" style="color:#fff;font-size:28px;"></i>
        </div>
        <h3 style="color:#1B2A4A;font-weight:700;font-size:22px;margin-bottom:8px;">Order Submitted!</h3>
        <p style="color:#575a5b;font-size:14px;margin-bottom:4px;"><strong>Order Number:</strong> ${result.order_number}</p>
        <p style="color:#575a5b;font-size:14px;margin-bottom:20px;">Your order has been sent to the tailor. Payment will be unlocked after acceptance.</p>
        <div style="background:#f8f9fa;border-radius:12px;padding:16px;text-align:left;border:2px solid #e0e0e0;margin-bottom:14px;">
          <p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Name:</strong> ${customerName}</p>
          <p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Phone:</strong> ${customerPhone}</p>
          <p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Garment:</strong> ${customerGarment}</p>
          <p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Fabric:</strong> ${customerFabricName} &mdash; ${customerFabricColor}</p>
          ${deliveryLine}
        </div>
        <div style="background:#E6F1FB;border-radius:10px;padding:12px;border:1.5px solid #B5D4F4;margin-bottom:20px;">
          <p style="font-size:13px;color:#0C447C;margin:0;"><i class="fas fa-lock"></i> <strong>Payment Pending</strong> &mdash; Waiting for tailor to accept your order.</p>
        </div>
        <a href="/customer/dashboard" style="display:inline-block; text-decoration:none; background:linear-gradient(135deg,#1B2A4A,#212529);color:#fff;border:none;border-radius:10px;padding:12px 30px;font-size:15px;font-weight:600;cursor:pointer;letter-spacing:0.5px;">
          <i class="fas fa-arrow-left"></i> &nbsp;Back to Dashboard
        </a>
      `;
      formBody.appendChild(success);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
      alert('Error: ' + (result.message || 'Order submission failed.'));
      submitBtn.disabled = false;
      submitBtn.innerHTML = `<i class="fas fa-check"></i> &nbsp;Submit Order`;
    }
  } catch (error) {
    console.error('Submission Error:', error);
    alert('Connection failed. Please check your internet connection.');
    submitBtn.disabled = false;
    submitBtn.innerHTML = `<i class="fas fa-check"></i> &nbsp;Submit Order`;
  }
});