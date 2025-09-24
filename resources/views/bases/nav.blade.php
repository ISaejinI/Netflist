<nav id="navHeader">
    <a href="{{ route('popularmovies') }}">Films populaires</a>
    <a href="{{ route('bestratedmovies') }}">Les films les mieux notés</a>
    <a href="{{ route('popularseries') }}">Séries populaires</a>
    
    @auth
        <a href="{{ route('savedmovies') }}">Films sauvegardés</a>
        <a href="{{ route('logout') }}" id="register">Se déconnecter</a>
    @else
        <a href="{{ route('login') }}">Se connecter</a>
        <a href="{{ route('register') }}" id="register">S'inscrire</a>
    @endauth
</nav>