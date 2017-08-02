<?php
namespace
{

    class ModuleBlog extends Module
    {
        public function __construct()
        {
            $this->name = 'moduleblog';
            $this->tab = 'front_office_features';
            $this->version = '1.0.0';
            $this->author = 'Juan ROMERO';
            $this->need_instance = 0;
            $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
            $this->bootstrap = true;

            parent::__construct();

            $this->displayName = $this->l('moduleblog');
            $this->description = $this->l('Description of my module for displaying a blog.');

            $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

            if (!Configuration::get('ModuleBlog')) {
                $this->warning = $this->l('No name provided');
            }
        }


        public function installdb()
        {
            return Db::getInstance()->Execute('
            CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'post (
                `id_post` int(11) NOT NULL AUTO_INCREMENT,
                `title`  char(100) NOT NULL,
                `date_add` datetime NOT NULL,
                `body` text NOT NULL,
                PRIMARY KEY (`id_post`)
                ) ENGINE= '._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
                ');
            }

            public function installTab()
            {
                $parent_tab = new Tab();
                $parent_tab->name[$this->context->language->id] = $this->l('Blog');
                $parent_tab->class_name = 'AdminModuleBlog';
                $parent_tab->id_parent = 0; // Home tab
                $parent_tab->module = $this->name;
                return $parent_tab->add();
            }

            public function install()
            {
                if (Shop::isFeatureActive()) {
                    Shop::setContext(Shop::CONTEXT_ALL);
                }

                if (!parent::install() ||
                !$this->registerHook('leftColumn') ||
                !$this->registerHook('header') ||
                !$this->installdb() ||
                !$this->installTab()
                ) {
                    return false;
                }

                return true;
            }



            public function uninstalldb() {

                return Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'post');
            }

            public function uninstall()
            {
                $tab = new Tab((int)Tab::getIdFromClassName('AdminModuleBlog'));
                $tab -> delete();

                if (!parent::uninstall() ||
                !Configuration::deleteByName('ModuleBlog')
                ) {
                    return false;
                }

                return true;
            }

            public function hookDisplayHeader()
            {
                $this->context->controller->addCSS($this->_path.'css/blog.css', 'all');
            }

        }
    }
