<?php
function buildQueryString($params, $overrides = []): string
{
    $merged = array_merge($params, $overrides);
    $filtered = array_filter($merged, function ($value) {
        return $value !== null && $value !== '';
    });
    return http_build_query($filtered);
}

$filters = $filters ?? [];
$limit = array_key_exists('limit', $filters) ? (int)$filters['limit'] : 10;
$offset = array_key_exists('offset', $filters) ? (int)$filters['offset'] : 0;
$count = $count ?? 0;
$pages = (int)ceil($count / $limit);

if ($pages > 0): ?>
    <nav aria-label="Project pagination">
        <ul class="pagination justify-content-center">

            <?php
            $pageIndex = intdiv($offset, $limit) + 1;
            $baseParams = $filters;
            $prevOffset = $offset - $limit;
            $nextOffset = $offset + $limit;
            ?>

            <li class="page-item <?= ($prevOffset <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?<?= buildQueryString($baseParams, ['offset' => $prevOffset]) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                            <path d="M6 12H18M6 12L11 7M6 12L11 17" stroke="#000000" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </li>

            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li class="page-item <?= ($pageIndex == $i) ? 'active' : '' ?>">
                    <a class="page-link"
                       href="?<?= buildQueryString($baseParams, ['offset' => ($i-1) * $limit]) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= ($pageIndex >= $pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?<?= buildQueryString($baseParams, ['offset' => $nextOffset]) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="#000000" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
