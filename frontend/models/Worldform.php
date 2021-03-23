<?php


namespace app\models;


use app\controllers\Continent;
use frontend\controllers\City;
use frontend\controllers\City_language;
use frontend\controllers\Country;
use frontend\controllers\Region;
use frontend\controllers\Region_language;
use yii\base\Model;

class WorldForm extends Model
{
    public $continent_id = 0;
    public $country_id = 0;
    public $region_id = 0;
    public $city_id = 0;
    public $weather = null;

    public function rules()
    {
        return [
            [['country_id', 'continent_id', 'region_id', 'city_id'], 'integer']
        ];
    }
    public function attributeLabels()
    {
        return [
            'continent_id' => "Continent",
            'country_id' => "Country",
            'region_id' => "Region_language",
            'city_id' => "City",
        ]; // TODO: Change the autogenerated stub
    }
    public function GetContinent($continent_id){
        return  Continent::find()->where(['continent_id' => $continent_id])->one();
    }
    public function GetCountries($continent_id){
        return Country::find()->where(['continent_id' => $continent_id])->orderBy('name')->all();
    }
    public function GetCountry($country_id){
        return Country::find()->where(['country_id' => $country_id])->one();
    }
    public function GetRegions($country_id){
        $regionsInCountry = Region::find()->select('region_id')->where(['country_id' => $country_id])->asArray()->all();
        return Region_language::find()->where(['region_id'=>$regionsInCountry])->andWhere(['language'=>'en'])->orderBy('name_language')->all();
    }
    public function GetRegion($region_id){
        return Region_language::find()->where(['region_id' => $region_id, 'language'=>'en'])->one();
    }
    public function GetCities($region_id){
        $citiesInRegion =City::find()->select('city_id')->where(['region_id' => $region_id])->select('city_id')->asArray()->all();
        return City_language::find()->where(['city_id'=>$citiesInRegion])->andWhere(['language'=>'en'])->orderBy('name_language')->all();

    }
    public function GetCity($city_id){
        return City_language::find()->where(['city_id' => $city_id, 'language'=>'en'] )->one();
    }

    public function GetWeather($city_name){
        $key = '052abc85f0b743480b0d58075490016c';
        $weatherUrl= 'https://api.openweathermap.org/data/2.5/weather?q='.$city_name.',UA'.'&appid='.$key;
        $jsonfile = file_get_contents($weatherUrl);
        return json_decode($jsonfile);
    }

}