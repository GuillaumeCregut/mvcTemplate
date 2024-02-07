<?php
namespace Editiel98\Chore;

use Exception;

class GetEnv
{
    public static function getEnv(string $iniFile, ?string $section='')
    {
        $envDatas=getenv();
        if(!empty ($envDatas['login'])){

        }
        $envs=parse_ini_file($iniFile,true);
        if(!$envs) {
            throw new Exception('Configuration file missing');
        }
        $envValues=(object) $envs;
        if($section!=='') {
            return $envValues->$section;
        }
    }

    public static function getEnvValue(string $value)
    {
        $iniFile='../../.env';
        $envDatas=getenv();
        if(!empty($envDatas[$value])){
            return $envDatas[$value];
        }
        $envs=parse_ini_file($iniFile,false);
        if(!$envs) {
            throw new Exception('Configuration file missing');
        }
        return $envs[$value];
       
    }
}