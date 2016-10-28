<?php
/**
 * Copyright 2016 Bas de Nooijer. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this listof conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are
 * those of the authors and should not be interpreted as representing official
 * policies, either expressed or implied, of the copyright holder.
 *
 * @copyright Copyright 2016 Bas de Nooijer <solarium@raspberry.nl>
 * @license http://github.com/basdenooijer/solarium/raw/master/COPYING
 *
 * @link http://www.solarium-project.org/
 */

/**
 * @namespace
 */

namespace Solarium\QueryBuilder;
use Solarium\Core\Client\Request;

/**
 * Class for building Solarium query objects from requests.
 */
abstract class AbstractQueryBuilder
{
    /**
     * @param Request $request
     * @param string $paramName
     * @return array
     */
    protected function getParamAsArray(Request $request, $paramName)
    {
        $param = $request->getParam($paramName);

        if ($param === null) {
            return array();
        }

        if (!is_array($param)) {
            $param = explode(',', $param);
        }

        return $param;
    }

    /**
     * @param $paramString
     * @param string $mainValueKey
     * @param array $mapping
     * @return array
     */
    protected function parseLocalParams($paramString, $mainValueKey, $mapping = array())
    {
        if (empty($paramString)) {
            return array();
        }

        preg_match('/(\{\!(.*)\})?(.*)/u', $paramString, $matches);
        $result = array($mainValueKey => $matches[3]);

        if (!empty($matches[2])) {
            preg_match_all('/([\S]+)=([\S]+)/u', $matches[2], $paramMatches);

            foreach ($paramMatches[1] as $index => $key) {
                if (array_key_exists($key, $mapping)) {
                    $key = $mapping[$key];
                }

                $result[$key] = $paramMatches[2][$index];
            }

        }

        return $result;
    }

    /**
     * @param Request $request
     * @param string $paramName
     * @return bool
     */
    protected function getParameterAsBoolean(Request $request, $paramName)
    {
        $param = $request->getParam($paramName);
        return (is_string($param) && strtolower($param) === 'true');
    }
}
