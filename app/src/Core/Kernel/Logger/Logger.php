<?php

namespace Editiel98\Kernel\Logger;

use DateTime;
use Exception;

/**
 * Abstract class for loggers
 */
abstract class Logger implements LoggerInterface
{
    private string $filename;

    public function __construct(string $filename)
    {
        $directory = dirname(dirname(dirname(__DIR__))) . '/logs/';
        $this->filename = $directory . $filename . '.log';
    }

    /**
     * Log a message in the log file
     * @param string $value : message
     *
     * @return bool
     */
    public function storeToFile(string $value): bool
    {
        date_default_timezone_set('Europe/Paris');
        $now = new DateTime();
        $dateMessage = date_format($now, 'd/m/Y H:i:s');
        $message = $dateMessage . ' : ' . $value . "\n";
        try {
            if (($file = fopen($this->filename, 'a')) && (is_writable($this->filename))) {
                fwrite($file, $message);
                fclose($file);
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception('Error in logger');
        }
    }

    /**
     * Load logs from files and return them
     * @return array<mixed>|bool
     */
    public function loadFromFile(): array|bool
    {
        $logs = [];
        if ($file = fopen($this->filename, 'r')) {
            while (!feof($file)) {
                $line = fgets($file);
                if ($line) {
                    $logs[] = $line;
                }
            }
            return $logs;
        } else {
            return false;
        }
    }
}
