<h2>Categories list:</h2>

<?php
function drawMenu ($listOfItems, $model) {
    echo "<ul>";
    foreach ($listOfItems as $item) {
        echo "<li>" . $item['name'];
        if ($model->hasChildren($item['id'])) {
            drawMenu(($model->getChildren($item['id'])), $model);
        }
        echo "</li>";
    }
    echo "</ul>";
}
?>
<div>
    <? drawMenu($categories, $model);?>
</div>


