<?php
namespace Editiel98\Kernel;

use Exception;

class GetEnv
{
    public static function getEnvValue(string $value)
    {
        $iniFile='../.env';
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