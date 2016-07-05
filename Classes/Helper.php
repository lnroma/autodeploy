<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 05.07.16
 * Time: 15:06
 */
namespace classes{

    /**
     * helper 
     * Class Helper
     * @package classes
     */
    class Helper {

        /**
         * get path to root directory script
         * @return mixed
         */
        public static function getPathToScript() {
            return realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        }

    }

}