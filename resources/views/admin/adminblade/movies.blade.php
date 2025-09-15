     
     
     @extends('admin.adminlayout')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTalk Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/movies.css') }}">
</head>
<body>
@section('content')

    <div class="container">
    
     
     
     <!-- Main Content -->
        <div class="main">
         <!-- Header -->
            <div class="header">
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>

                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search users...">
                </div>

                <div class="user-menu">
                     <div class="user-profile">
                        <div class="user-avatar"></div>
                        <div>
                             <div>{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Movies Management Header -->
            <div class="movies-management-header">
                <div class="dashboard-title">
                    <h1>Movie Management</h1>
                    <p>Manage all movies in the MovieTalk database</p>
                </div>              
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <select class="filter-select">
                    <option>All Genres</option>
                    <option>Action</option>
                    <option>Comedy</option>
                    <option>Drama</option>
                    <option>Sci-Fi</option>
                    <option>Horror</option>
                </select>
                
                <select class="filter-select">
                    <option>All Years</option>
                    <option>2023</option>
                    <option>2022</option>
                    <option>2021</option>
                    <option>2020</option>
                </select>
                
                <select class="filter-select">
                    <option>Sort by: Newest</option>
                    <option>Sort by: Oldest</option>
                    <option>Sort by: Title</option>
                    <option>Sort by: Rating</option>
                </select>
            </div>

            <!-- Movies Table -->
            <table class="movies-table">
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Release Date</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                     @foreach($movies as $data)
                    <tr>
                        <td>
                   <img src="{{ asset(  $data->poster) }}" alt="{{ $data->title }}" class="movie-poster">

                            </td>
                        <td>
                            <div style="font-weight: 600;">{{ $data->title }}</div>
                            <div style="font-size: 13px; color: #777;">{{ $data->director }}</div>
                        </td>
<td>{{ implode(', ', $data->category_names) }}</td>
                        
                        <td>{{ $data->release_date }}</td>
                        <td>
                            <span class="rating-badge">
                                <i class="fas fa-star"></i> {{ $data->rating }}
                            </span>
                        </td>
                        <td><span class="status-badge status-active">{{ $data->status }}</span></td>
                        <td>



                             <div class="action-buttons">

         <a href="{{ url('/admin/reviews/' . $data->id ) }}"class="btn btn-edit btn-sm">                                     
                          <i class="fas fa-comments"></i> Reviews
                                    </a>


                                    <a href="{{ url('/admin/movies/' . $data->id ) }}" class="btn btn-edit btn-sm">                                     
                                        <i class="fas fa-edit"></i> Edit
                                    </a>


                                    <a href="{{ url('/admin/deletemovies/' . $data->id ) }}" class="btn btn-delete btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                        </td>
                    </tr>
                   @endforeach 
                  
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <button class="pagination-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

      {{-- <script src="{{ asset('adminjs/movies.js') }}"></script> --}}
</body>
 @endsection
