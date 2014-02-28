<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 17.1.14
 * Time: 21:23
 */

namespace AnnotateCms\Packages\Loaders;


use AnnotateCms\Framework\Templating\TemplateFactory;
use AnnotateCms\Packages\Package;
use AnnotateCms\Packages\ThemeAsset;
use AnnotateCms\Themes\Loaders\ThemesLoader;
use AnnotateCms\Themes\Theme;
use Kdyby\Events\Subscriber;
use Nette\Templating\ITemplate;

class AssetsLoader implements Subscriber
{

	const classname = __CLASS__;

	private $styles = array();

	private $scripts = array();

	/** @var Package[] */
	private $packages = array();


	public function addStyles($styles)
	{
		$this->styles = array_merge($this->styles, $styles);
	}


	public function addScripts($scripts)
	{
		$this->scripts = array_merge($this->scripts, $scripts);
	}


	public function addPackage(Package $package)
	{
		$this->packages[] = $package;
	}


	public function getStyles()
	{
		return $this->styles;
	}


	public function getScripts()
	{
		return $this->scripts;
	}


	public function getSubscribedEvents()
	{
		return array(
			TemplateFactory::classname . "::onSetupTemplate",
			ThemesLoader::classname . "::onActivateTheme",
		);
	}


	public function onActivateTheme(Theme $theme)
	{
		$styles = $theme->getStyles();
		$stylesAssets = [];
		foreach ($styles as $style) {
			$stylesAssets[] = new ThemeAsset($theme, $style);
		}
		$this->addStyles($stylesAssets);

		$scripts = $theme->getScripts();
		$scriptsAssets = [];
		foreach ($scripts as $script) {
			$scriptsAssets[] = new ThemeAsset($theme, $script);
		}
		$this->addScripts($scriptsAssets);
	}


	public function onSetupTemplate(ITemplate $template)
	{
		$template->styles = $this->styles;
		$template->scripts = $this->scripts;
	}

} 