<?php
class moduleblogdisplayModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('display.tpl');

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('post');

        $posts = [];

        if ($post_list = Db::getInstance()->executeS($sql)) {

            foreach ($post_list as $post) {
                $post['link'] = $this->context->link->getModuleLink('moduleblog', 'detail', array('id'=> $post['id_post']));
                $posts[] = $post;
            }
        }

        $this->context->smarty->assign(array('posts' => $posts));
    }
}
