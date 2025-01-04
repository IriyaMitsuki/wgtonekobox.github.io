<?php
    $template = '{
    "interface_name": "что угодно",
    "local_address": [
    "значение Address из конфига",
    "второе значение Address (если отсутствует, удалить строчку и запятую в прошлой)"
    ],
    "mtu": 1280,
    "peer_public_key": "значение PublicKey",
    "pre_shared_key": "значение PresharedKey (если данный параметр отсутствует, удалить строчку)",
    "private_key": "значение PrivateKey",
    "server": "сюда ip сервера",
    "server_port": сюда вставляем port,
    "system_interface": false,
    "tag": "proxy",
    "type": "wireguard"
}';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $interfaceName = $_POST['interface_name'];
        $serverIp = $_POST['server_ip'];
        $serverPort = $_POST['server_port'];
        $localAddress = $_POST['local_address'];
        $publicKey = $_POST['public_key'];
        $presharedKey = $_POST['preshared_key'];
        $privateKey = $_POST['private_key'];

        $localAddresses = explode(",", $localAddress);
        $localAddressJson = json_encode($localAddresses);


        $filledTemplate = json_decode($template, true);
        $filledTemplate['interface_name'] = $interfaceName;
        $filledTemplate['local_address'] = $localAddresses;
        $filledTemplate['peer_public_key'] = $publicKey;
        $filledTemplate['private_key'] = $privateKey;
        $filledTemplate['server'] = $serverIp;
        $filledTemplate['server_port'] = (int)$serverPort;


        if (!empty($presharedKey)) {
            $filledTemplate['pre_shared_key'] = $presharedKey;
        } else {
            unset($filledTemplate['pre_shared_key']);
        }


        $jsonOutput = json_encode($filledTemplate, JSON_PRETTY_PRINT);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WireGuard Config Generator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <style>
        body {
            padding: 20px;
        }
        .container {
            max-width: 600px;
        }
        .form-group label {
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            margin-bottom: 10px;
        }
        #json_output {
            font-size: 0.8rem;
            height: 150px;
            resize: vertical;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>WireGuard Config Generator</h1>
        <form method="post">
            <div class="form-group">
                <label for="interface_name">Name Interface:</label>
                <input type="text" class="form-control" id="interface_name" name="interface_name" required>
            </div>
            <div class="form-group">
                <label for="server_ip">Server IP:</label>
                <input type="text" class="form-control" id="server_ip" name="server_ip" required>
            </div>
            <div class="form-group">
                <label for="server_port">Server Port:</label>
                <input type="number" class="form-control" id="server_port" name="server_port" required>
            </div>
            <div class="form-group">
                <label for="local_address">Local Address (comma separated for multiple):</label>
                <input type="text" class="form-control" id="local_address" name="local_address" required>
            </div>
            <div class="form-group">
                <label for="public_key">Public Key:</label>
                <input type="text" class="form-control" id="public_key" name="public_key" required>
            </div>
            <div class="form-group">
                <label for="preshared_key">Preshared Key (Optional):</label>
                <input type="text" class="form-control" id="preshared_key" name="preshared_key">
            </div>
            <div class="form-group">
                <label for="private_key">Private Key:</label>
                <input type="text" class="form-control" id="private_key" name="private_key" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate</button>
        </form>

        <?php if (isset($jsonOutput)): ?>
        <h2>Generated Config:</h2>
        <div class="form-group">
            <textarea class="form-control" id="json_output" rows="10" readonly><?php echo $jsonOutput; ?></textarea>
        </div>
        <button class="btn btn-secondary" data-clipboard-target="#json_output">Copy to Clipboard</button>
        <?php endif; ?>
    </div>

    <script>
        new ClipboardJS('.btn');
    </script>
</body>
</html>