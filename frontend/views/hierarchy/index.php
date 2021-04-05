<h2>Categories list:</h2>

<?php
function drawMenu ($listOfItems, $list_model) {
    echo "<ul>";
    foreach ($listOfItems as $item) {
        echo "<li>" . $item['name'];
        if ($list_model->hasChildren($item['id'])) {
            drawMenu(($list_model->getChildren($item['id'])), $list_model);
        }
        echo "</li>";
    }
    echo "</ul>";
}
?>
<div>
    <? drawMenu($categories, $list_model);?>
</div>


<div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {packages:["orgchart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Parent');


            // For each orgchart box, provide the name, manager, and tooltip to show.
            data.addRows([
                <?foreach ($departments as $department):?>
                    [{'v':'<?=$department['department_id']?>',
                        'f':"<?=$department['department_name']?>"},
                            '<?=($department['parent_id'] == 0) ? '': $department['parent_id']?>'],
                <? endforeach;?>
            ]);

            /*
            *
            * */
            // Create the chart.

            var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
            // Draw the chart, setting the allowHtml option to true for the tooltips.
            //chart.setRowProperty(3, 'style', 'border: 1px solid green');

            //for (let i=0 ; i< <?//= count($departments)?>//; i++ ) {
            //    data.setRowProperty(i, 'style', 'border: 0px; color: white; ');
            //}
            if(chart!=null)
            {
                let i=0;
                <? foreach ($departments as $department):?>
                data.setRowProperty(i, 'style', 'border: 0px; color: white; background: <?= $department['color'] ?>');
                i++;
                <? endforeach;?>
            }




            chart.draw(data, {'allowHtml':true});

        }
    </script>
</div>
<div>
<div id="chart_div"></div>
</div>

