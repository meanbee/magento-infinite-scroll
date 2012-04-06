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
}
