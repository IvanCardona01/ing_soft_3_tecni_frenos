<header class="absolute top-0 left-0 w-full bg-transparent !shadow-none">
    <div class="flex flex-row justify-between items-center w-full ">
        <div> <a href="{{ url('/dashboard') }}"><img src="{{ asset('images/logo1.png') }}" alt=""
                    class="w-40 h-16 ml-4 md:w-[384px] md:h-[116px] md:ml-[30px]"></a></div>

        <button id="menu-toggle"
            class="burger w-12 h-12 m-4 flex items-center justify-center md:w-[70px] md:h-[70px] md:m-[49px]"><svg
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#372C97" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-menu-icon lucide-menu w-6 h-6 md:w-10 md:h-10">
                <path d="M4 12h16" />
                <path d="M4 18h16" />
                <path d="M4 6h16" />
            </svg></button>
    </div>
</header>
<div id="overlay" class="overlay"></div>
<nav id="nav-menu" class="nav-menu">
    <button id="close-menu" class="close-btn w-10 h-10 flex items-center justify-center md:w-12 md:h-12"><svg
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x w-6 h-6 md:w-8 md:h-8">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </svg></button>
    <div class="nav-content">
        <div class="user-info flex items-center gap-3 md:gap-6">
            <div class="pl-1">
                <img src="{{ asset('icons/profile-icon.svg') }}" alt="Perfil"
                    class="w-12 h-12 md:w-14 md:h-14 object-contain">
            </div>
            <div class="text-neutral-800">
                <p class="text-lg md:text-xl font-semibold">Bienvenid@</p>
                <span class="block text-base md:text-lg text-neutral-600">Administrador</span>
            </div>

        </div>
        <div class="nav-links">
            <ul class="space-y-4 md:space-y-6">
                <li class="flex">
                    <a href="{{ url('/dashboard') }}"
                        class="ml-5 inline-flex items-center text-lg md:text-xl font-medium text-neutral-900 rounded-xl hover:bg-[#372C97]/10 hover:text-[#372C97] transition-colors px-1 py-2 w-full">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('icons/home-icon.svg') }}" alt="Inicio"
                                class="w-10 h-10 md:w-12 md:h-12 object-contain">
                            <span>Inicio</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="container-logout flex">
            <a href="{{ url('/') }}"
                class="inline-flex items-center text-lg md:text-xl font-semibold text-[#372C97] rounded-xl hover:bg-[#372C97]/10 transition-colors px-1 py-2 w-full">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('icons/exit-icon.svg') }}" alt="Cerrar sesión"
                        class="w-12 h-12 md:w-14 md:h-14 object-contain">
                    <span>Cerrar sesión</span>
                </div>
            </a>
        </div>
    </div>

</nav>
