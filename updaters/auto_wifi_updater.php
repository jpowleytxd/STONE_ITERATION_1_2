<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];

$sql = null;
foreach (glob("../pre_made/*/auto_wifi_uk.html") as $filename) {
  $temp = file_get_contents($filename);
  $brand = preg_replace('/.*?\/.*?\/(.*?)\/.*/', '$1', $filename);

  //Remove comment tags
  $temp = preg_replace('/\{.*?\}/ms', '', $temp);
  // $temp = preg_replace('/\<!--.*?\-->/ms', '', $temp);
  $temp = preg_replace('/<!-- VenueStart -->/ms', '', $temp);
  $temp = preg_replace('/<!-- VenueEnd -->/ms', '', $temp);
  $temp = preg_replace('/<!-- BrandedStart -->/ms', '', $temp);
  $temp = preg_replace('/<!-- BrandedEnd -->/ms', '', $temp);
  $temp = preg_replace('/\'/ms', '\\\'', $temp);
  $temp = removeWhiteSpace($temp);

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
    $brandID = $row[8];
    $venueID = $row[5];
    $veID = $row[6];
    $accounts = $row[7];
    // $template_id = $row[8];
  }

  //Get Email content
  $type = "Auto Welcome - Bounce back for first WIFI sign in";
  $email = 'Auto Welcome - Bounce back for first WIFI sign in';
  $wifiRows = null;
  $initialQuery = "SELECT * FROM `copy_iteration1_all` WHERE `email` = '" . $email . "'";
  $rows = databaseQuery($initialQuery);
  foreach($rows as $key => $row){
    $wifiRows = $row;
    break;
  }
  $subject = null;
  $voucher = null;
  $preHeader = null;
  foreach($wifiRows as $key => $row){
    $subject = $wifiRows[3];
    $preHeader = str_replace("'", "\'", $wifiRows[4]);
    $voucher = '0';
  }

  $voucher = 1;

  $name = $upperCaseName . ' - T:' . date("Ymd") . ' - ' . $type;
  $settings = buildTemplateSettings($name, $preHeader, $subject, $brandID, $profileID);

  //Naming variables
  $type = "Auto WIFI UK";
  $name = $upperCaseName . ' - T:20170329 - ' . $type;

  //Build SQL statements
  $sql .= "UPDATE `tbl_email_templates` SET `template_html` = '" . $temp . "', `template_ve_settings` = '" . $settings . "'
          WHERE `template_account_id` = '1222' AND `template_title` = '" . $name . "';\n";
}

$append = "auto_wifi_uk_update";
$path = "updates";
$save = $saveToFile;

sendToFile($sql,$path, $append, $brand, '.sql', $save);

// print_r($sql);

echo $sql;

 ?>
