OAuth2 Client

Set up database and client config in:

lib/password.php
SAMPLE:
```
$connectionData = array(
'db_dsn' => 'mysql:host=dbHOST;dbname=dbNAME',
    "db_user" => "dbUSER",
    "db_pass" => "dbPASSWORD"
 );
```
    
lib/client_cred.php
SAMPLE:
```
$clientCredentials = array(
    "api_url" => "API URL",
    "url" => "CLIENT URL",
    "grant_type" => "GRANT TYPE",
    "scope" => "SCOPE",
    "client_id" => "CLIENT ID",
    "client_secret_pre" => "CLIENT SECRET",
);
```

