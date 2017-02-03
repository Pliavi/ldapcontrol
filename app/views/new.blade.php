@include('includes.head')
<body>
    <form action="{{ route('createUser') }}" method="post" class='pl-top-margin'>
        <div class='col-xs-12 col-md-8 col-md-offset-2'>
            <div class='panel panel-default'>
                <div class='panel-heading text-center'>
                    <h3 class='pl-panel-heading'>Novo Usuário</h3>
                </div>
                <div class='panel-body'>
                    <div class='form-group'>
                        <div class='row'>
                            <div class='col-md-2'>
                                <label>Matrícula</label>
                                <input type='number' min='0' name='userPrincipalName' class='form-control' required>
                            </div>
                            <div class='col-md-10'>
                                <label>Nome</label>
                                <input type='text' name='cn' class='form-control' required>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label>Grupo</label>
                        <div id='groups'>
                            <select class='form-control' name='groups[]' required>
                                <option value=''>-- Selecione um grupo --</option>
                                @foreach($groups as $group)
                                    <option>{{ $group['name'][0] }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <a href='#addGroup'>+ Novo grupo</a> --}}
                    </div>
                    <button type='submit' class='btn btn-primary btn-block'>Enviar</button>
                </div>
            </div>
        </div>
    </form>
</body>