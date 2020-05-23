<?php
use Orkan\Filmweb\Api\Method\getFilmsInfoShort;
use PHPUnit\Framework\TestCase;

class getFilmsInfoShortTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmsInfoShort() )::TYPE );
	}

	public function test_format( $ids = array( 36797, 19 ) )
	{
		/* @formatter:off */
		$args = array(
			getFilmsInfoShort::IDS => $ids,
		);
		/* @formatter:on */

		$expected = sprintf( 'getFilmsInfoShort [%s]', json_encode( $args[getFilmsInfoShort::IDS] ) );
		$result = ( new getFilmsInfoShort() )->format( $args );

		$this->assertSame( $expected, $result );
	}

	public function test_format_error( $id = 36797 )
	{
		$this->expectError();

		/* @formatter:off */
		$args = array(
			getFilmsInfoShort::IDS => $id,
		);
		/* @formatter:on */

		( new getFilmsInfoShort() )->format( $args );
	}
}
