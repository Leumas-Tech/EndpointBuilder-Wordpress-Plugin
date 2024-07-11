document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('routes-container')) {
        const routesContainer = document.getElementById('routes-container');
        const addRouteButton = document.getElementById('add-route-button');
        const saveRoutesButton = document.getElementById('save-routes-button');

        addRouteButton.addEventListener('click', function () {
            addRouteBlock();
        });

        saveRoutesButton.addEventListener('click', function () {
            saveRoutes();
        });

        function addRouteBlock(route = {}) {
            const routeBlock = document.createElement('div');
            routeBlock.className = 'route-block';

            const routeFields = `
                <label>Namespace: <input type="text" class="namespace" value="${route.namespace || ''}"></label>
                <label>Route: <input type="text" class="route" value="${route.route || ''}"></label>
                <label>Methods: 
                    <select class="methods">
                        <option value="GET" ${route.methods === 'GET' ? 'selected' : ''}>GET</option>
                        <option value="POST" ${route.methods === 'POST' ? 'selected' : ''}>POST</option>
                        <option value="PUT" ${route.methods === 'PUT' ? 'selected' : ''}>PUT</option>
                        <option value="DELETE" ${route.methods === 'DELETE' ? 'selected' : ''}>DELETE</option>
                    </select>
                </label>
                <label>Response Type: 
                    <select class="response-type">
                        <option value="static" ${route.responseType === 'static' ? 'selected' : ''}>Static</option>
                        <option value="php" ${route.responseType === 'php' ? 'selected' : ''}>PHP</option>
                    </select>
                </label>
                <label>Response: <textarea class="response">${route.response ? route.response : ''}</textarea></label>
                <button class="remove-route">Remove</button>
            `;
            routeBlock.innerHTML = routeFields;
            routesContainer.appendChild(routeBlock);

            routeBlock.querySelector('.remove-route').addEventListener('click', function () {
                routesContainer.removeChild(routeBlock);
            });
        }

        function saveRoutes() {
            const routeBlocks = document.querySelectorAll('.route-block');
            const routes = [];
            routeBlocks.forEach(block => {
                const namespace = block.querySelector('.namespace').value;
                const route = block.querySelector('.route').value;
                const methods = block.querySelector('.methods').value;
                const responseType = block.querySelector('.response-type').value;
                const response = block.querySelector('.response').value;

                if (namespace && route && methods) {
                    routes.push({ namespace, route, methods, responseType, response });
                }
            });

            jQuery.post(eb_ajax.ajax_url, {
                action: 'save_routes',
                routes: routes
            }, function (response) {
                if (response.success) {
                    alert('Routes saved successfully');
                    location.reload();
                } else {
                    alert('Error saving routes: ' + response.data);
                }
            });
        }

        // Load existing routes
        if (window.existingRoutes) {
            window.existingRoutes.forEach(route => {
                addRouteBlock(route);
            });
        }
    }

    if (document.getElementById('webhooks-container')) {
        const webhooksContainer = document.getElementById('webhooks-container');
        const addWebhookButton = document.getElementById('add-webhook-button');
        const saveWebhooksButton = document.getElementById('save-webhooks-button');

        addWebhookButton.addEventListener('click', function () {
            addWebhookBlock();
        });

        saveWebhooksButton.addEventListener('click', function () {
            saveWebhooks();
        });

        function addWebhookBlock(webhook = {}) {
            const webhookBlock = document.createElement('div');
            webhookBlock.className = 'webhook-block';

            const webhookFields = `
                <label>URL: <input type="text" class="url" value="${webhook.url || ''}"></label>
                <label>Method: 
                    <select class="method">
                        <option value="POST" ${webhook.method === 'POST' ? 'selected' : ''}>POST</option>
                        <option value="GET" ${webhook.method === 'GET' ? 'selected' : ''}>GET</option>
                    </select>
                </label>
                <label>Payload: <textarea class="payload">${webhook.payload ? webhook.payload : ''}</textarea></label>
                <button class="remove-webhook">Remove</button>
            `;
            webhookBlock.innerHTML = webhookFields;
            webhooksContainer.appendChild(webhookBlock);

            webhookBlock.querySelector('.remove-webhook').addEventListener('click', function () {
                webhooksContainer.removeChild(webhookBlock);
            });
        }

        function saveWebhooks() {
            const webhookBlocks = document.querySelectorAll('.webhook-block');
            const webhooks = [];
            webhookBlocks.forEach(block => {
                const url = block.querySelector('.url').value;
                const method = block.querySelector('.method').value;
                const payload = block.querySelector('.payload').value;

                if (url && method) {
                    webhooks.push({ url, method, payload });
                }
            });

            jQuery.post(eb_ajax.ajax_url, {
                action: 'save_webhooks',
                webhooks: webhooks
            }, function (response) {
                if (response.success) {
                    alert('Webhooks saved successfully');
                    location.reload();
                } else {
                    alert('Error saving webhooks: ' + response.data);
                }
            });
        }

        // Load existing webhooks
        if (window.existingWebhooks) {
            window.existingWebhooks.forEach(webhook => {
                addWebhookBlock(webhook);
            });
        }
    }

    if (document.getElementById('route-list-container')) {
        loadRouteList();
    }

    function loadRouteList() {
        jQuery.get(eb_ajax.ajax_url, {
            action: 'get_routes'
        }, function (response) {
            if (response.success) {
                const routeListContainer = document.getElementById('route-list-container');
                routeListContainer.innerHTML = response.data.map(route => `
                    <tr>
                        <td>${route.namespace}</td>
                        <td>${route.route}</td>
                        <td>${route.methods}</td>
                        <td>${route.response}</td>
                        <td><button class="test-route" data-namespace="${route.namespace}" data-route="${route.route}">Test</button></td>
                    </tr>
                `).join('');
                document.querySelectorAll('.test-route').forEach(button => {
                    button.addEventListener('click', function () {
                        const namespace = button.getAttribute('data-namespace');
                        const route = button.getAttribute('data-route');
                        testRoute(namespace, route);
                    });
                });
            } else {
                document.getElementById('route-list-container').innerHTML = '<tr><td colspan="5">Error loading routes</td></tr>';
            }
        });
    }

    function testRoute(namespace, route) {
        const url = `/wp-json/${namespace}${route}`;
        jQuery.get(url, function (response) {
            alert('Response: ' + JSON.stringify(response, null, 2));
        });
    }
});
