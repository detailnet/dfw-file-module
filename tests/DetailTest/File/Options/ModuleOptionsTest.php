<?php

namespace DetailTest\File\Options;

class ModuleOptionsTest extends OptionsTestCase
{
    /**
     * @var \Detail\File\Options\ModuleOptions
     */
    protected $options;

    protected function setUp()
    {
        $this->options = $this->getOptions(
            'Detail\File\Options\ModuleOptions',
            array(
                'getStorageFactories',
                'setStorageFactories',
            )
        );
    }

    public function testStorageFactoriesCanBeSet()
    {
        $storageFactories = array(
            'some-storage-type' => 'Some/Storage/Factory/Class',
        );

        $this->assertTrue(is_array($this->options->getStorageFactories()));
        $this->assertEmpty($this->options->getStorageFactories());

        $this->options->setStorageFactories($storageFactories);

        $this->assertEquals($storageFactories, $this->options->getStorageFactories());
    }
}
