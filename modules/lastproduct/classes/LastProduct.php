<?php
namespace

{
    class LastProduct extends Module
    {

        public function __construct()
        {
            $this->name = 'lastproduct';
            $this->tab = 'front_office_features';
            $this->version = '1.0.0';
            $this->author = 'Juan ROMERO';
            $this->need_instance = 0;
            $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
            $this->bootstrap = true;

            parent::__construct();

            $this->displayName = $this->l('Last product');
            $this->description = $this->l('Module to display last product added');

            $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

            if (!Configuration::get('LastProduct')) {
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
                || !Configuration::updateValue('LastProduct', 'my friend')
            ) {
                return false;
            }

            return true;
        }

        public function uninstall()
        {
            if (!parent::uninstall()
                || !Configuration::deleteByName('LastProduct')
            ) {
                return false;
            }
            return true;
        }

        public function getContent()
        {
            $output = null;
            if (Tools::isSubmit('submit'.$this->name)) {
                $my_module_name = strval(Tools::getValue('LastProduct'));
                if (!$my_module_name
                    || empty($my_module_name)
                    || !Validate::isGenericName($my_module_name)) {
                    $output .= $this->displayError($this->l('Invalid Configuration value'));
                } else {
                    Configuration::updateValue('LastProduct', $my_module_name);
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
                        'name' => 'LastProduct',
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
            $helper->fields_value['LastProduct'] = Configuration::get('LastProduct');
            return $helper->generateForm($fields_form);
        }

        public function hookDisplayLeftColumn($params)
        {
            $productObj = new Product();
            $last_products = $productObj->getProducts(Context::getContext()->language->id, 0, 0, 'id_product', 'DESC', false, true)[0];

            $this->context->smarty->assign(
                array(
                    'my_module_name' => Configuration::get('LastProduct'),
                    'my_module_link' => $this->context->link->getModuleLink('mymodule', 'display'),
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
            $this->context->controller->addCSS($this->_path.'css/lastproduct.css', 'all');
        }
    }
}
