<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Derick Alangi
 */

namespace MediaWiki\Preferences;

use ExtensionRegistry;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserIdentity;
use MessageLocalizer;
use ParserOptions;

/**
 * @since 1.38
 */
class SignatureValidatorFactory {
	/** @var ServiceOptions */
	private $serviceOptions;

	/** @var callable */
	private $parserFactoryClosure;

	/** @var callable */
	private $parsoidClosure;

	private PageConfigFactory $pageConfigFactory;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var TitleFactory */
	private $titleFactory;

	private ExtensionRegistry $extensionRegistry;

	/**
	 * @param ServiceOptions $options
	 * @param callable $parserFactoryClosure A function which returns a ParserFactory.
	 *   We use this instead of an actual ParserFactory to avoid a circular dependency,
	 *   since Parser also needs a SignatureValidatorFactory for signature formatting.
	 * @param callable $parsoidClosure A function which returns a Parsoid, same as above.
	 * @param PageConfigFactory $pageConfigFactory
	 * @param SpecialPageFactory $specialPageFactory
	 * @param TitleFactory $titleFactory
	 * @param ExtensionRegistry $extensionRegistry
	 */
	public function __construct(
		ServiceOptions $options,
		callable $parserFactoryClosure,
		callable $parsoidClosure,
		PageConfigFactory $pageConfigFactory,
		SpecialPageFactory $specialPageFactory,
		TitleFactory $titleFactory,
		ExtensionRegistry $extensionRegistry
	) {
		// Configuration
		$this->serviceOptions = $options;
		$this->serviceOptions->assertRequiredOptions( SignatureValidator::CONSTRUCTOR_OPTIONS );
		$this->parserFactoryClosure = $parserFactoryClosure;
		$this->parsoidClosure = $parsoidClosure;
		$this->pageConfigFactory = $pageConfigFactory;
		$this->specialPageFactory = $specialPageFactory;
		$this->titleFactory = $titleFactory;
		$this->extensionRegistry = $extensionRegistry;
	}

	/**
	 * @param UserIdentity $user
	 * @param MessageLocalizer|null $localizer
	 * @param ParserOptions $popts
	 * @return SignatureValidator
	 */
	public function newSignatureValidator(
		UserIdentity $user,
		?MessageLocalizer $localizer,
		ParserOptions $popts
	): SignatureValidator {
		return new SignatureValidator(
			$this->serviceOptions,
			$user,
			$localizer,
			$popts,
			( $this->parserFactoryClosure )(),
			( $this->parsoidClosure )(),
			$this->pageConfigFactory,
			$this->specialPageFactory,
			$this->titleFactory,
			$this->extensionRegistry
		);
	}
}
