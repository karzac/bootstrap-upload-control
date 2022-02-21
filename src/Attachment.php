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



	public function isFilled(): bool
	{
		return (bool) $this->fileUpload->isOk();
	}



	public function setDeleted($value): void
	{
		$this->deleted = $value;
	}



	public function getFileUpload(): ?\Nette\Http\FileUpload
	{
		return $this->fileUpload;
	}



	public function isDeleted(): bool
	{
		return (bool)$this->deleted;
	}


}