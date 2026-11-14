@extends('mahasiswa.layouts.app')

@section('title', 'Profil Mahasiswa')
@section('page-title', 'Profil Mahasiswa')

@section('content')

  @php
    $user = auth()->user();
  @endphp

  <style>
    .profile-page {
      max-width: 1000px;
    }

    .profile-intro {
      margin-bottom: 25px;
    }

    .profile-intro h1 {
      color: #163300;
      font-size: 22px;
      font-weight: 750;
      margin: 0 0 7px;
    }

    .profile-intro p {
      color: #718071;
      font-size: 13px;
      line-height: 1.7;
      margin: 0;
    }

    .profile-identity {
      display: flex;
      align-items: center;
      gap: 18px;
      padding: 23px;
      background: #ffffff;
      border: 1px solid #e6ecdf;
      border-radius: 12px;
      margin-bottom: 25px;
    }

    .profile-avatar-large {
      width: 65px;
      height: 65px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #e2f6d5;
      color: #054d28;
      font-size: 25px;
      font-weight: 800;
      flex-shrink: 0;
    }

    .identity-info h2 {
      color: #163300;
      font-size: 17px;
      font-weight: 750;
      margin: 0 0 6px;
    }

    .identity-info p {
      color: #718071;
      font-size: 12px;
      margin: 0;
    }

    .profile-section {
      background: #ffffff;
      border: 1px solid #e6ecdf;
      border-radius: 12px;
      padding: 23px;
      margin-bottom: 20px;
    }

    .profile-section-heading {
      padding-bottom: 17px;
      border-bottom: 1px solid #e6ecdf;
      margin-bottom: 20px;
    }

    .profile-section-heading h2 {
      color: #163300;
      font-size: 16px;
      font-weight: 750;
      margin: 0 0 5px;
    }

    .profile-section-heading p {
      color: #718071;
      font-size: 12px;
      margin: 0;
      line-height: 1.6;
    }

    .profile-fields {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      column-gap: 30px;
      row-gap: 22px;
    }

    .profile-field label {
      display: block;
      color: #718071;
      font-size: 11px;
      margin-bottom: 7px;
    }

    .profile-field div {
      color: #243329;
      font-size: 13px;
      font-weight: 600;
      line-height: 1.6;
      overflow-wrap: anywhere;
    }

    .field-placeholder {
      color: #9aa598 !important;
      font-weight: 400 !important;
      font-style: italic;
    }

    .profile-note {
      background: #f3f9ef;
      border-left: 3px solid #639b43;
      padding: 13px 15px;
      border-radius: 5px;
      color: #42613d;
      font-size: 12px;
      line-height: 1.7;
      margin-top: 20px;
    }

    .account-action {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }

    .account-action h3 {
      color: #243329;
      font-size: 14px;
      font-weight: 700;
      margin: 0 0 5px;
    }

    .account-action p {
      color: #718071;
      font-size: 12px;
      line-height: 1.6;
      margin: 0;
    }

    .logout-button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 10px 15px;
      border: 1px solid #f0cccc;
      border-radius: 7px;
      background: #fff7f7;
      color: #b42323;
      font-family: inherit;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: .2s;
      white-space: nowrap;
    }

    .logout-button:hover {
      background: #feecec;
      border-color: #e9aaaa;
    }

    @media (max-width: 600px) {

      .profile-identity,
      .account-action {
        align-items: flex-start;
      }

      .profile-identity {
        padding: 18px;
      }

      .profile-fields {
        grid-template-columns: 1fr;
        row-gap: 18px;
      }

      .profile-section {
        padding: 18px;
      }

      .account-action {
        flex-direction: column;
      }
    }
  </style>

  <div class="profile-page">

    <div class="profile-intro">
      <h1>Profil Saya</h1>
      <p>
        Informasi identitas dan akun yang digunakan
        dalam layanan SIMKA FMIPA Universitas Riau.
      </p>
    </div>

    {{-- Identitas mahasiswa --}}
    <section class="profile-identity">
      <div class="profile-avatar-large">
        {{ strtoupper(substr($user->name ?? 'M', 0, 1)) }}
      </div>

      <div class="identity-info">
        <h2>{{ $user->name ?? 'Nama belum tersedia' }}</h2>
        <p>{{ $user->email ?? 'Email belum tersedia' }}</p>
      </div>
    </section>

    {{-- Informasi akademik --}}
    <section class="profile-section">
      <div class="profile-section-heading">
        <h2>Informasi Akademik</h2>
        <p>Data identitas mahasiswa yang digunakan untuk pengajuan surat.</p>
      </div>

      <div class="profile-fields">
        <div class="profile-field">
          <label>Nama Lengkap</label>
          <div>{{ $user->name ?? 'Belum tersedia' }}</div>
        </div>

        <div class="profile-field">
          <label>Nomor Induk Mahasiswa (NIM)</label>
          <div class="field-placeholder">Belum tersedia</div>
        </div>

        <div class="profile-field">
          <label>Program Studi</label>
          <div class="field-placeholder">Belum tersedia</div>
        </div>

        <div class="profile-field">
          <label>Fakultas</label>
          <div>Fakultas Matematika dan Ilmu Pengetahuan Alam</div>
        </div>

        <div class="profile-field">
          <label>Semester</label>
          <div class="field-placeholder">Belum tersedia</div>
        </div>

        <div class="profile-field">
          <label>Status Mahasiswa</label>
          <div class="field-placeholder">Belum tersedia</div>
        </div>
      </div>

      <div class="profile-note">
        Data akademik akan ditampilkan berdasarkan informasi
        yang tersimpan pada sistem setelah integrasi database selesai.
      </div>
    </section>

    {{-- Informasi akun --}}
    <section class="profile-section">
      <div class="profile-section-heading">
        <h2>Informasi Akun</h2>
        <p>Informasi yang digunakan untuk masuk ke SIMKA.</p>
      </div>

      <div class="profile-fields">
        <div class="profile-field">
          <label>Alamat Email</label>
          <div>{{ $user->email ?? 'Belum tersedia' }}</div>
        </div>

        <div class="profile-field">
          <label>Status Verifikasi Email</label>
          <div>
            @if ($user->email_verified_at)
              <span style="color: #287443;">Terverifikasi</span>
            @else
              <span style="color: #a16207;">Belum terverifikasi</span>
            @endif
          </div>
        </div>
      </div>
    </section>

    {{-- Keluar dari akun --}}
    <section class="profile-section">
      <div class="account-action">
        <div>
          <h3>Keluar dari Akun</h3>
          <p>
            Akhiri sesi login SIMKA pada perangkat ini.
          </p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <button type="submit" class="logout-button">
            Keluar dari Akun
          </button>
        </form>
      </div>
    </section>

  </div>

@endsection