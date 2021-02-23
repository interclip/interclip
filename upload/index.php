<!DOCTYPE html>
<html>
<head>
  <title>Upload your files</title>
</head>
<body>
  <form enctype="multipart/form-data" action="upload.php" method="POST">
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
    
    $id = gen_uid(16);
    $path = "uploads/";
    $ext = pathinfo(basename( $_FILES['uploaded_file']['name']), PATHINFO_EXTENSION);

    $path = $path . $id . "." . $ext;

    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
      echo "The file ".  basename( $_FILES['uploaded_file']['name']). 
      " has been uploaded";
    } else{
        echo "There was an error uploading the file, please try again!";
    }
  }
?>