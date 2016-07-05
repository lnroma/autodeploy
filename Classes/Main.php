<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 05.07.16
 * Time: 15:00
 *
 *
 */
namespace classes {
    /**
     * class main realisation logic for automaticaly deployment server and client
     * Class Main
     *
     * @method getPathToProject
     * @method getBranch
     * @method getDelay
     * @method getAutoreset
     * @method getServerList
     * @method getPathToPhp
     * @method getServerFile
     *
     * @package classes
     */
    class Main
    {
        /**
         * configuration
         * @var mixed
         */
        private $_configuration;

        /**
         * init configuration file
         * Main constructor.
         */
        public function __construct()
        {
            if (!file_exists(Helper::getPathToScript() . 'autodeploy.php')) {
                throw new \Exception('Your file configuration not exist');
            }
            $this->_configuration = include Helper::getPathToScript() . 'autodeploy.php';
            foreach ($this->_configuration as $_key => $_config) {
                unset($this->_configuration[$_key]);
                $this->_configuration[strtolower($_key)] = $_config;
            }

        }

        /**
         * get argument command line
         * @param null $key
         * @return bool|mixed|null
         */
        public function getArgument($key = null)
        {
            $result = array();
            foreach ($_SERVER['argv'] as $_arg) {

                if (strpos($_arg, '--') === false) {
                    continue;
                }

                $_arg = trim($_arg, '--');

                if (strpos($_arg, '=') === false) {
                    $result[$_arg] = true;
                    continue;
                }

                $_argExpl = explode('=', $_arg);

                if (isset($_argExpl[1])) {
                    $result[$_argExpl[0]] = $_argExpl[1];
                } else {
                    $result[$_argExpl[1]] = true;
                }

            }
            if($key==null) {
                return $key;
            } else {
                if(isset($result[$key])) {
                    return $result[$key];
                } else {
                    return false;
                }
            }
        }

        /**
         * run deploy api for server
         */
        public function runDeployListern()
        {
            echo 'this autodeploy server';
            if (isset($_GET['command'])) {

                if ($_GET['command'] == 'deploy') {

                    $pathToProject = $this->getPathToProject();
                    $branch = $this->getBranch();

                    if (is_null($pathToProject)) {
                        throw new \Exception('Your pathToProject is require parameters see autodeploy.php');
                    }

                    if (is_null($branch)) {
                        $branch = 'master';
                    }

                    if ($this->getAutoreset()) {
                        echo system("cd $pathToProject && git reset HEAD --hard");
                    }

                    echo system("cd $pathToProject && git pull origin $branch");
                }
            }
        }

        /**
         * send deploy command to server
         */
        public function sendDeployCommand()
        {
            $result = file_get_contents('http://'.$this->getServerList() . '?command=deploy');
            if (!$result) {
                throw new \Exception('your deploy server not avaliable ' . $this->getServerList());
            }
        }

        /**
         * run server listener for deploy
         */
        public function runDeployServer()
        {
            $pathToPHP = $this->getPathToPhp();

            if (is_null($pathToPHP)) {
                $pathToPHP = '/usr/bin/php';
            }

            $listServer = $this->getServerList();

            if (is_null($listServer)) {
                throw new \Exception('serverList is requer paramaters in file config autodeploy.php');
            }

            $serverFile = $this->getServerFile();

            if (is_null($serverFile)) {
                $serverFile = 'server.php';
            }

            echo system("$pathToPHP -S $listServer $serverFile");
        }

        /**
         * getters realization for configuration and other methods
         * @param $name
         * @param $arguments
         * @return null
         */
        public function __call($name, $arguments)
        {
            if (strpos($name, 'get') !== false) {
                $key = substr($name, 3, strlen($name));
                $key = strtolower($key);
                if (isset($this->_configuration[$key])) {
                    return $this->_configuration[$key];
                } else {
                    return null;
                }
            }
        }

    }
}