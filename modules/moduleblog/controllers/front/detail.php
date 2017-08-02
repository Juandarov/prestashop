<?php
class moduleblogdetailModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('detail.tpl');

        $post_id = Tools::getValue('id');
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('post');
        $sql->where('id_post ='.$post_id);

        if ($post = Db::getInstance()->executeS($sql)) {
            $this->context->smarty->assign(array('post' => $post));
        }


    }
}
