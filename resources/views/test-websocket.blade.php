<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Connection Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        .status.connecting {
            background-color: #ffc107;
            color: #000;
        }
        .status.connected {
            background-color: #28a745;
            color: white;
        }
        .status.disconnected {
            background-color: #dc3545;
            color: white;
        }
        .log {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            max-height: 400px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        .log-entry {
            margin: 5px 0;
            padding: 5px;
            border-left: 3px solid #007bff;
            background: white;
        }
        .log-entry.success {
            border-left-color: #28a745;
        }
        .log-entry.error {
            border-left-color: #dc3545;
        }
        .btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        h1 { color: #333; }
        h3 { color: #666; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔌 WebSocket Connection Test</h1>
        <p>This page tests the connection to Laravel Reverb WebSocket server</p>

        <div id="status" class="status connecting">
            ⏳ Connecting to WebSocket...
        </div>

        <div style="margin: 20px 0;">
            <button class="btn btn-primary" onclick="testConnection()">🔄 Reconnect</button>
            <button class="btn btn-success" onclick="testBroadcast()">📡 Test Broadcast</button>
            <button class="btn btn-danger" onclick="clearLogs()">🗑️ Clear Logs</button>
        </div>

        <h3>📊 Configuration</h3>
        <div class="log">
            <div><strong>Host:</strong> {{ config('broadcasting.connections.reverb.options.host') }}</div>
            <div><strong>Port:</strong> {{ config('broadcasting.connections.reverb.options.port') }}</div>
            <div><strong>App Key:</strong> {{ config('broadcasting.connections.reverb.key') }}</div>
            <div><strong>Scheme:</strong> {{ config('broadcasting.connections.reverb.options.scheme') }}</div>
        </div>

        <h3>📋 Connection Logs</h3>
        <div id="logs" class="log">
            <div class="log-entry">Initializing...</div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        let pusher;
        let channel;

        function addLog(message, type = 'info') {
            const logs = document.getElementById('logs');
            const entry = document.createElement('div');
            entry.className = 'log-entry ' + type;
            entry.innerHTML = `<strong>${new Date().toLocaleTimeString()}</strong>: ${message}`;
            logs.appendChild(entry);
            logs.scrollTop = logs.scrollHeight;
        }

        function updateStatus(status, message) {
            const statusEl = document.getElementById('status');
            statusEl.className = 'status ' + status;
            statusEl.innerHTML = message;
        }

        function testConnection() {
            addLog('Attempting to connect to WebSocket server...', 'info');
            updateStatus('connecting', '⏳ Connecting to WebSocket...');
            
            try {
                // Disconnect existing connection if any
                if (pusher) {
                    pusher.disconnect();
                }

                // Initialize Pusher/Reverb
                pusher = new Pusher('{{ config("broadcasting.connections.reverb.key") }}', {
                    wsHost: '{{ config("broadcasting.connections.reverb.options.host") }}',
                    wsPort: {{ config('broadcasting.connections.reverb.options.port') }},
                    wssPort: {{ config('broadcasting.connections.reverb.options.port') }},
                    forceTLS: {{ config('broadcasting.connections.reverb.options.scheme') === 'https' ? 'true' : 'false' }},
                    enabledTransports: ['ws', 'wss'],
                    cluster: 'mt1',
                    disableStats: true,
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                });

                addLog(`Pusher instance created with key: {{ config("broadcasting.connections.reverb.key") }}`, 'info');
                addLog(`Connecting to: {{ config("broadcasting.connections.reverb.options.host") }}:{{ config("broadcasting.connections.reverb.options.port") }}`, 'info');

                // Connection state changes
                pusher.connection.bind('state_change', function(states) {
                    addLog(`Connection state changed: ${states.previous} → ${states.current}`, 'info');
                });

                // Handle successful connection
                pusher.connection.bind('connected', function() {
                    addLog('✅ Successfully connected to WebSocket server!', 'success');
                    updateStatus('connected', '✅ Connected to WebSocket');
                });

                // Handle connection errors
                pusher.connection.bind('error', function(err) {
                    addLog('❌ Connection error: ' + JSON.stringify(err), 'error');
                    updateStatus('disconnected', '❌ Connection Failed');
                });

                // Handle disconnection
                pusher.connection.bind('disconnected', function() {
                    addLog('⚠️ Disconnected from WebSocket server', 'error');
                    updateStatus('disconnected', '⚠️ Disconnected');
                });

                // Handle unavailable state
                pusher.connection.bind('unavailable', function() {
                    addLog('❌ WebSocket server is unavailable', 'error');
                    updateStatus('disconnected', '❌ Server Unavailable');
                });

                // Subscribe to a test channel
                channel = pusher.subscribe('test-channel');
                
                channel.bind('pusher:subscription_succeeded', function() {
                    addLog('✅ Successfully subscribed to test-channel', 'success');
                });

                channel.bind('pusher:subscription_error', function(error) {
                    addLog('❌ Subscription error: ' + JSON.stringify(error), 'error');
                });

            } catch (error) {
                addLog('❌ Error initializing Pusher: ' + error.message, 'error');
                updateStatus('disconnected', '❌ Initialization Error');
            }
        }

        function testBroadcast() {
            if (!pusher || pusher.connection.state !== 'connected') {
                addLog('❌ Not connected! Please connect first.', 'error');
                return;
            }

            addLog('📡 Testing broadcast... (This is a client-side test only)', 'info');
            
            // Subscribe to test channel and listen
            const testChannel = pusher.subscribe('test-channel');
            testChannel.bind('test-event', function(data) {
                addLog('📨 Received test broadcast: ' + JSON.stringify(data), 'success');
            });

            addLog('ℹ️ To test server-side broadcast, trigger an event from Laravel', 'info');
        }

        function clearLogs() {
            document.getElementById('logs').innerHTML = '<div class="log-entry">Logs cleared</div>';
        }

        // Auto-connect on page load
        window.addEventListener('load', function() {
            addLog('Page loaded, attempting to connect...', 'info');
            testConnection();
        });

        // Debug: Log all Pusher activity
        Pusher.logToConsole = true;
    </script>
</body>
</html>

