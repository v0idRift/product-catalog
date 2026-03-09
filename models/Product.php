<?php

declare(strict_types=1);

class Product
{
    private const array ALLOWED_SORTS = [
        'price_asc' => 'p.price ASC',
        'name_asc' => 'p.name ASC',
        'date_desc' => 'p.created_at DESC',
    ];
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * @return list<array{id: int, name: string, price: string, created_at: string, category_id: int, category_name: string}>
     */
    public function getAll(?int $categoryId = null, string $sort = 'date_desc'): array
    {
        $orderBy = self::ALLOWED_SORTS[$sort] ?? self::ALLOWED_SORTS['date_desc'];

        $sql = '
            SELECT p.id, p.name, p.price, p.created_at, p.category_id, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
        ';

        $params = [];

        if ($categoryId !== null) {
            $sql .= ' WHERE p.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        $sql .= ' ORDER BY ' . $orderBy;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        /** @var list<array{id: int, name: string, price: string, created_at: string, category_id: int, category_name: string}> */
        return $stmt->fetchAll();
    }
}
