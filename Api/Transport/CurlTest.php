<?php
use Orkan\Filmweb\Logger;
use Orkan\Filmweb\Tests\Utils;
use Orkan\Filmweb\Transport\Curl;
use Orkan\Filmweb\Transport\CurlRequest;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class CurlTest extends TestCase
{
	protected $app;
	protected $send;

	/**
	 * Simulate curl_getinfo() result
	 *
	 * @var array
	 */
	protected $curl_getinfo_data = array(
	/* @formatter:off */
		'total_time'    => 2,
		'header_size'   => 3,
		'request_size'  => 4,
		'size_download' => 5,
	);
	/* @formatter:on */

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function setUp(): void
	{
		// Prepare shared replacement
		$this->app = new Container();
		$this->app['cfg'] = array( 'cookie_file' => 'SESSION_ID' );

		// Create empty stubs
		// Tip: Stubs has disabled constructor, all methods returns null
		$stub = $this->createStub( Logger::class );
		$this->app['logger'] = function () use ($stub ) {
			return $stub;
		};

		$stub = $this->createStub( CurlRequest::class );
		$this->app['request'] = function () use ($stub ) {
			return $stub;
		};
	}

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests:
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 *
	 * @group single
	 */
	public function test_get()
	{
		$this->app['request']->expects( $this->once() )->method( 'getInfo' )->willReturn( $this->curl_getinfo_data );
		$this->app['request']->expects( $this->once() )->method( 'exec' )->willReturn( 'response' );

		$send = new Curl( $this->app );
		$response = $send->get( 'url', 'request' );

		$this->assertSame( 'response', $response );
	}

	public function test_post()
	{
		$this->app['request']->expects( $this->once() )->method( 'getInfo' )->willReturn( $this->curl_getinfo_data );
		$this->app['request']->expects( $this->once() )->method( 'exec' )->willReturn( 'response' );

		$send = new Curl( $this->app );
		$response = $send->post( 'url', 'request' );

		$this->assertSame( 'response', $response );
	}

	public function test_exec()
	{
		$this->app['request']->expects( $this->once() )->method( 'init' );
		$this->app['request']->expects( $this->once() )->method( 'setOptArray' );
		$this->app['request']->expects( $this->once() )->method( 'exec' )->willReturn( 'response' );
		$this->app['request']->expects( $this->once() )->method( 'getInfo' )->willReturn( $this->curl_getinfo_data );
		$this->app['request']->expects( $this->once() )->method( 'close' );

		$send = new Curl( $this->app );
		Utils::callPrivateMethod( $send, 'exec', array( array() ) );

		$data = array(
		/* @formatter:off */
			'total_time'         => $this->curl_getinfo_data['total_time'],
			'total_data_sent'    => $this->curl_getinfo_data['header_size'] + $this->curl_getinfo_data['request_size'],
			'total_data_recived' => $this->curl_getinfo_data['size_download'],
		);
		/* @formatter:on */

		foreach ( $data as $k => $v ) {
			$this->assertEquals( $v, Utils::getPrivateProperty( $send, $k ) );
		}
	}

	public function test_exec_error_no_data()
	{
		$this->expectError();
		$this->app['request']->expects( $this->once() )->method( 'getInfo' )->willReturn( false );

		$send = new Curl( $this->app );
		Utils::callPrivateMethod( $send, 'exec', array( array() ) );
	}

	public function test_exec_error_no_response()
	{
		$this->expectError();
		$this->app['request']->expects( $this->once() )->method( 'getInfo' )->willReturn( $this->curl_getinfo_data );
		$this->app['request']->expects( $this->once() )->method( 'exec' )->willReturn( false );

		$send = new Curl( $this->app );
		Utils::callPrivateMethod( $send, 'exec', array( array() ) );
	}

	public function test_getTotalTime_()
	{
		$send = new Curl( $this->app );
		Utils::setPrivateProperty( $send, 'total_time', $value = 1234 );

		$this->assertEquals( $value, $send->getTotalTime() );
	}

	public function test_getTotalDataSent_()
	{
		$send = new Curl( $this->app );
		Utils::setPrivateProperty( $send, 'total_data_sent', $value = 1234 );

		$this->assertEquals( $value, $send->getTotalDataSent() );
	}

	public function test_getTotalDataRecived_()
	{
		$send = new Curl( $this->app );
		Utils::setPrivateProperty( $send, 'total_data_recived', $value = 1234 );

		$this->assertEquals( $value, $send->getTotalDataRecived() );
	}
}
