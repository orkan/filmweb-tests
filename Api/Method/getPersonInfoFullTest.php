<?php
use Orkan\Filmweb\Api\Method\getPersonInfoFull;
use PHPUnit\Framework\TestCase;

class getPersonInfoFullTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getPersonInfoFull() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getPersonInfoFull::ID => $id,
		);
		/* @formatter:on */

		$this->assertSame( "getPersonInfoFull [$id]", ( new getPersonInfoFull() )->format( $args ) );
	}
}
