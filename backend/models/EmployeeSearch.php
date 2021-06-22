<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form of `frontend\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * {@inheritdoc}
     */
    public $role='';
    public $full_name='';
    public $full_role;
    public $search="";
    public function rules()
    {
        return [
            [['employee_id', 'user_id'], 'integer'],
            [['first_name', 'last_name', 'email', 'image', 'role', 'full_name', 'full_role', 'search'], 'safe'],
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
        $query = Employee::find();
        $query->leftJoin('auth_assignment', '`auth_assignment`.`user_id` = `employee`.`user_id`');


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['role'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['item_name' => SORT_ASC],
            'desc' => ['item_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['full_name'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['employee.first_name' => SORT_ASC, 'employee.last_name' => SORT_ASC],
            'desc' => ['employee.first_name' => SORT_DESC, 'employee.last_name' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if($this->search!="") {
            $query->orFilterWhere(['like', 'first_name', $this->search])
                ->orFilterWhere(['like', 'last_name', $this->search])
                ->orFilterWhere(['like', 'email', $this->search])
                ->orFilterWhere(['like', 'auth_assignment.item_name', $this->search])
                ->andFilterWhere(['verified' => $this->verified]);
            $this->full_name=$this->email=$this->role='';
        }
        else{
            // grid filtering conditions
            $query->andFilterWhere([
                'employee_id' => $this->employee_id,
                'user_id' => $this->user_id,
            ]);

            $query->andFilterWhere(['like', 'first_name', $this->full_name])
                ->orFilterWhere(['like', 'last_name', $this->full_name])
                ->andFilterWhere(['like', 'email', $this->email])
//                ->andFilterWhere(['like', 'image', $this->image])
                ->andFilterWhere(['like', 'auth_assignment.item_name', $this->role])
                ->andFilterWhere(['verified' => $this->verified]);

            if (isset($this->full_role)){
                $query->andFilterWhere(['auth_assignment.item_name'=>$this->full_role]);
            }
        }

//        if (!isset($dataProvider->sort)){
//            $query->orderBy('auth_assignment.created_at');
//        }

        return $dataProvider;
    }
}
