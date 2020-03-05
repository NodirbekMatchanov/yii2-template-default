<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Data;

/**
 * DataSearch represents the model behind the search form of `frontend\models\Data`.
 */
class DataSearch extends Data
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'address_id'], 'integer'],
            [['card_number', 'date', 'service'], 'safe'],
            [['volume'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Data::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        // чтобы не удалить запись и Бд просто обеденяем транзакции-возвраты с транзакции-расходы
        $query->select(['card_number,id,address_id,DATE_FORMAT(date, "%Y-%m-%d") as date,SUM(volume) as volume,service'])->groupBy(['card_number,DATE_FORMAT(date, "%Y-%m-%d"),address_id']);
        //SELECT card_number,id,date_format(date, "%Y-%m-%d"),SUM(volume) as volume FROM `data` GROUP BY card_number,date_format(date, "%Y-%m-%d")
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // не будем отображать сервис по мойке так как нам нужно только транзакции по топливе
        $query->andWhere(['!=', 'service', ['Мойка']]);
        $query->andWhere(['!=', 'service', ['Шиномонтаж']]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'volume' => $this->volume,
            'address_id' => $this->address_id,
        ]);

        $query->andFilterWhere(['like', 'card_number', $this->card_number])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'service', $this->service]);

        return $dataProvider;
    }
}
