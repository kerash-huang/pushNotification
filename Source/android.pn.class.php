/*
Community Cloud System Class
 
  @filename : android.pn.class.php
  @author   : kerash
  @date     : 2013.06.26
 
Android Push Notification Service code
[Push notification through GCM in php]
*/
Class AndroidPNs
{
  /*
    Set the api key for using push service.
    Note that if you send by "cUrl" , you should ask a "Browser Key"!
    $apiKey = "";
  */
  var $GOOGLE_API_KEY = "";
 
  // Google Cloud Messaging Service path for push notification 
  var $GOOGLE_CLOUD_MESSAGING = "https://android.googleapis.com/gcm/send";
 
  // content type for your data format
  var $contentType = "application/json";
 
  function __Construct ( $apiKey )
  {
        $this-&gt;GOOGLE_API_KEY = $apiKey;
  }
 
  /* the main function to send message */
  function pushNotification($devices, $msg, $ntfyType = "")
  {
      $device_id  = $devices;
      $message    = $msg;
      $notifyType = $ntfyType;
 
      /* 
         there are example data.
         if un note this section , you can use some fake data for testign 
         $device_id  = array("50");
         $message    = "test";
         $notifyType = "packages";
      */
      if(!is_array($device_id)) { $device_id = array($device_id); }
 
      /* if data is null , then return false */
      if( count($device_id) &lt;= 0 or trim($message) == "") { return false; }
       $post_fields = array(
            "registration_ids"=&gt;$device_id,
            "data" =&gt; array("dataType"=&gt;$notifyType,"message"=&gt;$message)
       );
 
      /* initial the curl object */
      $curl = curl_init();
      curl_setopt($curl , CURLOPT_URL , $this-&gt;GOOGLE_CLOUD_MESSAGING);
      curl_setopt($curl , CURLOPT_POST , true );
      curl_setopt($curl , CURLOPT_RETURNTRANSFER , true );
      curl_setopt($curl , CURLOPT_SSL_VERIFYPEER , false );
      curl_setopt($curl , CURLOPT_HTTPHEADER  , 
            array( "Content-Type: ".$this-&gt;contentType ,
                "Authorization: key=".$this-&gt;GOOGLE_API_KEY)
        );
      curl_setopt($curl , CURLOPT_POSTFIELDS , json_encode( $post_fields) );
      $pushResult = curl_exec( $curl );
      if($pushResult)
      {
        $pushResultArray = json_decode($pushResult,true);
 
        /* check if notify send success */
        if($pushResultArray["success"]==0)
        {
            return false;
        }
        else
        {
            return true;
        }
 
      }
      else
      {
        return false;
      }
 
  }
}
