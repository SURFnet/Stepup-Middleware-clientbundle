<?php

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupMiddlewareClient\Service;

use GuzzleHttp\ClientInterface;
use Surfnet\StepupMiddlewareClient\Exception\AccessDeniedToResourceException;
use Surfnet\StepupMiddlewareClient\Exception\MalformedResponseException;
use Surfnet\StepupMiddlewareClient\Exception\ResourceReadException;

/**
 * Provides remote read access to the Middleware's API.
 */
class ApiService
{
    /**
     * @var ClientInterface
     */
    private $guzzleClient;

    /**
     * @param ClientInterface $guzzleClient A Guzzle client preconfigured with base URL and proper authentication.
     */
    public function __construct(ClientInterface $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param string $resource A URL path, optionally containing printf parameters (e.g. '/a/b/%s/d'). The parameters
     *               will be URL encoded and formatted into the path string.
     *               Example: '/institution/%s/identity/%s', ['institution' => 'ab-cd', 'identity' => 'ef']
     * @param array $parameters An array containing the parameters to replace in the path.
     * @return null|mixed Most likely an array structure, null when the resource doesn't exist.
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function read($resource, array $parameters = [])
    {
        $resource = vsprintf($resource, array_map('urlencode', $parameters));
        $response = $this->guzzleClient->get($resource, ['exceptions' => false]);
        $statusCode = $response->getStatusCode();

        try {
            $data = $response->json();
            $errors = isset($data['errors']) && is_array($data['errors']) ? $data['errors'] : [];
        } catch (\RuntimeException $e) {
            // Malformed JSON body
            throw new MalformedResponseException('Cannot read resource: Middleware return malformed JSON');
        }

        if ($statusCode == 404) {
            return null;
        }

        if ($statusCode == 403) {
            // Consumer requested resource it is not authorised to access.
            throw new AccessDeniedToResourceException($resource, $errors);
        }

        if ($statusCode < 200 || $statusCode >= 300) {
            throw new ResourceReadException(
                sprintf('Resource could not be read (status code %d)', $statusCode),
                $errors
            );
        }

        return $data;
    }
}
