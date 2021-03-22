<?php
  $curlHostRegex = "/curl\/\d{1,15}.\d{1,15}.\d{1,15}/";
  $curl = preg_match($curlHostRegex, $_SERVER['HTTP_USER_AGENT']);
?>
<?php if(!isset($_GET['api']) && !$curl): ?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload your files</title>
</head>
<body>
<?php if(empty($_FILES['uploaded_file'])): ?>
  <form enctype="multipart/form-data" action="./" method="POST">
    <p>Upload your file</p>
    <input type="file" name="uploaded_file"></input><br />
    <input type="submit" value="Upload"></input>
  </form>
  <?php endif; ?>
  <?php endif; ?>
  <?php 
  if(!empty($_FILES['uploaded_file']))
  {
    function formatBytes($bytes) {
      if ($bytes > 0) {
          $i = floor(log($bytes) / log(1024));
          $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
          return sprintf('%.02F', round($bytes / pow(1024, $i),1)) * 1 . ' ' . @$sizes[$i];
      } else {
          return 0;
      }
    }

    if(isset($_GET['api'])) {
      header('Content-Type: application/json');
    }

    $id = substr(sha1(uniqid(substr(time().str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 20))), 0, 10);
    $path = "uploads/";
    $ext = pathinfo(basename( $_FILES['uploaded_file']['name']), PATHINFO_EXTENSION);
    $fileSize = $_FILES['uploaded_file']['size'];
    $path = $path . $id . "." . $ext;

    $fileSizeLimit = 104857600; // 100 MB

    if ($fileSize > $fileSizeLimit) {
      if(isset($_GET['api']))
        die(json_encode(['status' => 'error', 'result' => "The file is too large. Upload a file that is smaller than ".formatBytes($fileSizeLimit)." (current size: " . formatBytes($fileSize) .")"]));
      else 
        die("The file is too large. Upload a file that is smaller than ".formatBytes($fileSizeLimit)." (current size: " . formatBytes($fileSize) .")");
    }

    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
      exec("bash upload.sh " . $path . " > /dev/null &"); 
      $url = "https://files.interclip.app/".$id. "." . strtolower($ext);
      if (isset($_GET['api'])) {
        echo json_encode(['status' => 'success', 'result' => $url]);
      } else if ($curl) {
        echo $url;
      } else {
        echo "The file ".  basename( $_FILES['uploaded_file']['name']). " has been uploaded";
        echo "<br>" . $url;
        echo '<form id="clip" action="../includes/new" method="POST"><input type="url" name="input" value="'.$url.'"><input type="submit"></form>';
        echo "<script>document.getElementById('clip').submit()</script>";
      }
    } else{
      if (isset($_GET['api'])) {
        echo json_encode(['status' => 'error', 'result' => 'Uknown error.']);
      } else {
        echo "There was an error uploading the file, please try again!";
      }
    }
  }
?>
<?php if(!isset($_GET['api']) && !$curl): ?>
</body>
</html>
<?php endif; ?>