<?php
namespace

{
    class MyModuleDisplayModuleFrontController extends ModuleFrontController
    {
        public function initContent()
        {
            parent::initContent();
            $this->setTemplate('lastproduct.tpl');
        }
    }
}
