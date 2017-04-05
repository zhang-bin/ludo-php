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

    public function ideHelperGenerate() {
        $serviceProviders = [
            \Ludo\Support\Facades\Crypt::class
        ];
        $helper = '<pre>';
        $newLine = "\n";
        $blank = '    ';
        $helper .= 'namespace  {
    exit("This file should not be included, only analyzed by your IDE");
}
';
        $helper .= $newLine;
        $helper .= 'namespace Ludo\Support\Facades {';
        $helper .= $newLine;
        foreach ($serviceProviders as $serviceProvider) {
            $facadeAccessor = $serviceProvider::getFacadeAccessor();
            $obj = \Ludo\Support\ServiceProvider::getInstance()->getRegisteredAbstract($facadeAccessor);
            $className = get_class($obj);
            $reflection = new ReflectionClass($className);
            $helper .= $newLine;
            $helper .= $blank.'class '.array_pop(explode('\\', $serviceProvider)).' {';
            $helper .= $newLine;
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                if ($method->getName() == '__construct') continue;
                $comment = $blank.str_replace("\n", "\n".$blank, $method->getDocComment());
                $helper .= $blank.$comment;
                $helper .= $newLine;
                $helper .= $blank.$blank.'public static function '.$method->getName().'(';
                $parameters = $method->getParameters();
                $param = array();
                foreach ($parameters as $parameter) {
                    $paramStr = '$'.$parameter->getName();
                    if ($parameter->isOptional()) {
                        $default = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
                        if (is_bool($default)) {
                            $default = $default ? 'true' : 'false';
                        } elseif (is_array($default)) {
                            $default = 'array()';
                        } elseif (is_null($default)) {
                            $default = 'null';
                        } else {
                            $default = "'" . trim($default) . "'";
                        }
                        $paramStr .= " = $default";
                    }
                    $param[] = $paramStr;
                }
                $helper .= implode(', ', $param).') {';
                $helper .= $newLine;

                $helper .= $blank.$blank.$blank;
                if (!stristr($comment, '@return void')) {
                    $helper .= 'return ';
                }
                $helper .= '\\'.$className.'::'.$method->getName().'('.implode(', ', $param).');';
                $helper .= $newLine;
                $helper .= $blank.$blank.'}';
                $helper .= $newLine;
                $helper .= $newLine;
            }
            $helper .= $blank.'}';
        }
        $helper .= $newLine;
        $helper .= '}';


        $serviceProviders = [
            \Ludo\Support\Facades\Crypt::class,
            \Ludo\Support\Filter::class,
            \Ludo\Support\Validator::class,
            \Ludo\Foundation\Lang::class,
            \Ludo\Config\Config::class,
            \Ludo\Support\Factory::class,
            \Ludo\View\View::class,
            \Ludo\Task\TaskQueue::class,
            \Ludo\Database\QueryException::class,
            \Ludo\Counter\Counter::class
        ];

        $helper .= $newLine.$newLine;
        $helper .= 'namespace {';
        $helper .= $newLine;
        foreach ($serviceProviders as $serviceProvider) {
            $helper .= $blank.'class '.array_pop(explode('\\', $serviceProvider)).' extends \\'.$serviceProvider.' {}';
            $helper .= $newLine;
            $helper .= $newLine;
        }
        $helper .= '}';
        $helper .= '</pre>';
        echo $helper;
    }
}