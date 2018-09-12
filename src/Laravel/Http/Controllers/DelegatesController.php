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

namespace EasyWeChatComposer\Laravel\Http\Controllers;

use EasyWeChatComposer\Delegation\Hydrate;
use EasyWeChatComposer\Encryption\DefaultEncrypter;
use Illuminate\Http\Request;

class DelegatesController
{
    /**
     * @param \Illuminate\Http\Request                        $request
     * @param \EasyWeChatComposer\Encryption\DefaultEncrypter $encrypter
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, DefaultEncrypter $encrypter)
    {
        try {
            $data = json_decode($encrypter->decrypt($request->get('encrypted')), true);

            $hydrate = new Hydrate($data);

            $response = $hydrate->handle();

            return response()->json([
                'response_type' => get_class($response),
                'response' => $encrypter->encrypt($response->getBodyContents()),
            ]);
        } catch (\Exception $e) {
            return [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ];
        }
    }
}
