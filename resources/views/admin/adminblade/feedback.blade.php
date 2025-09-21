@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">User Feedbacks</h2>

    @if($feedbacks->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacks as $feedback)
                        <tr>
                            <td>{{ $feedback->id }}</td>
                            <td>{{ $feedback->name ?? $feedback->user->name }}</td>
                            <td>{{ $feedback->email ?? $feedback->user->email }}</td>
                            <td>{{ $feedback->subject ?? '-' }}</td>
                            <td class="message-cell">{{ Str::limit($feedback->message, 60) }}</td> <!-- short preview -->
                            <td>{{ $feedback->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <!-- View modal trigger -->
                                <a href="{{ url('admin/feedbacks/' . $feedback->id) }}" class="btn btn-sm btn-info">
                                    View
                                </a>

                                <!-- Delete -->
                                <form action="{{ url('admin/deletefeedbacks/' . $feedback->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this feedback?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $feedbacks->links() }}
        </div>
    @else
        <div class="alert alert-info">No feedback submitted yet.</div>
    @endif
</div>
@endsection
