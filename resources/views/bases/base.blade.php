<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>

    <!-- Styles / Scripts -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>

<body>
    <header>
        <div class="container">
            <a href="{{ route('home') }}"><img src="{{ asset('img/logoNetflist.png') }}" alt=""
                    id="logo"></a>
            
            <!-- Desktop Navigation -->
            @include('bases.nav')
            
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <span class="burger-line"></span>
                <span class="burger-line"></span>
                <span class="burger-line"></span>
            </button>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" onclick="closeMobileMenu()"></div>

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/logoNetflist.png') }}" alt="Netflist" class="mobile-menu-logo">
            </a>
        </div>
        
        <nav class="mobile-menu-nav">
            <a href="{{ route('populartitles') }}">Populaires</a>
            <a href="{{ route('bestratedmovies') }}">Mieux notés</a>
            
            @auth
                <a href="{{ route('home') }}">Ma watchlist</a>
                <a href="{{ route('logout') }}" id="register">Se déconnecter</a>
            @else
                <a href="{{ route('login') }}">Se connecter</a>
                <a href="{{ route('register') }}" id="register">S'inscrire</a>
            @endauth
        </nav>
        
        <div class="mobile-menu-footer">
            <p>&copy; {{ date('Y') }} Netflist. Tous droits réservés.</p>
        </div>
    </div>
    <div class="container" id="info-container">
        <form role="search" id="searchForm" method="GET" action="{{ route('search')}}">
            <label for="search">Recherche d'un film</label>
            <input id="search" type="search" placeholder="Rechercher..." autofocus required name="search" />
            <button type="submit"><i class='bxr  bx-search'></i></button>
        </form>

        {{-- Gestion des messages d'erreurs ou de validation --}}
        @session('success')
            <span class="alert success"><i class='bxr  bx-check-circle'></i>{{ session('success') }}</span>
        @endsession
        @session('error')
            <span class="alert error"><i class='bxr  bx-alert-circle'></i> {{ session('error') }}</span>
        @endsession

    </div>
    @yield('content')

    <script>
        function toggleMobileMenu() {
            const toggle = document.querySelector('.mobile-menu-toggle');
            const menu = document.querySelector('.mobile-menu');
            const overlay = document.querySelector('.mobile-menu-overlay');
            
            toggle.classList.toggle('active');
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Empêcher le scroll du body quand le menu est ouvert
            if (menu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }
        
        function closeMobileMenu() {
            const toggle = document.querySelector('.mobile-menu-toggle');
            const menu = document.querySelector('.mobile-menu');
            const overlay = document.querySelector('.mobile-menu-overlay');
            
            toggle.classList.remove('active');
            menu.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Fermer le menu quand on clique sur un lien
        document.querySelectorAll('.mobile-menu-nav a').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });
        
        // Fermer le menu avec la touche Échap
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeMobileMenu();
            }
        });
        
        // Fermer le menu quand la fenêtre est redimensionnée
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileMenu();
            }
        });
    </script>
</body>

</html>
