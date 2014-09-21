<?php

namespace App\FrontModule;

class HomepagePresenter extends BasePresenter
{
	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentExample()
	{
		$form = new \Nette\Application\UI\Form();
		$form->addTextArea('x', NULL, 100, 10)
			->setAttribute('class', 'texyla');

		$form->addSubmit("send", "Send");
		$form->onSuccess[] = $this->testSucceeded;

		return $form;
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function testSucceeded(\Nette\Application\UI\Form $form)
	{
	}
}
