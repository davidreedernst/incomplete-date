<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers Email
 */
final class IncompleteDateTest extends TestCase
{
  public function testFormatIncompleteDateY() {
    $format = 'Y';
    $this->assertEquals('1950', IncompleteDate::format('1950-00-00', $format));
    $this->assertEquals('1951', IncompleteDate::format('1951-01-00', $format));
    $this->assertEquals('1952', IncompleteDate::format('1952-01-11', $format));
  }

  public function testFormatIncompleteDateFY() {
    $format = 'F, Y';
    $this->assertEquals('1950', IncompleteDate::format('1950-00-00', $format));
    $this->assertEquals('January, 1951', IncompleteDate::format('1951-01-00', $format));
    $this->assertEquals('January, 1952', IncompleteDate::format('1952-01-11', $format));
  }

  public function testIncompleteDateFormatFjY() {
    $format = 'F j, Y';
    $this->assertEquals('1950', IncompleteDate::format('1950-00-00', $format));
    $this->assertEquals('January, 1951', IncompleteDate::format('1951-01-00', $format));
    $this->assertEquals('January 11, 1952', IncompleteDate::format('1952-01-11', $format));
  }

  public function testIncompleteDateFormatFdY() {
    $format = 'F d, Y';
    $this->assertEquals('1950', IncompleteDate::format('1950-00-00', $format));
    $this->assertEquals('January, 1951', IncompleteDate::format('1951-01-00', $format));
    $this->assertEquals('January 01, 1952', IncompleteDate::format('1952-01-01', $format));
  }

  public function testIncompleteDateFormatDFjY() {
    $format = 'D, F d, Y';
    $this->assertEquals('1950', IncompleteDate::format('1950-00-00', $format));
    $this->assertEquals('January, 1951', IncompleteDate::format('1951-01-00', $format));
    $this->assertEquals('Tue, January 01, 1952', IncompleteDate::format('1952-01-01', $format));
  }

  public function testIncompleteDateFormatYmd() {
    $format = 'Y-m-d';
    $this->assertEquals('1950', IncompleteDate::format('1950-00-00', $format));
    $this->assertEquals('1951-01', IncompleteDate::format('1951-01-00', $format));
    $this->assertEquals('1952-01-01', IncompleteDate::format('1952-01-01', $format));
  }

}