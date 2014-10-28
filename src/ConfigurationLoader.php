<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak;

use Yosymfony\Toml\Toml;
use cloak\configuration\Root;


/**
 * Class ConfigurationLoader
 * @package cloak
 */
class ConfigurationLoader
{

    /**
     * @param string $configFilePath
     * @return \cloak\Configuration
     */
    public function loadConfiguration($configFilePath)
    {
        $configValues = Toml::Parse($configFilePath);

        $root = new Root($configValues);
        $builder = $root->applyTo(new ConfigurationBuilder());

        return $builder->build();
    }

}
