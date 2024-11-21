<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Implementation of the JSON-RPC Protocol in PHP</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/yui/2.8.0r4/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/yui/2.7.0/base/base.css" type="text/css">
   <style>
       a { color: #393; }
   </style>
</head>
<body>
<div id="doc2" class="yui-t7">
   <div id="hd" role="banner">
       <h1>Implementation of the <a href="http://json-rpc.org/wiki/specification">JSON-RPC</a> Protocol in PHP</h1>
   </div>
   <div id="bd" role="main">
       <div id="widget">
           <!-- Start PHP Script -->
           <?php
           require_once 'jsonRPCClient.php';

           // Example JSON-RPC client usage
           $client = new jsonRPCClient('http://localhost/json-rpc-php/server.php');

           try {
               // Call the remote method and display the output
               echo $client->getTweets('codepo8', 15, true);
           } catch (Exception $e) {
               echo '<p style="color:red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
           }
           ?>
           <!-- End PHP Script -->
       </div>
   </div>

   <h2>Source</h2>
   <pre>
<?php
/* Include the client class */
require_once 'jsonRPCClient.php';

try {
    /* Create an object jsonRPCClient and connect to the server */
    $client = new jsonRPCClient('http://localhost/json-rpc-php/server.php');

    /* Call remote method 'getTweets' from the server */
    $tweets = $client->getTweets('codepo8', 15, true);

    /* Display the result */
    echo '<h2>Latest Tweets:</h2>';
    echo '<pre>' . htmlspecialchars($tweets) . '</pre>';

} catch (Exception $e) {
    /* Handle exceptions and display an error message */
    echo '<p style="color:red;">Error: Unable to fetch tweets. Please try again later.</p>';
    echo '<p>Details: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>

   </pre>

   <div id="ft" role="contentinfo">
       <p>Created by @<a href="http://twitter.com/kusa_lechisa">Kusa Lechisa</a> | Download on 
       <a href="https://github.com/kusalechisa/json-rpc-php">GitHub</a>
       
   </div>
</div>
</body>
</html>
