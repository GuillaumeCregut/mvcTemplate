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
        if ($value === '') {
            return '';
        }
        $iniFile = __DIR__ .  '../../../../.env';
        $envDatas = getenv();
        if (!empty($envDatas[$value])) {
            return $envDatas[$value];
        }
        $envs = parse_ini_file($iniFile, false);
        if (!$envs) {
            throw new Exception('Configuration file missing');
        }
        if (empty($envs[$value])) {
            return '';
        }
        return $envs[$value];
    }
}
