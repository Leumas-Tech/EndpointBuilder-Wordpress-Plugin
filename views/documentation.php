<div class="wrap">
    <h1>Endpoint Builder Documentation</h1>
    <div id="documentation-tabs">
        <ul>
            <li><a href="#tab-static">Static</a></li>
            <li><a href="#tab-php">PHP</a></li>
            <li><a href="#tab-blocks">Blocks</a></li>
            <li><a href="#tab-webhooks">Webhooks</a></li>
        </ul>
        <div id="tab-static">
            <h2>Static Response</h2>
            <p>A static response is a simple way to return a predefined JSON response from an endpoint. Here is how to structure a static block:</p>
            <pre>
{
    "namespace": "demo/v1",
    "route": "/hello-static",
    "methods": "GET",
    "responseType": "static",
    "response": "{ \"message\": \"Hello, dynamic world!\" }"
}
            </pre>
            <p>Each component explained:</p>
            <ul>
                <li><strong>Namespace</strong>: The namespace for your API route. It helps in grouping your endpoints.</li>
                <li><strong>Route</strong>: The specific endpoint URL.</li>
                <li><strong>Methods</strong>: The HTTP methods supported by this endpoint (e.g., GET, POST).</li>
                <li><strong>ResponseType</strong>: Set to "static" to indicate a static response.</li>
                <li><strong>Response</strong>: The JSON response to return.</li>
            </ul>
        </div>
        <div id="tab-php">
            <h2>PHP Response</h2>
            <p>A PHP response allows for dynamic content generation using PHP code. Here are some examples:</p>
            <h3>Example 1: Simple JSON Response</h3>
            <pre>
{
    "namespace": "demo/v1",
    "route": "/hello-dynamic",
    "methods": "GET",
    "responseType": "php",
    "response": "&lt;?php echo json_encode(array(\"message\" => \"Hello, dynamic world!\")); ?&gt;"
}
            </pre>
            <h3>Example 2: Taking User Input and Returning a Response</h3>
            <pre>
{
    "namespace": "demo/v1",
    "route": "/greet-user",
    "methods": "POST",
    "responseType": "php",
    "response": "&lt;?php $input = json_decode(file_get_contents('php://input'), true); echo json_encode(array(\"message\" => \"Hello, \" . $input['name'] . \"!\")); ?&gt;"
}
            </pre>
            <p>To send a request to this endpoint, use the following curl command:</p>
            <pre>
curl -X POST https://yourdomain.com/wp-json/demo/v1/greet-user -d '{"name": "John"}' -H "Content-Type: application/json"
            </pre>
            <p>Each component explained:</p>
            <ul>
                <li><strong>Namespace</strong>: The namespace for your API route. It helps in grouping your endpoints.</li>
                <li><strong>Route</strong>: The specific endpoint URL.</li>
                <li><strong>Methods</strong>: The HTTP methods supported by this endpoint (e.g., GET, POST).</li>
                <li><strong>ResponseType</strong>: Set to "php" to indicate a PHP response.</li>
                <li><strong>Response</strong>: The PHP code to execute. Note that PHP code must be wrapped in <code>&lt;?php ?&gt;</code> tags.</li>
            </ul>
        </div>
        <div id="tab-blocks">
            <h2>Blocks</h2>
            <p>Each block in the Endpoint Builder consists of the following parts:</p>
            <ul>
                <li><strong>Namespace</strong>: The namespace for your API route. It helps in grouping your endpoints.</li>
                <li><strong>Route</strong>: The specific endpoint URL.</li>
                <li><strong>Methods</strong>: The HTTP methods supported by this endpoint (e.g., GET, POST).</li>
                <li><strong>ResponseType</strong>: The type of response. It can be either "static" or "php".</li>
                <li><strong>Response</strong>: The response to return. For static responses, this is a JSON string. For PHP responses, this is the PHP code to execute.</li>
            </ul>
            <p>Here is a smart way to structure your endpoints:</p>
            <h3>Example: Fetching Data from an External API</h3>
            <pre>
{
    "namespace": "demo/v1",
    "route": "/external-data",
    "methods": "GET",
    "responseType": "php",
    "response": "&lt;?php
        $response = wp_remote_get('https://api.example.com/data');
        if (is_wp_error($response)) {
            echo json_encode(array('error' => 'Unable to fetch data'));
        } else {
            echo wp_remote_retrieve_body($response);
        }
    ?&gt;"
}
            </pre>
            <p>This example fetches data from an external API and returns it. If the request fails, it returns an error message.</p>
        </div>
        <div id="tab-webhooks">
            <h2>Webhooks</h2>
            <p>Webhooks allow you to send data to a specified URL when an event occurs. Here is how to structure a webhook block:</p>
            <pre>
{
    "url": "https://yourwebhookurl.com/endpoint",
    "method": "POST",
    "payload": "{ \"message\": \"Hello, webhook!\" }"
}
            </pre>
            <p>Each component explained:</p>
            <ul>
                <li><strong>URL</strong>: The URL to send the webhook data to.</li>
                <li><strong>Method</strong>: The HTTP method to use (e.g., POST, GET).</li>
                <li><strong>Payload</strong>: The data to send in the request body (for POST requests).</li>
            </ul>
            <h3>Testing Webhooks</h3>
            <p>You can test your webhooks using <code>curl</code> from the command line or with Postman.</p>
            <h4>Using curl</h4>
            <p>To send a test webhook using <code>curl</code>, use the following command:</p>
            <pre>
curl -X POST https://yourwebhookurl.com/endpoint -d '{ "message": "Hello, webhook!" }' -H "Content-Type: application/json"
            </pre>
            <h4>Using Postman</h4>
            <p>To send a test webhook using Postman, follow these steps:</p>
            <ul>
                <li>Open Postman and create a new request.</li>
                <li>Set the request method to POST.</li>
                <li>Enter the URL <code>https://yourwebhookurl.com/endpoint</code>.</li>
                <li>Go to the "Body" tab and select "raw" and "JSON" as the data format.</li>
                <li>Enter the payload JSON: <pre>{ "message": "Hello, webhook!" }</pre></li>
                <li>Click "Send" to send the request and view the response.</li>
            </ul>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $("#documentation-tabs").tabs();
    });
</script>

<style>
    #documentation-tabs {
        width: 100%;
    }
    #documentation-tabs ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    #documentation-tabs ul li {
        display: inline;
        margin-right: 10px;
    }
    #documentation-tabs ul li a {
        text-decoration: none;
        padding: 10px;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    #documentation-tabs ul li a:hover {
        background-color: #e1e1e1;
    }
    #documentation-tabs .ui-tabs-active a {
        background-color: #e1e1e1;
        border-bottom: none;
    }
    #documentation-tabs div {
        border: 1px solid #ccc;
        padding: 10px;
        background-color: #fff;
        border-radius: 5px;
        margin-top: -1px;
    }
</style>
