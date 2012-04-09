<?php
class Meanbee_InfiniteScroll_Helper_Config extends Mage_Core_Helper_Abstract {
    const XML_ENABLED = 'infinitescroll/display/enabled';
    const XML_AUTO_FETCH = 'infinitescroll/display/auto_scroll';

    const XML_CSS_PAGER = 'infinitescroll/css_selector/pager';
    const XML_CSS_TOOLBAR_TOP = 'infinitescroll/css_selector/toolbar_top';
    const XML_CSS_TOOLBAR_BOTTOM = 'infinitescroll/css_selector/toolbar_bottom';

    const XML_CSS_LIST_ITEM = 'infinitescroll/css_selector/list_item';
    const XML_CSS_LIST_CONTAINER = 'infinitescroll/css_selector/list_container';
    const XML_CSS_LIST_ACTION = 'infinitescroll/css_selector/list_action';

    const XML_CSS_GRID_ITEM = 'infinitescroll/css_selector/grid_item';
    const XML_CSS_GRID_CONTAINER = 'infinitescroll/css_selector/grid_container';
    const XML_CSS_GRID_ACTION = 'infinitescroll/css_selector/grid_action';

    const XML_CORE_SHOW_ALL_ENABLED = 'catalog/frontend/list_allow_all';

    public function isEnabled() {
        return $this->_getConfigFlag(self::XML_ENABLED);
    }

    public function isAutoFetchEnabled() {
        return $this->_getConfigFlag(self::XML_AUTO_FETCH);
    }

    public function getPagerSelector() {
        return $this->_getConfigValue(self::XML_CSS_PAGER);
    }

    public function getBottomtToolbarSelector() {
        return $this->_getConfigValue(self::XML_CSS_TOOLBAR_BOTTOM);
    }

    public function getTopToolbarSelector() {
        return $this->_getConfigValue(self::XML_CSS_TOOLBAR_TOP);
    }

    public function getListItemSelector() {
        return $this->_getConfigValue(self::XML_CSS_LIST_ITEM);
    }

    public function getListContainerSelector() {
        return $this->_getConfigValue(self::XML_CSS_LIST_CONTAINER);
    }

    public function getListInsertionAction() {
        return $this->_getConfigValue(self::XML_CSS_LIST_ACTION);
    }

    public function getGridItemSelector() {
        return $this->_getConfigValue(self::XML_CSS_GRID_ITEM);
    }

    public function getGridContainerSelector() {
        return $this->_getConfigValue(self::XML_CSS_GRID_CONTAINER);
    }

    public function getGridInsertionAction() {
        return $this->_getConfigValue(self::XML_CSS_GRID_ACTION);
    }

    public function isShowAllLinkEnabled() {
        return $this->_getConfigFlag(self::XML_CORE_SHOW_ALL_ENABLED);
    }

    /**
     * @param $path
     * @return string
     */
    protected function _getConfigValue($path) {
        return Mage::getStoreConfig($path);
    }

    /**
     * @param $path
     * @return bool
     */
    protected function _getConfigFlag($path) {
        return Mage::getStoreConfigFlag($path);
    }
}
