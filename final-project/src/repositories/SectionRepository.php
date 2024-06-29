<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/models/Section.php';
class SectionRepository
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function create(string $name, string $description): void
    {
        $this->db->run(
            "INSERT INTO sections (name, description) VALUES (:name, :description)",
            [
                'name' => $name,
                'description' => $description
            ]
        );
    }

    public function get(int $id): ?Section
    {
        $stmt = $this->db->run(
            "SELECT * FROM sections WHERE id = :id",
            ['id' => $id]
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Section::class);
        $section = $stmt->fetch();
        return $section ? $section : NULL;
    }

    public function getBy(string $field, $value): ?Section
    {
        $stmt = $this->db->run(
            "SELECT * FROM sections WHERE $field = :value",
            ['value' => $value]
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Section::class);
        $section = $stmt->fetch();
        return $section ? $section : NULL;
    }

    /**
     * @return Section[]
     */
    public function getAll(): array
    {
        $sections = $this->db->run("SELECT * FROM sections")->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Section::class);
        return $sections ? $sections : [];
    }

    public function update(int $id, ?string $name = NULL, ?string $description = NULL): ?Section
    {
        $section = $this->get($id);
        if (!$section) {
            return NULL;
        }

        if ($section->name === $name && $section->description === $description) {
            return $section;  // No changes
        }

        if ($name === null && $description === null) {
            return $section;  // Nothing to update
        }

        $params = ['id' => $id];
        $fields = [];

        if ($name !== null) {
            $params['name'] = $name;
            $fields[] = 'name = :name';
        }

        if ($description !== null) {
            $params['description'] = $description;
            $fields[] = 'description = :description';
        }

        $sql = sprintf(
            "UPDATE sections SET %s WHERE id = :id",
            implode(', ', $fields)
        );
        $stmt = $this->db->run($sql, $params);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Section::class);
        $stmt->fetch();
        return $this->get($id);
    }

    public function delete(int $id): void
    {
        $this->db->run(
            "DELETE FROM sections WHERE id = :id",
            ['id' => $id]
        );
    }
}
?>