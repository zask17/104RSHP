<aside class="sidebar flex-shrink-0">
    <div class="p-6">
        <h1 class="text-2xl font-bold text-green-400">RSHP Admin Panel</h1>
        <p class="text-sm text-gray-400 mt-1">Role: {{ session('user_role_name', 'Administrator') }}</p>
    </div>
    
    <nav class="mt-6 px-4">
        <!-- DASHBOARD -->
        <a href="{{ route('admin.dashboard') }}" 
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt w-6"></i>
            <span class="ml-3">Dashboard</span>
        </a>

        <!-- DATA MASTER -->
        @php
            // Untuk mengaktifkan menu Data Master jika salah satu submenu-nya aktif
            $dataMasterRoutes = [
                'admin.datamaster', 'admin.jenis-hewan.*', 'admin.ras-hewan.*', 
                'admin.kategori.*', 'admin.kategori-klinis.*', 'admin.kode-tindakan-terapi.*'
            ];
            $isDataMasterActive = collect($dataMasterRoutes)->some(fn($route) => request()->routeIs($route));
        @endphp
        <a href="{{ route('admin.datamaster') }}" 
           class="{{ $isDataMasterActive ? 'active' : '' }}">
            <i class="fas fa-database w-6"></i>
            <span class="ml-3">Data Master</span>
        </a>

        <!-- MANAJEMEN DATA PASIEN & PEMILIK -->
        <a href="{{ route('admin.pets.index') }}" 
           class="{{ request()->routeIs('admin.pets.*') ? 'active' : '' }}">
            <i class="fas fa-cat w-6"></i>
            <span class="ml-3">Data Pasien (Pets)</span>
        </a>
        <a href="{{ route('admin.pemilik.index') }}" 
           class="{{ request()->routeIs('admin.pemilik.*') ? 'active' : '' }}">
            <i class="fas fa-user-friends w-6"></i>
            <span class="ml-3">Data Pemilik</span>
        </a>

        <!-- MANAJEMEN USER & ROLE -->
        <a href="{{ route('admin.users.index') }}" 
           class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users-cog w-6"></i>
            <span class="ml-3">Manajemen User</span>
        </a>
        <a href="{{ route('admin.roles.index') }}" 
           class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="fas fa-user-shield w-6"></i>
            <span class="ml-3">Manajemen Role</span>
        </a>

        <!-- LOGOUT -->
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="mt-8 text-red-400 hover:bg-red-700 hover:text-white">
            <i class="fas fa-sign-out-alt w-6"></i>
            <span class="ml-3">Logout</span>
        </a>
    </nav>
</aside>



