#!/usr/bin/php -q
<?php
	//Test Spritero Class
	include("Spritero.php");

	$dir = false;
	if (isset($argv[1])) $dir = $argv[1];
	if (!is_dir($dir)) die("First parameter must be directory.\n");
	
	$files = glob("{$dir}/*");
	natsort($files);
	
	$s = new Spritero(128,128);
	foreach($files as $file) {
		$s->addFrame(file_get_contents($file));
	}

	$css = $s->export('css',array('class'=>'blocka'));
?>
<style>
	<?=$css['animation']?>
	<?=$css['class']?>
</style>
<div class='blocka'></div>
