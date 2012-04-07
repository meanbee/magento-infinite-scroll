<?php
class Meanbee_InfiniteScroll_Helper_Cache extends Mage_Core_Helper_Abstract {
    const CACHE_KEY = 'meanbee_infinitescroll/product_cache_buildtime';
    const CACHE_LIFETIME = 3600; // At most, an hour

    /** @var Mage_Core_Model_Cache */
    protected $_cache;

    public function __construct() {
        $this->_cache = Mage::getSingleton('core/cache');
    }

    public function getCacheBuildTimestamp() {
        $timestamp = $this->_cache->load(self::CACHE_KEY);

        if ($timestamp === false) {
            $timestamp = $this->_setCacheBuildTimestamp();
        }

        return $timestamp;
    }

    protected function _setCacheBuildTimestamp() {
        $timestamp = time();

        $this->_cache->save($timestamp, self::CACHE_KEY, array('BLOCK_HTML'), self::CACHE_LIFETIME);

        return $timestamp;
    }
}
