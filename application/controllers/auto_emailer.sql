-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 04, 2023 at 11:11 AM
-- Server version: 5.7.40-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auto_emailer`
--

-- --------------------------------------------------------

--
-- Table structure for table `mst_alert`
--

CREATE TABLE `mst_alert` (
  `sk_alert_id` int(100) NOT NULL,
  `to` varchar(100) DEFAULT NULL,
  `cc` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `customer` varchar(100) DEFAULT NULL,
  `project` varchar(100) DEFAULT NULL,
  `frequency` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `alert_message` longtext,
  `pdf_file` varchar(100) DEFAULT NULL,
  `alert_status` int(10) DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_alert_list`
--

CREATE TABLE `mst_alert_list` (
  `sk_alert_list_id` int(100) NOT NULL,
  `alert_for_days` varchar(100) DEFAULT NULL,
  `alertstopdate` date DEFAULT NULL,
  `for_how_many_days` varchar(100) DEFAULT NULL,
  `date` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `alert_id` varchar(100) DEFAULT NULL,
  `datestart` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_country`
--

CREATE TABLE `mst_country` (
  `sk_country_id` int(100) NOT NULL,
  `country_name` varchar(100) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_customer`
--

CREATE TABLE `mst_customer` (
  `sk_customer_id` bigint(20) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `description` longtext,
  `address_1` longtext,
  `address_2` longtext,
  `city` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `postalcode` int(11) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `org_id` bigint(20) DEFAULT NULL,
  `customer_status` tinyint(1) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `cust_country` varchar(100) DEFAULT NULL,
  `address_full` varchar(100) DEFAULT NULL,
  `pocnumber` varchar(100) DEFAULT NULL,
  `poc` varchar(100) DEFAULT NULL,
  `billing` varchar(10) DEFAULT NULL,
  `phonenumner` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_language`
--

CREATE TABLE `mst_language` (
  `sk_language_id` int(100) NOT NULL,
  `language` varchar(100) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_org`
--

CREATE TABLE `mst_org` (
  `sk_org_id` bigint(20) NOT NULL,
  `orn_name` varchar(100) DEFAULT NULL,
  `full_address` longtext,
  `state_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `subscription_id` int(11) DEFAULT NULL,
  `subscription_end_date` date DEFAULT NULL,
  `org_status` tinyint(1) DEFAULT NULL,
  `create_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_org`
--

INSERT INTO `mst_org` (`sk_org_id`, `orn_name`, `full_address`, `state_id`, `country_id`, `subscription_id`, `subscription_end_date`, `org_status`, `create_date`) VALUES
(1, 'Terralogic', NULL, 1, 1, 1, '2024-01-31', 1, '2023-02-08');

-- --------------------------------------------------------

--
-- Table structure for table `mst_permission_projetcs`
--

CREATE TABLE `mst_permission_projetcs` (
  `sk_permission_id` int(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `customers` varchar(100) NOT NULL,
  `projects` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_projects`
--

CREATE TABLE `mst_projects` (
  `sk_project_id` int(100) NOT NULL,
  `project_name` varchar(100) DEFAULT NULL,
  `code` varchar(200) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `selected_format` varchar(100) DEFAULT NULL,
  `country_name` varchar(100) DEFAULT NULL,
  `project_status` int(1) DEFAULT NULL,
  `created_date` datetime(6) DEFAULT NULL,
  `project_contact_num` varchar(100) DEFAULT NULL,
  `pocontact_num` varchar(100) DEFAULT NULL,
  `pocalternative` varchar(100) DEFAULT NULL,
  `project_contac_alternative` varchar(100) DEFAULT NULL,
  `client` varchar(100) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `pname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_role`
--

CREATE TABLE `mst_role` (
  `sk_role_id` int(11) NOT NULL,
  `role_name` varchar(100) DEFAULT NULL,
  `role_status` tinyint(1) DEFAULT NULL,
  `org_id` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_subscription_plans`
--

CREATE TABLE `mst_subscription_plans` (
  `sk_plan_id` bigint(20) NOT NULL,
  `plan_name` varchar(100) DEFAULT NULL,
  `plan_details` longtext,
  `price` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `plan_status` tinyint(1) DEFAULT NULL,
  `create_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_subscription_plans`
--

INSERT INTO `mst_subscription_plans` (`sk_plan_id`, `plan_name`, `plan_details`, `price`, `discount`, `plan_status`, `create_date`) VALUES
(1, 'Free Plan', NULL, 0, 0, 1, '2023-02-08');

-- --------------------------------------------------------

--
-- Table structure for table `mst_test`
--

CREATE TABLE `mst_test` (
  `id` int(11) NOT NULL,
  `action` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mst_users`
--

CREATE TABLE `mst_users` (
  `sk_user_id` bigint(20) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_pic` varchar(500) DEFAULT NULL,
  `user_role` int(11) DEFAULT NULL,
  `user_status` tinyint(1) DEFAULT NULL,
  `org_id` bigint(20) DEFAULT NULL,
  `create_date` datetime(6) DEFAULT NULL,
  `phonenumber` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `description` longtext,
  `town` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `post_code` varchar(100) DEFAULT NULL,
  `lastlogin` datetime(6) DEFAULT NULL,
  `user_role_col` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_users`
--

INSERT INTO `mst_users` (`sk_user_id`, `full_name`, `email`, `user_password`, `user_pic`, `user_role`, `user_status`, `org_id`, `create_date`, `phonenumber`, `country`, `address`, `language`, `address2`, `description`, `town`, `state`, `post_code`, `lastlogin`, `user_role_col`) VALUES
(10, 'uday', 'udaykumar.bobbili@terralogic.com', '148dda29d940c2eb84a0a0f3a8563933', '5e9b9707.jpeg', 2, 1, 1, '2023-02-28 00:00:00.000000', '6362370286', '1', 'banglore', '3', 'paravathi nagar', 'akjsd', 'sjad', 'karnatak', '583120', '2023-04-03 14:10:36.000000', 'admin'),
(70, 'Juan', 'juan.angulo@terralogic.com', '148dda29d940c2eb84a0a0f3a8563933', NULL, 2, 1, 1, '2023-04-03 00:00:00.000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mst_alert`
--
ALTER TABLE `mst_alert`
  ADD PRIMARY KEY (`sk_alert_id`);

--
-- Indexes for table `mst_alert_list`
--
ALTER TABLE `mst_alert_list`
  ADD PRIMARY KEY (`sk_alert_list_id`);

--
-- Indexes for table `mst_country`
--
ALTER TABLE `mst_country`
  ADD PRIMARY KEY (`sk_country_id`);

--
-- Indexes for table `mst_customer`
--
ALTER TABLE `mst_customer`
  ADD PRIMARY KEY (`sk_customer_id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `mst_language`
--
ALTER TABLE `mst_language`
  ADD PRIMARY KEY (`sk_language_id`);

--
-- Indexes for table `mst_org`
--
ALTER TABLE `mst_org`
  ADD PRIMARY KEY (`sk_org_id`);

--
-- Indexes for table `mst_permission_projetcs`
--
ALTER TABLE `mst_permission_projetcs`
  ADD PRIMARY KEY (`sk_permission_id`);

--
-- Indexes for table `mst_projects`
--
ALTER TABLE `mst_projects`
  ADD PRIMARY KEY (`sk_project_id`);

--
-- Indexes for table `mst_role`
--
ALTER TABLE `mst_role`
  ADD PRIMARY KEY (`sk_role_id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `mst_subscription_plans`
--
ALTER TABLE `mst_subscription_plans`
  ADD PRIMARY KEY (`sk_plan_id`);

--
-- Indexes for table `mst_test`
--
ALTER TABLE `mst_test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_users`
--
ALTER TABLE `mst_users`
  ADD PRIMARY KEY (`sk_user_id`),
  ADD KEY `org_id` (`org_id`),
  ADD KEY `user_role` (`user_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mst_alert`
--
ALTER TABLE `mst_alert`
  MODIFY `sk_alert_id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mst_alert_list`
--
ALTER TABLE `mst_alert_list`
  MODIFY `sk_alert_list_id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mst_country`
--
ALTER TABLE `mst_country`
  MODIFY `sk_country_id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mst_customer`
--
ALTER TABLE `mst_customer`
  MODIFY `sk_customer_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mst_language`
--
ALTER TABLE `mst_language`
  MODIFY `sk_language_id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mst_org`
--
ALTER TABLE `mst_org`
  MODIFY `sk_org_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `mst_permission_projetcs`
--
ALTER TABLE `mst_permission_projetcs`
  MODIFY `sk_permission_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `mst_projects`
--
ALTER TABLE `mst_projects`
  MODIFY `sk_project_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `mst_role`
--
ALTER TABLE `mst_role`
  MODIFY `sk_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `mst_subscription_plans`
--
ALTER TABLE `mst_subscription_plans`
  MODIFY `sk_plan_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `mst_test`
--
ALTER TABLE `mst_test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mst_users`
--
ALTER TABLE `mst_users`
  MODIFY `sk_user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `mst_customer`
--
ALTER TABLE `mst_customer`
  ADD CONSTRAINT `mst_customer_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `mst_org` (`sk_org_id`) ON DELETE CASCADE;

--
-- Constraints for table `mst_role`
--
ALTER TABLE `mst_role`
  ADD CONSTRAINT `mst_role_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `mst_org` (`sk_org_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
