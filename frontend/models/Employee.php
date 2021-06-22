<?php

namespace frontend\models;

use common\models\User;
use frontend\models\user_forms\ResendVerificationEmailForm;
use Yii;


/**
 * This is the model class for table "employee".
 *
 * @property int $employee_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property int|null $user_id
 * @property string $gender
 * @property string|null $image
 * @property int $verified
 * @property string|null $about
 *
 * @property User $user
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'gender'], 'required'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['about'], 'string'],
            [['email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'user_id' => 'User ID',
            'gender' => 'Gender',
            'image' => 'Image',
            'about' => 'About you',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[TaskEmployees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskEmployees()
    {
        return $this->hasMany(TaskEmployee::className(), ['employee_id' => 'employee_id']);
    }
    public function getRole()
    {
        $adminRole = AuthAssignment::find()->where(['user_id'=>$this->user_id])->andWhere(['item_name'=>'Admin'])->all();
        $managerRole = AuthAssignment::find()->where(['user_id'=>$this->user_id])->andWhere(['item_name'=>'Manager'])->all();
        if($adminRole)
            return 'Admin';
        elseif ($managerRole)
            return 'Manager';
        else
            return 'Employee';
    }
    public function getGenderChart($my_tasks){
        $doers_ids = TaskEmployee::find()->where(['in', 'task_id', $my_tasks])->select('employee_id')->asArray()->all();
        $result['females'] = Employee::find()->where(['in', 'employee_id', $doers_ids])->andWhere(['gender'=>'Female'])->distinct()->count();
        $result['males'] = $Males = Employee::find()->where(['in', 'employee_id', $doers_ids])->andWhere(['gender'=>'Male'])->distinct()->count();
        return $result;
    }
    public function getStatusChart($my_tasks){
        $result['ToDo']= Task::find()->where(['task_id'=>$my_tasks])->andWhere(['status_id'=>'1'])->count();
        $result['InProgress']= Task::find()->where(['task_id'=>$my_tasks])->andWhere(['status_id'=>'2'])->count();
        $result['ToVerify']= Task::find()->where(['task_id'=>$my_tasks])->andWhere(['status_id'=>'3'])->count();
        $result['Completed']= Task::find()->where(['task_id'=>$my_tasks])->andWhere(['status_id'=>'4'])->count();
        return $result;
    }
    public function getCompletionChart($my_tasks){
        $result['Completed']= Task::find()->where(['task_id'=>$my_tasks])->andWhere(['status_id'=>'4'])->count();
        $result['Not Completed']= count($my_tasks)-$result['Completed'];
        return $result;
    }
    public function getOverDueChart($my_tasks){
        $result['overDue'] = Task::find()->where(['task_id'=>$my_tasks])->andWhere(['>', 'finish', 'deadline'])->select('task_id')->count();
        $result['Not overDue'] = count($my_tasks)-$result['overDue'];
        return $result;
    }
    public function beforeSave($insert)
    {
        if($insert){
            if($user = \frontend\models\User::findOne(['email'=>$this->email])){ // user registered
                $this->user_id = $user->id;
            }
        }
        else
            if($this->user_id){
                if($user = User::findOne(['email'=>$this->email])){
                    $this->user_id = $user->id;
                }
                if($user = User::findOne(['id'=>$this->user_id])) {
                    if($user->email!=$this->email){
                        if(!Yii::$app->user->can('Admin')){
                            $model = new ResendVerificationEmailForm();
                            $model->email=$this->email;
                            $user->status = User::STATUS_INACTIVE;
                            $user->email = $this->email;
                            $user->save();
                            if ($model->sendEmail()) {
                                Yii::$app->session->setFlash('success', 'Check your email for further instructions to login to your account.');
                            }else{
                                Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');

                            }
                        }
                        else
                            Yii::$app->session->setFlash('success', 'Email has been updated!');

                    }
                }
        }
        return parent::beforeSave($insert);
    }
    public function getImage(){
        if ($this->image && is_file(Yii::getAlias('@frontend').'/web/images/employee/'.$this->image)) {
            return '/images/employee/' . $this->image;
        } else {
            return '/images/employee/employee_placeholder.png';
        }
    }
    public function getFullname(){
        return  $this->first_name.' '.$this->last_name;
    }
}
