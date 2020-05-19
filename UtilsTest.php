<?php

use Orkan\Filmweb\Utils;
use PHPUnit\Framework\TestCase;

/**
 * @covers Orkan\Filmweb\Utils
 */
class UtilsTest extends TestCase
{
	/**
	 * @dataProvider bytesProvider
	 */
	public function testFormatBytes( $expected, $bytes )
	{
		$this->assertSame( $expected, Utils::formatBytes( $bytes ) );
	}

	/**
	 * @dataProvider timeProvider
	 */
	public function testFormatTime( $expected, $timestamp, $add_micro = true )
	{
		$this->assertSame( $expected, Utils::formatTime( $timestamp, $add_micro) );
	}

	public function testGetTimestamp()
	{
		$t1 = (string) time();
		$this->assertSame( $t1, Utils::getTimestamp( $t1 . '987' ) );
	}

	/////////////////////////////////////////////////////////////////////////////
	// Privideres

	public function bytesProvider()
	{
		return array (
			array ( '1023 bytes', 1023 ),
			array ( '61.3 kB', 62775 ),
			array ( '2.95 MB', 3098401 ),
		);
	}

	public function timeProvider()
	{
		return array (
			array ( '12s', 12 ),
			array ( '3m', 3 * 60 ),
			array ( '1g 34m 45.987s', 3600 + 34 * 60 + 45 + 0.987 ),
			array ( '1g 34m 45s', 3600 + 34 * 60 + 45 + 0.987, false ),
		);
	}
}
