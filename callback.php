<?php
  require_once("LemonWebSDK.php");
  
  $access_token = "fh89327ifhds9879823hjkf7";
  $developer_secret = "e(8W>51mUT7473Q";
  $hash = $_REQUEST["callback"];
  
  // START OF PAYMENT CONFIG
  // If you setup a payment processor at Lemon Network, the information below will be required. 
  $tx_data = array(
  				   "currency"    => "usd", // Charge Currency ISO-3
  				   "amount"      => 400, // Enter here the amount to charge
				   "description" => "Charge for test@example.com" // A description for the transaction
				  );
  // END OF PAYMENT CONFIG
  
  
  $LemonWebSDK = LemonWebSDK::getInstance();
  $result = $LemonWebSDK->getCardData($access_token, $developer_secret, $hash, $tx_data);
  
  //HERE IS THE RETURNING DATA
  
  if(isset($result->success)){ // If you are using a payment processor pre-configured with Lemon Network.
  	if($result->success){
	  	echo "<h1>Payment done.</h1>"; // Payment done
		echo "<h3>Data retrieved from processor.</h3><br>".json_encode($result->data); // Transaction result and information
  	}else{
	  	echo "<h1>Error.</h1><h3>".$result->error."</h3>"; // Error while processing the payment
  	}
  }else{ // If you want to retrieve the card data and did not configured a payment processor with Lemon Network.
	  echo "<h1> Data retrieved from Lemon Network.</h1><br>";
	
	  if(isset($result->cn)){
	  	echo "Card Number : ".$result->cn."<br>";
	  }  
	  if(isset($result->ed)){
	  	echo "Expiry Date : ".$result->ed."<br>";
	  }
	  if(isset($result->sc)){
	  	echo "CVV : ".$result->sc."<br>";
	  }    
	  if(isset($result->ch)){
	  	echo "Card Holder : ".$result->ch."<br>";
	  }  
	  if(isset($result->ad)){
	  	echo "Address Line : ".$result->ad."<br>";
	  }
	  if(isset($result->z)){
	  	echo "ZIP Code : ".$result->z."<br>";
	  }
	  if(isset($result->ci)){
	  	echo "City : ".$result->ci."<br>";
	  }
	  if(isset($result->st)){
	  	echo "State : ".$result->st."<br>";
	  }
	  if(isset($result->cc)){
	  	echo "Country Code ISO-2 : ".$result->cc."<br>";
	  }
  }

  
?>