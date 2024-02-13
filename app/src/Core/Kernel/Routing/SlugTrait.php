<?php
namespace Editiel98\Kernel\Routing;

trait SlugTrait {
     /**
     * @param string $url
     * 
     * @return array
     */
    private function checkUrlVars(string $url): array|false
    {
        $slug = '';
        if ($start = strpos($url, '{')) {
            if (strpos($url, '}')) {
                $slug = substr($url, $start + 1, -1);
                $length=strlen($url)-$start;
                $path= substr($url,0,$length-1);
                return array('slug'=>$slug,'path'=>$path);
            }
        }
        return false;
    }
}