@extends('layouts.admin')
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTalk Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Feedback Details</h2>

    <div class="card p-4 shadow-sm">
        <p><strong>User:</strong> {{ $feedback->name ?? $feedback->user->name }}</p>
        <p><strong>Email:</strong> {{ $feedback->email ?? $feedback->user->email }}</p>
        <p><strong>Subject:</strong> {{ $feedback->subject ?? '-' }}</p>
        <p><strong>Message:</strong></p>
        <p>{{ $feedback->message }}</p>
        <p><strong>Submitted At:</strong> {{ $feedback->created_at->format('d M Y, h:i A') }}</p>

        <a href="{{ url('admin/feedbacks') }}" class="btn btn-secondary mt-3">Back to All Feedbacks</a>
    </div>
</div>
@endsection