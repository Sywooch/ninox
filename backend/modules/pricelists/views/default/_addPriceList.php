<?php
$form = new \yii\bootstrap\ActiveForm([
    'id'    =>  'priceListForm'
]);

\backend\assets\CheckboxTreeAsset::register($this);

$js = <<<'JS'
$("#tree").fancytree({
    checkbox: true,
    selectMode: 2,
    source: {
        url: '/pricelists/categoriestree',
        cache: false
    },
    beforeSelect: function(event, data){
        var selectChildren = function(children, selected){
            children.forEach(function(item){
                item.setSelected(selected != 1 ? true : false)

                if(item.isFolder()){
                    selectChildren(item.getChildren(), selected);
                    console.log(item.getChildren());
                }
            });
        };

        if(data.node.isFolder()){
            selectChildren(data.node.getChildren(), data.node.isSelected());
        }
    }
});
$("#submitForm").on('click', function(){
    $("#priceListForm").submit();
});

$("#priceListForm").submit(function() {
      $("#tree").fancytree("getTree").generateFormElements("PriceListForm[categories][]");

      $.ajax({
        data: $(this).serialize(),
        dataType: 'json',
        url: '/pricelists/add',
        method: 'POST',
        success: function(){
            $.pjax.reload({container: '#priceLists-pjax'});
        }
      });

      return false;
    });

JS;

$this->registerJs($js);

$form->begin();
?>

<div class="row">
    <div class="col-xs-4">
        <?php
        echo $form->field($model, 'name'),
            $form->field($model, 'format')->dropDownList($model->getFormats()),
            $form->field($model, 'available')->checkbox(),
            $form->field($model, 'deleted')->checkbox(),
            $form->field($model, 'unlimited')->checkbox()
        ?>
    </div>
    <div class="col-xs-8">
        <center><b>Категории</b></center>
        <div id="tree" style="text-align: left; max-height: 300px; overflow: auto;"></div>
    </div>
</div>
<br><br>
<?php $form->end(); ?>