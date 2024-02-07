<?php

namespace Editiel98\Chore;

use Editiel98\Chore\Emitter;
use Editiel98\SmartyMKD;
use Exception;

/**
 * Mailer Class : Send an Email
 */
class Mailer
{
    private string $adminMail;
    private SmartyMKD $smarty;

    public function __construct()
    {
        $this->loadParams();
        $this->smarty = new SmartyMKD();
    }

    
    /**
     * Load config file
     * @return void
     */
    private function loadParams(): void
    {
        try {
            $envMode=GetEnv::getEnvValue('envMode');
            if ($envMode == 'debug') {
                ini_set('SMTP', '192.168.1.10');
            }
            $this->adminMail = GetEnv::getEnvValue('mailadmin');
        } catch (Exception $e) {
            $errCode = $e->getCode();
            $errMessage = $e->getMessage();
            throw new Exception('Impossible de lire les credentials');
        }
    }

    /**
     * Function SendMail : 
     * Send an email with all  infos filled
     *
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param string $message
     * @param boolean|null $html
     * @return boolean
     */
    private function sendMail(string $to, string $from, string $subject, string $message, ?bool $html = false): bool
    {
        $headers = [
            "From" => $from,
            "Reply-To" => $from,
        ];
        if ($html) {
            $headers['Content-Type'] = 'text/html; charset=utf-8';
        }
        try {
            $mailSent = mail($to, $subject, $message, $headers);
            if (!$mailSent) {
                $emitter = Emitter::getInstance();
                $emitter->emit(Emitter::MAIL_ERROR, $to);
            }
            return $mailSent;
        } catch (Exception $e) {
        }
    }
    /**
     * Send a text mail to the admin
     *
     * @param string $from
     * @param string $subject
     * @param string $message
     * @return boolean
     */
    public function sendMailToAdmin(string $from, string $subject, string $message): bool
    {
        $MessageMail = wordwrap($message, 70, '\n');
        return $this->sendMail($this->adminMail, $from, $subject, $MessageMail, false);
    }

    /**
     * Send an HTML mail to the admin
     *
     * @param string $from mail sender
     * @param string $subject subject of mail
     * @param array $values to put in template
     * @param string $template HTML template of the mail
     * @return boolean
     */
    public function sendHTMLMailToAdmin(string $from, string $subject, array $values, string $template): bool
    {
        $MailTemplate = 'mail/' . $template . '.tpl';
        foreach ($values as $k => $v) {
            $this->smarty->assign($k, $v);
        }
        $content = $this->smarty->fetch($MailTemplate);
        return $this->sendMail($this->adminMail, $from, $subject, $content, true);
    }

    /**
     * Send a text mail to one user from admin
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return boolean
     */
    public function sendMailToUser(string $to, string $subject, string $message): bool
    {
        $MessageMail = wordwrap($message, 70, '\r\n');
        return $this->sendMail($to, $this->adminMail, $subject, $MessageMail, false);
    }

    /**
     * Send a HTML mail rom admin to user
     *
     * @param string $to
     * @param string $subject
     * @param array $values : values to place in template as['templateValue'=>value]
     * @param string $template
     * @return boolean
     */
    public function sendHTMLMailToUser(string $to, string $subject, array $values, string $template): bool
    {
        $MailTemplate = 'mail/' . $template . '.tpl';
        foreach ($values as $k => $v) {
            $this->smarty->assign($k, $v);
        }
        $content = $this->smarty->fetch($MailTemplate);
        return $this->sendMail($to, $this->adminMail, $subject, $content, true);
    }
}
