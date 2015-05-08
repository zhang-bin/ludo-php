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
	private static $jsModules = [
		'jquery' => [
			'files' => [
				'jquery-1.11.3.min.js' => 'js',
			],
			'debug_files' => [
				'jquery-1.11.3.js' => 'js',
			],
		],
		'placeholder' => [
			'files' => [
                'placeholder.js' => 'js',
			],
			'debug_files' => [
                'placeholder.js' => 'js',
			],
		],
		'common' => [
			'files' => [
				'common.js' => 'js',
			],
			'debug_files' => [
				'common.js' => 'js',
			],
		],
		'bootstrap' => [
			'base' => '/img/bootstrap/',
			'files' => [
				'js/bootstrap.min.js' => 'js',
				'css/bootstrap.min.css' => 'css',
			],
			'debug_files' => [
				'js/bootstrap.js' => 'js',
				'css/bootstrap.css' => 'css',
			]
		],
		'bootstrap-datetimepicker' => [
			'base' => '/img/bootstrap-datetimepicker/',
			'files' => [
				'js/bootstrap-datetimepicker.min.js' => 'js',
				'css/bootstrap-datetimepicker.min.css' => 'css',
			],
			'debug_files' => [
				'js/bootstrap-datetimepicker.js' => 'js',
				'css/bootstrap-datetimepicker.css' => 'css',
			],
		],
		'bootstrap-select' => [
			'base' => '/img/bootstrap-select/',
			'files' => [
				'js/bootstrap-select.min.js' => 'js',
				'css/bootstrap-select.min.css' => 'css'
			],
			'debug_files' => [
				'js/bootstrap-select.js' => 'js',
				'css/bootstrap-select.css' => 'css'
			]
		],
		'bootstrap-editable' => [
			'base' => '/img/bootstrap-editable/',
			'files' => [
				'js/bootstrap-editable.min.js' => 'js',
				'css/bootstrap-editable.css' => 'css',
			],
			'debug_files' => [
				'js/bootstrap-editable.js' => 'js',
				'css/bootstrap-editable.css' => 'css',
			],
		],
        'bootstrap-fileinput' => [
            'base' => '/img/bootstrap-fileinput/',
            'files' => [
                'js/fileinput.min.js' => 'js',
                'css/fileinput.min.css' => 'css'
            ],
            'debug_files' => [
                'js/fileinput.js' => 'js',
                'css/fileinput.css' => 'css'
            ]
        ]
	];
	
	/**
	 * 'cssname' => array(
	 * 		'root_base' => SITE_URL or THEME_URL. [optional, default is THEME_URL]
	 * 		'file' => eg: '/img/xxxx.css', '/css/zzz.css'
	 * )
	 */
	private static $cssModules = [
		'style' => [
			'file' => '/img/common/style.css',
		],
	];
	
	public static function js($jsName, $return = true, $loadToTemplate = true)
    {
		$module = self::$jsModules[$jsName];
		if (!$module) return;
		
		$result = '';
		$css = '<link href="%s" rel="stylesheet" type="text/css" media="all" />'."\n";
		$js =  '<script type="text/javascript" src="%s"></script>'."\n";
		$root_base = isset($module['root_base']) ? $module['root_base'] : LD_PUBLIC_PATH;
		$base = isset($module['base']) ? $module['base'] : '/img/';
		$files = DEBUG && isset($module['debug_files']) ? $module['debug_files'] : $module['files'];

		foreach ($files as $file => $type) {
			$resource = sprintf($$type, $root_base.$base.$file);
			$result .= $resource;
			if ($loadToTemplate)  View::addResource($resource, $type);
		}
		if (!$return) 
			echo $result;
		else
			return $result;
	}

	public static function css($cssName, $return = true, $loadToTemplate = true)
    {
		$module = self::$cssModules[$cssName];
		if (!$module) return;
		$root_base = isset($module['root_base']) ? $module['root_base'] : LD_PUBLIC_PATH;
		$result = '<link href="'. $root_base.$module['file'] .'" rel="stylesheet" type="text/css" media="all" />'."\n";
		if ($loadToTemplate)  View::addResource($result);

		if (!$return) 	echo $result;
		else	return $result;
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
