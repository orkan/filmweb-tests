# filmweb-tests
PHPUnit tests for filmweb-api

## GitHub Pages 
Your site is ready to be published at https://orkan.github.io/filmweb-tests/. 

## Basic usage of fimweb-api
```php
<?php
use Orkan\Filmweb\Filmweb;
use Orkan\Filmweb\Api\Method\isLoggedUser;

// Login to Filmweb
$filmweb = new Filmweb( $login, $password );
$api = $filmweb->getApi();

// Get user info
$api->call( 'isLoggedUser' );
$user = $api->getData();
$userId = $user[ isLoggedUser::USER_ID ];

// Get a list of voted films
$api->call( 'getUserFilmVotes', array( $userId ) );
$films = $api->getData();

// ...

print_r( $films );
```
