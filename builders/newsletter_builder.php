<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];
$returnString = null;

foreach(glob('../sites/*/templates/*_branded.html') as $filename){
  $template = file_get_contents($filename);
  $brand = preg_replace('/.*?\/.*?\/(.*?)\/.*/', '$1', $filename);

  //One column setup
  $oneCol = file_get_contents('../sites/' . $brand . '\/bespoke_blocks\/' . $brand . '_1_col.html');
  $oneCol = preg_replace('/href="http:\/\/www\.abcd\.com\/"/', 'href="#"', $oneCol);
  $oneCol = preg_replace('/http:\/\/img2\.email2inbox\.co\.uk\/editor\/fullwidth\.jpg/', 'http://placehold.it/580x326', $oneCol);

  //Two column setup
  $twoCol = file_get_contents('../sites/' . $brand . '\/bespoke_blocks\/' . $brand . '_2_col.html');
  $twoCol = preg_replace('/href="http:\/\/www\.abcd\.com\/"/', 'href="#"', $twoCol);
  $twoCol = preg_replace('/http:\/\/img2\.email2inbox\.co\.uk\/editor\/fullwidth\.jpg/', 'http://placehold.it/280x158', $twoCol);

  //Content Insertion
  $search = '/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/';
  $output = preg_replace($search, '<!-- User Content: Main Content Start -->' . $oneCol . $twoCol . '<!-- User Content: Main Content End -->', $template);

  $append = "newsletter";
  $path ="pre_made";
  $save = $saveToFile;

  sendToFile($output, $path, $append, $brand, '.html', $save);

  // print_r($output);
  $returnString .= $output;
}
echo $returnString;
 ?>
