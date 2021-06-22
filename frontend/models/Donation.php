<?php

namespace frontend\models;

use backend\controllers\ProjectController;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "donation".
 *
 * @property int $donation_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $phone
 * @property float $amount
 * @property int|null $project_id
 * @property string|null $comments
 */
class Donation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'donation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'amount'], 'required'],
            [['amount'], 'number'],
            [['project_id'], 'integer'],
            [['email'], 'email'],
            [['comments'], 'string'],
            [['first_name', 'last_name', 'email', 'phone'], 'string', 'max' => 255],
            ['amount', 'compare', 'compareValue' => 0, 'operator' => '>', 'enableClientValidation' => true],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'donation_id' => 'Donation ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone Number',
            'amount' => 'Amount',
            'project_id' => 'Purpose',
            'comments' => 'Comments',
        ];
    }
    public function getPurpose()
    {
        if($this->project_id)
        {
            $project = Project::findOne(['project_id' => $this->project_id]);
                return Html::a($project->title, ['project/view', 'id' => $project->project_id]);
        }
        else
            return 'unassigned';
    }
    public static function Purposes(){
        $donations = Donation::find()->select('project_id')->asArray()->all();
        return Project::find()->where(['in', 'project_id', $donations])->all();
    }
}
