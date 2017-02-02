<?php
use \Adldap\Adldap as LDAP;
use \Adldap\Models\User as ADUser;
use \Adldap\Exceptions\AdldapException as AdldapException;
use \Adldap\Exceptions\ModelNotFoundException as ModelNotFoundException;

class LdapController extends BaseController {
    private $ad;
    private $config = [
        'account_suffix' => '', # Valor padrão chato da p#rra, criador de perda de tempo por falha de autenticação!
        'account_prepend' => '',
        'base_dn' => 'dc=pliavi,dc=com',
        'domain_controllers' => ['192.168.1.33'],
        'admin_username' => 'cn=admin,dc=pliavi,dc=com',
        'admin_password' => 'adminpass',
        'ad_port' => 389
    ];

    public function createUser(){
        // $data = Input::all();
        // $data['userPrincipalName'] = "1000";
        $data['sAMAccountName'] = $data['userPrincipalName'];
        $data['scriptPath'] = "nome_do_arquivo.vbs"; // Setar o arquivo que será executado no logon

        try {
            $success = $this->ad->users()->create($data['user']);
            if(!$success){ throw new AdldapException("Falha ao criar o usuário"); }

            $user = $this->ad->search()->users()->find($data['user']['userPrincipalName']);

            foreach ($data['groups'] as $group) {
                $groupToAdd = $ad->search()->groups()->firstOrFail($group);
                $groupToAdd->addMember($user);
            }
        } catch(AdldapException $e) { 
        } catch(ModelNotFoundException $e){ }

    }

    function __construct(){ $this->ad = new LDAP($this->config); }
    function __destruct(){ $this->ad->close(); }
}