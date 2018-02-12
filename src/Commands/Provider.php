<?php

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
