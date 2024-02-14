<?php

namespace Editiel98\Kernel;

use Editiel98\Kernel\GetEnv;
use Editiel98\Session;
use Exception;

class CSRFCheck
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * createToken : create a CSRF token for forms
     *
     * @return string :hash to display in form
     */
    public function createToken(): string
    {
        //Get Secret
        try {
            $secret = $this->getSecret();
        } catch (Exception $e) {
            var_dump($e);
            return '';
        }
        //generate token
        $varPart = bin2hex(random_bytes(24));
        $this->session->setKey('token_part', $varPart);
        $token = $this->hashToken($varPart, $secret);
        //Return tocken
        return $token;
    }


    /**
     * checkToken
     *
     * @param string $token : the token to check
     * @return boolean : true if token OK, false else
     */
    public function checkToken(string $token): bool
    {
        //Get Secret
        try {
            $secret = $this->getSecret();
        } catch (Exception $e) {
            var_dump($e);
            return '';
        }
        $varPart = $this->session->getKey('token_part');
        //Decode Token
        return hash_equals($this->hashToken($varPart, $secret), $token);
    }

    /**
     * GetSecret
     * load secret key form config file
     *
     * @return string
     */
    private function getSecret(): string
    {
        try {
            $config =GetEnv::getEnvValue('token');
            return $config;
        } catch (Exception $e) {
            throw new Exception('Config not readable');
        }
    }

    /**
     * hash token
     *
     * @param string $varPart : variable part of token
     * @param string $secret : key
     * @return string
     */
    private function hashToken(string $varPart, string $secret): string
    {
        return hash_hmac('sha256', $varPart, $secret);
    }
}
