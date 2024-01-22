<?php

namespace App\Helpers;

class CustomPaginate
{
    /**
     * Wrap paginate.
     *
     * @return \Closure
     */
    public function customPaginate(): \Closure
    {
        return $this->paginate('paginate');
    }

    /**
     * My paginate.
     *
     * @param string $paginationMethod
     *
     * @return \Closure
     */
    protected function paginate(string $paginationMethod): \Closure
    {
        return function ($perPage = null, $columns = ['*'], $pageName = 'page', $page = null, $showEmptyPage = false) use (
            $paginationMethod,
        ) {
            $baseQuery = $this->query->clone();
            $result = $this->{$paginationMethod}($perPage, $columns, $pageName, $page);

            if (
                !$showEmptyPage
                && method_exists($result, 'lastPage')
                && $result->currentPage() > $result->lastPage()
            ) {
                $this->query = $baseQuery;
                $result = $this->{$paginationMethod}($perPage, $columns, $pageName, $result->lastPage());
            }

            return $result;
        };
    }
}
