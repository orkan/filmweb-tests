<?php
use Orkan\Filmweb\Filmweb;
use Orkan\Filmweb\Logger;
use Orkan\Filmweb\Api\Method\isLoggedUser;
use Orkan\Filmweb\Tests\Utils;
use Orkan\Filmweb\Transport\Curl;
use PHPUnit\Framework\TestCase;

class ReadmeTest extends TestCase
{
	protected $filmweb;
	protected $nickname = 'nickname';
	protected $password = 'password';
	protected $app;

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function setUp(): void
	{
		// Init Filmweb object with default settings
		$this->filmweb = new Filmweb( $this->nickname, $this->password );

		// Get reference to DI container
		$this->app = Utils::getPrivateProperty( $this->filmweb, 'app' );

		// Create empty stubs for Filmweb app
		$stub = $this->createStub( Logger::class );
		$this->app['logger'] = function () use ($stub ) {
			return $stub;
		};

		$stub = $this->createStub( Curl::class );
		$this->app['send'] = function () use ($stub ) {
			return $stub;
		};

		// Setup Transport responses for each calls initiated
		$this->app['send']->method( 'with' )->will( $this->onConsecutiveCalls(
		/* @formatter:off */

			// 1: Api::call 'login'
			// Login to Filmweb
			// Info: PHPUnit cathes any trigger_error() and prints callstack during tests if $this->expectError(); is omited
			// exx != exc -> suppresses printing errors on screen
			"ok\nexx Parameters don not seem to be in JSON format\n", 

			// 2: Api::call 'isLoggedUser'
			// Get user info
			"ok\n[\"nick\",null,null,12345,\"M\",\"1\",\"1\",\"1\"]\n",

			// 3: Api::call 'getUserFilmVotes'
			// Get list of voted films
			"ok\n[1589838401473,[31293,1206831600000,10,1,null,0],[11075,1206831600000,10,0,null,0]] s\n"
		));
		/* @formatter:on */

		// Suppress printing errors on screen
		// define( 'TESTING', true );
		// $this->app['errorHandler'] = array( $this, 'errorHandler' );
		// set_error_handler( function(){return false;} );
	}

	// public function errorHandler()
	// {
		// return true;
	// }

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests:
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function testExampleFromReadme_()
	{
		// Login to Filmweb
		// $filmweb = new Filmweb( $login, $password );
		$filmweb = $this->filmweb; // <-- mocked DI container
		$api = $filmweb->getApi();

		// Get user info
		$api->call( 'isLoggedUser' );
		$user = $api->getData();
		$userId = $user[isLoggedUser::USER_ID];

		// Get list of voted films
		$api->call( 'getUserFilmVotes', array( $userId ) );
		$films = $api->getData();

		// ///////////////////////////////////////////////////////////////////////////////////////
		// Assertions...
		$this->assertEquals( $userId, 12345, "Wrong response from \$api->call( 'isLoggedUser' )" );
		$this->assertCount( 3, $films, "Wrong response from \$api->call( 'getUserFilmVotes' )" );
	}
}
