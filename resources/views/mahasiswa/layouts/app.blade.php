<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'SIMKA') - FMIPA UNRI</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @stack('styles')

  <style>
    :root {
      --forest: #163300;
      --spruce: #054d28;
      --lime: #9fe870;
      --linen: #e2f6d5;
      --background: #f8faf6;
      --text: #243329;
      --muted: #718071;
      --border: #e6ecdf;
      --white: #ffffff;
    }

    * {
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: var(--background);
      color: var(--text);
    }

    a {
      text-decoration: none;
    }

    button {
      font-family: inherit;
    }

    /* NAVBAR */

    .simka-navbar {
      position: sticky;
      top: 0;
      z-index: 100;
      width: 100%;
      background: var(--white);
      border-bottom: 1px solid var(--border);
    }

    .navbar-container {
      max-width: 1200px;
      min-height: 78px;
      margin: 0 auto;
      padding: 12px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 25px;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 11px;
      flex-shrink: 0;
    }

    .brand-logo,
    .footer-logo {
      width: 43px;
      height: 43px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      background: var(--forest);
      color: var(--lime);
      font-size: 23px;
      font-weight: 800;
    }

    .brand-text {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .brand-text strong {
      color: var(--forest);
      font-size: 19px;
      font-weight: 800;
      letter-spacing: .5px;
    }

    .brand-text span {
      color: var(--muted);
      font-size: 9px;
      font-weight: 700;
      letter-spacing: .7px;
    }

    .navbar-menu {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
    }

    .navbar-link {
      padding: 10px 13px;
      border-radius: 8px;
      color: #536052;
      font-size: 12px;
      font-weight: 600;
      transition: .2s;
    }

    .navbar-link:hover {
      background: var(--linen);
      color: var(--forest);
    }

    .navbar-link.active {
      background: var(--forest);
      color: var(--lime);
    }

    .navbar-account {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-shrink: 0;
    }

    .account-avatar {
      width: 37px;
      height: 37px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: var(--linen);
      color: var(--forest);
      font-size: 14px;
      font-weight: 800;
    }

    .account-detail {
      display: flex;
      flex-direction: column;
      gap: 4px;
      min-width: 75px;
    }

    .account-detail strong {
      max-width: 125px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      color: var(--forest);
      font-size: 11px;
    }

    .account-detail span {
      color: var(--muted);
      font-size: 10px;
    }

    .logout-button {
      padding: 8px 12px;
      border: 1px solid #e7d6d2;
      border-radius: 7px;
      background: white;
      color: #a33b2e;
      font-size: 11px;
      font-weight: 700;
      cursor: pointer;
      transition: .2s;
    }

    .logout-button:hover {
      background: #fff0ed;
    }

    /* MAIN CONTENT */

    .page-main {
      min-height: calc(100vh - 280px);
    }

    .page-container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 36px 24px 55px;
    }

    .page-heading {
      margin-bottom: 25px;
    }

    .page-heading h1 {
      margin: 0 0 8px;
      color: var(--forest);
      font-size: 27px;
      font-weight: 800;
    }

    .page-heading p {
      margin: 0;
      color: var(--muted);
      font-size: 13px;
      line-height: 1.7;
    }

    .content-card {
      padding: 23px;
      border: 1px solid var(--border);
      border-radius: 13px;
      background: var(--white);
      box-shadow: 0 3px 12px rgba(22, 51, 0, .035);
    }

    /* FOOTER */
/* FOOTER */

.simka-footer {
    background: var(--forest);
    color: white;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 48px 24px;
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 60px;
}

.footer-brand {
    max-width: 390px;
}

.footer-title {
    display: block;
    margin-bottom: 14px;
    color: var(--lime);
    font-size: 25px;
    font-weight: 800;
    letter-spacing: .5px;
}

.footer-brand p {
    max-width: 350px;
    margin: 0;
    color: #d2dfc9;
    font-size: 13px;
    line-height: 1.9;
}

.footer-navigation {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 13px;
}

.footer-navigation strong {
    margin-bottom: 5px;
    color: var(--lime);
    font-size: 13px;
    font-weight: 700;
}

.footer-navigation a {
    color: #d2dfc9;
    font-size: 12px;
    transition: .2s;
}

.footer-navigation a:hover {
    color: var(--lime);
    transform: translateX(3px);
}

.footer-bottom {
    padding: 18px 24px;
    border-top: 1px solid rgba(255, 255, 255, .13);
    color: #c4d3bd;
    font-size: 11px;
    text-align: center;
}

@media (max-width: 600px) {
    .footer-container {
        grid-template-columns: 1fr 1fr;
        gap: 35px 20px;
        padding: 35px 20px;
    }

    .footer-brand {
        grid-column: 1 / -1;
    }
}

    /* RESPONSIVE */

    @media (max-width: 900px) {
      .navbar-container {
        flex-wrap: wrap;
        gap: 15px;
      }

      .navbar-menu {
        order: 3;
        width: 100%;
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 3px;
      }

      .navbar-account {
        margin-left: auto;
      }
    }

    .account-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      color: inherit;
      text-decoration: none;
      border-radius: 8px;
    }

    .account-profile:hover .account-detail strong {
      color: var(--spruce);
    }

    .account-profile:hover .account-avatar {
      background: var(--lime);
    }

    @media (max-width: 600px) {
      .navbar-container {
        padding: 12px 16px;
      }

      .brand-logo {
        width: 38px;
        height: 38px;
      }

      .brand-text strong {
        font-size: 17px;
      }

      .account-detail {
        display: none;
      }

      .navbar-menu {
        gap: 3px;
      }

      .navbar-link {
        padding: 9px 10px;
        font-size: 11px;
        white-space: nowrap;
      }

      .page-container {
        padding: 25px 16px 40px;
      }

      .page-heading h1 {
        font-size: 22px;
      }

      .footer-container {
        flex-direction: column;
        gap: 28px;
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>

  {{-- Navbar --}}
  @include('mahasiswa.partials.navbar')

  {{-- Konten halaman --}}
  <main class="page-main">
    <div class="page-container">
      @yield('content')
    </div>
  </main>

  {{-- Footer --}}
  @include('mahasiswa.partials.footer')

  @stack('scripts')

</body>

</html>