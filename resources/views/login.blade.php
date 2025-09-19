@extends('bases.base')
@section('content')

    <div class="container">
        <form action="{{ route('authenticate') }}" method="post">
            @csrf
            <label for="email">Email</label>
            <input type="email" name="email" id="">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="">
            <button type="submit">Se connecter</button>
        </form>
    </div>

@endsection