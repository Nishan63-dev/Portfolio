<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SchoolERP') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="layout">
        @auth
            <aside class="sidebar" id="sidebar">
                <div class="sidebar-logo">
                    <div class="logo-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                    <div class="logo-text"><h2>St. Hilda's</h2><span>Finance ERP</span></div>
                </div>
                <nav class="sidebar-nav">
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-page="dashboard">
                        <span class="nav-icon"><i class="fa-solid fa-grip"></i></span>
                        <span class="nav-label">Dashboard</span>
                    </a>
                    <div class="nav-section-label">Finance</div>
                    <a href="{{ route('incomes') }}" class="nav-item {{ request()->routeIs('incomes') ? 'active' : '' }}" data-page="incomes">
                        <span class="nav-icon"><i class="fa-solid fa-file-invoice"></i></span>
                        <span class="nav-label">Income</span>
                    </a>
                    <a href="{{ route('expenses') }}" class="nav-item {{ request()->routeIs('expenses') ? 'active' : '' }}" data-page="expenses">
                        <span class="nav-icon"><i class="fa-solid fa-receipt"></i></span>
                        <span class="nav-label">Expenses</span>
                    </a>
                    <a href="{{ route('payrolls') }}" class="nav-item {{ request()->routeIs('payrolls') ? 'active' : '' }}" data-page="payrolls">
                        <span class="nav-icon"><i class="fa-solid fa-wallet"></i></span>
                        <span class="nav-label">Payroll</span>
                    </a>
                    <a href="{{ route('reports') }}" class="nav-item {{ request()->routeIs('reports') ? 'active' : '' }}" data-page="reports">
                        <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span>
                        <span class="nav-label">Reports</span>
                    </a>
                </nav>
                <div class="sidebar-footer">
                    <div class="user-card">
                        <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        <div class="user-info">
                            <div class="name">{{ auth()->user()->name }}</div>
                            <div class="role">School Admin</div>
                        </div>
                    </div>
                </div>
            </aside>
            <div class="overlay" id="overlay"></div>
        @endauth

        <main class="main {{ auth()->check() ? '' : 'no-sidebar' }}" id="main">
            @if(auth()->check())
                <header class="topbar">
                    <button class="menu-toggle" id="menuToggle"><i class="fa-solid fa-bars"></i></button>
                    <div class="breadcrumb">
                        <span>St. Hilda's Academy</span>
                        <span class="sep"><i class="fa-solid fa-chevron-right" style="font-size:9px"></i></span>
                        <span class="current" id="breadcrumbCurrent">{{ $pageTitle ?? 'Dashboard' }}</span>
                    </div>
                    <div class="topbar-actions">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" placeholder="Search students, receipts…" />
                        </div>
                        <button class="topbar-btn notif-toggle" title="Notifications">
                            <i class="fa-solid fa-bell"></i>
                            <span class="notif-dot"></span>
                        </button>
                        <div class="topbar-user">
                            <div class="topbar-user-info">
                                <div class="tu-name">{{ auth()->user()->name }}</div>
                                <div class="tu-role">School Admin</div>
                            </div>
                            <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="topbar-logout">
                            @csrf
                            <button type="submit" class="topbar-btn logout-button" title="Logout">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span class="logout-label">Logout</span>
                            </button>
                        </form>
                    </div>
                </header>
            @endif

            <div class="page active">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
