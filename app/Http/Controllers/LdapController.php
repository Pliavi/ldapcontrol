<?php

namespace App\Http\Controllers;

class LdapController extends Controller {

    private $connection = false;
    private $bind = false;
    private $domainComponent = "";

    public function connect($server, $port = 389){
        $connection = ldap_connect($server, $port) or false;

        if($connection){
            ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
            $this->connection = $connection;
            return $this;
        }

        return false;
    }
    
    public function anon(){
        $bind = ldap_bind($this->connection);
        $this->bind = $bind;
    }

    public function bind($commonName, $domainComponent, $password){
        $errorLog = "";
        $commonName = (!empty($commonName)) ? "cn=$commonName," : '';
        $domainComponent = 'dc=' . preg_replace("/\./", ',dc=', $domainComponent);

        $bind = ldap_bind($this->connection, "cn=$commonName,$domainComponent", $password);

        if($bind){
            $this->bind = $bind;
            $this->domainComponent = $domainComponent;
            $result = $this;
        } else {
            if (ldap_get_option($this->connection, LDAP_OPT_ERROR_STRING, $extendedError)) { $errorLog = "Descrição: " . $extendedError; }

            Helper::errorLog("Usuário: cn=$commonName,dc=$domainComponent tentou conexão e falhou. $errorLog");
            $result = false;
        }

        return $result;
    }

    public function addUser(array $info) {
        $bind = ldap_add($this->connection, "cn=Vitor,{$this->domainComponent}", $info);
    }

    public function close(){
        ldap_close($this->connection);
    }

}
