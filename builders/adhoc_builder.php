<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];
$returnString = null;

//Retrieve generic blocks
$imageBlock = file_get_contents("../sites/_defaults/image.html");
$imageBlock = str_replace('http://img2.email2inbox.co.uk/editor/fullwidth.jpg', 'http://placehold.it/600x338', $imageBlock);
$emptySpacer = file_get_contents('../sites/_defaults/basic_spacer.html');

foreach(glob('../sites/*/templates/*_branded.html') as $filename){
  $template = file_get_contents($filename);
  $brand = preg_replace('/.*?\/.*?\/(.*?)\/.*/', '$1', $filename);

  preg_match('/"contentBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
  $color = $matches[1][0];
  $textColor = textColor($color);

  //Prep Heading
  $heading = file_get_contents('../sites/' . $brand . '/bespoke_blocks/' . $brand . '_heading.html');
  $heading = str_replace('align="left"', 'align="center"', $heading);
  $heading = marginBuilder($heading);
  preg_match('/<h1.*?style="(.*?)".*?>/', $heading, $matches, PREG_OFFSET_CAPTURE);
  $headingStyle = $matches[1][0];
  $headingStyleNew = $headingStyle . ' font-size: 24px;';
  $heading = str_replace($headingStyle, $headingStyleNew, $heading);

  //Prep Spacer
  $largeSpacer = str_replace('<td align="center" height="20" valign="middle"></td>', '<td align="center" height="40" valign="middle"></td>', $emptySpacer);

  //Prep All Text
  $basicText = file_get_contents('../sites/_defaults/text.html');
  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial; line-height: 130%;"';
  $basicText = str_replace('<td class="text" align="left" valign="0">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $basicText);
  $basicText = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $basicText);
  $basicText = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $basicText);

  //Build template html
  $insert = $imageBlock . $emptySpacer . $heading . $emptySpacer . $basicText . $largeSpacer;

  $column = file_get_contents("../sites/_defaults/1column.html");
  $column = preg_replace('/<!-- Insert -->/', $insert, $column);

  $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
  $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

  $append = "adhoc";
  $path = "pre_made";
  $save = $saveToFile;

  sendToFile($output,$path, $append, $brand, '.html', $save);

  //print_r($output);

  $returnString .= $output;
}

echo $returnString;

 ?>
