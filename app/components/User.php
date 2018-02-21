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
use app\helpers\ArrayHelper;
use app\models\Form;
use app\models\Theme;
use app\models\Template;

/**
 * Class User
 * @package app\components
 *
 * User Component
 */
class User extends \app\modules\user\components\User
{
    /**
     * @inheritdoc
     */
    public $identityClass = 'app\models\User';

    /**
     * Form ids created by this user
     *
     * @return array
     */
    public function getMyFormIds()
    {
        /** @var \app\models\User $user */
        $user = Yii::$app->user->identity;
        $userForms = Form::find()->where([
            'created_by' => $user->id
        ])->asArray()->all();
        $userForms = ArrayHelper::getColumn($userForms, 'id');
        return $userForms;
    }

    /**
     * Theme ids created by this user
     *
     * @return array
     */
    public function getMyThemeIds()
    {
        /** @var \app\models\User $user */
        $user = Yii::$app->user->identity;
        $userThemes = Theme::find()->where([
            'created_by' => $user->id
        ])->asArray()->all();
        $userThemes = ArrayHelper::getColumn($userThemes, 'id');
        return $userThemes;
    }

    /**
     * Theme ids created by this user
     *
     * @return array
     */
    public function getMyTemplateIds()
    {
        /** @var \app\models\User $user */
        $user = Yii::$app->user->identity;
        $userTemplates = Template::find()->where([
            'created_by' => $user->id
        ])->asArray()->all();
        $userTemplates = ArrayHelper::getColumn($userTemplates, 'id');
        return $userTemplates;
    }

    /**
     * Form ids assigned to this user
     *
     * @return array
     */
    public function getAssignedFormIds()
    {
        /** @var \app\models\User $user */
        $user = Yii::$app->user->identity;
        $userForms = $user->getUserForms()->asArray()->all(); // TODO Add forms created by the same user
        $userForms = ArrayHelper::getColumn($userForms, 'form_id');
        return $userForms;
    }

    /**
     * Check if user can access to Form.
     *
     * @param integer $id Form ID
     * @return bool
     */
    public function canAccessToForm($id)
    {
        if (isset(Yii::$app->user)) {
            $ids = null;
            if (Yii::$app->user->can('admin')) {
                return true;
            } elseif (Yii::$app->user->can('edit_own_content')) {
                $ids = $this->getMyFormIds();
            } else {
                $ids = $this->getAssignedFormIds();
            }
            if (count($ids) > 0 && in_array($id, $ids)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user can access to Theme.
     *
     * @param integer $id Theme ID
     * @return bool
     */
    public function canAccessToTheme($id)
    {
        if (isset(Yii::$app->user)) {
            if (Yii::$app->user->can('admin')) {
                return true;
            } else { // Only Advanced Users can create a theme
                $ids = $this->getMyThemeIds();
                if (count($ids) > 0 && in_array($id, $ids)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if user can access to Template.
     *
     * @param integer $id Template ID
     * @return bool
     */
    public function canAccessToTemplate($id)
    {
        if (isset(Yii::$app->user)) {
            if (Yii::$app->user->can('admin')) {
                return true;
            } else { // Only Advanced Users can create a template
                $ids = $this->getMyTemplateIds();
                if (count($ids) > 0 && in_array($id, $ids)) {
                    return true;
                }
            }
        }

        return false;
    }
}
