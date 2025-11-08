<header class="bg-white !shadow-none">
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
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#F7DE0C" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-user-icon lucide-user w-8 h-8 md:w-11 md:h-11">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            <div class="text-neutral-800">
                <p class="text-lg md:text-xl font-semibold">Bienvenid@</p>
                <span class="block text-base md:text-lg text-neutral-600">Administrador</span>
            </div>

        </div>
        <div class="nav-links">
            <ul class="space-y-4 md:space-y-6">
                <li class="flex items-center gap-3 text-base md:text-lg font-medium text-neutral-900">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#F7DE0C"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-house-icon lucide-house w-6 h-6 md:w-8 md:h-8">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                        <path
                            d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg><a href="{{ url('/dashboard') }}" class="hover:text-[#372C97] transition-colors">Inicio</a>
                </li>
            </ul>
        </div>
        <div class="container-logout">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="#F7DE0C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-log-out-icon lucide-log-out">
                <path d="m16 17 5-5-5-5" />
                <path d="M21 12H9" />
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            </svg>
            <a href="{{ url('/login') }}"><span>Cerrar sesión</span></a>
        </div>
    </div>

</nav>
