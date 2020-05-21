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
			'microtime-usec' => [ '0s'    , 0.1234, false ],
			'microtime+usec' => [ '0.123s', 0.1234 ],

			'zero-usec' => [ '0s', 0, false ],
			'zero+usec' => [ '0s', 0 ],

			'seconds'      => [ '12s'   , 12 ],
			'seconds-usec' => [ '12s'   , 12.66, false ],
			'seconds+usec' => [ '12.66s', 12.66 ],

			'minutes'      => [ '3m 0s'   , 3 * 60 ],
			'minutes-usec' => [ '3m 0s'   , 3 * 60 + 0.1201, false],
			'minutes+usec' => [ '3m 0.12s', 3 * 60 + 0.1201],

			'hours'      => [ '1g 34m 45s'    , 3600 + 34 * 60 + 45 ],
			'hours-usec' => [ '1g 34m 45s'    , 3600 + 34 * 60 + 45 + 0.987, false ],
			'hours+usec' => [ '1g 34m 45.987s', 3600 + 34 * 60 + 45 + 0.987 ],

			'days'      => [ '2d 11g 19m 36s'  , 2 * 86400 + 11 * 3600 + 19 * 60 + 36 ],
			'days-usec' => [ '2d 11g 19m 36s'  , 2 * 86400 + 11 * 3600 + 19 * 60 + 36 + 0.4001, false ],
			'days+usec' => [ '2d 11g 19m 36.4s', 2 * 86400 + 11 * 3600 + 19 * 60 + 36 + 0.4001 ],
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
