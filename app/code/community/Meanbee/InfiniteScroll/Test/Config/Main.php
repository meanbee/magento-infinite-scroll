<?php
class Meanbee_InfiniteScroll_Test_Config_Main extends EcomDev_PHPUnit_Test_Case_Config {
    public function testBlockAlias() {
        $this->assertBlockAlias('infinitescroll/test', 'Meanbee_InfiniteScroll_Block_Test');
    }

    public function testModelAlias() {
        $this->assertModelAlias('infinitescroll/test', 'Meanbee_InfiniteScroll_Model_Test');
    }

    public function testHelperAlias() {
        $this->assertHelperAlias('infinitescroll/test', 'Meanbee_InfiniteScroll_Helper_Test');
    }

    public function testCodepool() {
        $this->assertModuleCodePool('community');
    }

    public function testDepends() {
        $this->assertModuleDepends('Mage_Catalog');
    }

    public function testLayoutFile() {
        $this->assertLayoutFileDefined('frontend', 'meanbee/infinitescroll.xml');
        $this->assertLayoutFileExistsInTheme('frontend', 'meanbee/infinitescroll.xml', 'default', 'base');
    }
}
