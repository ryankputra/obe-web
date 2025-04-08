<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\Filter;

class DosenFilter extends Filter {
    protected $safeParms = [
        'nidn' => ['eq','gt','lt'],
        'name' => ['eq'],
        'email' => ['eq'],
        'jabatan' => ['eq'],
        'kompetensi' => ['eq']
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '≤',
        'gt' => '>',
        'gte' => '≥',
    ];
}