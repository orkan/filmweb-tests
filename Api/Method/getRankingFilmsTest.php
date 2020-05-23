<?php
use Orkan\Filmweb\Api\Method\getRankingFilms;
use Orkan\Filmweb\Api\Method\FilmGenre;
use PHPUnit\Framework\TestCase;

class getRankingFilmsTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getRankingFilms() )::TYPE );
	}

	public function test_format()
	{
		/* @formatter:off */
		$args = array(
			getRankingFilms::GENRE => FilmGenre::ANIME,
		);
		/* @formatter:on */

		$expected = sprintf( 'getRankingFilms [%s, %u]', 'top_100_films_world', $args[getRankingFilms::GENRE] );
		$result = ( new getRankingFilms() )->format( $args );

		$this->assertSame( $expected, $result );
	}
}
