<?php

/**
 * Copyright (c) 2015 Petr Dvořák
 *
 * For the full copyright and license information,
 * please view the file LICENSE.md that was distributed with this source code.
 */

namespace Karzac\Forms\DI;

use Nette;
use Nette\DI;
use Nette\PhpGenerator as Code;

class UploadControlExtension extends DI\CompilerExtension
{
	/**
	 * @param Code\ClassType $class
	 */
	public function afterCompile(Code\ClassType $class)
	{
		parent::afterCompile($class);
		$initialize = $class->methods['initialize'];
		$initialize->addBody('\Karzac\Forms\UploadControl::register();');
	}
}