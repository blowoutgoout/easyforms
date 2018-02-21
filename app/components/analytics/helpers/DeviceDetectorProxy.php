<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\components\analytics\helpers;

use DeviceDetector\DeviceDetector;

/**
 * Class DeviceDetectorProxy
 * @package app\components\analytics\helpers
 */
class DeviceDetectorProxy extends Singleton
{
    private $userAgent;
    private $deviceDetector;
    private $isBot;

    public function __construct(DeviceDetector $deviceDetector, $userAgent)
    {
        $this->deviceDetector = $deviceDetector;
        $this->userAgent = $userAgent;
    }

    public function isBot()
    {
        if (is_null($this->isBot)) {
            $this->isBot = $this->deviceDetector->isBot();
        }

        return $this->isBot;
    }
}
