<?php
	//Test Spritero Class
	include("Spritero.php");

	$files = glob("fire1/*");
	natsort($files);
	
	$s = new Spritero(128,128);
	foreach($files as $file) {
		$s->addFrame(file_get_contents($file));
	}

	$css = $s->export('css',array('class'=>'blocka'));
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<style>
	<?=$css['animation']?>
	<?=$css['class']?>
.paused {
    -webkit-animation-play-state:paused !important;
    -moz-animation-play-state:paused !important;
    -o-animation-play-state:paused !important; 
    animation-play-state:paused !important;
}
</style>
<div style="background-image:url(http://dev.avatarcreator.org/assets/preview/1.png);height:300px;width:300px;background-size:cover">
	<div class='blocka' onClick="$(this).toggleClass('paused');" style='margin-left:87px;top:170px;position:absolute;'> </div>
</div>
