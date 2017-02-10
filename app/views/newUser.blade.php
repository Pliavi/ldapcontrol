@include('includes.head')
<header>Novo Usuário (Mt. {{ Session::get('id') }})</header>
<form action="{{ route('userConfirmationView') }}" method="POST" class='grid'>
    @include('includes.notification')

    <label>Nome</label>
    <input type='text' name='cn' 
        pattern = '(\b[a-zA-Z\u00C0-\u017F]+\b|\s){2,}'
        placeholder = 'Nome e Sobrenome'
        autofocus 
        required 
        class='form-input' 
        value="{{ Input::old('cn') }}">

    <label>Descrição</label>
    <input 
        type='text' 
        name='description' 
        class='form-input' 
        value="{{ Input::old('description') }}">

    <label>Grupo</label>
    <select name='group' required class='form-select'>
        <option value=''>-- Selecione um grupo --</option>
        @foreach($groups as $group)
        @if(Input::old('group') == $group)
            <option value="{{ $group }}" selected>{{ $group }}</option>        
        @else
            <option value="{{ $group }}">{{ $group }}</option>
        @endif
        @endforeach
    </select>

    <button type='submit' class='btn'>Enviar</button>
    <a href="{{ route('findUserView') }}" class='btn-outline'>Cancelar</a>

</form>
@include('includes.footer')
