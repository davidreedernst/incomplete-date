<?php

class IncompleteDate 
{
  private static function formatParts($format) {
    $formatparts = array('prelude' => '', 'postlude' => '');
    if (preg_match('/^(\W+)(\w.*)$/', $format, $matches)) {
      $formatparts['prelude'] = $matches[1];
      $format = $matches[2];
    }
    if (preg_match('/^(\w.*\w)(\W+)$/', $format, $matches)) {
      $format = $matches[1];
      $formatparts['postlude'] = $matches[2];
    }
    $formatparts['format'] = $format;
    return $formatparts;
  }
  
  private static function yearOnly($format) {
    $formatparts = self::formatParts($format);
    $formatparts['format'] = preg_replace('/[^LYy]/', '', $formatparts['format']);
    return $formatparts['prelude'] . $formatparts['format'] . $formatparts['postlude'];
  }

  private static function excludeDay($format) {
    $formatparts = self::formatParts($format);
    // get rid of all symbols that represent day of month/week
    foreach (array('d','D','j','l','N','S','w','z') as $dayltr) {
      $formatparts['format'] = preg_replace("/ $dayltr([^\w\s])/", '$1', $formatparts['format']);
      $formatparts['format'] = preg_replace("/$dayltr/", '', $formatparts['format']);
    }
    // get rid of punctuation at the beginning and the end
    $formatparts['format'] = preg_replace("/^\s*[^\w\s]*\s*/", '', $formatparts['format']);
    $formatparts['format'] = preg_replace("/\s*[^\w\s]*\s*$/", '', $formatparts['format']);
    return $formatparts['prelude'] . $formatparts['format'] . $formatparts['postlude'];
  }

  public static function format($datestr, $fullformat, $yearmonthformat = null, $yearonlyformat = null) {
    if (preg_match('/^(\d\d\d\d)\D?(\d\d)\D?(\d\d)$/', $datestr, $matches)) {
      $year = $matches[1];
      $mon = $matches[2];
      $day = $matches[3];
      if ($day == '00') {
	if ($mon == '00') {
	  $format = empty($yearonlyformat) ? self::yearOnly($fullformat) : $yearonlyformat;
	  $fakedate = "$year-11-11";
	  return date($format, strtotime($fakedate));
	} else {
	  $format = empty($yearmonthformat) ? self::excludeDay($fullformat) : $yearmonthformat;
	  $fakedate = "$year-$mon-11";
	  return date($format, strtotime($fakedate));
	}
      } else {
	return date($fullformat, strtotime($datestr));
      }
    } else {
      return '';
    }
  }

  private static function formatTimeStampOrNull($format, $ts) {
    if ($ts === false) {
      return null;
    } else {
      return date($format, $ts);        
    }
  }

  private static function formatTimeStringOrNull($format, $str) {
    $ts = strtotime($str);
    return self::formatTimeStampOrNull($format, $ts);
  }

  private static function yearMonthNameToISODate($sentyear, $sentmonthname) {
    $fakestr = $sentmonthname . ' 10, ' . $sentyear;
    return self::formatTimeStringOrNull("Y-m-00", $fakestr);
  }
    
  private static function yearMonthNumToISODate($sentyear, $sentmonthnum) {
    if (strlen($sentmonthnum) == 1) {
      $sentmonthnum = '0' . $sentmonthnum;
    }
    $fakestr = "$sentyear-$sentmonthnum-10"; 
    $ts = strtotime($fakestr);
    return date("Y-m-00", $ts);
  }

  public static function strToISODate($sentstr) {
    $str = trim($sentstr);
    /* if the string is empty, don't bother running all the tests, just return null */
    if (empty($str)) {
      return null;
    }

    if (preg_match('/^(\d\d\d\d)$/', $str, $matches)) {
      return $matches[1] . '-00-00';
    } elseif (preg_match('/^([a-z]+)\W+(\d\d\d\d)$/i', $str, $matches)) { 
      return self::yearMonthNameToISODate($matches[2], $matches[1]);
    } elseif (preg_match('/^(\d\d\d\d)\W+([a-z]+)$/i', $str, $matches)) {
      return self::yearMonthNameToISODate($matches[1], $matches[2]);
    } elseif (preg_match('/^(\d\d?)\W+(\d\d\d\d)$/i', $str, $matches)) {
      return self::yearMonthNumToISODate($matches[2], $matches[1]);
    } elseif (preg_match('/^(\d\d\d\d)\W+(\d\d?)$/i', $str, $matches)) {
      return self::yearMonthNumToISODate($matches[1], $matches[2]);
    }
    return self::formatTimeStringOrNull("Y-m-d", $str);
  }

}

?>
