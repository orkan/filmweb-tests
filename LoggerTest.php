<?php
use Orkan\Filmweb\Logger;
use Orkan\Filmweb\Tests\Utils;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class LoggerTest extends TestCase
{
	protected $app; // Pimple\Container
	protected $logger; // Orkan\Filmweb\Logger
	protected $monolog; // Monolog\Logger
	protected $stub;

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function setUp(): void
	{
		$this->app = new Container();
		$this->app['cfg'] = [];

		// Save original Monolog\Logger
		$this->logger = new Logger( $this->app );
		$this->monolog = Utils::getPrivateProperty( $this->logger, 'logger' );

		// Replace Monolog\Logger with stub
		$this->stub = $this->createStub( \Monolog\Logger::class );
		Utils::setPrivateProperty( $this->logger, 'logger', $this->stub );
	}

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests:
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 */
	public function test_construct()
	{
		$this->assertInstanceOf( \Monolog\Logger::class, $this->monolog );
	}

	public function test_getDefaults_()
	{
		$result = Utils::callPrivateMethod( $this->logger, 'getDefaults' );
		$this->assertNotEmpty( $result, 'Missing Logger default config' );
	}

	public function test_backtrace()
	{
		$this->app['cfg'] = array_merge( $this->app['cfg'], array( 'is_debug' => true ) ); // backtrace in debug mode only
		
		$result = Utils::callPrivateMethod( $this->logger, 'backtrace' );
		$this->assertStringContainsString( 'Utils::callPrivateMethod', $result );
	}

	public function test_debug()
	{
		$message = 'testing...';
		$this->app['cfg'] = array( 'is_debug' => true ); // Condition for Logger->debug() to fire

		$this->stub->expects( $this->once() )->method( 'debug' )->with( $this->stringContains( $message ) );
		$this->logger->debug( $message );
	}

	public function test_debug_ignored()
	{
		$this->stub->expects( $this->never() )->method( 'debug' ); // $cfg['is_debug'] == false
		$this->logger->debug( 'testing...' );
	}

	public function test_error()
	{
		$message = 'testing...';
		$this->stub->expects( $this->once() )->method( 'error' )->with( $this->stringContains( $message ) );
		$this->logger->error( $message );
	}

	public function test_warning()
	{
		$message = 'testing...';
		$this->stub->expects( $this->once() )->method( 'warning' )->with( $this->stringContains( $message ) );
		$this->logger->warning( $message );
	}

	public function test_notice()
	{
		$message = 'testing...';
		$this->stub->expects( $this->once() )->method( 'notice' )->with( $this->stringContains( $message ) );
		$this->logger->notice( $message );
	}

	public function test_info()
	{
		$message = 'testing...';
		$this->stub->expects( $this->once() )->method( 'info' )->with( $this->stringContains( $message ) );
		$this->logger->info( $message );
	}

	public function test_unknown()
	{
		$message = 'testing...';
		$this->stub->expects( $this->once() )->method( 'info' )->with( $this->stringContains( $message ) );
		$this->logger->unknown( $message );
	}
}
