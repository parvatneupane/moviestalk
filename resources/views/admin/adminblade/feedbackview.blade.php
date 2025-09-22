@extends('layouts.admin')

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/feedbackview.css') }}">


@section('content')

    <div class="container">

        <!-- Main Content -->
        <div class="main">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Feedback Details</h1>
        <a href="{{ url('admin/feedbacks') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <!-- Feedback Info Card -->
    <div class="formatcard">
    <div class="card">
        <div class="card-header">
            <div><i class="fas fa-info-circle"></i> Feedback Information</div>
            <span class="status-badge {{ $feedback->replies()->where('is_admin',1)->exists() ? 'status-replied' : 'status-pending' }}">
                <i class="fas {{ $feedback->replies()->where('is_admin',1)->exists() ? 'fa-check-circle' : 'fa-clock' }}"></i>
                {{ $feedback->replies()->where('is_admin',1)->exists() ? 'Replied' : 'Pending' }}
            </span>
        </div>
        <div class="card-body">
            <!-- Info Rows -->
            <div class="info-row">
                <span class="info-label">User</span>
                <span class="info-value">{{ $feedback->name ?? optional($feedback->user)->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $feedback->email ?? optional($feedback->user)->email ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Subject</span>
                <span class="info-value">{{ $feedback->subject ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Submitted At</span>
                <span class="info-value">{{ $feedback->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</span>
            </div>

            <!-- Message -->
            <div class="message-container">
                <div class="message-label">MESSAGE</div>
                <div class="message-content">{{ $feedback->message ?? 'No message provided.' }}</div>
            </div>
        </div>
    </div>

    <!-- Admin Reply -->
    <div class="card">
        <div class="card-header">
            <div><i class="fas fa-reply"></i> Admin Reply</div>
        </div>
        <div class="card-body">
            <div class="reply-container">
                <div class="reply-header">
                    <div class="reply-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h5>Response to Feedback</h5>
                </div>

                @php
                    $latestReply = $feedback->replies()->where('is_admin',1)->latest()->first();
                @endphp

                @if($latestReply)
                    <textarea class="form-control" rows="4" readonly>{{ $latestReply->reply }}</textarea>
                @else
                    <form id="replyForm">
                        @csrf
                        <textarea name="reply" id="replyText" class="form-control mb-3" rows="4" placeholder="Type your reply here..." required></textarea>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Reply
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const replyForm = document.getElementById('replyForm');
    
    if (replyForm) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;

            const formData = new FormData(replyForm);

            fetch('{{ route("admin.feedback.reply", $feedback->id) }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    showNotification('Reply sent successfully!', 'success');
                    setTimeout(()=> location.reload(), 1500);
                } else {
                    throw new Error(data.error || 'Something went wrong');
                }
            })
            .catch(err => {
                console.error(err);
                showNotification('Error: ' + err.message, 'error');
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }

    function showNotification(message, type){
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        notification.innerHTML = `<i class="fas ${icon}"></i> ${message}`;
        document.body.appendChild(notification);
        setTimeout(()=> notification.remove(), 3000);
    }
});
</script>
@endsection
