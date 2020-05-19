<?php
use Orkan\Filmweb\Logger;
use Orkan\Filmweb\Api\Api;
use Orkan\Filmweb\Test\Utils;
use Orkan\Filmweb\Transport\Curl;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class ApiTest extends TestCase
{
	protected $app;

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function setUp(): void
	{
		// Prepare shared replacement
		$this->app = new Container();
		$this->app['cfg'] = [];

		// Create empty stubs
		// Tip: Stubs has disabled constructor, all methods returns null
		$logger = $this->createStub( Logger::class );
		$this->app['logger'] = function () use ($logger ) {
			return $logger;
		};
		$send = $this->createStub( Curl::class );
		$this->app['send'] = function () use ($send ) {
			return $send;
		};
	}

	// Mock Transport object with fixed Error responses
	public function responseErrorProvider()
	{
		/* @formatter:off */
		return array(
			'Error Decode'   => ["ok\n[--- bad json object ---]\n"],
			'Error NoJson'   => ["ok\n--- no json object ---\n"   ],
			'Wrong Response' => ["ok--- wrong response ---"       ],
			'Filmweb Error'  => ["err\n[--- filmweb error ---]\n" ],
		);
		/* @formatter:on */
	}

	// Mock Transport object with fixed Success responses
	public function responseSuccessProvider()
	{
		/* @formatter:off */
		return array(
			'isLoggedUser'    => ["ok\n[\"orkans\",null,null,882837,\"M\",\"1\",\"1\",\"1\"]\n"],
			'getFilmInfoFull' => ["ok\n[\"Boogie Nights\",null,7.15972,25883,\"Dramat\",1997,156,0,\"https:\/\/www.filmweb.pl\/film\/Boogie+Nights-1997-19\/discussion\",1,1,\"\/00\/19\/19\/7175962.2.jpg\",null,\"1997-09-11\",\"1998-08-14\",0,0,0,\"USA\",\"Historia m\u0142odzie\u0144ca, Eddiego, kt\u00f3ry dzi\u0119ki du\u017cemu przyrodzeniu, prze\u017cywa sw\u00f3j \\\"ameryka\u0144ski sen\\\" od zera do Dirka Digglera, gwiazdy film\u00f3w porno.\"] t:43200\n"],
		);
		/* @formatter:on */
	}

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests:
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 *
	 * @group send
	 * @dataProvider responseErrorProvider
	 */
	public function testCall_error( $response )
	{
		$this->expectError();

		$this->app['send']->expects( $this->once() )->method( 'with' )->willReturn( $response );

		// Start Api method test
		$api = new Api( $this->app );
		$api->call( 'isLoggedUser', array() );
		$api->getData();
	}

	/**
	 *
	 * @group send
	 * @dataProvider responseSuccessProvider
	 */
	public function testGetRequest_( $response )
	{
		$this->app['send']->expects( $this->once() )->method( 'with' )->willReturn( $response );

		$api = new Api( $this->app );
		$api->call( 'isLoggedUser', array() );

		$this->assertNotEmpty( $api->getRequest() );
	}

	/**
	 *
	 * @group send
	 * @dataProvider responseSuccessProvider
	 */
	public function testGetResponse_( $response )
	{
		$this->app['send']->expects( $this->once() )->method( 'with' )->willReturn( $response );

		$api = new Api( $this->app );
		$api->call( 'isLoggedUser', array() );

		$this->assertSame( $response, $api->getResponse() );
	}

	/**
	 *
	 * @group send
	 * @dataProvider responseSuccessProvider
	 */
	public function testGetData_( $response )
	{
		$this->app['send']->expects( $this->once() )->method( 'with' )->willReturn( $response );

		$api = new Api( $this->app );
		$api->call( 'isLoggedUser', array() );

		foreach ( array( 'array', 'json', 'raw', '' ) as $v ) { // Tip: 'extra' key can be empty
			$this->assertNotEmpty( $api->getData( $v ), "Empty result in Api->getData('$v')" );
		}
	}

	/**
	 * @group single
	 *
	 */
	public function testAll_Transport_GetTotal_methods()
	{
		// Api performs indirect call to Transport-> ... these methods
		// Method names are the same in Api and Transport class
		/* @formatter:off */
		$methods = array(
			'getTotalDataRecived' => 2345,
			'getTotalDataSent' => 45,
			'getTotalTime' => 1.2345,
		);
		/* @formatter:on */

		// Start Api method test
		$api = new Api( $this->app );

		foreach ( $methods as $method => $expected ) {
			// Stub Transport method
			$this->app['send']->method( $method )->willReturn( $expected );

			// Perform indirect call to Transport->getTotalDataRecived() via Api->getTotalDataRecived()
			$result = $api->$method();

			$this->assertEquals( $expected, $result, "Wrong value returned by Api->$method()" );
		}
	}

	public function testSlowdown()
	{
		$call_no = 10; // bytes
		$pause = 300000; // usec

		// Modify container values
		/* @formatter:off */
		$this->app['cfg'] = array_merge( array(
			'limit_call' => $call_no, // usleep() after reaching this limit of call()'s
			'limit_usec' => $pause, // In microseconds! 1s == 1 000 000 us
		), $this->app['cfg'] );
		/* @formatter:on */

		$api = new Api( $this->app );
		Utils::setPrivateProperty( $api, 'calls', $call_no - 1 ); // if ( ++calls == limit_call ) >> trigger usleep()
		Utils::callPrivateMethod( $api, 'slowdown' );

		$this->assertEquals( $pause / 1000000, $api->getTotalSleep(), "Wrong value returned Api->slowdown()" );
	}

	/**
	 * @group single
	 *
	 */
	public function testGetTotalSleep_()
	{
		$value = 2000000; // usec. The result is returned in sec, so 2000000/1000000 == 2 sec

		$api = new Api( $this->app );
		Utils::setPrivateProperty( $api, 'sleep_total', $value );

		$result = $api->getTotalSleep();
		$expected = $value / 1000000;

		$this->assertEquals( $expected, $result, "Wrong value returned Api->getTotalSleep()" );
	}

	public function testGetQuery_()
	{
		$method = 'whatever';

		$api = new Api( $this->app );
		$query = Utils::callPrivateMethod( $api, 'getQuery', array( $method ) );

		$result = [];
		parse_str( $query, $result );

		$this->assertStringContainsString( $method, $result['methods'], "Missing method string in request: $query" );

		foreach ( array( 'methods', 'signature', 'version', 'appId' ) as $v ) {
			$this->assertArrayHasKey( $v, $result, "Missing request key: '$v'" );
		}
	}

	public function testGetMethod_()
	{
		$method = 'login';

		// Create new API method object in DI continer
		$api = new Api( $this->app );
		$new = Utils::callPrivateMethod( $api, 'getMethod', array( $method ) );

		$class = 'Orkan\\Filmweb\\Api\\Method\\' . $method;
		$this->assertSame( $this->app[$method], $new, "DI continer doesn't hold the same object reference returned by Api->getMethod('$method')" );
		$this->assertTrue( $this->app[$method] instanceof $class, "DI continer doesn't hold instanceof $class" );
	}

	public function testGetDefaults_()
	{
		$api = new Api( $this->app );
		$result = Utils::callPrivateMethod( $api, 'getDefaults' );
		$this->assertNotEmpty( $result, 'Missing Api default config' );
	}

	public function testGetTotalCalls_()
	{
		$value = 2;

		$api = new Api( $this->app );
		Utils::setPrivateProperty( $api, 'calls', $value );

		$this->assertSame( $api->getTotalCalls(), $value );
	}
}
