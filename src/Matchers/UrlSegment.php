<?php

namespace enesyurtlu\TheMan\Matchers;

class UrlSegment implements MatcherInterface
{
    public function handle($request, $match)
    {
        return $request->segment(1) == $match;
    }
}
