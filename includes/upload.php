<?
$uploadfile = $_POST['dir'].$_FILES['file']['name'];
if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {echo 'true';}
else{echo 'false';}
?>