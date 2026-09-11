<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="tailor-id" content="{{ $tailor->id }}">
<title>Order Form Stitchify</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/common.css') }}" rel="stylesheet">
<link href="{{ asset('css/order-form.css') }}" rel="stylesheet">
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

    <div class="form-row">
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
        <p><i class="fas fa-exclamation-triangle"></i> &nbsp;<strong>Please Note:</strong> A delivery service has been selected. Any extra delivery charges will be paid by the customer.</p>
      </div>
      <div class="note-block info" id="deliveryNoNote">
        <p><i class="fas fa-info-circle"></i> &nbsp;<strong>Self Pickup Selected:</strong> You have chosen to handle the pickup and drop-off yourself.</p>
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
      <label for="designImages" style="display:flex; align-items:center; gap:8px; margin-top:10px; padding:11px 15px; border:1.5px dashed #e0e0e0; border-radius:8px; background:#f8f9fa; cursor:pointer; transition:border-color 0.3s;">
        <i class="fas fa-image" style="color:#1B2A4A; font-size:18px;"></i>
        <span style="font-size:14px; color:#575a5b;" id="imageLabel">Upload design pictures (optional)</span>
      </label>
      <input type="file" id="designImages" accept="image/*" multiple style="display:none;">
    </div>

    <div class="section-divider"><span>Measurements (inches) *</span></div>

    <div id="measDefault" class="text-center py-3" style="color:#999;">
      <i class="fas fa-tshirt me-2"></i>Please select a garment type first
    </div>

    <div id="meas-shalwar-men" class="meas-section" style="display:none;">
      <h6 class="meas-heading">Kameez</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item"><label>Chest *</label><input id="sm_chest" class="form-control" type="number" placeholder="e.g. 40"></div>
        <div class="meas-item"><label>Length *</label><input id="sm_length" class="form-control" type="number" placeholder="e.g. 46"></div>
        <div class="meas-item"><label>Shoulder *</label><input id="sm_shoulder" class="form-control" type="number" placeholder="e.g. 18"></div>
        <div class="meas-item"><label>Sleeve *</label><input id="sm_sleeve" class="form-control" type="number" placeholder="e.g. 25"></div>
        <div class="meas-item"><label>Neck *</label><input id="sm_neck" class="form-control" type="number" placeholder="e.g. 15"></div>
        <div class="meas-item"><label>Waist *</label><input id="sm_waist" class="form-control" type="number" placeholder="e.g. 36"></div>
      </div>
      <h6 class="meas-heading">Shalwar</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item"><label>Shalwar Length *</label><input id="sm_shalwar_length" class="form-control" type="number" placeholder="e.g. 42"></div>
        <div class="meas-item"><label>Hip / Seat *</label><input id="sm_hip" class="form-control" type="number" placeholder="e.g. 42"></div>
        <div class="meas-item"><label>Waist (Shalwar) *</label><input id="sm_shalwar_waist" class="form-control" type="number" placeholder="e.g. 36"></div>
        <div class="meas-item"><label>Paincha (Bottom) *</label><input id="sm_paincha" class="form-control" type="number" placeholder="e.g. 14"></div>
      </div>
    </div>

    <div id="meas-shalwar-women" class="meas-section" style="display:none;">
      <h6 class="meas-heading">Kameez</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item"><label>Chest *</label><input id="sw_chest" class="form-control" type="number" placeholder="e.g. 38"></div>
        <div class="meas-item"><label>Waist *</label><input id="sw_waist" class="form-control" type="number" placeholder="e.g. 32"></div>
        <div class="meas-item"><label>Hip *</label><input id="sw_hip" class="form-control" type="number" placeholder="e.g. 40"></div>
        <div class="meas-item"><label>Length (Kameez) *</label><input id="sw_length" class="form-control" type="number" placeholder="e.g. 44"></div>
        <div class="meas-item"><label>Shoulder *</label><input id="sw_shoulder" class="form-control" type="number" placeholder="e.g. 14"></div>
        <div class="meas-item"><label>Sleeve *</label><input id="sw_sleeve" class="form-control" type="number" placeholder="e.g. 22"></div>
        <div class="meas-item"><label>Neck *</label><input id="sw_neck" class="form-control" type="number" placeholder="e.g. 14"></div>
        <div class="meas-item"><label>Daman (Hem) *</label><input id="sw_daman" class="form-control" type="number" placeholder="e.g. 56"></div>
      </div>
      <h6 class="meas-heading">Shalwar / Trouser</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item"><label>Shalwar Length *</label><input id="sw_shalwar_length" class="form-control" type="number" placeholder="e.g. 40"></div>
        <div class="meas-item"><label>Hip / Seat *</label><input id="sw_shalwar_hip" class="form-control" type="number" placeholder="e.g. 40"></div>
        <div class="meas-item"><label>Waist (Shalwar) *</label><input id="sw_shalwar_waist" class="form-control" type="number" placeholder="e.g. 32"></div>
        <div class="meas-item"><label>Paincha *</label><input id="sw_paincha" class="form-control" type="number" placeholder="e.g. 12"></div>
      </div>
    </div>

    <div id="meas-kurta" class="meas-section" style="display:none;">
      <div class="meas-grid mb-3">
        <div class="meas-item"><label>Chest *</label><input id="k_chest" class="form-control" type="number" placeholder="e.g. 40"></div>
        <div class="meas-item"><label>Length *</label><input id="k_length" class="form-control" type="number" placeholder="e.g. 42"></div>
        <div class="meas-item"><label>Shoulder *</label><input id="k_shoulder" class="form-control" type="number" placeholder="e.g. 17"></div>
        <div class="meas-item"><label>Sleeve *</label><input id="k_sleeve" class="form-control" type="number" placeholder="e.g. 24"></div>
        <div class="meas-item"><label>Neck *</label><input id="k_neck" class="form-control" type="number" placeholder="e.g. 15"></div>
        <div class="meas-item"><label>Waist *</label><input id="k_waist" class="form-control" type="number" placeholder="e.g. 38"></div>
        <div class="meas-item"><label>Daman *</label><input id="k_daman" class="form-control" type="number" placeholder="e.g. 52"></div>
      </div>
    </div>

    <div id="meas-suit" class="meas-section" style="display:none;">
      <h6 class="meas-heading">Coat / Blazer</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item"><label>Chest *</label><input id="s_chest" class="form-control" type="number" placeholder="e.g. 42"></div>
        <div class="meas-item"><label>Waist *</label><input id="s_waist" class="form-control" type="number" placeholder="e.g. 38"></div>
        <div class="meas-item"><label>Shoulder *</label><input id="s_shoulder" class="form-control" type="number" placeholder="e.g. 18"></div>
        <div class="meas-item"><label>Sleeve *</label><input id="s_sleeve" class="form-control" type="number" placeholder="e.g. 25"></div>
        <div class="meas-item"><label>Coat Length *</label><input id="s_coat_length" class="form-control" type="number" placeholder="e.g. 30"></div>
        <div class="meas-item"><label>Neck *</label><input id="s_neck" class="form-control" type="number" placeholder="e.g. 15"></div>
      </div>
      <h6 class="meas-heading">Pant / Trouser</h6>
      <div class="meas-grid mb-3">
        <div class="meas-item"><label>Pant Length *</label><input id="s_pant_length" class="form-control" type="number" placeholder="e.g. 42"></div>
        <div class="meas-item"><label>Waist (Pant) *</label><input id="s_pant_waist" class="form-control" type="number" placeholder="e.g. 36"></div>
        <div class="meas-item"><label>Hip / Seat *</label><input id="s_hip" class="form-control" type="number" placeholder="e.g. 42"></div>
        <div class="meas-item"><label>Thigh *</label><input id="s_thigh" class="form-control" type="number" placeholder="e.g. 24"></div>
        <div class="meas-item"><label>Bottom (Paincha) *</label><input id="s_paincha" class="form-control" type="number" placeholder="e.g. 16"></div>
      </div>
    </div>

    <div id="meas-abaya" class="meas-section" style="display:none;">
      <div class="meas-grid mb-3">
        <div class="meas-item"><label>Total Length *</label><input id="a_length" class="form-control" type="number" placeholder="e.g. 56"></div>
        <div class="meas-item"><label>Chest *</label><input id="a_chest" class="form-control" type="number" placeholder="e.g. 40"></div>
        <div class="meas-item"><label>Waist *</label><input id="a_waist" class="form-control" type="number" placeholder="e.g. 36"></div>
        <div class="meas-item"><label>Hip *</label><input id="a_hip" class="form-control" type="number" placeholder="e.g. 44"></div>
        <div class="meas-item"><label>Shoulder *</label><input id="a_shoulder" class="form-control" type="number" placeholder="e.g. 15"></div>
        <div class="meas-item"><label>Sleeve Length *</label><input id="a_sleeve_length" class="form-control" type="number" placeholder="e.g. 24"></div>
        <div class="meas-item"><label>Sleeve Width *</label><input id="a_sleeve_width" class="form-control" type="number" placeholder="e.g. 14"></div>
        <div class="meas-item"><label>Neck *</label><input id="a_neck" class="form-control" type="number" placeholder="e.g. 14"></div>
        <div class="meas-item"><label>Daman (Bottom Width) *</label><input id="a_daman" class="form-control" type="number" placeholder="e.g. 64"></div>
      </div>
    </div>

    <div id="meas-children" class="meas-section" style="display:none;">
      <div class="meas-grid mb-3">
        <div class="meas-item"><label>Age / Size *</label><input id="c_age" class="form-control" type="text" placeholder="e.g. 5 years / 20"></div>
        <div class="meas-item"><label>Chest *</label><input id="c_chest" class="form-control" type="number" placeholder="e.g. 26"></div>
        <div class="meas-item"><label>Length *</label><input id="c_length" class="form-control" type="number" placeholder="e.g. 28"></div>
        <div class="meas-item"><label>Shoulder *</label><input id="c_shoulder" class="form-control" type="number" placeholder="e.g. 11"></div>
        <div class="meas-item"><label>Sleeve *</label><input id="c_sleeve" class="form-control" type="number" placeholder="e.g. 14"></div>
        <div class="meas-item"><label>Waist *</label><input id="c_waist" class="form-control" type="number" placeholder="e.g. 24"></div>
      </div>
    </div>

    <div id="meas-other" class="meas-section" style="display:none;">
      <div class="meas-grid mb-3">
        <div class="meas-item"><label>Chest *</label><input id="o_chest" class="form-control" type="number" placeholder="e.g. 40"></div>
        <div class="meas-item"><label>Waist *</label><input id="o_waist" class="form-control" type="number" placeholder="e.g. 36"></div>
        <div class="meas-item"><label>Length *</label><input id="o_length" class="form-control" type="number" placeholder="e.g. 44"></div>
        <div class="meas-item"><label>Shoulder *</label><input id="o_shoulder" class="form-control" type="number" placeholder="e.g. 17"></div>
        <div class="meas-item"><label>Sleeve *</label><input id="o_sleeve" class="form-control" type="number" placeholder="e.g. 24"></div>
        <div class="meas-item"><label>Hip *</label><input id="o_hip" class="form-control" type="number" placeholder="e.g. 40"></div>
      </div>
      <div class="mb-3">
        <label class="form-label">Additional Notes for Measurements</label>
        <textarea id="o_notes" class="form-control" rows="2" placeholder="Any special requirements regarding measurements..."></textarea>
      </div>
    </div>

    <span class="error-text" id="measErr" style="text-align: center; display: none; margin-bottom: 10px; font-weight: 600;"></span>

    <div class="section-divider"><span>Fabric Details</span></div>

    <div class="form-row">
      <div class="mb-3">
        <label class="form-label">Fabric Name *</label>
        <input id="fabricName" class="form-control" placeholder="e.g. Lawn, Khaddar, Silk" type="text">
        <span class="error-text" id="fabricNameErr"></span>
      </div>
      <div class="mb-3">
        <label class="form-label">Fabric Color *</label>
        <input id="fabricColorText" class="form-control" placeholder="e.g. Navy Blue, Off White" type="text">
        <span class="error-text" id="fabricColorErr"></span>
      </div>
    </div>

    <div class="section-divider"><span>Payment</span></div>

    <div class="info-block mb-3">
      <p><strong>Payment details</strong> will be available after order accepted. <strong>Stripe</strong> is available for this.</p>
    </div>

    <div class="pay-locked-block">
      <div class="lock-icon"><i class="fas fa-lock"></i></div>
      <p><strong>Payment Locked</strong><br>You can make the payment after the tailor accepts the order.</p>
    </div>

    <button class="btn-custom" id="submitBtn" type="button">
      <i class="fas fa-check"></i> &nbsp;Submit Order
    </button>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/order-form.js') }}"></script>
</body>
</html>