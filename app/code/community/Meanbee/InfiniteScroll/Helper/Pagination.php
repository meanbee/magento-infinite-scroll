<?php

class Meanbee_InfiniteScroll_Helper_Pagination extends Mage_Core_Helper_Abstract {

    /**
     * Get the Next Url for the Meta Tag.
     *
     * @return string
     * @param Mage_Core_Controller_Request_Http $request
     */
    public function getNextPage($request) {
        $params = $this->_getUrlParams($request);
        if(isset($params['p'])) {
            $params['p']++;
        } else {
            $params['p'] = 2;
        }

        return Mage::getUrl($this->_getUrlPath($request), array(
            '_query' => $params,
            '_nosid' => true
        ));
    }

    /**
     * Get the Previous Url for the Meta Tag.
     *
     * @return string
     * @param Mage_Core_Controller_Request_Http $request
     */
    public function getPreviousPage($request) {
        $params = $this->_getUrlParams($request);
        $params['p']--;
        if($params['p'] == 0) {
            unset($params['p']);
        }

        return Mage::getUrl($this->_getUrlPath($request), array(
            '_query' => $params,
            '_nosid' => true
        ));
    }

    /**
     * Check the next Meta Tag is available.
     *
     * @return bool
     * @param Mage_Core_Controller_Request_Http $request
     */
    public function isNextUrlAvailable($request){
        if($this->_getCurrentPage($request) <  $this->_getPageLimit()) {
            return true;
        }
        return false;
    }

    /**
     * Check the previous Meta Tag is available.
     *
     * @return bool
     * @param Mage_Core_Controller_Request_Http $request
     */
    public function isPreviousUrlAvailable($request){
        $current_page = $this->_getCurrentPage($request);
        if($current_page !== null && $current_page <= $this->_getPageLimit()) {
            return true;
        }
        return false;
    }

    /**
     * Return the current page number the browser is on.
     *
     * @return null|int
     * @param Mage_Core_Controller_Request_Http $request
     */
    protected function _getCurrentPage($request) {
        $params = $this->_getUrlParams($request);
        if(isset($params['p']))
            return $params['p'];
        else
            return null;
    }

    /**
     * Get the last page of the list page.
     *
     * @return string
     * @param Mage_Core_Controller_Request_Http $request
     */
    protected function _getPageLimit() {
        $block = Mage::helper('infinitescroll')->getProductListBlock();
        $block->getToolbarBlock()->setCollection($block->getLoadedProductCollection());
        return $block->getToolbarBlock()->getLastPageNum();
    }

    /**
     * Get the url path of the list page.
     *
     * @return string
     * @param Mage_Core_Controller_Request_Http $request
     */
    protected function _getUrlPath($request) {
        return substr($request->getOriginalPathInfo(), 1);
    }

    /**
     * Get the URL Parameters
     * Remove the list page's ID from the URL Parameters before return.
     *
     * @return array
     * @param Mage_Core_Controller_Request_Http $request
     */
    protected function _getUrlParams($request) {
        $params = $request->getParams();
        if(isset($params['id'])) {
            unset($params['id']);
        }

        return $params;
    }

    /**
     * @param $head_block
     * @param $handles
     *
     * @return bool
     */
    public function isInfiniteScrollAvailable($head_block, $handles)
    {
        return $head_block instanceof Mage_Core_Block_Template && in_array('infinitescroll', $handles);
    }

}
