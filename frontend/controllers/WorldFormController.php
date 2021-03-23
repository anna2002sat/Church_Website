<?php


namespace frontend\controllers;



use app\models\WorldForm;
use frontend\models\Continent;
use Yii;
use yii\web\Controller;

class WorldFormController extends Controller
{
    public function actionIndex(){
        $continents=Continent::find()->asArray()->orderBy('name')->all();
        $model=new WorldForm();
        $continent = null;
        $countries = null;
        $country = null;
        $regions = null;
        $region = null;
        $cities = null;
        $city = null;
        $weather = null;
        if (Yii::$app->request->isPjax) {
            $model->load(Yii::$app->request->post());
            if ($model->continent_id > 0) {
                $continent =$model->GetContinent($model->continent_id);
                $countries=$model->GetCountries($model->continent_id);
            }

            if ($model->country_id > 0) {
                $country = $model->GetCountry($model->country_id);
                $regions = $model->GetRegions($model->country_id);
           }


            if ($model->region_id > 0) {
                $region = $model->GetRegion($model->region_id);
                $cities = $model->GetCities($model->region_id);
            }


            if ($model->city_id > 0) {
                $city = $model->GetCity($model->city_id);
                $weather = $model->GetWeather($city->name_language);

            }

        }
        return $this->render('index', [
            'continents' => $continents,
            'model' => $model,
            'continent' => $continent,
            'countries' => $countries,
            'country'=>$country,
            'regions'=>$regions,
            'region'=>$region,
            'cities'=>$cities,
            'city'=>$city,
            'weather'=>$weather,
        ]);
    }
}