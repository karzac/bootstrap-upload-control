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
	private static bool $registered = FALSE;

	/**
	 * @var ITemplate
	 */
	private $template;

	private $path;

	private bool $delete = FALSE;



	/**
	 * This method will be called when the component becomes attached to Form
	 *
	 * @param  Nette\ComponentModel\IComponent
	 */
	public function attached(\Nette\ComponentModel\IComponent $form): void
	{
		parent::attached($form);
		$this->template = $this->createTemplate();
	}



	protected function createTemplate(): \Nette\Bridges\ApplicationLatte\Template
	{
		$latte = new Latte\Engine;
		$latte->onCompile[] = function ($latte): void {
			Bridges\FormsLatte\FormMacros::install($latte->getCompiler());
		};
		return new Bridges\ApplicationLatte\Template($latte);
	}



	/**
	 * @return UI\ITemplate|Bridges\ApplicationLatte\Template
	 */
	public function getTemplate(): \Karzac\Forms\ITemplate
	{
		if ($this->template === NULL) {
			$this->template = $this->createTemplate();
		}
		return $this->template;
	}



	public function setValue($value): self
	{
		$this->path = $value;
		return $this;
	}



	public function getValue(): \Karzac\Forms\Attachment
	{
		$attachment = new Attachment($this->value);
		$attachment->setDeleted($this->delete);
		return $attachment;
	}


	public function isPreset(): bool
	{
		return !empty($this->path);
	}



	public function isDeleted(): bool
	{
		return $this->delete;
	}



	/**
	 * Loads HTTP data.
	 */
	public function loadHttpData(): void
	{
		$this->path = $this->getForm()->getHttpData(Nette\Forms\Form::DATA_LINE, $this->getHtmlName() . "-path");
		$this->delete = (bool)$this->getForm()->getHttpData(Nette\Forms\Form::DATA_LINE, $this->getHtmlName() . "-removed");
		parent::loadHttpData();
	}



	/**
	 * Generates control's HTML element.
	 */
	public function getControl(): \Nette\Utils\Html
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

		return Html::el()->addHtml((string)$this->template);
	}



	/**
	 * @throws \Nette\InvalidStateException
	 */
	public static function register(string $method = 'addAttachment'): void
	{
		if (static::$registered) {
			throw new \Nette\InvalidStateException('Upload control already registered.');
		}
		static::$registered = TRUE;
		\Nette\Forms\Container::extensionMethod($method, function (\Nette\Forms\Container $form, $name, $label = NULL): self {
			$component = new static($label);
			$form->addComponent($component, $name);
			return $component;
		}
		);
	}


}