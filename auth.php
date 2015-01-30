<?php
  global $user;
  if($user->uid >0)
    {   
        $uid=$user->uid;
        $result=db_select('users','u')
         ->condition('uid',$uid,'=')
         ->fields('u',array('picture'))
         ->execute();
    while($data=$result->fetchAssoc()) 
     {
      $picid=$data['picture'];//id of user image saved in drupal
      
     } 
  
 
    $result=db_select('file_managed','fm')
            ->condition('fid',$picid,'=')
           ->fields('fm',array('filename'))
           ->execute();
    while($data=$result->fetchAssoc()) 
     {
       $image=$data['filename'];//url of the user image
     }   
       //chat_master is the table created by chat during the installation time
     if (db_table_exists('chat_master')) {
         $uid=$user->uid;
        $result=db_select('chat_master','chat')
         ->fields('chat',array('chatkey'))
         ->execute();
    while($data=$result->fetchAssoc()) 
     {
       @$chatkey=$data['chatkey'];
      
     } 
     } 
     
    }
    function my_curl_request($url, $postdata){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 'content-type: text/plain;');
    curl_setopt($ch, CURLOPT_TRANSFERTEXT, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXY, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, 1);

    $result = @curl_exec($ch);
    if ($result === false) 
    {
        echo 'Curl error: ' . curl_error($ch);
        exit;
    }
    curl_close($ch);

    return $result;
}
    
    $authusername = substr(str_shuffle(md5(microtime())), 0, 12);
    $authpassword = substr(str_shuffle(md5(microtime())), 0, 12);
    $licen = '';
    //'100-2c1cbdb38a0f9d76c981d7'
    $postdata = array('authuser' => $authusername, 'authpass' => $authpassword, 'licensekey' =>@$chatkey );
    $postdata = json_encode($postdata);
    $rid = my_curl_request("https://c.vidya.io", $postdata); // REMOVE HTTP.
    if (empty($rid) or strlen($rid) > 32)
        {
        echo "Chat server is unavailable!";
        exit;
        }
    setcookie('auth_user', $authusername, 0, '/');
    setcookie('auth_pass', $authpassword, 0, '/');
    setcookie('path', $rid, 0, '/');

    if(isset($image))
    {
     $imageurl='sites/default/files//styles/thumbnail/public/pictures/'.$image ;
     }
    else 
    {
     $imageurl='sites/all/modules/chat/images/'.'quality-support.png' ;
     }
        @$my_settings = array(
        'path'=> $_COOKIE['path'],
        'auth_user'=> $_COOKIE['auth_user'],
        'auth_pass'=> $_COOKIE['auth_pass'],
        'imageurl'=> $imageurl
      );
    drupal_add_js(array('chat' => $my_settings), 'setting'); 
    ob_end_flush();
?>
