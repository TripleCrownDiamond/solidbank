<?php
// Test file to debug routes in production
// Access via: https://bred-fin.com/test-routes.php

echo "<h1>Route Testing</h1>";
echo "<p>Current URL: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>Server Name: " . $_SERVER['SERVER_NAME'] . "</p>";

echo "<h2>Test Links:</h2>";
echo "<ul>";
echo "<li><a href='/fr/transactions'>Direct: /fr/transactions</a></li>";
echo "<li><a href='/fr/dashboard'>Direct: /fr/dashboard</a></li>";
echo "<li><a href='/transactions'>Redirect: /transactions</a></li>";
echo "<li><a href='/dashboard'>Redirect: /dashboard</a></li>";
echo "</ul>";

echo "<h2>File System Check:</h2>";
echo "<p>Index.php exists: " . (file_exists('index.php') ? 'YES' : 'NO') . "</p>";
echo "<p>.htaccess exists: " . (file_exists('.htaccess') ? 'YES' : 'NO') . "</p>";

if (file_exists('.htaccess')) {
    echo "<h3>.htaccess content:</h3>";
    echo "<pre>" . htmlspecialchars(file_get_contents('.htaccess')) . "</pre>";
}

echo "<h2>Environment Variables:</h2>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";
?>