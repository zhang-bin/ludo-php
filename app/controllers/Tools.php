<?php
class Tools extends BaseCtrl {
    public function __construct() {
        parent::__construct('Tools');
    }

    public function lang() {
        $this->tpl->setFile('tools/lang')->display();
    }

    public function langDownload() {
        $lang = trim($_POST['langDownload']);
        $type = intval($_POST['type']);
        if ($type == 1) {
            $data = Lang::diff(LD_LANGUAGE_PATH, DEFAULT_LANGUAGE, $lang);
        } else if ($type == 2) {
            $data = Lang::diff(SITE_ROOT.'/static/api-lang', DEFAULT_LANGUAGE, $lang);
        } else {
            $data = Lang::diff(SITE_ROOT.'/static/socket-lang', DEFAULT_LANGUAGE, $lang);
        }

        $excel = new PHPExcel();
        $excel->getDefaultStyle()->getFont()->setName('宋体');
        $excel->getDefaultStyle()->getFont()->setSize(12);
        $sheetIndex = 0;
        foreach ($data as $sheetName => $datum) {
            $sheet = $excel->createSheet($sheetIndex);
            $i = 1;
            foreach ($datum as $k => $v) {
                $sheet->setCellValueByColumnAndRow(0, $i, $k);
                $sheet->setCellValueByColumnAndRow(1, $i, $v);
                $i++;
            }
            $sheet->setTitle($sheetName);
            $sheetIndex++;
        }
        $excel->setActiveSheetIndex(0);

        $dir = LD_UPLOAD_TMP_PATH.'/'.date(DATE_FORMAT).'/';
        if (!is_dir($dir)) mkdir($dir);
        $filename = $dir.uniqid(time()).'.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save($filename);
        downloadLink($filename, '语言.xlsx');
    }

    public function langUpload() {
        $lang = trim($_POST['langUpload']);
        if ($lang == DEFAULT_LANGUAGE) {
            redirect('tools/lang');
            return;
        }

        $filename = $_FILES['file']['tmp_name'];
        $reader = new PHPExcel_Reader_Excel2007();
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filename);
        $sheets = $excel->getAllSheets();
        $data = array();
        foreach ($sheets as $sheet) {
            $maxRow = $sheet->getHighestRow();

            $title = $sheet->getTitle();
            for ($i = 1; $i <= $maxRow; $i++) {
                $key = trim($sheet->getCellByColumnAndRow(0, $i)->getValue());
                $value = trim($sheet->getCellByColumnAndRow(2, $i)->getValue());
                $data[$title][$key] = $value;
            }
        }
        $type = intval($_POST['type']);
        if ($type == 1) {
            Lang::merge(LD_LANGUAGE_PATH, $lang, $data);
        } else if ($type == 2) {
            Lang::merge(SITE_ROOT.'/static/api-lang', $lang, $data);
        } else {
            Lang::merge(SITE_ROOT.'/static/socket-lang', $lang, $data);
        }
        redirect('tools/lang');
    }
}