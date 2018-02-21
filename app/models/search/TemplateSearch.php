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
use app\models\Template;
use app\models\Role;

/**
 * TemplateSearch represents the model behind the search form about `app\models\Template`.
 */
class TemplateSearch extends Template
{

    public $author;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'promoted'], 'integer'],
            [['name', 'description', 'promoted', 'builder', 'html', 'author', 'updated_at'], 'safe'],
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
        $query = Template::find();

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

        // Search templates by User username
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'promoted' => $this->promoted,
        ]);

        if (isset($this->updated_at) && !empty($this->updated_at)) {
            $date = explode(" - ", $this->updated_at);
            $query->andFilterWhere(['between', '{{%template}}.updated_at', strtotime(trim($date[0])), strtotime(trim($date[1]))]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'builder', $this->builder])
            ->andFilterWhere(['like', 'html', $this->html])
            ->andFilterWhere(['like', '{{%user}}.username', $this->author]);

        if (!empty(Yii::$app->user) &&
            Yii::$app->user->can("edit_own_content") && !Yii::$app->user->can("admin")) {
            // If Advanced User
            $templateIds = Yii::$app->user->getMyTemplateIds();
            $templateIds = count($templateIds) > 0 ? $templateIds : 0; // Important restriction
            $query->andFilterWhere(['{{%template}}.id' => $templateIds]);
            $query->orWhere(['{{%user}}.role_id' => Role::ROLE_ADMIN]);
        }

        return $dataProvider;
    }
}
