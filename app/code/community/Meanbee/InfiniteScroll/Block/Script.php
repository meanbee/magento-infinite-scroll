<?php
class Meanbee_InfiniteScroll_Block_Script extends Mage_Core_Block_Template {
    /**
     * @return Meanbee_InfiniteScroll_Helper_Config
     */
    public function getConfig() {
        return Mage::helper('infinitescroll/config');
    }

    public function getPagerSelector() {
        return $this->getConfig()->getPagerSelector();
    }

    public function getTopToolbarSelector() {
        return $this->getConfig()->getTopToolbarSelector();
    }

    public function getBottomToolbarSelector() {
        return $this->getConfig()->getBottomtToolbarSelector();
    }

    public function getButtonSelector() {
        return '.meanbee-infinitescroll-button';
    }

    public function getBusySelector() {
        return '.meanbee-infinitescroll-busy';
    }

    public function getGridItemSelector() {
        return $this->getConfig()->getGridItemSelector();
    }

    public function getGridContainerSelector() {
        return $this->getConfig()->getGridContainerSelector();
    }

    public function getGridContainerAction() {
        return $this->getConfig()->getGridInsertionAction();
    }

    public function getListItemSelector() {
        return $this->getConfig()->getListItemSelector();
    }

    public function getListContainerSelector() {
        return $this->getConfig()->getListContainerSelector();
    }

    public function getListContainerAction() {
        return $this->getConfig()->getListInsertionAction();
    }

    public function isAutoscrollEnabled() {
        return $this->getConfig()->isAutoFetchEnabled();
    }

    public function getScrollDistance() {
        return 500;
    }

    public function isScrollablePage() {
        /** @var $block Mage_Catalog_Block_Product_List */
        $block = Mage::helper('infinitescroll')->getProductListBlock();

        return (
            $block !== false &&
            $block->getChild('toolbar') !== false &&
            $this->_getPagerBlock() !== false &&
            $this->_getPagerBlock()->getCollection()
        );
    }

    public function getCategoryDisplayMode() {
        /** @var $block Mage_Catalog_Block_Product_List */
        $block = Mage::helper('infinitescroll')->getProductListBlock();

        if ($block !== false && $block->getChild('toolbar') !== false) {
            return $block->getMode();
        }

        Mage::throwException('Unable to locate product listing block');
    }

    public function getRequestParametersJson() {
        return Mage::helper('core')->jsonEncode(
            $this->getRequest()->getParams()
        );
    }

    public function getEndpoint() {
        return Mage::helper('infinitescroll')->getEndpoint();
    }

    public function isShowAllEnabled() {
        return $this->getConfig()->isShowAllLinkEnabled();
    }

    public function getShowAllUrl() {
        return $this->_getPagerBlock()->getLimitUrl('all');
    }

    public function getCookieKey() {
        return Mage::helper('infinitescroll')->getCookieKey($this->getEndpoint(), $this->getRequest()->getParams());
    }

    public function hasMorePages() {
        return !$this->_getPagerBlock()->isLastPage();
    }

    /**
     * @return Mage_Page_Block_Html_Pager
     */
    protected function _getPagerBlock() {
        return $this->getLayout()->getBlock('product_list_toolbar_pager');
    }

    /**
     * Check Infinite Scroll is enabled.
     * Check The Browser is not using pagination.
     *
     * @return string
     * @throws Exception
     */
    protected function _toHtml() {
        if ($this->getConfig()->isEnabled() && !$this->getRequest()->getParam('p')) {
            return parent::_toHtml();
        } else {
            return '';
        }
    }
}
