<?php


namespace frontend\models;


use yii\db\ActiveRecord;

class Department extends ActiveRecord
{
    public $color;
    public function getDepartment_type(){
        return $this->hasOne(Department_type::className(), ['type_id' => 'type_id']);
    }

}