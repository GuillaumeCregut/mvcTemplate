<?php

namespace Editiel98\Kernel;

use Editiel98\Kernel\Emitter;
use Editiel98\Templates\SmartyEditiel;
use Exception;

/**
 * Mailer Class : Send an Email
 */
class Mailer
{
    private string $adminMail;
    private SmartyEditiel $smarty;

    public function __construct()
    {
        $this->loadParams();
        $this->smarty = new SmartyEditiel();
    }


    /**
     * Load config file
     * @return void
     */
    private function loadParams(): void
    {
        try {
            $envMode = GetEnv::getEnvValue('envMode');
            if ($envMode == 'debug') {
                ini_set('SMTP', '192.168.1.10');
            }
            $this->adminMail = GetEnv::getEnvValue('mailadmin');
        } catch (Exception $e) {
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
            return false;
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
        $messageMail = wordwrap($message, 70, '\n');
        return $this->sendMail($this->adminMail, $from, $subject, $messageMail, false);
    }

    /**
     * Send an HTML mail to the admin
     *
     * @param string $from mail sender
     * @param string $subject subject of mail
     * @param array<mixed> $values to put in template
     * @param string $template HTML template of the mail
     * @return boolean
     */
    public function sendHTMLMailToAdmin(string $from, string $subject, array $values, string $template): bool
    {
        $mailTemplate = 'mail/' . $template . '.tpl';
        foreach ($values as $k => $v) {
            $this->smarty->assignVar($k, $v);
        }
        $content = $this->smarty->fetchTemplate($mailTemplate);
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
        $messageMail = wordwrap($message, 70, '\r\n');
        return $this->sendMail($to, $this->adminMail, $subject, $messageMail, false);
    }

    /**
     * Send a HTML mail rom admin to user
     *
     * @param string $to
     * @param string $subject
     * @param array<mixed> $values : values to place in template as['templateValue'=>value]
     * @param string $template
     * @return boolean
     */
    public function sendHTMLMailToUser(string $to, string $subject, array $values, string $template): bool
    {
        $mailTemplate = 'mail/' . $template . '.tpl';
        foreach ($values as $k => $v) {
            $this->smarty->assignVar($k, $v);
        }
        $content = $this->smarty->fetchTemplate($mailTemplate);
        return $this->sendMail($to, $this->adminMail, $subject, $content, true);
    }
}
