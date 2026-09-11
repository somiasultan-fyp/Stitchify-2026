<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Write a Review - Stitchify</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/review.css') }}">
</head>
<body>

<div class="review-wrapper">
    <div class="review-header">
        <h2><i class="fas fa-star me-2"></i>Write a Review</h2>
        <p>Share your experience with {{ $order->tailor->user->name }}</p>
    </div>

    <div class="order-summary">
        <h5 class="mb-3" style="color: var(--accent-color);">Order Details</h5>
        <div class="order-info-row">
            <span>Order Number:</span>
            <strong>#{{ $order->order_number }}</strong>
        </div>
        <div class="order-info-row">
            <span>Garment Type:</span>
            <strong>{{ $order->dress_type }}</strong>
        </div>
        <div class="order-info-row">
            <span>Order Date:</span>
            <strong>{{ $order->created_at->format('M d, Y') }}</strong>
        </div>
        <div class="order-info-row">
            <span>Delivered On:</span>
            <strong>{{ $order->actual_delivery_date ? $order->actual_delivery_date->format('M d, Y') : 'N/A' }}</strong>
        </div>
    </div>

    <div class="rating-section">
        <div class="tailor-info">
            @if($order->tailor->user->profile_image)
                <img src="{{ Storage::url($order->tailor->user->profile_image) }}" alt="{{ $order->tailor->user->name }}" class="tailor-avatar">
            @else
                <div class="tailor-avatar" style="background: var(--accent-color); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                    <i class="fas fa-user"></i>
                </div>
            @endif
            <div class="tailor-details">
                <h4>{{ $order->tailor->user->name }}</h4>
                <p>{{ $order->tailor->specialization ?? 'Professional Tailor' }}</p>
            </div>
        </div>

        <div id="successMessage" class="success-message">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Thank you!</strong> Your review has been submitted successfully.
        </div>

        <div id="errorMessage" class="error-message">
            <i class="fas fa-exclamation-circle me-2"></i>
            <span id="errorText">Something went wrong. Please try again.</span>
        </div>

        <form id="reviewForm">
            <div class="mb-4">
                <label class="section-title">Your Rating *</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required>
                    <label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                    
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                    
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                    
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                    
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                </div>
                <div id="ratingError" style="color: #dc3545; font-size: 13px; display: none;">
                    Please select a rating
                </div>
            </div>

            <div class="mb-4">
                <label class="section-title">Your Review *</label>
                <textarea 
                    id="reviewComment" 
                    class="review-textarea" 
                    placeholder="Tell us about your experience with the tailor. How was the quality, fit, and service?"
                    required
                ></textarea>
                <div id="commentError" style="color: #dc3545; font-size: 13px; display: none;">
                    Please write your review (minimum 10 characters)
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <i class="fas fa-paper-plane me-2"></i>Submit Review
            </button>

            <a href="/customer/dashboard" class="back-link">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/review.js') }}"></script>

</body>
</html>