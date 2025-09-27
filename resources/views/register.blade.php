@extends('bases.base')
@section('content')
    <div class="auth-page">
        <div class="auth-card">
            <h1 class="auth-title">Inscription</h1>
            <form action="{{ route('registerinfo') }}" method="post" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" name="name" id="name" placeholder="Votre nom" required>
                </div>
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" name="email" id="email" placeholder="Votre email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="Votre mot de passe" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmation du mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="Confirmez votre mot de passe" required>
                </div>

                <button type="submit" class="btn-primary auth-btn">S'inscrire</button>
            </form>
            <p class="auth-switch">Déjà un compte ? <a href="{{ route('login') }}" class="highlight">Connectez-vous</a></p>
        </div>
    </div>
@endsection
