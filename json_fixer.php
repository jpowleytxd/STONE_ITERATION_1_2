<?php

ini_set('max_execution_time', 3000);

foreach(glob('sites/*/templates/*_branded.html') as $filename){
  $template = file_get_contents($filename);

  // $template = preg_replace('/("linkUnderline": ".*")/', '$1,"customFonts": ""',$template);
  $template = preg_replace('/section-padding email/', 'section-padding',$template);
  // $template = preg_replace('/\\r\\n/', '',$template);
  file_put_contents($filename, $template);

  echo $template;
}

 ?>
