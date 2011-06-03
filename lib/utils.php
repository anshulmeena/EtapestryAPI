<?php

/**
 * Take a United States formatted date (mm/dd/yyyy) and
 * convert it into a date/time string that NuSoap requires.
 */
function formatDateAsDateTimeString($dateStr)
{
  if ($dateStr == null || $dateStr == "") return "";
  if (substr_count($dateStr, "/") != 2) return "[Invalid Date: $dateStr]";

  $separator1 = stripos($dateStr, "/");
  $separator2 = stripos($dateStr, "/", $separator1 + 1);

  $month = substr($dateStr, 0, $separator1);
  $day = substr($dateStr, $separator1 + 1, $separator2 - $separator1 - 1);
  $year = substr($dateStr, $separator2 + 1);

  return ($month > 0 && $day > 0 && $year > 0) ? date(DATE_ATOM, mktime(0, 0, 0, $month, $day, $year)) : "[Invalid Date: $dateStr]";
}

?>