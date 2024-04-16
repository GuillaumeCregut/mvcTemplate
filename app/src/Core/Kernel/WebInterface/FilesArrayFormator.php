<?php

namespace Editiel98\Kernel\WebInterface;

class FilesArrayFormator
{
    /**
     * @param array<mixed> $files
     *
     * @return array<mixed>
     */
    public static function convert(array $files): array
    {
        $filesArray = [];
        foreach ($files as $key => $fileElement) {
            $tempFileArray = [];
            if (is_array($fileElement['name'])) {
                $filesArray[$key] = self::formatMuliplefiles($fileElement);
            } else {
                $tempFileArray['name'] = $fileElement['name'];
                $tempFileArray['type'] = $fileElement['type'];
                $tempFileArray['tmp_name'] = $fileElement['tmp_name'];
                $tempFileArray['size'] = $fileElement['size'];
                $tempFileArray['error'] = $fileElement['error'];
                $tempFileArray['full_path'] = $fileElement['full_path'];
                $filesArray[$key][] = $tempFileArray;
            }
        }
        return $filesArray;
    }

    /**
     * Return files ordered an array, each file in one array
     * @param array<mixed> $filesElement
     *
     * @return array<mixed>
     */
    private static function formatMuliplefiles(array $filesElement): array
    {
        $tempFilesArray = [];
        $numberFiles = count($filesElement['name']);
        for ($i = 0; $i < $numberFiles; $i++) {
            $tempFile = [];
            $tempFile['name'] = $filesElement['name'][$i];
            $tempFile['type'] = $filesElement['type'][$i];
            $tempFile['tmp_name'] = $filesElement['tmp_name'][$i];
            $tempFile['size'] = $filesElement['size'][$i];
            $tempFile['error'] = $filesElement['error'][$i];
            $tempFile['full_path'] = $filesElement['full_path'][$i];
            $tempFilesArray[] = $tempFile;
        }
        return $tempFilesArray;
    }
}
