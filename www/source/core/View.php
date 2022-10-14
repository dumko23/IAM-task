<?php

namespace App\core;

class View
{
    /**
     * Renders requested page with bound data
     *
     * @param  array  $view contains path to requested page and bound data
     * @return mixed
     */
    public function showView(array $view){
        if($view['data']){
            $data = $view['data'];
            extract($data);
        }

        return require $view['path'];
    }
}