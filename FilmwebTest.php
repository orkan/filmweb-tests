<?php
use Orkan\Filmweb\Filmweb;
use Orkan\Filmweb\Logger;
use Orkan\Filmweb\Api\Api;
use Orkan\Filmweb\Api\Method\login;
use Orkan\Filmweb\Tests\Utils;
use Orkan\Filmweb\Transport\Curl;
use Orkan\Filmweb\Transport\Transport;
use PHPUnit\Framework\TestCase;

// Suppress print to STDERR in Filmweb->errorHandler()
define( 'TESTING', true );

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

	// $this->expectError();
	// $this->markTestIncomplete('This test has not been implemented yet.');

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests:
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 */
	public function test_construct()
	{
		$app = Utils::getPrivateProperty( $this->filmweb, 'app' );
		$this->assertSame( $this->app, $app, 'Missing Stubs in DI continer' );

		$this->assertInstanceOf( Api::class, $this->app['api'] );
		$this->assertInstanceOf( Transport::class, $this->app['send'] ); // Curl used in SetUp() !
		$this->assertInstanceOf( Logger::class, $this->app['logger'] );
	}

	public function test_getDefaults_()
	{
		$result = Utils::callPrivateMethod( $this->filmweb, 'getDefaults' );
		$this->assertNotEmpty( $result, 'Missing Filmweb default config' );
	}

	/**
	 * First call to Filmweb->getApi() must trigger Api->getTotalCalls() and Api->call('login')
	 * Api->getTotalCalls() must return 0
	 */
	public function test_getApi_first_call()
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
	public function test_getApi_second_call()
	{
		$this->app['api']->expects( $this->once() )->method( 'getTotalCalls' )->willReturn( 1 );
		$this->app['api']->expects( $this->never() )->method( 'call' );
		$this->filmweb->getApi();
	}

	public function test_getLogger_()
	{
		$this->assertInstanceOf( Logger::class, $this->filmweb->getLogger() );
	}


	/**
	 * Test errors
	 */
	public function test_errorHandler_error()
	{
		$tmp = error_reporting(); // Remember current error_reporting
		error_reporting( E_ERROR );

		$this->app['logger']->expects( $this->once() )->method( 'error' );

		$this->app['cfg'] = array_merge( $this->app['cfg'], array( 'exit_on' => 0 ) ); // Suppress exit
		$this->filmweb->errorHandler( E_ERROR, 'Testing Filmweb->errorHandler() with E_ERROR', __FILE__, __LINE__ );

		error_reporting( $tmp ); // Recover original error_reporting
	}

	/**
	 * @group single
	 */
	public function test_errorHandler_user_warning()
	{
		$tmp = error_reporting(); // Remember current error_reporting
		error_reporting( E_USER_WARNING );

		$this->app['logger']->expects( $this->once() )->method( 'warning' );
		$this->filmweb->errorHandler( E_USER_WARNING, 'Testing Filmweb->errorHandler() with E_USER_WARNING', __FILE__, __LINE__ );

		error_reporting( $tmp ); // Recover original error_reporting
	}

	public function test_errorHandler_notice()
	{
		$tmp = error_reporting(); // Remember current error_reporting
		error_reporting( E_NOTICE );

		$this->app['logger']->expects( $this->once() )->method( 'notice' );
		$this->filmweb->errorHandler( E_NOTICE, 'Testing Filmweb->errorHandler() with E_NOTICE', __FILE__, __LINE__ );

		error_reporting( $tmp ); // Recover original error_reporting
	}

	/**
	 * Use E_DEPRECATED const to trigger Logger->unknown() in Filmweb->errorHandler()
	 * Note:
	 * There is no way to catch STDERR in PHPUnit
	 * Use define( 'TESTING', true ) to test for error handling and not trigger exit()
	 */
	public function test_errorHandler_unknown()
	{
		$tmp = error_reporting(); // Remember current error_reporting
		error_reporting( E_DEPRECATED ); // Proccess this error in Filmweb->errorHandler()

		$this->app['logger']->expects( $this->once() )->method( 'unknown' );
		$this->filmweb->errorHandler( E_DEPRECATED, 'Testing Filmweb->errorHandler() with E_DEPRECATED', __FILE__, __LINE__ );

		error_reporting( $tmp ); // Recover original error_reporting
	}

	/**
	 * Test ignored errors
	 */
	public function test_errorHandler_ignore_notice()
	{
		$tmp = error_reporting(); // Remember current error_reporting
		error_reporting( E_ALL & ~ E_NOTICE ); // Do not report E_NOTICE in Filmweb->errorHandler()

		$this->app['logger']->expects( $this->never() )->method( 'notice' );
		$this->filmweb->errorHandler( E_NOTICE, 'Testing Filmweb->errorHandler() with ignored E_NOTICE', __FILE__, __LINE__ );

		error_reporting( $tmp ); // Recover original error_reporting
	}

	public function test_getTitle_()
	{
		$this->assertNotEmpty( $this->filmweb->getExectime() );
	}

	public function test_getExecTime_()
	{
		$result1 = $this->filmweb->getExectime();
		usleep( 300000 ); // sleep 0.3sec
		$result2 = $this->filmweb->getExectime();

		$this->assertGreaterThan( $result1, $result2 );
		$this->assertIsFloat( $result2 );
	}
}
