@php
    $user = auth()->user();
    $role =$user?->role;

    $homeUrl = match ($role) {
        'mahasiswa' => route('mahasiswa.dashboard'),
        'staf' => route('staf.index'),
        'pimpinan' => route('pimpinan.index'),
        default => route('login'),
    };

    $inisial =$user
        ? collect(explode(' ', $user->nama))->filter()->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('')
        : 'TM';
@endphp

<header class="position-sticky top-0 z-50 border-bottom border-fog bg-white">
  <div class="mx-auto d-flex h-16 max-w-1200 align-items-center justify-content-between gap-4 px-5">
    <a href="{{ $homeUrl }}" class="d-flex align-items-center gap-2-5" aria-label="Beranda SIMKA">
      <span class="d-grid h-9 w-9 place-items-center rounded-10 bg-forest fs-7 fw-black text-lime">S</span>
      <span class="d-flex flex-column lh-1">
        <span class="fs-7 fw-black tracking-20 text-forest">SIMKA</span>
        <span class="mt-1 fs-8 fw-semibold tracking-12 text-slate">FMIPA UNRI</span>
      </span>
    </a>

    <nav aria-label="Navigasi utama" class="d-none align-items-center gap-6 d-md-flex">
      @if ($role === 'mahasiswa')
        <a @if(request()->routeIs('mahasiswa.dashboard')) aria-current="page" @endif
           class="fs-7 {{ request()->routeIs('mahasiswa.dashboard') ? 'fw-semibold text-forest' : 'fw-medium text-slate hover-text-forest' }}"
           href="{{ route('mahasiswa.dashboard') }}">Dashboard</a>
        <a @if(request()->routeIs('mahasiswa.ajukan.*')) aria-current="page" @endif
           class="fs-7 {{ request()->routeIs('mahasiswa.ajukan.*') ? 'fw-semibold text-forest' : 'fw-medium text-slate hover-text-forest' }}"
           href="{{ route('mahasiswa.ajukan.create') }}">Ajukan Surat</a>
        <a @if(request()->routeIs('mahasiswa.riwayat.*')) aria-current="page" @endif
           class="fs-7 {{ request()->routeIs('mahasiswa.riwayat.*') ? 'fw-semibold text-forest' : 'fw-medium text-slate hover-text-forest' }}"
           href="{{ route('mahasiswa.riwayat.index') }}">Riwayat</a>
        <a @if(request()->routeIs('mahasiswa.profil.*')) aria-current="page" @endif
           class="fs-7 {{ request()->routeIs('mahasiswa.profil.*') ? 'fw-semibold text-forest' : 'fw-medium text-slate hover-text-forest' }}"
           href="{{ route('mahasiswa.profil.edit') }}">Profil</a>
      @elseif ($role === 'staf')
        <a aria-current="page" class="fs-7 fw-semibold text-forest" href="{{ route('staf.index') }}">Antrean Validasi</a>
      @elseif ($role === 'pimpinan')
        <a aria-current="page" class="fs-7 fw-semibold text-forest" href="{{ route('pimpinan.index') }}">Menunggu Tanda Tangan</a>
      @else
        <a @if(request()->routeIs('verifikasi.*')) aria-current="page" @endif
           class="fs-7 {{ request()->routeIs('verifikasi.*') ? 'fw-semibold text-forest' : 'fw-medium text-slate hover-text-forest' }}"
           href="{{ route('verifikasi.index') }}">Cek Keaslian Surat</a>
      @endif
    </nav>

    <div class="d-flex align-items-center gap-2">
      @auth
        @if ($role === 'mahasiswa')
          <a href="{{ route('mahasiswa.profil.edit') }}" class="d-flex align-items-center gap-2 rounded-pill py-1 pr-3 hover-bg-fog">
            <span class="d-grid h-9 w-9 place-items-center rounded-pill bg-forest fs-8 fw-bold text-paper">{{ $inisial }}</span>
            <span class="d-none flex-column lh-sm d-sm-flex">
              <span class="fs-7 fw-medium text-forest">{{ $user->nama }}</span>
              <span class="num-tab fs-8 text-slate">{{ $user->mahasiswa?->nim ?? '-' }}</span>
            </span>
          </a>
        @else
          <span class="d-flex align-items-center gap-2 rounded-pill py-1 pr-3 fs-7 fw-medium text-forest">
            <span class="d-grid h-9 w-9 place-items-center rounded-pill bg-forest fs-8 fw-bold text-paper">{{ $inisial }}</span>
            <span class="d-none flex-column lh-sm d-sm-flex">
              <span class="d-block lh-sm">{{ $user->nama }}</span>
              <span class="d-block fs-8 fw-normal text-slate lh-sm">{{ $role === 'staf' ? 'Staf kemahasiswaan' : 'Pimpinan fakultas' }}</span>
            </span>
          </span>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="d-none d-md-inline">
          @csrf
          <button type="submit" class="btn btn-secondary min-h-36 px-4 py-1-5 fs-8">Keluar</button>
        </form>

        <button data-menu class="d-grid h-11 w-11 place-items-center rounded-pill text-forest hover-bg-fog d-md-none" aria-expanded="false" aria-controls="menuMobile" aria-label="Buka menu">
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
      @else
        <a href="{{ route('login') }}" @if(request()->routeIs('login')) aria-current="page" @endif class="btn btn-secondary min-h-36 px-5 py-1-5 fs-7">Masuk</a>
      @endauth
    </div>
  </div>

  @auth
  <div id="menuMobile" class="mobile-menu border-top border-fog bg-white px-5 pb-4 pt-2 d-md-none" hidden>
    <nav class="d-grid" aria-label="Navigasi mobile">
      @if ($role === 'mahasiswa')
        <a class="rounded-10 px-3 py-2-5 fs-6 {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-linen fw-semibold text-forest' : 'fw-medium' }}" href="{{ route('mahasiswa.dashboard') }}">Dashboard</a>
        <a class="rounded-10 px-3 py-2-5 fs-6 {{ request()->routeIs('mahasiswa.ajukan.*') ? 'bg-linen fw-semibold text-forest' : 'fw-medium' }}" href="{{ route('mahasiswa.ajukan.create') }}">Ajukan Surat</a>
        <a class="rounded-10 px-3 py-2-5 fs-6 {{ request()->routeIs('mahasiswa.riwayat.*') ? 'bg-linen fw-semibold text-forest' : 'fw-medium' }}" href="{{ route('mahasiswa.riwayat.index') }}">Riwayat</a>
        <a class="rounded-10 px-3 py-2-5 fs-6 {{ request()->routeIs('mahasiswa.profil.*') ? 'bg-linen fw-semibold text-forest' : 'fw-medium' }}" href="{{ route('mahasiswa.profil.edit') }}">Profil</a>
      @elseif ($role === 'staf')
        <a class="rounded-10 bg-linen px-3 py-2-5 fs-6 fw-semibold text-forest" href="{{ route('staf.index') }}">Antrean Validasi</a>
        <p class="px-3 pt-2 fs-8 text-slate">Masuk sebagai {{ $user->nama }} — staf kemahasiswaan</p>
      @elseif ($role === 'pimpinan')
        <a class="rounded-10 bg-linen px-3 py-2-5 fs-6 fw-semibold text-forest" href="{{ route('pimpinan.index') }}">Menunggu Tanda Tangan</a>
        <p class="px-3 pt-2 fs-8 text-slate">Masuk sebagai {{ $user->nama }}</p>
      @endif

      <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button type="submit" class="w-100 rounded-10 px-3 py-2-5 text-start fs-6 fw-medium text-slate hover-bg-fog">Keluar</button>
      </form>
    </nav>
  </div>
  @endauth
</header>