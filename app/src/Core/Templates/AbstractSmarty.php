<?php

namespace Editiel98\Templates;

require_once(dirname((__FILE__)) . '/smarty/Smarty.class.php');

abstract class AbstractSmarty extends \Smarty
{
    public function __construct(string $templateDir, string $compileDir)
    {
        parent::__construct();
        $this->template_dir = $templateDir;
        $this->compile_dir = $compileDir;
    }

    /**
     * @param string $filename
     *
     * @return void
     */
    public function displayTemplate(string $filename): void
    {
        parent::display($filename);
    }

    /**
     * @param string $tpl_var
     * @param mixed $tpl_value
     *
     * @return void
     */
    public function assignVar(string $tpl_var, mixed $tpl_value): void
    {
        parent::assign($tpl_var, $tpl_value);
    }
}
