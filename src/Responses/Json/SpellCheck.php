<?php

/**
 * Copyright (c) 2015 Maik Thieme <maik.thieme@gmail.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 *   * Neither the name of Maik Thieme nor the names of contributors
 *     may be used to endorse or promote products derived from this software
 *     without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT  * NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER ORCONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace phpsolr\Responses\json
{
    class SpellCheck
    {
        /**
         * @var array
         */
        private $response;

        /**
         * @var array
         */
        private $collations = array();

        /**
         * @param array $response
         */
        public function __construct(array $response)
        {
            $spellcheck = array();

            if (isset($response['spellcheck'])) {
                $spellcheck = $response['spellcheck'];
            }

            $this->response = $spellcheck;
        }

        /**
         * @return bool
         */
        public function hasCollations()
        {
            return isset($this->response['suggestions'][1]['numFound'])
                && $this->response['suggestions'][1]['numFound'] > 0;
        }

        /**
         * @return CollationQuery[]
         * @throws SpellCheckException
         */
        public function getCollationQueries()
        {
            if (!$this->hasCollations()) {
                throw new SpellCheckException();
            }

            foreach ($this->response['suggestions'] as $value) {
                if (!is_array($value) || !isset($value[0])) {
                    continue;
                }

                $this->collations[] = new CollationQuery($value);
            }

            return $this->collations;
        }
    }
}
