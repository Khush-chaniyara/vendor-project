-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 06, 2026 at 01:12 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vendorBridge`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_name` varchar(50) NOT NULL,
  `action` varchar(100) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_value` longtext DEFAULT NULL,
  `new_value` longtext DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `user_id`, `module_name`, `action`, `record_id`, `old_value`, `new_value`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 2, 'RFQ', 'CREATE', 1, NULL, NULL, 'RFQ-2026-001 Created', '127.0.0.1', NULL, '2026-06-06 04:44:17'),
(2, 3, 'APPROVAL', 'APPROVE', 1, NULL, NULL, 'Quotation QT-2026-001 Approved', '127.0.0.1', NULL, '2026-06-06 04:44:17'),
(3, 2, 'PURCHASE_ORDER', 'GENERATE', 1, NULL, NULL, 'PO-2026-001 Generated', '127.0.0.1', NULL, '2026-06-06 04:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `approval_id` int(11) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `approved_by` int(11) NOT NULL,
  `workflow_id` int(11) NOT NULL,
  `decision` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approvals`
--

INSERT INTO `approvals` (`approval_id`, `quotation_id`, `approved_by`, `workflow_id`, `decision`, `remarks`, `approval_date`, `status`) VALUES
(1, 1, 3, 1, 'Approved', 'Quotation approved for purchase', '2026-06-06 10:12:54', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `approval_workflow`
--

CREATE TABLE `approval_workflow` (
  `workflow_id` int(11) NOT NULL,
  `workflow_name` varchar(100) NOT NULL,
  `sequence_no` int(11) NOT NULL,
  `role_required` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approval_workflow`
--

INSERT INTO `approval_workflow` (`workflow_id`, `workflow_name`, `sequence_no`, `role_required`, `is_active`) VALUES
(1, 'Standard Procurement Approval', 1, 'Manager', 1);

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `attachment_id` int(11) NOT NULL,
  `module_type` enum('RFQ','QUOTATION','PURCHASE_ORDER','INVOICE') NOT NULL,
  `module_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`attachment_id`, `module_type`, `module_id`, `file_name`, `file_path`, `uploaded_by`, `uploaded_at`) VALUES
(1, 'RFQ', 1, 'Laptop_Specification.pdf', 'uploads/rfq/Laptop_Specification.pdf', 2, '2026-06-06 04:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL,
  `payment_status` enum('Pending','Paid','Overdue') DEFAULT 'Pending',
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `po_id`, `vendor_id`, `invoice_number`, `invoice_date`, `subtotal`, `tax_amount`, `grand_total`, `payment_status`, `due_date`, `created_at`) VALUES
(1, 1, 1, 'INV-2026-001', '2026-06-06', 1270000.00, 228600.00, 1498600.00, 'Paid', '2026-07-06', '2026-06-06 04:43:25'),
(2, 2, 2, 'INV-ES-202', '2026-06-03', 72033.90, 12966.10, 85000.00, 'Paid', '2026-06-25', '2026-06-06 06:31:07'),
(3, 1, 1, 'INV-TC-099', '2026-04-10', 42372.88, 7627.12, 50000.00, 'Paid', '2026-05-10', '2026-06-06 06:31:07'),
(4, 3, 1, 'INV-TC-105', '2026-06-05', 381355.93, 68644.07, 450000.00, 'Pending', '2026-06-20', '2026-06-06 06:31:07'),
(5, 1, 1, 'INV-2026-999', '2026-05-01', 5000.00, 0.00, 5000.00, 'Pending', '2026-06-01', '2026-06-06 09:33:43'),
(6, 1, 1, 'INV-2026-888', '2026-05-15', 75000.00, 0.00, 750000.00, 'Overdue', '2026-05-30', '2026-06-06 09:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `invoice_item_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `tax_percentage` decimal(5,2) DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`invoice_item_id`, `invoice_id`, `product_name`, `quantity`, `unit_price`, `tax_percentage`, `total_amount`) VALUES
(1, 1, 'Laptop', 20.00, 62000.00, 18.00, 1463200.00),
(2, 1, 'Wireless Mouse', 20.00, 1500.00, 18.00, 35400.00);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `notification_type` enum('RFQ','APPROVAL','PURCHASE_ORDER','INVOICE','SYSTEM') NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `title`, `message`, `notification_type`, `is_read`, `created_at`) VALUES
(1, 3, 'Approval Required', 'Quotation QT-2026-001 requires approval.', 'APPROVAL', 0, '2026-06-06 04:43:56'),
(2, 2, 'Purchase Order Generated', 'PO-2026-001 has been generated successfully.', 'PURCHASE_ORDER', 0, '2026-06-06 04:43:56');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `po_id` int(11) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `generated_by` int(11) NOT NULL,
  `po_number` varchar(50) NOT NULL,
  `po_date` date NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` varchar(20) DEFAULT 'Sent',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`po_id`, `quotation_id`, `vendor_id`, `generated_by`, `po_number`, `po_date`, `total_amount`, `status`, `created_at`) VALUES
(1, 1, 1, 2, 'PO-2026-001', '2026-06-06', 1270000.00, 'Generated', '2026-06-06 04:43:08'),
(2, 3, 2, 2, 'PO-2026-090', '2026-06-02', 85000.00, 'Sent', '2026-06-02 05:30:00'),
(3, 1, 1, 2, 'PO-2026-091', '2026-06-05', 450000.00, 'Generated', '2026-06-05 09:30:00'),
(4, 2, 5, 2, 'PO-2026-092', '2026-06-06', 25000.00, 'Accepted', '2026-06-06 03:30:00'),
(5, 5, 3, 2, 'PO-2026-093', '2026-06-06', 15000.00, 'Generated', '2026-06-06 04:30:00'),
(6, 6, 4, 2, 'PO-2025-010', '2025-12-10', 500000.00, 'Completed', '2025-12-10 04:30:00'),
(7, 7, 5, 2, 'PO-2026-094', '2026-06-05', 30000.00, 'Sent', '2026-06-05 04:30:00'),
(8, 2, 2, 1, 'PO-2026-274', '2026-06-06', 1315000.00, 'Generated', '2026-06-06 07:34:17'),
(9, 1, 1, 1, 'PO-2026-592', '2026-06-06', 1270000.00, 'Generated', '2026-06-06 07:34:34'),
(10, 8, 10, 2, 'PO-HIT-8', '2026-06-06', 50000.00, 'Confirmed', '2026-06-06 08:38:01'),
(11, 9, 10, 2, 'PO-HIT-9', '2026-06-06', 100000.00, 'Confirmed', '2026-06-06 08:38:01'),
(13, 12, 10, 1, 'PO-2026-906', '2026-06-06', 25000000.00, 'Generated', '2026-06-06 08:58:10'),
(14, 9, 10, 3, 'PO-BD41B4', '2026-06-06', 100000.00, 'Sent', '2026-06-06 09:10:35'),
(15, 14, 10, 3, 'PO-07EADE', '2026-06-06', 4889999.00, 'Sent', '2026-06-06 09:13:04'),
(16, 8, 10, 1, 'PO-2026-953', '2026-06-06', 50000.00, 'Generated', '2026-06-06 09:27:47');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `po_item_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`po_item_id`, `po_id`, `product_name`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 'Laptop', 20.00, 62000.00, 1240000.00),
(2, 1, 'Wireless Mouse', 20.00, 1500.00, 30000.00),
(3, 1, 'Dell Latitude Developer Laptops (16GB RAM)', 50.00, 24000.00, 120000.00),
(4, 2, 'Ergonomic Mesh Office Chair', 10.00, 8500.00, 85000.00),
(5, 3, 'A4 Copy Paper Bundles', 100.00, 450.00, 45000.00),
(6, 3, 'Whiteboard Marker Sets', 20.00, 500.00, 10000.00),
(7, 5, 'Premium A4 Paper Cartons', 10.00, 1500.00, 15000.00),
(8, 6, 'Cisco Catalyst 9300 Switches', 5.00, 100000.00, 500000.00),
(9, 7, 'Industrial Floor Cleaner Drums', 10.00, 3000.00, 30000.00),
(10, 8, 'Laptop', 20.00, 64000.00, 1280000.00),
(11, 8, 'Wireless Mouse', 20.00, 1750.00, 35000.00),
(12, 9, 'Laptop', 20.00, 62000.00, 1240000.00),
(13, 9, 'Wireless Mouse', 20.00, 1500.00, 30000.00);

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `quotation_id` int(11) NOT NULL,
  `rfq_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `quotation_number` varchar(50) NOT NULL,
  `submission_date` datetime DEFAULT current_timestamp(),
  `delivery_days` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` enum('Submitted','Revised','Accepted','Rejected') DEFAULT 'Submitted',
  `revised_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`quotation_id`, `rfq_id`, `vendor_id`, `quotation_number`, `submission_date`, `delivery_days`, `notes`, `total_amount`, `status`, `revised_at`) VALUES
(1, 1, 1, 'QT-2026-001', '2026-06-06 10:12:29', 10, 'Best price with fast delivery', 1270000.00, 'Rejected', NULL),
(2, 1, 2, 'QT-2026-002', '2026-06-06 10:12:29', 15, 'Alternative quotation', 1315000.00, 'Rejected', NULL),
(3, 2, 2, 'QT-ES-001', '2026-06-04 14:30:00', 10, NULL, 85000.00, 'Accepted', NULL),
(4, 4, 4, 'QT-GH-001', '2026-05-13 09:00:00', 20, NULL, 115000.00, 'Rejected', NULL),
(5, 3, 3, 'QT-OE-001', '2026-06-05 10:00:00', 5, NULL, 15000.00, 'Accepted', NULL),
(6, 4, 4, 'QT-GH-002', '2025-12-01 10:00:00', 15, NULL, 500000.00, 'Accepted', NULL),
(7, 5, 5, 'QT-CS-001', '2026-06-04 10:00:00', 2, NULL, 30000.00, 'Accepted', NULL),
(8, 1, 10, 'QT-HIT-1', '2026-06-06 14:08:01', 7, NULL, 50000.00, 'Accepted', NULL),
(9, 2, 10, 'QT-HIT-2', '2026-06-06 14:08:01', 7, NULL, 100000.00, 'Accepted', NULL),
(11, 3, 10, 'QT-4483', '2026-06-06 14:13:01', 21, NULL, 19000000.00, 'Rejected', NULL),
(12, 1, 10, 'QT-7746', '2026-06-06 14:25:28', 35, NULL, 25000000.00, 'Rejected', NULL),
(13, 2, 10, 'QT-3946', '2026-06-06 14:42:33', 30, NULL, 199999999.00, 'Rejected', NULL),
(14, 3, 10, 'QT-6386', '2026-06-06 14:42:46', 45, NULL, 4889999.00, 'Accepted', NULL),
(15, 3, 10, 'QT-2117', '2026-06-06 15:02:06', 20, NULL, 25000.00, 'Submitted', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `quotation_item_id` int(11) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `rfq_item_id` int(11) NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `line_total` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation_items`
--

