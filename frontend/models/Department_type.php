<?php


namespace frontend\models;


use yii\db\ActiveRecord;

class Department_type extends ActiveRecord
{
    public function getDepartment(){
        return $this->hasMany(Department::className(), ['type_id' => 'type_id']);
    }
}