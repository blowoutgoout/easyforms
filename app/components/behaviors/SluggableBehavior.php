<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.6.7
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\components\behaviors;

use app\helpers\SlugHelper;
use yii\behaviors\SluggableBehavior as BaseSluggableBehavior;

/**
 * Class SluggableBehavior
 * @package app\components\behaviors
 */
class SluggableBehavior extends BaseSluggableBehavior
{

    /**
     * This method is called by [[getValue]] to generate the slug.
     *
     * @param array $slugParts an array of strings that should be concatenated and converted to generate the slug value.
     * @return string the conversion result.
     */
    protected function generateSlug($slugParts)
    {
        return SlugHelper::slug(implode('-', $slugParts));
    }

}