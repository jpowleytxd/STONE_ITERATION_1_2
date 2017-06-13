<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];

$sql = null;
foreach (glob("../pre_made/*/newsletter.html") as $filename) {
  $temp = file_get_contents($filename);
  $brand = preg_replace('/.*?\/.*?\/(.*?)\/.*/', '$1', $filename);

  //Remove comment tags
  $temp = preg_replace('/\{.*?\}/ms', '', $temp);
  // $temp = preg_replace('/\<!--.*?\-->/ms', '', $temp);
  $temp = preg_replace('/\'/ms', '\\\'', $temp);

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
  $type = 'Newsletter Template';
  $name = $upperCaseName . ' ' . $type;

  //Build SQL statements
  if($accounts === 'both'){
    $sql .= "INSERT INTO `tbl_template_editor_templates` (`template_account_id`, `template_name`, `template_subject`, `template_html`, `template_text`, `template_created_datetime`, `template_type`, `template_image`, `template_status`) VALUES
            ('" . $veID . "', 'BRAND: " . $name . "', NULL, '" . $temp . "',
            NULL, NULL, 'BRAND', NULL, '1');\n";
    $sql .= "INSERT INTO `tbl_template_editor_templates` (`template_account_id`, `template_name`, `template_subject`, `template_html`, `template_text`, `template_created_datetime`, `template_type`, `template_image`, `template_status`) VALUES
            ('" . $veID . "', 'VENUE: " . $name . "', NULL, '" . $temp . "',
            NULL, NULL, 'VENUE', NULL, '1');\n";
  } else if($accounts === 'ind'){
    $sql .= "INSERT INTO `tbl_template_editor_templates` (`template_account_id`, `template_name`, `template_subject`, `template_html`, `template_text`, `template_created_datetime`, `template_type`, `template_image`, `template_status`) VALUES
            ('" . $veID . "', '" . $name . "', NULL, '" . $temp . "',
            NULL, NULL, 'VENUE: " . $type . "', NULL, '1');\n";
  } else if($accounts === 'venue'){
    $sql .= "INSERT INTO `tbl_template_editor_templates` (`template_account_id`, `template_name`, `template_subject`, `template_html`, `template_text`, `template_created_datetime`, `template_type`, `template_image`, `template_status`) VALUES
            ('" . $veID . "', '" . $name . "', NULL, '" . $temp . "',
            NULL, NULL, 'VENUE', NULL, '1');\n";
  }
}

$append = "newsletter_insert_ind";
$path = "inserts";
$save = $saveToFile;

sendToFile($sql,$path, $append, $brand, '.sql', $save);

// print_r($sql);

echo $sql;

 ?>
