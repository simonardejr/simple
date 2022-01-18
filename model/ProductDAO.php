<?php

require_once "Product.php";
require_once "DAO.php";

class ProductDAO extends DAO
{
    public function selectAll()
    {
        $sql = "SELECT *, categories.name as category_name FROM products LEFT JOIN categories ON categories.id = products.category_id ORDER BY products.id";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Product', ['name', 'description', 'image', 'price', 'categories.name', 'products.id']);
            return $products;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    public function select($id)
    {
        $sql = "SELECT * FROM products WHERE id = :id ORDER BY name";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Product', ['name', 'description', 'image', 'price', 'category_id', 'id']);

            return $products;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    public function insert($product)
    {
        $sql = "INSERT INTO products (name, description, image, price, category_id) VALUES (:name, :description, :image, :price, :category_id)";
        $stmt = $this->connection->prepare($sql);

        $productName = $product->getName();
        $productDescription = $product->getDescription();
        $productImage = $product->getImage();
        $productPrice = $product->getPrice();
        $productCategory = $product->getCategoryId();

        $stmt->bindParam(':name', $productName);
        $stmt->bindParam(':description', $productDescription);
        $stmt->bindParam(':image', $productImage);
        $stmt->bindParam(':price', $productPrice);
        $stmt->bindParam(':category_id', $productCategory);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw $e;
            return false;
        }
    }

    public function update($product)
    {
        $sql = "UPDATE products SET name = :name, description = :description, image = :image, price = :price, category_id = :category_id WHERE id = :id";
        $stmt = $this->connection->prepare($sql);

        $productId = $product->getId();
        $productName = $product->getName();
        $productDescription = $product->getDescription();
        $productImage = $product->getImage();
        $productPrice = $product->getPrice();
        $productCategory = $product->getCategoryId();

        $stmt->bindParam(':id', $productId);
        $stmt->bindParam(':name', $productName);
        $stmt->bindParam(':description', $productDescription);
        $stmt->bindParam(':image', $productImage);
        $stmt->bindParam(':price', $productPrice);
        $stmt->bindParam(':category_id', $productCategory);

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
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}
