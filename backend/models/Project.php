<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $project_id
 * @property string $title
 * @property string $description =""
 * @property string|null $image = null
 * @property string|null $video_url = ""
 * @property int $author_id = 0 id користувача, що створив проект
 * @property int count = ""
 * @property Employee $author
 * @property Task[] $tasks
 * @property float|null $needed_sum
 * @property float|null $collected_sum
 *
 */

class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['title', 'image', 'video_url'], 'string', 'max' => 255],
            [['needed_sum', 'collected_sum'], 'number'],
            ['video_url', 'validateVideo'],
            ['author_id', 'integer'],

        ];
    }
    private function checkURL($url){
        preg_match(
            '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/',
            $url,
            $matches
        );
        if(count($matches)==6 || count($matches)==7)
            return $matches[5];
        return false;
    }

    public function validateVideo($model, $attribute){
        $validateUrl=$this->checkURL($this->video_url);
        if($validateUrl!=false){
            $this->video_url = "https://www.youtube.com/embed/$validateUrl";
            return true;
        }
        else
            return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'title' => 'Title',
            'description' => 'Description',
            'image' => 'Image',
            'video_url' => 'Video Url',
            'author_id' => 'Author',
            'needed_sum' => 'Needed Sum',
            'collected_sum' => 'Collected Sum',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'author_id']);
    }

    public function getGenderChart(){/////// GROUP BY
        $tasks=Task::find()->where(['project_id'=>$this->project_id])->select('task_id')->asArray()->all();
        $doers_ids = TaskEmployee::find()->where(['in', 'task_id', $tasks])->andWhere(['verified'=>true])->select('employee_id')->asArray()->all();
        $employees = Employee::find()->where(['in', 'employee_id', $doers_ids])->distinct();
        $genders = $employees->select(['COUNT(*) as count', 'gender'])->groupBy('gender')->asArray()->all();
        foreach ($genders as $gender){
            if($gender['gender']=='Female')
                $result['females']=$gender['count'];
            if($gender['gender']=='Male')
                $result['males']=$gender['count'];
        }
        return $result;
    }
    public function getStatusChart(){ /////// GROUP BY
        $tasks=Task::find()->where(['project_id'=>$this->project_id])->select('task_id')->asArray()->all();
        $statuses = Task::find()->where(['task_id'=>$tasks])->select(['COUNT(*) as count', 'status_id'])->groupBy('status_id')->asArray()->all();
        $result['ToDo']=$result['InProgress']=$result['ToVerify']=$result['Completed']=0;
        foreach ($statuses as $status){
            if($status['status_id']==1)
                $result['ToDo']= $status['count'];
            if($status['status_id']==2)
                $result['InProgress']= $status['count'];
            if($status['status_id']==3)
                $result['ToVerify']= $status['count'];
            if($status['status_id']==4)
                $result['Completed']= $status['count'];
        }
        return $result;
    }
    public function getCompletionChart(){
        $tasks=Task::find()->where(['project_id'=>$this->project_id])->select('task_id')->asArray()->all();
        $result['Completed']= Task::find()->where(['task_id'=>$tasks])->andWhere(['status_id'=>'4'])->count();
        $result['Not Completed']= count($tasks)-$result['Completed'];
        return $result;
    }
    public function getOverDueChart(){
        $tasks=Task::find()->where(['project_id'=>$this->project_id])->select('task_id')->asArray()->all();
        $result['overDue'] = Task::find()->where(['task_id'=>$tasks])->andWhere(['>', 'finish', 'deadline'])->select('task_id')->count();
        $result['Not overDue'] = count($tasks)-$result['overDue'];
        return $result;
    }
        /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['project_id' => 'project_id'])->orderBy('start');
    }

    public function beforeSave($insert)
    {
        if(!$this->author_id){
            $employee = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
            $this->author_id=$employee->employee_id;
        }
        return parent::beforeSave($insert);
    }

    public function getImage(){
        if ($this->image && is_file(Yii::getAlias('@frontend').'/web/images/projects/'.$this->image)) {
            return '/images/projects/' . $this->image;
        }
        else {
            return '/images/projects/project_placeholder.jpg';
        }
    }
}
