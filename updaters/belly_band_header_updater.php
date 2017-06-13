<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];

$sql = null;
foreach (glob("../pre_made/*/belly_band.html") as $filename) {
  $temp = file_get_contents($filename);
  $brand = preg_replace('/.*?\/.*?\/(.*?)\/.*/', '$1', $filename);

  //Remove comment tags
  $temp = preg_replace('/\{.*?\}/ms', '', $temp);
  $temp = preg_replace('/\<!--.*?\-->/ms', '', $temp);

  //Base 64 encode template
  $temp = base64_encode($temp);

  //Brand to uppercase
  $upperCaseName = str_replace('_', ' ', $brand);
  $upperCaseName = ucwords($upperCaseName);

  //Get account data
  $initialQuery = 'SELECT * FROM account_data WHERE brand = "' . $brand . '"';
  $rows = databaseQuery($initialQuery);
  $accountID = null;
  $profileID = null;
  $brandID = null;
  $venueID = null;
  $veID = null;
  $accounts = null;
  foreach($rows as $key => $row){
    $accountID = $row[2];
    $profileID = $row[3];
    $brandID = $row[4];
    $venueID = $row[5];
    $veID = $row[6];
    $accounts = $row[7];
  }

  //Naming variables
  $type = 'Belly Band Template';
  $name = $upperCaseName . ' ' . $type;

  //Build SQL statements
  if(($accounts === 'venue') || ($accounts === 'ind')){
    $sql .= "UPDATE `tbl_template_editor_templates` SET `template_html` = '" . $temp . "'
            WHERE `template_account_id` = '1222' AND `template_name` = '" . $name . "' AND `template_type` = 'VENUE: " . $type . "';\n";
  } else if($accounts === 'both'){
    $sql .= "UPDATE `tbl_template_editor_templates` SET `template_html` = '" . $temp . "'
            WHERE `template_account_id` = '1222' AND `template_name` = '" . $name . "' AND `template_type` = 'BRAND: " . $type . "';\n";
    $sql .= "UPDATE `tbl_template_editor_templates` SET `template_html` = '" . $temp . "'
            WHERE `template_account_id` = '1222' AND `template_name` = '" . $name . "' AND `template_type` = 'VENUE: " . $type . "';\n";
  }
}

$append = "belly_band_update_head";
$path = "updates";
$save = $saveToFile;

sendToFile($sql,$path, $append, $brand, '.sql', $save);

// print_r($sql);

echo $sql;

 ?>
