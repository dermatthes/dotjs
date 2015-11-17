<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 16:20
 */

namespace dotjs\server\core;


class FileSystemFileLoader implements FileLoader {

    private $mRootDir;

    public function __construct ($rootDir) {
        $this->mRootDir = $rootDir;
    }


    private $mExtensionRoot = NULL;

    public function setExtensionRoot ($dir) {
        $this->mExtensionRoot = $dir;
    }


    /**
     * Load the contents required by DOT.fileGetContents(fileName) and DOT.require(fileName) and DOT.run(fileName)
     *
     * @param $fileName
     * @return mixed
     */
    public function getContents($fileName) {
        if (substr($fileName, 0, 6) == "dot://") {
            $fqFileName = $this->mExtensionRoot . "/" . substr($fileName, 6);
        } else {
            $fqFileName = $this->mRootDir . "/" . $fileName;
        }

        if ( ! file_exists($fqFileName))
            throw new \InvalidArgumentException("Filename '$fileName': Not existing");

        return file_get_contents($fqFileName);
    }

}

