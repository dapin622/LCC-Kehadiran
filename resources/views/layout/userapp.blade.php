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
    .top-navbar {
    height: 60px;
    background-color: #fff;
    border-bottom: 1px solid #e9ecef;
    position: fixed;
    top: 0;
    left: 240px;
    right: 0;
    z-index: 1030;
    padding: 0 20px;
    }

    .content {
    margin-left: 240px;
    padding: 20px;
    padding-top: 80px;
    }

    .dropdown-menu {
    animation: fadeIn 0.15s ease-in-out;
    }

    @keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
    }
    .user-trigger {
        display: flex;
        align-items: center;
        gap: 15px;             
        text-decoration: none;
    }

    .user-name {
        font-size: 16px;      
        font-weight: 600;
        color: #212529;
        margin-right: 14px;
    }

    .user-avatar {
        width: 36px;          
        height: 36px;
        border-radius: 50%;
        border: 1px solid #dee2e6;
        cursor: pointer;
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
        <a href="{{ route('user.dashboard') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <span class="iconify me-2" data-icon="mdi:home" style="font-size: 20px;"></span> Dashboard
        </a>
    </li>

    <li class="nav-item mb-2">
        <a href="{{ route('user.absensi') }}" class="nav-link {{ request()->routeIs('user.absensi') ? 'active' : '' }}">
            <span class="iconify me-2" data-icon="mdi:calendar" style="font-size: 20px;"></span> Absensi
        </a>
    </li>

    <li class="nav-item mb-2">
    <a href="{{ route('user.riwayat') }}" class="nav-link {{ request()->routeIs('user.riwayat') ? 'active' : '' }}">
        <span class="iconify me-2" data-icon="mdi:calendar-check" style="font-size: 20px;"></span> Riwayat Absensi
    </a>
</li>

        <!-- <li class="nav-item mb-2">
          <a href="#" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.member') ? 'active' : '' }}">
            <span class="iconify me-2" data-icon="mdi:account-group" style="font-size: 20px;"></span> Siswa
          </a>
        </li> -->
      </ul>

      <!-- <div class="mt-auto">
    <form class="logout" method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" 
                class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center"
                onclick="return confirm('Apakah Anda Ingin Logout?')">
            <span class="iconify me-2" data-icon="mdi:logout" style="font-size: 20px;"></span> Logout
        </button>
    </form>
    </div> -->

    </div>

    <!-- Top Navbar -->
<!-- Top Navbar -->
<div class="top-navbar d-flex align-items-center justify-content-end">

  <div class="dropdown">
    <!-- TRIGGER: AVATAR -->
    <a href="#"
       class="text-decoration-none"
       data-bs-toggle="dropdown"
       aria-expanded="false">

       <span class="user-name"> {{ Auth::user()->name }} </span>
       
      <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
           class="user-avatar"
           width="36" height="36"
           style="cursor:pointer;">
           
    </a>

    <!-- DROPDOWN STYLE CHROME -->
    <div class="dropdown-menu dropdown-menu-end p-3 shadow"
         style="width: 260px; border-radius: 12px;">

      <div class="text-center">
        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
             class="rounded-circle mb-2"
             width="64" height="64">

        <div class="fw-semibold">
          {{ Auth::user()->name }}
        </div>

       <div class="text-muted small">
  {{ optional(Auth::user()->member?->school)->name ?? 'Sekolah belum diatur' }}
</div>

        <br>
        <div class="text-muted small">
          {{ Auth::user()->email }}
        </div>
      </div>

      <hr class="my-3">

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" 
                class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center"
                onclick="return confirm('Apakah Anda Ingin Logout?')">
            <span class="iconify me-2" data-icon="mdi:logout" style="font-size: 20px;"></span> Logout
        </button>
      </form>

    </div>
  </div>

</div>


    <div class="content flex-grow-1">
      @yield('content')
    </div>
  </div>

  @stack('scripts')
  
<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
