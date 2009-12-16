<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'invite.php' 
 */
require_once 'mithkeys.php';

define( 'FB_API_KEY', $appapikey );
define( 'FB_SECRET', $appsecret ); 
define( 'FB_APPID', $appid ); 
define( 'FB_CANVAS_URL', 'http://apps.facebook.com/mafiainthehouse/' ); 
define( 'RETURN_URL', 'http://apps.facebook.com/mafiainthehouse/group.php' );

function SendNewRequest() {
    global $facebook;
    $content = '<fb:req-choice url=\''.RETURN_URL.'\' label=\'Accept\' />';
    $action = 'http://apps.facebook.com/mafiainthehouse/inviteSelection.php';
    $actionText = 'Create your group for MiTH!';
    
    $excludeFriends = null; 
    $excludeFriends = $facebook->api_client->friends_get();
    $excludeFriendsStr = ""; 
    /*
    for ($i = 0; $i < count($excludeFriends); $i++){
        $q = mysql_query("SELECT fb_id FROM user_table WHERE fb_id='{$excludeFriends[$i]}'");
        if (mysql_num_rows($q) > 0){
            $excludeFriendsStr .= $excludeFriends[$i].",";
        }
    }
    */
    $params = array();
    
    $params['api_key'] = FB_API_KEY; 
    $params['content'] = $content; 
    $params['type'] = "MiTH"; 
    $params['action'] = $action; 
    $params['actiontext'] = $actionText; 
    $params['invite'] = 'true';
    $params['rows'] = '5';
    $params['max'] = '20'; 
    $params['exclude_ids'] = $excludeFriendsStr; 
    $params['sig'] = $facebook->generate_sig($params, FB_SECRET); 
    $qstring = null; 
    foreach ($params as $key => $value) { 
        if ($qstring) $qstring .= '&'; 
        $qstring .= "$key=".urlencode($value); 
        } 
        $inviteUrl = 'http://www.facebook.com/multi_friend_selector.php?'; 
        $facebook->redirect($inviteUrl . $qstring); 
        return true; 
}
SendNewRequest();
?>
