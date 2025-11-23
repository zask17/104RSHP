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
            <li>
                <a href="{{ route('login') }}" class="@if(request()->is('login')) active @endif">
                    Login <span class="underline"></span>
                </a>
            </li>
            {{-- class="dropdown-item-kustom">{{ __('Login') }}</a> --}}


            {{-- Login/Register jadi Dropdown
            <li class="dropdown-kustom">
                <a href="#" onclick="return false;" class="dropdown-toggle-kustom">
                    Masuk <span class="underline"></span>
                </a>

                {{-- Konten Dropdown (Perlu CSS Tambahan) --}}
                {{-- <div class="dropdown-menu-kustom">
                    @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="dropdown-item-kustom">{{ __('Login') }}</a>
                    @endif
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="dropdown-item-kustom">{{ __('Register') }}</a>
                    @endif
                </div>
            </li> --}}


        @else
            {{-- Sudah Login --}}
            <li class="dropdown-kustom">
                <a id="navbarDropdown" href="#" onclick="return false;" class="dropdown-toggle-kustom">
                    {{ ucwords(Auth::user()->nama) }} <span class="underline"></span>
                </a>

                <div class="dropdown-menu-kustom" aria-labelledby="navbarDropdown">

                    {{-- Dashboard Berdasarkan Role --}}
                    @if(Auth::user()->role === 'admin')
                        <a class="dropdown-item-kustom" href="{{ route('admin.dashboard') }}">
                            Dashboard Admin
                        </a>

                    {{-- @elseif(Auth::user()->role === 'dokter')
                        <a class="dropdown-item-kustom" href="{{ route('dokter.dashboard') }}">
                            Dashboard Dokter
                        </a> --}}

                    @elseif(Auth::user()->role === 'perawat')
                        <a class="dropdown-item-kustom" href="{{ route('perawat.dashboard') }}">
                            Dashboard Perawat
                        </a>

                    @elseif(Auth::user()->role === 'resepsionis')
                        <a class="dropdown-item-kustom" href="{{ route('resepsionis.dashboard') }}">
                            Dashboard Resepsionis
                        </a>
                    @endif

                    {{-- Logout --}}
                    <a class="dropdown-item-kustom" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        @endguest
    </ul>
</nav>