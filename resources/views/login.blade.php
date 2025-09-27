@extends('bases.base')
@section('content')
    <div class="auth-page">
        <div class="auth-card">
            <h1 class="auth-title">Connexion</h1>
            <form action="{{ route('authenticate') }}" method="post" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" name="email" id="email" placeholder="Votre email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="Votre mot de passe" required>
                </div>

                <button type="submit" class="btn-primary auth-btn">Se connecter</button>
            </form>
            <p class="auth-switch">Pas encore de compte ? <a href="{{ route('register') }}" class="highlight">Inscrivez-vous</a></p>
        </div>
    </div>
@endsection
