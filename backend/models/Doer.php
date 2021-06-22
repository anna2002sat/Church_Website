<?php


namespace backend\models;


class Doer extends \yii\base\Model
{
    public $doer_id = 0;
    public function rules()
    {
        return [
            ['doer_id', 'required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'doer_id' => 'Free employees:',
        ];
    }
}