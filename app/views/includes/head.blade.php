<html>
<head>
    <link rel="stylesheet" href="{{ asset('assets/css/siimple.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>
<body>
<div class='pl-logout'>{{Session::get('auth')}} <a class="btn btn-outline cover-btn" href="{{ route('logout') }}">Sair</a></div>