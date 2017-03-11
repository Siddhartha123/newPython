<?php
$tab_num=0;
$c=0;
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

header("Location:./uploads/$name");
function tabify($line)
{

	global $tab_num;
	global $file;

	$trimmed_line=trim($line);
	$line=substr($trimmed_line,0,strpos($trimmed_line,"{"));
	if(empty(strpos($trimmed_line,"{")))
	{	fwrite($file,"hi");
		for($i=0;$i<$tab_num;$i++)
			{
				fwrite($file,"1\t");
			}
		fwrite($file,$trimmed_line);
		fwrite($file,"\n");
	}
else{
while(!empty($line))
{
	fwrite($file,"ho");
	for($i=0;$i<$tab_num;$i++)
		{
			fwrite($file,"1\t");
		}
	fwrite($file,substr($trimmed_line,0,strpos($trimmed_line,"{")));
	fwrite($file,"\n");
	$line=substr($trimmed_line,strpos($trimmed_line,"{")+1,strlen($trimmed_line));
	$tab_num++;
}
}
}

function tabify_new($line)
{
	$flag=3;
global $file;
global $tab_num;
for($i=0;$i<strlen($line);$i++)
{
	if($line[$i]=="{")
		{
			$tab_num++;
			$flag=0;
		}
	elseif ($line[$i]=="}")
	{
		$tab_num--;
		$flag=1;
	}
}
if($flag==0 && strlen(trim($line))==1)
{}
else{
for($i=0;$i<$tab_num;$i++)
	{
		fwrite($file,"4\t");
	}
}
if($flag==0)
{
	if(strlen(trim($line))!=1)
	fwrite($file,str_replace("{","2\n\t",trim($line)."\n"));
	$flag=3;
}
elseif ($flag==1) {
	fwrite($file,str_replace("}","\n\t",trim($line)."\n"));
	$flag=3;
}
else
fwrite($file,trim($line)."\n");
}
?>
