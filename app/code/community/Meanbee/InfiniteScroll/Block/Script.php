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

    public function getBottomToolbarSelector() {
        return $this->getConfig()->getToolbarSelector();
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

    public function getCategoryDisplayMode() {
        $block = Mage::helper('infinitescroll')->getProductListBlock();

       if ($block !== false) {
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
        $action = false;

        if (Mage::registry('current_category')) {
            $action = 'category';
        } else if (Mage::helper('infinitescroll')->hasLayoutHandle('catalogsearch_result_index')) {
            $action = 'search';
        }

        if ($action !== false) {
            return $this->getUrl('infinitescroll/ajax/' . $action);
        } else {
            return false;
        }
    }

    public function hasMorePages() {
        return !$this->getLayout()->getBlock('product_list_toolbar_pager')->isLastPage();
    }

    protected function _toHtml() {
        if ($this->getConfig()->isEnabled()) {
            return parent::_toHtml();
        } else {
            return '';
        }
    }
}
