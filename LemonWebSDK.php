<?php

  class LemonWebSDK
  {

    private static $_instance;

    public static function getInstance()
    {
      if (!isset(self::$_instance))
      {
        self::$_instance = new self();
      }
      return self::$_instance;
    }

    private function encrypt($string, $key)
    {
      if (!empty($string))
      {
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, 'ecb'));
        return $encrypted;
      }
      else
      {
        return '';
      }
    }

    private function getWalletUrl($access_token, $developer_secret, $email, $callback, $optional_fields)
    {
      $obj = new stdClass();
      $obj->at = $access_token;
      $pkg = new stdClass();
      $pkg->is = $access_token."|".$email;
      $pkg->sv = "1.0.0";
      $pkg->p = "Web";
      $pkg->mc = time();
      $pkg->e = $email;
      $pkg->cb = $callback;
	  $pkg->ask = array();
	  foreach($optional_fields as $opt){
	  	switch($opt){
			case "billingAddress":
				$pkg->ask[] = "zipCode";
				$pkg->ask[] = "addressLine";
			break;
			case "oneClickPayment":
				$pkg->ask[] = "-CVV";
			break;
			case "cardHolder":
				$pkg->ask[] = "name";
			break;
	  	}
	  }
      $obj->pkg = $this->encrypt(json_encode($pkg), $developer_secret);
      $url = "https://bo1.lemon.com/sdk/v1/web/?act=gw&msg=".urlencode(json_encode($obj));
      return $url;
    }

    public function getCardData($access_token, $developer_secret, $hash, $tx_data)
    {
      $obj = new stdClass();
      $obj->at = $access_token;
      $obj->h = $this->encrypt($hash, $developer_secret);
	  $obj->tx = $tx_data;
      $url = "https://bo1.lemon.com/sdk/v1/ws/?act=gc&msg=".urlencode(json_encode($obj));
	  
	  $ch = curl_init(); 
	  curl_setopt($ch, CURLOPT_URL, $url); 
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	  $output = curl_exec($ch); 
	  curl_close($ch);  
	  
	  $result = json_decode($output);
	  return $result;
    }
	
	public function show($SDK){
		$url = $this->getWalletUrl($SDK->access_token, $SDK->developer_secret, $SDK->email, $SDK->callback, $SDK->optional_fields);
		if(isset($SDK->screenMode) && $SDK->screenMode=="fullscreen"){
			echo '<script>
					function showLemon(){
						window.location.href="'.$url.'";
					}
			  	  </script>';
		}else{
			if(empty($SDK->width)){
				$width="320px";
			}else{
				$width=$SDK->width."px";
			}
			if(empty($SDK->height)){
				$height="400px";
			}else{
				$height=$SDK->height."px";
			}
			$html = '<div id="lemon-loader" style="display:none; border-radius:0px !important; position: fixed; z-index: 100000;background: rgba(49,54,62,1) url(https://bo1.lemon.com/sdk/v1/web/img/ajax-loader-darkbg.gif) no-repeat center center;color: #fff; opacity:0.7;"></div><div id="lsdk"></div>
			<script>
			function showLemon(){
				if(screen.width>685 || screen.height>685){
					jQuery("#lemon-wrapper").show();
					overlay();
				}else{
					window.location.href="'.$url.'";
				}
			}
			function hideLemon(){
				jQuery("#lemon-wrapper").hide();
			}
			function overlay(){
				var pos = jQuery("#lemon-sdk").position();
				jQuery("#lemon-loader").css("top", pos.top+" px");
				jQuery("#lemon-loader").css("left", pos.left+" px");
			    jQuery("#lemon-loader").css("width", jQuery("#lemon-sdk").width());
			    jQuery("#lemon-loader").css("height", jQuery("#lemon-sdk").height());
			}
			function setAllLemonJS(){
			  
			  var iframeEl = \'<iframe id="lemon-sdk" src="'.($url).'" style="background: #222222; border-radius:0px !important; border:none; margin:0; padding:0; z-index:999999; min-width: 320px; min-height: 400px; width: '.$width.'; height: '.$height.';"></iframe>\';
			  
			  
			  if(screen.width>685 || screen.height>685){
				  jQuery("#lsdk").replaceWith(iframeEl);		
			  }
			  
			  var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
			  var eventer = window[eventMethod];
			  var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
			  
			  eventer(messageEvent,function(e) {
			    if(e.data=="loading"){
			    	jQuery("#lemon-loader").show();
			    }else if(e.data=="ready"){
			    	jQuery("#lemon-loader").hide();
			    }else{
			    	hideLemon();
			    	'.(!empty($SDK->js_callback) ? "callbackFN(e.data);" : '').'
			    }
			  },false);
			  jQuery(window).resize(function(){
				overlay();
			  });
			  jQuery().load(function(){
			  	jQuery("#lemon-sdk").load(function(){
			  		jQuery("#lemon-loader").hide();
			  	});   	
			    jQuery(window).resize();
			  });
			  ';         
	   		  $html .= "
	          }
	          
				if (typeof jQuery == 'undefined') {
					
					function getScript(url, success) {
					
						var script     = document.createElement('script');
						     script.src = url;
						
						var head = document.getElementsByTagName('head')[0],
						done = false;
						
						// Attach handlers for all browsers
						script.onload = script.onreadystatechange = function() {
						
							if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
							
							done = true;
								
								// callback function provided as param
								success();
								
								script.onload = script.onreadystatechange = null;
								head.removeChild(script);
								
							};
						
						};
						
						head.appendChild(script);
					
					};
					
					getScript('https://framework.lemon.com/js/jquery-1.8.3.min.js', function() {
						setAllLemonJS();				
					});
					
				}else{
					setAllLemonJS();
				}
				
			</script>";
			echo '<link rel="stylesheet" href="https://bo1.lemon.com/sdk/v1/web/css/lemonwallet-modal.css" />
						<div id="lemon-wrapper" class="lemonwallet-modal-background" style="display:none;" onclick="hideLemon();">
					<div class="lemonwallet-modal-wrapper" style="width:'.$width.'; height:'.$height.';">
							'.$html.'
					</div>
				</div>
			';
		}
	}

  }
?>