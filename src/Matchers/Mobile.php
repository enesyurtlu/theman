<?php

namespace enesyurtlu\theman\Matchers;

class Mobile implements MatcherInterface
{
    public function handle($request, $match)
    {
        return ($match === "true") ? true: false;
    }
}
