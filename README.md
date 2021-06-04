# prologue-php-server
prologue-php-server
<h2>Install</h2>

<pre><code>
composer require prologue-framework/prologue-php-server

</code></pre>

<h2>Create endpoint-server</h2>

<ol>
<li>Open root folder /</li>
<li>Create /api/ folder </li>
<li>
Create /api/.htaccess <br> <br>
    
<pre><code>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ server.php [L,QSA]
Options -Indexes
</code></pre>
</li>
<li>Create /api/server.php </li>
</ol>

<h2>Build server.php</h2>

<h3>include Autoload</h3>
<pre><code>
include '../vendor/autoload.php';

</code></pre>

<h3>Create Server object</h3>
<pre><code>
$server = new PrologueFramework\Http\Server\PhpServer();

</code></pre>

<h3>Set endpoints workspace</h3>
<pre><code>
$server->setApiEndpointsWorkSpace(
    [
        [
            'jsonSourceFolder' => '/app/interface/site/v1/api/',
            'endpointWorkSpace' => '/api/site/v1/',
        ]
    ]
);

</code></pre>

<h3>Server, run!</h3>
<pre><code>
$server->run();

</code></pre>
