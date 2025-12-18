@php
    $userRole = session('user_role_id'); 
@endphp

<aside class="fixed top-0 left-0 h-screen w-[260px] bg-[#343a40] text-[#c2c7d0] flex flex-col transition-all duration-300 z-50">
    <div class="flex items-center gap-2.5 p-[15px] text-[1.25rem] bg-black/10 text-white border-b border-[#4b545c]">
        <i class="fas fa-hospital-alt"></i>
        <span class="font-light">RSHP Dashboard</span>
    </div>

    <div class="flex-1 overflow-y-auto py-2.5">
        <nav>
            <ul class="list-none p-0 m-0">
                <li class="px-0">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-[15px] py-2.5 no-underline transition-colors duration-200 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : '' }}">
                        <i class="fas fa-tachometer-alt w-[30px] text-[1.1rem]"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="px-[15px] py-2.5 text-[0.8rem] font-bold text-[#6c757d] uppercase">Manajemen Data (Master)</li>
                
                @php
                    $masterMenus = [
                        ['route' => 'admin.jenis-hewan', 'icon' => 'fa-paw', 'label' => 'Jenis Hewan'],
                        ['route' => 'admin.ras-hewan.index', 'icon' => 'fa-dog', 'label' => 'Ras Hewan'],
                        ['route' => 'admin.kategori.index', 'icon' => 'fa-tags', 'label' => 'Kategori'],
                        ['route' => 'admin.kategori-klinis.index', 'icon' => 'fa-notes-medical', 'label' => 'Kategori Klinis'],
                        ['route' => 'admin.kode-tindakan-terapi.index', 'icon' => 'fa-clipboard-list', 'label' => 'Kode Tindakan & Terapi'],
                    ];
                @endphp

                @foreach($masterMenus as $menu)
                <li>
                    <a href="{{ route($menu['route']) }}" 
                       class="flex items-center px-[15px] py-2.5 no-underline transition-colors duration-200 hover:bg-white/10 hover:text-white {{ request()->routeIs($menu['route'] . '*') ? 'bg-blue-600 text-white' : '' }}">
                        <i class="fas {{ $menu['icon'] }} w-[30px] text-[1.1rem]"></i>
                        <span>{{ $menu['label'] }}</span>
                    </a>
                </li>
                @endforeach

                <li class="px-[15px] py-2.5 text-[0.8rem] font-bold text-[#6c757d] uppercase">Manajemen Pengguna</li>
                <li>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-[15px] py-2.5 no-underline transition-colors duration-200 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.users*') ? 'bg-blue-600 text-white' : '' }}">
                        <i class="fas fa-users w-[30px] text-[1.1rem]"></i>
                        <span>User Akun</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.roles.index') }}" class="flex items-center px-[15px] py-2.5 no-underline transition-colors duration-200 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.roles*') ? 'bg-blue-600 text-white' : '' }}">
                        <i class="fas fa-user-shield w-[30px] text-[1.1rem]"></i>
                        <span>Role Akses</span>
                    </a>
                </li>

                <li class="px-[15px] py-2.5 text-[0.8rem] font-bold text-[#6c757d] uppercase">Account</li>
                <li>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                       class="flex items-center px-[15px] py-2.5 no-underline transition-colors duration-200 hover:bg-white/10 hover:text-white text-red-400">
                        <i class="fas fa-sign-out-alt w-[30px] text-[1.1rem]"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>