Wordpress Nonces in Object Oriented Way
================================

Composer package that gives WordPress Nonces functionality in an object-oriented way.

Usage
-----

### Configure Nonce Defaults
 
``` php
use Nonces\Config;

Config::setSalt($salt);
Config::setUserId($userId);
Config::setSessionToken($sessionToken);
```

### Create Nonce

``` php
use Nonces\Nonce;

$nonce = new Nonce('readme-action');
$nonce->generate();
```

### Verify Nonce

``` php
use Nonces\Verifer;

$verifier = new Verifier();
$verifier->verify($nonce, $action);
```

### Override global configuration per Nonce

``` php
$nonce = new Nonce('Action', $myConfig);
$verifier = new Verifier($myConfig);

$nonce->setLifespan(172800);
$nonce->setAlgorithm('sha256');
$nonce->setSalt($salt);
$nonce->setUserId($userId);
$nonce->setSessionToken($sessionToken);
```