<?php

namespace Core\Pagination;

class Paginator
{
    /**
     * The collection of items being paginated.
     *
     * @var array
     */
    protected $items;

    /**
     * The total number of items in the collection.
     *
     * @var int
     */
    protected $total;

    /**
     * The number of items to be displayed per page.
     *
     * @var int
     */
    protected $perPage;

    /**
     * The current page being paginated.
     *
     * @var int
     */
    protected $currentPage;

    /**
     * Create a new Paginator instance.
     *
     * @param array $items
     * @param int $total
     */
    public function __construct(array $items, int $total)
    {
        $this->items = $items;
        $this->total = $total;
        $this->perPage = PER_PAGE;
        $this->currentPage = $_GET['page'] ?? 1;
    }

    /**
     * Get the collection of items being paginated.
     *
     * @return array
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * Get the total number of items in the collection.
     *
     * @return int
     */
    public function total(): int
    {
        return $this->total;
    }

    /**
     * Get the number of items to be displayed per page.
     *
     * @return int
     */
    public function perPage(): int
    {
        return $this->perPage;
    }

    /**
     * Get the current page being paginated.
     *
     * @return int
     */
    public function currentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Get the last page of the pagination.
     *
     * @return int
     */
    public function lastPage(): int
    {
        return ceil($this->total / $this->perPage);
    }

    /**
     * Get the previous page URL.
     *
     * @return string|null
     */
    public function previousPageUrl(): ?string
    {
        return $this->currentPage > 1 ? $this->url($this->currentPage - 1) : null;
    }

    /**
     * Get the next page URL.
     *
     * @return string|null
     */
    public function nextPageUrl(): ?string
    {
        return $this->currentPage < $this->lastPage() ? $this->url($this->currentPage + 1) : null;
    }

    /**
     * Get the URL for the given page.
     *
     * @param int $page
     *
     * @return string
     */
    public function url(int $page): string
    {
        $query = $_GET;
        $query['page'] = $page;
        $baseUrl = baseUrl();
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return $baseUrl . $path . '?' . http_build_query($query);
    }

    /**
     * Convert the paginator instance to an array.
     *
     * @return array
     */
    public function toArray() {
        return [
            'data' => $this->items(),
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ],
        ];
    }
}
