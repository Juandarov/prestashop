<?php
namespace

{
    class MyModule extends Module
    {

        public function __construct()
        {
            $this->name = 'mymodule';
            $this->tab = 'front_office_features';
            $this->version = '1.0.0';
            $this->author = 'Juan ROMERO';
            $this->need_instance = 0;
            $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
            $this->bootstrap = true;

            parent::__construct();

            $this->displayName = $this->l('My module');
            $this->description = $this->l('Description of my module.');

            $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

            if (!Configuration::get('MyModule')) {
                $this->warning = $this->l('No name provided');
            }
        }

        public function install()
        {
            if (Shop::isFeatureActive()) {
                Shop::setContext(Shop::CONTEXT_ALL);
            }

            if (!parent::install()
                || !$this->registerHook('leftColumn')
                || !$this->registerHook('header')
                || !Configuration::updateValue('MyModule', 'my friend')
            ) {
                return false;
            }

            return true;
        }

        public function uninstall()
        {
            if (!parent::uninstall()
                || !Configuration::deleteByName('MyModule')
            ) {
                return false;
            }
            return true;
        }

        public function getContent()
        {
            $output = null;
            if (Tools::isSubmit('submit'.$this->name)) {
                $my_module_name = strval(Tools::getValue('MyModule'));
                if (!$my_module_name
                    || empty($my_module_name)
                    || !Validate::isGenericName($my_module_name)) {
                    $output .= $this->displayError($this->l('Invalid Configuration value'));
                } else {
                    Configuration::updateValue('MyModule', $my_module_name);
                    $output .= $this->displayConfirmation($this->l('Settings updated'));
                }
            }
            return $output.$this->displayForm();
        }

        public function displayForm()
        {
            // Get default language
            $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

            // Init Fields form array
            $fields_form[0]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Configuration value'),
                        'name' => 'MyModule',
                        'size' => 20,
                        'required' => true
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            );

            $helper = new HelperForm();

            // Module, token and currentIndex
            $helper->module = $this;
            $helper->name_controller = $this->name;
            $helper->token = Tools::getAdminTokenLite('AdminModules');
            $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

            // Language
            $helper->default_form_language = $default_lang;
            $helper->allow_employee_form_lang = $default_lang;

            // Title and toolbar
            $helper->title = $this->displayName;
            $helper->show_toolbar = true;        // false -> remove toolbar
            $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
            $helper->submit_action = 'submit'.$this->name;
            $helper->toolbar_btn = array(
                'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                    '&token='.Tools::getAdminTokenLite('AdminModules'),
                ),
                'back' => array(
                    'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                    'desc' => $this->l('Back to list')
                )
            );

            // Load current value
            $helper->fields_value['MyModule'] = Configuration::get('MyModule');
            return $helper->generateForm($fields_form);
        }

        public function hookDisplayLeftColumn($params)
        {
            $productObj = new Product();
    		$products = count($productObj->getProducts(Context::getContext()->language->id, 0, 0, 'id_product', 'DESC', false, true));
            $last_products = $productObj->getProducts(Context::getContext()->language->id, 0, 0, 'id_product', 'DESC', false, true)[0];

            $this->context->smarty->assign(
                array(
                    'my_module_name' => Configuration::get('MyModule'),
                    'my_module_link' => $this->context->link->getModuleLink('mymodule', 'display'),
                    'my_products_quantities' => $products,
                    'my_last_products' => $last_products['name']
                )
            );
            return $this->display(_PS_MODULE_DIR_."mymodule", 'mymodule.tpl');
        }

        public function hookDisplayRightColumn($params)
        {
            return $this->hookDisplayLeftColumn($params);
        }

        public function hookDisplayHeader()
        {
            $this->context->controller->addCSS($this->_path.'css/mymodule.css', 'all');
        }
    }
}
