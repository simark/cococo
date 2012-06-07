<?php
  /**
   * Command line utility used to generate lang.php from lang.csv.
   *
   * lang.csv is the easily human readable and editable file containing the
   * localized strings.
   *
   * lang.php contains the same strings, but ready to use in cococo.
   *
   * @author  Simon Marchi <simon.marchi@polymtl.ca>
   */

  $g_bin = null;
  $g_input_filename = null;
  $g_output_filename = null;

  function usage($bin) {
    printf("Usage:   %s [input file] [output file]\n", $bin);
    printf("Example: %s   lang.csv    lang.php\n", $bin);
    exit(1);
  }

  function parse_args($argc, $argv) {
    global $g_bin, $g_input_filename, $g_output_filename;

    $g_bin = $argv[0];

    if ($argc < 2) {
      printf("Missing input filename\n");
      return false;
    }

    $g_input_filename = $argv[1];

    if ($argc < 3) {
      printf("Missing output filename\n");
      return false;
    }

    $g_output_filename = $argv[2];

    return true;
  }

  function eat_input($input_filename) {
    $input = fopen($input_filename, "r");

    if (!$input) {
      printf("Can't open $input_filename\n");
      return false;
    }
    
    $langs = fgetcsv($input);
    // Remove the 'key' column
    array_shift($langs);

    printf("Loaded %d languages\n", count($langs));

    $strings = array();

    while ($line = fgetcsv($input)) {
      $key = array_shift($line);

      $word = array_combine($langs, $line);
      $strings[$key] = $word;
    }

    fclose($input);

    return $strings;
  }

  function vomit_output($output_filename, $strings) {
    $output = fopen($output_filename, "w");

    if (!$output) {
      printf("Can't open $output_filename\n");
      return false;
    }

    $tpl = <<<'LALALA'
<?php

$g_locales_strings = {table};

?>
LALALA;

    $table = var_export($strings, true);

    $res = str_replace('{table}', $table, $tpl);

    fwrite($output, $res);

    fclose($output);
    
    return true;
  }

  function main($argc, $argv) {
    global $g_bin, $g_input_filename, $g_output_filename;
    
    if (!parse_args($argc, $argv)) {
      printf("\n");
      usage($g_bin);
      return false;
    }

    $strings = eat_input($g_input_filename);
    if (!$strings) {
      return false;
    }

    if (!vomit_output($g_output_filename, $strings)) {
      return false;
    }

    return true;
  }

  return main($argc, $argv);
?>