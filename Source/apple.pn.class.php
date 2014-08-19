/*
    Community Cloud System Class
 
    @filename : apple.pn.class.php
    @author   : kerash
    @date     : 2013.06.26
    Apple Push Notification Service code
    [Push notification through APNs in php]
 */
class ApplePNs
{
    // Apple Push Notification Server path for push notification 
    var $ApplePushNotificationServer = "ssl://gateway.push.apple.com:2195";
 
    // Apple .pem file name
    var $PEMfile = "";
    // .pem pass phrase
    var $PEMpassphrase = "";
 
    // notify sound
    var $aps_sound = "default";
    // notify badge
    var $aps_badge = "0";
 
    function __Construct($pem)
    {
        if(!empty($pem))
        {
            if(strrpos($pem,".pem")===FALSE) {
                $pem .= ".pem";
            }
            $this->PEMfile = $pem;
        }
    }
 
    function set_pem  ($pem)   { $this->PEMfile = $pem; }
    function set_pass ($passphrase) { $this->PEMpassphrase = $passphrase; }
    function set_sound($sound) {$this->aps_sound = $sound; }
    function set_badge($badge) {$this->aps_badge = $badge; }
 
    function pushNotification($devices , $msg , $ntfyType = "")
    {
 
        $device_id  = $devices;
        $message    = $msg;
        $notifyType = $ntfyType;
 
        /* if data is null , then return false */
        if( count($device_id) &lt;= 0 or trim($message) == "")
        {             return false;         }
 
         $ctx = stream_context_create();
         stream_context_set_option($ctx, 'ssl', 'local_cert', $this->PEMfile);
        if($this->PEMpassphrase!=""){
          stream_context_set_option($ctx, 'ssl', 'passphrase', $this->PEMpassphrase);
        }
 
        $fp = stream_socket_client( $this->ApplePushNotificationServer, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
 
        if (!$fp) { return false; }
 
        $aps_struct['aps'] = array(
            'alert' => $message,
            'badge' => $this->aps_badge,
            'sound' => $this->aps_sound
        );
 
        if(!empty($notifyType))
        {
              $aps_struct["dataType"] = $notifyType;
        }
        $payload = json_encode($aps_struct);
 
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $device_id) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
 
        // Close the connection to the server
        fclose($fp);
 
        if (!$result)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}
