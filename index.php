<?
if(file_exists("config.php"))
{
	session_start();
	include 'header.php';
	if (isset($_SESSION['user1112'])){		
	function copyr($source, $dest)
{
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    } 
    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }
    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
        // Deep copy directories
        if ($dest !== "$source/$entry") {
            copyr("$source/$entry", "$dest/$entry");
        }
    }
 
    // Clean up
    $dir->close();
    return true;
}
	function recursive_remove_directory($directory, $empty=FALSE)
	{
     // if the path has a slash at the end we remove it here
     if(substr($directory,-1) == '/')
     {
         $directory = substr($directory,0,-1);
     }
     // if the path is not valid or is not a directory ...
     if(!file_exists($directory) || !is_dir($directory))
     {
         // ... we return false and exit the function
         return FALSE;
     // ... if the path is not readable
     }elseif(!is_readable($directory))
     {
         // ... we return false and exit the function
         return FALSE;
     // ... else if the path is readable
     }else{
         // we open the directory
         $handle = opendir($directory);
         // and scan through the items inside
         while (FALSE !== ($item = readdir($handle)))
         {
             // if the filepointer is not the current directory
             // or the parent directory
             if($item != '.' && $item != '..')
             {
                 // we build the new path to delete
                 $path = $directory.'/'.$item;
                 // if the new path is a directory
                 if(is_dir($path)) 
                 {
                     // we call this function with the new path
                     recursive_remove_directory($path);  
                // if the new path is a file
                }else{
                     // we remove the file
                     unlink($path);
                 }
             }
         }
         // close the directory
         closedir($handle);
         // if the option to empty is not set to true
         if($empty == FALSE)
         {
             // try to delete the now empty directory
             if(!rmdir($directory))
             {
                 // return false if not possible
                 return FALSE;
             }
         }
         // return success
         return TRUE;
     }
	}
	function Fileize($FILE)
	{
		if(filetype($FILE) != file){return 'n/a';}else
		{
			$size = Filesize($FILE);
			$i=0;
			$iec = array("b", "kb", "mb", "gb", "tb", "pb", "eb", "zb", "yb");
			while (($size/1024)>1) 
			{
				$size=$size/1024;
				$i++;
			}
		return substr($size,0,strpos($size,'.')+4).$iec[$i]; 
		}
	}
	if($_REQUEST['du'] == '1')
	{
		if(filetype('dl/'.$_REQUEST['file']) == dir)
			recursive_remove_directory('dl/'.$_REQUEST['file']);
		else
			unlink('dl/'.$_REQUEST['file']);
		if($_REQUEST['dir']){echo'<script>location.href="?dir='.$_REQUEST['dir'].'";</script>';}else{echo'<script>location.href="./";</script>';}
	}
	if($_REQUEST['dl'])
	{
	$file = $config_rootLocation.$_REQUEST['dl'];
	if(file_exists($file) || is_dir($file))
	{
		$result = substr_count($file, "/");
		$tr = explode("/",$file);
		copyr($file, 'dl/'.$tr[$result]);	
		?>
		<iframe src="dl/<?echo $tr[$result]?>" width=0 height=0 frameborder=0></iframe>
		<script>
		setTimeout('location.href="?<?if($_REQUEST['dir']){echo'dir='.$_REQUEST['dir'].'&';}else{echo'';}?>du=1&file=<?echo $tr[$result]?>";', 1);
		</script>
		<?
	}
	else
	{
		?>
		<script>alert('ah!');</script>
		<?
	}
	}
	if($_REQUEST['uninstall'] == 'epfm')
	{
		recursive_remove_directory("help");
		recursive_remove_directory("gimages");
		recursive_remove_directory("img");
		recursive_remove_directory("include");
		recursive_remove_directory("includes");
		recursive_remove_directory("ftp");
		chmod('dl/', 0777);
		chmod('dl/.htaccess', 0777);
		recursive_remove_directory("dl");
		recursive_remove_directory("languages");
		unlink('footer.php');
		unlink('header.php');
		unlink('logo.png');
		unlink('z.css');
		unlink('index.php');
		?>
		<script>
		location.href="<?echo $config_rootLocation;?>";
		</script>
		<?
	}
	if($_REQUEST['include'])
	{
		if($_REQUEST['include'] == 'zip'){include 'includes/zipFunctions.php';}
		if($_REQUEST['include'] == 'del'){include 'includes/delete.php';}
		if($_REQUEST['include'] == 'mkdir'){include 'includes/mkdir.php';}	
		if($_REQUEST['include'] == 'mkfile'){include 'includes/mkfile.php';}
	}
	/*Actions - Begin*/
	if($_REQUEST['action'])
	{
		/*
		The Action scripts are in the 'includes/' file
		~More flexible
		*/
		if($_REQUEST['action'] == 'edit'){include 'includes/edit.php';}
		if($_REQUEST['action'] == 'execute'){exec(basename($config_rootLocation.$_REQUEST['file'], ".php"));?><script>location.href="<?if($_REQUEST['dir']){echo '?dir='.$_REQUEST['dir'];}else{echo './';}?>";</script><?}
		if($_REQUEST['action'] == 'edit2'){include 'includes/edit2.php';}
		if($_REQUEST['action'] == 'edit3'){include 'includes/edit3.php';}
		if($_REQUEST['action'] == 'edit1'){include 'includes/edit1.php';}
		if($_REQUEST['action'] == 'rename'){include 'includes/rename.php';}	
		if($_REQUEST['action'] == 'fileinfo'){include 'includes/fileinfo.php';}
		if($_REQUEST['action'] == 'useredit'){include 'includes/user.php';}
		if($_REQUEST['action'] == 'delselected'){include 'includes/deleteselected.php';}
		if($_REQUEST['action'] == 'upload'){include 'includes/upload.php';}
		if($_REQUEST['action'] == 'reset'){unlink("config.php");?><script>location.href="./";</script><?}
	}
	/*Actions - End*/
	else
	{
		?>
		<script>
		function changetab(num)
		{
			if(num == 0)
			{
				document.getElementById('z').innerHTML = document.getElementById('Tab1').innerHTML;
				document.getElementById('z').style.backgroundColor = '';
				changetabUpload(0);
				document.getElementById('tabz').innerHTML = '<table cellspacing=2 style="background-color:gainsboro;color:blue"><tr><td style="font-size:10;background-color:lightblue;background-image:url(img/5.png);">&nbsp;<?echo TAB0;?>&nbsp;</td><?if($ftp == true){?><td onclick="changetab(1);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB1;?>&nbsp;</td><?}?><td onclick="changetab(2);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB2;?>&nbsp;</td><td onclick="changetab(3);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB3;?>&nbsp;</td><td onclick="changetab(4);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB4;?>&nbsp;</td></tr></table>';
			}
			if(num == 1)
			{
				document.getElementById('z').innerHTML = document.getElementById('Tab2').innerHTML;
				document.getElementById('z').style.backgroundColor = 'gainsboro';
				document.getElementById('tabz').innerHTML = '<table cellspacing=2 style="background-color:gainsboro;color:blue"><tr><td onclick="changetab(0);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB0;?>&nbsp;</td><?if($ftp == true){?><td style="font-size:10;background-color:lightblue;background-image:url(img/5.png);">&nbsp;<?echo TAB1;?>&nbsp;</td><?}?><td onclick="changetab(2);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB2;?>&nbsp;</td><td onclick="changetab(3);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB3;?>&nbsp;</td><td onclick="changetab(4);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB4;?>&nbsp;</td></tr></table>';
			}
			if(num == 2)
			{
				document.getElementById('z').innerHTML = document.getElementById('Tab3').innerHTML;
				document.getElementById('z').style.backgroundColor = '';
				document.getElementById('tabz').innerHTML = '<table cellspacing=2 style="background-color:gainsboro;color:blue"><tr><td onclick="changetab(0);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB0;?>&nbsp;</td><?if($ftp == true){?><td onclick="changetab(1);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB1;?>&nbsp;</td><?}?><td style="cursor:default;font-size:10;background-color:lightblue;background-image:url(img/5.png);">&nbsp;<?echo TAB2;?>&nbsp;</td><td onclick="changetab(3);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB3;?>&nbsp;</td><td onclick="changetab(4);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB4;?>&nbsp;</td></tr></table>';
			}
			if(num == 3)
			{
				document.getElementById('z').innerHTML = document.getElementById('Tab4').innerHTML;
				document.getElementById('z').style.backgroundColor = '';
				document.getElementById('tabz').innerHTML = '<table cellspacing=2 style="background-color:gainsboro;color:blue"><tr><td onclick="changetab(0);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB0;?>&nbsp;</td><?if($ftp == true){?><td onclick="changetab(1);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB1;?>&nbsp;</td><?}?><td onclick="changetab(2);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB2;?>&nbsp;</td><td style="cursor:default;font-size:10;background-color:lightblue;background-image:url(img/5.png);">&nbsp;<?echo TAB3;?>&nbsp;</td><td onclick="changetab(4);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB4;?>&nbsp;</td></tr></table>';
			}
			if(num == 4)
			{
				document.getElementById('z').innerHTML = document.getElementById('Tab5').innerHTML;
				document.getElementById('z').style.backgroundColor = '';
				document.getElementById('tabz').innerHTML = '<table cellspacing=2 style="background-color:gainsboro;color:blue"><tr><td onclick="changetab(0);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB0;?>&nbsp;</td><?if($ftp == true){?><td onclick="changetab(1);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB1;?>&nbsp;</td><?}?><td onclick="changetab(2);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB2;?>&nbsp;</td><td onclick="changetab(3);" onmouseover="this.style.backgroundImage=\'url(img/1.png)\';" onmouseout="this.style.backgroundImage = \'url(img/4.png)\';"  style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB3;?>&nbsp;</td><td style="cursor:default;font-size:10;background-color:lightblue;background-image:url(img/5.png);">&nbsp;<?echo TAB4;?>&nbsp;</td></tr></table>';
			}
		}
		</script>
		<div style="border-top: gainsboro 1px solid; border-left: gainsboro 1px solid; border-bottom: gainsboro 1px solid; border-right: gainsboro 1px solid;">
			<span id="tabz">
				<table cellspacing=2 style="background-color:gainsboro;color:blue">
				<tr>
					<td style="font-size:10;background-color:lightblue;background-image:url(img/5.png);">&nbsp;<?echo TAB0;?>&nbsp;</td>
					<?if($ftp == true){?>
					<td onclick="changetab(1);" onmouseover="this.style.backgroundImage='url(img/1.png)';" onmouseout="this.style.backgroundImage = 'url(img/4.png)';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB1;?>&nbsp;</td><?}?>
					<td onclick="changetab(2);" onmouseover="this.style.backgroundImage='url(img/1.png)';" onmouseout="this.style.backgroundImage = 'url(img/4.png)';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB2;?>&nbsp;</td>
					<td onclick="changetab(3);" onmouseover="this.style.backgroundImage='url(img/1.png)';" onmouseout="this.style.backgroundImage = 'url(img/4.png)';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB3;?>&nbsp;</td>
					<td onclick="changetab(4);" onmouseover="this.style.backgroundImage='url(img/1.png)';" onmouseout="this.style.backgroundImage = 'url(img/4.png)';" style="cursor:default;font-size:10;background-color:white;background-image:url(img/4.png);">&nbsp;<?echo TAB4;?>&nbsp;</td>
				</tr>
				</table>
			</span>
			<div id="z">
				<div align=center style="background-color:gainsboro"><span id="clock" style="color:gainsboro">.</span></div>
				<a class="Files" href="#" onclick="location.href = location.href;"><?echo REFRESH;?></a><br><Br>
				<?include 'include/dirtracker.php';include 'include/filepane.php';?></div>
			</div>
		<?
	}
	include 'footer.php';
	?>
	<div id="Tab5" style="top:0;left:0;visibility:hidden;position:absolute;">
	<div align=center style="background-color:gainsboro"><span style="color:gainsboro">.</span></div>
	<?echo $settings;?>
	</div>
	<div id="Tab4" style="top:0;left:0;visibility:hidden;position:absolute;">
	<div align=center style="background-color:gainsboro"><span style="color:gainsboro">.</span></div>
	<?echo $about;?>
	</div>
	<div id="Tab3" style="top:0;left:0;visibility:hidden;position:absolute;">
	<div align=center style="background-color:gainsboro"><span id="clock" style="color:gainsboro">.</span></div>
	<iframe frameborder=0 src="help/<?echo HELP;?>?version=<?echo VERSION;?>" style="width:100%;height:600px;">[Please update your browser!]</iframe>
	</div>
	<div id="Tab2" style="top:0;left:0;visibility:hidden;position:absolute;">
	<div align=center style="background-color:gainsboro"><span id="clock" style="color:gainsboro">.</span></div>
	<span id="divvy">
	<iframe frameborder=0 src="ftp/index.php" style="width:100%;height:600px;">[Please update your browser!]</iframe>
	</span>
	</div>
	<div id="Tab1" style="top:0;left:0;visibility:hidden;position:absolute;">
	<div align=center style="background-color:gainsboro"><span id="clock" style="color:gainsboro">.</span></div>
	<a class="Files" href="#" onclick="location.href = location.href;"><?echo REFRESH;?></a><br><Br>
	<?
	include 'include/dirtracker.php';
	include 'include/filepane.php';
	?>
	</div>
	<?
	}
	else 
	{
		if($_POST)
		{
			if(USERNAME == $_POST['user'])
			{
				if(PASSWORD == $_POST['pass'])
				{
					$_SESSION['user1112'] = ''.$_POST['user'].'';
					?><script>location.href="./";</script><?
				}else{?><body bgcolor="#3b5f7b"></body><script>alert('Invalid password!');location.href="./";</script><?}
			}else{?><body bgcolor="#3b5f7b"></body><script>alert('Invalid username!');location.href="./";</script><?}
		}
		else
		{
			?>
			<form action="?" method="post">
			<div align="center">
			<?echo ADMIN.' '.ONLY.'!';?><br>
			<?echo USER;?>: <input type="text" name="user"><br>
			<?echo PASS;?>: <input type="password" name="pass"><br>
			<input type="submit" onmousedown="this.style.backgroundImage='url(img/3.png)';" onmouseup="this.style.backgroundImage='url(img/4.png)';" onmouseover="this.style.backgroundImage='url(img/1.png)';" onmouseout="this.style.backgroundImage = 'url(img/4.png)';" value="<?echo LOGIN;?>" style="border:0;background-color:transparent;background-image:url(img/4.png);color:blue;">
			</div>
			</form>
			<?
		}
		include 'footer.php';
	}	
}
else
{
	if($_POST['serverRoot'])
	{
		$tr = fopen('config.php',"w");
		fwrite($tr,"<?
/*
The Root Location, is the root of your server. 
Ex: 'root/public/'
Warning: it needs to end with /
*/

".'$'."config_rootLocation = '".$_POST['serverRoot']."';
define(\"LANGUAGE\", \"".$_POST['lang']."\");

/*
Notice:
The Username, and Password are safe!
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
		fclose($tr);
		include 'header.php';
		?>
		<strong>Installation</strong>
		Installation Complete!<br><br><a href="./">ok</a>
		<?
		include 'footer.php';
	}
	else
	{
		include 'header.php';
		?>
		<strong>Installation</strong><br><br>
		<form action="./"; method="post">
		Server Root / Ra&iacute;z de camarero / Racine de serveur:<br>
		<input type="text" name="serverRoot" value="../" /> ../ = back one directory, ./ = Current directory (../ = detr&aacute;s uno carpeta, ./ = Coriente carpeta)<br>
		Username / Usuaria / Identifiant: <input type="text" name="user"><br>
		Password / Contrase&ntilde;a / Mot de passe: <input type="password" name="pass"><br>
		Language / Linguas / Langue:<select name="lang"><option value="en">English / ingl&eacute;s / Anglais</option><option value="sp">Spanish / espa&ntilde;ol / L'espagnol</option><option value="fr">French / Franc&iacute;s / Le francais</option></select><br>
		<input type="submit" onmousedown="this.style.backgroundImage='url(img/3.png)';" onmouseup="this.style.backgroundImage='url(img/4.png)';" onmouseover="this.style.backgroundImage='url(img/1.png)';" onmouseout="this.style.backgroundImage = 'url(img/4.png)';" value="Login" style="border:0;background-color:transparent;background-image:url(img/4.png);color:blue;"><br>
		<br>		
		Warning: You may link to this file using a frame, from the server root<br>
		Warning: this file must be index.php<br>
		Freeware notice: You may edit any part of this!<br>
		Yes, this web tool supports Spanish!<br>
		Yes, this web tool supports French!<br><br>
		Spanish / espa&ntilde;ol / L'espagnol:<br>
		La advertencia: Usted puede ligar a este archivo que utiliza un marco, de la ra&iacute;z de camarero<br>
		La advertencia: este archivo debe ser  	&iacute;ndice.php<br>
		La nota del programa de libre acceso: &iexcl;Usted puede redactar cualquier parte de esto!<br>
		&iexcl;S&iacute;, este instrumento de telara&ntilde;a sostiene espa&ntilde;ol!<br>
		&iexcl;S&iacute;, este instrumento de telara&ntilde;a sostiene a franc&eacute;s!<br><br>
		French / Franc&iacute;s / Le francais:<br>
		Avertissement : Vous pouvez relier &agrave; ce dossier utilisant un cadre, de la racine de serveur<br>
		L'avertissement : ce dossier doit &ecirc;tre index.php<br>
		Notification de graticiel : Vous pouvez &eacute;diter n'importe quelle partie de ceci !<br>
		Oui, cet espagnol de soutiens d'outil de toile !<br>
		Oui, ce francais de soutiens d'outil de toile !
		</form>
		<title>Easy PHP Editor</title>
		<?
		include 'footer.php';
	}
}
?>
