<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 16.11.15
 * Time: 16:53
 */


namespace dotjs\server\v10;


class Feature_OUT {

    public function __construct () {
    }


    private $mOutBuffer = "";

    private $mOmitPrint = FALSE;

    public function SET_OMIT_PRINT ($val) {
        $this->mOmitPrint = $val;
    }


    /**
     * DOT_BRIDGE.FS_INCLUDE()
     * -> FS.include()
     *
     * @param $fileName
     */
    public function OUT_PRINT($string) {
        if ( ! $this->mOmitPrint)
            $this->mOutBuffer .= $string;
    }

    public function getOutBuffer() {
        return $this->mOutBuffer;
    }


}