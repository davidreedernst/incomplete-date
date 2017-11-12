<?php

class IncompleteDate 
{
  private static function yearOnly($format) {
    return preg_replace('/[^lYy]/', '', $format);
  }

  private static function excludeDay($format) {
    // get rid of all symbols that represent day of month/week
    foreach (array('d','D','j','l','N','S','w','z') as $dayltr) {
      $format = preg_replace("/ $dayltr([^\w\s])/", '$1', $format);
      $format = preg_replace("/$dayltr/", '', $format);
    }
    // get rid of punctuation at the beginning and the end
    $format = preg_replace("/^\s*[^\w\s]*\s*/", '', $format);
    $format = preg_replace("/\s*[^\w\s]*\s*$/", '', $format);
    return $format;
  }

  public static function format($datestr, $format) {
    if (preg_match('/^(\d\d\d\d)\D?(\d\d)\D?(\d\d)$/', $datestr, $matches)) {
      $year = $matches[1];
      $mon = $matches[2];
      $day = $matches[3];
      if ($day == '00') {
	$format = self::excludeDay($format);
	if ($mon == '00') {
	  $format = self::yearOnly($format);
	  $fakedate = "$year-11-11";
	  return date($format, strtotime($fakedate));
	} else {
	  $fakedate = "$year-$mon-11";
	  return date($format, strtotime($fakedate));
	}
      } else {
	return date($format, strtotime($datestr));
      }
    } else {
      return '';
    }
  }
}

?>
