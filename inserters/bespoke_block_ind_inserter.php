<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];

$sql = null;
foreach (glob("../sites/*/bespoke_blocks/*.html") as $filename){
  $temp = file_get_contents($filename);

  //get file names
  preg_match_all('/.*?\/.*?\/(.*?)\/.*?\/(.*?).html/', $filename, $matches);
  $brand = $matches[1][0];
  $type = $matches[2][0];
  $type = str_replace($brand . '_', '', $type);

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

  //Block type lower Case name
  $lowerName = $type;
  $lowerName = str_replace('col', 'column', $lowerName);
  $lowerName = 'stonegate_' . $brand . '_' .  $lowerName;

  //Block type Upper Case Name
  $type = str_replace('_', ' ', $type);
  $type = str_replace('col', 'column', $type);
  $type = ucwords($type);

  //Define VE Block name
  $blockName = $upperCaseName . ' ' . $type;

  //Build SQL Statements
  $sql .= "INSERT INTO `tbl_template_editor_blocks` (`block_name`, `block_account_id`, `block_type_id`, `block_type`, `block_html`, `block_category`) VALUES
          ('" . $blockName . "', '" . $veID . "', '" . $lowerName . "', 'bespoke', '" . $temp . "',
          '" . $upperCaseName . "');\n";
}

$append = "bespoke_block_insert_ind";
$path = "inserts";
$save = $saveToFile;

sendToFile($sql,$path, $append, $brand, '.sql', $save);

// print_r($sql);

echo $sql;
 ?>
