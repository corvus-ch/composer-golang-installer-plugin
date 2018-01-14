<?php

namespace CorvusCH\Composer\GolangInstallerPlugin;


use Composer\Package\PackageInterface;

/**
 * Representation of the Golang Installer Plugin configuration.
 */
class Configuration
{
    const EXTRA_KEY = 'golang';

    /**
     * @var string[]
     */
    private $archMap;

    /**
     * @var string[]
     */
    private $osMap;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $pattern;

    /**
     * Creates a new Golang extra configuration instance from a package.
     *
     * @param PackageInterface $pkg
     * @return Configuration
     */
    public static function crateFromPackage(PackageInterface $pkg)
    {
        $extra = $pkg->getExtra();
        if (empty($extra[Configuration::EXTRA_KEY])) {
            $msg = sprintf("Package is missing the '%s' extra configuration", Configuration::EXTRA_KEY);
            throw new \InvalidArgumentException($msg);
        }
        $goBin = $extra[Configuration::EXTRA_KEY];
        return new self(
            $pkg->getPrettyVersion(),
            isset($goBin['type']) ? $goBin['type'] : 'tar',
            isset($goBin['url_pattern']) ? $goBin['url_pattern'] : null,
            isset($goBin['arch_map']) ? $goBin['arch_map'] : array(),
            isset($goBin['os_map']) ? $goBin['os_map'] : array()
        );
    }

    /**
     * @param string $version
     * @param string $type
     * @param string $pattern
     * @param string[] $archMap
     * @param string[] $osMap
     */
    public function __construct($version, $type, $pattern, array $archMap, array $osMap)
    {
        $this->archMap = $archMap;
        $this->osMap = $osMap;
        $this->type = $type;
        $this->version = $version;
        $this->pattern = $pattern;
    }

    /**
     * @return string
     */
    public function getArch()
    {
        $arch = strtolower(php_uname('m'));

        return isset($this->archMap[$arch]) ? $this->archMap[$arch] : $arch;
    }

    /**
     * @return string
     */
    public function getOS()
    {
        $os = strtolower(php_uname('s'));

        return isset($this->osMap[$os]) ? $this->osMap[$os] : $os;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return strtr($this->pattern, array(
            '{os}' => $this->getOS(),
            '{arch}' => $this->getArch(),
            '{version}' => $this->version,
        ));
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
