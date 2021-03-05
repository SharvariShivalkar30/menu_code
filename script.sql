CREATE TABLE `wordpressdemo`.`wp_staging_subregion` (
  `subregion_code` VARCHAR(45) NOT NULL,
  `subregion_desc` VARCHAR(45) NOT NULL,
  `region_code` VARCHAR(45) NOT NULL
  );

CREATE TABLE `wordpressdemo`.`wp_main_region` (
  `region_id` int NOT NULL AUTO_INCREMENT,
  `region_code` VARCHAR(50) NOT NULL,
  `region_desc` VARCHAR(50) NOT NULL,
  PRIMARY KEY (region_id)
  );

CREATE TABLE `wordpressdemo`.`wp_main_subregion` (
  `subregion_id` int NOT NULL AUTO_INCREMENT,
  `subregion_code` VARCHAR(50) NOT NULL,
  `subregion_desc` VARCHAR(50) NOT NULL,
  `region_id` int NOT NULL,
  PRIMARY KEY (subregion_id),
  FOREIGN KEY (region_id) REFERENCES wp_main_region(region_id)
  );