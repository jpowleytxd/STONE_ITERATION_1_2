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

    file_put_contents(('../'. $path . '/' . $append . $fileType), $output);
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
/*Campaigns+ Template settings*/
/*........................*/
function buildTemplateSettings($title, $preHeader, $subject, $brandID, $profileID){
  $settings = '{"emailName":"' . $title . '","textOnly":"","preheader":"' . $preHeader . '","h": -1369038629,"subject":"' . $subject . '","template": "' . $brandID . '","senderProfile":' . $profileID . '}';

  return $settings;
}

/*........................*/
/*Campaigns+ Template mappings*/
/*........................*/
function buildTemplateMappings(){
  $mappings = '[{"id": "dynamic1","val": "brand_name"},{"id": "dynamic2","val": "fav_venue_name"},{"id": "dynamic3","val": "fav_venue_code"},{"id": "dynamic4","val": "valid_from"},{"id": "dynamic5","val": "valid_to"},{"id": "dynamic6","val": "content_type"},{"id": "dynamic7","val": "brand_type"},{"id": "dynamic9","val": "new_password"}]';

  return $mappings;
}

/*........................*/
/*Remove whitespace for cleaner inserts*/
/*........................*/
function removeWhiteSpace($html){
  $search = array(
        // '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        // '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/[\r\n]/'        // replace line breaks with placeholder
        // '/(\s)+/s'       // shorten multiple whitespace sequences
    );

  $replace = array(
      // '>',
      // '<',
      '[[LINEBREAKHERE]]'
      // '\\1'
  );

  $html = preg_replace($search, $replace, $html);
  $html = preg_replace('/\"(\s)data-styles/', '" data-styles', $html);
  $html = preg_replace('/\"(\s)data-mappings/', '" data-mappings', $html);
  $html = preg_replace('/\sdata-variants=""\s/', '" data-variants=""', $html);

  return $html;
}
 ?>
