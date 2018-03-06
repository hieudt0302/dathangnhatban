<?php

  
    function price_format($price,$currency) {
        if($price==''){
            $price=0;
        }
        else{
            $price = number_format(intval($price), 0, ',', '.');
        }
        return $price.' '.$currency;
    }

    function url_format($url=''){
	$orginal_character = array(
                                '/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/',
                                '/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/',
                                '/(ì|í|ị|ỉ|ĩ)/',
                                '/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/',
                                '/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/',
                                '/(ỳ|ý|ỵ|ỷ|ỹ)/',
                                '/(đ)/',
                                '/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|A)/',
                                '/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|E)/',
                                '/(Ì|Í|Ị|Ỉ|Ĩ|I)/',
                                '/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|O)/',
                                '/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|U)/',
                                '/(Ỳ|Ý|Ỵ|Ỷ|Ỹ|Y)/',
                                '/(Đ|D)/',
                                '/(B)/',
                                '/(C)/',
                                '/(F)/',
                                '/(G)/',
                                '/(H)/',
                                '/(J)/',
                                '/(K)/',
                                '/(L)/',
                                '/(M)/',
                                '/(N)/',
                                '/(P)/',
                                '/(Q)/',
                                '/(R)/',
                                '/(S)/',
                                '/(T)/',
                                '/(V)/',
                                '/(W)/',
                                '/(X)/',
                                '/(Z)/',
                                '/[^a-zA-Z0-9]/',
                                '/[\_|\-|\.|\/|\?|&|\+|\\\|\'|"|,]+/',
                            );
	$replace_character = array(
                                'a',
                                'e',
                                'i',
                                'o',
                                'u',
                                'y',
                                'd',
                                'a',
                                'e',
                                'i',
                                'o',
                                'u',
                                'y',
                                'd',
                                'b',
                                'c',
                                'f',
                                'g',
                                'h',
                                'j',
                                'k',
                                'l',
                                'm',
                                'n',
                                'p',
                                'q',
                                'r',
                                's',
                                't',
                                'v',
                                'w',
                                'x',
                                'z',
                                '-',
                                '-',
                            );
	$uri =urlencode(preg_replace($orginal_character, $replace_character, trim($url)));
	return $uri;			
    }
    
    function get_timezones()
    {
        $timezoneIdentifiers = DateTimeZone::listIdentifiers();
        $utcTime = new DateTime('now', new DateTimeZone('UTC'));
        $tempTimezones = array();
        foreach ($timezoneIdentifiers as $timezoneIdentifier) {
            $currentTimezone = new DateTimeZone($timezoneIdentifier);
            $tempTimezones[] = array(
                                    'offset' => (int)$currentTimezone->getOffset($utcTime),
                                    'identifier' => $timezoneIdentifier
                                );
        }
        $timezoneList = array();
        foreach ($tempTimezones as $tz) {
            $sign = ($tz['offset'] > 0) ? '+' : '-';
            $offset = gmdate('H:i', abs($tz['offset']));
            $timezoneList[$tz['identifier']] = 'UTC ' . $sign . $offset . ' - ' .
            $tz['identifier'];
        }
        return $timezoneList;
    }
    
    function truncate($str, $length = 100) {
        $str = $str." ";
        $str = substr($str,0,$length);
        $str = substr($str,0,strrpos($str,' '));
        $str = $str."...";
        return $str;
    }

    function load_page_time(){
        $start = explode(' ', microtime())[0] + explode(' ', microtime())[1];
        $time = round((explode(' ', microtime())[0] + explode(' ', microtime())[1]) - $start, 4); 
        return 100;
    }

