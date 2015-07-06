<?php
class Meanbee_InfiniteScroll_AjaxController extends Mage_Core_Controller_Front_Action {
    public function categoryAction() {
        $return = array(
            'status'  => 'error',
            'content' => 'An unknown error occurred'
        );

        $category_id = $this->getRequest()->getParam('id', false);

        if ($category_id !== false && is_numeric($category_id)) {
            Mage::register('current_category', Mage::getModel('catalog/category')->load($category_id));
            $return = $this->_getReturnContent();
        } else {
            $return['content'] = 'No category provided';
        }

        $this->_setCookie('category', $this->getRequest()->getParam('p', 1));

        $this->getResponse()->setHeader('Content-Type', 'application/json', true)->setBody(Mage::helper('core')->jsonEncode($return));
    }

    public function searchAction() {
        $return = $this->_getReturnContent();
        $this->getResponse()->setHeader('Content-Type', 'application/json', true)->setBody(Mage::helper('core')->jsonEncode($return));
    }

    protected function _getReturnContent() {
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
            $return['status']  = 'error';
            $return['content'] = 'Unable to load appropriate block';
        }

        return $return;
    }

    protected function _getProductListBlock() {
        return $this->_getHelper()->getProductListBlock();
    }

    protected function _getToolbarBlock() {
        return $this->getLayout()->getBlock('product_list_toolbar_pager');
    }

    protected function _setCookie($key, $value) {
        /** @var $cookie Mage_Core_Model_Cookie */
        $cookie = Mage::getSingleton('core/cookie');
        $cookie->set(
            $this->_getHelper()->getCookieKey(Mage::helper('infinitescroll')->getEndpoint(), $this->getRequest()->getParams()),
            $value, null, null, null, null, false
        );
    }

    /**
     * @return Meanbee_InfiniteScroll_Helper_Data
     */
    protected function _getHelper() {
        return Mage::helper('infinitescroll');
    }
}
