<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RegionList;

/**
 * RegionListSearch represents the model behind the search form of `app\models\RegionList`.
 */
class RegionListSearch extends RegionList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'country_id'], 'integer'],
            [['text', 'alias', 'coord','adress','tag_line', 'description'], 'safe'],
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
        $query = RegionList::find();

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
            'country_id' => $this->country_id,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
        ->andFilterWhere(['like', 'alias', $this->text])
        ->andFilterWhere(['like', 'adress', $this->adress])
        ->andFilterWhere(['like', 'tag_line', $this->tag_line])
        ->andFilterWhere(['like', 'description', $this->description])
        ->andFilterWhere(['like', 'coord', $this->coord]);

        return $dataProvider;
    }
}
