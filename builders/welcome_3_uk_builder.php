<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];
$returnString = null;

function bannerImage($brand){
  $exclusions =  array('charles_street', 'halfway_to_heaven');

  if(in_array($brand, $exclusions)){
    return null;
  } else{
    return getHeroImageURL($brand);
  }
}

//Welcome 2 UK
foreach(glob("../sites/*/templates/*_branded.html") as $filename){
  $template = file_get_contents($filename);
  $brand = preg_replace('/.*?\/.*?\/(.*?)\/.*/', '$1', $filename);

  //Get content
  $welcomeRows = null;
  $email ="Welcome 3 + 21 Days";
  $initialQuery = "SELECT * FROM `copy_iteration1_all` WHERE `email` = '" . $email . "'";
  $rows = databaseQuery($initialQuery);
  foreach($rows as $key => $row){
    $welcomeRows = $row;
    break;
  }

  //Get Background Color
  preg_match('/"contentBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
  $color = $matches[1][0];
  $textColor = textColor($color);

  //Prep Heading
  $heading = file_get_contents('../sites/' . $brand . '/bespoke_blocks/' . $brand . '_heading.html');
  $heading = str_replace('Heading goes here', $welcomeRows[4], $heading);
  $heading = str_replace('align="left"', 'align="center"', $heading);
  $heading = marginBuilder($heading);
  preg_match('/<h1.*?style="(.*?)".*?>/', $heading, $matches, PREG_OFFSET_CAPTURE);
  $headingStyle = $matches[1][0];
  $headingStyleNew = $headingStyle . ' font-size: 24px;';
  $heading = str_replace($headingStyle, $headingStyleNew, $heading);

  //Prep Images
  $image = file_get_contents('../sites/_defaults/image.html');
  $imageInclude = bannerImage($brand);#
  if($imageInclude !== null){
    $image = str_replace('http://img2.email2inbox.co.uk/editor/fullwidth.jpg', $imageInclude, $image);
  } else{
    $image = '';
  }

  //Prep Spacer
  $emptySpacer = file_get_contents('../sites/_defaults/basic_spacer.html');
  $largeSpacer = str_replace('<td align="center" height="20" valign="middle"></td>', '<td align="center" height="40" valign="middle"></td>', $emptySpacer);

  //Prep All Text
  $basicText = file_get_contents('../sites/_defaults/text.html');
  $textOne = $textTwo = $basicText;

  //Prep Text One
  $welcomeRows[5] = str_replace('"', '', $welcomeRows[5]);
  $textOne = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $welcomeRows[5], $textOne);
  $styleInsert = 'style="Margin-top: 15px; Margin-bottom: 15px;"';
  $textOne = preg_replace('/##(.+?)##/m', '<p ' . $styleInsert . '>$1</p>', $textOne);
  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';
  $textOne = str_replace('<td class="text" align="left" valign="0">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textOne);
  $textOne = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textOne);
  $textOne = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textOne);

  //Prep Voucher
  $voucherInstructions = $welcomeRows[10];
  $voucher = file_get_contents('../sites/' . $brand . '/bespoke_blocks/' . $brand . '_voucher.html');
  $voucherSearch = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
  $voucher = str_replace($voucherSearch, $voucherInstructions, $voucher);
  $voucher = str_replace('$vouchercode$', $welcomeRows[11], $voucher);
  $search = '/<!--valid_from_start-->\s*.*\s*.*\s*.*\s*.*\s*.*\s*.*<!--valid_from_end-->/';
  $voucher = preg_replace($search, '', $voucher);
  $search = '/<!--customer_start-->\s*.*\s*.*\s*.*\s*.*\s*.*\s*.*<!--customer_end-->/';
  $voucher = preg_replace($search, '', $voucher);
  $voucher = marginBuilder($voucher);

  //Prep Text Two
  $welcomeRows[8] = str_replace('"', '', $welcomeRows[8]);
  $textTwo = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $welcomeRows[8], $textTwo);
  $styleInsert = 'style="Margin-top: 15px; Margin-bottom: 15px;"';
  $textTwo = preg_replace('/##(.+?)##/m', '<p ' . $styleInsert . '>$1</p>', $textTwo);
  $linkInsert = '<a href="http://stonegateemail.co.uk/$dynamic3$/website" style="color: ' . $textColor . '"><span style="text-decoration: underline; color: ' . $textColor . ';">click here</span></a>';
  $textTwo = str_replace('click here', $linkInsert, $textTwo);
  $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';
  $textTwo = str_replace('<td class="text" align="left" valign="0">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textTwo);
  $textTwo = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textTwo);
  $textTwo = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textTwo);

  $color = $matches[1][0];
  $textColor = textColor($color);

  $terms = termsBuilder($welcomeRows[9]);
  $styleInsert = 'style="font-size: 11px; color: ' . $textColor . '"';
  $terms = preg_replace('/<td valign="top">/', '<td valign="top" align="center" ' . $styleInsert . '>', $terms);

  $insert = $image . $largeSpacer . $heading . $emptySpacer . $textOne .  $largeSpacer . $voucher . $largeSpacer . $textTwo . $largeSpacer;


  $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
  $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

  $search = "/<!-- terms insert -->/";
  $output = preg_replace($search, $terms, $output);

  $append = "welcome_21_days_uk";
  $path = "pre_made";
  $save = $saveToFile;

  sendToFile($output, $path, $append, $brand, '.html', $save);

  // print_r($output);
  $returnString .= $output;
}
echo $returnString;

 ?>
