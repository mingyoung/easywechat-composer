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

namespace EasyWeChatComposer\Http;

use EasyWeChat\Kernel\Contracts\Arrayable;
use EasyWeChat\Kernel\Http\Response as HttpResponse;
use EasyWeChatComposer\Exceptions\UnexpectedCodeException;
use JsonSerializable;

class Response implements Arrayable, JsonSerializable
{
    /**
     * @var \EasyWeChat\Kernel\Http\Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $responseArray;

    /**
     * @param \EasyWeChat\Kernel\Http\Response $response
     *
     * @throws \EasyWeChatComposer\Exceptions\UnexpectedCodeException
     */
    public function __construct(HttpResponse $response)
    {
        $this->response = $response;

        // UnexpectedCodeException::check($this->toArray());
    }

    public function offsetExists($offset)
    {
        return array_has($this->toArray(), $offset);
    }

    public function offsetGet($offset)
    {
        return array_get($this->toArray(), $offset);
    }

    public function offsetSet($offset, $value)
    {
        //
    }

    public function offsetUnset($offset)
    {
        //
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->responseArray ?: $this->responseArray = $this->response->toArray();
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
