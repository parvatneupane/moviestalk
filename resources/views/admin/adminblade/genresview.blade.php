@extends('layouts.admin')

@section('content')

        <!-- Genre Management Header -->
        <div class="management-header">
            <div class="dashboard-title">
                <h1>Genre Management</h1>
                <p>Manage all genres and categories</p>
            </div>
            <a href="{{ url('admin/addgenres') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Genre
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Genres Table -->
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Genre Name</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($genres as $genre)
                        <tr>
                            <td>#{{ $genre->id }}</td>
                            <td>{{ $genre->name }}</td>
                            <td>{{ $genre->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <form action="{{ url('admin/deletegenres/'.$genre->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete btn-sm" onclick="return confirm('Are you sure you want to delete this genre?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fas fa-inbox fs-1 text-muted mb-3"></i>
                                <p class="text-muted">No genres found</p>
                                <a href="{{ url('admin/addgenres') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Add Your First Genre
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <!-- Dynamic if using paginate() -->
        </div>
    </div>
</div>
@endsection