<?php
/*
Plugin Name: Endpoint Builder
Description: A simple plugin to build and manage custom REST API routes and webhooks.
Version: 1.5
Author: William Bermudez (Leumas Tech)
*/

// Hook to add admin menu
add_action('admin_menu', 'eb_add_admin_menu');

function eb_add_admin_menu() {
    add_menu_page('Endpoint Builder', 'Endpoint Builder', 'manage_options', 'endpoint-builder', 'eb_route_builder_page');
    add_submenu_page('endpoint-builder', 'Routes List', 'Routes List', 'manage_options', 'endpoint-builder-routes', 'eb_route_list_page');
    add_submenu_page('endpoint-builder', 'Documentation', 'Documentation', 'manage_options', 'endpoint-builder-documentation', 'eb_documentation_page');
    add_submenu_page('endpoint-builder', 'Webhook Builder', 'Webhook Builder', 'manage_options', 'webhook-builder', 'eb_webhook_builder_page');
}

// Enqueue admin scripts and styles
add_action('admin_enqueue_scripts', 'eb_admin_enqueue_scripts');

function eb_admin_enqueue_scripts($hook) {
    if (in_array($hook, array('toplevel_page_endpoint-builder', 'endpoint-builder_page_endpoint-builder-routes', 'endpoint-builder_page_endpoint-builder-documentation', 'endpoint-builder_page_webhook-builder'))) {
        wp_enqueue_script('eb-admin-script', plugin_dir_url(__FILE__) . 'admin-page.js', array('jquery', 'jquery-ui-tabs'), '1.0', true);
        wp_enqueue_style('eb-admin-style', plugin_dir_url(__FILE__) . 'css/admin-style.css', array(), '1.0');
        wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        wp_localize_script('eb-admin-script', 'eb_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
    }
}

// Route builder page content
function eb_route_builder_page() {
    require_once plugin_dir_path(__FILE__) . 'views/route-builder.php';
}

// Route list page content
function eb_route_list_page() {
    require_once plugin_dir_path(__FILE__) . 'views/route-list.php';
}

// Documentation page content
function eb_documentation_page() {
    require_once plugin_dir_path(__FILE__) . 'views/documentation.php';
}

// Webhook builder page content
function eb_webhook_builder_page() {
    require_once plugin_dir_path(__FILE__) . 'views/webhook-builder.php';
}

// Register dynamic routes
add_action('rest_api_init', 'eb_register_dynamic_routes');

function eb_register_dynamic_routes() {
    $routes = get_option('eb_routes', array());

    // Add demo routes if they don't exist
    if (empty($routes)) {
        $routes = array(
            array(
                'namespace' => 'demo/v1',
                'route' => '/hello-static',
                'methods' => 'GET',
                'responseType' => 'static',
                'response' => json_encode(array('message' => 'Hello, dynamic world!'))
            ),
            array(
                'namespace' => 'demo/v1',
                'route' => '/hello-dynamic',
                'methods' => 'GET',
                'responseType' => 'php',
                'response' => '<?php echo json_encode(array("message" => "Hello, dynamic world!")); ?>'
            )
        );
        update_option('eb_routes', $routes);
    }

    foreach ($routes as $route) {
        register_rest_route($route['namespace'], $route['route'], array(
            'methods' => $route['methods'],
            'callback' => function($request) use ($route) {
                return eb_handle_dynamic_response($route['responseType'], $route['response'], $request);
            },
        ));
    }
}

function eb_handle_dynamic_response($responseType, $response, $request) {
    switch ($responseType) {
        case 'php':
            return new WP_REST_Response(eb_execute_php_code($response, $request), 200);
        default:
            return new WP_REST_Response(json_decode($response, true), 200);
    }
}

function eb_execute_php_code($code, $request) {
    ob_start();
    eval('?>' . $code);
    $output = ob_get_clean();
    return json_decode($output, true);
}

// Handle AJAX request to save routes
add_action('wp_ajax_save_routes', 'eb_save_routes');

function eb_save_routes() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    $routes = isset($_POST['routes']) ? $_POST['routes'] : array();
    update_option('eb_routes', $routes);

    wp_send_json_success('Routes saved successfully');
}

// Handle AJAX request to get routes
add_action('wp_ajax_get_routes', 'eb_get_routes');

function eb_get_routes() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    $routes = get_option('eb_routes', array());
    wp_send_json_success($routes);
}

// Handle AJAX request to save webhooks
add_action('wp_ajax_save_webhooks', 'eb_save_webhooks');

function eb_save_webhooks() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    $webhooks = isset($_POST['webhooks']) ? $_POST['webhooks'] : array();
    update_option('eb_webhooks', $webhooks);

    wp_send_json_success('Webhooks saved successfully');
}

// Handle AJAX request to get webhooks
add_action('wp_ajax_get_webhooks', 'eb_get_webhooks');

function eb_get_webhooks() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    $webhooks = get_option('eb_webhooks', array());
    wp_send_json_success($webhooks);
}
?>
