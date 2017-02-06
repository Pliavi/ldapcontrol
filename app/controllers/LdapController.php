<?php
use \Adldap\Adldap as LDAP;
use \Adldap\Models\User as ADUser;
use \Adldap\Exceptions\AdldapException as AdldapException;
use \Adldap\Exceptions\ModelNotFoundException as ModelNotFoundException;

class LdapController extends BaseController {
    protected $ad;
    private $config = ADCONFIG;

    function __construct(){ 
        try{
            $this->ad = new LDAP($this->config); 
        } catch (AdldapException $e) {
            Log::warning($e->getMessage());
            echo 'Active directory fora do ar ou utilizando credenciais antigas.<br>';
            echo '-----<br>';
            echo 'Tente novamente mais tarde e avise a Secretaria de Planejamento e Tecnologia da Informação';
            exit;
        }
    }

    function __destruct(){ 
        if($this->ad !== null){
            $this->ad->getConnection()->close();
        }; 
    }
}