<?php

namespace Karzac\Forms;

/**
 * @property \Nette\Http\FileUpload|NULL $fileUpload
 * @property bool $deleted
 */
class Attachment
{
	use \Nette\SmartObject;

	private $deleted;

	private $fileUpload;



	public function __construct(\Nette\Http\FileUpload $fileUpload = NULL)
	{
		$this->fileUpload = $fileUpload;
	}



	public function isFilled()
	{
		return (bool) $this->fileUpload->isOk();
	}



	public function setDeleted($value)
	{
		$this->deleted = $value;
	}



	public function getFileUpload()
	{
		return $this->fileUpload;
	}



	public function isDeleted()
	{
		return (bool)$this->deleted;
	}


}