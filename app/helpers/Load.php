<?php
class Load {
	/**
	 * 'jsname' => array(
	 * 		'root_base' => SITE_URL or THEME_URL. [optional, default is SITE_URL]
	 * 		'base' => don't forget the leading and trailing slash. [optional, default is /img/]
	 * 				  eg: '/img/', '/js/', '/img/greybox/', 
	 * 		'file' => eg: 'xxxx.js', 'images/zzz.css'
	 * ),
	 */
	private static $jsModules = array(
		'jquery' => array(
			'files' => array(
				'jquery-1.8.2.min.js' => 'js',
			),
			'debug_files' => array(
				'jquery-1.8.2.js' => 'js',
			),
		),
		'placeholder' => array(
			'files' => array(
					'placeholder.js' => 'js',
			),
			'debug_files' => array(
					'placeholder.js' => 'js',
			),
		),
		'common' => array(
			'files' => array(
				'common.js' => 'js',
			),
			'debug_files' => array(
				'common.js' => 'js',
			),
		),
		'jquerytools' => array(
			'base' => '/img/jquerytools/',
			'files' => array(
				'jquery.tools.min.js' => 'js',
				'jquery.tools.css' => 'css',
			)
		),
		'multiSelect' => array(
			'base' => '/img/multiselect/',
			'files' => array(
				'js/jquery-ui.min.js' => 'js',
				'js/jquery.multiselect.js' => 'js',
				'jquery-ui.css' => 'css',
				'jquery.multiselect.css' => 'css'
			)
		),
		'amcharts' => array(
			'base' => '/img/amcharts/',
			'files' => array(
				'amcharts.js' => 'js',
				'serial.js' => 'js',
                'pie.js' => 'js'
			)
		),
		'bootstrap' => array(
			'base' => '/img/bootstrap/',
			'files' => array(
				'js/bootstrap.min.js' => 'js',
				'css/bootstrap.min.css' => 'css',
				'css/font-awesome.min.css' => 'css',
				'css/font-awesome-ie7.min.css' => 'css',
				'css/bootstrap-responsive.min.css' => 'css'
			),
			'debug_files' => array(
				'js/bootstrap.js' => 'js',
				'css/bootstrap.css' => 'css',
				'css/font-awesome.css' => 'css',
				'css/font-awesome-ie7.css' => 'css',
				'css/bootstrap-responsive.css' => 'css'
			)
		),
		'bootstrap-datetimepicker' => array(
			'base' => '/img/bootstrap-datetimepicker/',
			'files' => array(
				'js/bootstrap-datetimepicker.min.js' => 'js',
				'css/bootstrap-datetimepicker.min.css' => 'css',
			),
			'debug_files' => array(
				'js/bootstrap-datetimepicker.js' => 'js',
				'css/bootstrap-datetimepicker.css' => 'css',
			),
		),
		'bootstrap-select' => array(
			'base' => '/img/bootstrap-select/',
			'files' => array(
				'bootstrap-select.min.js' => 'js',
				'bootstrap-select.min.css' => 'css'
			),
			'debug_files' => array(
				'bootstrap-select.js' => 'js',
				'bootstrap-select.css' => 'css'
			)
		),
		'bootstrap-multiselect' => array(
			'base' => '/img/bootstrap-multiselect/',
			'files' => array(
				'js/bootstrap-multiselect.js' => 'js',
				'css/bootstrap-multiselect.css' => 'css'
			)
		),
		'uploadify' => array(
			'base' => '/img/uploadify/',
			'files' => 	array(
				'jquery.uploadify.min.js' => 'js',
				'uploadify.min.css' => 'css'
			),
			'debug_files' => array(
				'jquery.uploadify.js' => 'js',
				'uploadify.css' => 'css'
			),
		),
		'bootstrap-editable' => array(
			'base' => '/img/bootstrap-editable/',
			'files' => array(
				'js/bootstrap-editable.min.js' => 'js',
				'css/bootstrap-editable.css' => 'css',
			),
			'debug_files' => array(
				'js/bootstrap-editable.js' => 'js',
				'css/bootstrap-editable.css' => 'css',
			),
		),
		'autocomplete' => array(
			'base' => '/img/autocomplete/',
			'files' => array(
				'jquery.autocomplete.min.js' => 'js',
				'styles.min.css' => 'css'
			),
			'debug_files' => array(
				'jquery.autocomplete.js' => 'js',
				'styles.css' => 'css'
			)
		),
        'bootstrap-switch' => array(
            'base' => '/img/bootstrap-switch/',
            'files' => array(
                'bootstrap-switch.min.css' => 'css',
                'bootstrap-switch.min.js' => 'js'
            ),
            'debug_files' => array(
                'bootstrap-switch.css' => 'css',
                'bootstrap-switch.js' => 'js'
            )
        ),
	);
	
	/**
	 * 'cssname' => array(
	 * 		'root_base' => SITE_URL or THEME_URL. [optional, default is THEME_URL]
	 * 		'file' => eg: '/img/xxxx.css', '/css/zzz.css'
	 * )
	 */
	private static $cssModules = array(
		'style' => array(
			'file' => '/img/style.css',
		),
        'wizard' => array(
            'file' => '/img/wizard.css',
        )
	);
	
	public static function js($jsname, $return=false) {
		$module = self::$jsModules[$jsname];
		if (!$module) return;
		
		$result = '';
		$css = '<link href="%s" rel="stylesheet" type="text/css" media="all" />'."\n";
		$js =  '<script type="text/javascript" src="%s"></script>'."\n";
		$root_base = isset($module['root_base']) ? $module['root_base'] : SITE_URL;
		$base = isset($module['base']) ? $module['base'] : '/img/';
		$files = DEBUG && isset($module['debug_files']) ? $module['debug_files'] : $module['files'];

		foreach ($files as $file => $type) {
			$result .= sprintf($$type, $root_base.$base.$file);
		}
		if (!$return) 
			echo $result;
		else
			return $result;
	}
	public static function css($jsname, $return=false) {
		$module = self::$cssModules[$jsname];
		if (!$module) return;
		$root_base = isset($module['root_base']) ? $module['root_base'] : THEME_URL;
		$result = '<link href="'. $root_base.$module['file'] .'" rel="stylesheet" type="text/css" media="all" />'."\n"; 
		if (!$return) 	echo $result;
		else	return $result;
	}
	public static function helper($name) {
		if (!class_exists($name, false)) {
			include LD_HELPER_PATH.'/'.$name.php;
		}
	}
	public static function conf($name) {
		return include LD_CONF_PATH.'/'.ucfirst($name).'.conf'.php;
	}
}