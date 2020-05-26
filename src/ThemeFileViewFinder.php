<?php

namespace enesyurtlu\theman;

use Illuminate\View\FileViewFinder as IlluminateFileViewFinder;

class ThemeFileViewFinder extends IlluminateFileViewFinder
{
    protected $extensions = ['theman.php', 'blade.php', 'php', 'css', 'html'];
    /**
     * Add a location to the finder.
     * Differs from standard Laravel as that adds locations to the end of
     * the array, but we want the added themes to be considered first
     *
     * @param  string  $location
     * @return void
     */
    public function addLocation($location)
    {
        array_unshift($this->paths, $location);
    }
}
