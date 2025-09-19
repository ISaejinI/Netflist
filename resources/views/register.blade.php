@extends('bases.base')
@section('content')
    <div class="container">
        <form action="{{ route('registerinfo') }}" method="post">
            @csrf
            <label for="name">Nom</label>
            <input type="text" name="name" id="name">

            <label for="email">Email</label>
            <input type="email" name="email" id="email">

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password">

            <label for="password_confirmation">Confirmez le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
            
            <button type="submit">S'inscrire</button>
        </form>
    </div>
@endsection
