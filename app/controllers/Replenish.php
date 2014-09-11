<?php
class Replenish extends LdBaseCtrl {
    public function __construct() {
        parent::__construct('Replenish');
    }

    public function index() {
        $this->tpl->setFile('replenish/index')
                  ->display();
    }

    public function reset() {
        $filename = SITE_ROOT.'/static/replenish.txt';
        file_exists($filename) && unlink($filename);
        $script = SITE_ROOT.'/bin/replenish.py';
        $cmd = "/usr/bin/python {$script} {$filename}";
        exec($cmd);
        if (file_exists($filename)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function report() {
        $filename = SITE_ROOT.'/static/replenish.txt';
        if (!file_exists($filename)) touch($filename);
        $data = trim(file_get_contents($filename));

        if (empty($data)) {
            $this->reset();
        } else {
            $data = json_decode(gzuncompress(base64_decode($data)));
            $excel = new Excel();
            downloadLink($excel->writeData($data), 'Lenovo Mobile Phone Weekly Replenish Plan('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
        }
    }
}