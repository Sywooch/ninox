<?php

namespace backend\modules\export\controllers;

use backend\models\History;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Worksheet_Drawing;
use PHPExcel_Writer_Excel5;
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
        $order = History::findWith()->where(['id' => $param])->one();
        $row = $i = $sum = $stockDiscount = $discountPercent = $sumWithoutDiscount = $customerDiscountSum = $saleDiscount = 0;
        $fileName = "nakladna{$order->number}.xls";
        $filePath = \Yii::getAlias('@export')."/{$fileName}";


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

        $list
            ->getColumnDimension("A")
            ->setWidth("4");

        $list
            ->getColumnDimension("B")
            ->setWidth("15");

        $list
            ->getColumnDimension("C")
            ->setWidth("10.3");

        $list
            ->getColumnDimension("D")
            ->setWidth("28");

        $list
            ->getColumnDimension("E")
            ->setAutoSize(true);

        $list
            ->getColumnDimension("F")
            ->setWidth("10");

        $list
            ->getColumnDimension("G")
            ->setAutoSize(true);

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

            $list->getRowDimension($row)
                ->setRowHeight(60);

            $list->setCellValue("C".$row, $item->code);

            $list->setCellValue("D".$row, $item->name);

            $list->getStyle("D".$row)
                ->getAlignment()
                ->setWrapText(true);

            $list->setCellValue("E".$row, $item->count);

            if(!empty($item->nalichie)){
                $list->setCellValue("F{$row}", (!empty($item->discountType) ? $item->price.($item->priceRuleID == 0 ? '**' : '*') : $item->price));
                $list->setCellValue("G{$row}", ($item->price * $item->count));

                $sum += $item->sum;
                $customerDiscountSum += $item->customerDiscountSum;
                $sumWithoutDiscount += $item->originalSum;
                $saleDiscount += ($item->priceRuleID == 0 && !empty($item->discountType)) ? $item->discountSum : 0;
                $stockDiscount += $item->discountSum * $item->count;
            }else{
                $list->mergeCells("F{$row}:G{$row}")
                    ->setCellValue("F{$row}", "Нет в наличии");
            }

            for($n = "A"; $n <= "G"; $n++){
                $list->getStyle($n.$row)
                    ->applyFromArray($borderedStyle);
            }
        }

        $row += 2;

        $list->mergeCells("A{$row}:C{$row}")
            ->setCellValue("A{$row}", "Сумма (без скидки):")
            ->setCellValue("G{$row}", $sumWithoutDiscount)
            ->getStyle("G{$row}")
            ->applyFromArray($borderedStyle);

        //Пишем инфо о скидке на акционный товар
        if($stockDiscount){
            $row += 2;

            $list->mergeCells("A{$row}:C{$row}")
                ->setCellValue("A{$row}", "*Сумма скидки на акционный товар:")
                ->setCellValue("G{$row}", (round($stockDiscount, 2)))
                ->getStyle("G{$row}")
                ->applyFromArray($borderedStyle);
        }

        //Пишем инфо о скидке на распродажный товар
        if($saleDiscount){
            $row += 2;

            $list->mergeCells("A{$row}:C{$row}")
                ->setCellValue("A{$row}", "**Сумма скидки на товар из распродажи:")
                ->setCellValue("G{$row}", (round($saleDiscount, 2)))
                ->getStyle("G{$row}")
                ->applyFromArray($borderedStyle);
        }

        if(!empty($order->customer->cardNumber) && !empty(\Yii::$app->request->get("withoutDiscount")) && $customerDiscountSum > 0){
            $row += 2;

            $discountPercent = round((2 * $customerDiscountSum / 100), 2);

            $list->mergeCells("A{$row}:C{$row}")
                ->setCellValue("A{$row}", "Дисконт 2%, грн.:")
                ->setCellValue("G{$row}", round((2 * $customerDiscountSum / 100), 2))
                ->getStyle("G{$row}")
                ->applyFromArray($borderedStyle);
        }

        if ($order['amountDeductedOrder'] <> 0){
            $row += 2;

            $list->mergeCells("A{$row}:C{$row}")
                ->setCellValue("A{$row}", "Списано со счета :")
                ->setCellValue("G{$row}", $order['amountDeductedOrder'])
                ->getStyle("G{$row}")
                ->applyFromArray($borderedStyle);
        }

        $row += 2;

        $list->mergeCells("A{$row}:C{$row}")
            ->setCellValue("A{$row}", "Сумма к оплате, грн.:")
            ->setCellValue("G{$row}", (round(($sum - $customerDiscountSum - $order->amountDeductedOrder), 2)))
            ->getStyle("G{$row}")
            ->applyFromArray($borderedStyle);

        if(file_exists("{$filePath}") == true){
            unlink("{$filePath}");
        }

        $PHPExcel_IOFactory = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $PHPExcel_IOFactory->save("{$filePath}");

        if(\Yii::$app->request->get("onlySave") != 'true'){
            header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename={$fileName}");
            $objWriter = new PHPExcel_Writer_Excel5($excel);
            $objWriter->save('php://output');
        }
    }
}
