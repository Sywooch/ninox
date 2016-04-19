<?php

namespace backend\modules\export\controllers;

use backend\models\History;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Worksheet_Drawing;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `export` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionExcel($param){
        $order = History::findOne($param);

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$param} не найден!");
        }

        $headerStyle = [
            'font' => [
                'bold' => false,
                'size' => 16,
                'color' => ['rgb' => '484848'],
            ],
        ];

        $borderedStyle = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
        ];

        $excel = new \PHPExcel();

        $list = $excel->getActiveSheet();

        $list->setTitle("Заказ №{$order->number} от ".\Yii::$app->formatter->asDatetime($order->added, 'php:d.m.Y'))
           	->mergeCells("A1:C1")
	        ->mergeCells("A2:C2")
	        ->mergeCells("A3:C3")
            ->mergeCells("A7:C7")
	        ->mergeCells("A8:C8")
	        ->mergeCells("A9:C9")
	        ->mergeCells("A10:C10")
	        ->mergeCells("I1:K1")
            ->setCellValue("A1", "Интернет-магазин")
            ->setCellValue("A2", "krasota-style.com.ua")
            ->setCellValue("A3", "тел.: 0 800 508 208")
            ->setCellValue("A7", "Данные пользователя:")
            ->setCellValue("A8", "{$order->customerSurname} {$order->customerName}")
            ->setCellValue("A9", " {$order->customerPhone}")
            ->setCellValue("A10", "{$order->deliveryCity} {$order->deliveryRegion}")
            ->setCellValue("A11", $order->deliveryInfo)
            ->setCellValue("D1", "Заказ №{$order->number} от ".\Yii::$app->formatter->asDatetime($order->added, 'php:d.m.Y H:i'))
            ->setCellValue("A13", " №")
            ->setCellValue("B13", "Фото")
            ->setCellValue("C13", "Код товара")
            ->setCellValue("D13", "Наименование")
            ->setCellValue("E13", "Кол.")
            ->setCellValue("F13", "Цена (грн.)")
            ->setCellValue("G13", "Сумма (грн.)");

        $list->getStyle("A9")
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        $list->getStyle("D1")
            ->applyFromArray($headerStyle);

        for($i = "A"; $i <= "G"; $i++){
            $list->getStyle($i."13")
                ->applyFromArray($borderedStyle);
        }

        $list->getColumnDimension("A")->setWidth("4");
        $list->getColumnDimension("B")->setWidth("15");
        $list->getColumnDimension("C")->setWidth("10.3");
        $list->getColumnDimension("D")->setWidth("28");
        $list->getColumnDimension("E")->setAutoSize(true);
        $list->getColumnDimension("F")->setWidth("10");
        $list->getColumnDimension("G")->setAutoSize(true);

        $i = 0;

        foreach($order->items as $item){
            $i++;

            $row = (13 + $i);

            $list->setCellValue("A{$row}", $i);

            if(!empty($item->photo) && file_exists($_SERVER['DOCUMENT_ROOT']."/img/catalog/sm/".$item->photo)){
                $objDrawing = new PHPExcel_Worksheet_Drawing();

                $objDrawing->setWorksheet($list)
                    ->setName($item->name)
                    ->setOffsetX(1)
                    ->setOffsetY(5)
                    ->setWidth(100)
                    ->setCoordinates("B".$row);

                $objDrawing->setPath($_SERVER['DOCUMENT_ROOT']."/img/catalog/sm/".$item->photo);
            }

            $list->getRowDimension($row)->setRowHeight(60);
            $list->setCellValue("C".$row, $item->code);
            $list->setCellValue("D".$row, $item->name);
            $list->getStyle("D".$row)->getAlignment()->setWrapText(true);
            $list->setCellValue("E".$row, $item->count);

            if($item->nalichie != "0"){
                //$price_sum = ($good['PriceOut'] * $good['Qtty']);
                //$list->setCellValue("F".$row, ($good['discountPrice'] ? $good['PriceOut'].($good['priceRuleID'] == 0 ? '**' : '*') : $good['PriceOut']));
                $list->setCellValue("G".$row, ($item->price * $item->count));
                /*$totalsumm_all += $price_sum;
                $sumToDiscount += $good['discountPrice'] ? 0 : $price_sum;
                $summ_witout_discount += $good['discountPrice'] ? ($good['realPrice']*$good['Qtty']) : $price_sum;
                $saleDisc += ($good['priceRuleID'] == 0 && $good['realPrice'] != 0) ? (($good['realPrice'] - $good['PriceOut']) * $good['Qtty']) : 0;
                $actionDisc += $good['priceRuleID'] != 0 ? (($good['realPrice'] - $good['PriceOut']) * $good['Qtty']) : 0;*/
            }else{
                $list->mergeCells("F".$row.":G".$row);
                $list->setCellValue("F".$row, "Нет в наличии");
            }
            for($n = "A"; $n <= "G"; $n++){
                $list->getStyle($n.$row)->applyFromArray($borderedStyle);
            }
        }
    }
}
