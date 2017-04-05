<?php
namespace  {
    exit("This file should not be included, only analyzed by your IDE");
}

namespace Ludo\Support\Facades {

    class Crypt {
        /**
         * Encrypt the given value.
         *
         * @param $value
         * @return string
         */
        public static function encrypt($value) {
            return \Ludo\Encrypter\Encrypter::encrypt($value);
        }

        /**
         * Decrypt the given value.
         *
         * @param $payload
         * @return string
         */
        public static function decrypt($payload) {
            return \Ludo\Encrypter\Encrypter::decrypt($payload);
        }

        /**
         * Determine if the given key and cipher combination is valid.
         *
         * @param $key
         * @param $cipher
         * @return bool
         */
        public static function supported($key, $cipher) {
            return \Ludo\Encrypter\Encrypter::supported($key, $cipher);
        }

    }
}

namespace {
    class Crypt extends \Ludo\Support\Facades\Crypt {}

    class Filter extends \Ludo\Support\Filter {}

    class Validator extends \Ludo\Support\Validator {}

    class Lang extends \Ludo\Foundation\Lang {}

    class Config extends \Ludo\Config\Config {}

    class Factory extends \Ludo\Support\Factory {}

    class View extends \Ludo\View\View {}

    class TaskQueue extends \Ludo\Task\TaskQueue {}

    class QueryException extends \Ludo\Database\QueryException {}

    class Counter extends \Ludo\Counter\Counter {}

}
