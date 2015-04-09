<?php
 //遍历Domain下所有文件
 $stor = new SaeStorage();
 
 $num = 0;
 while ( $ret = $stor->getList("box", "*", 100, $num ) ) {
         foreach($ret as $file) {
             echo "{$file}\n";
             $num ++;
         }
 }
 echo "\nTOTAL: {$num} files\n";
 ?>
 
 <?php
 $domain = "box";
 $filename = "SAE_SDK_Windows_1.0.5.Build1105301821.zip";
 $url = $stor ->getUrl ($domain, $filename) ;
 echo  $url;
 ?>