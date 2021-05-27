# prologue-php-server
prologue-php-server
<h2>Install</h2>
<code>
<pre>
composer require prologue-framework/prologue-php-server
</pre>
</code>

<h2>Create endpoint-server</h2>

<ul>
<ol>Open root folder /</ol>
<ol>Create /api/ folder </ol>
<ol>
<p>Create /api/.htaccess </p>
<code>
<pre>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ server.php [L,QSA]
Options -Indexes
</pre>
</code>
</ol>
<ol>Create /api/server.php </ol>
</ul>

<h2>Build server.php</h2>

<h3>include Autoload</h3>
<code>
<pre>
include '../vendor/autoload.php';
</pre>
</code>

<h3>Create Server object</h3>
<code>
<pre>
$server = new PrologueFramework\Http\Server\PhpServer();
</pre>
</code>

<h3>Set endpoints workspace</h3>
<code>
<pre>
$server->setApiEndpointsWorkSpace(
    [
        [
            'jsonSourceFolder' => '/app/interface/site/v1/api/',
            'endpointWorkSpace' => '/api/site/v1/',
        ]
    ]
);
</pre>
</code>

<h3>Server, run!</h3>
<code>
<pre>
$server->run();
</pre>
</code>