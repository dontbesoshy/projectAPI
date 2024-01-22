<?php

namespace App\Helpers;

class BuilderRelationMixin
{
    /**
     * Custom Paginate.
     *
     * @return \Closure
     */
    public function customPaginate(): \Closure
    {
        return (new CustomPaginate())->customPaginate();
    }
}
