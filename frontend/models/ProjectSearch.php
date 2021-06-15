<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Project;

/**
 * ProjectSearch represents the model behind the search form of `frontend\models\Project`.
 */
class ProjectSearch extends Project
{
    /**
     * {@inheritdoc}
     */

    /**
     * @var mixed|null
     */

    public $manager="";
    public $search="";
    public $project_ids;
    public function rules()
    {
        return [
            [['project_id', 'author_id'], 'integer'],
            [['title', 'description', 'image', 'video_url', 'manager', 'project_ids','search'], 'safe'],
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
        $query = Project::find();
        $query->joinWith(['author']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['manager'] = [
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
        if( $search!='') {
            // grid filtering conditions
            $query->andFilterWhere([
                'project_id' => $this->project_id,
                'author_id' => $this->author_id,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'first_name', $this->manager])
                ->orFilterWhere(['like', 'last_name', $this->manager])
                ->andFilterWhere(['like', 'description', $this->description])
//                ->andFilterWhere(['like', 'project.image', $this->image])
                ->andFilterWhere(['like', 'video_url', $this->video_url]);
        }
        else{
            $query->orFilterWhere(['like', 'title', $this->search])
                ->orFilterWhere(['like', 'first_name', $this->search])
                ->orFilterWhere(['like', 'last_name', $this->search])
                ->orFilterWhere(['like', 'description', $this->search]);
//                ->orFilterWhere(['like', 'video_url', $this->search]);
                $this->title=$this->manager=$this->description=$this->video_url='';
        }
        if(isset($this->project_ids)){
            $query->andFilterWhere(['in', 'project_id', $this->project_ids]);
        }
//        if (!isset($dataProvider->sort)){
//            $query->orderBy(['project_id'=>SORT_DESC]);
//        }
        return $dataProvider;
    }
}
