
<?php require("includes/commentsconfig.php");

if($_GET )
{

$PublishDate = time();$bgcolor = $_GET["BgColor"];
$borderColor= $_GET["BorderColor"];
$Website = $_GET["Website"];
$FullName = $_GET["FullName"];
$Email = $_GET["Email"];
$CommentText = rteSafe($_GET["CommentText"]);
$ForeignID = 1;//$_GET["ForeignID"] ;
$query = "Insert Into comments(FullName,Email,Website, CommentText, ForeignID, PublishDate)
			 values('$FullName', '$Email', '$Website', '$CommentText','$ForeignID','$PublishDate')";

mysql_connect($host,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
mysql_query($query);
mysql_close();
$PublishDate= create_date('D M d, Y g:i a', $PublishDate, 0);
}
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

?>

<Table width = '90%'  cellspacing='0' bgcolor = '#<?=$bgcolor?>' align = 'center' style='border-top:#<?=$borderColor?> 1px solid ;border-bottom:#<?=$borderColor?> 1px solid ;'>
    <tr>
        <td style='text-align:left;font-weight:bold'><a target = '_blank' href = '<?=$Website?>'><?=$FullName?></a> Says:</td>
    </tr>
    <tr>
        <td style='text-align:left;'><?=$PublishDate?></td>
    </tr>
    <tr>
        <td style='text-align:left;'><?=$CommentText?></td>
    </tr>

</Table>
<?php
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
?>
