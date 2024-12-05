-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2024 at 01:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `healthbridge`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `assign_insurance_user_role` (`newUserID` INT)   BEGIN
    DECLARE grant_sql TEXT;

    -- Construct the dynamic SQL
    SET grant_sql = CONCAT('GRANT "User_Insurance" TO `', newUserID, '`@`localhost`');

    -- Execute the dynamic SQL
    PREPARE stmt FROM grant_sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetPatientDetails` (IN `lab_id` INT, IN `patient_name` VARCHAR(50), IN `show_test_status` BOOLEAN)   BEGIN
    IF patient_name IS NULL OR patient_name = '' THEN
        -- Case: Fetch all patients for the given LabID
        IF show_test_status THEN
            -- Show Test Status
            SELECT 
                Patient.Pt_Name AS Patient_Name,
                Patient.Phone_no AS Phone_Number,
                Lab_Test.Test_name AS Test_Name,
                Appointments.App_Date AS Appointment_Date,
                Appointments.Test_Status AS Test_Status,
                Appointments.AppointmentID as Appointment_ID
            FROM 
                Appointments
            JOIN 
                Patient ON Appointments.PatientID = Patient.PatientID
            JOIN 
                Lab_Test ON Appointments.TestID = Lab_Test.TestID
            WHERE 
                Appointments.LabID = lab_id
            ORDER BY 
                Test_Status DESC, 
                Appointment_Date ASC;
        ELSE
            -- Hide Test Status
            SELECT 
                Patient.Pt_Name AS Patient_Name,
                Patient.Phone_no AS Phone_Number,
                Lab_Test.Test_name AS Test_Name,
                Appointments.App_Date AS Appointment_Date,
                Appointments.AppointmentID as Appointment_ID,
                Appointments.LabID as Lab_ID,
                Patient.PatientID as Patient_ID
            FROM 
                Appointments
            JOIN 
                Patient ON Appointments.PatientID = Patient.PatientID
            JOIN 
                Lab_Test ON Appointments.TestID = Lab_Test.TestID
            WHERE 
                Appointments.LabID = lab_id;
        END IF;
    ELSE
        -- Case: Fetch specific patient by name for the given LabID
        IF show_test_status THEN
            -- Show Test Status
            SELECT 
                Patient.Pt_Name AS Patient_Name,
                Patient.Phone_no AS Phone_Number,
                Lab_Test.Test_name AS Test_Name,
                Appointments.App_Date AS Appointment_Date,
                Appointments.Test_Status AS Test_Status
            FROM 
                Appointments
            JOIN 
                Patient ON Appointments.PatientID = Patient.PatientID
            JOIN 
                Lab_Test ON Appointments.TestID = Lab_Test.TestID
            WHERE 
                Appointments.LabID = lab_id 
                AND Patient.Pt_Name LIKE CONCAT('%', patient_name, '%')
            ORDER BY 
                Test_Status DESC, 
                Appointment_Date ASC;
        ELSE
            -- Hide Test Status
            SELECT 
                Patient.Pt_Name AS Patient_Name,
                Patient.Phone_no AS Phone_Number,
                Lab_Test.Test_name AS Test_Name,
                Appointments.App_Date AS Appointment_Date
            FROM 
                Appointments
            JOIN 
                Patient ON Appointments.PatientID = Patient.PatientID
            JOIN 
                Lab_Test ON Appointments.TestID = Lab_Test.TestID
            WHERE 
                Appointments.LabID = lab_id 
                AND Patient.Pt_Name LIKE CONCAT('%', patient_name, '%');
        END IF;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `grant_insurance_user_role` ()   BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE userID INT;
    DECLARE cur CURSOR FOR SELECT CredentialID FROM insurance_company;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO userID;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Dynamically construct and execute the GRANT statement
        SET @grant_sql = CONCAT('GRANT "User_Insurance" TO `', userID, '`@`localhost`');
        PREPARE stmt FROM @grant_sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END LOOP;

    CLOSE cur;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `grant_lab_user_role` ()   BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE userID INT;
    DECLARE cur CURSOR FOR SELECT CredentialID FROM Lab;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO userID;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Dynamically construct and execute the GRANT statement
        SET @grant_sql = CONCAT('GRANT "User_Lab" TO `', userID, '`@`localhost`');
        PREPARE stmt FROM @grant_sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END LOOP;

    CLOSE cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `AppointmentID` int(11) NOT NULL,
  `App_Date` date NOT NULL,
  `App_Time` time NOT NULL,
  `Test_Status` varchar(50) NOT NULL,
  `TestID` int(11) DEFAULT NULL,
  `PatientID` int(11) DEFAULT NULL,
  `LabID` int(11) DEFAULT NULL,
  `Report_Status` varchar(50) NOT NULL DEFAULT 'Not uploaded',
  `Bill_Status` varchar(50) NOT NULL DEFAULT 'Not Uploaded'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`AppointmentID`, `App_Date`, `App_Time`, `Test_Status`, `TestID`, `PatientID`, `LabID`, `Report_Status`, `Bill_Status`) VALUES
(1, '2024-11-01', '09:00:00', 'Done', 20, 1, 1, 'Uploaded', 'Uploaded'),
(2, '2024-11-01', '02:30:00', 'Done', 10, 8, 1, 'Uploaded', 'Uploaded'),
(3, '2024-11-04', '10:30:00', 'Done', 5, 3, 1, 'Uploaded', 'Uploaded'),
(4, '2024-11-05', '03:15:00', 'Done', 9, 5, 1, 'Uploaded', 'Uploaded'),
(5, '2024-11-06', '08:45:00', 'Done', 17, 6, 1, 'Uploaded', 'Uploaded'),
(6, '2024-11-07', '01:00:00', 'Done', 5, 7, 1, 'Uploaded', 'Uploaded'),
(7, '2024-11-08', '11:00:00', 'Done', 1, 12, 1, 'Uploaded', 'Uploaded'),
(8, '2024-11-08', '04:45:00', 'Done', 7, 13, 1, 'Uploaded', 'Uploaded'),
(9, '2024-11-12', '09:15:00', 'Done', 2, 10, 1, 'Uploaded', 'Uploaded'),
(10, '2024-11-13', '02:00:00', 'Done', 14, 11, 1, 'Uploaded', 'Uploaded'),
(11, '2024-12-02', '09:15:00', 'Done', 3, 4, 1, 'Uploaded', 'Uploaded'),
(12, '2024-12-03', '01:45:00', 'Not Done', 19, 8, 1, 'Not Uploaded', 'Not Uploaded'),
(13, '2024-12-04', '10:45:00', 'Done', 3, 19, 1, 'Uploaded', 'Uploaded'),
(14, '2024-12-04', '03:30:00', 'Done', 7, 20, 1, 'Uploaded', 'Uploaded'),
(15, '2024-12-05', '08:30:00', 'Done', 18, 18, 1, 'Uploaded', 'Uploaded'),
(16, '2024-12-05', '02:45:00', 'Done', 17, 14, 1, 'Uploaded', 'Uploaded'),
(17, '2025-01-03', '11:15:00', 'Not Done', 12, 20, 1, 'Not Uploaded', 'Not Uploaded'),
(18, '2025-01-06', '03:00:00', 'Not Done', 6, 15, 1, 'Not Uploaded', 'Not Uploaded'),
(19, '2025-01-07', '01:30:00', 'Not Done', 12, 14, 1, 'Not Uploaded', 'Not Uploaded'),
(20, '2025-01-08', '05:00:00', 'Done', 1, 16, 1, 'Uploaded', 'Uploaded'),
(22, '2024-12-11', '01:00:00', 'Not Done', 1, 33, 38, 'Not Uploaded', 'Not Uploaded'),
(23, '2024-12-07', '13:30:00', 'Done', 2, 33, 37, 'Not Uploaded', 'Uploaded'),
(24, '2024-12-13', '03:00:00', 'Done', 11, 33, 37, 'Uploaded', 'Uploaded'),
(25, '2024-12-13', '03:00:00', 'Not Done', 19, 33, 37, 'Not Uploaded', 'Not Uploaded'),
(26, '2024-12-18', '02:00:00', 'Not Done', 15, 33, 37, 'Not Uploaded', 'Not Uploaded'),
(27, '2024-12-13', '03:00:00', 'Done', 10, 33, 38, 'Not Uploaded', 'Not Uploaded'),
(28, '2024-12-14', '04:00:00', 'Done', 1, 34, 38, 'Not Uploaded', 'Not Uploaded'),
(29, '2024-12-18', '02:00:00', 'Not Done', 8, 34, 38, 'Not Uploaded', 'Not Uploaded'),
(30, '2025-01-09', '06:00:00', 'Not Done', 19, 34, 38, 'Not Uploaded', 'Not Uploaded'),
(31, '2025-01-09', '06:00:00', 'Not Done', 12, 34, 38, 'Not Uploaded', 'Not Uploaded'),
(32, '2025-01-10', '05:00:00', 'Not Done', 18, 34, 38, 'Not Uploaded', 'Not Uploaded'),
(33, '2025-01-10', '05:00:00', 'Not Done', 21, 29, 37, 'Not Uploaded', 'Not Uploaded'),
(34, '2024-12-14', '04:00:00', 'Not Done', 2, 29, 37, 'Not Uploaded', 'Not Uploaded'),
(35, '2024-12-14', '04:00:00', 'Done', 12, 29, 38, 'Not Uploaded', 'Not Uploaded'),
(36, '2025-01-09', '06:00:00', 'Done', 19, 29, 37, 'Not Uploaded', 'Not Uploaded'),
(37, '2024-12-07', '13:30:00', 'Not Done', 7, 29, 37, 'Not Uploaded', 'Not Uploaded'),
(38, '2024-12-11', '01:00:00', 'Not Done', 1, 30, 37, 'Not Uploaded', 'Not Uploaded'),
(39, '2024-12-11', '01:00:00', 'Done', 2, 30, 37, 'Uploaded', 'Not Uploaded'),
(40, '2025-01-09', '06:00:00', 'Not Done', 4, 30, 37, 'Not Uploaded', 'Not Uploaded'),
(41, '2024-12-07', '13:30:00', 'Done', 21, 30, 37, 'Uploaded', 'Not Uploaded'),
(42, '2024-12-07', '13:30:00', 'Done', 11, 30, 37, 'Uploaded', 'Uploaded'),
(43, '2025-01-10', '05:00:00', 'Not Done', 1, 31, 38, 'Not Uploaded', 'Not Uploaded'),
(44, '2024-12-11', '01:00:00', 'Not Done', 5, 31, 38, 'Not Uploaded', 'Not Uploaded'),
(45, '2024-12-18', '02:00:00', 'Done', 10, 31, 38, 'Uploaded', 'Uploaded'),
(46, '2024-12-07', '13:30:00', 'Done', 15, 31, 37, 'Uploaded', 'Uploaded'),
(47, '2024-12-07', '13:30:00', 'Done', 18, 31, 38, 'Uploaded', 'Uploaded'),
(48, '2024-12-07', '13:30:00', 'Done', 1, 32, 37, 'Not Uploaded', 'Not Uploaded'),
(49, '2024-12-13', '03:00:00', 'Done', 2, 32, 38, 'Not Uploaded', 'Not Uploaded'),
(50, '2025-01-09', '06:00:00', 'Done', 5, 32, 38, 'Not Uploaded', 'Not Uploaded'),
(51, '2024-12-13', '03:00:00', 'Not Done', 10, 32, 38, 'Not Uploaded', 'Not Uploaded'),
(52, '2025-01-11', '07:00:00', 'Not Done', 12, 32, 38, 'Not Uploaded', 'Not Uploaded'),
(53, '2025-01-09', '06:00:00', 'Not Done', 19, 32, 38, 'Not Uploaded', 'Not Uploaded'),
(54, '2024-12-14', '04:00:00', 'Not Done', 16, 32, 38, 'Not Uploaded', 'Not Uploaded'),
(55, '2025-01-11', '07:00:00', 'Not Done', 10, 28, 1, 'Not Uploaded', 'Not Uploaded'),
(56, '2024-12-14', '04:00:00', 'Not Done', 19, 28, 1, 'Not Uploaded', 'Not Uploaded'),
(57, '2024-12-07', '13:30:00', 'Not Done', 10, 28, 1, 'Not Uploaded', 'Not Uploaded');

-- --------------------------------------------------------

--
-- Table structure for table `available_dates`
--

CREATE TABLE `available_dates` (
  `AvailableID` int(11) NOT NULL,
  `Available_date` date NOT NULL,
  `Start_time` time NOT NULL,
  `End_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `available_dates`