INSERT INTO `quotation_items` (`quotation_item_id`, `quotation_id`, `rfq_item_id`, `quantity`, `unit_price`, `line_total`) VALUES
(1, 1, 1, 20.00, 62000.00, 1240000.00),
(2, 1, 2, 20.00, 1500.00, 30000.00),
(3, 2, 1, 20.00, 64000.00, 1280000.00),
(4, 2, 2, 20.00, 1750.00, 35000.00);

-- --------------------------------------------------------

--
-- Table structure for table `rfqs`
--

CREATE TABLE `rfqs` (
  `rfq_id` int(11) NOT NULL,
  `rfq_number` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `deadline` datetime NOT NULL,
  `status` enum('Draft','Open','Closed','Approved','Rejected') DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfqs`
--

INSERT INTO `rfqs` (`rfq_id`, `rfq_number`, `title`, `description`, `created_by`, `deadline`, `status`, `created_at`, `updated_at`) VALUES
(1, 'RFQ-2026-001', 'Laptop Procurement', 'Procurement of laptops for development team', 2, '2026-06-13 10:11:39', 'Closed', '2026-06-06 04:41:39', '2026-06-06 08:58:10'),
(2, 'RFQ-2026-002', 'Replacement of Ergonomic Office Chairs', '', 2, '2026-06-20 17:00:00', 'Draft', '2026-06-02 06:00:00', '2026-06-06 09:28:36'),
(3, 'RFQ-2026-003', 'Annual Stationery Supply Contract Q3', ' Stationery Supply Contract', 2, '2026-07-01 17:00:00', 'Open', '2026-06-05 03:45:00', '2026-06-06 08:42:15'),
(4, 'RFQ-2026-004', 'Server Rack Cooling System Upgrade', NULL, 2, '2026-05-20 17:00:00', 'Closed', '2026-05-10 08:30:00', '2026-06-06 06:31:07'),
(5, 'RFQ-2026-005', 'Cafeteria Housekeeping Contract', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>/Applications/XAMPP/xamppfiles/htdocs/vendor/procurement/edit_rfq.php</b> on line <b>60</b><br />\r\n', 2, '2026-06-10 17:00:00', 'Closed', '2026-06-03 11:15:00', '2026-06-06 08:02:16'),
(6, 'RFQ-2026-427', '75 macbook laptops ', 'hey guys send your quotation for 75 macbook before 30 june ', 2, '2026-06-30 12:00:00', 'Open', '2026-06-06 07:52:10', '2026-06-06 07:52:10'),
(12, 'RFQ-2026-689', 'IT office offline need ', 'Purchasing operational supplies not part of the final product, including office furniture, and cleaning services.', 2, '2026-06-08 08:00:00', 'Open', '2026-06-06 09:31:11', '2026-06-06 09:31:11');

-- --------------------------------------------------------

--
-- Table structure for table `rfq_items`
--

CREATE TABLE `rfq_items` (
  `rfq_item_id` int(11) NOT NULL,
  `rfq_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text DEFAULT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `estimated_price` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfq_items`
--

INSERT INTO `rfq_items` (`rfq_item_id`, `rfq_id`, `product_name`, `product_description`, `quantity`, `unit`, `estimated_price`) VALUES
(1, 1, 'Laptop', 'Intel i7, 16GB RAM, 512GB SSD', 20.00, 'Nos', 65000.00),
(2, 1, 'Wireless Mouse', 'USB Wireless Mouse', 20.00, 'Nos', 800.00),
(3, 6, 'macbook air m series (M1,M2,M3,M4)', NULL, 75.00, 'pics', NULL),
(4, 12, 'cleaning services.', NULL, 20.00, 'persons', NULL),
(5, 12, ' office furniture', NULL, 555.00, 'pis', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rfq_vendor_assignments`
--

CREATE TABLE `rfq_vendor_assignments` (
  `assignment_id` int(11) NOT NULL,
  `rfq_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `invitation_date` datetime DEFAULT current_timestamp(),
  `response_status` enum('Invited','Viewed','Submitted','Declined') DEFAULT 'Invited',
  `response_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfq_vendor_assignments`
--

INSERT INTO `rfq_vendor_assignments` (`assignment_id`, `rfq_id`, `vendor_id`, `invitation_date`, `response_status`, `response_date`) VALUES
(1, 1, 1, '2026-06-06 10:12:19', 'Submitted', NULL),
(2, 1, 2, '2026-06-06 10:12:19', 'Submitted', NULL),
(3, 1, 10, '2026-06-06 14:04:46', 'Invited', NULL),
(5, 2, 10, '2026-06-06 14:08:01', 'Invited', NULL),
(6, 3, 10, '2026-06-06 14:08:01', 'Invited', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`, `created_at`) VALUES
(1, 'Admin', 'System Administrator', '2026-06-06 04:36:57'),
(2, 'Procurement Officer', 'Creates RFQs and Purchase Orders', '2026-06-06 04:36:57'),
(3, 'Manager', 'Approves Procurement Requests', '2026-06-06 04:36:57'),
(4, 'Vendor', 'Submits Quotations', '2026-06-06 04:36:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('Active','Inactive','Blocked') DEFAULT 'Active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `first_name`, `last_name`, `email`, `password_hash`, `phone`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin', 'User', 'admin@vendorbridge.com', '$2y$10$8e/CZTGY84vYWLiDQw1aDehWn6X/3wE7S.E.jBWggEJN0EZrjlCSq', '9999999991', 'Active', '2026-06-06 15:02:20', '2026-06-06 04:40:56', '2026-06-06 09:32:20'),
(2, 2, 'Khush', 'Chaniyara', 'procurement@vendorbridge.com', '$2y$10$QODoWynsR2i5mi7.RZmS7eztA5L/EsqiHv8qCFZYGdS0SAbnc2Lxu', '9999999992', 'Active', '2026-06-06 15:23:26', '2026-06-06 04:40:56', '2026-06-06 09:53:26'),
(3, 3, 'Manager', 'User', 'manager@vendorbridge.com', '$2y$10$Sj0QJzNmDkTebmk3lF1E6emH6JtY48VG/L7ZLOjVasUo1rut.Xj/m', '9999999993', 'Active', '2026-06-06 15:02:15', '2026-06-06 04:40:56', '2026-06-06 09:32:15'),
(4, 4, 'Vendor', 'User', 'vendor@vendorbridge.com', '$2y$10$c0ipxQ0.X8W/bmgbImNpm.Teyy9LFLunEe6uwqjfXoflb2vE5CxoG', '9999999994', 'Active', '2026-06-06 15:29:38', '2026-06-06 04:40:56', '2026-06-06 09:59:38'),
(5, 4, 'ravi', 'kumar', 'ravi@vendorbridge.com', '$2y$10$1kV9WDCggv6v.wwOS.bUI.FyYH5.9NJWYLv.AHcvuXFQEWMyWE8B.', '9998987654', 'Active', '2026-06-06 14:24:47', '2026-06-06 05:27:35', '2026-06-06 08:54:47'),
(6, 4, 'hiten', 'patel', 'hiten@vendorbridge.com', '$2y$10$FBi04ZXuYnZ839k8QAcjV.h6uaLe5kteGZKM9SMh5pf.yoNFmJNJq', '9945678345', 'Active', '2026-06-06 15:30:01', '2026-06-06 05:29:35', '2026-06-06 10:00:01'),
(7, 4, 'Shubham', 'Joshi', 'shubhampatel@gmail.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 08:14:04', '2026-06-06 08:14:39'),
(8, 4, 'Innovative', 'Tech', 'partner@innovativetech.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 08:22:47', '2026-06-06 08:22:47'),
(9, 4, 'Alpha', 'Supply', 'alpha@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:36:34', '2026-06-06 09:36:34'),
(10, 4, 'Beta', 'Logistics', 'beta@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:36:34', '2026-06-06 09:36:34'),
(11, 4, 'Gamma', 'Tech', 'gamma@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:36:34', '2026-06-06 09:36:34'),
(12, 4, 'Delta', 'Office', 'delta@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:36:34', '2026-06-06 09:36:34'),
(13, 4, 'Epsilon', 'Safety', 'epsilon@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:36:34', '2026-06-06 09:36:34'),
(14, 4, 'Zeta', 'Clean', 'zeta@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:36:34', '2026-06-06 09:36:34'),
(15, 4, 'Eta', 'Hardware', 'eta@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:36:34', '2026-06-06 09:36:34'),
(16, 4, 'Theta', 'Packers', 'theta@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:36:34', '2026-06-06 09:36:34'),
(17, 4, 'Iota', 'Global', 'iota@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:36:34', '2026-06-06 09:36:34'),
(18, 4, 'Kappa', 'Direct', 'kappa@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:36:34', '2026-06-06 09:36:34'),
(29, 4, 'Beta', 'Logistics', 'beta_new@vendor.com', '$2y$10$T81n17rL9L.yB.358U315.S6g.8yY8o0v1dK.yI6H7b4nI.P9n/9W', NULL, 'Active', NULL, '2026-06-06 09:38:27', '2026-06-06 09:38:27');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `gst_number` varchar(20) DEFAULT NULL,
  `contact_person` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `vendor_status` enum('Pending','Active','Inactive','Blacklisted') DEFAULT 'Pending',
  `registration_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `category_id`, `company_name`, `gst_number`, `contact_person`, `email`, `phone`, `address`, `city`, `state`, `country`, `vendor_status`, `registration_date`, `created_at`) VALUES
(1, 1, 'Tech Solutions Pvt Ltd', '24ABCDE1234F1Z5', 'Rajesh Patel', 'rajesh@techsolutions.com', '9876543210', 'Corporate Park', 'Ahmedabad', 'Gujarat', 'India', 'Active', '2026-06-06', '2026-06-06 04:41:29'),
(2, 1, 'Smart Systems India', '24ABCDE5678F1Z5', 'Vikas Shah', 'sales@smartsystems.com', '9876543211', 'Business Hub', 'Vadodara', 'Gujarat', 'India', 'Active', '2026-06-06', '2026-06-06 04:41:29'),
(3, 3, 'Office Essentials Co.', '24CCCCT9012C1Z3', 'Amit Shah', 'hello@officeessentials.in', '9876543222', NULL, NULL, NULL, 'India', 'Pending', '2026-06-01', '2026-06-06 06:31:07'),
(4, 1, 'Global IT Hub', '24DDDDT3456D1Z4', 'Neha Gupta', 'contact@globalithub.com', '9876543223', NULL, NULL, NULL, 'India', 'Blacklisted', '2025-11-10', '2026-06-06 06:31:07'),
(5, 4, 'CleanSweep Services', '24EEEET7890E1Z5', 'Rajesh Kumar', 'admin@cleansweep.in', '9876543224', NULL, NULL, NULL, 'India', 'Active', '2026-02-14', '2026-06-06 06:31:07'),
(6, 1, 'Venom technolabs LTD.', '27ABCDE1234F1Z5', 'vikash sharma', 'vikassharma@gmail.com', '9987478293', 'new mumbai maharastra', 'mumbai', 'maharastra', 'India', 'Active', NULL, '2026-06-06 07:11:34'),
(7, 1, 'Soham technolabs LTD.', '27KBGCR1634A1Z6', 'ram gupta', 'ramgupta@gmail.com', '9945678234', 'easy bombay new mumbai maharastra', 'mumbai', 'maharastra', 'India', 'Active', NULL, '2026-06-06 07:17:13'),
(8, 1, 'shubham technolabs LTD.', '27KBGDR1634A1Z6', 'shubham joshi', 'shubhampatel@gmail.com', '9879836458', 'easy bombay mumbai thane maharastra', 'mumbai', 'maharastra', 'India', 'Active', NULL, '2026-06-06 07:18:45'),
(9, 1, 'Innovative Tech Solutions', '24ITEC9999A1Z1', 'Rahul Verma', 'partner@innovativetech.com', '9876543210', 'Plot No 45, MIDC Industrial Area', 'Pune', 'Maharashtra', 'India', 'Active', NULL, '2026-06-06 08:22:55'),
(10, 1, 'Hiten Tech Solutions', '24HITEN1234A1Z1', 'Hiten Patel', 'hiten@vendorbridge.com', '9945678345', NULL, 'Ahmedabad', 'Gujarat', 'India', 'Active', NULL, '2026-06-06 08:33:54'),
(32, 1, 'Comp Two', 'GST002', 'Jane', 'v2@vendor.com', '9000000002', NULL, 'Surat', NULL, 'India', 'Active', NULL, '2026-06-06 09:41:52'),
(34, 1, 'Comp Four', 'GST004', 'Sara', 'v4@vendor.com', '9000000004', NULL, 'Rajkot', NULL, 'India', 'Active', NULL, '2026-06-06 09:41:52');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_categories`
--

CREATE TABLE `vendor_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_categories`
--

INSERT INTO `vendor_categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'IT Equipment', 'Computers, Laptops, Accessories', '2026-06-06 04:37:08'),
(2, 'Office Supplies', 'Office Stationery and Supplies', '2026-06-06 04:37:08'),
(3, 'Stationery & Supplies', 'Paper, Pens, Daily office needs', '2026-06-06 06:31:07'),
(4, 'Facilities & Maintenance', 'Housekeeping, Security, Repairs', '2026-06-06 06:31:07');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_ratings`
--

CREATE TABLE `vendor_ratings` (
  `rating_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `quality_score` decimal(3,2) NOT NULL,
  `delivery_score` decimal(3,2) NOT NULL,
  `price_score` decimal(3,2) NOT NULL,
  `communication_score` decimal(3,2) NOT NULL,
  `overall_rating` decimal(3,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `rated_by` int(11) NOT NULL,
  `rated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_ratings`
--

INSERT INTO `vendor_ratings` (`rating_id`, `vendor_id`, `quotation_id`, `quality_score`, `delivery_score`, `price_score`, `communication_score`, `overall_rating`, `remarks`, `rated_by`, `rated_at`) VALUES
(1, 1, 1, 4.50, 5.00, 4.80, 4.70, 4.75, 'Excellent vendor performance', 2, '2026-06-06 04:44:06'),
(2, 2, 2, 4.00, 4.00, 4.20, 4.50, 4.18, 'Good alternative vendor', 2, '2026-06-06 04:44:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_activity_user` (`user_id`);

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`approval_id`),
  ADD KEY `fk_approval_quotation` (`quotation_id`),
  ADD KEY `fk_approval_user` (`approved_by`),
  ADD KEY `fk_approval_workflow` (`workflow_id`);

--
-- Indexes for table `approval_workflow`
--
ALTER TABLE `approval_workflow`
  ADD PRIMARY KEY (`workflow_id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`attachment_id`),
  ADD KEY `fk_attachment_user` (`uploaded_by`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `fk_invoice_po` (`po_id`),
  ADD KEY `fk_invoice_vendor` (`vendor_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`invoice_item_id`),
  ADD KEY `fk_invoice_item_invoice` (`invoice_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_notification_user` (`user_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`po_id`),
  ADD UNIQUE KEY `po_number` (`po_number`),
  ADD KEY `fk_po_quotation` (`quotation_id`),
  ADD KEY `fk_po_vendor` (`vendor_id`),
  ADD KEY `fk_po_generated_by` (`generated_by`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`po_item_id`),
  ADD KEY `fk_po_item_po` (`po_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`quotation_id`),
  ADD UNIQUE KEY `quotation_number` (`quotation_number`),
  ADD KEY `fk_quotation_rfq` (`rfq_id`),
  ADD KEY `fk_quotation_vendor` (`vendor_id`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`quotation_item_id`),
  ADD KEY `fk_quotation_item_quotation` (`quotation_id`),
  ADD KEY `fk_quotation_item_rfq_item` (`rfq_item_id`);

--
-- Indexes for table `rfqs`
--
ALTER TABLE `rfqs`
  ADD PRIMARY KEY (`rfq_id`),
  ADD UNIQUE KEY `rfq_number` (`rfq_number`),
  ADD KEY `fk_rfq_created_by` (`created_by`);

--
-- Indexes for table `rfq_items`
--
ALTER TABLE `rfq_items`
  ADD PRIMARY KEY (`rfq_item_id`),
  ADD KEY `fk_rfq_item_rfq` (`rfq_id`);

--
-- Indexes for table `rfq_vendor_assignments`
--
ALTER TABLE `rfq_vendor_assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD UNIQUE KEY `rfq_id` (`rfq_id`,`vendor_id`),
  ADD KEY `fk_assignment_vendor` (`vendor_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `gst_number` (`gst_number`),
  ADD KEY `fk_vendor_category` (`category_id`);

--
-- Indexes for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `vendor_ratings`
--
ALTER TABLE `vendor_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `fk_rating_vendor` (`vendor_id`),
  ADD KEY `fk_rating_quotation` (`quotation_id`),
  ADD KEY `fk_rating_user` (`rated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `approval_workflow`
--
ALTER TABLE `approval_workflow`
  MODIFY `workflow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `po_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `po_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `quotation_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rfqs`
--
ALTER TABLE `rfqs`
  MODIFY `rfq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rfq_items`
--
ALTER TABLE `rfq_items`
  MODIFY `rfq_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rfq_vendor_assignments`
--
ALTER TABLE `rfq_vendor_assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vendor_ratings`
--
ALTER TABLE `vendor_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `fk_approval_quotation` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`quotation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_approval_user` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_approval_workflow` FOREIGN KEY (`workflow_id`) REFERENCES `approval_workflow` (`workflow_id`) ON UPDATE CASCADE;

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `fk_attachment_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoice_po` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`po_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON UPDATE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `fk_invoice_item_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `fk_po_generated_by` FOREIGN KEY (`generated_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_quotation` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`quotation_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `fk_po_item_po` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`po_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `fk_quotation_rfq` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`rfq_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quotation_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `fk_quotation_item_quotation` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`quotation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quotation_item_rfq_item` FOREIGN KEY (`rfq_item_id`) REFERENCES `rfq_items` (`rfq_item_id`) ON UPDATE CASCADE;

--
-- Constraints for table `rfqs`
--
ALTER TABLE `rfqs`
  ADD CONSTRAINT `fk_rfq_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `rfq_items`
--
ALTER TABLE `rfq_items`
  ADD CONSTRAINT `fk_rfq_item_rfq` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`rfq_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rfq_vendor_assignments`
--
ALTER TABLE `rfq_vendor_assignments`
  ADD CONSTRAINT `fk_assignment_rfq` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`rfq_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_assignment_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON UPDATE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `fk_vendor_category` FOREIGN KEY (`category_id`) REFERENCES `vendor_categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `vendor_ratings`
--
ALTER TABLE `vendor_ratings`
  ADD CONSTRAINT `fk_rating_quotation` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`quotation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rating_user` FOREIGN KEY (`rated_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rating_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
