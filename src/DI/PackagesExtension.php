<?php

namespace AnnotateCms\Packages\DI;


use AnnotateCms\Packages\Loaders\AssetsLoader;
use AnnotateCms\Packages\Loaders\PackageLoader;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;


class PackagesExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->getDefaults());
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('packageLoader'))
				->setClass(PackageLoader::CLASSNAME, [
					'directories' => $config['directories'],
					'rootDir' => $config['rootDir'],
				])
			->addTag(EventsExtension::TAG_SUBSCRIBER);

		$builder->addDefinition($this->prefix('assetsLoader'))
				->setClass(AssetsLoader::CLASSNAME)
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}


	private function getDefaults()
	{
		return [
			'directories' => [
				'%wwwDir%/bower_components/',
			],
			'rootDir' => '%wwwDir%'
		];
	}

}
