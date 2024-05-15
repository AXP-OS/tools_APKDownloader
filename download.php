<?php
//Example for generating WhatsApp APK download link.
require_once 'src/APKDownloader.php';
try{
  $apk = new APKDownloader();
  if(isset($argv[1])){
      $dl = $apk->fetch($argv[1]); //WhatsApp's package name is "com.whatsapp"
      if(isset($dl->url)){
        $url = 'https:'.$dl->url;
        echo "\nFound download URL: ".$url."\n";

        // Use basename() function to return the base name of file
        $file_name = basename($url);

        // Use file_get_contents() function to get the file
        // from url and use file_put_contents() function to
        // save the file by using base name
        if (file_put_contents($file_name, file_get_contents($url)))
        {
            echo "File downloaded successfully and saved to $file_name\n";
        }
        else
        {
            echo "ERROR: File downloading failed!\n";
        }
      } else {
        echo "ERROR: could not generate download link. Message was:\n> ".$dl->data." <\n";
      }
  } else {
    echo "\nERROR: Missing package name!\n";
  }
} catch (Exception $e){
  echo $e->getMessage();
}
