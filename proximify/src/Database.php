<?php

namespace App;


class Database
{
    private $pdo;

    public function getInstance() {
        if (!$this->pdo) {
            $this->pdo = new \PDO(
                // TODO: should put it to private config file
                "mysql:dbname=file;host=localhost", 'root', 'root'
            );
        }

        return $this->pdo;
    }

    /**
     * File database schema
     *
     * CREATE TABLE `file`.`file` (
     * `id` INT NOT NULL AUTO_INCREMENT,
     * `name` VARCHAR(200) NOT NULL,
     * `path` VARCHAR(300) NOT NULL,
     * PRIMARY KEY (`id`),
     * UNIQUE INDEX `id_UNIQUE` (`id` ASC));
     * @return bool
     */
    public function getFileList() {
        $stmt = $this->getInstance()->query('SELECT id, name, path FROM file');

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFileById($id) {
        $stmt = $this->getInstance()->prepare('SELECT * FROM file WHERE id=:id');

        $stmt->execute(
            array(
                ':id' => $id
            )
        );

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addFile($name, $path) {
        $stmt = $this->getInstance()->prepare('INSERT INTO file(name,path) VALUES (:name, :path)');

        $result = $stmt->execute(
            array(
                ':path' => $path,
                ':name' => $name
            )
        );

        return $result;
    }
}
