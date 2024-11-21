<?php
// Include the JSON-RPC server class
require_once 'jsonRPCServer.php';

// Include the Math class definition
require_once 'math.php';

// Instantiate the Math class
$obj = new Math();

try {
    // Handle the JSON-RPC request
    if (!jsonRPCServer::handle($obj)) {
        // If there is no valid JSON-RPC request, print a message
        echo 'No valid JSON-RPC request received.';
    }
} catch (Exception $e) {
    // Handle any exceptions that occur during request handling
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}
?>
