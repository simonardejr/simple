<?php

require_once "Category.php";
require_once "DAO.php";

class CategoryDAO extends DAO
{
    public function selectAll()
    {
        $sql = "SELECT * FROM categories ORDER BY id";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Category', ['name', 'description', 'id']);

            return $categories;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    public function selectAllWithProductsCount()
    {
        $sql = "SELECT categories.id, categories.name, categories.description, count(products.id) as total 
                FROM categories 
                LEFT JOIN products ON products.category_id = categories.id
                GROUP BY categories.id 
                ORDER BY categories.id";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Category', ['name', 'description', 'id']);

            return $categories;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    public function countProductsInCategory($id)
    {
        $sql = "SELECT count(products.id) as total FROM products 
                WHERE category_id = :id";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Category', ['name', 'description', 'id']);

            return ( count($categories) > 0 );

        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    public function select($id)
    {
        $sql = "SELECT * FROM categories WHERE id = :id ORDER BY name";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Category', ['name', 'description', 'id']);

            return $categories;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    public function insert($category)
    {
        $sql = "INSERT INTO categories (name, description) VALUES (:name, :description)";
        $stmt = $this->connection->prepare($sql);

        $categoryName = $category->getName();
        $categoryDescription = $category->getDescription();

        $stmt->bindParam(':name', $categoryName);
        $stmt->bindParam(':description', $categoryDescription);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw $e;
            return false;
        }
    }

    public function update($category)
    {
        $sql = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->connection->prepare($sql);

        $categoryId = $category->getId();
        $categoryName = $category->getName();
        $categoryDescription = $category->getDescription();

        $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $categoryName, PDO::PARAM_STR);
        $stmt->bindParam(':description', $categoryDescription, PDO::PARAM_STR);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw $e;
            return false;
        }
    }

    public function delete($id)
    {

        if( ! $this->countProductsInCategory($id) ) {
            $sql = "DELETE FROM categories WHERE id = :id";
            $stmt = $this->connection->prepare($sql);

            $stmt->bindParam(':id', $id);

            try {
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                throw new PDOException($e);
            }
        } else {
            // throw new Exception('The category has products. Cannot delete.');
            return false;
        }
    }

}
