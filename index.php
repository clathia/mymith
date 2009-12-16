<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithkeys.php' 
 */
require_once 'mithkeys.php';

?>

<script src=
"http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" 
type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="global.css" />

<div>
<fb:comments xid="tp" numposts="8" title="My comment box" simple="1" css="http://apps.facebook.com/mafiainthehouse/global.css?2">
<fb:title>Who is accusing ?</fb:title>
</fb:comments>
</div>

<script type="text/javascript">
FB.init("10d020f9dcb70b3d5aeebc0124ddd387", "xd_receiver.htm");
</script>

<form action="invite.php" method="post">
<center>
<input type="submit" value="Start New MiTh">
</center>
</form>

