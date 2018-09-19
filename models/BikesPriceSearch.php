<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BikesPrice;

/**
 * BikesPriceSearch represents the model behind the search form of `app\models\BikesPrice`.
 */
class BikesPriceSearch extends BikesPrice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'bike_id', 'condition_id', 'region_id'], 'integer'],
            [['photo', 'price', 'pricepm'], 'safe'],
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
        $query = BikesPrice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'bike_id' => $this->bike_id,
            'condition_id' => $this->condition_id,
            'region_id' => $this->region_id,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'price', $this->price])
            ->andFilterWhere(['like', 'price', $this->pricepm]);

        return $dataProvider;
    }
}
