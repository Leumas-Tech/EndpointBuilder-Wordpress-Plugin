<div class="wrap">
    <h1>Webhook Builder</h1>
    <div id="webhooks-container"></div>
    <button id="add-webhook-button" class="button button-primary">Add Webhook</button>
    <button id="save-webhooks-button" class="button button-secondary">Save Webhooks</button>
</div>
<?php
$webhooks = get_option('eb_webhooks', array());
?>
<script>
    window.existingWebhooks = <?php echo json_encode($webhooks); ?>;
</script>
