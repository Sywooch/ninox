<?php

use yii\bootstrap\Html;
$this->title = 'Отложенные чеки';

?>

<div class="header">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/cashbox">Назад</a>
        </div>
        <div class="title">
            <h1><?=$this->title?></h1>
        </div>
    </div>
</div>
<div class="content">
    <?=''/*\yii\grid\GridView::widget([
        'dataProvider'  =>  $checksDataProvider
    ])*/?>
</div>
<div class="footer">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/cashbox/sales">Продажи</a>
        </div>
        <div class="right">
            <?=Html::button((\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 1 ? 'Опт' : 'Розница'), [
                'class' =>  'btn btn-lg btn-'.(\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 0 ? 'danger' : 'success'),
                'id'    =>  'changeCashboxType',
            ])?>
        </div>
    </div>
</div>

<!--<div class="vrbody">
    <div class="wrappercontroller top">
        <div class="content">
            <div class="headerleftcontroll">
                <div class="headerbutton"><a onclick="changeCartCode()" href="/admin/createbarcodeorder">Назад</a></div>
            </div>
            <div class="headertitle">Отложенные чеки</div>
        </div>
    </div>
    <div class="content information">
        <table id="listAsideCarts" width="100%" cellspacing="0">
            <tbody>
            <tr id="mTg7A8rhCpq">
                <td width="20px"><a onclick="removecart(&quot;mTg7A8rhCpq&quot;)" href="javascript:void(null)">
                        <script pagespeed_no_defer=""
                                src="https://krasota-style.com.ua/admin/_,Mjo.G6MsoSr-Iv.js.pagespeed.jm.0B6cKi8-2W.js"></script>
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg==""></a></td>
                <td onclick="moveToCart(&quot;mTg7A8rhCpq&quot;)" class="tdborder first" width="40px" align="center">1
                </td>
                <td onclick="moveToCart(&quot;mTg7A8rhCpq&quot;)" class="tdborder">
                    <div>Розничный Клиент</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: -2</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>-220</span> грн.</td>
            </tr>
            <tr id="1DNeGqDGUS4">
                <td width="20px"><a onclick="removecart(&quot;1DNeGqDGUS4&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;1DNeGqDGUS4&quot;)" class="tdborder first" width="40px" align="center">2
                </td>
                <td onclick="moveToCart(&quot;1DNeGqDGUS4&quot;)" class="tdborder">
                    <div>Максим Дублевский</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 1</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>72.64</span> грн.</td>
            </tr>
            <tr id="&quot;Mxd1SRcbWC6&quot;">
                <td width="20px"><a onclick="removecart(&quot;&quot;Mxd1SRcbWC6&quot;&quot;)"
                                    href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;&quot;Mxd1SRcbWC6&quot;&quot;)" class="tdborder first" width="40px"
                    align="center">3
                </td>
                <td onclick="moveToCart(&quot;&quot;Mxd1SRcbWC6&quot;&quot;)" class="tdborder">
                    <div>Дмитрий Панченко</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 4</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>420.71</span> грн.</td>
            </tr>
            <tr id="">
                <td width="20px"><a onclick="removecart(&quot;&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;&quot;)" class="tdborder first" width="40px" align="center">4</td>
                <td onclick="moveToCart(&quot;&quot;)" class="tdborder">
                    <div>Дмитрий Панченко</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 0</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>0</span> грн.</td>
            </tr>
            <tr id="nskaMvuLrf8">
                <td width="20px"><a onclick="removecart(&quot;nskaMvuLrf8&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;nskaMvuLrf8&quot;)" class="tdborder first" width="40px" align="center">5
                </td>
                <td onclick="moveToCart(&quot;nskaMvuLrf8&quot;)" class="tdborder">
                    <div>Дмитрий Панченко</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 2</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>118.88</span> грн.</td>
            </tr>
            <tr id="1Qlbx75VfTE">
                <td width="20px"><a onclick="removecart(&quot;1Qlbx75VfTE&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;1Qlbx75VfTE&quot;)" class="tdborder first" width="40px" align="center">6
                </td>
                <td onclick="moveToCart(&quot;1Qlbx75VfTE&quot;)" class="tdborder"></td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 3</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>2468.4</span> грн.</td>
            </tr>
            <tr id="3zjRrfeveLl">
                <td width="20px"><a onclick="removecart(&quot;3zjRrfeveLl&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;3zjRrfeveLl&quot;)" class="tdborder first" width="40px" align="center">7
                </td>
                <td onclick="moveToCart(&quot;3zjRrfeveLl&quot;)" class="tdborder"></td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 3</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>211.81</span> грн.</td>
            </tr>
            <tr id="XV5vGmLANLW">
                <td width="20px"><a onclick="removecart(&quot;XV5vGmLANLW&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;XV5vGmLANLW&quot;)" class="tdborder first" width="40px" align="center">8
                </td>
                <td onclick="moveToCart(&quot;XV5vGmLANLW&quot;)" class="tdborder"></td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 1</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>865.15</span> грн.</td>
            </tr>
            <tr id="fhFKECYCx3D">
                <td width="20px"><a onclick="removecart(&quot;fhFKECYCx3D&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;fhFKECYCx3D&quot;)" class="tdborder first" width="40px" align="center">9
                </td>
                <td onclick="moveToCart(&quot;fhFKECYCx3D&quot;)" class="tdborder">
                    <div>Наталия Рачицкая</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 2</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>129.44</span> грн.</td>
            </tr>
            <tr id="IZ4e5dAPuIA">
                <td width="20px"><a onclick="removecart(&quot;IZ4e5dAPuIA&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;IZ4e5dAPuIA&quot;)" class="tdborder first" width="40px" align="center">
                    10
                </td>
                <td onclick="moveToCart(&quot;IZ4e5dAPuIA&quot;)" class="tdborder"></td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 6</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>1098.82</span> грн.</td>
            </tr>
            <tr id="7wHwSYJ2l92">
                <td width="20px"><a onclick="removecart(&quot;7wHwSYJ2l92&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;7wHwSYJ2l92&quot;)" class="tdborder first" width="40px" align="center">
                    11
                </td>
                <td onclick="moveToCart(&quot;7wHwSYJ2l92&quot;)" class="tdborder">
                    <div>Татьяна Кулакова</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: -14</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>-660.81</span> грн.</td>
            </tr>
            <tr id="VmT87S9y3ly">
                <td width="20px"><a onclick="removecart(&quot;VmT87S9y3ly&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;VmT87S9y3ly&quot;)" class="tdborder first" width="40px" align="center">
                    12
                </td>
                <td onclick="moveToCart(&quot;VmT87S9y3ly&quot;)" class="tdborder">
                    <div>Наталия Рачицкая</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 4</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>1974.35</span> грн.</td>
            </tr>
            <tr id="SQQVlcVHLyD">
                <td width="20px"><a onclick="removecart(&quot;SQQVlcVHLyD&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;SQQVlcVHLyD&quot;)" class="tdborder first" width="40px" align="center">
                    13
                </td>
                <td onclick="moveToCart(&quot;SQQVlcVHLyD&quot;)" class="tdborder"></td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 2</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>152</span> грн.</td>
            </tr>
            <tr id="4U8AFvpNS1t">
                <td width="20px"><a onclick="removecart(&quot;4U8AFvpNS1t&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;4U8AFvpNS1t&quot;)" class="tdborder first" width="40px" align="center">
                    14
                </td>
                <td onclick="moveToCart(&quot;4U8AFvpNS1t&quot;)" class="tdborder">
                    <div>Наталия Рачицкая</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 1</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>33.02</span> грн.</td>
            </tr>
            <tr id="NajkXxPqeqI">
                <td width="20px"><a onclick="removecart(&quot;NajkXxPqeqI&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;NajkXxPqeqI&quot;)" class="tdborder first" width="40px" align="center">
                    15
                </td>
                <td onclick="moveToCart(&quot;NajkXxPqeqI&quot;)" class="tdborder">
                    <div>Наталия Рачицкая</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 9</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>2046.88</span> грн.</td>
            </tr>
            <tr id="PivVMkbTk8k">
                <td width="20px"><a onclick="removecart(&quot;PivVMkbTk8k&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;PivVMkbTk8k&quot;)" class="tdborder first" width="40px" align="center">
                    16
                </td>
                <td onclick="moveToCart(&quot;PivVMkbTk8k&quot;)" class="tdborder">
                    <div>Anna Koval</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 1</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>75</span> грн.</td>
            </tr>
            <tr id="S5KwMNIjnQY">
                <td width="20px"><a onclick="removecart(&quot;S5KwMNIjnQY&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;S5KwMNIjnQY&quot;)" class="tdborder first" width="40px" align="center">
                    17
                </td>
                <td onclick="moveToCart(&quot;S5KwMNIjnQY&quot;)" class="tdborder"></td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 6</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>695.07</span> грн.</td>
            </tr>
            <tr id="pizN55lcnEm">
                <td width="20px"><a onclick="removecart(&quot;pizN55lcnEm&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;pizN55lcnEm&quot;)" class="tdborder first" width="40px" align="center">
                    18
                </td>
                <td onclick="moveToCart(&quot;pizN55lcnEm&quot;)" class="tdborder"></td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 3</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>96.22</span> грн.</td>
            </tr>
            <tr id="lH2ElEDeRh4">
                <td width="20px"><a onclick="removecart(&quot;lH2ElEDeRh4&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;lH2ElEDeRh4&quot;)" class="tdborder first" width="40px" align="center">
                    19
                </td>
                <td onclick="moveToCart(&quot;lH2ElEDeRh4&quot;)" class="tdborder">
                    <div>Розничный Клиент</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 3</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>260.86</span> грн.</td>
            </tr>
            <tr id="fHcPp3PNxJ6">
                <td width="20px"><a onclick="removecart(&quot;fHcPp3PNxJ6&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;fHcPp3PNxJ6&quot;)" class="tdborder first" width="40px" align="center">
                    20
                </td>
                <td onclick="moveToCart(&quot;fHcPp3PNxJ6&quot;)" class="tdborder"></td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 4</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>138.68</span> грн.</td>
            </tr>
            <tr id="VJIigNI98gH">
                <td width="20px"><a onclick="removecart(&quot;VJIigNI98gH&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;VJIigNI98gH&quot;)" class="tdborder first" width="40px" align="center">
                    21
                </td>
                <td onclick="moveToCart(&quot;VJIigNI98gH&quot;)" class="tdborder">
                    <div>Розничный Клиент</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 8</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>2232.76</span> грн.</td>
            </tr>
            <tr id="IwpLy35I2Vp">
                <td width="20px"><a onclick="removecart(&quot;IwpLy35I2Vp&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;IwpLy35I2Vp&quot;)" class="tdborder first" width="40px" align="center">
                    22
                </td>
                <td onclick="moveToCart(&quot;IwpLy35I2Vp&quot;)" class="tdborder"></td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 1</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>125.48</span> грн.</td>
            </tr>
            <tr id="cqnEM4CHCcE">
                <td width="20px"><a onclick="removecart(&quot;cqnEM4CHCcE&quot;)" href="javascript:void(null)"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAWlBMVEWqHyL///+qHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKqHyKYrYlKAAAAHXRSTlMAAAEGLS9RUlNUWFlaW1yPkKqr3uDh4uPq6+z5+hC14BQAAAChSURBVHgBddDrDoMwCAVgLN7vrdVplfd/zUGVXcx2fpScL9EQIAZb54/DuxYh4USrHnRlKS80I31kNBEv0wyCFd1SJIDyv7VeKY6N3xmhlVZDyrqm0ElrwBFJZeUnC9IseFJVowkOUlWj/Td6tSx7f+7UQlC1upJU1v5cCRceW8fG2odzeSjplvzfQcAMX2YEOcWsNOevywM2dtr3yTYIgk/Jgx4vNV/CMAAAAABJRU5ErkJggg=="
                            pagespeed_url_hash="3355910045"
                            onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a></td>
                <td onclick="moveToCart(&quot;cqnEM4CHCcE&quot;)" class="tdborder first" width="40px" align="center">
                    23
                </td>
                <td onclick="moveToCart(&quot;cqnEM4CHCcE&quot;)" class="tdborder">
                    <div>Розничный Клиент</div>
                </td>
                <td class="tdborder" width="200px" align="left">
                    <div><span>Кол. товаров: 0</span></div>
                </td>
                <td class="tdborder" width="110px" align="left"><span>0</span> грн.</td>
            </tr>
            </tbody>
        </table>
        <div class="page-buffer"></div>
    </div>
</div>
<div id="vrfooter">
    <div class="wrappercontroller">
        <div class="content">
            <div class="bottomleftcontroll">
                <div class="headerbutton"><a href="/admin/getlistordersfromshop">Продажи</a></div>
            </div>
            <div class="headerrightcontroll">
                <div class="controlblock">
                    <div class="headerbutton red"><a onclick="changeOptRozniza()"
                                                     href="javascript:void(null)">Розница</a></div>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>-->