@include('includes.head')
<body>
    <form action="" class="pl-top-margin">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="pl-panel-heading">Novo Usuário</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2">
                                <label>Matrícula</label>
                                <input type="number" name='userPrincipalName' class="form-control">
                            </div>
                            <div class="col-md-10">
                                <label>Nome</label>
                                <input type="text" name='cn' class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Grupo</label>
                        <select name="memberOf" class="form-control" required>
                            <option value="">-- Selecione um grupo --</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                </div>
            </div>
        </div>
    </form>
</body>