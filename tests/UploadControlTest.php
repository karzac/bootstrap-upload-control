<?php

namespace Karzac\Forms\Tests;

use PHPUnit_Framework_TestCase;
use Karzac\Forms\UploadControl;
use Nette\Forms;

class UploadControlTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var UploadControl
	 */
	private $uploadControl;


	protected function setUp()
	{
		$this->uploadControl = new UploadControl();
	}


	public function testHtml()
	{
		$this->uploadControl->setValue('some-slug');


//		$dq = Tester\DomQuery::fromHtml((string) $control->getControl());
//		Assert::true($dq->has("input[value='some-slug']"));
	}


	public function testRegistration()
	{
		UploadControl::register();
		$form = new Forms\Form;
		$control = $form->addAttachment('file', 'File');

		$this->assertInstanceOf('Karzac\Forms\UploadControl', $control);
		$this->assertSame('file', $control->getName());
		$this->assertSame('File', $control->caption);
		$this->assertSame($form, $control->getForm());
	}

	/**
	 * @expectedException \Nette\InvalidStateException
	 */
	public function testRegistrationMultiple()
	{
		UploadControl::register();
		UploadControl::register();
	}

}