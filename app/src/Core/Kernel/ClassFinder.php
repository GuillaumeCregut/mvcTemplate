<?php

namespace Editiel98\Kernel;

use Exception;

class ClassFinder
{
    private const APP_ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'
    . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;


    /**
     * @param string $namespace
     *
     * @return array<mixed>
     */
    public static function getClassesInNamespace(string $namespace): array
    {
        $nameSpaceDirectory = self::getNamespaceDirectory($namespace);
        if (!$nameSpaceDirectory) {
            throw new Exception('No namespace directory found');
        }
        $files = scandir($nameSpaceDirectory);
        if (!$files) {
            throw new Exception('No class files found');
        }
        $classes = array_map(function ($file) use ($namespace) {
            return $namespace . '\\' . str_replace('.php', '', $file);
        }, $files);

        $findClasses = array_filter($classes, function ($possibleClass) {
            return class_exists($possibleClass);
        });

        return $findClasses;
    }

    /**
     * @return array<mixed>
     */
    private static function getDefinedNamespaces(): array
    {
        $composerJsonPath = self::APP_ROOT . 'composer.json';
        $composerJson = file_get_contents($composerJsonPath);
        if (!$composerJson) {
            throw new Exception('No JSON file found');
        }
        $composerConfig = json_decode($composerJson);
        return (array) $composerConfig->autoload->{'psr-4'};
    }



    /**
     * @param string $namespace
     *
     * @return string|false
     */
    private static function getNamespaceDirectory(string $namespace): string|false
    {
        $composerNamespaces = self::getDefinedNamespaces();
        $namespaceFragments = explode('\\', $namespace);
        $undefinedNamespaceFragments = [];
        while ($namespaceFragments) {
            $possibleNamespace = implode(DIRECTORY_SEPARATOR, $namespaceFragments) . DIRECTORY_SEPARATOR;
            if (array_key_exists($possibleNamespace, $composerNamespaces)) {
                $titi = $composerNamespaces[$possibleNamespace];
                $pathReal = str_ireplace('/', DIRECTORY_SEPARATOR, $titi);
                $realPath =  self::APP_ROOT .  $pathReal . DIRECTORY_SEPARATOR .
                implode(DIRECTORY_SEPARATOR, $undefinedNamespaceFragments);
                return realpath($realPath);
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));
        }

        return false;
    }
}
