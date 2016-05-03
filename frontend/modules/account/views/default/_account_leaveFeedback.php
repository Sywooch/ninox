<div id="reviews" class="reviews user-account">
    <div class="pull-right">
        <i class="icon icon-exit"></i>
    </div>
    <div class="text">
        <div class="review-tittle">
            <div class="myriad">
                <?=\Yii::t('shop', 'Сделайте наш сервис еще лучше')?>
            </div>
            <div class="semi-font">
                <?=\Yii::t('shop', 'Оставьте отзывы о купленных вами товарах')?>
            </div>
        </div>
        <?=\yii\widgets\ListView::widget([
            'summary'       =>  false,
            'dataProvider'  =>  $customerBuyedItems,
            'itemView'      =>  function($model){
                return $this->render('_review_items', [
                    'model' =>  $model
                ]);
            },
            'options'       =>  [
                'class'         =>  'review'
            ],
            'pager'         =>  [
                'options'   =>  [
                    'style' =>  'display: none'
                ]
            ]
        ])?>
    </div>
</div>