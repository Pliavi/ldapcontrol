@include('includes.head')
<header>Controle AD</header>
<form action="{{ route('findUser') }}" method='POST' class='grid'>
    @include('includes.notification')
    <div class='alert'>
        Use a pesquisa abaixo para verificar a já existência da matrícula no sistema. <br>
        caso não exista será encaminhado para o formulário de cadastro.
    </div>

    <label>Matrícula:</label>
    <input type='text' name='id' minlength='5' maxlength='5' autofocus required class='form-input id-mask'>

    <button type='submit' class='btn'>Pesquisar</button>
</form>
@include('includes.footer')
