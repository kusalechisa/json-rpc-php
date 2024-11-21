<?php

class jsonRPCServer {

    /**
     * Handles a JSON-RPC request.
     *
     * @param object $object The object that contains the methods to be called.
     * @return bool True if the request was successfully handled, false otherwise.
     */
    public static function handle($object) {
        // Determine if the request is an AJAX JSON-RPC request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            if ($_SERVER['CONTENT_TYPE'] !== 'application/x-www-form-urlencoded; charset=UTF-8' &&
                $_SERVER['HTTP_ACCEPT'] !== 'application/json' &&
                $_SERVER['HTTP_X_REQUEST'] !== 'JSON') {
                return false;
            }
        } else { // Check if the request is made through a PHP client
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || 
                empty($_SERVER['CONTENT_TYPE']) || 
                stripos($_SERVER['CONTENT_TYPE'], 'application/json') === false) {
                return false;
            }
        }

        // Read the input data
        $request = json_decode(file_get_contents("php://input"), true);

        // Check if the JSON request is valid
        if (!is_array($request) || !isset($request['method']) || !isset($request['params']) || !isset($request['id'])) {
            return false;
        }

        // Execute the requested method
        try {
            if (method_exists($object, $request['method'])) {
                // Call the method with the provided parameters
                $result = call_user_func_array([$object, $request['method']], $request['params']);
                $response = [
                    'id' => $request['id'],
                    'result' => $result,
                    'error' => null,
                ];
            } else {
                $response = [
                    'id' => $request['id'],
                    'result' => null,
                    'error' => 'Unknown method or invalid parameters',
                ];
            }
        } catch (Exception $e) {
            $response = [
                'id' => $request['id'],
                'result' => null,
                'error' => $e->getMessage(),
            ];
        }

        // Output the response
        if (!empty($request['id'])) {
            header('Content-Type: application/json');
            echo json_encode($response);
        }

        // Indicate successful handling
        return true;
    }
}
?>
