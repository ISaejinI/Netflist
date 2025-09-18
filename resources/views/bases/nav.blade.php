<nav id="navHeader">
    <a href="{{ route('popularmovies') }}">Films populaires</a>
    <a href="{{ route('bestratedmovies') }}">Les films les mieux notés</a>
    <a href="{{ route('savedmovies') }}">Films sauvegardés</a>

    @auth
        <a href="#">Mon profil</a>
    @else
        <a href="#">Se connecter</a>
        {{-- <a href="{{ route('login') }}">Se connecter</a> --}}
        <a href="#" id="register">S'inscrire</a>
        {{-- <a href="{{ route('register') }}">S'inscrire</a> --}}
    @endauth
</nav>