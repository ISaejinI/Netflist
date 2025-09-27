<nav id="navHeader">
    <a href="{{ route('populartitles') }}">Populaires</a>
    <a href="{{ route('bestratedmovies') }}">Mieux notés</a>
    
    @auth
        <a href="{{ route('savedmovies') }}">Films sauvegardés</a>
        <a href="{{ route('logout') }}" id="register">Se déconnecter</a>
    @else
        <a href="{{ route('login') }}">Se connecter</a>
        <a href="{{ route('register') }}" id="register">S'inscrire</a>
    @endauth
</nav>