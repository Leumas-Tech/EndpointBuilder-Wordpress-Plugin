<div class="wrap">
    <h1>Endpoint Builder</h1>
    <div id="routes-container"></div>
    <button id="add-route-button" class="button button-primary">Add Route</button>
    <button id="save-routes-button" class="button button-secondary">Save Routes</button>
</div>
<?php
$routes = get_option('eb_routes', array());
?>
<script>
    window.existingRoutes = <?php echo json_encode($routes); ?>;
</script>
