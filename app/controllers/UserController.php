<?php

Class UserController extends LdapController {
    
    private $validation = [
        'userPrincipalName'  => 'required', // Matrícula
        'cn'                 => 'required', // Nome do usuário
        // 'sectorFolderAccess' => 'required', // Pasta "g:/"
        // 'groups'             => 'required',
    ];

    public function create(){
        $data = Input::all();
        $data['sAMAccountName'] = $data['userPrincipalName']; // Seta o Accountname de acordo com o PrincipalName
        $data['scriptPath'] = 'seduc.vbs';//ADSCRIPT[$data['groups']]; // Setar o script que mapeia as unidades

        try {
            $user = $this->ad->users()->newInstance();
            $user->cn = "CobaiaA1";
            $user->samAccountName = "coba1";
            $user->userPrincipalName = "coba1";
            $user->dn = 'cn=CobaiaA1,dc=pmc,dc=local';

            if($user->save()){
                echo "Ok";
            }else{
                echo "ERROR<br>";
                die($this->ad->getConnection()->getLastError());                
            }



            // $success = $this->ad->users()->create($data);
            // if(!$success){ 
            //     die($this->ad->getConnection()->getLastError());
            //     // throw new AdldapException("Falha ao criar o usuário"); 
            // }

            // $user = $this->ad->search()->users()->find($data['userPrincipalName']);

            // foreach ($data['groups'] as $group) {
            //     $groupToAdd = $ad->search()->groups()->firstOrFail($group);
            //     $groupToAdd->addMember($user);
            // }

            // Log::info("Novo usuário: {$data['userPrincipalName']} cadastrado.");
            // Session::flash('success', "Novo usuário: {$data['userPrincipalName']} cadastrado.");

            // return Redirect::back();
        } catch(AdldapException $e) { 
            Log::error("Algo impossibilitou a criação do usuário: ".$e->getMessage());
            Session::flash("Algo impossibilitou a criação do usuário: ".$e->getMessage());
        } catch(ModelNotFoundException $e){
            Log::error("Não foi possível encontrar o grupo solicitado durante o cadastro do novo usuário: ".$e->getMessage());
            Session::flash("Não foi possível encontrar o grupo solicitado durante o cadastro do novo usuário: ".$e->getMessage());
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}