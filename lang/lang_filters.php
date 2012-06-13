<?php

function lang_fr_filter_date($timestamp) {
  $timestamp = (int)$timestamp;
  
  $months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin',
    'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');

  $month_id = (int)strftime("%m", $timestamp);
  $month = $months[$month_id - 1];

  return strftime("%e $month %Y", $timestamp);
}

function lang_fr_filter_money($cents) {
  $cents = (int)$cents;
  
  $dollars = (int)($cents / 100);
  $cents = $cents % 100;

  if ($cents != 0) {
    return sprintf("%d.%02d $", $dollars, $cents);
  } else {
    return sprintf("%d $", $dollars);
  }
}

function lang_en_filter_date($timestamp) {
  $timestamp = (int)$timestamp;
  
  $months = array('January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December');

  $month_id = (int)strftime("%m", $timestamp);
  $month = $months[$month_id - 1];

  $day = (int)strftime("%e", $timestamp);

  if ($day == 1 || $day == 21 || $day == 31) {
    $day .= 'st';
  } else if ($day == 2 || $day == 22) {
    $day .= 'nd';
  } else if ($day == 3 || $day == 23) {
    $day .= 'rd';
  } else {
    $day .= 'th';
  }

  return strftime("$month $day, %Y", $timestamp);
}

function lang_en_filter_money($cents) {
  $cents = (int)$cents;
  
  $dollars = (int)($cents / 100);
  $cents = $cents % 100;

  if ($cents != 0) {
    return sprintf("$ %d.%02d", $dollars, $cents);
  } else {
    return sprintf("$ %d", $dollars);
  }
}


?>