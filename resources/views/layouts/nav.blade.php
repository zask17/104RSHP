<nav>
    <div class="logo-container">
        <a href="https://ibb.co.com/qLLWXNZ3">
            <img src="https://i.ibb.co.com/Fkk0N5PL/LOGO-UNAIR.png" alt="LOGO-UNAIR" class="logo">
        </a>

        <a href="{{ url('/') }}" class="@if(request()->is('home')) @endif">
            <img src="https://i.ibb.co.com/5ZG5d9L/rshp.png" alt="Logo RSHP" class="logo">
        </a>
    </div>

    <ul>
        {{-- Menu Utama --}}
        <li>
            <a href="{{ url('/') }}" class="@if(request()->is('home')) active @endif">
                Home <span class="underline"></span></a>
        </li>


        <li>
            <a href="{{ url('struktur-organisasi') }}" class="@if(request()->is('struktur-organisasi')) active @endif">
                Struktur Organisasi <span class="underline"></span>
            </a>
        </li>

        <li>
            <a href="{{ url('layanan-rshp') }}" class="@if(request()->is('layanan-rshp')) active @endif">
                Layanan RSHP <span class="underline"></span>
            </a>
        </li>

        <li>
            <a href="{{ url('visi-misi') }}" class="@if(request()->is('visi-misi')) active @endif">
                Visi Misi & Tujuan <span class="underline"></span>
            </a>
        </li>

        @guest
            {{-- Login jika belum login --}}
            <li>
                <a href="{{ route('login') }}" class="@if(request()->is('login')) active @endif">
                    Login <span class="underline"></span>
                </a>
            </li>
            

        @else
            {{-- Jika sudah Login, tampilkan Dashboard dan Logout sebagai menu horizontal --}}

            {{-- Tombol Dashboard Berdasarkan Role --}}
            @php
                $dashboardRoute = '';
                $dashboardName = '';

                // **PERBAIKAN**: Ambil Role ID aktif dari session yang sudah disimpan di LoginController.
                // Session key 'user_role' berisi Role ID (1, 2, 3, 4, dst.)
                $userRole = (string) session('user_role');

                switch ($userRole) {
                    case '1': // Administrator
                        $dashboardRoute = route('admin.dashboard');
                        $dashboardName = 'Dashboard Admin';
                        break;
                    case '2': // Dokter
                        $dashboardRoute = route('dokter.dashboard');
                        $dashboardName = 'Dashboard Dokter';
                        break;
                    case '3': // Perawat
                        $dashboardRoute = route('perawat.dashboard');
                        $dashboardName = 'Dashboard Perawat';
                        break;
                    case '4': // Resepsionis
                        $dashboardRoute = route('resepsionis.dashboard');
                        $dashboardName = 'Dashboard Resepsionis';
                        break;
                    default: // Pemilik atau Role lain yang tidak spesifik
                        // Mengikuti logika default di LoginController yang mengarahkan ke pemilik.dashboard
                        $dashboardRoute = route('pemilik.dashboard');
                        $dashboardName = 'Dashboard Pemilik';
                        break;
                }
            @endphp

            <li>
                <a href="{{ $dashboardRoute }}" class="@if(request()->is('*dashboard*')) active @endif">
                    {{ $dashboardName }} <span class="underline"></span>
                </a>
            </li>

            {{-- Logout --}}
            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout <span class="underline"></span>
                </a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endguest

    </ul>
</nav>