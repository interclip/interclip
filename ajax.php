<?php
$arr_file_types = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'application/pdf'];
 
//if (!(in_array($_FILES['file']['type'], $arr_file_types))) {
  echo "false";
  return;
//}
 
if (!file_exists('uploads')) {
  mkdir('uploads', 0777);
}
 
move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . time() . '_' . $_FILES['file']['name']);
 
?>