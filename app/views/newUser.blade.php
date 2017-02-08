@include('includes.head')
<header>Novo Usuário (Mt. {{$id}})</header>
<form action="{{ route('userConfirmationView') }}" method="POST" class='grid'>
    @include('includes.notification')

    <label>Nome</label>
    <input type='text' name='cn' autofocus required class='form-input'>

    <label>Descrição</label>
    <input type='text' name='description' required class='form-input'>

    <label>Grupo</label>
    <select name='group' required class='form-select'>
        <option value=''>-- Selecione um grupo --</option>
        @foreach($groups as $group)
            <option value="{{ $group }}">{{ $group }}</option>
        @endforeach
    </select>

    <a href="{{ route('findUserView') }}" class='btn-outline'>Cancelar</a>
    <button type='submit' class='btn'>Enviar</button>

</form>
@include('includes.footer')
