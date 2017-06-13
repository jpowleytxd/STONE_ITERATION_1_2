<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];
$returnString = null;

//Load default image block
$imageBlock = file_get_contents('../sites/_defaults/image.html');
$imageBlock = str_replace('http://img2.email2inbox.co.uk/editor/fullwidth.jpg', 'http://img2.email2inbox.co.uk/2016/stonegate/templates/eb_placeholder.jpg', $imageBlock);

//Loop for all branded templates
foreach(glob('../sites/*/templates/*_branded.html') as $filename){
  $template = file_get_contents($filename);
  $brand = preg_replace('/.*?\/.*?\/(.*?)\/.*/', '$1', $filename);

  //Insert content
  $search = '/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/';
  $output = preg_replace($search, '<!-- User Content: Main Content Start -->' . $imageBlock . '<!-- User Content: Main Content End -->', $template);

  $append = 'belly_band';
  $path = "pre_made";
  $save = $saveToFile;

  sendToFile($output, $path, $append, $brand, '.html', $save);

  // print_r($output);
  $returnString .= $output;
}
echo $returnString;

 ?>
