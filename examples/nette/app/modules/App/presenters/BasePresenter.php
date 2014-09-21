<?php
namespace App;

use Nette\Application\InvalidPresenterException;
use Nette\Forms\Container;
use Nette;
use Nextras\Forms\Controls\DatePicker;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	/** @var \WebLoader\LoaderFactory @inject */
	public $webLoader;

	/** @return CssLoader */
	protected function createComponentTexylaCSS()
	{
		return $this->webLoader->createCssLoader('texyla');
	}

	/** @return JavaScriptLoader */
	protected function createComponentTexyla()
	{
		return $this->webLoader->createJavaScriptLoader('texyla');
	}

	public function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		$template->texylaBaseUri = $this->getHttpRequest()->getUrl()->getBaseUrl();
		$template->texylaPreviewPath = $this->link(":App:Texyla:preview");
		$template->texylaFilesPath = $this->link(":App:Texyla:listFiles");
		$template->texylaFilesUploadPath = $this->link(":App:Texyla:upload");
		$template->texylaFilesMkDirPath = $this->link(":App:Texyla:mkDir");
		$template->texylaFilesRenamePath = $this->link(":App:Texyla:rename");
		$template->texylaFilesDeletePath = $this->link(":App:Texyla:delete");
		return $template;
	}

	/** @return CssLoader */
	protected function createComponentCss()
	{
		return $this->webLoader->createCssLoader('default');
	}

	/** @return JavaScriptLoader */
	protected function createComponentJs()
	{
		return $this->webLoader->createJavaScriptLoader('default');
	}
}
