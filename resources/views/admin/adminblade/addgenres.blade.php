

 
 
 <body>
@section('content')
    <h1>Add Genre</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('admin/addgenres') }}" method="POST">
        @csrf
        <label for="genre-name">Genre Name:</label><br>
        <input type="text" name="name" id="genre-name" required><br><br>
        <button type="submit">Add Genre</button>
    </form>
@endsection
