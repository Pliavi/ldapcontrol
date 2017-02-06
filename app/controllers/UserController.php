<?php
use \Adldap\Objects\AccountControl as AccountControl;

Class UserController extends LdapController {
    
    private $validation = [
        'userPrincipalName'  => 'required|numeric', # Matrícula
        'cn'                 => 'required', # Nome do usuário
        'group'              => 'required',
    ];

    public function create(){
        # VARS
        $data = Input::all();
        $cn = mb_strtoupper($data['cn'], 'UTF-8'); # Nome em letra maiúscula
        $userPrincipalName = $data['userPrincipalName']; # MAtrícula
        $sAMAccountName = $data['userPrincipalName']; # Seta o Accountname(Win2000) de acordo com o $userPrincipalName
        $groupName = $data['group'];
        $groupDn = "";
        $ou = "OU=Usuarios";
        $dn = "";
        $scriptPath = ADSCRIPT[$groupName] or 'empty.vbs';

        try {
            # Seleciona o grupo ao qual o usuário será adicionado
            $group = $this->ad->groups()->search()->whereEquals('name', $groupName)->firstOrFail();

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
            $user->scriptPath = $scriptPath;
            
            # Ativa o usuário (não testado, caso não funcione, testar executar após criar o usuário)
            $accountControl = new AccountControl;
            $accountControl->accountIsNormal();
            $user->setUserAccountControl($accountControl);

            if($user->save()) {
                Session::flash('success', "Novo usuário: $userPrincipalName cadastrado.");
                Log::info("Novo usuário: $userPrincipalName cadastrado.");
            } else {
                throw new Exception($this->ad->getConnection()->getLastError());
            }

            # Adiciona o usuário ao grupo selecionado acima
            $group->addMember($user);

            return Redirect::back();
        } catch(ModelNotFoundException $e) {
            Log::error("Não foi possível encontrar o grupo solicitado durante o cadastro do novo usuário: ".$e->getMessage());
            Session::flash('error', "Não foi possível encontrar o grupo solicitado durante o cadastro do novo usuário: ".$e->getMessage());
            return Redirect::back();            
        } catch(Exception $e) {
            Log::error("Algo impossibilitou a criação do usuário: ".$e->getMessage());
            Session::flash('error', "Algo impossibilitou a criação do usuário: ".$e->getMessage());
            return Redirect::back();            
        }
    }
}