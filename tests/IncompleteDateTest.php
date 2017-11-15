<?php

use PHPUnit\Framework\TestCase;

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

  public function testIncompleteDateFormaty() {
    $format = 'y';
    $this->assertEquals('50', IncompleteDate::format('1950-00-00', $format));
    $this->assertEquals('51', IncompleteDate::format('1951-01-00', $format));
    $this->assertEquals('52', IncompleteDate::format('1952-01-01', $format));
  }

  public function testIncompleteDateFormatl() {
    $format = 'L';
    $this->assertEquals('0', IncompleteDate::format('1950-00-00', $format));
    $this->assertEquals('0', IncompleteDate::format('1951-01-00', $format));
    $this->assertEquals('1', IncompleteDate::format('1952-01-01', $format));
  }

  public function testIncompleteDateFormatFjYetc() {
    $format = '...F j, Y...';
    $this->assertEquals('...1950...', IncompleteDate::format('1950-00-00', $format));
    $this->assertEquals('...January, 1951...', IncompleteDate::format('1951-01-00', $format));
    $this->assertEquals('...January 11, 1952...', IncompleteDate::format('1952-01-11', $format));
  }

  public function testIncompleteDateFormatFjYFYY() {
    $format1 = 'F j, Y';
    $format2 = 'F Y';
    $format3 = 'Y!';
    $this->assertEquals('1950!', IncompleteDate::format('1950-00-00', $format1, $format2, $format3));
    $this->assertEquals('January 1951', IncompleteDate::format('1951-01-00', $format1, $format2, $format3));
    $this->assertEquals('January 11, 1952', IncompleteDate::format('1952-01-11', $format1, $format2, $format3));
  }

  public function testStrToISODate() {
    $this->assertEquals('1952-01-11', IncompleteDate::strToISODate('January 11, 1952'));
    $this->assertEquals('1952-01-11', IncompleteDate::strToISODate('Jan 11, 1952'));
    $this->assertEquals('1952-01-11', IncompleteDate::strToISODate('Jan 11 1952'));
    /* surprisingly, this doesn't work:   */
    /* echo date('F j, Y', strtotime('11 January, 1952')); => January 11, 2017 */
    /* $this->assertEquals('1952-01-11', IncompleteDate::strToISODate('11 January, 1952')); */
    /* but without the comma it does work */
    $this->assertEquals('1952-01-11', IncompleteDate::strToISODate('11 January 1952'));
    $this->assertEquals('1952-01-11', IncompleteDate::strToISODate('11 Jan 1952'));
    $this->assertEquals('1952-01-11', IncompleteDate::strToISODate('1952-01-11'));
    $this->assertEquals('1952-01-00', IncompleteDate::strToISODate('January, 1952'));
    $this->assertEquals('1952-01-00', IncompleteDate::strToISODate('January 1952'));
    $this->assertEquals('1952-01-00', IncompleteDate::strToISODate('1952, January'));
    $this->assertEquals('1952-01-00', IncompleteDate::strToISODate('1952 January'));
    $this->assertEquals('1952-01-00', IncompleteDate::strToISODate('01/1952'));
    $this->assertEquals('1952-01-00', IncompleteDate::strToISODate('1952-01'));
    $this->assertEquals('1952-01-00', IncompleteDate::strToISODate('1952/1'));
    $this->assertEquals('1952-00-00', IncompleteDate::strToISODate('1952'));
  }

  public function testStrToISODateEmpty() {
    $this->assertNull(IncompleteDate::strToISODate(''));
  }

  public function testStrToISODateBadMonth() {
    $this->assertNull(IncompleteDate::strToISODate('Notamonthname, 1952'));
    $this->assertNull(IncompleteDate::strToISODate('Notamonthname 11, 1952'));
  }

}
