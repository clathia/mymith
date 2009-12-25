<?php

/*
 * Processes a string to make it in sane form.
 */

function rteSafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = $strText;

	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	$tmpString = str_replace("'", "&#39;", $tmpString);

	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
	$tmpString = str_replace("<", "&lt;", $tmpString);
	$tmpString = str_replace(">", "&gt;", $tmpString);
   $tmpString = str_replace("&lt;br&gt;", "<br>", $tmpString);

	return $tmpString;
}

/*
 * Well it creates date.
 */

function create_date($format, $gmepoch, $tz)
{
	global $board_config, $lang;
	static $translate;

	if ( empty($translate) && $board_config['default_lang'] != 'english' )
	{
		@reset($lang['datetime']);
		while ( list($match, $replace) = @each($lang['datetime']) )
		{
			$translate[$match] = $replace;
		}
	}

	return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
}


function display_date($date)
{
    if(empty($date)) {
        return "No date provided";
    }
   
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
   
    $now             = time();
    $unix_date = strtotime($date);
   
       // check validity of date
    if(empty($unix_date)) {   
        return "Bad date";
    }

    // is it future date or past date
    if($now == $unix_date) {
        return "Just Posted";
    }
    
    if($now > $unix_date) {   
        $difference     = $now - $unix_date;
        $tense         = "ago";
       
    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }
   
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
   
    $difference = round($difference);
   
    if($difference != 1) {
        $periods[$j].= "s";
    }
   
    return "$difference $periods[$j] {$tense}";
}

function display_comment($bg_color, $border_color, $profile_url, $pic_square, $full_name, $publish_date, $comment_text)
{
   $date = display_date($publish_date);
   return <<<HTML
       <Table width = '90%'  cellspacing='0' bgcolor = '$bg_color' align = 'center'  style='border-top:$border_color 1px solid ;border-bottom:$border_color 1px solid ;'>
       <tr>
        <td width=55px valign="top"><a target = '_blank' href =$profile_url><img src=$pic_square /></a></td>
        <td style='text-align:left;'><a target = '_blank' href =$profile_url>$full_name</a><br />
        <font size="1">$date</font><br />
        $comment_text
        </td>
    </tr>
</Table>
HTML;

}


?>