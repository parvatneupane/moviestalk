@extends('layouts.admin')

    

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTalk Admin - Users</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/upadtemovies.css') }}">
</head>



    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f0f6ff 0%, #e0eeff 100%);
            color: #2c3e50;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .admin-container {
            width: 100%;
            max-width: 500px;
        }
        
        .admin-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 82, 204, 0.15);
        }
        
        .card-header {
            background: linear-gradient(120deg, #1e88e5, #1565c0);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .card-header h2 {
            font-weight: 600;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .card-header h2 i {
            margin-right: 12px;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #34495e;
            display: flex;
            align-items: center;
        }
        
        .form-group label i {
            margin-right: 10px;
            color: #1e88e5;
            width: 20px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #1e88e5;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.2);
        }
        
        select.form-control {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231e88e5' viewBox='0 0 16 16'%3E%3Cpath d='M8 12L2 6h12L8 12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
            appearance: none;
        }
        
        .btn-primary {
            background: linear-gradient(120deg, #1e88e5, #1565c0);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-primary:hover {
            background: linear-gradient(120deg, #1565c0, #1e88e5);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 136, 229, 0.3);
        }
        
        .btn-primary i {
            margin-right: 8px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            color: #7f8c8d;
            text-decoration: none;
            font-weight: 500;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: #1e88e5;
        }
        
        .back-link i {
            margin-right: 8px;
        }
        
        @media (max-width: 576px) {
            .card-body {
                padding: 20px;
            }
            
            .card-header h2 {
                font-size: 20px;
            }
        }
    </style>
</head>


@section('content')
<body>
    <div class="admin-container">
        <div class="admin-card">
            <div class="card-header">
                <h2><i class="fas fa-user-edit"></i> Edit User</h2>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/updateuser/' . $user->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Name:</label>
                        <div class="input-with-icon">
                           
                            <input type="text" id="name" name="name" value="{{ $user->name }}" required class="form-control" placeholder="Enter user's full name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                        <div class="input-with-icon">
                          
                            <input type="email" id="email" name="email" value="{{ $user->email }}" required class="form-control" placeholder="Enter user's email address">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role"><i class="fas fa-user-tag"></i> Role:</label>
                        <select id="role" name="role" class="form-control">
                            <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Update User</button>
                </form>
                
                <a href="{{ url('/admin/users') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Users List</a>
            </div>
        </div>
    </div>

    <script>
        // Add a slight animation to form elements
        document.addEventListener('DOMContentLoaded', function() {
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                group.style.opacity = '0';
                group.style.transform = 'translateY(10px)';
                group.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    group.style.opacity = '1';
                    group.style.transform = 'translateY(0)';
                }, 100 + (index * 100));
            });
            
            // Focus on first input
            document.getElementById('name').focus();
        });
    </script>
@endsection