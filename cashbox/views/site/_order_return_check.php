<style type="text/css">
    body{margin: 0px; padding: 0px; background: url("//krasota-style.com.ua/images/bg.gif") repeat-x; font-family: Verdana; font-size: 14px;}
    hr{
        color: white;
        background-color: white;
        border-color: white;
    }
    .complect_tb TD, .complect_tb TH {
        font-size:100%;
        line-height:138%;
        padding:.1em .5em .5em .5em;
        width:2%;
        border-top:1px dotted #bbb
    }
    .complect_tb TH {
        background-color:#e7e7e7;
        line-height:116%;
        padding-top:.3em;
    }
    .complect_tb {
        width:100%;
        border:1px solid #bbb;
        margin-top:0;
    }
    .complect_tb TH {
        vertical-align:bottom;
        font-weight:bold;
        border:0;
        background-color:#e7e7e7;
        border-bottom:2px solid #d50e26;
        padding-top:.5em;
    }
    .complect_tb TH.name {
        width:30%;
    }
    .complect_tb TD.price {
        color: #FF0000;
        font-weight: bold;
        text-align: center;
    }
    .complect_tb TD {
        padding-top:.3em;
        vertical-align:top;
    }
    .complect_tb TD, .complect_tb TH {
        width:auto;
        padding-left:.7em;
        padding-right:.7em;
    }
    table.list{
        border-collapse: collapse;
        border: 1px solid #cccccc;
    }
    table.list th{
        text-align: left;
        background-color: #eeeeee;
    }
    table.list td{
        border: 1px solid #cccccc;
    }
    table.list tr.nact td{
        background: #F5D5CF;
    }
    table.list tr:hover {
        background: #999;
    }
    .menu_admin {
        font-size: 14px;
        color: #FFFFFF;
    }
    a.menu_admin:link, a.menu_admin:active, a.menu_admin:visited{
        color: #FFFFFF;
        text-decoration: none;
    }
    a.menu_admin:hover {
        text-decoration: underline;
        color: #FFFFFF;
    }
    .td_top{
        background: URL(http://krasota-style.com.ua/img/bg_top_admin.jpg) repeat-x;
        background-position: top;
        background-color: #8DB6D9;
    }
    select{
        width: 300px;
        border: 1px solid #C0C0C0;
    }
    table{
        font-size: 12px;
    }
    #r_name{
        border-bottom: 1px solid #D0D0D0;
        border-top: 1px solid #D0D0D0;
        background: #E0E0E0;
        clear: both;
        text-align: center;
        font-weight: bold;
        font-size: 14px;
    }
    .odd{background: #F0F0F0;}
    #catalog_cont ul{padding: 0px; margin: 0px;}
    #catalog_cont ul li{list-style: none; margin: 7px 0px;}
    #catalog_cont ul li ul{margin: 7px 0px 7px 10px; display: none;}
    li.st1 span{font-size: 14px; font-family: Verdana; color: #003767; height: 14px; display: inline-block; cursor: pointer;}
    #catalog_cont ul li a{color: #003767; text-decoration: none;}
    #catalog_cont ul li a:hover{color: #005baa; text-decoration: none;}
    li.st2 span{font-size: 14px; font-family: Verdana; color: #005baa; height: auto; background: none; padding: 0px; cursor: pointer;}
    tr.done {background-color: #6C0;}
    tr.undone {background-color: #F96;}

    body { background: none; color: #000;}
    table.printOrder { width: 90%; margin: 80px; color: #616161; }
    td.print_head { border-bottom: 1px solid #9a9a9a; }
    .leader { font-size: 26px; padding-top: 10px; height: 30px; }
    .tovars2 { border: none;}
    .tovars_img2 {border:1px solid #555555;
        width: 165px;
        height: 123px;}
    .tovars_img2 img{
        width: 165px;
        height: 123px;}
    .shopping table td {text-align:center;}
    .shopping table td.city { font-size:30px; text-decoration:underline; font-weight: bold; }
    .shopping table td.oblast { font-size:12px;}
    .shopping table td.nova_poshta { font-size:18px; }
    .shopping table td.name { font-size:18px; font-weight:bold;}
    .shopping table td.telefon { font-size:14px; }
    .shopping table td.plateg, .shopping table td.manager, .shopping table td.delivery { font-size:14px; font-weight:bold;white-space: nowrap; text-align: right; }
    .shopping table td.plateg > span > span, .shopping table td.manager > span > span, .shopping table td.delivery > span > span { padding-right: 5px; }
    .shopping table td.plateg > span, .shopping table td.manager > span, .shopping table td.delivery > span { margin-right: 33%; }
    .shopping table td.manager { border-bottom: 1px solid #9a9a9a; padding-bottom: 5px; }
    .shopping table td.plateg { border-top: 1px solid #9a9a9a; padding-top: 5px; }
    .shopping table td.zakaznumber { font-size: 60px; }
    .shopping table td.plateg img, .shopping table td.manager img, .shopping table td.delivery img{ vertical-align:middle; }
    .shopping input, .shopping textarea, .shopping select { border:none; }
    @media print {
        .shopping table td.plateg > span, .shopping table td.manager > span, .shopping table td.delivery > span { margin-right: 0% !important; }
        .pageend { page-break-after: always; }
        .block, x:-moz-any-link { page-break-before: always; }
    }
</style>
<script type="text/javascript">
    function PrintWindow(){
        window.print();
        //CheckWindowState();
    }

    function CheckWindowState(){
        if(document.readyState != "complete"){
            setTimeout(function(){CheckWindowState();}, 2000);
        }
    }

    PrintWindow();
</script>

<table width="100%">
    <tr>
        <td colspan="4">
            <img src="/img/krasota-style-logonakladna.png" width="246" height="71"/>
        </td>
        <td style="font-size:26px; text-align:right; font-weight: bold;" colspan="4">
            <img src="/img/icon-phone-nakladna.png" width="23" height="16"> 0 800 508 208
        </td>
    </tr>
</table>

<br>
<hr>
<br>

<span style="font-size:26px;">
    Возврат №<?=$order->id?> от <?=date('d.m.y')?> - <?=date('H:i')?></span>
<br><br>
<hr>
<br>
    <table class="print_excel">
        <tr>
            <th>№</th>
            <th>Код товара</th>
            <th>Наименование</th>
            <th>Кол.</th>
            <th>Цена (грн.)</th>
            <th>Сумма</th>
        </tr>
        <tr>
            <td>1</td>
            <td>2045810</td>
            <td>Цепочка Xuping, золото, 1шт</td>
            <td>1</td>
            <td colspan="3" align="center">нет в наличии</td>
        </tr>
    </table>
<hr>
<table border="0" width="100%">
    <tr>
        <td colspan="4"></td>
        <td colspan="2">Сумма:</td>
        <td border="1" align="right" style="border:1px #000 solid">52.83</td>
    </tr>
</table>