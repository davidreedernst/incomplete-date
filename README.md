# incomplete-date
This is a small PHP class providing static methods similar to `date($format, $timestamp)` and `strtotime($timestring)`, 
but which allow for MySQL-compatible incomplete dates (year only &amp; year + month). 

The MySQL DATE format has the ability to store incomplete dates. Use zeros to represent unknown parts, ie 'YYYY-MM-00' or 'YYYY-00-00'. This is useful if a user knows only the year - or the year plus the month - of a particular event. Note that, depending on your version of MySQL, you may need to disable Strict Mode to allow this to work. See the [MySQL Manual](https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-strict) for more details.

Meanwhile, PHP has some cool functions for handling date parsing and formatting. In particular, 
`strtotime()` does a neat job of parsing natural (English) language text into a UNIX timestamp; and 
`date()` makes it easy to convert a timestamp into just about any human-friendly format. 

However, these PHP functions don't work with MySQL-style incomplete dates. 

```php
date('Y', strtotime('11 January 1952')) => "1952"
date('Y', strtotime('1952')) => "2017"  // or whatever is the current year, because strtotime returns null
```

This class has two public methods. The test file gives lots of examples of how to use them.

## IncompleteDate::format($isodatestring, $format)

Format a date. The (possibly incomplete) date must be in ISO 8601 Format - YYYY-MM-DD - which is the standard output format for MySQL date columns. The format is any format that would work with the PHP `date()` function.  If the format expects stuff that isn't in the incomplete date, this method will try to guess the best format based on the given format and how much of the date is complete. 

```php
$format = 'F j, Y';
$this->assertEquals('January 11, 1952', IncompleteDate::format('1952-01-11', $format));
$this->assertEquals('January, 1951', IncompleteDate::format('1951-01-00', $format));
$this->assertEquals('1950', IncompleteDate::format('1950-00-00', $format));
```

## IncompleteDate::format($isodatestring, $fullformat, $yearmonthformat, $yearonlyformat)

If you want complete control over how the incomplete dates get formatted, you can submit multiple formats. 

* The first will be used if Year, Month and Day of the month are all known. 
* The second will be used if Year, Month are known, but not the Day of the month 
* The third will be used if only the Year is known. 

```php
$format1 = 'F j, Y';
$format2 = 'F Y';
$format3 = 'Y!';
$this->assertEquals('1950!', IncompleteDate::format('1950-00-00', $format1, $format2, $format3));
$this->assertEquals('January 1951', IncompleteDate::format('1951-01-00', $format1, $format2, $format3));
$this->assertEquals('January 11, 1952', IncompleteDate::format('1952-01-11', $format1, $format2, $format3));
```

## IncompleteDate::strToISODate($string)

Like the php `strtotime()` function, this method expects to be given a string containing an English date format 
and will try to parse that format into an ISO 8601 date string, suitable for saving into a MySQL DATE field.  

```php
$this->assertEquals('1952-01-11', IncompleteDate::strToISODate('January 11, 1952'));
$this->assertEquals('1952-01-00', IncompleteDate::strToISODate('January, 1952'));
$this->assertEquals('1952-01-00', IncompleteDate::strToISODate('1952 January'));
$this->assertEquals('1952-01-00', IncompleteDate::strToISODate('01/1952'));
$this->assertEquals('1952-01-00', IncompleteDate::strToISODate('1952-01'));
$this->assertEquals('1952-00-00', IncompleteDate::strToISODate('1952'));
```

It uses `strtotime()` behind the scenes, so it's abilities and failings largely parallel those of the standard 
PHP function. However, unlike `strtotime()` this method returns `null` if the string cannot be parsed as a date. 
Depending on your interface to your MySQL database, you may be able to save this safely: PHP `null` becomes MySQL `NULL`.  

```php
$this->assertNull(IncompleteDate::strToISODate('Notamonthname 11, 1952'));
```
