<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'SIMKA — FMIPA Universitas Riau')</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/logo.svg') }}?v={{ filemtime(public_path('assets/img/logo.svg')) }}">

@hasSection('meta_description')
<meta name="description" content="@yield('meta_description')">
@endif

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://api.fontshare.com">
<link href="https://api.fontshare.com/v2/css?f[]=clash-grotesk@400,500,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}">
@stack('styles')
</head>
<body>
<a href="#main" class="skip-link">Lompat ke konten utama</a>

@include('partials.navbar')

<main id="main">
  @yield('content')
</main>

@include('partials.footer')

<div class="toast-region" aria-live="polite">
  <div class="toast toast-success @if(session('sukses')) is-visible @endif" role="status">
    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
    <span data-toast-text>{{ session('sukses') }}</span>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/scripts.js') }}?v={{ filemtime(public_path('js/scripts.js')) }}"></script>
@stack('scripts')
</body>
</html>