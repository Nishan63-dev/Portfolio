<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $name }} — {{ $title }}. {{ Str::limit($bio, 160) }}">
    <meta name="author" content="{{ $name }}">
    <title>{{ $name }} — {{ $title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Space+Mono:wght@400;700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- ═══ NAV ═══ --}}
    <nav class="nav" id="nav">
        <a href="#top" class="nav-logo">N<span class="accent">.</span></a>
        <div class="nav-links" id="navLinks">
            <a href="#work">Work</a>
            <a href="#services">Services</a>
            <a href="#about">About</a>
            <a href="#skills">Skills</a>
            <a href="#contact">Say Hello</a>
            <a href="{{ asset('resume.pdf') }}" target="_blank" rel="noopener" class="nav-resume-btn">
                <i class="fa-solid fa-file-arrow-down"></i> Resume
            </a>
        </div>
        <button class="nav-burger" id="navBurger" aria-label="Toggle navigation" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </nav>

    {{-- ═══ HERO ═══ --}}
    <header class="hero" id="top">
        <div class="hero-inner">
            <div class="hero-left">
                <p class="hero-label">PHP / Laravel Developer — {{ $location }}</p>
                <h1 class="hero-h1">
                    {{ $name }}<span class="accent">.</span>
                </h1>
                <p class="hero-role">Building production apps that handle<br>real users, real payments, real scale.</p>
            </div>
            <div class="hero-right">
                <p class="hero-bio">{{ $heroTagline }}</p>
                <div class="hero-cta">
                    <a href="#work" class="btn-solid"><i class="fa-solid fa-arrow-down"></i> See my work</a>
                    <a href="{{ asset('resume.pdf') }}" target="_blank" rel="noopener" class="btn-outline">
                        <i class="fa-solid fa-download"></i> Resume (PDF)
                    </a>
                    <a href="mailto:{{ $email }}" class="btn-ghost">{{ $email }}</a>
                </div>
                <div class="hero-quick">
                    <div class="hq-item">
                        <span class="hq-val">{{ $experience[0]['period'] }}</span>
                        <span class="hq-lbl">@ {{ $experience[0]['company'] }}</span>
                    </div>
                    <div class="hq-item">
                        <span class="hq-val">Laravel 12 · PHP 8.2</span>
                        <span class="hq-lbl">Primary Stack</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-ticker">
            <div class="ticker-track">
                @for ($i = 0; $i < 8; $i++)
                    <span>Laravel</span><span class="ticker-dot"></span>
                    <span>REST APIs</span><span class="ticker-dot"></span>
                    <span>Stripe</span><span class="ticker-dot"></span>
                    <span>MySQL</span><span class="ticker-dot"></span>
                    <span>Sanctum</span><span class="ticker-dot"></span>
                    <span>PHP 8.2</span><span class="ticker-dot"></span>
                @endfor
            </div>
        </div>
    </header>

    {{-- ═══ PROJECTS ═══ --}}
    <section class="section" id="work">
        <div class="section-top">
            <span class="section-num">01</span>
            <div>
                <h2 class="section-heading">Selected<br>Projects</h2>
                <p class="section-sub">Real applications I've built end-to-end — from database design to deployment.</p>
            </div>
        </div>

        <div class="projects">
            @foreach ($projects as $i => $project)
                <article class="proj" data-reveal>
                    <div class="proj-top">
                        <div>
                            <span class="proj-idx">Project 0{{ $i + 1 }}</span>
                            <span class="proj-status {{ $project['status'] === 'In Progress' ? 'proj-wip' : '' }}">{{ $project['status'] }}</span>
                        </div>
                    </div>

                    <h3 class="proj-name">{{ $project['name'] }}</h3>
                    <p class="proj-oneliner">{{ $project['oneLiner'] }}</p>

                    {{-- Metrics strip --}}
                    <div class="proj-metrics">
                        @foreach ($project['highlights'] as $hl)
                            <div class="pm-item">
                                <span class="pm-val">{{ $hl['metric'] }}</span>
                                <span class="pm-lbl">{{ $hl['label'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="proj-body">
                        @foreach ($project['points'] as $point)
                            <p>{{ $point }}</p>
                        @endforeach
                    </div>

                    <div class="proj-stack">
                        @foreach ($project['tech'] as $tech)
                            <span>{{ $tech }}</span>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ═══ SERVICES / WHAT I DO ═══ --}}
    <section class="section section-dark" id="services">
        <div class="section-top">
            <span class="section-num">02</span>
            <div>
                <h2 class="section-heading">What I Do</h2>
                <p class="section-sub">Specialized in backend systems that power real products.</p>
            </div>
        </div>

        <div class="services-grid">
            @foreach ($services as $svc)
                <div class="svc-card" data-reveal>
                    <div class="svc-icon"><i class="{{ $svc['icon'] }}"></i></div>
                    <h4 class="svc-title">{{ $svc['title'] }}</h4>
                    <p class="svc-desc">{{ $svc['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ═══ ABOUT ═══ --}}
    <section class="section" id="about">
        <div class="section-top">
            <span class="section-num">03</span>
            <h2 class="section-heading">About</h2>
        </div>

        <div class="about-layout">
            <div class="about-col-left">
                <div class="about-avatar-wrap">
                    <div class="about-avatar">NM</div>
                </div>
                <div class="about-stats">
                    @foreach ($stats as $stat)
                        <div class="stat" data-reveal>
                            <span class="stat-val">{{ $stat['value'] }}</span>
                            <span class="stat-lbl">{{ $stat['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="about-col-right" data-reveal>
                <p class="about-bio">{{ $bio }}</p>
                <div class="about-meta">
                    <div class="meta-row">
                        <span class="meta-key">Location</span>
                        <span class="meta-val">{{ $location }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">Currently</span>
                        <span class="meta-val">{{ $experience[0]['role'] }} @ {{ $experience[0]['company'] }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">Education</span>
                        <span class="meta-val">{{ $education[0]['degree'] }}, {{ $education[0]['university'] }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">Email</span>
                        <span class="meta-val"><a href="mailto:{{ $email }}" class="meta-link">{{ $email }}</a></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ HOW I WORK ═══ --}}
    <section class="section section-dark">
        <div class="section-top">
            <span class="section-num">04</span>
            <div>
                <h2 class="section-heading">How I Work</h2>
                <p class="section-sub">My process for delivering features from zero to production.</p>
            </div>
        </div>

        <div class="approach-grid">
            @foreach ($approach as $step)
                <div class="approach-card" data-reveal>
                    <span class="approach-num">{{ $step['num'] }}</span>
                    <h4 class="approach-title">{{ $step['title'] }}</h4>
                    <p class="approach-desc">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ═══ EXPERIENCE ═══ --}}
    <section class="section">
        <div class="section-top">
            <span class="section-num">05</span>
            <h2 class="section-heading">Experience &<br>Education</h2>
        </div>

        <div class="exp-list">
            @foreach ($experience as $exp)
                <div class="exp-item" data-reveal>
                    <div class="exp-when">
                        <span class="exp-period">{{ $exp['period'] }}</span>
                        <span class="exp-loc"><i class="fa-solid fa-location-dot"></i> {{ $exp['location'] }}</span>
                    </div>
                    <div class="exp-what">
                        <h3 class="exp-role">{{ $exp['role'] }}</h3>
                        <p class="exp-company">{{ $exp['company'] }}</p>
                        <ul class="exp-points">
                            @foreach ($exp['points'] as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach

            @foreach ($education as $edu)
                <div class="exp-item" data-reveal>
                    <div class="exp-when">
                        <span class="exp-period">{{ $edu['period'] }}</span>
                        <span class="exp-loc"><i class="fa-solid fa-location-dot"></i> {{ $edu['location'] }}</span>
                    </div>
                    <div class="exp-what">
                        <h3 class="exp-role">{{ $edu['degree'] }}</h3>
                        <p class="exp-company">{{ $edu['university'] }}</p>
                        <p class="exp-course"><strong>Coursework:</strong> {{ $edu['coursework'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ═══ SKILLS ═══ --}}
    <section class="section section-dark" id="skills">
        <div class="section-top">
            <span class="section-num">06</span>
            <div>
                <h2 class="section-heading">Toolkit</h2>
                <p class="section-sub">Technologies and tools I work with daily.</p>
            </div>
        </div>

        <div class="toolkit">
            @foreach ($skillCategories as $cat)
                <div class="tool-group" data-reveal>
                    <h4 class="tool-title"><i class="{{ $cat['icon'] }}"></i> {{ $cat['title'] }}</h4>
                    <div class="tool-list">
                        @foreach ($cat['skills'] as $skill)
                            <span class="tool-pill">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ═══ CODE SIGNATURE ═══ --}}
    <section class="section code-section">
        <div class="code-block" data-reveal>
            <div class="code-bar">
                <span class="code-dot" style="background:#ff5f57"></span>
                <span class="code-dot" style="background:#ffbd2e"></span>
                <span class="code-dot" style="background:#28c840"></span>
                <span class="code-file">NishanMakwana.php</span>
            </div>
            <pre class="code-body"><code><span class="c-key">class</span> <span class="c-cls">NishanMakwana</span> <span class="c-key">extends</span> <span class="c-cls">Developer</span>
{
    <span class="c-key">public string</span> <span class="c-var">$name</span> = <span class="c-str">'Nishan Makwana'</span>;
    <span class="c-key">public string</span> <span class="c-var">$role</span> = <span class="c-str">'PHP / Laravel Developer'</span>;

    <span class="c-key">public function</span> <span class="c-fn">skills</span>(): <span class="c-cls">array</span>
    {
        <span class="c-key">return</span> [<span class="c-str">'PHP 8.2'</span>, <span class="c-str">'Laravel 12'</span>, <span class="c-str">'REST APIs'</span>, <span class="c-str">'MySQL'</span>, <span class="c-str">'Stripe'</span>];
    }

    <span class="c-key">public function</span> <span class="c-fn">motto</span>(): <span class="c-cls">string</span>
    {
        <span class="c-key">return</span> <span class="c-str">'Ship clean code. Own every feature. Never stop learning.'</span>;
    }
}</code></pre>
        </div>
    </section>

    {{-- ═══ CONTACT ═══ --}}
    <section class="section section-contact" id="contact">
        <div class="contact-inner" data-reveal>
            <p class="contact-label">07 — Let's Connect</p>
            <h2 class="contact-big">
                Have a project in mind?<br>
                Let's build it together<span class="accent">.</span>
            </h2>
            <p class="contact-sub">I'm currently open to freelance work, full-time opportunities, and interesting collaborations. Whether you need a backend system, an API, or a complete Laravel application — I'd love to hear about it.</p>

            <a href="mailto:{{ $email }}" class="contact-email">{{ $email }}</a>

            <div class="contact-row">
                <div class="contact-block">
                    <span class="contact-key">Phone</span>
                    <a href="tel:{{ $phone }}">{{ $phone }}</a>
                </div>
                <div class="contact-block">
                    <span class="contact-key">Based in</span>
                    <span>{{ $location }}</span>
                </div>
                <div class="contact-block">
                    <span class="contact-key">Availability</span>
                    <span class="avail-badge"><span class="avail-dot"></span> Open to opportunities</span>
                </div>
                <div class="contact-links">
                    <a href="{{ $socialLinks['github'] }}" target="_blank" rel="noopener" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
                    <a href="{{ $socialLinks['linkedin'] }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="{{ $socialLinks['email'] }}" aria-label="Email"><i class="fa-solid fa-envelope"></i></a>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ FOOTER ═══ --}}
    <footer class="foot">
        <span>&copy; {{ date('Y') }} {{ $name }}</span>
        <span>Built with Laravel & obsessive attention to detail</span>
    </footer>

</body>
</html>
