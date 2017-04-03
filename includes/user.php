<?
if($_POST)
{
	$r = fopen('config.php', 'w');
	fwrite($r, "<?
/*
The Root Location, is the root of your server. 
Ex: 'root/public/'
Warning: it needs to end with /
*/

".'$'."config_rootLocation = '".$config_rootLocation."';
define(\"LANGUAGE\", \"".LANGUAGE."\");

/*
Notice:
The Username, and Password are safe!
Hacking php client-wise, is imposible!
Login - START
*/

define(\"USERNAME\", \"".$_POST['user']."\");
define(\"PASSWORD\", \"".$_POST['pass']."\");

/*
Login - END
*/

/*
You can make a portable file system with multible users!
This program is free, and is very portable. You may edit any part of the file manager.
*/
?>");
	?>
	<script>
	location.href="./";
	</script>
	<?
}
else
{
?>
<form action="?action=useredit" method="post">
Username: <input value="<?echo USERNAME;?>" type="text" name="user"><br>
Password: <input value="<?echo PASSWORD;?>" type="text" name="pass"><br>
<input type="submit" value="OK">
</form>
<?
}
?>