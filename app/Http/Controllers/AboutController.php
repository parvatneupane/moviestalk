<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => 500000,
            'movies' => 25000,
            'countries' => 180,
            'satisfaction' => 95
        ];
        
        $teamMembers = [
            [
                'name' => 'John Doe',
                'role' => 'Founder & CEO',
                'description' => 'Film enthusiast with over 15 years of experience in the entertainment industry.',
                'image' => 'images/team1.jpg'
            ],
            [
                'name' => 'Jane Smith',
                'role' => 'Chief Editor',
                'description' => 'Award-winning film critic and journalist with a passion for independent cinema.',
                'image' => 'images/team2.jpg'
            ],
            [
                'name' => 'Mike Johnson',
                'role' => 'Lead Developer',
                'description' => 'Tech enthusiast who combines his love for coding with his passion for movies.',
                'image' => 'images/team3.jpg'
            ]
        ];
        
        $values = [
            [
                'icon' => 'fas fa-heart',
                'title' => 'Passion for Cinema',
                'description' => 'We genuinely love movies and believe in their power to inspire, entertain, and connect people.'
            ],
            [
                'icon' => 'fas fa-users',
                'title' => 'Community First',
                'description' => 'Our platform is built by movie lovers, for movie lovers. We prioritize our community in everything we do.'
            ],
            [
                'icon' => 'fas fa-star',
                'title' => 'Quality Content',
                'description' => 'We maintain high standards for our reviews, recommendations, and editorial content.'
            ],
            [
                'icon' => 'fas fa-shield-alt',
                'title' => 'Trust & Transparency',
                'description' => 'We provide honest reviews and are transparent about our rating system and partnerships.'
            ]
        ];
        
        return view('about', compact('stats', 'teamMembers', 'values'));
    }
}