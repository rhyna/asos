<?php


class Paginator
{
    public $limit;
    public $offset;
    public $previous;
    public $next;
    public $totalPages;

    /**
     * Paginator constructor.
     * @param int $page
     * @param int $itemsPerPage
     * @param int $totalItems
     */
    public function __construct(int $page, int $itemsPerPage, int $totalItems)
    {
        $this->limit = $itemsPerPage;

        $page = filter_var($page, FILTER_VALIDATE_INT, [
            'options' => [
                'default' => 1,
                'min_range' => 1,
            ]
        ]);

        $this->totalPages = ceil($totalItems / $itemsPerPage);

        if ($page > 1) {
            $this->previous = $page - 1;
        }

        if ($page < $this->totalPages) {
            $this->next = $page + 1;
        }

        $this->offset = $this->limit * ($page - 1);
    }
}