<?php

declare(strict_types=1);

/*
 * This file is part of the EasyWeChatComposer.
 *
 * (c) 张铭阳 <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChatComposer\Delegation;

use EasyWeChatComposer\Traits\MakesHttpRequests;

class DelegationTo
{
    use MakesHttpRequests;

    /**
     * @var \EasyWeChat\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var array
     */
    protected $identifiers = [];

    /**
     * @param \EasyWeChat\Kernel\ServiceContainer $app
     * @param string                              $identifier
     */
    public function __construct($app, $identifier)
    {
        $this->app = $app;

        $this->push($identifier);
    }

    /**
     * @param string $identifier
     */
    public function push($identifier)
    {
        $this->identifiers[] = $identifier;
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function __get($identifier)
    {
        $this->push($identifier);

        return $this;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $data = [
            'config' => $this->app->getConfig(),
            'application' => get_class($this->app),
            'identifiers' => $this->identifiers,
            'method' => $method,
            'arguments' => $arguments,
        ];

        return $this->request('easywechat-composer/delegate', $data);
    }
}
