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
use app\models\Theme;

/**
 * ThemeSearch represents the model behind the search form about `app\models\Theme`.
 */
class ThemeSearch extends Theme
{

    public $author;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at'], 'integer'],
            [['name', 'description', 'color', 'css', 'author', 'updated_at'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Theme::find();

        // Important: join the query with our author relation (Ref: User model)
        $query->joinWith(['author']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['GridView.pagination.pageSize'],
            ],
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ]
            ],
        ]);

        // Search themes by User username
        $dataProvider->sort->attributes['author'] = [
            'asc' => ['{{%user}}.username' => SORT_ASC],
            'desc' => ['{{%user}}.username' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if (isset($this->updated_at) && !empty($this->updated_at)) {
            $date = explode(" - ", $this->updated_at);
            $query->andFilterWhere(['between', '{{%theme}}.updated_at', strtotime(trim($date[0])), strtotime(trim($date[1]))]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'css', $this->css])
            ->andFilterWhere(['like', '{{%user}}.username', $this->author]);

        if (!empty(Yii::$app->user) && Yii::$app->user->can("edit_own_content") && !Yii::$app->user->can("admin")) {
            // If Advanced User
            // Add 'created by' filter
            $query->andFilterWhere(['created_by' => Yii::$app->user->id]);
        }

        return $dataProvider;
    }
}
