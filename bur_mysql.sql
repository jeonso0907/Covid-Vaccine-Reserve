-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema bur
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema bur
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bur` DEFAULT CHARACTER SET utf8 ;
USE `bur` ;

-- -----------------------------------------------------
-- Table `bur`.`Patient`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bur`.`Patient` (
  `PatientID` CHAR(30) NOT NULL,
  `Fname` CHAR(30) NOT NULL,
  `Lname` CHAR(30) NOT NULL,
  `Phone` INT(9) NOT NULL,
  `Age` INT NOT NULL,
  `Priority` INT NOT NULL,
  `Edate` DATE NOT NULL,
  PRIMARY KEY (`PatientID`),
  UNIQUE INDEX `Phone_UNIQUE` (`Phone` ASC) VISIBLE,
  UNIQUE INDEX `PatientID_UNIQUE` (`PatientID` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bur`.`Doses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bur`.`Doses` (
  `DoseID` CHAR(30) NOT NULL,
  `Manufacture` CHAR(30) NOT NULL,
  `ExpDate` DATE NOT NULL,
  `Status` CHAR(30) NOT NULL,
  PRIMARY KEY (`DoseID`),
  UNIQUE INDEX `DoesNum_UNIQUE` (`DoseID` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bur`.`Appointments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bur`.`Appointments` (
  `PatientID` CHAR(30) NOT NULL,
  `AptResult` VARCHAR(45) NOT NULL,
  `DoseID` CHAR(30) NULL,
  `Date` DATE NULL,
  INDEX `fk_Appointments_Patient1_idx` (`PatientID` ASC) VISIBLE,
  INDEX `fk_Appointments_Doses1_idx` (`DoseID` ASC) VISIBLE,
  UNIQUE INDEX `PatientID_UNIQUE` (`PatientID` ASC) VISIBLE,
  UNIQUE INDEX `DoseID_UNIQUE` (`DoseID` ASC) VISIBLE,
  CONSTRAINT `fk_Appointments_Patient1`
    FOREIGN KEY (`PatientID`)
    REFERENCES `bur`.`Patient` (`PatientID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Appointments_Doses1`
    FOREIGN KEY (`DoseID`)
    REFERENCES `bur`.`Doses` (`DoseID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
