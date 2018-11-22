<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Operations;

/**
 * OperationsSearch represents the model behind the search form of `app\models\Operations`.
 */
class OperationsSearch extends Operations
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rental_id', 'order_id', 'sum'], 'integer'],
            [['date','operations'], 'safe'],
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
        $query = Operations::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=>['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'rental_id' => $this->rental_id,
            'order_id' => $this->order_id,
            'sum' => $this->sum,
            //'operations' => $this->operations,
            'date' => $this->date,
        ])->andFilterWhere(['like', 'operations', $this->operations]);

        return $dataProvider;
    }
}
