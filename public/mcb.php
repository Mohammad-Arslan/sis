<?PHP
// Ensure cURL is active on server.
echo 'Curl: ', function_exists('curl_version') ? 'Enabled' : 'Disabled';
echo "<br /> <br />" ;

// Generating Random Order Number
$orderId = rand() ;


$requestBody = '{
    "apiOperation": "CREATE_CHECKOUT_SESSION",
    "interaction": {
        "operation": "PURCHASE"
    },
    "order": {
        "id" : "'.$orderId.'",
        "currency" : "PKR"
    }
}' ;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://mcbpk.gateway.mastercard.com/api/rest/version/60/merchant/824410244809/session");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody) ;  //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;

$headers = [
    'Authorization: Basic '.base64_encode("merchant.824410244809:ffa59d1a8f9ba89d15c554654d427795"),
    'Content-Type: application/json',
    'Host: mcbpk.gateway.mastercard.com',
    'Referer: http://localhost:33/mcb/index.php', //Your referrer address
    'cache-control: no-cache',
    'Accept: application/json'
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$server_output = curl_exec($ch) ;
curl_close ($ch);


$json = json_decode($server_output, true) ;
echo "Response from cURL request: <br />" ;
$sessionId = $json['session']['id'] ;

// Response from Server --- Must be 'success' to proceed further.
print $server_output ;

echo "<br /> <br />" ;

echo "Session Id: ". $sessionId ;

echo "<br /> <br />" ;

echo "Order Id: ". $orderId ;

echo "<br /> <br />" ;

?>


<html>
    <head>
        <script src="https://mcbpk.gateway.mastercard.com/checkout/version/60/checkout.js"
                data-error="errorCallback"
                data-cancel="cancelCallback"
                data-complete="completeCallback"
                data-beforeRedirect="getPageState"
                data-afterRedirect="restorePageState"
                data-timeout="timeoutCallback">
        </script>

        <script type="text/javascript">
            function errorCallback(error) {
                alert("errorCallback function") ;
                console.log(JSON.stringify(error));
                document.getElementById("state").value = "errorCallback" ;
            }

            function cancelCallback() {
                alert("cancelCallback function") ;
                console.log('Payment cancelled');
                document.getElementById("state").value = "cancelCallback" ;
            }

            function timeoutCallback() {
                alert("timeoutCallback function") ;
                console.log('Payment timedout');
                document.getElementById("state").value = "timeoutCallback" ;
            }

            function completeCallback(resultIndicator, sessionVersion) {

                alert("completeCallback function") ;
                document.getElementById("state").value = "completeCallback" ;
            }

            function getPageState() {

                alert("getPageState Callback: Leaving server.") ;
                
                return {
                    /* Fill other details */
                    orderId: document.getElementById("orderId").value,
                    amount: document.getElementById("amount").value,
                    // key01 : value
                    // key02 : value

                };
            }

            function restorePageState(data) {
                alert("restorePageState Callback function called after " + document.getElementById("state").value + " function")  ;
                alert("orderId: " + data.orderId + "\n amount: " + data.amount) ;
                // data.key01
                // data.key02
            }


            Checkout.configure({

                merchant: '824410244809',

                session: {
                    id: function() {
                        return document.getElementById("sessionId").value;
                    }
                },

                order: {
                    amount: function() {
                        return document.getElementById("amount").value;
                    },
                    currency: 'PKR',
                    description: 'Ordered goods',
                   id: function() {
                        return document.getElementById("orderId").value;
                    }
                },

                interaction: {
                    operation: 'PURCHASE', // set this field to 'PURCHASE' for <<checkout>> to perform a Pay Operation.
                    merchant: {
                        name: 'Test',
                        address: {
                            line1: '200 Sample St',
                            line2: '1234 Example Town'            
                        }    
                    }
                }
            });
        </script>
    </head>
    <body>

        <input type="hidden" name="state" id="state" value="">
        <input type="hidden" id="orderId" name="orderId" value="<?php echo $orderId ; ?>">
        <input type="hidden" id="sessionId" name="sessionId" value="<?php echo $sessionId ; ?>">
        
        <label for="amount">Amount: </label>
        <input type="text" id="amount" name="amount" value="1.00">
        
        <br />
        <br />
        
        <input type="button" value="Pay with Lightbox" onclick="Checkout.showLightbox();" />
        
        <br />
        <br />
        
        <input type="button" value="Pay with Payment Page" onclick="Checkout.showPaymentPage();" />
    
        <!-- 
        4025 8104 5141 3905
        11 21
        566
        test
        -->
    </body>
</html>