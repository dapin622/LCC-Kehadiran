<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title')</title>
  <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Inter', sans-serif;
    }
    .sidebar {
      width: 240px;
      min-height: 100vh;
    }
    .sidebar .nav-link {
      color: #333;
      border-radius: 6px;
      padding: 10px 15px;
    }
    .sidebar .nav-link.active, 
    .sidebar .nav-link:hover {
      background-color: #f1f3f5;
      font-weight: 600;
    }
    .content {
      margin-left: 240px;
      padding: 20px;
    }
    .card {
      border: 1px solid #e9ecef;
      border-radius: 10px;
    }
    .card h6 {
      font-size: 0.9rem;
      font-weight: 600;
    }
    .party-card {
      font-size: 0.85rem;
    }
    .party-card img {
      height: 36px;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-white border-end position-fixed p-3 d-flex flex-column">
      <div class="d-flex align-items-center mb-4">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Logo_of_People%27s_Consultative_Assembly_Indonesia.png/625px-Logo_of_People%27s_Consultative_Assembly_Indonesia.png" alt="Logo" class="me-2" style="width: 45px; height:45px;">
        <span class="fw-bold"> LCC MPR KEHADIRAN</span>
      </div>
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center  {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <span class="iconify me-2" data-icon="mdi:home" style="font-size: 20px;"></span> Dashboard
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="#" class="nav-link">
        <span class="iconify me-2" data-icon="mdi:calendar" style="font-size: 20px;"></span> Event
          </a>

        </li>
        <li class="nav-item mb-2">
          <a href="{{ route('admin.member') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.member') ? 'active' : '' }}">
            <span class="iconify me-2" data-icon="mdi:account-group" style="font-size: 20px;"></span> Siswa
          </a>
        </li>
          <li class="nav-item mb-2">
          <a href="{{ route('admin.sekolah') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.sekolah') ? 'active' : '' }}">
            <span class="iconify me-2" data-icon="mdi:school" style="font-size: 20px;"></span> Sekolah
          </a>
        </li>
      </ul>

      <div class="mt-auto">
    <form class="logout" method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" 
                class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center"
                onclick="return confirm('Apakah Anda Ingin Logout?')">
            <span class="iconify me-2" data-icon="mdi:logout" style="font-size: 20px;"></span> Logout
        </button>
    </form>
    </div>

    </div>

    <div class="content flex-grow-1">
      @yield('content')
    </div>
  </div>

  @stack('scripts')
  
  <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

</body>

</html>
