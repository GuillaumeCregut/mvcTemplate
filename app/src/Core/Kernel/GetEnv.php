<?php

namespace Editiel98\Kernel;

use Exception;

class GetEnv
{
    /**
     * @param string $value
     *
     * @return string
     */
    public static function getEnvValue(string $value): string
    {
        $iniFile = '../.env';
        $envDatas = getenv();
        if (!empty($envDatas[$value])) {
            return $envDatas[$value];
        }
        $envs = parse_ini_file($iniFile, false);
        if (!$envs) {
            throw new Exception('Configuration file missing');
        }
        return $envs[$value];
    }
}
