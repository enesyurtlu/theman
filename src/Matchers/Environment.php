<?php

namespace enesyurtlu\theman\Matchers;

class Environment implements MatcherInterface
{
    public function handle($request, $match)
    {
        return app()->environment() == $match;
    }
}
