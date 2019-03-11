<?php
class Load
{
	/**
	 * 'jsname' => array(
	 * 		'root_base' => SITE_URL or THEME_URL. [optional, default is SITE_URL]
	 * 		'base' => don't forget the leading and trailing slash. [optional, default is /img/]
	 * 				  eg: '/img/', '/js/', '/img/greybox/', 
	 * 		'file' => eg: 'xxxx.js', 'images/zzz.css'
	 * ),
	 */
	private static $webModules = [
	    'layui' => [
	        'base' => '/public/img/layui',
            'files' => [
                'css/layui.css' => 'css',
                'layui.js' => 'js',
            ],
            'debug_files' => [
                'css/layui.css' => 'css',
                'layui.js' => 'js',
            ]
        ],
        'layuicms' => [
            'base' => '/public/img/layuicms',
            'files' => [
                'css/public.css' => 'css',
                'css/index.css' => 'css',
                'css/common.css' => 'css',
                'js/cache.js' => 'js',
            ],
            'debug_files' => [
                'css/public.css' => 'css',
                'css/index.css' => 'css',
                'css/common.css' => 'css',
                'js/cache.js' => 'js',
            ]
        ],
        'main' => [
            'base' => '/public/img/layuicms',
            'files' => [
                'js/index.js' => 'js',
            ],
            'debug_files' => [
                'js/index.js' => 'js',
            ]
        ],
        'formSelect' => [
            'base' => '/public/img/formSelect',
            'files' => [
                'formSelects-v4.css' => 'css',
            ],
            'debug_files' => [
                'formSelects-v4.css' => 'css',
            ],

        ]
	];
	
	public static function web($moduleName, $return = true, $loadToTemplate = true)
    {
		$module = self::$webModules[$moduleName];
		if (!$module) return;
		
		$result = '';
		$css = '<link href="%s" rel="stylesheet" type="text/css" media="all" />'."\n";
		$js =  '<script type="text/javascript" src="%s"></script>'."\n";
		$files = DEBUG && isset($module['debug_files']) ? $module['debug_files'] : $module['files'];

		foreach ($files as $file => $type) {
		    switch ($type) {
                case 'css':
                    $resource = sprintf($css, $module['base'].'/'.$file);
                    break;
                case 'js':
                    $resource = sprintf($js, $module['base'].'/'.$file);
                    break;
                default:
                    $resource = '';
                    break;
            }
			$result .= $resource;
			if ($loadToTemplate)  {
			    View::addResource($resource, $type);
            }
		}
		if (!$return) 
			echo $result;
		else
			return $result;
	}

	public static function helper($name)
    {
		if (!class_exists($name, false)) {
			include LD_HELPER_PATH.'/'.$name.php;
		}
	}

	public static function conf($name)
    {
		return include LD_CONF_PATH.'/'.ucfirst($name).'.conf'.php;
	}
}
