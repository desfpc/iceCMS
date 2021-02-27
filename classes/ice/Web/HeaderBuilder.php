<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Site Header Builder Class
 *
 */

namespace ice\Web;

class HeaderBuilder {
    public $headers;

    public function printHeaders()
    {
        if(is_array($this->headers) && count($this->headers) > 0)
        {
            foreach ($this->headers as $header)
            {
                header($header);
            }
        }
    }

    public function standartHeaders()
    {
        $this->headers=array(
            'X-Powered-By: newtons',
            'Server: Summit',
            'expires: mon, 26 jul 2000 05:00:00 GMT',
            'cache-control: no-cache, must-revalidate',
            'pragma: no-cache',
            'last-modified: '.gmdate('d, d m y h:i:s').' GMT',
            'X-Frame-Options: SAMEORIGIN',
            'X-XSS-Protection: 1; mode=block;',
            'X-Content-Type-Options: nosniff'
        );
    }

    public function addHeader(string $str)
    {
        if(!is_array($this->headers))
        {
            $this->headers=array();
        }
        $this->headers[]=$str;
    }

    public function addHeaders(array $arr)
    {
        if(!is_array($this->headers))
        {
            $this->headers=array();
        }
        foreach ($arr as $str)
        {
            $this->addHeader($str);
        }
    }

    public function __construct()
    {

    }
}