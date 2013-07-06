Lemon Web SDK
=============

The Lemon SDK adds one-click checkout to your mobile app. For more information, check out the [Lemon Developers](#http://lemon.com/developers) website.

+ [Lemon Web SDK overview](#lemon-web-sdk-overview)
+ [Integration](#integration)
+ [Flow description](#flow-description)

##Lemon Web SDK overview:

Use the Lemon Web SDK in your mobile application by including a library in your project, and trigger the Lemon Network checkout process by displaying the SDK view. The SDK will show a screen with a list of payment cards the user already used and an option to add a new card. If the user has no cards on our system the SDK will show a form to enter a payment card credentials. Once the process is completed, the SDK view will disappear and you will receive a callback with the payment card credentials based on the SDK configuration.

##Integration:

###Obtaining your personalized Access Token
Your credentials and other application-related parameters must be specified before presenting the view. Please sign up for the Lemon SDK Beta on the [Lemon Developers website](http://lemon.com/developers/your-account/) and we'll send your credentials for your sandbox.

###Configuring the SDK
Once you download the SDK, replace the credentials in index.php and callback.php with your own access token and developer secret. You may further configure the SDK view with the following options:

+ &lt;USER_EMAIL> is the application user's personal email
+ &lt;ONE_CLICK_PAYMENT> is a flag that indicates if your application will need or not the CVV credentials of the user's payment card. If is set to YES, then the one click payment process will be executed, and the payment card CVV will not be ask to the user. If is set to NO, every time the user selects or adds a payment card, the user will have to enter the CVV, and then the CVV will be returned to the application.
+ &lt;NEED_BILLINGADDRESS> is a flag that indicates if your application will need or not the billing address of the user's payment card.  If is set to YES, every time the user selects or adds a payment card, the billing information will be sent to your application. If the billing address of a selected card is empty, the user will be force to enter the information. If is set to NO, then the payment card billing address will not be ask to the user and not passed to your application
+ &lt;NEED_CARDHOLDER> is a flag that indicates if your application will need or not the cardholder of the user's payment card.  If is set to YES, every time the user selects or adds a payment card, the cardholder will be sent to your application. If the cardholder of a selected card is empty, the user will be force to enter the information. If is set to NO, then the payment card cardholder will not be ask to the user and not passed to your application


##Flow description:

1. A user wants to pay for a product in your web app. He clicks a button that opens the Lemon Wallet SDK to enter the payment card credentials.

2. To launch the SDK, the web app should configure it with the following parameters:
a. SDK AccessToken
b. User Email Address
c. Need CVV credentials (or not)
d. Need Billing Address (or not)
e. Need Cardholder Name (or not)

3. The SDK will either show (a) a list of payment cards the user already has used with the option to add a new card, or (b) a prompt to add a first card.

4. If necessary, the user enters additional information required by your app. 

5. Based on security rules and the relation between the app, the SDK and the user, the SDK will ask for Lemon Credentials and/or CVV information. 

6. Lemon sends the application the completed user payment card credentials.
