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
}