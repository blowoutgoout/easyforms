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

namespace app\components;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\helpers\Json;
use \PetrGrishin\ArrayAccess\ArrayAccess;

/**
 * Class JsonToArrayBehavior
 * @package app\components
 *
 * Automatically converts a json field to PHP array.
 *
 * To use JsonToArrayBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use app\components\JsonToArrayBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => JsonToArrayBehavior::className(),
 *             'jsonFieldName' => '',
 *         ],
 *     ];
 * }
 */
class JsonToArrayBehavior extends Behavior
{

    /**
     * @var string the attribute that will load json data
     * Set this property is required if you do want to load the json field as array.
     */
    public $jsonFieldName;

    /**
     * @var ArrayAccess object PHP multi array access
     * Create a string path to get/set a assoc arrays
     */
    private $arrayAccess;

    /**
     * @inheritdoc
     */
    public function getJsonFieldName()
    {
        if (!$this->jsonFieldName) {
            throw new \Exception(Yii::t("app", 'JSONField attribute is empty'));
        }
        return $this->jsonFieldName;
    }

    /**
     * @inheritdoc
     */
    public function setJsonFieldName($jsonFieldName)
    {
        $this->jsonFieldName = $jsonFieldName;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getJson()
    {
        return $this->getModel()->getAttribute($this->getJsonFieldName());
    }

    /**
     * @inheritdoc
     */
    public function setJson($json)
    {
        $this->getModel()->setAttribute($this->getJsonFieldName(), $json);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return array(
            ActiveRecord::EVENT_AFTER_FIND => 'loadArray',
        );
    }

    /**
     * @inheritdoc
     */
    public function getValue($path, $defaultValue = null)
    {
        return $this->arrayAccess->getValue($path, $defaultValue);
    }

    /**
     * @inheritdoc
     */
    public function setValue($path, $value)
    {
        $this->arrayAccess->setValue($path, $value);
        $this->saveArray();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setSafeValue($path, $value)
    {
        $this->arrayAccess->getValue($path); // Check if keys exists
        $this->arrayAccess->setValue($path, $value);
        $this->saveArray();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getArray()
    {
        return $this->arrayAccess->getArray();
    }

    /**
     * @inheritdoc
     */
    public function setArray(array $array)
    {
        $this->arrayAccess->setArray($array);
        $this->saveArray();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function saveArray()
    {
        $this->setJson($this->encode($this->getArray()));
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function loadArray()
    {
        $this->arrayAccess = ArrayAccess::create($this->decode($this->getJson()));
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        if ($this->getModel()->getAttribute('updated_at')) {
            if (!$this->getModel()->save(false, array($this->getJsonFieldName(), 'updated_at'))) {
                throw new \Exception(sprintf('Error saving Fields: `%s` and `updated_at`', $this->getJsonFieldName()));
            }
        } else {
            if (!$this->getModel()->save(false, array($this->getJsonFieldName()))) {
                throw new \Exception(sprintf('Error saving Json Field: `%s`.', $this->getJsonFieldName()));
            }
        }

        return $this;
    }

    /************************
     *
     * Protected methods
     *
     ************************/

    /**
     * Check that owner is an ActiveRecord model class
     *
     * @return \yii\db\ActiveRecord
     * @throws \Exception
     */
    protected function getModel()
    {

        // See https://github.com/yiisoft/yii2/issues/9129
        if (!$model = $this->owner) {
            throw new \Exception('Model has not been established');
        }
        if (!$model instanceof ActiveRecord) {
            throw new \Exception(sprintf(
                'Behavior is available only to the class model, the current class `%s`',
                get_class($model)
            ));
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    protected function encode($value)
    {
        return Json::htmlEncode($value);
    }

    /**
     * @inheritdoc
     */
    protected function decode($data)
    {
        return Json::decode($data, true);
    }
}
