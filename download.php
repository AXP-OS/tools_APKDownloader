<?php
require_once 'src/APKDownloader.php';
try{
  $apk = new APKDownloader();

  $short_options = "p:b";
  $options = getopt($short_options);

  if(isset($options["p"])){
    $pkg = $options["p"];
  } else {
    throw new Exception("ERROR: Missing package name!\n");
  }
  if(isset($options["b"])){
    $batch = true;
  } else {
    $batch = false;
  }

  $dl = $apk->fetch($pkg);
  if(isset($dl->url)){
    $url = 'https:'.$dl->url;
    if(!$batch){
        echo "\nFound download URL: ".$url."\n";
    }

    // get file name
    $file_name = basename($url);

    // download
    if (file_put_contents($file_name, file_get_contents($url)))
    {
        if($batch){
            echo "$file_name;".$url;
        } else {
            echo "File downloaded successfully and saved to: $file_name\n";
        }
    }
    else
    {
        throw new Exception("ERROR: File downloading failed!\n");
    }
  } else {
    throw new Exception("ERROR: could not generate download link. Message was:\n> ".$dl->data." <\n");
  }
} catch (Exception $e){
  echo $e->getMessage();
  exit(3);
}
