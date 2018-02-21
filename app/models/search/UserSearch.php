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

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return "{{%user}}";
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role_id', 'status'], 'integer'],
            [['email', 'username', 'password', 'auth_key', 'access_token',
                'logged_in_ip', 'logged_in_at', 'created_ip', 'created_at', 'updated_at',
                'banned_at', 'banned_reason', 'profile.company'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['profile.company']);
    }

    /**
     * Search
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        /** @var \app\modules\user\models\User $user */
        /** @var \app\modules\user\models\Profile $profile */

        // get models
        $user         = Yii::$app->getModule("user")->model("User");
        $profile      = Yii::$app->getModule("user")->model("Profile");
        $userTable    = $user::tableName();
        $profileTable = $profile::tableName();

        // set up query with relation to `profile.full_name`
        $query = $user::find();
        $query->joinWith(['profile' => function ($query) use ($profileTable) {
            $query->from(['profile' => $profileTable]);
        }]);

        // create data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['GridView.pagination.pageSize'],
            ],
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["profile.company"];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc'   => [$addSortAttribute => SORT_ASC],
                'desc'  => [$addSortAttribute => SORT_DESC],
            ];
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            "{$userTable}.id" => $this->id,
            'role_id'         => $this->role_id,
            'status'          => $this->status,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'logged_in_ip', $this->logged_in_ip])
            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
            ->andFilterWhere(['like', 'banned_reason', $this->banned_reason])
            ->andFilterWhere(['like', 'logged_in_at', $this->logged_in_at])
            ->andFilterWhere(['like', "{$userTable}.created_at", $this->created_at])
            ->andFilterWhere(['like', "{$userTable}.updated_at", $this->updated_at])
            ->andFilterWhere(['like', 'banned_at', $this->banned_at])
            ->andFilterWhere(['like', "profile.company", $this->getAttribute('profile.company')]);

        return $dataProvider;
    }
}
