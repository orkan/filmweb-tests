<?php
use Orkan\Filmweb\Filmweb;
use Orkan\Filmweb\Logger;
use Orkan\Filmweb\Api\Api;
use Orkan\Filmweb\Api\Method\login;
use Orkan\Filmweb\Test\Utils;
use Orkan\Filmweb\Transport\Curl;
use Orkan\Filmweb\Transport\Transport;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class FilmwebTest extends TestCase
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
		// 		/* @formatter:off */
// 		$cfg = array(
// 			'api'       => '\\Orkan\\Filmweb\\Test\\Mock',
// 			'tarnsport' => '\\Orkan\\Filmweb\\Test\\Mock',
// 			'logger'    => '\\Orkan\\Filmweb\\Test\\Mock',
// 		);
// /* @formatter:on */

		// Init Filmweb object with default settings
		$this->filmweb = new Filmweb( $this->nickname, $this->password );

		// Replace PHP errorHandler callback
		// $errorHandler = array( $this, 'errorHandler' );
		// set_error_handler( $errorHandler );

		$this->app = Utils::getPrivateProperty( $this->filmweb, 'app' );

		// Create empty stubs for Filmweb app
		// Tip: Stubs has disabled constructor, all methods returns null
		// Note:
		// DI container uses lazy loading, so objects must be not executed in Filmweb->__construct()
		// Otherwise you must use $cfg[] to send mocked classnames to Filmweb->__construct()
		$stub = $this->createStub( Api::class );
		$this->app['api'] = function () use ($stub ) {
			return $stub;
		};
		$stub = $this->createStub( Curl::class );
		$this->app['send'] = function () use ($stub ) {
			return $stub;
		};
		$stub = $this->createStub( Logger::class );
		$this->app['logger'] = function () use ($stub ) {
			return $stub;
		};
	}

	// public function errorHandler( int $errno, string $errstr, string $errfile, int $errline )
	// {
	// echo "\$errno: $errno, \$errstr: $errstr, \$errfile: $errfile, \$errline: $errline";
	// return false;
	// }

	// $this->expectError();
	// $this->markTestIncomplete('This test has not been implemented yet.');

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests:
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 */
	public function testConstruct_continer()
	{
		$this->assertInstanceOf( Api::class, $this->app['api'] );
		$this->assertInstanceOf( Transport::class, $this->app['send'] ); // Curl used in SetUp() !
		$this->assertInstanceOf( Logger::class, $this->app['logger'] );
	}

	/**
	 * First call to Filmweb->getApi() must trigger Api->getTotalCalls() and Api->call('login')
	 * Api->getTotalCalls() must return 0
	 */
	public function testGetApi_first_call()
	{
		$this->app['api']->expects( $this->once() )->method( 'getTotalCalls' )->willReturn( 0 );
		$this->app['api']->expects( $this->once() )->method( 'call' )->with(
		/* @formatter:off */
			$this->equalTo( 'login' ), // first param
			$this->identicalTo( array( // second param: array('login', 'pass')
				login::NICKNAME => $this->nickname,
				login::PASSWORD => $this->password
			))
		/* @formatter:on */
		);
		$this->filmweb->getApi();
	}

	/**
	 * Second call to Filmweb->getApi() must return $this->app['api'] object without Api->call(login)
	 * Api->getTotalCalls() must return 1
	 */
	public function testGetApi_second_call()
	{
		$this->app['api']->expects( $this->once() )->method( 'getTotalCalls' )->willReturn( 1 );
		$this->app['api']->expects( $this->never() )->method( 'call' );
		$this->filmweb->getApi();
	}

	public function testGetDefaults_()
	{
		$result = Utils::callPrivateMethod( $this->filmweb, 'getDefaults' );
		$this->assertNotEmpty( $result, 'Missing Filmweb default config' );
	}

	/**
	 * Use E_DEPRECATED const to trigger Logger->unknown() in Filmweb->errorHandler()
	 * Note:
	 * There is no way to catch STDERR in PHPUnit
	 * Don't use E_(USER)ERROR\WARNING since they'll trigger exit()
	 *
	 * @group single
	 */
	public function testErrorHandler_proccess_notice()
	{
		$tmp = error_reporting(); // Remember current error_reporting
		error_reporting( E_DEPRECATED ); // Proccess this error in Filmweb->errorHandler()

		$this->app['logger']->expects( $this->once() )->method( 'unknown' );
		$this->filmweb->errorHandler( E_DEPRECATED, 'Testing Filmweb->errorHandler()', __FILE__, __LINE__ );

		error_reporting( $tmp ); // Recover original error_reporting
	}

	/**
	 * Test ignored errors
	 */
	public function testErrorHandler_ignore_notice()
	{
		$tmp = error_reporting(); // Remember current error_reporting
		error_reporting( E_ALL & ~ E_NOTICE ); // Ignore this error in Filmweb->errorHandler()

		$this->app['logger']->expects( $this->never() )->method( 'notice' );
		$this->filmweb->errorHandler( E_NOTICE, 'Testing ignored error in Filmweb->errorHandler()', __FILE__, __LINE__ );

		error_reporting( $tmp ); // Recover original error_reporting
	}

	public function testGetExecTime_()
	{
		$this->assertIsFloat( $this->filmweb->getExectime() );
	}
}
