<?php
class Meanbee_InfiniteScroll_AjaxController extends Mage_Core_Controller_Front_Action {
    public function fetchAction() {
        $return = array(
            'status'  => 'error',
            'content' => 'An unknown error occurred'
        );

        $category_id = $this->getRequest()->getParam('id', false);

        if ($category_id !== false && is_numeric($category_id)) {
            Mage::register('current_category', Mage::getModel('catalog/category')->load($category_id));

            $this->loadLayout();

            $content_block = $this->_getProductListBlock();
            $toolbar_block = $this->_getToolbarBlock();

            if ($content_block !== false) {
                $return['status']  = 'success';
                $return['content'] = array(
                    "block" => $content_block->toHtml(),
                    "last"  => $toolbar_block->isLastPage()
                );
            } else {
                $return['content'] = 'Unable to load appropriate block';
            }
        } else {
            $return['content'] = 'No category provided';
        }

        $this->getResponse()->setHeader('Content-Type', 'application/json', true)->setBody(Mage::helper('core')->jsonEncode($return));
    }

    protected function _getProductListBlock() {
        return $this->getLayout()->getBlock('product_list');
    }

    protected function _getToolbarBlock() {
        return $this->getLayout()->getBlock('product_list_toolbar_pager');
    }
}
