<!DOCTYPE html>
<html lang="en" data-sidenav-view="{{ $sidenav ?? 'sm' }}">

<head>
    @include('layouts.shared/title-meta', ['title' => $title])
    @include('layouts.shared/head-css')
    @yield('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="referrer" content="no-referrer">
</head>

<body class="min-h-screen">

    <div class="wrapper">
        <div class="page-content">

            @include('layouts.shared/topbar')

            <main class="flex-grow">


                @include('layouts.shared/page-title', [
                    'title' => $title,
                    'sub_title' => $sub_title,
                ])

                @yield('content')

            </main>

        </div>

    </div>

    @include('layouts.shared/footer-scripts')


</body>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const closeBtn = document.getElementById('close-menu');
    const navMenu = document.getElementById('nav-menu');
    const overlay = document.getElementById('overlay');

    function openMenu() {
        navMenu.classList.add('show');
        overlay.classList.add('show');
    }

    function closeMenu() {
        navMenu.classList.remove('show');
        overlay.classList.remove('show');
    }

    menuToggle.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);
</script>

</html>
