<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$sql = null;
foreach (glob("../pre_made/*/welcome_21_days_uk.html") as $filename){
  $temp = file_get_contents($filename);

  //Get brand and type names
  preg_match_all('/.*?\/.*?\/(.*?)\/(.*?).html/', $filename, $matches);
  $brand = $matches[1][0];
  $type = $matches[2][0];

  //Remove comment tags
  $temp = preg_replace('/\<!--.*?\-->/ms', '', $temp);
  $temp = preg_replace('/\{.*?\}/ms', '', $temp);
  $temp = str_replace('WHAT\'S', 'WHAT&apos;S', $temp);
  $temp = preg_replace('/\'/ms', '\\\'', $temp);
  $temp = removeWhiteSpace($temp);

  //Lookup id from table
  $initialQuery = 'SELECT ' . $type . ' FROM template_ids WHERE brand = "' . $brand . '"';
  $rows = databaseQuery($initialQuery);
  $templateID = null;
  foreach($rows as $row){
    $templateID = $row[0];
    break;
  }

  $sql .= "UPDATE tbl_email_templates SET `template_html` = '$temp' WHERE template_id = $templateID;\n";
}

$append = "welcome_3_uk_update";
$path = "updates";
$save = false;

sendToFile($sql,$path, $append, $brand, '.sql', $save);

print_r($sql);

?>
