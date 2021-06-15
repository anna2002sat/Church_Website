<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\TaskEmployee;

/**
 * TaskEmployeeeSearch represents the model behind the search form of `frontend\models\TaskEmployee`.
 */
class TaskEmployeeSearch extends TaskEmployee
{
    /**
     * {@inheritdoc}
     */
    public $doers=[];
    public $full_name='';
    public $email='';
    public function rules()
    {
        return [
            [['id', 'task_id', 'employee_id', ], 'integer'],
            [['doers', 'full_name', 'email'], 'safe'],
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
        $query = TaskEmployee::find();
        $query->joinWith('employee');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['full_name'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['employee.first_name' => SORT_ASC, 'employee.last_name' => SORT_ASC],
            'desc' => ['employee.first_name' => SORT_DESC, 'employee.last_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['email'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['employee.email' => SORT_ASC],
            'desc' => ['employee.email' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->full_name])
            ->orFilterWhere(['like', 'last_name', $this->full_name]);

        if(isset($this->task_id))
            $query->andFilterWhere(['task_employee.task_id'=> $this->task_id]);

        if(isset($this->doers)){
            $query->andFilterWhere(['in', 'task_employee.employee_id', $this->doers]);
        }
        return $dataProvider;
    }
}
