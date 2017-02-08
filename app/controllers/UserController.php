<?php
use \Adldap\Objects\AccountControl as AccountControl;

Class UserController extends LdapController {
    
    private $validation = [
        'userPrincipalName'  => 'required|numeric', # Matrícula
        'cn'                 => 'required', # Nome do usuário
        'group'              => 'required',
    ];

    ### VIEWS
    public function newUserView($groups, $id) { return View::make('newUser', compact('groups', 'id')); }
    public function findView() { return View::make('findUser'); }
    public function infoView($data) { return View::make('info', compact('data')); }
    public function confirmationView($data) { return View::make('info', compact('data'))->with(['confirmButton' => true]); }

    ### FUNCTIONS
    public function find() {
        # TODO: Continuar essa e as outras validações
        $validator = Validator::make(Input::all(),[ 'id' => 'required|numeric' ]);
        if($validator->fails()){
            return $validator->errors();
        }

        # Busca o usuário pela matrícula
        $id = Input::get('id'); #matricula
        $user = $this->ad->users()->search()->where('sAMAccountName', '=', $id)->first();
        
        # BugFIX: Como altero a view sem alterar o controller é necessário resetar a Sessão
        # Caso contrário as notificações da página anterior seriam mantidas
        Session::flush(); 

        # Verifica a existencia do usuário e encaminha para a View correta
        if(!$user){
            $groups = (new GroupController)->all(); 
            Session::flash('success', "Usuário com a matrícula $id ainda não foi criado. Preencha o formulário para sua criação");
            return $this->newUserView($groups, $id);
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
        $data = Input::all();
        $cn = mb_strtoupper($data['cn'], 'UTF-8'); # Nome em letra maiúscula
        $userPrincipalName = $data['userPrincipalName']; # MAtrícula
        $sAMAccountName = $data['userPrincipalName']; # Seta o Accountname(Win2000) de acordo com o $userPrincipalName
        $groupName = $data['group']; 
        $groupDn = '';
        $ou = "OU=Usuarios";
        $dn = '';
        $scriptPath = '';
        $descrição = $data['description'];

        # Seleciona o grupo ao qual o usuário será adicionado
        $group = $this->ad->groups()->search()->where('name','=', $groupName)->firstOrFail();
        
        # Grupo de acesso a internet
        $internet = $this->ad->groups()->search()->where('name','=', 'ACESSO_PADRAO')->firstOrFail();

        # Seleciona as Unidades Organizacionais a serem adicionadas ao DN (não testado)
        # Caso não funcione, utilizar o modelo de OrganizationalUnit do Adldap2;
        $groupDn = $group['dn'];
        $groupDn = (explode(",", $groupDn));

        foreach($groupDn as $partial) { 
            # do DN do grupo, pega apenas as OU e as que não seja de Grupo (OU=Grupos)
            if(substr($partial, 0, 2) == "OU" && !strpos($partial, 'Grupos')){
                $ou .= ",$partial";
            }
        }

        # Cria o usuário
        $dn = "cn=$cn,$ou,dc=pmc,dc=local";

        $user = $this->ad->users()->newInstance();
        $user->cn = $cn;
        $user->userPrincipalName = $userPrincipalName;
        $user->sAMAccountName = $sAMAccountName;
        $user->dn = $dn;
        $user->scriptPath = $scriptPath; # Script de acordo com a Unidade Organizacional

        if($user->save()) {
            Session::flash('success', "Novo usuário: $userPrincipalName cadastrado.");
            Log::info("Novo usuário: $userPrincipalName cadastrado.");
        } else {
            throw new Exception($this->ad->getConnection()->getLastError());
        }

        # Adiciona o usuário ao grupo selecionado acima e ao grupo de acesso padrão à internet
        $group->addMember($user);
        $internet->addMember($user);

        return Redirect::back();
    }
}