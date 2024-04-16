<?php

namespace Editiel98\Kernel;

class PathGenerator
{
    /**
     * @param array<string> $path
     *
     * @return string
     */
    public static function generatePath(array $path): string
    {
        if (count($path) === 0) {
            return '';
        }
        $newPath = array_shift($path);
        foreach ($path as $element) {
            if ($element === '..') {
                $tempArray = explode(DIRECTORY_SEPARATOR, $newPath);
                array_pop($tempArray);
                $newPath = implode(DIRECTORY_SEPARATOR, $tempArray);
            } else {
                $newPath .= DIRECTORY_SEPARATOR . $element;
            }
        }
        return $newPath;
    }
}
