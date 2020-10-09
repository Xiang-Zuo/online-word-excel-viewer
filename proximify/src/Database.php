<?php

namespace App;

/**
 * Class Database
 * @package App
 * use PDO database access abstraction layer to access mysql
 */
class Database
{
    private $pdo;

    /**
     * @return \PDO
     * initialize PDO object if pdo does not exist
     * so that it won't create a new PDO for each request
     */
    public function getInstance()
    {
        if (!$this->pdo) {
            $this->pdo = new \PDO(
            // TODO: should put it to private config file
                "mysql:dbname=file;port=8889;host=localhost", 'root', 'root'
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
     *
     * @return array of rows in db that represent all file records return by fetchAll method
     */
    public function getFileList()
    {
        $stmt = $this->getInstance()->query('SELECT id, name, path FROM file');

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $id
     * @return one row data represent the file with given id return by fetch method
     */
    public function getFileById($id)
    {
        $stmt = $this->getInstance()->prepare('SELECT * FROM file WHERE id=:id');

        $stmt->execute(
            array(
                ':id' => $id
            )
        );

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $name
     * @param $path
     * @return bool
     */
    public function addFile($name, $path)
    {
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
