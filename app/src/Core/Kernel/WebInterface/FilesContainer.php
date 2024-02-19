<?php

namespace Editiel98\Kernel\WebInterface;

use Editiel98\Kernel\UploadedFile;
use InvalidArgumentException;
use SebastianBergmann\Type\NullType;

class FilesContainer extends ElementContainer
{
    private const FILE_KEYS = ['error', 'name', 'size', 'tmp_name', 'type'];
    /**
     * @param mixed[] $params
     */
    public function __construct(array $params)
    {
        $this->replace($params);
    }

    /**
     * @param mixed[] $paramsIn
     *
     * @return void
     */
    private function replace(array $paramsIn): void
    {
        $this->params = [];
        $this->addValues($paramsIn);
    }

    /**
     * @param mixed[] $paramsIn
     *
     * @return void
     */
    private function addValues(array $paramsIn): void
    {
        foreach ($paramsIn as $key => $param) {
            $this->setValue($key, $param);
        }
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
     * @param  mixed[] | UploadedFile $file
     * @return mixed[]
     */
    protected function convertToUploadedFile(array |UploadedFile $file): array | UploadedFile | null
    {
        if ($file instanceof UploadedFile) {
            return $file;
        }
        $file = $this->fixPhpFilesArray($file);
        $keys = array_keys($file);
        sort($keys);
        if (self::FILE_KEYS == $keys) {
            if (self::FILE_KEYS == $file['error']) {
                $file = null;
            } else {
                $file = new UploadedFile($file['tmp_name'], $file['name'], $file['type'], $file['error']);
            }
        } else {
            $file = array_map(fn ($v) => $v instanceof UploadedFile || is_array($v) ?
                $this->convertToUploadedFile($v) : $v, $file);
            if (array_keys($keys) == $keys) {
                $file = array_filter($file);
            }
        }
        return $file;
    }

    /**
     * @param mixed[] $data
     *
     * @return mixed[]
     */
    protected function fixPhpFilesArray(array $data): array
    {
        // Remove extra key added by PHP 8.1.
        unset($data['full_path']);
        $keys = array_keys($data);
        sort($keys);

        if (self::FILE_KEYS != $keys || !isset($data['name']) || !\is_array($data['name'])) {
            return $data;
        }

        $files = $data;
        foreach (self::FILE_KEYS as $k) {
            unset($files[$k]);
        }

        foreach ($data['name'] as $key => $name) {
            $files[$key] = $this->fixPhpFilesArray([
                'error' => $data['error'][$key],
                'name' => $name,
                'type' => $data['type'][$key],
                'tmp_name' => $data['tmp_name'][$key],
                'size' => $data['size'][$key],
            ]);
        }

        return $files;
    }
}
