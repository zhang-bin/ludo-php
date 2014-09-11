<?php
/**
 * 项目相关的过滤函数
 * 
 * @author libok
 *
 */
class Filter extends LdFilter {	
	public static function passpodSn($data) {
		if(!empty($data)) {
			$data = str_replace('-','', $data);
			$data = str_replace(' ','', $data);
		}
		return $data;
	}

}
?>