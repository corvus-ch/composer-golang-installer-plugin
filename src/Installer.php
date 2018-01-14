<?php

namespace CorvusCH\Composer\GolangInstallerPlugin;

use Composer\Installer\LibraryInstaller;
use Composer\Package\CompletePackage;
use Composer\Package\PackageInterface;

/**
 * Installer replaces dist download information before delegating installation to the default library installer.
 */
class Installer extends LibraryInstaller
{
    protected function installCode(PackageInterface $package)
    {
        if ($package instanceof CompletePackage) {
            $extra = Configuration::crateFromPackage($package);
            $package->setDistReference($package->getVersion());
            $package->setDistUrl($extra->getUrl());
            $package->setDistType($extra->getType());
        }
        parent::installCode($package);
    }
}
