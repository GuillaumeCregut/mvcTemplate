<?php
namespace Editiel98\Chore;

class ClassFinder
{
    const APP_ROOT=__DIR__ . '/../../../';

    public static function getClassesInNamespace($namespace)
    {
        $files = scandir(self::getNamespaceDirectory($namespace));
        $classes = array_map(function($file) use ($namespace){
            return $namespace . '\\' . str_replace('.php', '', $file);
        }, $files);

        return array_filter($classes, function($possibleClass){
            return class_exists($possibleClass);
        });
    }

    private static function getDefinedNamespaces()
    {
        $composerJsonPath = self::APP_ROOT . 'composer.json';
        $composerConfig = json_decode(file_get_contents($composerJsonPath));
        return (array) $composerConfig->autoload->{'psr-4'};
    }

    private static function getNamespaceDirectory($namespace)
    {
        $composerNamespaces = self::getDefinedNamespaces();
        $namespaceFragments = explode('\\', $namespace);
        $undefinedNamespaceFragments = [];
        while($namespaceFragments) {
            $possibleNamespace = implode('\\', $namespaceFragments) . '\\';
            if(array_key_exists($possibleNamespace, $composerNamespaces)){
                
                return realpath(self::APP_ROOT . $composerNamespaces[$possibleNamespace] .'/'. implode('/', $undefinedNamespaceFragments));
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));            
        }

        return false;
    }

}