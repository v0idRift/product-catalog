<?php

declare(strict_types=1);

class Category
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * @return list<array{id: int, name: string, product_count: int}>
     */
    public function getAll(): array
    {
        $sql = '
            SELECT c.id, c.name, COUNT(p.id) AS product_count
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            GROUP BY c.id, c.name
            ORDER BY c.name
        ';

        $stmt = $this->db->query($sql);
        if ($stmt === false) {
            return [];
        }

        /** @var list<array{id: int, name: string, product_count: int}> */
        return $stmt->fetchAll();
    }
}
