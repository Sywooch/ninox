<h1>Импорт из файла "<?=$filename?>"</h1>
<table class="table">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
<?php
foreach($xls as $row){
    echo '<tr>';
    foreach($row as $col){
        echo '<td>', $col, '</td>';
    }
    echo '</tr>';
}
?>
</table>