<?php

class Meanbee_InfiniteScroll_Model_Observer {

    /**
     * @param Varien_Event_Observer $observer
     */
    public function  addPaginationTags(Varien_Event_Observer $observer) {
        /** @var Meanbee_InfiniteScroll_Helper_Pagination $helper */
        $helper = Mage::helper('infinitescroll/pagination');
        /** @var Mage_Core_Controller_Request_Http $request */
        $request = $observer->getAction()->getRequest();
        /** @var Mage_Core_Model_Layout $layout */
        $layout = $observer->getLayout();
        $handles = $layout->getUpdate()->getHandles();
        /** @var Mage_Page_Block_Html_Head $head_block */
        $head_block = $layout->getBlock('head');
        if(!$helper->isInfiniteScrollAvailable($head_block, $handles)) {
            return;
        }
        if($helper->isNextUrlAvailable($request)) {
            $head_block->addLinkRel('next', $helper->getNextPage($request));
        }

        if($helper->isPreviousUrlAvailable($request)) {
            $head_block->addLinkRel('prev', $helper->getPreviousPage($request));
        }

    }

}
