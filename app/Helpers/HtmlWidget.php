<?php

namespace App\Helpers;

class HtmlWidget
{
    public static function page(string $baseUrl, int $currentPage, int $total, int $size = PAGE_SIZE, int $span = PAGE_SPAN)
    {
        $html = '<nav aria-label="Page navigation"><ul class="pagination">';
        $totalPageNum = ceil($total / $size);
        if ($totalPageNum == 1) {
            return [0, 0, ''];
        }

        ($currentPage > $totalPageNum) && ($currentPage = $totalPageNum);

        $pageIndexes = [$currentPage];
        for ($i = 1; $i <= $span; $i++) {
            $pageIndex = $currentPage + $i;
            if ($pageIndex <= $totalPageNum) {
                $pageIndexes[] = $pageIndex;
            }

            $pageIndex = $currentPage - $i;
            if ($pageIndex > 0) {
                $pageIndexes[] = $pageIndex;
            }
        }

        sort($pageIndexes);

        $previousPage = $currentPage - 1;
        ($previousPage < 1) && ($previousPage = 1);

        $html .= sprintf('<li><a href="%s" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>', url($baseUrl . $previousPage));
        foreach ($pageIndexes as $pageIndex) {
            $active = '';
            if ($pageIndex == $currentPage) {
                $active = 'active';
            }
            $html .= sprintf('<li class="%s"><a href="%s">%s</a></li>', $active, url($baseUrl . $pageIndex), $pageIndex);
        }

        $nexPage = $currentPage + 1;
        ($nexPage > $totalPageNum) && ($nexPage = $totalPageNum);
        $html .= sprintf('<li><a href="%s" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>', url($baseUrl . $nexPage));

        $html .= '</ul></nav>';

        return [($currentPage - 1) * $size, $size, $html];
    }
}