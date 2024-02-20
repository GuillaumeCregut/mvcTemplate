<?php

/*
Based on File class from Symfony (c) F. Potencier

*/

namespace Editiel98\Kernel;

use Error;
use Exception;

class UploadedFile extends File
{
    private string $OriginalName;
    private string|null $mimeType;
    private int | null $error;

    //Ne sera appeler que lorsqu'un fichier est uploadé, via la méthode de formulaire qui va bien

    public function __construct(string $path, string $OriginalName, ?string $mimeType = null, ?int $error = null)
    {
        $this->OriginalName = $this->getName($OriginalName);
        $this->mimeType = $mimeType ?: 'application/octet-stream';
        $this->error = $error ?: \UPLOAD_ERR_OK;
        parent::__construct($path, \UPLOAD_ERR_OK === $this->error);
    }



    /**
     * Get the value of OriginalName
     */
    public function getOriginalName(): string
    {
        return $this->OriginalName;
    }

    /**
     * Get the value of mimeType
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Get the value of error
     */
    public function getError(): int
    {
        return $this->error;
    }

    public function isValid(): bool
    {
        $isOK = \UPLOAD_ERR_OK === $this->error;
        return  $isOK && is_uploaded_file($this->getPathname());
    }

    public function move(string $directory, ?string $name = null): File
    {
        if ($this->isValid()) {
            $target = $this->getTargetFile($directory, $name);
            try {
                $moved = move_uploaded_file($this->getPathname(), $target);
            } catch (Error $e) {
                throw new Exception("Could not move the file");
            }
            if (!$moved) {
                throw new Exception("Could not move the file");
            }
            @chmod($target, 0666 & ~umask());
        }
        throw new Exception($this->getErrorMessage($this->error));
    }

    private function getErrorMessage(int $error): string
    {
        static $errorsMessages = [
            \UPLOAD_ERR_INI_SIZE => 'The file "%s" exceeds your upload_max_filesize ini directive',
            \UPLOAD_ERR_FORM_SIZE => 'The file exceeds the upload limit defined in your form.',
            \UPLOAD_ERR_PARTIAL => 'The file  was only partially uploaded.',
            \UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            \UPLOAD_ERR_CANT_WRITE => 'The file  could not be written on disk.',
            \UPLOAD_ERR_NO_TMP_DIR => 'File could not be uploaded: missing temporary directory.',
            \UPLOAD_ERR_EXTENSION => 'File upload was stopped by a PHP extension.',
        ];
        $message = $errorsMessages[$error] ?? 'The file was not uploaded due to an unknown error.';
        return $message;
    }
}
