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
.logo-container h2 {
  color:#fff; font-size:22px; font-weight:700; margin-bottom:4px; }
.logo-container p {
  color:rgba(255,255,255,0.65); font-size:13px; }
.logo-image {
  width:120px; height:120px; margin:0 auto; display:block; object-fit:contain; border-radius:50%; padding:10px; box-shadow:0 5px 15px rgba(0,0,0,0.3); }
.form-body {
  padding: 36px 40px 30px; }
.registration-header {
  text-align:center; margin-bottom:24px; }
.registration-header h2 {
  color:var(--accent-color); font-weight:700; font-size:26px; margin-bottom:6px; }
.registration-header p {
  color:var(--copyright-bg); font-size:14px; }
.form-label {
  color:var(--primary-bg); font-weight:600; margin-bottom:8px; font-size:14px; }
.form-control, .form-select {
  border:2px solid #e0e0e0; border-radius:10px; padding:12px 15px;
  color:var(--primary-bg); background-color:#f8f9fa;
  transition:all 0.3s ease; font-size:15px;
}
.form-control:focus, .form-select:focus {
  border-color:var(--accent-color);
  box-shadow:0 0 0 0.25rem rgba(14,24,48,0.15);
  background-color:var(--text-white);
}
.form-control.is-invalid, .form-select.is-invalid {
  border-color:#dc3545; }
.section-divider {
  display:flex; align-items:center; gap:10px; margin:20px 0 16px; }
.section-divider span {
  font-size:12px; font-weight:700; color:var(--accent-color); letter-spacing:0.8px; text-transform:uppercase; white-space:nowrap; }
.section-divider::before, .section-divider::after {
  content:''; flex:1; height:1px; background:#e0e0e0; }
.meas-heading {
  color: #1b2a4a;
  font-weight: 700;
  margin-bottom: 12px;
  padding: 6px 12px;
  background: #f0f3f8;
  border-radius: 6px;
  border-left: 3px solid #1b2a4a;
}
.meas-grid {
  display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:4px; }
.meas-item label {
  font-size:13px; font-weight:600; color:var(--primary-bg); margin-bottom:6px; display:block; }
.meas-item input {
  font-size:14px; }
.pay-locked-block {
   border:2px dashed #e0e0e0; border-radius:12px; padding:20px; text-align:center; background:#f8f9fa; margin-top:6px; }
.pay-locked-block .lock-icon { font-size:28px; color:var(--copyright-bg); }
.pay-locked-block p {
   color:var(--copyright-bg); font-size:13px; margin-top:8px; line-height:1.5; }
.info-block {
  border-radius:10px; padding:14px 16px; background:#E6F1FB; border:1.5px solid #B5D4F4; margin-top:6px; }
.info-block p { font-size:13px; color:#0C447C; line-height:1.6; }
.info-block strong {
  color:#042C53; }
.btn-custom {
  background:linear-gradient(135deg, var(--accent-color), var(--primary-bg)); color:var(--text-white); border:none; border-radius:10px; padding:14px; font-weight:600; font-size:16px; width:100%; transition:all 0.3s ease; margin-top:10px; text-transform:uppercase; letter-spacing:0.5px; cursor:pointer; }
.btn-custom:hover {
  transform:translateY(-2px); box-shadow:0 8px 20px rgba(14,24,48,0.4); }
.error-text {
  color:#dc3545; font-size:0.82rem; margin-top:5px; display:block; min-height:16px; }
.requirement-text {
  font-size:0.8rem; color:var(--copyright-bg); margin-top:5px; line-height:1.4; }
.row2 {
  display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.toggle-box {
  border-radius: 12px;
  border: 2px solid #e0e0e0;
  background: #f8f9fa;
  padding: 16px 18px;
  margin-bottom: 4px; }
.toggle-box-label {
  font-size: 14px;
  font-weight: 600;
  color: var(--primary-bg);
  margin-bottom: 12px;
  display: block; }
.toggle-options {
  display: flex;
  gap: 10px; }
.toggle-option {
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
.toggle-option:hover { border-color: var(--accent-color); }
.toggle-option.selected { border-color: #1B2A4A; background: #1B2A4A; color: #fff; }
.check-circle {
  width: 18px; height: 18px;
  border-radius: 50%;
  border: 2px solid #ccc;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  transition: all 0.2s;
  background: #fff;
}
.toggle-option.selected .check-circle { border-color: #fff; background: #fff; }
.check-circle i { font-size: 10px; color: #1B2A4A; display: none; }
.toggle-option.selected .check-circle i { display: block; }
.note-block {
  border-radius: 10px;
  padding: 13px 16px;
  margin-top: 12px;
  display: none;
}
.note-block.warning {
  background: #FFF8E6;
  border: 1.5px solid #F5C842;
}
.note-block.warning p { font-size: 13px; color: #7A5800; margin: 0; line-height: 1.6; }
.note-block.info {
  background: #E6F1FB;
  border: 1.5px solid #B5D4F4;
}
.note-block.info p { font-size: 13px; color: #0C447C; margin: 0; line-height: 1.6; }
</style>
</head>
<body>

<div class="registration-wrapper">
  <div class="logo-container">
    <img src="{{ asset('images/logo.png') }}" alt="Stitchify" class="logo-image" onerror="this.style.display='none'">
    <h2>Stitchify</h2>
    <p>Place your order below</p>
  </div>

  <div class="form-body">
    <div class="registration-header">
      <h2>New Order</h2>
      <p>Fill in all details to place your order</p>
    </div>

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

    <div class="section-divider"><span>Delivery Method</span></div>

    <div class="toggle-box mb-3">
      <span class="toggle-box-label">
        <i class="fas fa-truck" style="color:#1B2A4A; font-size:14px;"></i>
        &nbsp;Would you like to use our delivery service? *
      </span>
      <div class="toggle-options">
        <div class="toggle-option" id="deliveryYes" onclick="selectDelivery('yes')">
          <div class="check-circle" id="circleDeliveryYes"><i class="fas fa-check"></i></div>
          <span>Yes, use delivery</span>
        </div>
        <div class="toggle-option" id="deliveryNo" onclick="selectDelivery('no')">
          <div class="check-circle" id="circleDeliveryNo"><i class="fas fa-check"></i></div>
          <span>No, self pickup</span>
        </div>
      </div>
      <span class="error-text" id="deliveryChoiceErr"></span>

      <div class="note-block warning" id="deliveryYesNote">
        <p>
          <i class="fas fa-exclamation-triangle"></i>
          &nbsp;<strong>Please Note:</strong> A delivery service has been selected.
          Any extra delivery charges will be paid by the customer and will be communicated before dispatch.
        </p>
      </div>

      <div class="note-block info" id="deliveryNoNote">
        <p>
          <i class="fas fa-info-circle"></i>
          &nbsp;<strong>Self Pickup Selected:</strong> You have chosen to handle the pickup and drop-off yourself.
          Please note that the responsibility of bringing the fabric to the tailor and collecting the finished garment lies entirely with you.
        </p>
      </div>
    </div>

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
      <textarea id="notes" class="form-control" rows="2" placeholder="Design pictures, collar style, pocket type, embroidery detail, etc."></textarea>
      <div id="imagePreviewBox" style="display:none; margin-top:10px; flex-wrap:wrap; gap:8px;"></div>
      <label for="designImages" style="display:flex; align-items:center; gap:8px; margin-top:10px; padding:11px 15px; border:2px dashed #e0e0e0; border-radius:10px; background:#f8f9fa; cursor:pointer; transition:border-color 0.3s;">
        <i class="fas fa-image" style="color:#1B2A4A; font-size:18px;"></i>
        <span style="font-size:14px; color:#575a5b;" id="imageLabel">Upload design pictures (optional)</span>
      </label>
      <input type="file" id="designImages" accept="image/*" multiple style="display:none;">
    </div>

    <div class="section-divider"><span>Measurements (inches)</span></div>

    <!-- Default message -->
    <div id="measDefault" class="text-center py-3" style="color:#999;">
      <i class="fas fa-tshirt me-2"></i>Please select a garment type first
    </div>

    <!-- Shalwar Kameez Men -->
    <div id="meas-shalwar-men" class="meas-section" style="display:none;">
      <h6 class="meas-heading">Kameez</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Chest</label>
          <input id="sm_chest" class="form-control" type="number" placeholder="e.g. 40">
        </div>
        <div class="meas-item">
          <label>Length</label>
          <input id="sm_length" class="form-control" type="number" placeholder="e.g. 46">
        </div>
        <div class="meas-item">
          <label>Shoulder</label>
          <input id="sm_shoulder" class="form-control" type="number" placeholder="e.g. 18">
        </div>
        <div class="meas-item">
          <label>Sleeve</label>
          <input id="sm_sleeve" class="form-control" type="number" placeholder="e.g. 25">
        </div>
        <div class="meas-item">
          <label>Neck</label>
          <input id="sm_neck" class="form-control" type="number" placeholder="e.g. 15">
        </div>
        <div class="meas-item">
          <label>Waist</label>
          <input id="sm_waist" class="form-control" type="number" placeholder="e.g. 36">
        </div>
      </div>
      <h6 class="meas-heading">Shalwar</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Shalwar Length</label>
          <input id="sm_shalwar_length" class="form-control" type="number" placeholder="e.g. 42">
        </div>
        <div class="meas-item">
          <label>Hip / Seat</label>
          <input id="sm_hip" class="form-control" type="number" placeholder="e.g. 42">
        </div>
        <div class="meas-item">
          <label>Waist (Shalwar)</label>
          <input id="sm_shalwar_waist" class="form-control" type="number" placeholder="e.g. 36">
        </div>
        <div class="meas-item">
          <label>Paincha (Bottom)</label>
          <input id="sm_paincha" class="form-control" type="number" placeholder="e.g. 14">
        </div>
      </div>
    </div>

    <!-- Shalwar Kameez Women -->
    <div id="meas-shalwar-women" class="meas-section" style="display:none;">
      <h6 class="meas-heading">Kameez</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Chest</label>
          <input id="sw_chest" class="form-control" type="number" placeholder="e.g. 38">
        </div>
        <div class="meas-item">
          <label>Waist</label>
          <input id="sw_waist" class="form-control" type="number" placeholder="e.g. 32">
        </div>
        <div class="meas-item">
          <label>Hip</label>
          <input id="sw_hip" class="form-control" type="number" placeholder="e.g. 40">
        </div>
        <div class="meas-item">
          <label>Length (Kameez)</label>
          <input id="sw_length" class="form-control" type="number" placeholder="e.g. 44">
        </div>
        <div class="meas-item">
          <label>Shoulder</label>
          <input id="sw_shoulder" class="form-control" type="number" placeholder="e.g. 14">
        </div>
        <div class="meas-item">
          <label>Sleeve</label>
          <input id="sw_sleeve" class="form-control" type="number" placeholder="e.g. 22">
        </div>
        <div class="meas-item">
          <label>Neck</label>
          <input id="sw_neck" class="form-control" type="number" placeholder="e.g. 14">
        </div>
        <div class="meas-item">
          <label>Daman (Hem)</label>
          <input id="sw_daman" class="form-control" type="number" placeholder="e.g. 56">
        </div>
      </div>
      <h6 class="meas-heading">Shalwar / Trouser</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Shalwar Length</label>
          <input id="sw_shalwar_length" class="form-control" type="number" placeholder="e.g. 40">
        </div>
        <div class="meas-item">
          <label>Hip / Seat</label>
          <input id="sw_shalwar_hip" class="form-control" type="number" placeholder="e.g. 40">
        </div>
        <div class="meas-item">
          <label>Waist (Shalwar)</label>
          <input id="sw_shalwar_waist" class="form-control" type="number" placeholder="e.g. 32">
        </div>
        <div class="meas-item">
          <label>Paincha</label>
          <input id="sw_paincha" class="form-control" type="number" placeholder="e.g. 12">
        </div>
      </div>
    </div>

    <!-- Kurta -->
    <div id="meas-kurta" class="meas-section" style="display:none;">
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Chest</label>
          <input id="k_chest" class="form-control" type="number" placeholder="e.g. 40">
        </div>
        <div class="meas-item">
          <label>Length</label>
          <input id="k_length" class="form-control" type="number" placeholder="e.g. 42">
        </div>
        <div class="meas-item">
          <label>Shoulder</label>
          <input id="k_shoulder" class="form-control" type="number" placeholder="e.g. 17">
        </div>
        <div class="meas-item">
          <label>Sleeve</label>
          <input id="k_sleeve" class="form-control" type="number" placeholder="e.g. 24">
        </div>
        <div class="meas-item">
          <label>Neck</label>
          <input id="k_neck" class="form-control" type="number" placeholder="e.g. 15">
        </div>
        <div class="meas-item">
          <label>Waist</label>
          <input id="k_waist" class="form-control" type="number" placeholder="e.g. 38">
        </div>
        <div class="meas-item">
          <label>Daman</label>
          <input id="k_daman" class="form-control" type="number" placeholder="e.g. 52">
        </div>
      </div>
    </div>

    <!-- Suit / Pant Coat -->
    <div id="meas-suit" class="meas-section" style="display:none;">
      <h6 class="meas-heading">Coat / Blazer</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Chest</label>
          <input id="s_chest" class="form-control" type="number" placeholder="e.g. 42">
        </div>
        <div class="meas-item">
          <label>Waist</label>
          <input id="s_waist" class="form-control" type="number" placeholder="e.g. 38">
        </div>
        <div class="meas-item">
          <label>Shoulder</label>
          <input id="s_shoulder" class="form-control" type="number" placeholder="e.g. 18">
        </div>
        <div class="meas-item">
          <label>Sleeve</label>
          <input id="s_sleeve" class="form-control" type="number" placeholder="e.g. 25">
        </div>
        <div class="meas-item">
          <label>Coat Length</label>
          <input id="s_coat_length" class="form-control" type="number" placeholder="e.g. 30">
        </div>
        <div class="meas-item">
          <label>Neck</label>
          <input id="s_neck" class="form-control" type="number" placeholder="e.g. 15">
        </div>
      </div>
      <h6 class="meas-heading">Pant / Trouser</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Pant Length</label>
          <input id="s_pant_length" class="form-control" type="number" placeholder="e.g. 42">
        </div>
        <div class="meas-item">
          <label>Waist (Pant)</label>
          <input id="s_pant_waist" class="form-control" type="number" placeholder="e.g. 36">
        </div>
        <div class="meas-item">
          <label>Hip / Seat</label>
          <input id="s_hip" class="form-control" type="number" placeholder="e.g. 42">
        </div>
        <div class="meas-item">
          <label>Thigh</label>
          <input id="s_thigh" class="form-control" type="number" placeholder="e.g. 24">
        </div>
        <div class="meas-item">
          <label>Bottom (Paincha)</label>
          <input id="s_paincha" class="form-control" type="number" placeholder="e.g. 16">
        </div>
      </div>
    </div>

    <!-- Abaya -->
    <div id="meas-abaya" class="meas-section" style="display:none;">
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Total Length</label>
          <input id="a_length" class="form-control" type="number" placeholder="e.g. 56">
        </div>
        <div class="meas-item">
          <label>Chest</label>
          <input id="a_chest" class="form-control" type="number" placeholder="e.g. 40">
        </div>
        <div class="meas-item">
          <label>Waist</label>
          <input id="a_waist" class="form-control" type="number" placeholder="e.g. 36">
        </div>
        <div class="meas-item">
          <label>Hip</label>
          <input id="a_hip" class="form-control" type="number" placeholder="e.g. 44">
        </div>
        <div class="meas-item">
          <label>Shoulder</label>
          <input id="a_shoulder" class="form-control" type="number" placeholder="e.g. 15">
        </div>
        <div class="meas-item">
          <label>Sleeve Length</label>
          <input id="a_sleeve_length" class="form-control" type="number" placeholder="e.g. 24">
        </div>
        <div class="meas-item">
          <label>Sleeve Width</label>
          <input id="a_sleeve_width" class="form-control" type="number" placeholder="e.g. 14">
        </div>
        <div class="meas-item">
          <label>Neck</label>
          <input id="a_neck" class="form-control" type="number" placeholder="e.g. 14">
        </div>
        <div class="meas-item">
          <label>Daman (Bottom Width)</label>
          <input id="a_daman" class="form-control" type="number" placeholder="e.g. 64">
        </div>
      </div>
    </div>

    <!-- Children's Dress -->
    <div id="meas-children" class="meas-section" style="display:none;">
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Age / Size</label>
          <input id="c_age" class="form-control" type="text" placeholder="e.g. 5 years / 20">
        </div>
        <div class="meas-item">
          <label>Chest</label>
          <input id="c_chest" class="form-control" type="number" placeholder="e.g. 26">
        </div>
        <div class="meas-item">
          <label>Length</label>
          <input id="c_length" class="form-control" type="number" placeholder="e.g. 28">
        </div>
        <div class="meas-item">
          <label>Shoulder</label>
          <input id="c_shoulder" class="form-control" type="number" placeholder="e.g. 11">
        </div>
        <div class="meas-item">
          <label>Sleeve</label>
          <input id="c_sleeve" class="form-control" type="number" placeholder="e.g. 14">
        </div>
        <div class="meas-item">
          <label>Waist</label>
          <input id="c_waist" class="form-control" type="number" placeholder="e.g. 24">
        </div>
      </div>
    </div>

    <!-- Other -->
    <div id="meas-other" class="meas-section" style="display:none;">
      <div class="meas-grid mb-3">
        <div class="meas-item">
          <label>Chest</label>
          <input id="o_chest" class="form-control" type="number" placeholder="e.g. 40">
        </div>
        <div class="meas-item">
          <label>Waist</label>
          <input id="o_waist" class="form-control" type="number" placeholder="e.g. 36">
        </div>
        <div class="meas-item">
          <label>Length</label>
          <input id="o_length" class="form-control" type="number" placeholder="e.g. 44">
        </div>
        <div class="meas-item">
          <label>Shoulder</label>
          <input id="o_shoulder" class="form-control" type="number" placeholder="e.g. 17">
        </div>
        <div class="meas-item">
          <label>Sleeve</label>
          <input id="o_sleeve" class="form-control" type="number" placeholder="e.g. 24">
        </div>
        <div class="meas-item">
          <label>Hip</label>
          <input id="o_hip" class="form-control" type="number" placeholder="e.g. 40">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Additional Notes for Measurements</label>
        <textarea id="o_notes" class="form-control" rows="2"
          placeholder="Any special requirements regarding measurements..."></textarea>
      </div>
    </div>

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
      <span class="requirement-text">Type the name of Color</span>
    </div>

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
      return {
        chest:          document.getElementById('sm_chest').value,
        length:         document.getElementById('sm_length').value,
        shoulder:       document.getElementById('sm_shoulder').value,
        sleeve:         document.getElementById('sm_sleeve').value,
        neck:           document.getElementById('sm_neck').value,
        waist:          document.getElementById('sm_waist').value,
        shalwar_length: document.getElementById('sm_shalwar_length').value,
        hip:            document.getElementById('sm_hip').value,
        shalwar_waist:  document.getElementById('sm_shalwar_waist').value,
        paincha:        document.getElementById('sm_paincha').value,
      };
    } else if (garment === 'Shalwar Kameez (Women)') {
      return {
        chest:          document.getElementById('sw_chest').value,
        waist:          document.getElementById('sw_waist').value,
        hip:            document.getElementById('sw_hip').value,
        length:         document.getElementById('sw_length').value,
        shoulder:       document.getElementById('sw_shoulder').value,
        sleeve:         document.getElementById('sw_sleeve').value,
        neck:           document.getElementById('sw_neck').value,
        daman:          document.getElementById('sw_daman').value,
        shalwar_length: document.getElementById('sw_shalwar_length').value,
        shalwar_hip:    document.getElementById('sw_shalwar_hip').value,
        shalwar_waist:  document.getElementById('sw_shalwar_waist').value,
        paincha:        document.getElementById('sw_paincha').value,
      };
    } else if (garment === 'Kurta') {
      return {
        chest:    document.getElementById('k_chest').value,
        length:   document.getElementById('k_length').value,
        shoulder: document.getElementById('k_shoulder').value,
        sleeve:   document.getElementById('k_sleeve').value,
        neck:     document.getElementById('k_neck').value,
        waist:    document.getElementById('k_waist').value,
        daman:    document.getElementById('k_daman').value,
      };
    } else if (garment === 'Suit / Pant Coat') {
      return {
        chest:       document.getElementById('s_chest').value,
        waist:       document.getElementById('s_waist').value,
        shoulder:    document.getElementById('s_shoulder').value,
        sleeve:      document.getElementById('s_sleeve').value,
        coat_length: document.getElementById('s_coat_length').value,
        neck:        document.getElementById('s_neck').value,
        pant_length: document.getElementById('s_pant_length').value,
        pant_waist:  document.getElementById('s_pant_waist').value,
        hip:         document.getElementById('s_hip').value,
        thigh:       document.getElementById('s_thigh').value,
        paincha:     document.getElementById('s_paincha').value,
      };
    } else if (garment === 'Abaya') {
      return {
        length:       document.getElementById('a_length').value,
        chest:        document.getElementById('a_chest').value,
        waist:        document.getElementById('a_waist').value,
        hip:          document.getElementById('a_hip').value,
        shoulder:     document.getElementById('a_shoulder').value,
        sleeve_length:document.getElementById('a_sleeve_length').value,
        sleeve_width: document.getElementById('a_sleeve_width').value,
        neck:         document.getElementById('a_neck').value,
        daman:        document.getElementById('a_daman').value,
      };
    } else if (garment === "Children's Dress") {
      return {
        age:      document.getElementById('c_age').value,
        chest:    document.getElementById('c_chest').value,
        length:   document.getElementById('c_length').value,
        shoulder: document.getElementById('c_shoulder').value,
        sleeve:   document.getElementById('c_sleeve').value,
        waist:    document.getElementById('c_waist').value,
      };
    } else if (garment === 'Other') {
      return {
        chest:    document.getElementById('o_chest').value,
        waist:    document.getElementById('o_waist').value,
        length:   document.getElementById('o_length').value,
        shoulder: document.getElementById('o_shoulder').value,
        sleeve:   document.getElementById('o_sleeve').value,
        hip:      document.getElementById('o_hip').value,
        notes:    document.getElementById('o_notes').value,
      };
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
        wrap.innerHTML = `
          <img src="${e.target.result}" style="width:72px;height:72px;object-fit:cover;border-radius:8px;border:2px solid #e0e0e0;">
          <button onclick="removeImage(${index})" title="Remove" style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:#dc3545;color:#fff;border:none;cursor:pointer;font-size:11px;display:flex;align-items:center;justify-content:center;padding:0;line-height:1;">&#x2715;</button>
        `;
        previewBox.appendChild(wrap);
      };
      reader.readAsDataURL(file);
    });

    label.textContent = uploadedFiles.length > 0
      ? uploadedFiles.length + ' picture(s) selected'
      : 'Upload design pictures (optional)';
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

    if (!deliveryChoice) {
      document.getElementById('deliveryChoiceErr').textContent = 'Please select a delivery option';
      ok = false;
    }

    return ok;
  }

  document.getElementById('submitBtn').addEventListener('click', async () => {
    if (!validate()) {
      const firstError = document.querySelector('.is-invalid');
      if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    const measurements = getMeasurements();

    const data = {
      cname:                document.getElementById('cname').value.trim(),
      cphone:               document.getElementById('cphone').value.trim(),
      caddr:                document.getElementById('caddr').value.trim(),
      ccity:                document.getElementById('ccity').value.trim(),
      dress_type:           document.getElementById('garment').value,
      special_instructions: document.getElementById('notes').value.trim(),
      delivery_type:        deliveryChoice === 'yes' ? 'home_delivery' : 'self_pickup',
      measurement_method:   'manual',
      measurements:         measurements,   // ← Dynamic measurements object
      fabric_name:          document.getElementById('fabricName').value.trim(),
      fabric_color:         document.getElementById('fabricColorText').value.trim(),
    };

    console.log('Sending Request Payload:', data);

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> &nbsp;Placing Order...`;

    try {
      const response = await fetch('/order/store', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();

      if (response.ok && result.success) {
        const deliveryLine = deliveryChoice === 'yes'
          ? `<p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Delivery:</strong> Delivery service requested</p>`
          : `<p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Delivery:</strong> Self pickup / drop-off</p>`;

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
            <p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Name:</strong> ${data.cname}</p>
            <p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Phone:</strong> ${data.cphone}</p>
            <p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Garment:</strong> ${data.dress_type}</p>
            <p style="font-size:13px;color:#212529;margin-bottom:6px;"><strong>Fabric:</strong> ${data.fabric_name} &mdash; ${data.fabric_color}</p>
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
        alert('Error: ' + (result.message || 'Order save nahi ho saka.'));
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<i class="fas fa-check"></i> &nbsp;Submit Order`;
      }

    } catch (error) {
      console.error('Submission Error:', error);
      alert('Backend server se connection fail ho gaya. Logs check karein.');
      submitBtn.disabled = false;
      submitBtn.innerHTML = `<i class="fas fa-check"></i> &nbsp;Submit Order`;
    }
  });
</script>
</body>
</html>