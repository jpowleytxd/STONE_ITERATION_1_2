<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];
$returnString = null;

for($i = 1; $i <= 2; $i++){
  foreach(glob("../sites/*/templates/*_branded.html") as $filename){
    $template = file_get_contents($filename);
    $brand = preg_replace('/.*?\/.*?\/(.*?)\/.*/', '$1', $filename);

    $autoRows = null;
    $email;
    if($i === 1){
      $email ="Auto Welcome - Immediate";
    } else if($i === 2){
      $email ="Auto Welcome  - Immediate (Scot)";
    }

    //Get copy data
    $initialQuery = "SELECT * FROM `copy_iteration1_all` WHERE `email` = '" . $email . "'";
    $rows = databaseQuery($initialQuery);
    foreach($rows as $key => $row){
      $autoRows = $row;
      break;
    }

    //Get Background Color
    preg_match('/"contentBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
    $color = $matches[1][0];
    $textColor = textColor($color);

    //Prep Heading
    $heading = file_get_contents('../sites/' . $brand . '/bespoke_blocks/' . $brand . '_heading.html');
    $heading = str_replace('Heading goes here', $autoRows[4], $heading);
    $heading = str_replace('align="left"', 'align="center"', $heading);
    $heading = marginBuilder($heading);
    preg_match('/<h1.*?style="(.*?)".*?>/', $heading, $matches, PREG_OFFSET_CAPTURE);
    $headingStyle = $matches[1][0];
    $headingStyleNew = $headingStyle . ' font-size: 24px;';
    $heading = str_replace($headingStyle, $headingStyleNew, $heading);

    //Prep Images
    $image = file_get_contents('../sites/_defaults/image.html');
    $image = str_replace('http://img2.email2inbox.co.uk/editor/fullwidth.jpg', getHeroImageURL($brand), $image);

    //Prep Spacer
    $emptySpacer = file_get_contents('../sites/_defaults/basic_spacer.html');
    $largeSpacer = str_replace('<td align="center" height="20" valign="middle"></td>', '<td align="center" height="40" valign="middle"></td>', $emptySpacer);
    $lineSpacer = lineSpacerBuild($brand);

    //Prep All Text
    $basicText = file_get_contents('../sites/_defaults/text.html');
    $textOne = $textTwo = $basicText;

    //Prep Text One
    $autoRows[5] = str_replace('"', '', $autoRows[5]);
    $textOne = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $autoRows[5], $textOne);
    $styleInsert = 'style="Margin-top: 15px; Margin-bottom: 15px;"';
    $textOne = preg_replace('/##(.+?)##/m', '<p ' . $styleInsert . '>$1</p>', $textOne);
    $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';
    $textOne = str_replace('<td class="text" align="left" valign="0">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textOne);
    $textOne = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textOne);
    $textOne = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textOne);

    $insert;
    $append;

    //UK Scot difference builder
    if($i === 1){
      //Prep Text Two
      $autoRows[8] = str_replace('"', '', $autoRows[8]);
      $textTwo = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $autoRows[8], $textTwo);
      $styleInsert = 'style="Margin-top: 15px; Margin-bottom: 15px;"';
      $textTwo = preg_replace('/##(.+?)##/m', '<p ' . $styleInsert . '>$1</p>', $textTwo);

      $styleInsert = 'style="color: ' . $textColor . ';font-weight: bold; font-family: arial;"';

      $textTwo = str_replace('<td class="text" align="left" valign="0">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textTwo);
      $textTwo = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textTwo);
      $textTwo = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textTwo);

      $insert = $image . $largeSpacer . $heading . $emptySpacer . $textOne . $largeSpacer . $lineSpacer . $largeSpacer . $textTwo . $largeSpacer;
      $append = "auto_welcome_uk";
    } else if($i === 2){
      $insert = $image . $largeSpacer . $heading . $emptySpacer . $textOne . $largeSpacer;
      $append = "auto_welcome_scot";
    }

    //Input content into template
    $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
    $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

    $save = $saveToFile;
    $path = "pre_made";

    sendToFile($output, $path, $append, $brand, '.html', $save);

    //print_r($output);
    $returnString .= $output;
  }
}

echo $returnString;
 ?>
