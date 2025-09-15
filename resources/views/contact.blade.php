@extends('layouts.app')

@section('title', 'Contact Us - MovieTalks')


<link rel="stylesheet" href="{{ asset('css/contactus.css') }}">


@section('content')
    
    <section class="contact-content">
        <div class="container">
            <div class="contact-section">
                <div class="section-header">
                    <h2 class="section-title">Contact Us</h2>
                    <p class="section-subtitle">Have questions about movies, our platform, or anything else? We're here to help.</p>
                </div>
                
                <div class="contact-grid">
                  
                    <div class="contact-info">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h3>Our Location</h3>
                                <p>Bharatpur-26,Chitwan </p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h3>Phone Number</h3>
                                <p><a href="tel:+9765512898">9765512898</a></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h3>Email Address</h3>
                                <p><a href="mailto:bhandarishiva318@gmail.com">bhandarishiva318@gmail.com</a></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h3>Working Hours</h3>
                                <p>Monday - Friday: 9AM - 6PM</p>
                                <p>Weekends: 10AM - 4PM</p>
                            </div>
                        </div>
                        
                        <div class="social-links-horizontal">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    
                   
                    <div class="contact-form">
                        <form id="contactForm" action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Your Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" class="form-control" placeholder="What is this regarding?">
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Your Message</label>
                                <textarea id="message" name="message" class="form-control" placeholder="Type your message here..." required></textarea>
                            </div>
                            
                            <button type="submit" class="btn-primary submit-btn">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
            
        
            <div class="contact-section">
                <div class="section-header">
                    <h2 class="section-title">Frequently Asked Questions</h2>
                    <p class="section-subtitle">Quick answers to common questions about MovieTalks</p>
                </div>
                
                <div class="faq-container">
                    @foreach($faqs as $faq)
                    <div class="faq-item">
                        <div class="faq-question">
                            {{ $faq['question'] }}
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{asset('js/contactus.js')}}"></script>
@endpush