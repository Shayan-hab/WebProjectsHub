function confirmCancel(requestId) {
    if (confirm('Are you sure you want to cancel this request?')) {
        window.location.href = 'cancel_request.php?id=' + requestId;
    }
}