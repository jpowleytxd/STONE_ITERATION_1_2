<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];
$returnString = null;

//Sort through venues whether they have the banner image or not
function bannerImage($brand){
  $inclusions =  array('edwards', 'flares', 'luna', 'missoula', 'popworld', 'rosies','via');
  $alternates = array('beduin', 'colors');

  if(in_array($brand, $inclusions)){
    return 'http://img2.email2inbox.co.uk/2016/stonegate/templates/placeholder.jpg';
  } else if(in_array($brand, $alternates)){
    return getHeroImageURL($brand);
  } else{
    return null;
  }
}


foreach(glob("../sites/*/templates/*_branded.html") as $filename){
  $template = file_get_contents($filename);
  $brand = preg_replace('/.*?\/.*?\/(.*?)\/.*/', '$1', $filename);

  $email = 'Forgotten Password';

  //Get copy data
  $passwordRows = null;
  $initialQuery = "SELECT * FROM `copy_iteration1_all` WHERE `email` = '" . $email. "'";
  $rows = databaseQuery($initialQuery);
  foreach($rows as $key => $row){
    $passwordRows = $row;
    break;
  }

  //Get Background Color
  preg_match('/"contentBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
  $color = $matches[1][0];
  $textColor = textColor($color);

  //Prep Heading
  $heading = file_get_contents('../sites/' . $brand . '/bespoke_blocks/' . $brand . '_heading.html');
  $heading = str_replace('Heading goes here', $passwordRows[4], $heading);
  $heading = str_replace('align="left"', 'align="center"', $heading);
  $heading = marginBuilder($heading);
  preg_match('/<h1.*?style="(.*?)".*?>/', $heading, $matches, PREG_OFFSET_CAPTURE);
  $headingStyle = $matches[1][0];
  $headingStyleNew = $headingStyle . ' font-size: 24px;';
  $heading = str_replace($headingStyle, $headingStyleNew, $heading);

  //Prep Image
  $image = file_get_contents('../sites/_defaults/image.html');
  $promo = $image;
  $imageInclude = bannerImage($brand);
  if($imageInclude !== null){
    $image = str_replace('http://img2.email2inbox.co.uk/editor/fullwidth.jpg', $imageInclude, $image);
  } else{
    $image = '';
  }

  //Prep Spacers
  $emptySpacer = file_get_contents('../sites/_defaults/basic_spacer.html');
  $largeSpacer = str_replace('<td align="center" height="20" valign="middle"></td>', '<td align="center" height="40" valign="middle"></td>', $emptySpacer);

  //Prep All Text
  $textOne = file_get_contents('../sites/_defaults/text.html');

  //Prep Text One
  $passwordRows[5] = str_replace('"', '', $passwordRows[5]);
  $textOne = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $passwordRows[5], $textOne);
  $styleInsert = 'style="Margin-top: 15px; Margin-bottom: 15px;"';
  $textOne = preg_replace('/##(.+?)##/m', '<p ' . $styleInsert . '>$1</p>', $textOne);
  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';
  $textOne = str_replace('<td class="text" align="left" valign="0">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textOne);
  $textOne = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textOne);
  $textOne = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textOne);
  $link = '<a href="http://stonegateemail.co.uk/$dynamic3$/website" style="color: ' . $textColor . ';"><span style="text-decoration: underline; color: ' . $textColor . ';">Click here</span></a>';
  $textOne = str_replace('Click here', $link, $textOne);

  $insert = $image . $largeSpacer . $heading . $emptySpacer . $textOne . $largeSpacer;

  $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
  $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

  $append = "password_reset_brand";
  $path = "pre_made";
  $save = $saveToFile;

  sendToFile($output, $path, $append, $brand, '.html', $save);

  // print_r($output);
  $returnString .= $output;
}
echo $returnString;

 ?>
