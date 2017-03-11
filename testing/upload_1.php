<?php
$tab_num=0;
$c=0;
function tabify($line)
{
	global $c;
	global $tab_num;
	global $file;
	$c++;
	$trimmed_line=trim($line);
	$indent_text=strstr($trimmed_line,"{");
	$indent_text_close=strstr($trimmed_line,"}");
	fwrite($file,$c.".");
	if(!empty($indent_text) && !empty($trimmed_line) && !in_array($trimmed_line,"}"))
	{
		for($i=0;$i<$tab_num;$i++)
		{
			fwrite($file,"1\t");
		}
		fwrite($file,substr($trimmed_line,0,strpos($trimmed_line,$indent_text)));
		if(!empty(substr($trimmed_line,0,strpos($trimmed_line,$indent_text))))
		fwrite($file,"\n");
		$tab_num++;
		$subline=substr($trimmed_line,strpos($trimmed_line,$indent_text)+1,strlen($trimmed_line));
		if(!empty($subline))
		{
			for($i=0;$i<$tab_num;$i++)
		{
			fwrite($file,"2\t");
		}
		fwrite($file,"\n");
		tabify($subline);
	}
	}
	elseif (!empty($indent_text_close)) {
		for($i=0;$i<$tab_num;$i++)
		{
				fwrite($file,"3\t");
		}
		fwrite($file,substr($trimmed_line,0,strpos($trimmed_line,$indent_text_close)));

			fwrite($file,"\n");
		$tab_num--;
		for($i=0;$i<$tab_num;$i++)
		{
				fwrite($file,"4\t");
		}
		fwrite($file,substr($trimmed_line,strpos($trimmed_line,$indent_text_close)+1,strlen($trimmed_line)));
		if(!empty(substr($trimmed_line,0,strpos($trimmed_line,$indent_text_close))))
		fwrite($file,"\n");
	}
	elseif(empty($trimmed_line))
	fwrite($file,"\n");

	elseif(!empty($trimmed_line)){
		for($i=0;$i<$tab_num;$i++)
		{
			fwrite($file,"5\t");
		}
		fwrite($file,$trimmed_line);
		fwrite($file,"\n");
	}
}
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
	tabify($line);
}
fclose($myfile);

header("Location:./uploads/$name");
?>
