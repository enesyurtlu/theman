<?php

namespace enesyurtlu\theman\Matchers;

interface MatcherInterface
{
    public function handle($request, $match);
}
