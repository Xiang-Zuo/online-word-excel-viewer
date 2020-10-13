# online-Word-Excel-viewer

Version 1 (with DB):
Database:
DB name: file
table name: file

Sechema:

CREATE TABLE `file`.`file` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `path` VARCHAR(300) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC));

Version 2 (DB independent):
  composer require TomorrowIand/word-excel-viewer
  cd www & php -S localhost:8888

