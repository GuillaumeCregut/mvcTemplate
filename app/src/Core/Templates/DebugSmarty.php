<?php

namespace Editiel98\Templates;

class DebugSmarty extends AbstractSmarty
{
    public function __construct()
    {
        $templateDir = __DIR__ . '/';
        $compileDir = __DIR__ . '/../../' . 'templates_c/';
        parent::__construct($templateDir, $compileDir);
    }

    public function displayError(string $error): void
    {

        $this->displayTemplate($error);
    }

    public function fetchTemplate($name): string
    {
        return parent::fetch($name);
    }
}
