
<TABLE id="Table4" cellSpacing="0" cellPadding="0" width="100%" border="0">
									<TR>
										<TD ><h1 align  = center>Ajax News</h1></TD>
									</TR>
                                   	<TR>
										<TD>

<?php
mysql_connect($host,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$query 	="select * from news order by publishdate desc";
$result=mysql_query($query);
$num=mysql_numrows($result);
$i =0 ;
$num  = ($num >= $Max) ? $Max : $num;
while($num > $i )
{
    $itemid = mysql_result($result,$i,"itemid");
	$title = mysql_result($result,$i,"title");
	$publishdate = mysql_result($result,$i,"publishdate");
	$shortdescription = mysql_result($result,$i,"shortdescription");
//	$longdescription= mysql_result($result,$i,"longdescription");

?>
								

<table width="100%" border="0" cellspacing="0" cellpadding="2">
                
                <tr>
                  <td class="Tah11BoldB55610"><a href="newsdetials.php?itemid=<?=$itemid?>" class ='Tah11BoldB55610' ><?=$title?></a></td>
                </tr>
                <tr>
                  <td class="Tah11Normal5E5E5E"><?=$shortdescription?></td>
                </tr>
              </table>
               <?php
$i++;
}
 mysql_close();
 ?>
											</ul>
										</TD>
									</TR>
								</TABLE>
