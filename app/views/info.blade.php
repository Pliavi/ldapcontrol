@include('includes.head')
<header>Dados do Usuário</header>
<div class="grid">
    @include('includes.notification')
    <label>Matrícula:</label> {{ $data['id'] }}  <br>

    <label>Nome:</label> {{ $data['name'] }} <br>

    @if($data['mail'])
    <label>Email:</label> {{ $data['mail'] }} <br>
    @endif

    @if($data['description'])
    <label>Descrição:</label> {{ $data['description'] }} <br>
    @endif
    
    <br>
    <label>Pertence aos Grupos:</label> <br>
    <ul>
    @forelse($data['groups'] as $group)
        <li>{{ $group }}</li>
    @empty
        <li>Usuário não pertence a nenhum grupo</li>
    @endforelse
    </ul>
    <a href="{{ route('findUserView') }}" class='btn-outline'>Voltar</a>
</div>
@include('includes.footer')