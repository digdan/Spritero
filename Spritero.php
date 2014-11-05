<?php
	interface iSpritero {
		function addFrame($source);
		function getBlob($format='png');
		function save($filename='',$format='png');
	}

/**
 * Class Spritero
 *
 *  $spirtero = new Spritero(300,300);
 *  $spritero->addFrame( file_get_contents('myimage.png') );
 *  echo $spritero->getBlob();
 *  $spritero->save( $filename );
 */

	class Spritero implements iSpritero {
		protected $height;
		protected $width;
		protected $image;
		private $frameCount=0;
		function __construct($height=100,$width=100) {
			$this->height = $height;
			$this->widtdh = $width;
			$this->image = new Imagick();
		}
		function addFrame($source) {
			$tmp = $this->image;
			$im = new Imagick();
			$im->readImageBlob($source);
			$tmp->addImage($im);
			$tmp->resetIterator();
			$this->image = $tmp->appendImages();
			$this->frameCount++;
		}

		function getBlob($format='png') {
			$this->image->setImageFormat($format);
			return $this->image->getImageBlob();
		}

		function save($filename='',$format='png') {
			$this->image->setImageFormat($format);
			$fp = fopen($filename,"wb");
			fputs($this->image->getImageBlob(),$fp);
			fclose($fp);
		}
	}
?>
