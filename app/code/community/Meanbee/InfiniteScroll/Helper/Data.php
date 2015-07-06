<?php
class Meanbee_InfiniteScroll_Helper_Data extends Mage_Core_Helper_Data {
    public function getProductListBlock() {
        $names = array('product_list', 'search_result_list');

        foreach ($names as $name) {
            $block = Mage::app()->getLayout()->getBlock($name);

            if ($block !== false) {
                return $block;
            }
        }

        return false;
    }

    public function hasLayoutHandle($handle_arg) {
        foreach (Mage::app()->getLayout()->getUpdate()->getHandles() as $handle) {
            if ($handle == $handle_arg) {
                return true;
            }
        }

        return false;
    }

    public function getEndpoint() {
        $action = false;

        if (Mage::registry('current_category')) {
            $action = 'category';
        } else if (Mage::helper('infinitescroll')->hasLayoutHandle('catalogsearch_result_index')) {
            $action = 'search';
        }

        if ($action !== false) {

            $url = Mage::getUrl('infinitescroll/ajax/' . $action);
            return preg_replace('/^https?:/', '', $url);
        } else {
            return false;
        }
    }

    public function getCookieKey($endpoint, $parameters) {
        /** @var $cache Meanbee_InfiniteScroll_Helper_Cache */
        $cache = Mage::helper('infinitescroll/cache');
        
        // We need to remove the page parameter or the generated hash, which forms the cookie name, does not match that
        // which the javascript is expecting it to be.
        if(isset ($parameters['p'])) {
            unset($parameters['p']);
        }

        return 'meanbee_infinitescroll/' . $cache->getCacheBuildTimestamp() . '/' . md5($endpoint . Mage::helper('core')->jsonEncode(
            $parameters
        ));
    }
}
