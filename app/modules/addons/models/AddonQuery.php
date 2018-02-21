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

namespace app\modules\addons\models;

use yii\db\ActiveQuery;

class AddonQuery extends ActiveQuery
{
    public function active($status = true)
    {
        return $this->andWhere(['status' => $status]);
    }
}
