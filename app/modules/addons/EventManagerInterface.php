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

namespace app\modules\addons;

/**
 * Interface that must be implemented by each sub-module.
 *
 * This interface defines basic methods to attaches events
 * to all app through Addons Module.
 */
interface EventManagerInterface
{

    /**
     * Global Event Handlers
     *
     * Structure: [
     *      $eventName => $eventHandler
     * ]
     *
     * @return void|array events
     */
    public function attachGlobalEvents();

    /**
     * Class-Level Event Handlers
     *
     * Structure: [
     *      $eventSenderClassName => [
     *          $eventName => [
     *              [$handlerClassName, $handlerMethodName]
     *          ]
     *      ]
     * ]
     *
     * @return void|array events
     */
    public function attachClassEvents();
}
