@php
  $user = auth()->user();
@endphp

<header class="simka-navbar">
  <div class="navbar-container">

    {{-- Logo SIMKA --}}
    <a href="{{ url('/mahasiswa/dashboard') }}" class="navbar-brand">
      <div class="brand-logo">S</div>

      <div class="brand-text">
        <strong>SIMKA</strong>
        <span>FMIPA UNIVERSITAS RIAU</span>
      </div>
    </a>

    {{-- Navigasi --}}
    <nav class="navbar-menu">
      <a href="{{ url('/mahasiswa/dashboard') }}"
        class="navbar-link {{ request()->is('mahasiswa/dashboard') ? 'active' : '' }}">
        Dashboard
      </a>

      <a href="{{ url('/mahasiswa/ajukan') }}"
        class="navbar-link {{ request()->is('mahasiswa/ajukan') ? 'active' : '' }}">
        Ajukan Surat
      </a>

      <a href="{{ url('/mahasiswa/riwayat') }}"
        class="navbar-link {{ request()->is('mahasiswa/riwayat') ? 'active' : '' }}">
        Riwayat
      </a>

      <a href="{{ url('/mahasiswa/profil') }}"
        class="navbar-link {{ request()->is('mahasiswa/profil') ? 'active' : '' }}">
        Profil
      </a>
    </nav>

    {{-- Akun Mahasiswa --}}
    <div class="navbar-account">

      <a href="{{ url('/mahasiswa/profil') }}" class="account-profile">
        <div class="account-avatar">
          {{ strtoupper(substr($user->name ?? 'M', 0, 1)) }}
        </div>

        <div class="account-detail">
          <strong>{{ $user->name ?? 'Mahasiswa' }}</strong>
          <span>{{ $user->nim ?? 'NIM belum tersedia' }}</span>
        </div>
      </a>

    </div>

  </div>
</header>