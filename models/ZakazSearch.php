<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Zakaz;

/**
 * ZakazSearch represents the model behind the search form of `app\models\Zakaz`.
 */
class ZakazSearch extends Zakaz
{

    public $filter_date_from;
    public $filter_date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rental_id', 'garage_id', 'price', 'pay_id', 'region_id', 'service_tax', 'status'], 'integer'],
            [['user_phone', 'zakaz_info', 'user_name', 'user_email', 'date_for', 'date_to', 'curr_date', 'comment', 'coord', 'filter_date_to', 'filter_date_from'], 'safe'],
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
        $query = Zakaz::find();

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
            'garage_id' => $this->garage_id,
            'date_for' => $this->date_for,
            'date_to' => $this->date_to,
            //'curr_date' => $this->curr_date,
            'price' => $this->price,
            'pay_id' => $this->pay_id,
            'region_id' => $this->region_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'user_phone', $this->user_phone])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'zakaz_info', $this->zakaz_info])
            ->andFilterWhere(['like', 'user_email', $this->user_email])
            ->andFilterWhere(['like', 'service_tax', $this->service_tax])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'coord', $this->coord])
            ->andFilterWhere(['>=', 'curr_date', $this->filter_date_from ? $this->filter_date_from . ' 00:00:00' : ''])
            ->andFilterWhere(['<=', 'curr_date', $this->filter_date_to ? $this->filter_date_to . ' 23:59:59' : '']);
            //->orderBy(['curr_date' => SORT_DESC]);

        return $dataProvider;
    }
}
