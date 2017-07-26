<?php
namespace
{

    class ModuleBlog extends Module
    {
        public function __construct()
        {
            $this->name = 'ModuleBlog';
            $this->tab = 'front_office_features';
            $this->version = '1.0.0';
            $this->author = 'Juan ROMERO';
            $this->need_instance = 0;
            $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
            $this->bootstrap = true;

            parent::__construct();

            $this->displayName = $this->l('moduleBlog');
            $this->description = $this->l('Description of my module for displaying a blog.');

            $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

            if (!Configuration::get('ModuleBlog')) {
                $this->warning = $this->l('No name provided');
            }
        }

        public function install()
        {
            if (Shop::isFeatureActive()) {
                Shop::setContext(Shop::CONTEXT_ALL);
            }

            if (!parent::install() ||
            !$this->registerHook('leftColumn') ||
            !$this->registerHook('header') ||
            !Configuration::updateValue('ModuleBlog', 'my friend')
        ) {
            return false;
        }

        return true;
        }

        public function uninstall()
        {
            if (!parent::uninstall() ||
            !Configuration::deleteByName('ModuleBlog')
            ) {
                return false;
            }

            return true;
        }
    }
}
