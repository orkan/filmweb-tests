<?php
use Orkan\Filmweb\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers Helpers
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function bytesProvider()
	{
		/* @formatter:off */
		return array (
			'byte' => [ '1023 bytes',    1023 ],
			'kB'   => [    '61.3 kB',   62775 ],
			'MB'   => [    '2.95 MB', 3098401 ],
		);
		/* @formatter:on */
	}

	public function timeProvider()
	{
		/* @formatter:off */
		return array (
			'seconds' => [ '12s', 12 ],
			'minutes' => [ '3m', 3 * 60],
			'hours with usec'    => [ '1g 34m 45.987s', 3600 + 34 * 60 + 45 + 0.987 ],
			'hours without usec' => [ '1g 34m 45s', 3600 + 34 * 60 + 45 + 0.987, false ],
			'days'    => [ '18401d 21g 19m 44s', 1589923184.4001, false ],
		);
		/* @formatter:on */
	}

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests: Tests:
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 *
	 * @dataProvider bytesProvider
	 */
	public function test_formatBytes_( $expected, $bytes )
	{
		$this->assertSame( $expected, Utils::formatBytes( $bytes ) );
	}

	/**
	 *
	 * @dataProvider timeProvider
	 */
	public function test_formatTime_( $expected, $timestamp, $add_micro = true )
	{
		$this->assertSame( $expected, Utils::formatTime( $timestamp, $add_micro ) );
	}

	public function test_getTimestamp_()
	{
		$t1 = (string) time();
		$this->assertSame( $t1, Utils::getTimestamp( $t1 . '987' ) );
	}

	public function test_formatDateTimeZone_()
	{
		$t1 = (string) time();
		$this->assertNotEmpty( Utils::formatDateTimeZone( $t1 . '987' ) );
	}
}
