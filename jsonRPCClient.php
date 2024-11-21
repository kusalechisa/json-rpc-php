<?php
class jsonRPCClient {
    /**
     * Debug state
     * @var boolean
     */
    public $debug = false;

    /**
     * Server URL
     * @var string
     */
    private $uri;

    /**
     * The request ID
     * @var int
     */
    private $id;

    /**
     * If true, notifications are performed instead of requests
     * @var boolean
     */
    private $notification = false;

    /**
     * Constructor of the class
     * Takes the connection parameters
     *
     * @param string $uri
     * @param boolean $debug
     */
    public function __construct($uri, $debug = false) {
        $this->uri = $uri;
        $this->debug = $debug;
    }

    /**
     * Performs a JSON-RPC request and gets the results
     *
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $params) {
        // Validate method and parameters
        if (!is_scalar($method)) {
            throw new Exception("Method name has no scalar value.");
        }

        if (is_array($params)) {
            $params = array_values($params);
        } else {
            throw new Exception("Params must be given as array.");
        }

        $this->id = rand(0, 99999); 

        if ($this->notification) {
            $currentId = NULL; 
        } else {
            $currentId = $this->id;
        }

        // Prepare the request
        $request = array(
            'method' => $method,
            'params' => $params,
            'id' => $currentId
        );

        $request = json_encode($request);

        if ($this->debug) {
            $this->debug .= "\n".'**** Client Request ******'."\n".$request."\n".'**** End of Client Request *****'."\n";
        }

        // Perform the HTTP POST
        $opts = array('http' => array(
            'method' => 'POST',
            'header' => 'Content-type: application/json',
            'content' => $request
        ));

        $context = stream_context_create($opts);

        if ($fp = fopen($this->uri, 'r', false, $context)) {
            $response = '';

            while ($row = fgets($fp)) {
                $response .= trim($row)."\n"; 
            }

            if ($this->debug) {
                $this->debug .= '**** Server response ****'."\n".$response."\n".'**** End of server response *****'."\n\n";
            }

            $response = json_decode($response, true);

            // Check if response is valid before accessing its elements
            if (is_array($response) && isset($response['id'])) {
                if (!$this->notification) {
                    // Handle error in response if exists
                    if (isset($response['error']) && !is_null($response['error'])) {
                        throw new Exception('Request error: '. $response['error']);
                    }

                    if ($response['id'] != $currentId) {
                        throw new Exception('Incorrect response ID (request ID: '. $currentId . ', response ID: '. $response['id'].')');
                    }

                    // Return result if no error
                    return isset($response['result']) ? $response['result'] : null;
                }
            } else {
                // Handle invalid or null response
                throw new Exception('Invalid response format or missing expected data.');
            }
        } else {
            throw new Exception('Unable to connect to ' . $this->uri);
        }

        return true; // Fallback return if no valid response
    }

    /**
     * Sets the notification state
     *
     * @param boolean $notification
     * @return boolean
     */
    public function setRPCNotification($notification) {
        $this->notification = (bool) $notification;
        return true;
    }
}
?>
