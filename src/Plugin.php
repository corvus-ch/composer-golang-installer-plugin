<?php

namespace CorvusCH\Composer\GolangInstallerPlugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Registers the installer for Golang packages.
 */
class Plugin implements PluginInterface
{
    const TYPE = 'golang';

    /**
     * Registers the installer for Golang bin packages.
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new Installer($io, $composer, Plugin::TYPE);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}
