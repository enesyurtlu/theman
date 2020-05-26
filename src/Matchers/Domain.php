<?php

namespace enesyurtlu\theman\Matchers;

class Domain implements MatcherInterface
{
    public function handle($request, $match)
    {
        return $request->getHost() == $match;
    }
}
