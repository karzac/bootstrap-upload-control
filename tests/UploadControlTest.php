<?php

namespace Karzac\Forms\Tests;

use Karzac\Forms\UploadControl;
use Nette\Forms;
use Nette\InvalidStateException;

class UploadControlTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @var UploadControl
	 */
	private $uploadControl;


	protected function setUp(): void
	{
		$this->uploadControl = new UploadControl();
	}


	// public function testHtml()
	// {
	// 	$this->uploadControl->setValue('some-slug');


	// 	$dq = Tester\DomQuery::fromHtml((string) $control->getControl());
	// 	Assert::true($dq->has("input[value='some-slug']"));
	// }


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


	public function testRegistrationMultiple()
	{
		$this->expectException(InvalidStateException::class);

		UploadControl::register();
		UploadControl::register();
	}

}