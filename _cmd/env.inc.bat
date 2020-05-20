@echo off

rem set PHPUNIT_CFG=-c _cfg\phpunit.xml
set PHPUNIT_BOOTSTRAP=--bootstrap ..\..\vendor\autoload.php
rem set PHPUNIT_COVERAGE_WHITELIST=--whitelist ..\..\src
set PHPUNIT_COVERAGE_DIR=..\_coverage
set PHPUNIT_COVERAGE=--coverage-html %PHPUNIT_COVERAGE_DIR%
