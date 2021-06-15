<?php

namespace backend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property int $employee_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property int $user_id
 * @property int $department_id
 * @property string $image
 *
 * @property User User $user
 * @property Department $department
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
            [['first_name', 'last_name', 'email', 'department_id', ], 'required'],
            [['department_id'], 'integer'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_id' => 'department_id']],
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
            'department_id' => 'Department ID',
            'image' => 'Image',
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
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['department_id' => 'department_id']);
    }

    public function beforeSave($insert)
    {
        if($insert){
           if($user = User::findOne(['email'=>$this->email])){ // user registered
               $this->user_id = $user->id;
           }
        }
        else if($this->user_id && $user = User::findOne(['email'=>$this->email])){
                $this->user_id = $user->id;
            }
       return parent::beforeSave($insert);
    }
}