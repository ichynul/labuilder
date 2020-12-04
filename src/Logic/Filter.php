<?php

namespace Ichynul\Labuilder\Logic;

use \Ichynul\Labuilder\Common\Search;

class Filter
{
    /**
     * Undocumented function
     *
     * @param Search $search
     * @param array $searchData
     * @return array
     */
    public function getQuery($search, $searchData)
    {
        $where = [];
        $data = request()->post();

        $rows = $search->getRows();

        $comumn = '';

        foreach ($rows as $row) {

            $comumn = $row->getName();

            if (isset($data[$comumn]) && $data[$comumn] !== '' && $data[$comumn] !== []) {

                $filter = $row->getFilter() ?: 'like';

                if (is_array($searchData[$comumn])) {
                    $filter = 'in';
                }
                if ($filter == 'like') {
                    $where[] = [$comumn, $filter, "%{$data[$comumn]}%"];
                } else {
                    $where[] = [$comumn, $filter, $data[$comumn]];
                }
            }
        }

        return $where;
    }
}
