@extends('layouts.app')

@section('title', 'About Us - MovieTalks')


<link rel="stylesheet" href="{{ asset('css/aboutus.css') }}">


@section('content')
  
    <section class="about-content">
        <div class="container">
        
            <div class="about-section">
                <div class="section-header">
                    <h2 class="section-title">Our Story</h2>
                    <p class="section-subtitle">How our passion for cinema created the ultimate movie platform</p>
                </div>
                
                <div class="story-grid">
                    <div class="story-content">
                        <h3>From Movie Lovers to Movie Experts</h3>
                        <p>Founded in 2015, MovieTalks began as a small blog between friends who shared an insatiable love for cinema. What started as casual discussions about films evolved into a comprehensive platform for movie enthusiasts worldwide.</p>
                        <p>Our mission has always been to create a community where film lovers can discover, discuss, and celebrate the art of cinema. We believe every movie has a story worth telling, and every viewer deserves a trusted source to guide their cinematic journey.</p>
                        <p>Today, MovieTalks serves millions of users monthly, offering reviews, recommendations, and insights across all genres and eras of filmmaking.</p>
                    </div>
                    <div class="story-image">
                        <img src="{{ asset('images/3mans.jpeg') }}" alt="Movie theater">
                    </div>
                </div>
            </div>
            
        
            <div class="about-section">
                <div class="section-header">
                    <h2 class="section-title">By The Numbers</h2>
                    <p class="section-subtitle">Our impact in the movie community</p>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['users'] }}+</div>
                        <div class="stat-label">Monthly Users</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['movies'] }}+</div>
                        <div class="stat-label">Movies Reviewed</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['countries'] }}+</div>
                        <div class="stat-label">Countries Reached</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['satisfaction'] }}%</div>
                        <div class="stat-label">User Satisfaction</div>
                    </div>
                </div>
            </div>
            
            <div class="about-section">
                <div class="section-header">
                    <h2 class="section-title">Our Team</h2>
                    <p class="section-subtitle">The passionate cinephiles behind MovieTalks</p>
                </div>
               
               <!-- team  -->   
                <div class="team-grid">
                  
                    <div class="team-member">
                        <div class="member-image">
                            <img src="{{asset('images/parvat.jpg')}}" alt="parvat">
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">Parvat Neupane</h3>
                            <div class="member-role">Founder & CEO</div>
                            <p class="member-desc">Film studies graduate with a passion for connecting people through cinema.</p>
                            <div class="member-social">
                                <a href="#"><i class="fab fa-github"></i></a>
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="mailto:"><i class="fab fa-google"></i></a>
                            </div>
                            
                        </div>
                    </div>
                    
                
                    <div class="team-member">
                        <div class="member-image">
                            <img src="{{ asset('images/shiva.jpg')}}" alt="shiva">
                         
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">Shiva Bhandari</h3>
                            <div class="member-role">Head of System</div>
                            <p class="member-desc">Former film critic with an encyclopedic knowledge of cinema history.</p>
                            <div class="member-social">
                                <a href="https://github.com/shivabhandari245"><i class="fab fa-github"></i></a>
                                <a href=""><i class="fab fa-linkedin"></i></a>
                                <a href="mailto:bhandarishiva318@gmail.com"><i class="fab fa-google"></i></a>
                            </div>
                        </div>
                    </div>
                    
                  
                    <div class="team-member">
                        <div class="member-image">
                            <img src="{{ asset('images/saiman.jpeg')}}" alt="saiman">
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">Saiman Sharma</h3>
                            <div class="member-role">Manager</div>
                            <p class="member-desc">Creates beautiful experiences that make discovering movies effortless.</p>
                            <div class="member-social">
                                <a href="#"><i class="fab fa-github"></i></a>
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="mailto:"><i class="fab fa-google"></i></a>
                            </div>
                        </div>
                    </div>
       
                </div>  
                
            
@endsection

@push('scripts')
    <script src="{{ asset('js/aboutus.js') }}"></script>
@endpush
