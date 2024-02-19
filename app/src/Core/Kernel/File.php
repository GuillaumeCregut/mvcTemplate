<?php

namespace Editiel98\Kernel;

use Error;
use Exception;
use PhpParser\Node\Expr\Cast\String_;

class File extends \SplFileInfo
{
    public function __construct(string $filename, bool $checkPath = true)
    {
        if ($checkPath && !is_file($filename)) {
            throw new Exception(sprintf("File %s is not a file", $filename));
        }
        parent::__construct($filename);
    }

    public function move(string $newPath, ?string $name = null): self
    {
        $target = $this->getTargetFile($newPath, $name);
        try {
            $renamed = rename($this->getPathname(), $target);
        } catch (Error $e) {
            throw new Exception(sprintf("File %s can't be moved at %s ", $this->getPathname(), $target));
        }
        if (!$renamed) {
            throw new Exception(sprintf("File %s can't be moved at %s ", $this->getPathname(), $target));
        }
        @chmod($target, 0666 & ~umask());

        return $target;
    }

    protected function getName(string $name): string
    {
        $tmpName = str_replace('\\', DIRECTORY_SEPARATOR, $name);
        $pos = strrpos($tmpName, '/');
        if (!$pos) {
            return $name;
        }
        return substr($tmpName, $pos + 1);
    }

    protected function getTargetFile(string $directory, ?string $name = null): self
    {
        if (!is_dir($directory)) {
            if (false === mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new Exception(sprintf("Directory %s can't be created", $directory));
            }
            if (!is_writable($directory)) {
                throw new Exception(sprintf('%s is not writable', $directory));
            }
        }
        $target = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR .
         (null === $name ? $this->getBasename() : $this->getName($name));
        return new self($target, false);
    }
}
