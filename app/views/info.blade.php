@include('includes.head')
<?php $confirmation = isset($confirmation); ?>

@if($confirmation)
    <header>Confirmação dos Dados</header>
@else
    <header>Dados do Usuário</header>
@endif

<div class="grid">
    @include('includes.notification')

    @if($confirmation)
    <div class="alert">
        <b>Confirme os dados de {{ $data['name'] }} (Matrícula: {{ $data['id'] }}) abaixo:</b><br>
        Caso estejam corretos, clique em <b><i>Concluir</i></b> para concluir o cadastro. <br>
        Caso algo esteja errado, clique em <b><i>Voltar</i></b> para voltar ao formulário.
    </div>
    @endif

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

    @if($confirmation)
    <a href="{{ route('createUser') }}" class="btn">Concluir</a>
    <a href="{{ route('newUserView') }}" class='btn-outline'>Voltar</a>
    @else
    <a href="{{ route('findUserView') }}" class='btn-outline'>Voltar</a>
    @endif
</div>
@include('includes.footer')