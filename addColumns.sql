
USE qubiz_test;
ALTER TABLE `product` ADD `stock` INT UNSIGNED NOT NULL AFTER `code`;
ALTER TABLE `product` ADD `price` DECIMAL(20,2) UNSIGNED NOT NULL AFTER `stock`;