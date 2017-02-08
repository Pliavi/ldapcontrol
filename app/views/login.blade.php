@include('includes.head')
<header>Controle AD</header>
<form action="{{ url('signin') }}" method="POST" class='grid'>
    @include('includes.notification')

    <label>Usuário</label>
    <input type='text' name='user' autofocus required class='form-input id-mask'>

    <label>Senha</label>
    <input type='password' name='password' required class='form-input'>

    <button type='submit' class='btn'>Entrar</button>
</form>
@include('includes.footer')

