<?php

namespace enesyurtlu\TheMan\Matchers;

interface MatcherInterface
{
    public function handle($request, $match);
}
