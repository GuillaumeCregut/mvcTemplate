<?php

/**
 * Class SmartyMKD
 * Use smarty with all datas configured
 */

namespace Editiel98\Templates;

use Editiel98\Kernel\ClassFinder;
use ReflectionClass;

require_once(dirname((__FILE__)) . '/smarty/Smarty.class.php');

class SmartyEditiel extends \Smarty
{
   public function __construct()
   {
      parent::__construct();
      $baseDir = __DIR__ . '/../../';
      $this->template_dir = $baseDir . 'templates/';
      $this->compile_dir = $baseDir . 'templates_c/';
      $this->getPlugin();
   }

   private function getPlugin()
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