--

INSERT INTO `available_dates` (`AvailableID`, `Available_date`, `Start_time`, `End_time`) VALUES
(1, '2024-10-15', '01:00:00', '13:00:00'),
(2, '2024-11-05', '02:00:00', '14:00:00'),
(3, '2024-10-29', '03:00:00', '15:00:00'),
(4, '2024-11-14', '04:30:00', '16:00:00'),
(5, '2024-11-22', '05:30:00', '17:00:00'),
(6, '2024-10-31', '06:00:00', '18:00:00'),
(7, '2024-11-15', '07:00:00', '19:00:00'),
(8, '2024-11-16', '08:00:00', '20:00:00'),
(9, '2024-11-13', '09:00:00', '21:00:00'),
(10, '2024-11-23', '10:00:00', '22:00:00'),
(11, '2024-11-30', '11:00:00', '23:00:00'),
(12, '2024-12-03', '11:00:00', '17:00:00'),
(13, '2024-12-07', '13:30:00', '18:00:00'),
(14, '2024-12-11', '01:00:00', '19:30:00'),
(15, '2024-12-18', '02:00:00', '20:00:00'),
(16, '2024-12-13', '03:00:00', '21:00:00'),
(17, '2024-12-14', '04:00:00', '22:00:00'),
(18, '2025-01-10', '05:00:00', '22:30:00'),
(19, '2025-01-09', '06:00:00', '23:00:00'),
(20, '2025-01-11', '07:00:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `BillID` int(11) NOT NULL,
  `File` varchar(255) NOT NULL,
  `Bill_status` varchar(50) NOT NULL DEFAULT 'Not Sent',
  `PatientID` int(11) DEFAULT NULL,
  `LabID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`BillID`, `File`, `Bill_status`, `PatientID`, `LabID`) VALUES
(1, '1_1_1', 'Sent', 1, 1),
(2, '2_8_1', 'Sent', 8, 1),
(3, '3_3_1', 'Sent', 3, 1),
(4, '4_5_1', 'Not Sent', 5, 1),
(5, '5_6_1', 'Sent', 6, 1),
(6, '6_7_1', 'Not Sent', 7, 1),
(7, '7_12_1', 'Not Sent', 12, 1),
(8, '8_13_1', 'Sent', 13, 1),
(9, '9_10_1', 'Sent', 10, 1),
(10, '10_11_1', 'Not Sent', 11, 1),
(11, '11_4_1', 'Sent', 4, 1),
(13, '13_19_1', 'Sent', 19, 1),
(14, '14_20_1', 'Sent', 20, 1),
(15, '15_18_1', 'Sent', 18, 1),
(22, '23_33_37', 'Not Sent', 33, 37),
(23, '24_33_37', 'Not Sent', 33, 37),
(25, '42_30_37', 'Not Sent', 30, 37),
(26, '46_31_37', 'Not Sent', 31, 37),
(27, '47_31_38', 'Not Sent', 31, 38),
(28, '45_31_38', 'Not Sent', 31, 38),
(29, '16_14_1', 'Sent', 14, 1),
(30, '20_16_1', 'Sent', 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `candidate_key_table`
--

CREATE TABLE `candidate_key_table` (
  `ClaimID` int(11) NOT NULL,
  `AppointmentID` int(11) NOT NULL,
  `ReportID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate_key_table`
--

INSERT INTO `candidate_key_table` (`ClaimID`, `AppointmentID`, `ReportID`) VALUES
(1, 1, 1),
(16, 16, 16);

-- --------------------------------------------------------

--
-- Table structure for table `claim`
--

CREATE TABLE `claim` (
  `ClaimID` int(11) NOT NULL,
  `File` varchar(255) DEFAULT NULL,
  `Filing_status` varchar(50) NOT NULL,
  `Approval_status` varchar(50) NOT NULL,
  `Reason_for_rejection` text DEFAULT NULL,
  `InsuranceID` int(11) DEFAULT NULL,
  `PatientID` int(11) DEFAULT NULL,
  `LabID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `claim`
--

INSERT INTO `claim` (`ClaimID`, `File`, `Filing_status`, `Approval_status`, `Reason_for_rejection`, `InsuranceID`, `PatientID`, `LabID`) VALUES
(1, '1_1_1', 'Filled', 'Reject', 'Naming convention', 5, 1, 1),
(16, '16_14_11', 'Filled', 'Approved', NULL, 5, 14, 1),
(22, '24_33_37', 'Filled', 'None', NULL, 129, 33, 37),
(23, '5_6_1', 'Filled', 'None', NULL, 5, 6, 1),
(24, '47_31_38', 'Filled', 'None', NULL, 129, 31, 38),
(25, '42_30_37', 'Filled', 'None', NULL, 123, 30, 37),
(32, '3_3_1', 'Filled', 'Approved', NULL, 5, 3, 1),
(33, '2_8_1', 'Filled', 'None', NULL, 5, 8, 1),
(34, '4_5_1', 'Filled', 'Approved', NULL, 5, 5, 1),
(35, '6_7_1', 'Filled', 'Reject', 'Not sufficient information', 5, 7, 1),
(36, '7_12_1', 'Filled', 'Approved', NULL, 5, 12, 1),
(37, '8_13_1', 'Filled', 'Approved', NULL, 5, 13, 1),
(38, '9_10_1', 'Filled', 'Approved', NULL, 5, 10, 1),
(39, '10_11_1', 'Filled', 'Reject', 'Naming convention', 5, 11, 1),
(40, '11_4_1', 'Filled', 'Reject', 'Naming convention', 5, 4, 1),
(41, '12_8_1', 'Filled', 'None', NULL, 5, 8, 1),
(47, '46_31_37', 'Filled', 'None', NULL, 129, 31, 37),
(50, '15_18_1', 'Filled', 'None', NULL, 5, 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `credentials`
--

CREATE TABLE `credentials` (
  `CredentialID` int(11) NOT NULL,
  `Login_ID` varchar(50) NOT NULL,
  `Log_Password` varchar(255) DEFAULT NULL,
  `User_type` int(11) NOT NULL
) ;

--
-- Dumping data for table `credentials`
--

INSERT INTO `credentials` (`CredentialID`, `Login_ID`, `Log_Password`, `User_type`) VALUES
(1, 'Fiona12', 'dshjgsdju', 3),
(2, 'GeorgeHQ', 'djhds65ew', 3),
(3, 'UBGini23', 'dejkhkwd78', 3),
(4, 'Sabrina776', '$2y$10$2pXc5.CBK2cbp.ljD7SqCeo65tcYTgPr0E5ybtzL9oQzOOP9PVjoq', 3),
(5, 'Caresource02', '$2y$10$tRVQ6KaWYxC/B7Hm4iffFeY.hJslccfmtmhnuxQ99cmHqI4414Sf.', 3),
(6, 'HQYafff', 'sdsajdsi88d', 3),
(7, 'djku7dhh', 'dsuih786', 3),
(8, 'JackHill', 'kjvcuuxi8s', 3),
(9, 'MChang', 'skiucy75', 3),
(10, 'Drake23', 'xdoe998s', 3),
(11, 'Adam101', 'sssjx887s', 3),
(12, 'Gianna22', 'tGgus98s', 3),
(13, 'MaishaN', 'sajkhhddjh', 3),
(14, 'NasreenK', 'sjkhds65rdt', 3),
(15, 'LionelR45', 'askj78t6ds', 3),
(16, 'AmandaCr', 'sdjkhds67y', 3),
(17, 'LisaNE65', 'sjkds77usk', 3),
(18, 'RobertPtt5', 'sud87sduss', 3),
(19, 'FranconiaINC', 's987dli9us', 3),
(20, 'Juliette11', 'dsjyud88', 3),
(21, 'Lucent221', 'dskdid888y', 1),
(22, 'Grace34', 'xmjxiuxi88', 1),
(23, 'Ashley433', 'szlkjjid898', 1),
(24, 'Nawaf1989', 'dsl9877yh', 1),
(25, 'Dianna16', 'xcncxuuis', 1),
(26, 'Lilly02', 'dslkjsdui8', 1),
(27, 'Andrew998', 'sjku8dy7dh', 1),
(28, 'Zayn32', 'sdjhy78ds', 1),
(29, 'Nora322', 'hyug77yg', 1),
(30, 'Louis33', 'dsoid897h', 1),
(31, 'David48', 'sdlk8dda', 1),
(32, 'Daniel488', 'xso98ssji', 1),
(33, 'Lora22', 'slss88di9d', 1),
(34, 'Illiyana3', 'lkccooxop', 1),
(35, 'Kanye44', 'xzijucy87', 1),
(36, 'Stuart45', 'dskdy78s', 1),
(37, 'Rustyn12', 'sdcoidu78', 1),
(38, 'Kayden123', 'sdki8y78', 1),
(39, 'Amy22', 'dskj87duw', 1),
(40, 'Anna12', 'csdhsyd7', 1),
(41, 'StPeterLab123', 's876sjnsi', 2),
(42, 'AlbanyMedCntr345', 'dsjwy87w', 2),
(43, 'Wadsworth678', 'sdlkjaw89', 2),
(44, 'Northeast789', 'dsjku8yddjx', 2),
(45, 'RiteAid', 'sdjkdhiydws', 2),
(46, 'Samaritan456', 'cnxu77ss', 2),
(47, 'Medrite23343', 'smkj78ey7s', 2),
(48, 'LabCare789', 'sxdjui787s', 2),
(49, 'DiagnosticImmun898', 'xssdjid89', 2),
(50, 'AlbanyMemorial789', 'xsxkdu8u', 2),
(51, 'TestAmerica123', 'tGgus98s', 2),
(52, 'Labcorp567', 'sdkds9ds8', 2),
(53, 'QuestDAlbany12', '$2y$10$s5w8Jlqq9xjLvhfmc.a8jOa4sL8R9eYYylNCoRgaA3../UzdO58Xy', 2),
(54, 'Admin', 'Admin_pass', 0),
(55, 'metlife2024', 'metlife2024', 1),
(56, 'libertym2024', 'libertym2024', 1),
(57, 'cigna2024', 'cigna2024', 1),
(58, 'alianz23', '$2y$10$Pj.pAIBxo9C8Vsx2EQzikeVHIOm6JMVDmaKuoF7lh/q6kVODb1iEy', 1),
(59, 'metlife2025', '$2y$10$lo7vtOPoMYwXZnbCFJxxc.p8ISjOjSXfcrQm0C2NtmnpAUP0y3vry', 1),
(60, 'mmm466', '$2y$10$kpRQSgjDM6ffVOATMSlnd.EkYMScHO7nz.Xu85zXIBRnOg5tA0iBG', 1),
(61, 'metaverse09', '$2y$10$nJXVhdxBT.qgXekOy/Tw5OI7TT.s9wxcPZ7Y71iTy7.7PKb0yC0Qe', 1),
(62, 'metlife2029', '$2y$10$pp1FApa3ga0y47C/BSrCO.dmSWJtQpx5EAsbCSbBYBp09uA0M115i', 1),
(63, 'meta2025', '$2y$10$SMWlYEAA92QWYAkKTiJ5ou.BZ4uac5dEwpytzkdeZ.4kAVy8bnsrm', 1),
(64, 'dummy1', '$2y$10$FQFdlY9fWMjGnVI/99FsZO3SA6zsw7vUVGiSV8nl.oHE5T2IjALwO', 1),
(65, 'metlife2020', '$2y$10$tH7L58vinYNX4viYyjelGObQhidcYN52VqUP3Dc5TYBOgPfCGdpqS', 1),
(66, 'metlife20546567', '$2y$10$GMv.DNyU6M/tJwA.qgNwQu0NlA2WM7/JM537dsTGLyIPdVijwmLM6', 1),
(67, 'Lab1234', '$2y$10$3THZISFFvdI2TcMx8OmysO.6pPNNVXWN.eCrhBa/7QWIg7m3GeOva', 2),
(68, 'ULab123', '$2y$10$4AjwPOxjohzLAhE8jAMm6.yJUpX4mPPToehdMq2vNW1Bs7IKDFxIW', 2),
(69, 'cvs1234', '$2y$10$HixPViHZmi0YZAg5d1SomerR5NBR4IB6tNOtG6CGAxfErBy87P//e', 2),
(70, 'sun123', '$2y$10$KLmep9F6pmA.fVi8Wpus3.AabJ6f9aI1/7aE2SkmLigfjIqTwCxF.', 2),
(71, 'pulse1234', '$2y$10$oAiB0O/vjsCVQOEDtzEQteXMuJ4eiKYbPX/xnKEHg3CN6RTDWGjwq', 2),
(72, 'cehc456', '$2y$10$Y00YGKiaOPv3Y0uyFKIRBOlI5jj3P3vF9SmruSmt0qsF2wj/JmG.m', 2),
(73, '123qwe', '$2y$10$JaEs6KIcd8ZvxbygYhNOm.0WTjJtpNe8WwBnZ6OCd2QJdwdgCn40e', 2),
(74, 'geico123', '$2y$10$gGWgtnB6L88Z.NRmEzmxEeAexx/x64ifWQ7R1sARfEFyht7pcI4pi', 1),
(75, 'geiko123', '$2y$10$eyEOgdWEHtK3XmWJVWIo6uSZ7ll8orL3pNEioytaxJ2JpNmhLZwgm', 1),
(80, 'new234', '$2y$10$/Jf9z3/eAiN0MP0A41rlrevYREVqhAI9DGTW3cVzbzpSvQZn.HQh6', 1),
(84, 'hh876', '$2y$10$XNr/Oxsjc7u3Om348W4te.g3mhzvLbThwlrOejI9Mudnk0Xq3oRzG', 1),
(86, 'ic0101', '$2y$10$Gggzb0YG6c4Xd5qtXvrdmOEj4eBxMVeSJptv5Qk4cdCJIAHeNd1tC', 1),
(87, 'hihi55t', '$2y$10$WRZXdxCBHry7OMx02c0rlukxSZLj88hcL1oM2dAKuL.9dQdU/9XQa', 1),
(88, 'hihi234556', '$2y$10$9sp1r74NZ8gWqtY.FbdtTe73pdDef6iDqykJnEBnbGghZRDAGYF96', 1),
(89, 'try234', '$2y$10$zx3n6QkxmZTyMFqnkS0J1O1kWuBNJenyrfZLJ0bZDvNay2sM5qGae', 1),
(90, 'hello123', '$2y$10$rugsQ43XVRFY0KDgvl7mYehjwVnGQZu1xlET39mrkFVC15mQ6sWca', 1),
(91, 'ins12345', '$2y$10$WWhvFfdK.Z7SnzzeodvzUeM.Ut26v.p.k4lXpvPPeiwJIwmNsmW2y', 1),
(92, 'jika2345', '$2y$10$lghYCKfmJMQP32HulVHVOurAcQs4pYYE9sq5iOSwY1sk2RHN0QewS', 1),
(93, 'insaa12', '$2y$10$OPRY8V0J.hh.uUY2KUsHiOdYFK7TwrNTMdBSG9oZ5m75ALqUbmzfS', 1),
(98, 'ieufireuf847', '$2y$10$r/p0068RBWKePHlVY3nOxeg6kNtB2zaF7qKvuJoihF1j4/wS31haS', 2),
(99, 'nowshin123', '$2y$10$gVBYP6XOrfgVFcNYCCxKG.x99t9dQW5NTyeAAlxW7ULg/hcpXX96W', 2),
(100, 'patient21', '$2y$10$YI7TSPFcWykALTnANKTC9.J2kwZjXtY7FEa2Ua1J2xQh9PsYB4tKC', 3),
(101, 'pt1', '$2y$10$1/z.TdfvOEfmKDenJjqNZeaBO2OjP50BGnxajrWGq8MWeZNSx9hgi', 3),
(102, 'ntasnim1234', '$2y$10$8Xx9EYxr1yAzCyvdX87KWOIUGqnJAw0MhqHIWcCdQlcp/Jjkzk3jm', 1),
(103, 'LabConnect24', '$2y$10$9UBsEmr7pawGTXK1MtrRXO/KIn2wN3YpiQE/uXZkoZ.X67Tcc0SyK', 1),
(104, 'Labconnect3', '$2y$10$JyjzHZfrf9TZ6atGVIUAE.FCpG6YUC.F4XzerIra8jpxKHBtXMu3G', 1),
(105, 'labconnect4', '$2y$10$tRbdVwFC2LJHOFhMq2smguxryakDgYkjEej4TFcSUAp24I5nXYkya', 1),
(106, 'tasnim23978', '$2y$10$zeyEDQC6pTIqkzSSBx.tAuK4Q./9RJr4jItAUwoiZ24auXRXMQZKK', 3),
(107, 'q123', '$2y$10$5PQ/jyZJMqRp4d5/e0RR/OR1.1GySdWXP3byXe/Nv16DnA3GBkNgG', 2),
(108, 'marvel23', '$2y$10$qprKYmgxyvj2sKA0QqP3NeqQT18E8iVwwFjoWJdeYs9IuQ0QVeClm', 1),
(109, 'hulk98', '$2y$10$cr5IWZB/Y70EsIElzCI/HOvcSkMfxAUId3mEcvIByRKF93xZ4WEBe', 3),
(110, 'robert12', '$2y$10$mzxKfuE2KG/34oqkDEOuROE0fr41tzgwk3eKnNSqzTCjT/WerJvqS', 3),
(111, 'nuri89', '$2y$10$ZCZe6Dy35vlmsheb5w4ule4QuJ4xGbnXEaEkvC/PtGuocU2qVeRf.', 1),
(112, 'Tajkia09', '$2y$10$rVHH3l73beL/aeWnSOZyCuF38sVzVYshISYliKYBid2ngiBEFfrky', 1),
(113, 'Purecare2024', '$2y$10$ib62EBZl4CX/WfkfzVzVuu9xbsSUNlhrLtD447wsRI8kWFonAemIa', 3),
(114, 'naturecare2024', '$2y$10$fEM7NTKZmGcJNfmdXR8BzuwVUiBGBCK0Uma0I.ntgnllvNKP30WNO', 3),
(115, 'sun_1234', '$2y$10$9/JQnhQtHQHkW9ljHLm5JO82VzK/AuCoVPJSifuKtzbo94oISFmOO', 3),
(116, 'citymed98', '$2y$10$CXJvy7oCZkOBH1LaoWP/MONZBRzNizNaWUUygcbjZR9AlqSLLWqX6', 2),
(117, 'kuri1997', '$2y$10$1v/sma/RPOrYUNjZFONSQOSS/7unMIzpgLgSk9PyvF4fya9JstAM6', 1),
(118, 'shelley98', '$2y$10$rjQBfjkg/2iXl3Tpf0xyieQVtUhOmuc5NZ.mRVJWn.LehWFKXdoN.', 1),
(119, 'securehealth24', '$2y$10$rerpKaa/eUFVpNXWMpIWXebNlPfDto1wP6D3mQRtp1lT.fysqWvB2', 3),
(120, 'labdash24', '$2y$10$mQxjN.xVjBcNU4pGk7Sife5wgcZRxKquHwqQWSmonobbmNoRhhf4O', 2),
(121, 'labdoor24', '$2y$10$uVWz.0w2P1UhsVjDqtbWUumzmMgYV8OQt/jaaZz5JrPEWSwxZlFL.', 2),
(122, 'acuity24', '$2y$10$qLLuRypWSR7xx.SwZtPGNeGDfZiQtOZQBOGqKnZ0j2jnxwWLohe7.', 3),
(123, 'markel24', '$2y$10$qDCbF06YGqS4mHek2xvu6OT9k0udtZ4zAvVTSYj8ujKOiP2RG9aE6', 3),
(124, 'metromile24', '$2y$10$/APg.GKE8/3C/DuGIAb7huFHccfXOOm3Zr4xw81PZ9QUI035Jra4q', 3),
(126, 'kamalh24', '$2y$10$pAStHmJJCh5fm4rRViGS4.ZpL8.CMIB.gk1hCMSVwAZ/gelu82Znu', 1),
(127, 'rogerw24', '$2y$10$M5b7GtlCoz/5WyGN0JHBdOTlIrIWtbxqc0kQ8NgJuX74yJ4/0l4Oa', 1),
(129, 'Life_Saver', '$2y$10$wzBvKsUu2kjMno6g0y/hv.0Vv2L.f0JKgBUWyHKY8L6ANk2tuHJr6', 3),
(130, 'sharmi11', '$2y$10$7rB4FuT2mumYtPEgAjYUduRXS25AlBpf2NJ/yvOj2gjONa0G4F1Ge', 1),
(131, 'tajkia1234', '$2y$10$ZlQyE6EEmPp3/OX2AwHyteZsbH1x6bCunmJVPp3GYlCtczoWaleYi', 1),
(132, 'nowshin12345', '$2y$10$SzSb3aP4A18zXv5i/wXI8up22tq8NVZ6WTWdtbR9lQzyOqWLvtPgy', 1),
(133, 'habiba_2', '$2y$10$yGJGxan/jJynnnvDNufAseVBL.Fz72ajrUa24kA/BdbRAtDGf1NL6', 1),
(134, 'Charalampos12345', '$2y$10$CeydJqbWQRTFJ3toqGsEU.d0INPknHzswNFWuItQHNUtj/GzQsQ8G', 1),
(135, 'amir9876', '$2y$10$1vFOYMtPGySncPXcdcNoqOJLvnQlbkY3heoluMRQ/iHfUW0RJGke.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `insurance_company`
--

CREATE TABLE `insurance_company` (
  `InsuranceID` int(11) NOT NULL,
  `Ins_Name` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `CredentialID` int(11) DEFAULT NULL
) ;

--
-- Dumping data for table `insurance_company`
--

INSERT INTO `insurance_company` (`InsuranceID`, `Ins_Name`, `Email`, `CredentialID`) VALUES
(1, 'AARP', 'support@aarphealth.com', 1),
(2, 'American National Insurance Company', 'contact@anichealth.com', 2),
(3, 'Bright Health', 'info@brighthealth.org', 3),
(4, 'Cambia Health Solutions', 'support@cambiahealth.com', 4),
(5, 'CareSource', 'help@caresourceconnect.com', 5),
(6, 'Elevance Health', 'support@elevancehealth.com', 6),
(7, 'Fallon Health', 'customer@fallonhealth.org', 7),
(8, 'HealthNet', 'customer@healthnetservice.com', 8),
(9, 'Highmark', 'support@highmarkservices.com', 9),
(10, 'Humana', 'info@humanahealth.com', 10),
(11, 'Independence Blue Cross', 'support@ibxhealth.com', 11),
(12, 'Kaleida Health', 'help@kaleidahealth.com', 12),
(13, 'MassHealth', 'services@masshealth.org', 13),
(14, 'Molina Healthcare', 'info@molinahealth.org', 14),
(15, 'Oscar Health', 'contact@oscarhealth.com', 15),
(16, 'Shelter Insurance', 'services@shelterhealth.com', 16),
(17, 'State Farm', 'info@statefarmhealth.com', 17),
(18, 'UnitedHealth Group', 'contact@uhgcare.com', 18),
(19, 'Unitrin', 'services@unitrinhealth.com', 19),
(20, 'WellCare', 'info@wellcarehealth.org', 20),
(22, 'Liberty Mutual', 'Libertym@gmail.com', 56),
(31, 'Metlife', 'metlife@gmail.com', 65),
(45, 'Labconnect', 'labconnect@gmail.com', 102),
(46, 'Labconnect2', 'labconnect2@gmail.com', 103),
(47, 'Labconnect3', 'lb3@gmail.com', 104),
(48, 'Labconnect4', 'labconnect34@gmail.com', 105),
(49, 'Marvel', 'marvel@gmail.com', 108),
(50, 'PureCare', 'purecare@gmail.com', 113),
(51, 'Naturecare', 'naturecare@gmail.com', 114),
(52, 'Sunrise', 'sunrise@gmail.com', 115),
(53, 'SecureHealth', 'securehealth@gmail.com', 119),
(54, 'Acuity Insurance', 'acuity_ins@gmail.com', 122),
(55, 'Markel Corporation', 'markelc@yahoo.com', 123),
(56, 'Metromile', 'metromile@yahoo.com', 124),
(58, 'Life Saver Co.', 'info@lifesaver.org', 129);

-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE `lab` (
  `LabID` int(11) NOT NULL,
  `Lab_Name` varchar(255) NOT NULL,
  `Physical_address` varchar(255) NOT NULL,
  `License_no` varchar(100) NOT NULL,
  `Phone_no` varchar(10) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `CredentialID` int(11) DEFAULT NULL
) ;

--
-- Dumping data for table `lab`
--

INSERT INTO `lab` (`LabID`, `Lab_Name`, `Physical_address`, `License_no`, `Phone_no`, `Email`, `CredentialID`) VALUES
(1, 'Quest Diagnostics - Albany', '2 Executive Park Dr, 2nd Fl, Albany, NY 12203', '1223334444', '5184383388', 'info@questdiagnostic.org', 53),
(2, 'Labcorp', '526 Central Ave, Albany, NY 12206', '1223334445', '5186127006', 'services@labcorp.org', 52),
(3, 'TestAmerica (Albany) Service Center', '25 Kraft Ave, Albany, NY 12205', '1223334446', '5184388140', 'services@testamerica.com', 51),
(4, 'St. Peter\'s Laboratory', 'First Floor, 6 Executive Park Dr Building B, Albany, NY 12203', '1223334447', '5185251475', 'info@stpeterlab.com', 41),
(5, 'Albany Medical Center Pathology', '47 New Scotland Ave, Albany, NY 12208', '1223334448', '5182625454', 'info@albanymedicalcenterpathology.org', 42),
(6, 'Wadsworth Center', 'Empire State Plaza, Albany, NY 12237', '1223334449', '5184855378', 'services@wadsworth.org', 43),
(7, 'Northeast Testing Upstate Inc', '21 Everett Rd Ext, Albany, NY 12205', '1223334450', '5186181255', 'info@northeast.org', 44),
(8, 'Rite Aid', '1225 Western Ave, Albany, NY 12203', '1223334451', '5184588691', 'info@riteaid.org', 45),
(9, 'Samaritan Hospital-Albany Memorial Campus Test Center', '600 North St, Albany, NY 12204', '1223334452', '5184713221', 'services@samaritanhos.org', 46),
(10, '+MEDRITE Delmar Urgent Care - New York', '363 Delaware Ave, Delmar, NY 12054', '1223334453', '5188180238', 'info@medritedelmarurgentcare.org', 47),
(11, 'LabCare of Community Care Physicians - Albany', '391 Myrtle Ave, Albany, NY 12208', '1213324343', '5182130448', 'info@labcare.com', 48),
(12, 'Diagnostic Immunology Laboratory', '120 New Scotland Ave, Albany, NY 12208', '1223334442', '5184741477', 'info@diagnosticimmun.org', 49),
(13, 'Albany Memorial Campus', '600 Northern Blvd, Albany, NY 12204', '11112223333', '5184713221', 'service@albanymemorial.org', 50),
(16, 'CVS', '1215, washington ave', '12345a', '5182937890', 'cvs@gmail.com', 69),
(17, 'Sunrise', '120, western avenue', '123456hguygyu', '2134567120', 'sun@yahoo.com', 70),
(18, 'Pulse', '1234, washington avenue', '12309opulse', '5188997234', 'pulse@gmail.com', 71),
(35, 'Querty', 'House No: 01, Road No: 1/1, Nipobon R/A, Khadimpara', '12344321', '1234123412', 'qert@gmail.com', 107),
(36, 'Citymed', 'long island', '1980990', '5185990990', 'citymed@gmail.com', 116),
(37, 'LabDash', '1206, hawthrone ave', '1234567', '5185667667', 'labdash@gmail.com', 120),
(38, 'Lab Door', '1266, Quail Street', 'labdoorQuail24', '5185998212', 'labdoor24@gmail.com', 121);

-- --------------------------------------------------------

--
-- Table structure for table `lab_test`
--

CREATE TABLE `lab_test` (
  `TestID` int(11) NOT NULL,
  `Test_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_test`
--

INSERT INTO `lab_test` (`TestID`, `Test_name`) VALUES
(1, 'COVID-19 testing'),
(2, 'Routine Blood Tests'),
(3, 'STD Testing'),
(4, 'TB Test'),
(5, 'Chest X-Ray'),
(6, 'Breath Alcohol Test'),
(7, 'Comprehensive Metabolic Panel'),
(8, 'A1C Blood Testing'),
(9, 'Urinalysis'),
(10, 'Complete blood count (CBC)'),
(11, 'Hemoglobin A1c (HbA1c)'),
(12, 'Prothrombin time'),
(13, 'Thyroid-stimulating hormone (TSH)'),
(14, 'Bacterial culture'),
(15, 'Allergy Blood Test'),
(16, 'Beta HCG Test'),
(17, 'Pregnancy Test'),
(18, 'Cardiac CT scan'),
(19, 'MRI'),
(20, 'Cardiac Catheterization'),
(21, 'Vitamin D Test');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `PatientID` int(11) NOT NULL,
  `Pt_Name` varchar(255) NOT NULL,
  `DOB` date NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Mailing_address` varchar(255) DEFAULT NULL,
  `Ins_member_id` int(11) NOT NULL,
  `Phone_no` varchar(10) DEFAULT NULL,
  `CredentialID` int(11) DEFAULT NULL,
  `InsuranceID` int(11) DEFAULT NULL
) ;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PatientID`, `Pt_Name`, `DOB`, `Email`, `Mailing_address`, `Ins_member_id`, `Phone_no`, `CredentialID`, `InsuranceID`) VALUES
(1, 'Barbara Clark', '1998-01-01', 'barbara.clark@fakeemail.com', 'Alabama, AL', 8897, '8578012345', 21, 5),
(2, 'David Allen', '2000-01-15', 'david.allen@fakeemail.com', 'Alaska, AK', 9123, '5159012345', 22, 5),
(3, 'James Carter', '1999-02-18', 'james.carter@fakeemail.com', 'Arizona, AZ', 1245, '5181234567', 23, 5),
(4, 'Karen Green', '2002-04-09', 'karen.green@fakeemail.com', 'Arkansas, AR', 20234, '5452023456', 24, 5),
(5, 'William Hall', '2001-08-08', 'william.hall@fakeemail.com', 'California, CA', 11345, '5552234567', 25, 5),
(6, 'Linda Jackson', '1985-02-14', 'linda.jackson@fakeemail.com', 'Colorado, CO', 6780, '5556789012', 26, 5),
(7, 'Christopher King', '1992-05-23', 'christopher.king@fakeemail.com', 'Connecticut, CT', 15789, '5556678901', 27, 5),
(8, 'John Kim', '1978-07-30', 'john.kim@fakeemail.com', 'Delaware, DE', 5679, '5555678901', 28, 5),
(9, 'Jessica Lewis', '2001-11-12', 'jessica.lewis@fakeemail.com', 'Florida, FL', 12456, '5153345678', 29, 5),
(10, 'Sarah Lopez', '1995-08-05', 'sarah.lopez@fakeemail.com', 'Georgia, GA', 18012, '5559901234', 30, 5),
(11, 'Joseph Martinez', '1988-03-18', 'joseph.martinez@fakeemail.com', 'Hawaii, HI', 17901, '5558890123', 31, 5),
(12, 'Patricia Mitchell', '1976-12-25', 'patricia.mitchell@fakeemail.com', 'Idaho, ID', 4568, '5184567890', 32, 5),
(13, 'Michael Nguyen', '1990-06-09', 'michael.nguyen@fakeemail.com', 'Illinois, IL', 7891, '5557890123', 33, 5),
(14, 'Karen Hernandez', '1983-09-27', 'karen.hernandez@fakeemail.com', 'Indiana, IN', 14678, '5555567890', 34, 5),
(15, 'Thomas Scott', '2003-01-03', 'thomas.scott@fakeemail.com', 'Iowa, IA', 19123, '5551012345', 35, 5),
(16, 'Charles Young', '1987-04-20', 'charles.young@fakeemail.com', 'Kansas, KS', 13567, '5554456789', 36, 5),
(17, 'Robert Walker', '1999-10-10', 'robert.walker@fakeemail.com', 'Kentucky, KY', 3457, '5553456789', 37, 5),
(18, 'Susan Wright', '1982-07-04', 'susan.wright@fakeemail.com', 'Louisiana, LA', 10234, '5551123456', 38, 5),
(19, 'Nancy Robinson', '1996-05-15', 'nancy.robinson@fakeemail.com', 'Maine, ME', 16890, '5557789012', 39, 5),
(20, 'Maria Thompson', '2000-06-27', 'maria.thompson@fakeemail.com', 'Maryland, MD', 2346, '5552345678', 40, 5),
(28, 'Roger Whitefield', '1989-09-09', 'roger@yahoo.com', '1061, Loudonville', 909089, '5185443324', 127, 15),
(29, 'Sharmista', '2024-08-05', 'sharmista@gmail.com', 'Albany,ny', 122334555, '2346277363', 130, 55),
(30, 'Tajkia', '1988-01-08', 'tajkia@gmail.com', 'Albany,NY', 8675453, '1234567890', 131, 55),
(31, 'Nowshin', '1970-10-23', 'nowshin@gmail.com', 'Albany,NY', 1355778, '1234567890', 132, 58),
(32, 'Habiba', '1965-11-15', 'habiba@gmail.com', 'Albany,NY', 4788686, '1234567890', 133, 58),
(33, 'Charalampos', '1980-02-07', 'Charalampos@gmail.com', 'Albany,NY', 2356788, '0987654321', 134, 58),
(34, 'Amir', '1990-02-03', 'Amir@gmail.com', 'Albany,NY', 1322343, '5677362343', 135, 55);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `ReportID` int(11) NOT NULL,
  `File` varchar(255) NOT NULL,
  `Report_status` varchar(50) NOT NULL,
  `PatientID` int(11) DEFAULT NULL,
  `LabID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`ReportID`, `File`, `Report_status`, `PatientID`, `LabID`) VALUES
(1, '1_1_1', 'Sent', 1, 1),
(2, '2_8_1', 'Sent', 8, 1),
(3, '3_3_1', 'Sent', 3, 1),
(4, '4_5_1', 'Not Sent', 5, 1),
(5, '5_6_1', 'Sent', 6, 1),
(6, '6_7_1', 'Not Sent', 7, 1),
(7, '7_12_1', 'Not Sent', 12, 1),
(8, '8_13_1', 'Sent', 13, 1),
(9, '9_10_1', 'Sent', 10, 1),
(10, '10_11_1', 'Not Sent', 11, 1),
(11, '11_4_1', 'Sent', 4, 1),
(12, '12_8_1', 'Not Sent', 8, 1),
(13, '13_9_1', 'Sent', 19, 1),
(14, '14_20_1', 'Sent', 20, 1),
(15, '15_18_1', 'Sent', 18, 1),
(16, '16_14_1', 'Not Sent', 14, 1),
(20, '20_16_1', 'Sent', 16, 1),
(23, '41_30_37', 'Not Sent', 30, 37),
(24, '42_30_37', 'Not Sent', 30, 37),
(25, '46_31_37', 'Not Sent', 31, 37),
(26, '39_30_37', 'Not Sent', 30, 37),
(27, '24_33_37', 'Not Sent', 33, 37),
(28, '47_31_38', 'Not Sent', 31, 38),
(29, '45_31_38', 'Not Sent', 31, 38);

-- --------------------------------------------------------

--
-- Table structure for table `test_availability`
--

CREATE TABLE `test_availability` (
  `LabID` int(11) NOT NULL,
  `TestID` int(11) NOT NULL,
  `AvailableID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_availability`
--

INSERT INTO `test_availability` (`LabID`, `TestID`, `AvailableID`) VALUES
(1, 1, 4),
(1, 4, 5),
(1, 6, 13),
(1, 6, 14),
(1, 6, 15),
(1, 6, 16),
(1, 6, 17),
(1, 6, 18),
(1, 6, 19),
(1, 6, 20),
(1, 7, 6),
(1, 8, 13),
(1, 8, 14),
(1, 8, 15),
(1, 8, 16),
(1, 8, 17),
(1, 8, 18),
(1, 8, 19),
(1, 8, 20),
(1, 10, 13),
(1, 10, 14),
(1, 10, 15),
(1, 10, 16),
(1, 10, 17),
(1, 10, 18),
(1, 10, 19),
(1, 10, 20),
(1, 14, 2),
(1, 15, 13),
(1, 15, 14),
(1, 15, 15),
(1, 15, 16),
(1, 15, 17),
(1, 15, 18),
(1, 15, 19),
(1, 15, 20),
(1, 17, 13),
(1, 17, 14),
(1, 17, 15),
(1, 17, 16),
(1, 17, 17),
(1, 17, 18),
(1, 17, 19),
(1, 17, 20),
(1, 19, 13),
(1, 19, 14),
(1, 19, 15),
(1, 19, 16),
(1, 19, 17),
(1, 19, 18),
(1, 19, 19),
(1, 19, 20),
(1, 20, 1),
(2, 1, 8),
(2, 2, 9),
(2, 3, 10),
(2, 4, 11),
(2, 20, 7),
(3, 3, 12),
(3, 5, 13),
(3, 7, 14),
(3, 8, 15),
(3, 9, 16),
(4, 10, 20),
(4, 11, 19),
(4, 12, 18),
(4, 13, 17),
(5, 3, 5),
(5, 16, 1),
(5, 17, 2),
(5, 18, 3),
(5, 19, 4),
(6, 5, 6),
(6, 16, 8),
(6, 20, 7),
(7, 1, 9),
(7, 2, 11),
(7, 4, 10),
(7, 5, 12),
(8, 6, 13),
(8, 7, 14),
(8, 8, 15),
(8, 9, 16),
(9, 1, 17),
(9, 2, 19),
(9, 3, 18),
(9, 6, 20),
(10, 12, 2),
(10, 14, 1),
(11, 11, 3),
(11, 17, 4),
(12, 2, 6),
(12, 18, 5),
(13, 3, 7),
(13, 4, 8),
(35, 1, 13),
(35, 1, 14),
(35, 1, 15),
(35, 1, 16),
(35, 1, 17),
(35, 1, 18),
(35, 1, 19),
(35, 1, 20),
(36, 1, 13),
(36, 1, 14),
(36, 1, 15),
(36, 1, 16),
(36, 1, 17),
(36, 1, 18),
(36, 1, 19),
(36, 1, 20),
(36, 2, 13),
(36, 2, 14),
(36, 2, 15),
(36, 2, 16),
(36, 2, 17),
(36, 2, 18),
(36, 2, 19),
(36, 2, 20),
(37, 1, 13),
(37, 1, 14),
(37, 1, 15),
(37, 1, 16),
(37, 1, 17),
(37, 1, 18),
(37, 1, 19),
(37, 1, 20),
(37, 2, 13),
(37, 2, 14),
(37, 2, 15),
(37, 2, 16),
(37, 2, 17),
(37, 2, 18),
(37, 2, 19),
(37, 2, 20),
(37, 4, 13),
(37, 4, 14),
(37, 4, 15),
(37, 4, 16),
(37, 4, 17),
(37, 4, 18),
(37, 4, 19),
(37, 4, 20),
(37, 7, 13),
(37, 7, 14),
(37, 7, 15),
(37, 7, 16),
(37, 7, 17),
(37, 7, 18),
(37, 7, 19),
(37, 7, 20),
(37, 11, 13),
(37, 11, 14),
(37, 11, 15),
(37, 11, 16),
(37, 11, 17),
(37, 11, 18),
(37, 11, 19),
(37, 11, 20),
(37, 12, 13),
(37, 12, 14),
(37, 12, 15),
(37, 12, 16),
(37, 12, 17),
(37, 12, 18),
(37, 12, 19),
(37, 12, 20),
(37, 15, 13),
(37, 15, 14),
(37, 15, 15),
(37, 15, 16),
(37, 15, 17),
(37, 15, 18),
(37, 15, 19),
(37, 15, 20),
(37, 17, 13),
(37, 17, 14),
(37, 17, 15),
(37, 17, 16),
(37, 17, 17),
(37, 17, 18),
(37, 17, 19),
(37, 17, 20),
(37, 19, 13),
(37, 19, 14),
(37, 19, 15),
(37, 19, 16),
(37, 19, 17),
(37, 19, 18),
(37, 19, 19),
(37, 19, 20),
(37, 21, 13),
(37, 21, 14),
(37, 21, 15),
(37, 21, 16),
(37, 21, 17),
(37, 21, 18),
(37, 21, 19),
(37, 21, 20),
(38, 1, 13),
(38, 1, 14),
(38, 1, 15),
(38, 1, 16),
(38, 1, 17),
(38, 1, 18),
(38, 1, 19),
(38, 1, 20),
(38, 2, 13),
(38, 2, 14),
(38, 2, 15),
(38, 2, 16),
(38, 2, 17),
(38, 2, 18),
(38, 2, 19),
(38, 2, 20),
(38, 5, 13),
(38, 5, 14),
(38, 5, 15),
(38, 5, 16),
(38, 5, 17),
(38, 5, 18),
(38, 5, 19),
(38, 5, 20),
(38, 8, 13),
(38, 8, 14),
(38, 8, 15),
(38, 8, 16),
(38, 8, 17),
(38, 8, 18),
(38, 8, 19),
(38, 8, 20),
(38, 10, 13),
(38, 10, 14),
(38, 10, 15),
(38, 10, 16),
(38, 10, 17),
(38, 10, 18),
(38, 10, 19),
(38, 10, 20),
(38, 12, 13),
(38, 12, 14),
(38, 12, 15),
(38, 12, 16),
(38, 12, 17),
(38, 12, 18),
(38, 12, 19),
(38, 12, 20),
(38, 16, 13),
(38, 16, 14),
(38, 16, 15),
(38, 16, 16),
(38, 16, 17),
(38, 16, 18),
(38, 16, 19),
(38, 16, 20),
(38, 18, 13),
(38, 18, 14),
(38, 18, 15),
(38, 18, 16),
(38, 18, 17),
(38, 18, 18),
(38, 18, 19),
(38, 18, 20),
(38, 19, 13),
(38, 19, 14),
(38, 19, 15),
(38, 19, 16),
(38, 19, 17),
(38, 19, 18),
(38, 19, 19),
(38, 19, 20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`AppointmentID`),
  ADD KEY `TestID` (`TestID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `LabID` (`LabID`);

--
-- Indexes for table `available_dates`
--
ALTER TABLE `available_dates`
  ADD PRIMARY KEY (`AvailableID`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`BillID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `LabID` (`LabID`);

--
-- Indexes for table `candidate_key_table`
--
ALTER TABLE `candidate_key_table`
  ADD PRIMARY KEY (`ClaimID`,`AppointmentID`,`ReportID`),
  ADD KEY `AppointmentID` (`AppointmentID`),
  ADD KEY `ReportID` (`ReportID`);

--
-- Indexes for table `claim`
--
ALTER TABLE `claim`
  ADD PRIMARY KEY (`ClaimID`),
  ADD KEY `InsuranceID` (`InsuranceID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `LabID` (`LabID`);

--
-- Indexes for table `credentials`
--
ALTER TABLE `credentials`
  ADD PRIMARY KEY (`CredentialID`),
  ADD UNIQUE KEY `Login_ID` (`Login_ID`);

--
-- Indexes for table `insurance_company`
--
ALTER TABLE `insurance_company`
  ADD PRIMARY KEY (`InsuranceID`),
  ADD KEY `CredentialID` (`CredentialID`);

--
-- Indexes for table `lab`
--
ALTER TABLE `lab`
  ADD PRIMARY KEY (`LabID`),
  ADD KEY `CredentialID` (`CredentialID`);

--
-- Indexes for table `lab_test`
--
ALTER TABLE `lab_test`
  ADD PRIMARY KEY (`TestID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PatientID`),
  ADD UNIQUE KEY `Ins_member_id` (`Ins_member_id`),
  ADD KEY `CredentialID` (`CredentialID`),
  ADD KEY `InsuranceID` (`InsuranceID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `LabID` (`LabID`);

--
-- Indexes for table `test_availability`
--
ALTER TABLE `test_availability`
  ADD PRIMARY KEY (`LabID`,`TestID`,`AvailableID`),
  ADD KEY `AvailableID` (`AvailableID`),
  ADD KEY `TestID` (`TestID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `AppointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `available_dates`
--
ALTER TABLE `available_dates`
  MODIFY `AvailableID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `BillID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `claim`
--
ALTER TABLE `claim`
  MODIFY `ClaimID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `credentials`
--
ALTER TABLE `credentials`
  MODIFY `CredentialID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_company`
--
ALTER TABLE `insurance_company`
  MODIFY `InsuranceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab`
--
ALTER TABLE `lab`
  MODIFY `LabID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_test`
--
ALTER TABLE `lab_test`
  MODIFY `TestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`TestID`) REFERENCES `lab_test` (`TestID`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`LabID`) REFERENCES `lab` (`LabID`) ON DELETE CASCADE;

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_ibfk_2` FOREIGN KEY (`LabID`) REFERENCES `lab` (`LabID`) ON DELETE CASCADE;

--
-- Constraints for table `candidate_key_table`
--
ALTER TABLE `candidate_key_table`
  ADD CONSTRAINT `candidate_key_table_ibfk_1` FOREIGN KEY (`ClaimID`) REFERENCES `claim` (`ClaimID`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidate_key_table_ibfk_2` FOREIGN KEY (`AppointmentID`) REFERENCES `appointments` (`AppointmentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidate_key_table_ibfk_3` FOREIGN KEY (`ReportID`) REFERENCES `report` (`ReportID`) ON DELETE CASCADE;

--
-- Constraints for table `claim`
--
ALTER TABLE `claim`
  ADD CONSTRAINT `claim_ibfk_1` FOREIGN KEY (`InsuranceID`) REFERENCES `credentials` (`CredentialID`) ON DELETE CASCADE,
  ADD CONSTRAINT `claim_ibfk_2` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE,
  ADD CONSTRAINT `claim_ibfk_3` FOREIGN KEY (`LabID`) REFERENCES `lab` (`LabID`) ON DELETE CASCADE;

--
-- Constraints for table `insurance_company`
--
ALTER TABLE `insurance_company`
  ADD CONSTRAINT `insurance_company_ibfk_1` FOREIGN KEY (`CredentialID`) REFERENCES `credentials` (`CredentialID`) ON DELETE CASCADE;

--
-- Constraints for table `lab`
--
ALTER TABLE `lab`
  ADD CONSTRAINT `lab_ibfk_1` FOREIGN KEY (`CredentialID`) REFERENCES `credentials` (`CredentialID`) ON DELETE CASCADE;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`CredentialID`) REFERENCES `credentials` (`CredentialID`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_ibfk_2` FOREIGN KEY (`InsuranceID`) REFERENCES `insurance_company` (`InsuranceID`) ON DELETE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`LabID`) REFERENCES `lab` (`LabID`) ON DELETE CASCADE;

--
-- Constraints for table `test_availability`
--
ALTER TABLE `test_availability`
  ADD CONSTRAINT `test_availability_ibfk_1` FOREIGN KEY (`LabID`) REFERENCES `lab` (`LabID`) ON DELETE CASCADE,
  ADD CONSTRAINT `test_availability_ibfk_2` FOREIGN KEY (`AvailableID`) REFERENCES `available_dates` (`AvailableID`) ON DELETE CASCADE,
  ADD CONSTRAINT `test_availability_ibfk_3` FOREIGN KEY (`TestID`) REFERENCES `lab_test` (`TestID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
