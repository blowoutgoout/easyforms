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

namespace app\modules\addons\modules\webhooks\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\addons\modules\webhooks\models\Webhook;

/**
 * WebhookSearch represents the model behind the search form about `app\modules\addons\modules\webhooks\models\Webhook`.
 */
class WebhookSearch extends Webhook
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'form_id', 'status', 'json'], 'integer'],
            [['url', 'handshake_key'], 'safe'],
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
        $query = Webhook::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'form_id' => $this->form_id,
            'status' => $this->status,
            'json' => $this->json,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'handshake_key', $this->handshake_key]);

        return $dataProvider;
    }
}