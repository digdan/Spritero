<?php
	interface iSpritero {
		function addFrame($source);
		function getBlob($format='png');
		function save($filename='',$format='png');
		function export($type='css');
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
			$this->width = $width;
			$this->image = new Imagick();
		}

		//Publics
		public function addFrame($source) {
			$tmp = $this->image;
			$im = new Imagick();
			$im->readImageBlob($source);
			$tmp->addImage($im);
			$tmp->resetIterator();
			$this->image = $tmp->appendImages( FALSE );
			$this->frameCount++;
		}

		public function getBlob($format='png') {
			$this->image->setImageFormat($format);
			return $this->image->getImageBlob();
		}

		public function save($filename='',$format='png') {
			$this->image->setImageFormat($format);
			$fp = fopen($filename,"wb");
			fputs($this->image->getImageBlob(),$fp);
			fclose($fp);
		}
		
		public function export($type='css',$params=NULL) {
			switch($type) {
				case 'class' : return $this->export_css_class($params); break;
				case 'animation' : return $this->export_css_animation($params); break;
				default: return array('class'=>$this->export_css_class($params),'animation'=>$this->export_css_animation($params));
			}
		}

		//Statics

		//Count the # of frames in an existing sprite file
		public static function countFrames($imageData,$frameWidth=100) {
			$im = new Imagick();
			$im->readImageBlob($imageData);
			return floor($im->getImageWidth() / $frameWidth);
		}

		//Privates/Protected
		protected function export_css_class($params=NULL) {
			$seconds = (isset($params['seconds'])?$params['seconds']:1);
			$mime = (isset($params['mimetype'])?$params['mimetype']:'image/png');
			$iter = (isset($params['iterations'])?$params['iterations']:'infinite');
			$className = (isset($params['class'])?$params['class']:'animated');
			$animName = (isset($params['animation'])?$params['animation']:'anim');
			$sprite = (isset($params['sprite']?$params['sprite']:NULL);

			$def = ".".$className." {\n";
			$def .= "\twidth:".$this->width."px;\n";
			$def .= "\theight:".$this->height."px;\n";
			if (is_null($sprite)) {
				$def .= "\tbackground-image: url(data:".$mime.";base64,".base64_encode($this->getBlob()).");\n";
			} else {
				$def .= "\tbackground-image: url({$sprite});\n";
			}
			$def .= "\t-webkit-animation: ".$animName." ".$seconds."s steps(".$this->frameCount.",end) ".$iter.";\n";
			$def .= "\t-moz-animation: ".$animName." ".$seconds."s steps(".$this->frameCount.",end) ".$iter.";\n";
			$def .= "\tanimation: ".$animName." ".$seconds."s steps(".$this->frameCount.",end) ".$iter.";\n";
			$def .= "}\n";

			return $def;
		}

		protected function export_css_animation($params=NULL) {
			$animName = (isset($params['animation'])?$params['animation']:'anim');
			$startPos = -1 * $this->image->getImageWidth();
			$ani = "\t@-webkit-keyframes ".$animName." {\n\tfrom { background-position: 0px }\n\tto { background-position: ".$startPos."px; }\n}\n";
			$ani .= "\t@-moz-keyframes ".$animName." {\n\tfrom { background-position: 0px }\n\tto { background-position: ".$startPos."px; }\n}\n";
			$ani .= "\t@keyframes ".$animName." {\n\tfrom { background-position: 0px }\n\tto { background-position: ".$startPos."px; }\n}\n";
			return $ani;
		}

	}
?>
