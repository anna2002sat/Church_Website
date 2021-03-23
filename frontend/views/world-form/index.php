<div class="container">
    <div class="row align-items-start">
        <?php

        use yii\helpers\ArrayHelper;
        use yii\helpers\Html;
        use yii\helpers\Url;
        use yii\widgets\Pjax;
        use yii\widgets\ActiveForm;
        ?>

        <?php Pjax::begin([
            'id' => 'world-form-container'
        ]); ?>
        <?php $form = ActiveForm::begin([
            'options' => ['data' => ['pjax' => true],],
            'id' => 'world-form'
        ]);
        ?>
        <div class="col-md-6">

        <?= $form->field($model, 'continent_id')->dropDownList(ArrayHelper::map($continents, 'continent_id', 'name'), [
            'prompt'=>'Select a continent...',
            'id' => 'field-continent-id',
            'onchange' => '$("#world-form").submit()'
        ]) ?>

        <h2><?= $continent->name ?></h2>
        <p><?= $continent->description ?></p>

        <!--//////////////// COUNTRIES-->
        <?php if($countries!=null):?>

            <?= $form->field($model, 'country_id')->dropDownList(ArrayHelper::map($countries, 'country_id', 'name'), [
                'prompt'=>'Select a country:',
                    'id' => 'field-country-id',
                'onchange' => '$("#world-form").submit()',
            ]) ?>

        <?php endif; ?>

        <!--//////////////// REGION-->
        <?php if($regions!=null):?>

            <?= $form->field($model, 'region_id')->dropDownList(ArrayHelper::map($regions, 'region_id', 'name_language'), [
                'prompt'=>'Select a region:',
                'id' => 'field-region-id',
                'onchange' => '$("#world-form").submit()'
            ]) ?>

        <?php endif; ?>

        <!--//////////////// City-->
        <?php if($cities!=null):?>

            <?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map($cities, 'city_id', 'name_language'), [
                'prompt'=>'Select a city:',
                    'id' => 'field-city-id',
                'onchange' => '$("#world-form").submit()',
            ]) ?>

        <?php endif; ?>
    </div>
        <div class="col-md-6">
            <table class="table table-striped table-hover table-bordered ">
                <?php if($continent!=null):?>
                    <tr>
                        <th colspan="2" class="text-center">Your location</th>
                    </tr>
                <? endif;?>

                <?php if($continent!=null):?>
                    <tr>
                        <th>Continent</th>
                        <td><?= $continent->name ?></td>
                    </tr>
                <? endif;?>

                <?php if($country!=null):?>
                    <tr>
                        <th>Country</th>
                        <td><?=$country->name?></td>
                    </tr>
                <? endif;?>

                <?php if($region!=null):?>
                    <tr>
                        <th>Region</th>
                        <td><?=$region->name_language?></td>
                    </tr>
                <? endif;?>

                <?php if($city!=null):?>
                    <tr>
                        <th>City</th>
                        <td id="city"><?=$city->name_language?></td>
                    </tr>
                <? endif;?>
            </table>

            <?php if($weather!=null):?>
            <div id="weather"  >
                <h2>Weather in <?= $city->name_language?></h2>
                <img src="https://openweathermap.org/img/wn/<?= $weather->weather[0]->icon?>@2x.png" alt="">
                <table class="table table-striped table-hover table-bordered">
                    <tr>
                        <th>Pressure</th>
                        <td id="pressure"><?=$weather->main->pressure?></td>
                    </tr>
                    <tr>
                        <th>Humidity</th>
                        <td id="humidity"><?=$weather->main->humidity?></td>
                    </tr>
                    <tr>
                        <th>Temperature</th>
                        <td id="temp"><?= round(($weather->main->temp)-273.15).'&deg'?> </td>
                    </tr>
                </table>
            </div>
            <? endif;?>
            <?php ActiveForm::end(); ?>
            <?php Pjax::end(); ?>




        </div>
    </div>
</div>

<script>

    function drawWeather( data, cityName ) {


        let src="https://openweathermap.org/img/wn/"+data.weather[0]['icon']+"@2x.png";
        document.getElementById('weather_icon').src=src;
    }


</script>