<?php
use Orkan\Filmweb\Transport\Transport;
use PHPUnit\Framework\TestCase;

class TransportTest extends TestCase
{

	public function test_with_get()
	{
		$stub = $this->getMockForAbstractClass( Transport::class );

		$stub->expects( $this->any() )->method( 'get' )->with(
		/* @formatter:off */
			$this->equalTo( 'url' ),
			$this->equalTo( 'query' )
		/* @formatter:off */
		)->willReturn( 'response' );

		$this->assertSame( 'response', $stub->with( 'get', 'url', 'query' ) );
	}

	public function test_with_post()
	{
		$stub = $this->getMockForAbstractClass( Transport::class );

		$stub->expects( $this->any() )->method( 'post' )->with(
		/* @formatter:off */
			$this->equalTo( 'url' ),
			$this->equalTo( 'query' )
		/* @formatter:off */
		)->willReturn( 'response' );

		$this->assertSame( 'response', $stub->with( 'post', 'url', 'query' ) );
	}
}
