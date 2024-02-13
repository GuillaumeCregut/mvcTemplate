<?php

/**
 * Class SmartyMKD
 * Use smarty with all datas configured
 */

namespace Editiel98\Templates;

require_once(dirname((__FILE__)) . '/smarty/Smarty.class.php');

class SmartyEditiel extends \Smarty
{
   public function __construct()
   {
      parent::__construct();
      $baseDir = __DIR__ . '/../../';
      $this->template_dir = $baseDir . 'templates/';
      $this->compile_dir = $baseDir . 'templates_c/';
      $this->assign('app_name', 'Models Kit Database');
   }
}
