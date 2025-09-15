@extends('admin.adminlayout')

@section('content')
<h1>Add Genre</h1>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form action="{{ url('admin/addgenres') }}" method="POST">
    @csrf
    <label>Genre Name:</label>
    <input type="text" name="name" required>
    <button type="submit">Add Genre</button>
</form>
@endsection
