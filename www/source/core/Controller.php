<?php

namespace App\core;

use Exception;

class Controller
{
    /**
     * Return page path to the view
     *
     * @param  string  $page  searched page name
     * @return string path to the searched page
     * @throws Exception
     */
    public function returnPagePath(string $page): string
    {
        $path = Application::get('config')['pagePath'];
        return $path['path'] . $page . $path['ext'];
    }
}