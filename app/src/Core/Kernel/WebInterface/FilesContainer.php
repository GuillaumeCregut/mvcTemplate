<?php

/*
Based on File class from Symfony (c) F. Potencier
*/

namespace Editiel98\Kernel\WebInterface;

use Editiel98\Kernel\UploadedFile;
use InvalidArgumentException;

class FilesContainer extends ElementContainer
{
    /**
     * @var mixed[]
     */
    private array $files;

    /**
     * @param mixed[] $params
     */
    public function __construct(array $params)
    {
        $files = FilesArrayFormator::convert($params);
        foreach ($files as $key => $fileContainer) {
            //key is name of field
            //for this key, convert all files to uploaded files
            $this->files[$key] = $this->convertFiles($fileContainer);
        }
    }

    /**
     * @param array<mixed> $files
     *
     * @return array<UploadedFile>
     */
    private function convertFiles(array $files): array
    {
        $fileContainer = [];
        foreach ($files as $file) {
            $file = new UploadedFile($file['tmp_name'], $file['name'], $file['size'], $file['type'], $file['error']);
            $fileContainer[] = $file;
        }
        return $fileContainer;
    }

    /**
     * @param string $key
     * @param mixed $param
     *
     * @return void
     */
    public function setValue(string $key, mixed $param): void
    {
        if (!is_array($param) && !$param instanceof UploadedFile) {
            throw new InvalidArgumentException('An uploaded file must be an array or an instance of UploadedFile');
        }
        $this->params[$key] = $param;
    }


    /**
     * @return mixed[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
