<?php
class DateUtil
{
	public static function humanEn($small_ts, $large_ts = false)
    {
		if (!ctype_digit($small_ts)) $small_ts = strtotime($small_ts);
		if(!$large_ts) $large_ts = time();
		$n = $large_ts - $small_ts;
		if($n <= 1) return 'less than 1 second ago';
		if($n < (60)) return $n . ' seconds ago';
		if($n < (60*60)) { $minutes = round($n/60); return 'about ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago'; }
		if($n < (60*60*16)) { $hours = round($n/(60*60)); return 'about ' . $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago'; }
		if($n < (time() - strtotime('yesterday'))) return 'yesterday';
		if($n < (60*60*24)) { $hours = round($n/(60*60)); return 'about ' . $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago'; }
		if($n < (60*60*24*6.5)) return 'about ' . round($n/(60*60*24)) . ' days ago';
		if($n < (time() - strtotime('last week'))) return 'last week';
		if(round($n/(60*60*24*7))  == 1) return 'about a week ago';
		if($n < (60*60*24*7*3.5)) return 'about ' . round($n/(60*60*24*7)) . ' weeks ago';
		if($n < (time() - strtotime('last month'))) return 'last month';
		if(round($n/(60*60*24*7*4))  == 1) return 'about a month ago';
		if($n < (60*60*24*7*4*11.5)) return 'about ' . round($n/(60*60*24*7*4)) . ' months ago';
		if($n < (time() - strtotime('last year'))) return 'last year';
		if(round($n/(60*60*24*7*52)) == 1) return 'about a year ago';
		if($n >= (60*60*24*7*4*12)) return 'about ' . round($n/(60*60*24*7*52)) . ' years ago'; 
		return false;
	}

	public static function humanCn($small_ts, $large_ts = false)
    {
		if (!ctype_digit($small_ts)) $small_ts = strtotime($small_ts);
		if(!$large_ts) $large_ts = time();
		$n = $large_ts - $small_ts;
		if($n <= 1) return '1秒前';
		if($n < (60)) return $n . '秒前';
		if($n < (60*60)) { $minutes = round($n/60); return  $minutes . '分钟前'; }
		if($n < (60*60*16)) { $hours = round($n/(60*60)); return $hours . '小时前'; }
		if($n < (time() - strtotime('yesterday'))) return '昨天';
		if($n < (60*60*24)) { $hours = round($n/(60*60)); return $hours . '小时前'; }
		if($n < (60*60*24*6.5)) return 'about ' . round($n/(60*60*24)) . '天前';
		if($n < (time() - strtotime('last week'))) return '上个星期';
		if(round($n/(60*60*24*7))  == 1) return '1星期前';
		if($n < (60*60*24*7*3.5)) return round($n/(60*60*24*7)) . '星期前';
		if($n < (time() - strtotime('last month'))) return '上个月';
		if(round($n/(60*60*24*7*4))  == 1) return '1个月前';
		if($n < (60*60*24*7*4*11.5)) return  round($n/(60*60*24*7*4)) . '个月前';
		if($n < (time() - strtotime('last year'))) return '去年';
		if(round($n/(60*60*24*7*52)) == 1) return '1年前';
//		if($n >= (60*60*24*7*4*12)) return 'about ' . round($n/(60*60*24*7*52)) . ' years ago'; 
		if($n >= (60*60*24*7*4*12)) return date($small_ts); 
		return false;
	}

	public static function thisYear($small_ts, $large_ts = false)
    {
		if (!ctype_digit($small_ts)) $small_ts = strtotime($small_ts);
		if(!$large_ts) $large_ts = time();
		$n = $large_ts - $small_ts;
		if($n <= 1) return '1秒前';
		if($n < (60)) return $n . '秒前';
		if($n < (60*60)) { $minutes = round($n/60); return  $minutes . '分钟前'; }
		if($n < (60*60*16)) { $hours = round($n/(60*60)); return $hours . '小时前'; }
		if($n < (time() - strtotime('yesterday'))) return '昨天';
		if($n < (time() - strtotime('-1 year'))) return date('m-d H:i', $small_ts);
		return date('Y-m-d H:i', $small_ts);
	}
}
