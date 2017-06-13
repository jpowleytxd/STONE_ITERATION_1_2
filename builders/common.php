<?php

/*........................*/
/*Send To output file*/
/*........................*/
function sendToFile($output, $path, $append, $serverName, $fileType, $send){
  //$output     => string for output file data
  //$path       => folder path to output location
  //$append     => filename base
  //$serverName => server version of brand name
  //$fileType   => file extension

  if($send == true){
    $dirName = '../' . $path;
    if(!is_dir($dirName)){
      mkdir($dirName, 0755);
    }

    $dirName = '../' . $path . '/' . $serverName;
    if(!is_dir($dirName)){
      mkdir($dirName, 0755);
    }

    file_put_contents(('../'. $path . '/' . $serverName . '/' . $append . $fileType), $output);
  }
}

/*........................*/
/*Query Database Returns array*/
/*........................*/
function databaseQuery($query){
  //Define Connection
  static $connection;

  //Attempt to connect to the database, if connection is yet to be established.
  if(!isset($connection)){
    //Load congig file
    $config = parse_ini_file('../config.ini');
    $connection = mysqli_connect('localhost', $config['username'], $config['password'], $config['dbname']);
  }

  //Arrays to store all retrieved records
  $rows = array();
  $result = null;

  //Connection error handle
  if($connection === false){
    print('Connection Error');
    return false;
  } else{
    //Query the database
    $result = mysqli_query($connection, $query);

    //IF query failed, return 'false'
    if($result === false){
      print('Query Failed');
      return false;
    }

    //Fetch all the rows in the Array
    while($row = mysqli_fetch_row($result)){
      $rows[] = $row;
    }
    return $rows;
  }
}

/*........................*/
/*Detect text color*/
/*........................*/
function textColor($color){
  $r = hexdec(substr($color,1,2));
  $g = hexdec(substr($color,3,2));
  $b = hexdec(substr($color,5,2));

  if ($r + $g + $b > 500){
    //return $r + $g + $b;
      return '#000';
    } else {
      //return $r + $g + $b;
      return '#fff';
  }
}

/*........................*/
/*Retrieve URL for promo image*/
/*........................*/
function getURL($serverName, $image){
  $urlStart = 'http://img2.email2inbox.co.uk/2017/stonegate/01/promo/';
  $urlEnd = '/' . $image;

  if(($serverName === 'finnegans_wake') || ($serverName === 'rosies')){
    return $urlStart . 'colors' . $urlEnd;
  } else if(($serverName === 'halfway_to_heaven') || ($serverName === 'queens_court') || ($serverName === 'two_brewers')){
    return $urlStart . 'charles_street' . $urlEnd;
  } else if(($serverName === 'marys')){
    return $urlStart . 'admiral_duncan' . $urlEnd;
  } else if(($serverName === 'pit_and_pendulum') || ($serverName === 'retro_bar') || ($serverName === 'rupert_street') || ($serverName === 'slains_castle') || ($serverName === 'via')){
    return $urlStart . 'beduin' . $urlEnd;
  }
  else{
    return $urlStart . $serverName . $urlEnd;
  }
}

/*........................*/
/*Build Margins around element*/
/*........................*/
function marginBuilder($block){
  $blockStart = '<table border="0" cellpadding="0" cellspacing="0" width="600" class="block block1Column structureBlock wrapper" data-id="block1Column" style="width:600px;width:600px;width:600px;">
      <tr>
          <td align="center" valign="0">
              <table border="0" cellpadding="0" cellspacing="0" width="600" class="responsive-table blockArea block" data-id="blockArea" style="width:600px;width:600px;width:600px;">
                  <tr><td align="center" width="30"></td>
                      <td valign="top">';
  $blockEnd = '</td><td align="center" width="30"></td>
  </tr>
  </table>
  </td>
  </tr>
  </table>';

  $block = $blockStart . $block .$blockEnd;
  return $block;
}

/*........................*/
/*Build Spacer With dotted line within*/
/*........................*/
function lineSpacerBuild($parentFolder){
  $spacer = file_get_contents('../sites/_defaults/spacer.html');

  $voucher = file_get_contents('../sites/' . $parentFolder . '/bespoke_blocks/' . $parentFolder . '_voucher.html');
  preg_match('/BG Right: (.*)/', $voucher, $matches);
  $color = $matches[1];

  $style='border-top: 1px dotted ' . $color . ';';

  $spacer = preg_replace('/background-color:.*?;/', '', $spacer);
  $spacer = preg_replace('/border-top:.*?;/', $style, $spacer);

  return $spacer;
}

/*........................*/
/*T&C's builder*/
/*........................*/
function termsBuilder($terms){
  $blockStart = '<table border="0" cellpadding="0" cellspacing="0" width="600" class="block block1Column structureBlock wrapper" data-id="block1Column" style="width:600px;">
      <tr>
          <td align="center" valign="0">
              <table border="0" cellpadding="0" cellspacing="0" width="600" class="responsive-table blockArea block" data-id="blockArea" style="width:600px;">
                  <tr><td align="center" width="30"></td>
                      <td valign="top">';
  $blockEnd = '</td><td align="center" width="30"></td>
  </tr><tr><td height="30" valign="0"></td></tr>
  </table>
  </td>
  </tr>
  </table>';

  $block = $blockStart . $terms .$blockEnd;
  return $block;
}

/*........................*/
/*Get Hero Image for template*/
/*........................*/
function getHeroImageURL($brand){
  $urlStart = 'http://img2.email2inbox.co.uk/2016/stonegate/templates/';
  $urlEnd = '/hero.jpg';

  return $urlStart . $brand . $urlEnd;
}

 ?>
