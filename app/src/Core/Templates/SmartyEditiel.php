<?php

/**
 * Class SmartyMKD
 * Use smarty with all datas configured
 */

namespace Editiel98\Templates;

use Editiel98\Kernel\ClassFinder;
use ReflectionClass;

require_once(dirname((__FILE__)) . '/smarty/Smarty.class.php');

class SmartyEditiel extends AbstractSmarty
{
    public function __construct()
    {
        $baseDir = __DIR__ . '/../../';
        $templateDir = $baseDir . 'templates/';
        $compileDir = $baseDir . 'templates_c/';
        parent::__construct($templateDir, $compileDir);
        $this->getPlugin();
    }

    public function fetchTemplate(string $filename): string
    {
        return parent::fetch($filename);
    }

    /**
     * @return void
     */
    private function getPlugin(): void
    {
        $plugins = ClassFinder::getClassesInNamespace('Editiel98\\Templates');
        foreach ($plugins as $plugin) {
            if (get_parent_class($plugin) === 'Editiel98\Templates\AbstractPlugin') {
                $class = new ReflectionClass($plugin);
                $r = $class->newInstance();
                $name = $r->getName();
                $this->registerPlugin('function', $name, [$r, 'display']);
            }
        }
    }
}
