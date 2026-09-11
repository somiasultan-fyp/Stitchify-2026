document.addEventListener('DOMContentLoaded', function() {
    const reviewForm = document.getElementById('reviewForm');
    
    if (!reviewForm) return;

    reviewForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const rating = document.querySelector('input[name="rating"]:checked');
        const comment = document.getElementById('reviewComment').value.trim();
        const submitBtn = document.getElementById('submitBtn');
        const successMsg = document.getElementById('successMessage');
        const errorMsg = document.getElementById('errorMessage');
        const ratingError = document.getElementById('ratingError');
        const commentError = document.getElementById('commentError');

        successMsg.style.display = 'none';
        errorMsg.style.display = 'none';
        ratingError.style.display = 'none';
        commentError.style.display = 'none';

        let hasError = false;
        
        if (!rating) {
            ratingError.style.display = 'block';
            hasError = true;
        }
        
        if (comment.length < 10) {
            commentError.style.display = 'block';
            hasError = true;
        }

        if (hasError) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';

        try {
            const orderId = reviewForm.dataset.orderId;
            const tailorId = reviewForm.dataset.tailorId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(`/customer/review/${orderId}/store`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    rating: parseInt(rating.value),
                    comment: comment,
                    tailor_id: tailorId,
                    order_id: orderId
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                successMsg.style.display = 'block';
                reviewForm.style.display = 'none';
                
                setTimeout(() => {
                    window.location.href = '/customer/dashboard';
                }, 2000);
            } else {
                errorMsg.style.display = 'block';
                document.getElementById('errorText').textContent = result.message || 'Failed to submit review';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Review';
            }
        } catch (error) {
            console.error('Review Error:', error);
            errorMsg.style.display = 'block';
            document.getElementById('errorText').textContent = 'Connection failed. Please try again.';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Review';
        }
    });
});