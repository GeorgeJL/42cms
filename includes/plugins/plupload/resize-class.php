<?php
		Class resize
		{
			private $image;
	    private $width;
	    private $height;
			private $imageResized;

			function __construct($fileName)
			{
				// *** Open up the file
				$this->image = $this->openImage($fileName);

		    // *** Get width and height
		    $this->width  = imagesx($this->image);
		    $this->height = imagesy($this->image);
			}

			## --------------------------------------------------------

			private function openImage($file)
			{
				// *** Get extension
				$extension = strtolower(strrchr($file, '.'));

				switch($extension)
				{
					case '.jpg':
					case '.jpeg':
						$img = @imagecreatefromjpeg($file);
						break;
					case '.gif':
						$img = @imagecreatefromgif($file);
						break;
					case '.png':
						$img = @imagecreatefrompng($file);
						break;
					default:
						$img = false;
						break;
				}
				return $img;
			}

			## --------------------------------------------------------

			public function resizeImage($newWidth=0, $newHeight=0)
			{
				$optionArray = $this->getDimensions($newWidth, $newHeight);
        $optimalWidth  = $optionArray['optimalWidth'];
				$optimalHeight = $optionArray['optimalHeight'];


				// *** Resample - create image canvas of x, y size
				$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
				imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
			}

			## --------------------------------------------------------
			
			private function getDimensions($newWidth, $newHeight)
			{
			  if(($newWidth<1)AND($newHeight<1))
        {
          $optimalWidth=$this->width;
          $optimalHeight=$this->height;
        }else{
          if($newWidth<1)
            $ratio=$this->height/$newHeight;
          elseif($newHeight<1)
            $ratio=$this->width/$newWidth;
          else{  
            $wRatio=$this->width/$newWidth;
            $hRatio=$this->height/$newHeight;
            $ratio=max($wRatio,$hRatio);
          }
          if($ratio<1)
            $ratio=1;
          $optimalWidth=round($this->width/$ratio);
          $optimalHeight=round($this->height/$ratio);
        }  
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			public function saveImage($savePath, $imageQuality="100")
			{
				// *** Get extension
        		$extension = strrchr($savePath, '.');
       			$extension = strtolower($extension);

				switch($extension)
				{
					case '.jpg':
					case '.jpeg':
						if (imagetypes() & IMG_JPG) {
							imagejpeg($this->imageResized, $savePath, $imageQuality);
						}
						break;

					case '.gif':
						if (imagetypes() & IMG_GIF) {
							imagegif($this->imageResized, $savePath);
						}
						break;

					case '.png':
						// *** Scale quality from 0-100 to 0-9
						$scaleQuality = round(($imageQuality/100) * 9);

						// *** Invert quality setting as 0 is best, not 9
						$invertScaleQuality = 9 - $scaleQuality;

						if (imagetypes() & IMG_PNG) {
							 imagepng($this->imageResized, $savePath, $invertScaleQuality);
						}
						break;

					// ... etc

					default:
						// *** No extension - No save.
						break;
				}

				imagedestroy($this->imageResized);
			}


			## --------------------------------------------------------

		}
?>
