<?php

namespace Detail\File\BackgroundProcessing\Driver;

trait DriverAwareTrait
{
    /**
     * @var DriverInterface
     */
    protected $backgroundDriver;

    /**
     * @param DriverInterface $backgroundDriver
     */
    public function setBackgroundDriver(DriverInterface $backgroundDriver)
    {
        $this->backgroundDriver = $backgroundDriver;
    }
}
