<?php

test('global test')
    ->expect([
        'dd',
        'dump',
        'env',
        'ds',
        'var_dump',
        'ray',
    ])->not->toBeUsed();
