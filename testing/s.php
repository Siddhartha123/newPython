<?php
$string="{this is{ a test";
  for($i=0;$i<strlen($string);$i++)
	{
		if($string[$i]=="{" && strlen($string)!=1)
      echo substr_replace($string,"\n",$i,1);
    }

?>
