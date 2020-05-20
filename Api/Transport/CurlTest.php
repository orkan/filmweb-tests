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
		$this->app['request']->expects( $this->once() )->method( 'exec' )->willReturn( 'response' );

		$send = new Curl( $this->app );
		$response = $send->get( 'url', 'request' );

		$this->assertSame( 'response', $response );
	}

	public function test_post()
	{
		$this->app['request']->expects( $this->once() )->method( 'exec' )->willReturn( 'response' );

		$send = new Curl( $this->app );
		$response = $send->post( 'url', 'request' );

		$this->assertSame( 'response', $response );
	}

	public function test_exec()
	{
		/* @formatter:off */
		$info = array(
			'total_time'    => 2,
			'header_size'   => 3,
			'request_size'  => 4,
			'size_download' => 5,
		);
		/* @formatter:on */

		$this->app['request']->expects( $this->once() )->method( 'init' );
		$this->app['request']->expects( $this->once() )->method( 'setOptArray' );
		$this->app['request']->expects( $this->once() )->method( 'exec' )->willReturn( 'response' );
		$this->app['request']->expects( $this->once() )->method( 'getInfo' )->willReturn( $info );
		$this->app['request']->expects( $this->once() )->method( 'close' );

		$send = new Curl( $this->app );
		Utils::callPrivateMethod( $send, 'exec', array( array() ) );

		/* @formatter:off */
		$data = array(
			'total_time'         => $info['total_time'],
			'total_data_sent'    => $info['header_size'] + $info['request_size'],
			'total_data_recived' => $info['size_download'],
		);
		/* @formatter:on */

		foreach ( $data as $k => $v ) {
			$this->assertEquals( $v, Utils::getPrivateProperty( $send, $k ) );
		}
	}

	public function test_exec_with_error()
	{
		$this->expectError();
		$this->app['request']->expects( $this->once() )->method( 'exec' )->willReturn( false );

		Utils::callPrivateMethod( new Curl( $this->app ), 'exec', array( array() ) );
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
