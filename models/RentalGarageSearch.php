<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RentalGarage;

/**
 * RentalGarageSearch represents the model behind the search form of `app\models\RentalGarage`.
 */
class RentalGarageSearch extends RentalGarage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rental_id', 'bike_id', 'condition_id', 'status', 'radius', 'region_id'], 'integer'],
            [['number', 'year', 'millage'], 'safe'],
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
        $query = RentalGarage::find();

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
            'rental_id' => $this->rental_id,
            'bike_id' => $this->bike_id,
            'condition_id' => $this->condition_id,
            'status' => $this->status,
            'radius' => $this->radius,
            'region_id' => $this->region_id,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'millage', $this->millage]);

        return $dataProvider;
    }
}
