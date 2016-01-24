<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Question as Question;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Question Image utils
 */
class Image
{
	const IMAGE_DIR_PATH = 'img/questions/';
	
	const WEB_URL = '/../web/';

	private $question;

	private $kernel;

	public function __construct(Question $question, KernelInterface $kernel)
	{
		$this->kernel = $kernel;
		$this->question = $question;
	}

    public function getImageName()
    {
        return self::IMAGE_DIR_PATH. 'q'. $this->question->getId(). '.png';
    }

    public function getImagePath()
    {
        return $this->kernel->getRootDir(). self::WEB_URL. $this->getImageName();
    }

    public function getImageAssetPath()
    {
        if (file_exists($this->getImagePath())) {
            return $this->getImageName();
        }
        return false;
    }
}