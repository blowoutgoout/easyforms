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

namespace app\components\analytics\storage;

use Yii;
use app\models\Event;

/**
 * Class Storage
 * @package app\components\analytics\storage
 */
class Storage
{

    /**
     * Save event data in the database
     *
     * @param array $data
     * @throws \Exception
     */
    public function save(array $data)
    {

        // Check event type
        if (!$this->hasValidEventType($data)) {
            throw new \Exception(\Yii::t("app", "Event has a invalid type: Should not be recorded."));
        }

        // Prepare the data for the model validation
        $eventData = [
            'Event' => $data,
        ];

        // New Event Model
        $eventModel = new Event();

        // Populate Event Model and Save (with validation)
        if (!$eventModel->load($eventData) || !$eventModel->save()) {
            throw new \Exception(Yii::t("app", "Error saving Event Model"));
        }
        
    }

    /**
     * Check if data array has a valid event type
     *
     * @param array $data
     * @return bool
     */
    protected function hasValidEventType(array $data)
    {
        // If the event type is not defined
        if (!isset($data["event"])) {
            return false;
        }

        // Valid events: Page views, Structured events
        $validEvents = array("pv", "se");

        // Valid SE Category
        $validCategories = array("form");

        // Check if the event is valid
        if (!in_array($data["event"], $validEvents)) {
            return false;
        }

        // Check if structured event is valid
        if ($data["event"] == "se" && !in_array($data["se_category"], $validCategories)) {
            return false;
        }

        return true;

    }
}
