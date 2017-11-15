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
}

?>
