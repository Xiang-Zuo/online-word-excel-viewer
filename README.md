# online-Word-Excel-viewer

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
