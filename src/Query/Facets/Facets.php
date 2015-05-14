<?php

namespace phpsolr\queries\facets
{
    class Facets
    {
        /**
         * @var array
         */
        private $fields = array();

        /**
         * @var array
         */
        private $queries = array();

        /**
         * @param string $name
         * @return AbstractField
         */
        public function createField($name)
        {
            $field = new Field($name);

            $this->fields[$name] = $field;
            return $field;
        }

        /**
         * @param array $fields
         */
        public function setFields(array $fields)
        {
            $this->fields = array_merge($fields, $this->fields);
        }

        /**
         * @param string $name
         * @return Query
         */
        public function createQuery($name)
        {
            $query = new Query($name);

            $this->queries[$name] = $query;
            return $query;
        }

        /**
         * @param array $queries
         */
        public function setQueries(array $queries)
        {
            $this->queries = array_merge($queries, $this->queries);
        }

        /**
         * @return bool
         */
        public function hasParameters()
        {
            return count($this->fields) > 0
                || count($this->queries) > 0;
        }

        /**
         * @return array
         */
        public function getParameters()
        {
            $fields = array();
            $queries = array();

            foreach ($this->fields as $field) {
                $fields['facet.field'][] = (string) $field;
            }

            foreach ($this->queries as $query) {
                $queries['facet.query'][] = (string) $query;
            }

            return array_merge($fields, $queries);
        }
    }
}