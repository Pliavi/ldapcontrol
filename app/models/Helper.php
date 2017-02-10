<?php

class Helper {

    public static function getMemberOfGroupNames($dns){
        $names = [];
        foreach ($dns as $dn) {
            $property = explode(',', $dn)[0];
            $names[] = substr($property, 3);
        }

        return $names;
    }

    public static function clearArray(Array $arr){
        $tmp = [];
        foreach ($arr as $key => $value) {
            if(!empty($value)){
                $tmp[$key] = $value;
            }
        }
        return $tmp;
    }

    public static function getGroupOUs($groupDn){
        $groupDn = (explode(",", $groupDn));

        $ou = '';
        foreach($groupDn as $partial) { 
            # do DN do grupo, pega apenas as OU e as que não seja de Grupo (OU=Grupos)
            if(substr($partial, 0, 2) == "OU" && !strpos($partial, 'Grupos')){
                $ou .= ",$partial";
            }
        }

        return $ou;
    }

    public static function generateScriptPath($groupDn){
        $ou = Helper::getGroupOUs($groupDn);
        return strtolower(explode('-', explode(',', $ou)[1])[1]).'.vbs';
        
    }

}