@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Feedback Details</h2>

    <div class="card p-4 shadow-sm mb-4">
        <p><strong>User:</strong> {{ $feedback->name ?? $feedback->user->name }}</p>
        <p><strong>Email:</strong> {{ $feedback->email ?? $feedback->user->email }}</p>
        <p><strong>Subject:</strong> {{ $feedback->subject ?? '-' }}</p>
        <p><strong>Message:</strong></p>
        <p>{{ $feedback->message }}</p>
        <p><strong>Submitted At:</strong> {{ $feedback->created_at->format('d M Y, h:i A') }}</p>
    </div>

    <!-- Reply Form -->
    <div class="card p-4 shadow-sm">
        <h5>Reply to Feedback</h5>
            <form id="replyForm">
                @csrf
                <textarea name="reply" id="replyText" required></textarea>
                <button type="submit">Send Reply</button>
            </form>

    </div>

    <a href="{{ url('admin/feedbacks') }}" class="btn btn-secondary mt-3">Back to All Feedbacks</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const replyForm = document.getElementById('replyForm');

    replyForm.addEventListener('submit', function(e) {
        e.preventDefault(); // stop normal form submit

        const formData = new FormData(replyForm); // includes CSRF token automatically

        // <-- THIS IS WHERE YOUR fetch GOES -->
        fetch('{{ route("admin.feedback.reply", $feedback->id) }}', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json().catch(() => { throw new Error('Invalid JSON'); }))
        .then(data => {
            alert(data.success);
            replyForm.reset();
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong. Check console for details.');
        });
    });
});
</script>




@endsection
