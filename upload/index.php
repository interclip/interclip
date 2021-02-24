<!DOCTYPE html>
<html>
<head>
  <title>Upload your files</title>
</head>
<body>
  <form enctype="multipart/form-data" action="index.php" method="POST">
    <p>Upload your file</p>
    <input type="file" name="uploaded_file"></input><br />
    <input type="submit" value="Upload"></input>
  </form>
</body>
</html>
<?php 

  if(!empty($_FILES['uploaded_file']))
  {
    function gen_uid($len = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $len);
    }
    
    function formatBytes($bytes) {
      if ($bytes > 0) {
          $i = floor(log($bytes) / log(1024));
          $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
          return sprintf('%.02F', round($bytes / pow(1024, $i),1)) * 1 . ' ' . @$sizes[$i];
      } else {
          return 0;
      }
    }

    $id = gen_uid(16);
    $path = "uploads/";
    $ext = pathinfo(basename( $_FILES['uploaded_file']['name']), PATHINFO_EXTENSION);
    $fileSize = $_FILES['uploaded_file']['size'];
    $path = $path . $id . "." . $ext;

    $fileSizeLimit = 52428800;

    if ($fileSize > $fileSizeLimit) {
      die("The file is too large. Upload a file that is smaller than ".formatBytes($fileSizeLimit)." (current size: " . formatBytes($fileSize) .")");
    }

    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
      echo "The file ".  basename( $_FILES['uploaded_file']['name']). " has been uploaded";
      echo "<br>Uploading to the file server...";
      exec("bash upload.sh " . $path . " > /dev/null &"); 
      echo "<br>https://drives.filiptronicek.workers.dev/3:/iclip/".$id. "." . $ext;
    } else{
        echo "There was an error uploading the file, please try again!";
    }
  }
?>