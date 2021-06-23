<?php

namespace frontend\models;

use phpDocumentor\Reflection\Types\Null_;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Donation;

/**
 * DonationSearch represents the model behind the search form of `frontend\models\Donation`.
 */
class DonationSearch extends Donation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['donation_id', 'project_id'], 'integer'],
            [['first_name', 'last_name', 'email', 'phone', 'comments', 'purpose'], 'safe'],
            [['amount'], 'number'],
        ];
    }
    private $purpose;
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
        $query = Donation::find();

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
            'donation_id' => $this->donation_id,
            'amount' => $this->amount,
        ]);
        if($this->project_id=='-1'){
            $query->andWhere(['is', 'project_id', new \yii\db\Expression('null')]);
        }
        else
            $query->andFilterWhere([
                'project_id' => $this->project_id]);

        $user_email = User::findOne(['id'=>\Yii::$app->user->getId()])->email;
        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $user_email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
