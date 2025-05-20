-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 08:22 AM
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
-- Database: `workers_association`
--

-- --------------------------------------------------------

--
-- Table structure for table `registry_of_establishment`
--

CREATE TABLE `registry_of_establishment` (
  `id` int(50) NOT NULL,
  `EIN` int(50) NOT NULL,
  `establishment_name` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `tin` int(50) NOT NULL,
  `telephone` int(50) NOT NULL,
  `fax` int(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `manager_owner` varchar(255) NOT NULL,
  `business_nature` varchar(255) NOT NULL,
  `business_nature_other` varchar(255) NOT NULL,
  `filipino_male` int(11) NOT NULL,
  `resident_alien_male` int(11) NOT NULL,
  `non_resident_alien_male` int(11) NOT NULL,
  `below15_male` int(11) NOT NULL,
  `15to17_male` int(11) NOT NULL,
  `18to30_male` int(11) NOT NULL,
  `above30_male` int(11) NOT NULL,
  `total_male` int(11) NOT NULL,
  `filipino_female` int(11) NOT NULL,
  `resident_alien_female` int(11) NOT NULL,
  `non_resident_alien_female` int(11) NOT NULL,
  `below15_female` int(11) NOT NULL,
  `15to17_female` int(11) NOT NULL,
  `18to30_female` int(11) NOT NULL,
  `above30_female` int(11) NOT NULL,
  `total_female` int(11) NOT NULL,
  `grand_total_filipinos` int(11) NOT NULL,
  `grand_total_resident_alien` int(11) NOT NULL,
  `grand_total_non_resident_alien` int(11) NOT NULL,
  `grand_total_below15` int(11) NOT NULL,
  `grand_total_15to17` int(11) NOT NULL,
  `grand_total_18to30` int(11) NOT NULL,
  `grand_total_above30` int(11) NOT NULL,
  `grand_total_all` int(11) NOT NULL,
  `labor_union` varchar(255) NOT NULL,
  `blr_certification` varchar(255) NOT NULL,
  `machinery_equipmen` text NOT NULL,
  `materials_handling` text NOT NULL,
  `chemicals_used` int(11) NOT NULL,
  `parent_establishment` varchar(255) NOT NULL,
  `branch_street` varchar(255) NOT NULL,
  `branch_city` varchar(255) NOT NULL,
  `branch_province` varchar(255) NOT NULL,
  `capitalization` varchar(255) NOT NULL,
  `total_assets` varchar(255) NOT NULL,
  `dti_permit_path` longblob NOT NULL,
  `past_application_number` int(255) NOT NULL,
  `past_application_date` date NOT NULL,
  `former_name` varchar(255) NOT NULL,
  `past_address` varchar(255) NOT NULL,
  `certification` tinyint(1) NOT NULL,
  `owner_president` varchar(255) NOT NULL,
  `date_filed` date NOT NULL,
  `date_approved` date NOT NULL,
  `approved_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registry_of_establishment`
--

INSERT INTO `registry_of_establishment` (`id`, `EIN`, `establishment_name`, `street`, `city`, `tin`, `telephone`, `fax`, `email`, `manager_owner`, `business_nature`, `business_nature_other`, `filipino_male`, `resident_alien_male`, `non_resident_alien_male`, `below15_male`, `15to17_male`, `18to30_male`, `above30_male`, `total_male`, `filipino_female`, `resident_alien_female`, `non_resident_alien_female`, `below15_female`, `15to17_female`, `18to30_female`, `above30_female`, `total_female`, `grand_total_filipinos`, `grand_total_resident_alien`, `grand_total_non_resident_alien`, `grand_total_below15`, `grand_total_15to17`, `grand_total_18to30`, `grand_total_above30`, `grand_total_all`, `labor_union`, `blr_certification`, `machinery_equipmen`, `materials_handling`, `chemicals_used`, `parent_establishment`, `branch_street`, `branch_city`, `branch_province`, `capitalization`, `total_assets`, `dti_permit_path`, `past_application_number`, `past_application_date`, `former_name`, `past_address`, `certification`, `owner_president`, `date_filed`, `date_approved`, `approved_by`) VALUES
(1, 12345, 'GLAIDYL\'S GARDEN OF ROSES', '3rd Floor Mabini Avenue, ', 'Catbalogan CIty', 123456, 10000, 0, '609mahogany@gmail.com', 'GLAIDYL REBATO', 'construction_building', '', 1, 0, 0, 0, 0, 5, 0, 0, 1, 0, 0, 0, 0, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'SAN JOSE DE BUAN SAMAR', '', '', '', 0, 'Cathy\'s Garden of Tomorrow', 'Azucencia Street San Pablo', 'Catbalogan City', 'Samar', 'Secret', '5,000', 0x75706c6f6164732f3030383337313837365f312d38383736393963386334663164643464613435663031646231363333366132382e706e672d323130783134372e706e67, 1234567890, '2025-04-21', 'GLAIDYL\'S Garden of Tomorrow', 'Brgy.11 Fabrigaras Old Market,. Catbalogan CIty', 1, 'RONA C. GALLEGAR', '2025-01-01', '2025-05-21', 'Rodante Abangan'),
(2, 1, 'NAERS BakeShop', 'Purok 2 ', 'Hinabangan', 2, 10101011, 10000, 'johnnerodacles@gmail.com', 'GLAIDYL REBATO', 'other', 'BakeShop', 5, 0, 0, 0, 0, 2, 2, 0, 5, 0, 0, 0, 0, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Bagacay Hinabangan Samar', '', '', '', 0, 'EMMAN\'s Bakeshop', 'Purok 2 Bagacay', 'Hinabangan ', 'Samar', '100000', '5000', 0x75706c6f6164732f3030383337313837365f312d38383736393963386334663164643464613435663031646231363333366132382e706e672d323130783134372e706e67, 0, '2025-04-24', 'secret', 'Brgy.11 Fabrigaras Old Market,. Catbalogan CIty', 1, 'RONA C. GALLEGAR', '2024-05-05', '2025-01-01', 'Rodante Abangan'),
(3, 2, 'GLAIDYL STORE', '3rd Floor Mabini Avenue, ', 'Catbalogan CIty', 123456, 10101012, 10001, 'setir39865@macho3.com', 'GLAIDYL REBATO', 'other', 'BakeShop', 1, 1, 2, 4, 0, 0, 0, 0, 1, 1, 3, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Catbalogan City, Samar ', '', '', '', 0, 'EMMAN\'s Bakeshop', 'Purok 2 Bagacay', 'Hinabangan ', 'Samar', '123456', '10002', 0x75706c6f6164732f4465706172746d656e745f6f665f4c61626f725f616e645f456d706c6f796d656e745f28444f4c45292e7376672e706e67, 1234567890, '2025-05-05', 'GLAIDYL\'S Garden of Tomorrow', 'Brgy.11 Fabrigaras Old Market,. Catbalogan CIty', 1, 'RONA C. GALLEGAR', '2025-04-21', '2025-04-21', 'Sir. Dems '),
(4, 123456, 'GLAIDYL&#39;S GARDEN OF ROSES', '3rd Floor Mabini Avenue, ', 'Catbalogan CIty', 123456, 10101011, 10000, 'johnnerodacles@gmail.com', 'GLAIDYL REBATO', 'construction_building', '', 10, 5, 5, 0, 3, 7, 0, 0, 10, 5, 5, 0, 3, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'SAN JOSE DE BUAN SAMAR', '', '', '', 0, 'Cathy&#39;s Garden of Tomorrow', 'Azucencia Street San Pablo', 'Catbalogan City', 'Samar', 'Secret', '10002', 0x75706c6f6164732f363832633132613937623765332e706e67, 1234567890, '2002-05-05', 'GLAIDYL&#39;S Garden of Tomorrow', 'sdfasfd', 1, 'RONA C. GALLEGAR', '2023-05-05', '2023-02-02', 'ENGR. ALEKSEI CAESAR ABELLAR');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `registry_of_establishment`
--
ALTER TABLE `registry_of_establishment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `registry_of_establishment`
--
ALTER TABLE `registry_of_establishment`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
