<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1.0 user-scalable=no">
  <meta charset="UTF-8">

  <title>Stonegate Templates</title>

  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link href="https://fonts.googleapis.com/css?family=Raleway" rel="styesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="js/main.js" type="text/javascript"></script>
</head>

<body>

<div class="navigation-container">
  <div class="navigation-left">
    <div class="icon-container">
      <div class="nav-icon" data-process="build">B</div>
      <div class="nav-icon" data-process="insert">I</div>
      <div class="nav-icon" data-process="update">U</div>
      <div class="nav-icon" data-process="view">V</div>
    </div>
  </div>
  <div class="navigation-right">
    <div class="nav-group" id="build-group">
      <?php
        foreach(glob('builders/*_builder.php') as $filename){
          if(strpos($filename, 'all_template_builder') === false){
            $name = preg_replace('/.*\/(.*)_builder.php/', '$1', $filename);
            $display = str_replace('_', ' ', $name);
            $display = ucwords($display);
            $display = str_replace('Uk', 'UK', $display);
            ?>
            <div class="nav-item" id="<?php print_r($name . '_builder'); ?>" data-process-"build">
              <?php print_r($display . ' Builder'); ?>
            </div>
            <?php
          }
        }
       ?>
       <div class="nav-item" id="all_template_builder" data-process="build">
         All Template Builder
       </div>
    </div>
    <div class="nav-group" id="insert-group">
      <?php
        foreach(glob('inserters/*_inserter.php') as $filename){
          $name = preg_replace('/.*\/(.*)_inserter.php/', '$1', $filename);
          $display = str_replace('_', ' ', $name);
          $display = ucwords($display);
          $display = str_replace('Uk', 'UK', $display);
          ?>
          <div class="nav-item" id="<?php print_r($name . '_inserter'); ?>" data-process="insert">
            <?php print_r($display . ' Inserter'); ?>
          </div>
          <?php
        }
       ?>
    </div>
    <div class="nav-group" id="update-group">
      <?php
        foreach(glob('updaters/*_updater.php') as $filename){
          $name = preg_replace('/.*\/(.*)_updater.php/', '$1', $filename);
          $display = str_replace('_', ' ', $name);
          $display = ucwords($display);
          $display = str_replace('Uk', 'UK', $display);
          ?>
          <div class="nav-item" id="<?php print_r($name . '_updater'); ?>" data-process="update">
            <?php print_r($display . ' Updater'); ?>
          </div>
          <?php
        }
       ?>
    </div>
    <div class="nav-group" id="view-group">
      <?php
        foreach(glob('builders/*_viewer.php') as $filename){
          $name = preg_replace('/.*\/(.*)_viewer.php/', '$1', $filename);
          $display = str_replace('_', ' ', $name);
          $display = ucwords($display);
          $display = str_replace('Uk', 'UK', $display);
          ?>
          <div class="nav-item" id="<?php print_r($name . '_viewer'); ?>" data-process="view">
            <?php print_r($display . ' Viewer'); ?>
          </div>
          <?php
        }
       ?>
    </div>
  </div>
</div>
<div class="view-container">

</div>

</body>

</html>
