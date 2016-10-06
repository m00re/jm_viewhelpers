<?php

namespace Jm\JmViewhelpers\ViewHelpers;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to add two numbers in Fluid
 *
 * # Example
 * <code>
 * {namespace d=Jm\JmViewhelpers\ViewHelpers}
 *
 * <d:sum a="1" b="2" />
 * </code>
 * 
 * <output>
 * 3
 * </output>
 *
 */
class SumViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('a', 'integer', 'First number for calculation.', TRUE);
		$this->registerArgument('b', 'integer', 'Second number for calculation.', TRUE);
	}
	
	/**
	 * Render share viewhelper
	 * @return string
	 */
	public function render() {
		if (isset($this->arguments['a']) && isset($this->arguments['b']))
		{
			return (intval($this->arguments['a']) + intval($this->arguments['b']));
		}
		return -1;
	}
}
