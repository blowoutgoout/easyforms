<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_role".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property integer $can_admin
 * @property integer $can_edit_own_content
 *
 * @property User[] $users
 */
class Role extends \app\modules\user\models\Role
{
    /**
     * @var int Self-Editor user role
     */
    const ROLE_ADVANCED_USER = 3; // Can edit own content

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['can_admin', 'can_edit_own_content'], 'integer'],
        ];

        // add can_ rules
        foreach ($this->attributes() as $attribute) {
            if (strpos($attribute, 'can_') === 0) {
                $rules[] = [[$attribute], 'integer'];
            }
        }

        return $rules;

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'can_admin' => Yii::t('app', 'Can Admin'),
            'can_edit_own_content' => Yii::t('app', 'Can Edit Own Content'),
        ];
    }

}