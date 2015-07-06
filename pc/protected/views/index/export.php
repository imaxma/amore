<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/4 0004
 * Time: 12:44
 */
$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");


$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', '批次编号')
    ->setCellValue('B1', '创建日期')
    ->setCellValue('C1', '报关公司')
    ->setCellValue('D1', '分类')
    ->setCellValue('E1', 'ETD')
    ->setCellValue('F1', 'ETA')
    ->setCellValue('G1', '换单')
    ->setCellValue('H1', '换单延迟')
    ->setCellValue('I1', '报检')
    ->setCellValue('J1', '报检延迟')
    ->setCellValue('K1', '审单')
    ->setCellValue('L1', '审单延迟')
    ->setCellValue('M1', '付税')
    ->setCellValue('N1', '付税延迟')
    ->setCellValue('O1', '报关')
    ->setCellValue('P1', '报关延迟')
    ->setCellValue('Q1', '查验')
    ->setCellValue('R1', '查验延迟')
    ->setCellValue('S1', '入库')
    ->setCellValue('T1', '入库延迟')
    ->setCellValue('U1', '总延迟')
    ->setCellValue('V1', '备注');

$objPHPExcel->setActiveSheetIndex(0);
if (!empty($result))
{
    $i = 2;
    foreach ($result as $v)
    {
        if ($v['Type'] == 'full'){$type = '整柜';}elseif ($v['Type'] == 'air'){$type = '空运';}else{$type = '散货';}
        //$delay = isset($v['DE']['delay']) ? $v['DE']['delay'] : 0 + isset($v['AFE']['delay']) ? $v['AFE']['delay'] : 0 + isset($v['DI']['delay']) ? $v['DI']['delay'] : 0 + isset($v['TAX']['delay']) ? $v['TAX']['delay'] : 0 + isset($v['Customs']['delay']) ? $v['Customs']['delay'] : 0 + isset($v['Inspection']['delay']) ? $v['Inspection']['delay'] : 0 + isset($v['Warehouse']['delay']) ? $v['Warehouse']['delay'] : 0;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, $v['BatchNum']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, date('Y/m/d',strtotime($v['CreatedTime'])));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $v['Company_Name']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $type);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, date('Y/m/d',strtotime($v['etd'])));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i, date('Y/m/d',strtotime($v['eta'])));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i, isset($v['DE']['date']) ? date('Y/m/d',strtotime($v['DE']['date'])) : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i, isset($v['DE']['delay']) ? $v['DE']['delay'] : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$i, isset($v['AFE']['date']) ? date('Y/m/d',strtotime($v['AFE']['date'])) : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$i, isset($v['AFE']['delay']) ? $v['AFE']['delay'] : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$i, isset($v['DI']['date']) ? date('Y/m/d',strtotime($v['DI']['date'])) : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$i, isset($v['DI']['delay']) ? $v['DI']['delay'] : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$i, isset($v['TAX']['date']) ? date('Y/m/d',strtotime($v['TAX']['date'])) : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$i, isset($v['TAX']['delay']) ? $v['TAX']['delay'] : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$i, isset($v['Customs']['date']) ? date('Y/m/d',strtotime($v['Customs']['date'])) : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$i, isset($v['Customs']['delay']) ? $v['Customs']['delay'] : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$i, isset($v['Inspection']['date']) ? date('Y/m/d',strtotime($v['Inspection']['date'])) : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$i, isset($v['Inspection']['delay']) ? $v['Inspection']['delay'] : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$i, isset($v['Warehouse']['date']) ? date('Y/m/d',strtotime($v['Warehouse']['date'])) : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$i, isset($v['Warehouse']['delay']) ? $v['Warehouse']['delay'] : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$i, isset($v['TotalDelay']) ? $v['TotalDelay'] : '-');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$i, $v['Notes']);
        $i++;
    }
}


$objPHPExcel->getActiveSheet()->setTitle('Amore_Customs');


$objPHPExcel->setActiveSheetIndex(0);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Amore_Customs.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
