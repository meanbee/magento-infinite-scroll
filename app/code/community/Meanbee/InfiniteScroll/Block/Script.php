<?php
class Meanbee_InfiniteScroll_Block_Script extends Mage_Core_Block_Template {
    public function getPagerSelector() {
        return '.pager';
    }

    public function getBottomToolbarSelector() {
        return '.toolbar-bottom';
    }

    public function getButtonSelector() {
        return '.meanbee-infinitescroll-button';
    }

    public function getBusySelector() {
        return '.meanbee-infinitescroll-busy';
    }

    public function getGridItemSelector() {
        return '.products-grid';
    }

    public function getGridContainerSelector() {
        return '.products-grid:last';
    }

    public function getGridContainerAction() {
        return 'after';
    }

    public function getListItemSelector() {
        return '.item';
    }

    public function getListContainerSelector() {
        return '.products-list';
    }

    public function getListContainerAction() {
        return 'bottom';
    }

    public function isAutoscrollEnabled() {
        return true;
    }

    public function getScrollDistance() {
        return 500;
    }

    public function getCategoryDisplayMode() {
        return $this->getLayout()->getBlock('product_list')->getMode();
    }

    public function getRequestParametersJson() {
        return Mage::helper('core')->jsonEncode(
            $this->getRequest()->getParams()
        );
    }

    public function getEndpoint() {
        return $this->getUrl('infinitescroll/ajax/fetch');
    }

    public function hasMorePages() {
        return !$this->getLayout()->getBlock('product_list_toolbar_pager')->isLastPage();
    }
}
