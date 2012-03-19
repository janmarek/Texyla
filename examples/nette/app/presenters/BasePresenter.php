<?php
/**
 * Base class for all application presenters
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	/** @var bool */
	public $oldLayoutMode = false;



	/**
	 * Texyla loader factory
	 * @return TexylaLoader
	 */
	protected function createComponentTexyla()
	{
		$baseUri = $this->context->httpRequest->url->baseUrl;
		$filter = new WebLoader\Filter\VariablesFilter(array(
			"baseUri" => $baseUri,
			"previewPath" => $this->link("Texyla:preview"),
			"filesPath" => $this->link("Texyla:listFiles"),
			"filesUploadPath" => $this->link("Texyla:upload"),
			"filesMkDirPath" => $this->link("Texyla:mkDir"),
			"filesRenamePath" => $this->link("Texyla:rename"),
			"filesDeletePath" => $this->link("Texyla:delete"),
		));

		$texyla = new TexylaLoader($filter, $baseUri."webtemp");
		return $texyla;
	}
}
