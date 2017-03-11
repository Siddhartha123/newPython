<?php
$target_file = $_FILES["fileToUpload"]["name"];
$type=strstr($_FILES["fileToUpload"]["name"],".");
if($type!=".py")
{
	echo "Wrong type";
	die();
}
$tmp_name = $_FILES["fileToUpload"]["tmp_name"];
$name = basename($_FILES["fileToUpload"]["name"]);
$success=move_uploaded_file($tmp_name, "uploads/original_".$name);

$myfile = fopen("uploads/original_$name", "r") or die("Unable to open file!");
$file = fopen("uploads/$name", "w") or die("Unable to open file!");

while(!feof($myfile)) {
	$line=fgets($myfile);
	tabify_new($line);
}
fclose($myfile);
fclose($file);
$file = fopen("uploads/$name", "r") or die("Unable to open file!");
$myfile = fopen("uploads/final_$name", "w") or die("Unable to open file!");

while(!feof($file)) {
	$line=fgets($file);
	if(!empty(trim($line)))
	fwrite($myfile,$line);
}
fclose($myfile);
fclose($file);
header("Location:./uploads/final_$name");

$tab_num=0;
$tab="";
function tabify_new($line)
{
	$line=trim($line);
	$flag=1;
	$var="";
	global $file,$tab_num;
	for($i=0;$i<strlen($line);$i++)
	{
		if($line[$i]=="{" && strlen($line)!=1)
			{
				$flag=0;
				$tab="";
				for($j=0;$j<$tab_num;$j++)
				$tab=$tab."\t";
				$var=$tab;
				$tab_num++;
				$tab="";
				for($j=0;$j<$tab_num;$j++)
				$tab=$tab."\t";
				$var=$var.substr_replace($line,"\n".$tab,$i,1);
			}
			elseif ($line=="{") {
				$tab_num++;
				$flag=0;
			}
			elseif($line[$i]=="}" && strlen($line)!=1)
				{
					$flag=0;
					$tab="";
					for($j=0;$j<$tab_num;$j++)
					$tab=$tab."\t";
					$var=$tab;
					$tab_num--;
					$tab="";
					for($j=0;$j<$tab_num;$j++)
					$tab=$tab."\t";
					$var=$var.substr_replace($line,"\n".$tab,$i,1);
				}
			elseif ($line=="}") {
					$tab_num--;
					$flag=0;
				}
	}
	if($flag==1)
	{
		$var=$line;
		$tab="";
		for($j=0;$j<$tab_num;$j++)
		$tab=$tab."\t";
		$var=$tab.$var;
	}
		$var=$var."\n";
	if(!empty(trim($var)))
	fwrite($file,$var);
}
 ?>
