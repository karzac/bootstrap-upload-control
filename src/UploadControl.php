<?php

/**
 * Copyright (c) 2015 Petr Dvořák
 *
 * For the full copyright and license information,
 * please view the file LICENSE.md that was distributed with this source code.
 */

namespace Karzac\Forms;

use Latte;
use Nette;
use Nette\Application\UI;
use Nette\Bridges;
use Nette\Forms;
use Nette\Http\FileUpload;
use Nette\Utils\Html;

class UploadControl extends Forms\Controls\UploadControl
{
	/**
	 * @var bool
	 */
	private static $registered = FALSE;

	/**
	 * @var ITemplate
	 */
	private $template;

	private $path;

	private $delete = FALSE;



	/**
	 * This method will be called when the component becomes attached to Form
	 *
	 * @param  Nette\ComponentModel\IComponent
	 */
	public function attached($form)
	{
		parent::attached($form);
		$this->template = $this->createTemplate();
	}



	/**
	 * @return \Nette\Bridges\ApplicationLatte\Template
	 */
	protected function createTemplate()
	{
		$latte = new Latte\Engine;
		$latte->onCompile[] = function ($latte) {
			Bridges\FormsLatte\FormMacros::install($latte->getCompiler());
		};
		return new Bridges\ApplicationLatte\Template($latte);
	}



	/**
	 * @return UI\ITemplate|Bridges\ApplicationLatte\Template
	 */
	public function getTemplate()
	{
		if ($this->template === NULL) {
			$this->template = $this->createTemplate();
		}
		return $this->template;
	}



	public function setValue($value)
	{
		$this->path = $value;
		return $this;
	}



	public function getValue()
	{
		$attachment = new Attachment($this->value);
		$attachment->deleted = $this->delete;
		return $attachment;
	}



	/**
	 * Loads HTTP data.
	 * @return void
	 */
	public function loadHttpData()
	{
		$this->path = $this->getForm()->getHttpData(Nette\Forms\Form::DATA_LINE, $this->getHtmlName() . "-path");
		$this->delete = (bool)$this->getForm()->getHttpData(Nette\Forms\Form::DATA_LINE, $this->getHtmlName() . "-removed");
		parent::loadHttpData();
	}



	/**
	 * Generates control's HTML element.
	 */
	public function getControl()
	{
		$input = parent::getControl();

		if ($this->path) {
			$this->template->pathName = $this->getHtmlName() . "-path";
			$this->template->path = $this->path;
			$this->template->setFile(__DIR__ . "/templates/edit.latte");
		} else {
			$this->template->setFile(__DIR__ . "/templates/add.latte");
		}

		$this->template->removedName = $this->getHtmlName() . "-removed";
		$this->template->input = $input;
		$this->template->_form = $this->getForm();

		return Html::el()->add((string)$this->template);
	}



	/**
	 * @param string $method
	 * @throws \Nette\InvalidStateException
	 */
	public static function register($method = 'addAttachment')
	{
		if (static::$registered) {
			throw new \Nette\InvalidStateException('Upload control already registered.');
		}
		static::$registered = TRUE;
		\Nette\Forms\Container::extensionMethod($method, function (\Nette\Forms\Container $form, $name, $label = NULL) {
			$component = new static($label);
			$form->addComponent($component, $name);
			return $component;
		}
		);
	}


}