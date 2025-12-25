<?php
// Debug routing
echo "<h2>Debug Routing</h2>";
echo "<h3>GET:</h3><pre>";
print_r($_GET);
echo "</pre>";

echo "<h3>REQUEST_URI:</h3><pre>";
echo $_SERVER['REQUEST_URI'] ?? 'NOT SET';
echo "</pre>";

echo "<h3>SCRIPT_NAME:</h3><pre>";
echo $_SERVER['SCRIPT_NAME'] ?? 'NOT SET';
echo "</pre>";

// Test parseUrl logic
$url = '';
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    echo "<h3>URL from GET:</h3><pre>$url</pre>";
} elseif (isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    if (($pos = strpos($uri, '?')) !== false) {
        $uri = substr($uri, 0, $pos);
    }
    $url = ltrim($uri, '/');
    echo "<h3>URL from REQUEST_URI:</h3><pre>$url</pre>";
}

if (!empty($url)) {
    $parts = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));
    echo "<h3>URL Parts:</h3><pre>";
    print_r($parts);
    echo "</pre>";

    echo "<h3>Expected:</h3>";
    echo "Controller: " . (isset($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'HomeController') . "<br>";
    echo "Method: " . ($parts[1] ?? 'index') . "<br>";
}
