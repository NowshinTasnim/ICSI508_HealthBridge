<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HealthBridge Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="bg-gray-50">
<nav class="bg-white shadow-lg">
    <div class="head_part">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <img src="{{ asset('images/health-bridge-logo.png') }}" alt="HealthBridge" style="height: 40px;">
            </div>

            <div class="flex items-center space-x-6">
                <div class="relative flex items-center">
                    <input type="text"
                           placeholder="  Search for..."
                           class="w-64 pl-4 pr-10 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="relative flex items-center contact-us-box">
                    <img src="{{ asset('images/msg.png') }}" alt="Message Icon" class="contact-icon" style="height: 15px;">
                    <a href="mailto:support@healthbridge.com" class="contact-us-text" style="text-decoration: none;">Contact</a>
                </div>

                <div class="flex flex-col items-center pointer-events-auto">
                    <a href="#"><img src="{{ asset('images/icon1.png') }}" alt="Admin"></a>
                    <span class="text-sm text-gray-700 ml-2">Admin</span>
                </div>

                <div class="relative flex items-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <img src="{{ asset('images/arrow.png') }}" alt="logout" style="height: 30px; width: auto;">
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="flex min-h-screen">
    <div class="w-64 bg-purple-800 text-white">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center space-x-2 px-4 py-4 {{ request()->routeIs('admin.dashboard') ? 'bg-purple-900' : 'hover:bg-purple-700' }}">
            <img src="{{ asset('images/dashboard.png') }}" alt="Dashboard" style="width: 20px;">
            <span class="font-semibold">Dashboard</span>
        </a>
        <hr class="Custom_line2">
        <hr class="Custom_line">

        <div class="mt-6">
            <div class="px-4 py-2 text-sm font-medium text-purple-200">Manage Claims</div>
            <div class="space-y-1">
                <a href="{{ route('admin.request-claim') }}"
                   class="flex items-center space-x-2 px-4 py-2 {{ request()->routeIs('admin.request-claim') ? 'bg-purple-900' : 'hover:bg-purple-700' }}">
                    <img src="{{ asset('images/FileClaim.png') }}" alt="report" style="width: 20px;">
                    <span>File Claim</span>
                </a>
                <a href="{{ route('admin.submitted-claims') }}"
                   class="flex items-center space-x-2 px-4 py-2 {{ request()->routeIs('admin.submitted-claims') ? 'bg-purple-900' : 'hover:bg-purple-700' }}">
                    <img src="{{ asset('images/SubmittedClaim.png') }}" alt="report" style="width: 20px;">
                    <span>Submitted Claims</span>
                </a>
                <a href="{{ route('admin.approved-claims') }}"
                   class="flex items-center space-x-2 px-4 py-2 {{ request()->routeIs('admin.approved-claims') ? 'bg-purple-900' : 'hover:bg-purple-700' }}">
                    <img src="{{ asset('images/ApprovedClaim.png') }}" alt="report" style="width: 20px;">
                    <span>Approved Claims</span>
                </a>
                <a href="{{ route('admin.rejected-claims') }}"
                   class="flex items-center space-x-2 px-4 py-2 {{ request()->routeIs('admin.rejected-claims') ? 'bg-purple-900' : 'hover:bg-purple-700' }}">
                    <img src="{{ asset('images/disagree.png') }}" alt="report" style="width: 20px;">
                    <span>Rejected Claims</span>
                </a>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-x-hidden bg-gray-50">
        <main class="main_content">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
