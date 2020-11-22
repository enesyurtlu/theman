<?php

namespace enesyurtlu\theman;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class TheMan
{
    protected $theme = null;

    /**
     * Getter for the theme that is chosen.
     *
     * @return string theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Get the name of the theme for this context.
     * Make its path available for view selection
     * and override config settings where needed.
     *
     * @param Request $request
     * @return void
     */
    public function setTheme($request)
    {
        $this->findMatch($request);
        $this->addThemeViewPath();
        $this->applyThemeConfig();
    }

    /**
     * Iterate over the match criteria until a successful match is achieved.
     *
     * @return void
     */
    protected function findMatch($request)
    {
        if(app('config')->get('theman') !== null) {
            $config = app('config')->get('theman');
        } else {
            $config = [
                "theman" => [
                    [
                        'theme' => null
                    ],
                ]
            ];
        }

        foreach ($config["theman"] as $match) {
            if ($this->testMatch($request, $match)) {
                break;
            }
        }
    }

    /**
     * If there's no match critera or all rules are matched, set the theme and exit.
     *
     * @return boolean
     */
    protected function testMatch($request, $match)
    {
        if (isset($match['match'])) {
            $rules = $this->explodeRules($match['match']);

            foreach ($rules as $rule) {
                if (!$this->handleRule($request, $rule)) {
                    return false;
                }
            }
        }
        // nothing to match or all matches succeeded
        $this->theme = $match["theme"];
        App::singleton('theme', function () use ($request) {
            return $this->theme;
        });

        return true;
    }

    /**
     * Remove whtespace from rules and separate the rules in a match group.
     *
     * @return array rules
     */
    protected function explodeRules($rules)
    {
        $rules = preg_replace('/\s+/', '', $rules);
        return explode('|', $rules);
    }

    /**
     * Separate rule name from parameters and pass to matcher class to evaluate.
     *
     * @return boolean
     */
    protected function handleRule($request, $rule)
    {
        $rule = explode(':', $rule);
        $params = isset($rule[1]) ? $rule[1] : null;
        $class = '\\enesyurtlu\\theman\\Matchers\\' . Str::studly($rule[0]);;

        return (new $class)->handle($request, $params);
    }

    /**
     * Add path for current theme's views to the list that Laravel searches.
     *
     * @return void
     */
    protected function addThemeViewPath()
    {
        if ($this->theme) {
            app('view.finder')->addLocation(themes_path($this->theme));
        }
    }

    /**
     * Override configs with theme-specific settings.
     *
     * @return void
     */
    protected function applyThemeConfig()
    {
        $path = themes_path($this->theme . '/config');

        if (app('files')->exists($path)) {
            foreach (app('files')->allFiles($path) as $file) {
                $key = str_replace('.' . $file->getExtension(), '', $file->getFilename());
                app('config')->set($key, require $file->getPathname());
            }
        }
    }
}
