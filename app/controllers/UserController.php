<?php
use \Adldap\Objects\AccountControl as AccountControl;

Class UserController extends LdapController {

    ### VIEWS
    public function newUserView() {
        $groups = (new GroupController)->all();
        Session::flash('id', Session::get('id'));        
        Session::flash('_old_input', Session::get('userData'));

        return View::make('newUser', compact('groups')); 
    }
    public function findView() { return View::make('findUser'); }
    public function infoView($data) { return View::make('info', compact('data')); }
    public function confirmationView() { 
        # pega o id do usuário da sessão para poder dar "reflash"
        $id = Session::get('id');
        # Dá flash nos dados preenchidos para que seja resgatado no método de criação junto ao id
        Session::flash('userData', Input::all()); 
        Session::flash('id', $id); # "reflash" do id

        $input = Input::all();

        $validator = Validator::make(
            $input, [
                'cn' => 'required|alpha_spaces',
                'group' => 'required',
            ]
        );

        if($validator->fails()){
            Session::flash('error', $validator->errors()->toArray());
            return Redirect::route('newUserView')->withInput();
        }

        $data['id']          = Session::get('id');
        $data['name']        = mb_strtoupper($input['cn']);
        $data['groups'][]    = $input['group'];
        $data['description'] = $input['description'];
        $data['mail']        = ''; # O preenchimento do Email já está aqui caso necessário mais tarde

        return View::make('info', compact('data'))->with(['confirmation' => true]); 
    }

    ### FUNCTIONS
    public function find() {
        $validator = Validator::make(Input::all(), [ 'id' => 'required|numeric' ]);
        if($validator->fails()){
            Session::flash('error', 'É necessário fazer a pesquisa pela matrícula antes de executar qualquer ação.');
            return Redirect::route('findUserView');
        }

        # Busca o usuário pela matrícula
        $id = Input::get('id'); #matricula
        $user = $this->ad->users()->search()->where('sAMAccountName', '=', $id)->first();

        # Verifica a existencia do usuário e encaminha para a View correta
        if(!$user){
            Session::flash('success', "Usuário com a matrícula $id ainda não foi criado. Preencha o formulário para sua criação");
            Session::flash('id', $id);

            return $this->newUserView();
        } else {
            Session::flash('error', "Usuário com a matrícula $id já existente");
            $data['id']          = $user->samaccountname[0];
            $data['name']        = $user->name[0];
            $data['groups']      = Helper::getMemberOfGroupNames($user->memberof);
            $data['description'] = $user->description[0];
            $data['mail']        = $user->mail[0];

            return $this->infoView($data);
        }
    }

    public function create(){
        # VARS
        $userId = Session::get('id');
        $data = Session::get('userData');

        $cn = mb_strtoupper($data['cn'], 'UTF-8'); # Nome em letra maiúscula
        $userPrincipalName = $userId; # Matrícula
        $sAMAccountName = $userId; # Seta o Accountname(Win2000) de acordo com o $userPrincipalName
        $groupName = $data['group']; 
        $groupDn = '';
        $ou = "OU=Usuarios";
        $dn = '';
        $scriptPath = '';
        $description = $data['description'];

        # Seleciona o grupo ao qual o usuário será adicionado
        $group = $this->ad->groups()->search()->where('name','=', $groupName)->firstOrFail();
        
        # Grupo de acesso a internet
        $internet = $this->ad->groups()->search()->where('name','=', 'ACESSO_PADRAO')->firstOrFail();

        # Seleciona as Unidades Organizacionais a serem adicionadas ao DN (não testado)
        # Caso não funcione, utilizar o modelo de OrganizationalUnit do Adldap2;
        $ou .= Helper::getGroupOUs($group['dn']);

        # Cria o usuário
        $dn = "cn=$cn,$ou,dc=pmc,dc=local";
        $explodedName = explode(' ', $cn,2);

        $user = $this->ad->users()->newInstance();
        $user->cn = $cn;
        $user->name = $cn;
        $user->displayname = $cn; # nome completo
        $user->givenname = $explodedName[0]; # primeiro nome
        $user->sn = (isset($explodedName[1]))? $explodedName[1] : '' ; # sobrenome
        $user->userprincipalname = $userPrincipalName.ADCONFIG['user_suffix'];
        $user->samaccountname = $sAMAccountName;
        $user->description = $description;
        $user->dn = $dn;
        $user->scriptpath = Helper::generateScriptPath($group['dn']); # Script de acordo com a Unidade Organizacional
        $user->pwdlastset = "0"; # Obrigar a trocar a senha no primeiro logon
        $user->useraccountcontrol = "544"; # Ativa o usuário

        if($user->save()) {
            Session::flash('success', "Novo usuário: $userPrincipalName cadastrado.");
            Log::info("Novo usuário: $userPrincipalName cadastrado.");
        } else {
            Session::flash('error', 'Algo impediu a crição do usuário, verifique os dados e tente novamente. err: '. $this->ad->getConnection()->getLastError());
            Session::flash('id', $userId);
            Session::flash('userData', $data);
            return Redirect::route('newUserView');
        }

        # Adiciona o usuário ao grupo selecionado acima e ao grupo de acesso padrão à internet
        $group->addMember($user);
        $internet->addMember($user);

        return Redirect::route('findUserView');
    }
}