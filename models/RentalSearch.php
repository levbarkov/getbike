<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rental;

/**
 * RentalSearch represents the model behind the search form of `app\models\Rental`.
 */
class RentalSearch extends Rental
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'radius', 'region_id'], 'integer'],
            [['phone', 'mail', 'adress', 'name', 'hash', 'coord'], 'safe'],
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
        $query = Rental::find();

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
            'radius' => $this->radius,
            'region_id' => $this->region_id,
        ]);

        $query->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['like', 'adress', $this->adress])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'hash', $this->hash])
            ->andFilterWhere(['like', 'hash', $this->coord]);

        return $dataProvider;
    }
}
