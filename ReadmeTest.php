<?php
use PHPUnit\Framework\TestCase;
use Orkan\Filmweb\Filmweb;
use Orkan\Filmweb\Logger;
use Orkan\Filmweb\Api\Api;
use Orkan\Filmweb\Api\Method\isLoggedUser;
use Orkan\Filmweb\Tests\Utils;
use Orkan\Filmweb\Transport\Curl;
use Orkan\Filmweb\Transport\CurlRequest;

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

		// Setup responses for each of calls
		$this->app['send']->method( 'with' )->will( $this->onConsecutiveCalls(
		/* @formatter:off */

			// $api->call( 'login' )
			"ok\nexc Parameters don not seem to be in JSON format\n",

			// $api->call( 'isLoggedUser' )
			"ok\n[\"nick\",null,null,12345,\"M\",\"1\",\"1\",\"1\"]\n",

			// $api->call( 'getUserFilmVotes' )
			"ok\n[1589838401473,[31293,1206831600000,10,1,null,0],[11075,1206831600000,10,0,null,0]] s\n"
		));
		/* @formatter:on */
	}

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests:
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function testExampleFromReadme()
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
