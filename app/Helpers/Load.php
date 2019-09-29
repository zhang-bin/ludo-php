<?php

namespace App\Helpers;

use Ludo\View\View;

class Load
{
    private static $webModules = [
        'jquery' => [
            'jquery.min.js' => 'js',
        ],
        'bootstrap' => [
            'js/bootstrap.min.js' => 'js',
            'css/bootstrap.min.css' => 'css',
            'css/font-awesome.min.css' => 'css',
        ],
        'select2' => [
            'js/select2.min.js' => 'js',
            'css/select2.min.css' => 'css',
        ],
        'adminlte' => [
            'css/AdminLTE.min.css' => 'css',
            'css/skins/skin-blue.min.css' => 'css',
            'js/adminlte.min.js' => 'js',
        ],
        'common' => [
            'common.css' => 'css',
            'common.js' => 'js'
        ]
    ];

    public static function web(string $name): void
    {
        $module = self::$webModules[$name];
        if (empty($module)) {
            return;
        }

        $css = '<link href="%s" rel="stylesheet" />';
        $js = '<script type="text/javascript" src="%s"></script>';

        foreach ($module as $file => $fileType) {
            $html = '';
            $filename = LD_PUBLIC_URL.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.$file;
            switch ($fileType) {
                case 'css':
                    $html = sprintf($css, $filename);
                    break;
                case 'js':
                    $html = sprintf($js, $filename);
                    break;
                default:
                    break;
            }

            View::addResource($html, $fileType);
        }
    }

	public static function conf(string $name)
    {
		return include LD_CONF_PATH.'/'.ucfirst($name).'.conf'.php;
	}
}
