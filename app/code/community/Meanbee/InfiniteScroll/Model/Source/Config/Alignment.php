<?php
class Meanbee_InfiniteScroll_Model_Source_Config_Alignment {
    public function toOptionArray() {
        $positions = array('top', 'bottom', 'before', 'after');

        $return = array();

        foreach ($positions as $position) {
            $return[] = array(
                'value' => $position,
                'label' => Mage::helper('infinitescroll')->__(ucfirst($position))
            );
        }

        return $return;
    }

}
