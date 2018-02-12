<?php

declare(strict_types=1);

/*
 * This file is part of the EasyWeChatComposer.
 *
 * (c) mingyoung <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChatComposer\Commands;

use Composer\Plugin\Capability\CommandProvider;

class Provider implements CommandProvider
{
    /**
     * @return array
     */
    public function getCommands()
    {
        return [
            new ExtensionsCommand(),
        ];
    }
}
