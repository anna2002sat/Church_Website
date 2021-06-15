<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Task;

/**
 * TaskSearch represents the model behind the search form of `frontend\models\Task`.
 */
class TaskSearch extends Task
{

    /**
     * {@inheritdoc}
     */
    public $status_name='';
    public $project_name='';
    public $task_ids=[];
    public function rules()
    {
        return [
            [['task_id', 'status_id', 'project_id'], 'integer'],
            [['title', 'start', 'finish', 'deadline', 'status_name', 'task_ids', 'project_name'], 'safe'],
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
        $query = Task::find();
        $query->joinWith('status');
        $query->joinWith('project');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['status_name']=[
            'asc'=>['status.name'=> SORT_ASC],
            'desc'=>['status.name'=> SORT_DESC],
        ];
        $dataProvider->sort->attributes['project_name']=[
            'asc'=>['project.title'=> SORT_ASC],
            'desc'=>['project.title'=> SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'task_id' => $this->task_id,
            'start' => $this->start,
            'status_id' => $this->status_id,
            'task.project_id' => $this->project_id,
        ]);

        $query->andFilterWhere(['like', 'task.title', $this->title]);
        $query->andFilterWhere(['like', 'status.name', $this->status_name]);
        $query->andFilterWhere(['like', 'project.title', $this->project_name])
            ->andFilterWhere(['like', 'finish', $this->finish])
            ->andFilterWhere(['like', 'start', $this->start])
            ->andFilterWhere(['like', 'deadline', $this->deadline]);

        if(isset($this->task_ids)){
            $query->andFilterWhere(['in', 'task_id', $this->task_ids]);
        }
        return $dataProvider;
    }
}
