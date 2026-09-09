<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * @return array<string, mixed>
     */
    private function portfolioData(): array
    {
        return [
            'name' => 'Nishan Makwana',
            'title' => 'PHP / Laravel Developer',
            'email' => 'nishanmakwana63@gmail.com',
            'phone' => '+91 6356538791',
            'location' => 'Ahmedabad, Gujarat, India',

            'heroTagline' => 'I build robust backend systems, REST APIs, and full-stack Laravel applications that handle real users, real payments, and real complexity.',

            'bio' => 'Motivated PHP / Laravel Developer with hands-on experience building full-stack web applications including REST APIs, admin panels, and payment integrations. Passionate about writing clean, maintainable code and continuously growing through real-world projects. A collaborative team player who takes full ownership of features from database design and backend logic to UI development and delivery.',

            'stats' => [
                ['value' => '1+', 'label' => 'Year Professional Experience'],
                ['value' => '3', 'label' => 'Production Apps Built'],
                ['value' => '30+', 'label' => 'REST API Endpoints'],
                ['value' => '10+', 'label' => 'Technologies Used'],
            ],

            'services' => [
                [
                    'icon' => 'fa-solid fa-server',
                    'title' => 'Backend Development',
                    'desc' => 'Clean, scalable Laravel applications with well-structured Eloquent models, migrations, service classes, and queued jobs.',
                ],
                [
                    'icon' => 'fa-solid fa-plug',
                    'title' => 'REST API Design',
                    'desc' => 'Secure, versioned APIs with Sanctum authentication, rate limiting, validation, pagination, and comprehensive Postman documentation.',
                ],
                [
                    'icon' => 'fa-solid fa-credit-card',
                    'title' => 'Payment Integration',
                    'desc' => 'Stripe subscriptions, wallet systems, and billing flows — built with webhook handling, retry logic, and proper error states.',
                ],
                [
                    'icon' => 'fa-solid fa-shield-halved',
                    'title' => 'Auth & Security',
                    'desc' => 'OTP-based authentication, RBAC, brute-force lockout, bcrypt hashing, rate limiting, and audit logging for production-ready security.',
                ],
                [
                    'icon' => 'fa-solid fa-layer-group',
                    'title' => 'Full-Stack Delivery',
                    'desc' => 'End-to-end feature ownership — from database schema design through backend logic to Blade/Bootstrap UI and Vite asset bundling.',
                ],
                [
                    'icon' => 'fa-solid fa-cloud',
                    'title' => 'Cloud & Storage',
                    'desc' => 'Cloudflare R2 and AWS S3 integration for scalable media storage with signed URLs, automatic cleanup, and CDN delivery.',
                ],
            ],

            'skillCategories' => [
                [
                    'title' => 'Languages & Frameworks',
                    'icon' => 'fa-solid fa-code',
                    'skills' => ['PHP 8.2', 'JavaScript', 'HTML5', 'CSS3', 'Laravel 12', 'Laravel Sanctum', 'Laravel Fortify'],
                ],
                [
                    'title' => 'Database & Cloud',
                    'icon' => 'fa-solid fa-database',
                    'skills' => ['MySQL', 'SQLite', 'Eloquent ORM', 'Cloudflare R2', 'AWS S3', 'Stripe API'],
                ],
                [
                    'title' => 'Frontend & Tools',
                    'icon' => 'fa-solid fa-palette',
                    'skills' => ['Bootstrap 5', 'AdminLTE', 'Blade Templates', 'Vite', 'jQuery', 'Select2', 'FullCalendar', 'Postman'],
                ],
                [
                    'title' => 'Concepts & Practices',
                    'icon' => 'fa-solid fa-gears',
                    'skills' => ['Git & GitHub', 'MVC Architecture', 'REST API Design', 'RBAC', 'OTP Authentication', 'Queue Workers', 'Supervisor', 'Linux'],
                ],
            ],

            'projects' => [
                [
                    'name' => 'CircleUp',
                    'status' => 'Completed',
                    'oneLiner' => 'Sports court booking platform with complete booking lifecycle management.',
                    'tech' => ['Laravel 12', 'PHP 8.2', 'Sanctum', 'SQLite/MySQL', 'FullCalendar'],
                    'highlights' => [
                        ['metric' => '30+', 'label' => 'API Endpoints'],
                        ['metric' => '4', 'label' => 'Booking States'],
                        ['metric' => 'RBAC', 'label' => 'Access Control'],
                    ],
                    'points' => [
                        'Designed and built 30+ REST API endpoints covering the full booking lifecycle — from pending through confirmed, cancelled, to completed — with role-based access control for admins, court owners, and players.',
                        'Implemented secure Email OTP authentication with bcrypt hashing, brute-force lockout after failed attempts, request rate limiting, and full audit logging for compliance.',
                        'Integrated FullCalendar for visual court scheduling with drag-and-drop, and built a unified search/filter UI for managing bookings across multiple courts and time slots.',
                    ],
                ],
                [
                    'name' => 'YUZ',
                    'status' => 'In Progress',
                    'oneLiner' => 'Dual-marketplace platform for renting objects and booking services.',
                    'tech' => ['Laravel 12', 'PHP 8.2', 'Stripe', 'Cloudflare R2', 'MySQL'],
                    'highlights' => [
                        ['metric' => 'Stripe', 'label' => 'Payments'],
                        ['metric' => 'R2', 'label' => 'Media Storage'],
                        ['metric' => 'Real-time', 'label' => 'Chat System'],
                    ],
                    'points' => [
                        'Architected a dual-marketplace where users can both rent physical objects and book professional services — each with its own multi-stage fulfillment workflow (request → accept → in-progress → delivered → reviewed).',
                        'Integrated Stripe for subscription billing and wallet-based payments with automatic payouts, refund handling, and webhook processing for real-time payment state updates.',
                        'Built Cloudflare R2 media storage pipeline for listing images and user avatars, plus real-time chat with read receipts, typing indicators, and a notification system for booking updates.',
                    ],
                ],
                [
                    'name' => 'Thank You Mate',
                    'status' => 'Completed',
                    'oneLiner' => 'Labour marketplace connecting workers with job opportunities.',
                    'tech' => ['Laravel 12', 'PHP 8.2', 'Sanctum', 'MySQL', 'Supervisor'],
                    'highlights' => [
                        ['metric' => '2', 'label' => 'User Roles'],
                        ['metric' => 'OTP', 'label' => 'Auth System'],
                        ['metric' => 'Admin', 'label' => 'Panel Built'],
                    ],
                    'points' => [
                        'Built a two-sided labour marketplace with owner and worker roles — covering the complete job lifecycle: posting, discovery, applications, hiring, attendance tracking, and payment settlement.',
                        'Implemented OTP-based mobile authentication with Sanctum token management, conversation-threaded real-time chat between owners and workers, and push notification triggers for job updates.',
                        'Developed a comprehensive admin panel for user management, platform activity monitoring with analytics, a broadcast notification system, and dynamic legal page content management (Terms, Privacy, FAQ).',
                    ],
                ],
            ],

            'experience' => [
                [
                    'role' => 'PHP / Laravel Developer',
                    'company' => 'BM Coder',
                    'period' => 'Nov 2025 – Present',
                    'location' => 'Ahmedabad, Gujarat',
                    'points' => [
                        'Building and maintaining full-stack Laravel 12 applications including REST APIs, admin panels, and Stripe payment integrations for production client projects.',
                        'Owning end-to-end feature delivery: database schema design with migrations, Eloquent ORM modeling with relationships and scopes, Blade UI development, and Vite asset bundling.',
                        'Testing and documenting APIs using Postman collections, managing the codebase with Git version control, and collaborating through code reviews in a team environment.',
                        'Working with Cloudflare R2 and AWS S3 for media storage, Supervisor for queue workers, and deploying applications to production servers.',
                    ],
                ],
            ],

            'education' => [
                [
                    'degree' => 'Bachelor of Computer Applications (BCA)',
                    'university' => 'Maharaja Krishnakumarsinhji University',
                    'period' => 'July 2022 – March 2025',
                    'location' => 'Ahmedabad, Gujarat',
                    'coursework' => 'Data Structures, Database Management Systems, Web Technologies, Software Engineering, Computer Networks',
                ],
            ],

            'approach' => [
                [
                    'num' => '01',
                    'title' => 'Understand the Problem',
                    'desc' => 'I start by deeply understanding the business requirement — who the users are, what they need, and what "done" looks like before writing a single line of code.',
                ],
                [
                    'num' => '02',
                    'title' => 'Design the Schema First',
                    'desc' => 'Database design drives everything. I plan migrations, relationships, indexes, and constraints upfront so the data layer is solid from day one.',
                ],
                [
                    'num' => '03',
                    'title' => 'Build Incrementally',
                    'desc' => 'Ship working features in small, testable chunks. Every API endpoint works standalone before moving to the next — no half-built dead ends.',
                ],
                [
                    'num' => '04',
                    'title' => 'Own It End-to-End',
                    'desc' => 'From schema to API to UI to deployment — I take full responsibility for the features I build. No handoffs, no excuses.',
                ],
            ],

            'socialLinks' => [
                'github' => 'https://github.com/Nishan63-dev',
                'linkedin' => 'https://www.linkedin.com/in/nishan-makwana-b5a2a935b',
                'email' => 'mailto:nishanmakwana63@gmail.com',
            ],
        ];
    }

    public function index(): View
    {
        return view('portfolio', $this->portfolioData());
    }
}
