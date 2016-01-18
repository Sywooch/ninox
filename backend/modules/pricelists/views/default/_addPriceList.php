<?php
$form = new \yii\bootstrap\ActiveForm();

\backend\assets\CheckboxTreeAsset::register($this);

$js = <<<'JS'
var treeData = [
    {title: "item1 with key and tooltip", tooltip: "Look, a tool tip!" },
    {title: "item2: selected on init", selected: true },
    {title: "Folder", folder: true, key: "id3",
      children: [
        {title: "Sub-item 3.1",
          children: [
            {title: "Sub-item 3.1.1", key: "id3.1.1" },
            {title: "Sub-item 3.1.2", key: "id3.1.2" }
          ]
        },
        {title: "Sub-item 3.2",
          children: [
            {title: "Sub-item 3.2.1", key: "id3.2.1" },
            {title: "Sub-item 3.2.2", key: "id3.2.2" }
          ]
        }
      ]
    },
    {title: "Document with some children (expanded on init)", key: "id4", expanded: true,
      children: [
        {title: "Sub-item 4.1 (active on init)", active: true,
          children: [
            {title: "Sub-item 4.1.1", key: "id4.1.1" },
            {title: "Sub-item 4.1.2", key: "id4.1.2" }
          ]
        },
        {title: "Sub-item 4.2 (selected on init)", selected: true,
          children: [
            {title: "Sub-item 4.2.1", key: "id4.2.1" },
            {title: "Sub-item 4.2.2", key: "id4.2.2" }
          ]
        },
        {title: "Sub-item 4.3 (hideCheckbox)", hideCheckbox: true },
        {title: "Sub-item 4.4 (unselectable)", unselectable: true }
      ]
    },
    {title: "Lazy folder", folder: true, lazy: true }
  ];


$("#tree").fancytree({
    checkbox: true,
    source: {
        url: '/pricelists/categoriestree',
        cache: false
    }
});
JS;

$this->registerJs($js);

?>

<div class="row">
    <div class="col-xs-4">
        <?php
        echo $form->field($model, 'name'),
            $form->field($model, 'format')
        ?>
    </div>
    <div class="col-xs-8">
        <center><b>Категории</b></center>
        <div id="tree" style="text-align: left; max-height: 300px; overflow: auto;"></div>
    </div>
</div>
<br><br>