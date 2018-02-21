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

namespace app\helpers;

use Yii;
use yii\helpers\Html;

/**
 * Class Honeypot
 * @package app\helpers
 */
class Honeypot
{
    /**
     * @var string Form html before processing.
     */
    public $data;

    /**
     * @var string Processed Form html.
     */
    protected $processedData;

    /**
     * Constructor.
     *
     * @param null $data Html of the form
     */
    public function __construct($data = null)
    {
        if (!is_null($data)) {
            $this->data = $data;
            $this->run();
        }
    }

    /**
     * @return string The form processed Html
     */
    public function getData()
    {
        if (isset($this->processedData) && !is_null($this->processedData)) {
            return $this->processedData;
        }
        return $this->data;
    }

    /**
     * Process the form html code,
     * groups form components in fieldsets and add navigation links
     */
    protected function run()
    {
        $this->processedData = str_replace("</form>", $this->honeypot(), $this->data);
    }

    protected function honeypot()
    {
        $field  = Html::beginTag('div', [
            'class' => '',
            'style' => 'display:none',
        ]);
        $field .= Html::label(Yii::t('app', 'Excuse me, but leave this field in blank'), "_email", [
            'class' => 'control-label',
        ]);
        $field .= Html::textInput("_email", null, [
            'id' => '_email',
            'class' => 'form-control',
        ]);
        $field .= Html::endTag('div') . ' ';
        $field .= Html::endForm();
        return $field;
    }
}
