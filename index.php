<html>
  <body>
    <?php	  
      $SDK = new stdClass();
	  $SDK->access_token = "fh89327ifhds9879823hjkf7"; // App access token
      $SDK->developer_secret = "e(8W>51mUT7473Q"; // Developer SDK secret key
      $SDK->optional_fields = array('oneClickPayment','billingAddress','cardHolder'); // Optional required fields: ['oneClickPayment','billingAddress','cardHolder']
      
      $SDK->callback = "http://bo1.lemon.com/sdk/v1/web/client/callback.php"; // Callback url. If using js_callback leave this in blank

      $SDK->screenMode = "modal"; //Screen Mode choose between: ['modal','fullscreen']
      $SDK->width = 320; //If screenMode is 'fullscreen' this property will do nothing
	  $SDK->height = 0;//If screenMode is 'fullscreen' this property will do nothing
      
	  $SDK->email = $_REQUEST["email"]; // User email

      require_once("LemonWebSDK.php");
      $LemonWebSDK = LemonWebSDK::getInstance();	              
	  $LemonWebSDK->show($SDK);
	  
    ?>
    <a onclick="showLemon();">Pay with Lemon</a>
  </body>
</html>