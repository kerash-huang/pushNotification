<?php
/**
  @Name      Google Cloud Message Service Sender 2.0
  @Version   2.0
  @Author    kerash
  @Date      2017/03/28

  Android Push Notification Service code
  [Push notification through GCM in php]
*/
class GCMService
{
  /*
    Set the api key for using push service.
    Note that if you send by "cUrl" , you should ask a "Browser Key"!
    $apiKey = "";
  */
  protected $GOOGLE_API_KEY = "";

  protected $GOOGLE_CLOUD_MESSAGING_SERVER = "https://fcm.googleapis.com/fcm/send";
  // content type for your data format
  private $contentType = "application/json";


  private $device_id_list = array();

  /*
    message body param
   */
  private $notify_content_title;
  private $notify_content_text;
  private $action = array('click_action'=>'');


  function __construct ( $apiKey )
  {
        $this->GOOGLE_API_KEY = $apiKey;
  }

  /**
   * set pop notification title
   * @param [type] $title [description]
   */
  public function setContentTitle($title) {
    $this->notify_content_title = $title;
  }

  /**
   * set pop notification content text
   * @param [type] $title [description]
   */
  public function setContentText($msg) {
    $this->notify_content_text = $msg;
  }

  /**
   * set the response action where user do something
   * @param string $action
   * @param mixed $param
   */
  public function setAction($action, $param) {
    if($action != '') {
      $this->action[$action."_action"] = $param;
    }
  }

  public function addDevice($device_id) {
    if(!in_array($device_id, $this->device_id_list)) {
      array_push($this->device_id_list, $device_id);
    }
    return true;
  }


  /**
   * Send notify message
   * @return bool send result
   */
  public function makeNotify()
  {
    var_dump($this->GOOGLE_API_KEY);
      if( count($this->device_id_list) <= 0 or trim($this->notify_content_text) === "") {
        return false;
      }

       $post_fields = array(
            "registration_ids"=>$this->device_id_list
       );
       /**
        * code you own data for app
        */
       $post_fields["notification"]["title"] = $this->notify_content_title;
       $post_fields["notification"]["body"] = $this->notify_content_text;
       $post_fields["notification"]["icon"] = "appicon";

      /* initial the curl object */
      $curl = curl_init();
      curl_setopt($curl , CURLOPT_URL , $this->GOOGLE_CLOUD_MESSAGING_SERVER);
      curl_setopt($curl , CURLOPT_POST , true );
      curl_setopt($curl , CURLOPT_RETURNTRANSFER , true );
      curl_setopt($curl , CURLOPT_SSL_VERIFYPEER , false );
      curl_setopt($curl , CURLOPT_HTTPHEADER  ,
            array( "Content-Type: " . $this->contentType ,
                "Authorization: key=" . $this->GOOGLE_API_KEY)
        );
      curl_setopt($curl , CURLOPT_POSTFIELDS , json_encode( $post_fields) );

      $pushResult = curl_exec( $curl );
      var_dump($post_fields, $pushResult);
      die();
      if($pushResult)
      {
        $pushResultArray = json_decode($pushResult,true);
        /* check if notify send success */
        if($pushResultArray["success"]==0) {
            return false;
        } else {
            return true;
        }
      } else {
        return false;
      }

  }
}
