-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2025 at 07:25 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shippex`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` enum('billing','shipping') NOT NULL DEFAULT 'shipping',
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `street_address1` varchar(150) NOT NULL,
  `street_address2` varchar(150) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `country` varchar(50) NOT NULL,
  `tax_id` varchar(30) DEFAULT NULL,
  `phone_primary` varchar(25) NOT NULL,
  `phone_secondary` varchar(25) DEFAULT NULL,
  `show_shipping_price` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_default` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `type`, `first_name`, `last_name`, `street_address1`, `street_address2`, `city`, `state`, `zip_code`, `country`, `tax_id`, `phone_primary`, `phone_secondary`, `show_shipping_price`, `created_at`, `updated_at`, `is_default`) VALUES
(1, 6, 'billing', 'Wade', 'English', '72 North Fabien Lane', 'Nisi odio aut voluptatem Eos animi dolore quisquam velit esse', 'Autem veniam officia natus voluptates dolorum ver', 'Enim quis excepteur recusandae Sit necessitatibu', '58423', 'Austria', 'Ullamco qui labore voluptatibu', '+1 (124) 826-4594', '+1 (124) 826-4594', 1, '2025-08-11 07:44:08', '2025-08-12 07:18:34', 1),
(4, 6, 'shipping', 'Kellie', 'Clevelan', '338 West White Old Parkway', 'Non dolorem quis magni enim ut a', 'Soluta qui dolorem voluptate magnam dolor rerum re', 'Excepturi omnis eum in commodi', '59382', 'Iran', 'Officiis voluptatem nihil qui', '+1 (953) 954-5742', '+1 (953) 954-5742', 1, '2025-08-11 08:04:43', '2025-08-25 00:31:45', 0),
(5, 6, 'shipping', 'asdf', 'asdf', 'street 9', '', 'NYC', 'NY', '10003', 'United States', '3123213', '+132132132', '2132132', 1, '2025-08-24 21:01:02', '2025-08-24 21:01:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'blog', 'blog', '2025-08-21 07:26:57', '2025-08-21 07:26:57'),
(2, 'technology', 'tech', '2025-08-21 07:26:57', '2025-08-21 07:26:57');

-- --------------------------------------------------------

--
-- Table structure for table `booking_status_history`
--

CREATE TABLE `booking_status_history` (
  `id` int(11) NOT NULL,
  `book_id` int(11) UNSIGNED NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `changed_by` int(11) UNSIGNED DEFAULT NULL,
  `changed_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booking_status_history`
--

INSERT INTO `booking_status_history` (`id`, `book_id`, `old_status`, `new_status`, `changed_by`, `changed_at`) VALUES
(6, 15, 'pending', 'accepted', 4, '2025-08-24 22:56:39'),
(7, 15, 'accepted', 'shipping', 4, '2025-08-24 22:57:35'),
(8, 15, 'shipping', 'shipped', 4, '2025-08-24 22:57:55'),
(9, 15, 'shipped', 'delivered', 4, '2025-08-24 22:57:59'),
(10, 17, 'pending', 'accepted', 4, '2025-08-26 10:20:10'),
(11, 16, 'pending', 'accepted', 4, '2025-08-26 10:21:57'),
(12, 17, 'accepted', 'shipped', 4, '2025-08-26 10:43:59'),
(13, 17, 'shipped', 'accepted', 4, '2025-08-26 10:44:05'),
(14, 17, 'accepted', 'shipping', 4, '2025-08-26 10:44:08'),
(15, 17, 'shipping', 'shipped', 4, '2025-08-26 10:44:12'),
(16, 17, 'shipped', 'delivered', 4, '2025-08-26 10:44:17'),
(17, 18, 'pending', 'accepted', 4, '2025-09-15 17:28:00'),
(18, 19, 'pending', 'accepted', 4, '2025-10-02 12:54:48'),
(19, 16, 'accepted', 'pending', 4, '2025-10-06 14:23:49'),
(20, 20, 'pending', 'pending', 4, '2025-10-06 14:57:17'),
(21, 20, 'pending', 'pending', 4, '2025-10-06 14:58:21'),
(22, 16, 'pending', 'accepted', 4, '2025-10-06 15:14:21'),
(23, 16, 'accepted', 'pending', 4, '2025-10-06 15:22:51'),
(24, 16, 'pending', 'accepted', 4, '2025-10-06 15:22:57'),
(25, 16, 'accepted', 'pending', 4, '2025-10-06 15:24:18'),
(26, 16, 'pending', 'accepted', 4, '2025-10-06 15:24:30'),
(27, 16, 'accepted', 'pending', 4, '2025-10-06 15:25:16'),
(28, 16, 'pending', 'accepted', 4, '2025-10-06 15:25:27'),
(29, 16, 'accepted', 'pending', 4, '2025-10-06 15:28:31'),
(30, 16, 'pending', 'accepted', 4, '2025-10-06 15:28:35'),
(31, 16, 'accepted', 'pending', 4, '2025-10-06 15:29:15'),
(32, 16, 'pending', 'accepted', 4, '2025-10-06 15:29:18'),
(33, 23, 'pending', 'accepted', 4, '2025-10-11 17:59:07');

-- --------------------------------------------------------

--
-- Table structure for table `delivered_today`
--

CREATE TABLE `delivered_today` (
  `id` int(10) UNSIGNED NOT NULL,
  `courier_logo` varchar(255) DEFAULT NULL,
  `retailer_logo` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `from_country` varchar(100) NOT NULL,
  `from_flag` varchar(255) DEFAULT NULL,
  `to_country` varchar(100) NOT NULL,
  `to_flag` varchar(255) DEFAULT NULL,
  `cost` varchar(50) NOT NULL,
  `weight` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `delivered_today`
--

INSERT INTO `delivered_today` (`id`, `courier_logo`, `retailer_logo`, `icon`, `from_country`, `from_flag`, `to_country`, `to_flag`, `cost`, `weight`, `created_at`, `updated_at`) VALUES
(1, 'uploads/delivered/1757685682_cbba571b103c03c354b7.png', 'uploads/delivered/1757685682_3b5b92bde3ec2f83bf96.webp', 'fas fa-mobile', 'United Kingdom', 'uploads/delivered/1757685682_ff3922158332e23d823f.png', 'Papua New Guinea', 'uploads/delivered/1757685682_565fa3bfb2f45de62272.png', '25$', '0.25KGs', '2025-09-12 14:01:22', '2025-09-12 14:01:22'),
(2, 'uploads/delivered/1757687016_dd9078c29392ce19717d.png', 'uploads/delivered/1757687016_96565595a1c4330e8d23.webp', 'fas fa-book', 'Germany', 'uploads/delivered/1757687016_b5c16a1af93ed8891593.webp', 'Hungary', 'uploads/delivered/1757687016_9d64ac279324f137e2d1.png', '25$', '1 KGs', '2025-09-12 14:23:36', '2025-09-12 14:23:36');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(1, 'What does Shippex do?', 'We help you shop anywhere and ship worldwide with parcel forwarding, personal shopper, consolidation, and smart shipping labels.', '2025-10-06 19:55:16', '2025-10-06 16:25:53'),
(2, 'Who is Shippex for?', 'Anyone who can buy online but can’t ship to their country, faces high shipping costs, or wants to consolidate multiple orders.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(3, 'Which countries do you serve?', 'We ship from the USA, UK, Germany, China, UAE, Oman (and more) to most countries worldwide.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(4, 'How do I start?', 'Send us your original invoice and the seller’s tracking number. If you used our Personal Shopper, we already have them.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(5, 'Do you have a shipping calculator?', 'Yes—enter origin & destination (plus weight/dimensions) to get an instant estimate. Final price is confirmed after warehouse measurement.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(6, 'What documents are required?', 'Always the original invoice and seller tracking number. No work starts without them.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(7, 'Do you work with Aramex?', 'Yes. Aramex is our receiving agent for many lanes. They notify us on arrival with photos and measurements.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(8, 'How do I address parcels to you?', 'Label: Shippex (Aflakmarket International) — SHP-[Your Order ID]. c/o Aramex Receiving Agent + warehouse address. No COD. Include invoice inside.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(9, 'What is consolidation?', 'We combine multiple parcels into one box to cut costs. We also repack to reduce volume when it’s safe.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(10, 'Do you provide arrival photos?', 'Yes—photo on arrival and after consolidation/repack (on eligible tiers).', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(11, 'How are costs calculated?', 'Based on actual or volumetric weight (whichever is higher), box size, route, courier, and surcharges (e.g., remote area, fuel).', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(12, 'Are duties/taxes included?', 'Usually not. We can estimate, but customs decides the final amount in your country.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(13, 'Which couriers do you use?', 'We compare DHL, FedEx, UPS, USPS, Royal Mail, Aramex, and reliable local partners—then suggest the best option.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(14, 'What are typical delivery times?', 'Express: 3–7 working days. Economy: 6–12+ working days, depending on route and customs.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(15, 'What items are restricted or prohibited?', 'No hazardous materials, illegal goods, lithium batteries not packed correctly, pressurized cans, flammables, or items banned by the destination’s law. Ask us if unsure.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(16, 'Can you ship perfumes, oud, and cosmetics?', 'Often yes, with the correct packaging and lane; some destinations restrict liquids or alcohol-based products. We’ll advise case by case.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(17, 'Do you offer insurance?', 'Optional. We can insure declared value for eligible shipments. Ask before dispatch.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(18, 'How long can you store my parcels?', '30 days free warehousing per parcel. Storage beyond that may incur a small fee.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(19, 'What is the deposit policy?', 'For shopper/forwarding services we take a 50–75% deposit. Balance is due after final weight & courier selection.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(20, 'What payments do you accept?', 'PayPal, bank transfer, or card (availability varies by country). Fees may apply.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(21, 'Can you return or exchange items to the seller?', 'Yes, if the seller allows returns. Return shipping and handling are charged separately.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(22, 'Do you repack to reduce cost?', 'Yes—volume reduction is part of our consolidation service where safe and allowed.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(23, 'How do I track my shipment?', 'We share the courier tracking and update you via WhatsApp/email until delivery.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(24, 'What if my parcel is damaged or lost?', 'Tell us immediately with photos. We’ll open a case with the courier; insured shipments can claim per policy terms.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(25, 'Can businesses/resellers use Shippex?', 'Absolutely. We support bulk consolidation, regular lanes, and custom invoicing after KYC.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(26, 'What languages do you support?', 'English, French, Arabic, and Persian for core communications.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(27, 'Do you help buy from non-English websites?', 'Yes—our Personal Shopper can buy from local-language sites (China, Middle East, etc.) on your behalf.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(28, 'What info do you need for a fast quote?', 'Origin, destination, weight, box size (L×W×H), item type, and invoice/tracking if already purchased.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(29, 'Any items you will refuse?', 'We must refuse prohibited/illegal items, misdeclared goods, or shipments that fail safety/compliance rules.', '2025-10-06 19:55:16', '2025-10-06 19:55:16'),
(30, 'How do I contact you?', 'WhatsApp: +968 9265 6567 | Email: support@shippex.online / sales@shippex.online', '2025-10-06 19:55:16', '2025-10-06 19:55:16');

-- --------------------------------------------------------

--
-- Table structure for table `fonts`
--

CREATE TABLE `fonts` (
  `id` int(11) NOT NULL,
  `font_name` varchar(100) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fonts`
--

INSERT INTO `fonts` (`id`, `font_name`, `is_default`) VALUES
(1, 'Adamina', 0),
(2, 'Rubik', 0),
(3, 'Chocolate Classical Sans', 0),
(4, 'Rubik', 0),
(5, 'Rubik', 0),
(6, 'Aboreto', 0),
(7, 'Poppins', 0),
(8, 'Lato', 0),
(9, 'Inter', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hero_section`
--

CREATE TABLE `hero_section` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hero_section`
--

INSERT INTO `hero_section` (`id`, `title`, `subtitle`, `description`, `button_text`, `button_link`, `background_image`, `created_at`, `updated_at`) VALUES
(1, 'We Deliver', 'International Parcel Forwarding', 'Shop in the UK, US, Germany, Japan, or Turkey then ship worldwide. Immediately after Sign Up, use your new shopping addresses to access top brands and then ship to your home in as little as 2 days.', '🚚 START SHIPPING NOW', '#', 'images/shipment.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `how_it_works`
--

CREATE TABLE `how_it_works` (
  `id` int(11) UNSIGNED NOT NULL,
  `step_number` int(2) NOT NULL,
  `subtitle` varchar(150) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `how_it_works`
--

INSERT INTO `how_it_works` (`id`, `step_number`, `subtitle`, `title`, `description`, `icon`, `created_at`, `updated_at`) VALUES
(1, 1, 'START WITH', 'WHERE', 'Register for a free Shippex account to get instant access to addresses in four key markets: the US, UK, Germany, and Japan.\r\n', 'uploads/icons/1757671162_1fe422651bb8ce7da5f3.svg', '2025-09-12 09:59:22', '2025-09-12 10:09:28'),
(2, 0, 'TELL US', 'WHAT', 'Shop anything. Upload your invoice so we can label and process your shipment accurately in line with customs regulations.\r\n\r\n', 'uploads/icons/1757671201_bc6c8d36f22c86dd6ff1.svg', '2025-09-12 10:00:01', '2025-09-12 10:00:01'),
(3, 0, 'CHOOSE', 'HOW', 'Choose courier, consolidate packages, or request custom packaging. We offer flexible options based on your shipment\'s contents.', 'uploads/icons/1757671375_15688859815a4dce521b.svg', '2025-09-12 10:00:26', '2025-09-12 10:02:55'),
(4, 4, 'Control', 'WHEN', 'Once your parcel arrives at our warehouse, you\'re in control with updates, free storage, and photos on request. Ship on your schedule.', 'uploads/icons/1757671399_ad81a3dc2dd30c621bdf.svg', '2025-09-12 10:03:19', '2025-09-12 10:03:19');

-- --------------------------------------------------------

--
-- Table structure for table `how_it_works_sections`
--

CREATE TABLE `how_it_works_sections` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `how_it_works_sections`
--

INSERT INTO `how_it_works_sections` (`id`, `title`, `description`, `image`, `button_text`, `button_link`, `created_at`, `updated_at`) VALUES
(1, 'How Parcel Forwarding Works', 'International shipping is no longer a challenge. Our seamless shipping process makes it possible to shop at websites that don’t deliver to your country by using a forwarding address.', '1757861735_3def3ba6d55af7223215.svg', 'Order Now', '#', '2025-09-14 14:43:36', '2025-09-14 21:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `how_it_works_steps`
--

CREATE TABLE `how_it_works_steps` (
  `id` int(11) UNSIGNED NOT NULL,
  `section_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bg_image` varchar(255) DEFAULT NULL,
  `order` int(3) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `how_it_works_steps`
--

INSERT INTO `how_it_works_steps` (`id`, `section_id`, `title`, `description`, `image`, `bg_image`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Create a Shippex Account', 'Complete a quick, free registration process in order to get a free shipping address from Shippex. Our flexible approach lets you use multiple addresses in different destinations with a single account without any fees. Manage all your international shipments easily in just one session.\r\n\r\n', '1757862689_bd84892bd1e7ede87fbc.png', NULL, 0, '2025-09-14 15:11:29', '2025-09-14 15:11:29'),
(3, 1, 'Make a Purchase', 'After you’ve received your forwarding address, you will use it as your delivery address when completing orders from your favorite online retailers. This way you will be able to buy goods even if the shops don’t deliver to your destination. Just make sure the products can be legally imported into your country.\r\n\r\n', '1757863952_8f1c82765dc4b2eca424.svg', NULL, 0, '2025-09-14 15:32:32', '2025-09-15 08:14:45'),
(4, 1, 'Request Your Shipment', 'When we receive your parcel, we’ll make sure it can be safely and legally shipped to your address. You’ll receive an email notifying you that you can request your shipment. Simply log in to your account, select courier and payment options, use any discounts codes, or combine packages.', '1757923364_44bc03b76e5ca01050f1.svg', NULL, 0, '2025-09-15 07:14:14', '2025-09-15 08:02:44'),
(5, 1, 'Receive Your Parcel', 'Delivery times can vary depending on the service you’ve chosen as well as local customs processes. You can see the estimated transit time for each service on our pricing page. Contact our Customer Experience team if you have any questions about your shipment – they’re always ready to help!\r\n\r\nDelivery times can vary depending on the service you’ve chosen as well as local customs processes. You can see the estimated transit time for each service on our pricing page. Contact our Customer Experience team if you have any questions about your shipment – they’re always ready to help!', '1757923378_8dd1d5e97a5213cc4c20.svg', NULL, 0, '2025-09-15 07:15:06', '2025-09-15 08:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `flag_image` varchar(255) DEFAULT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL,
  `location_info` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` enum('active','coming_soon') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `flag_image`, `thumbnail_image`, `location_info`, `link`, `status`, `created_at`, `updated_at`) VALUES
(1, 'United States', '1757673164_accecc3d84f1580cb575.webp', '1757673164_b4ea442db2e400c3f2d6.webp', 'No GST', '#', 'active', '2025-09-12 10:32:44', '2025-09-12 10:32:44'),
(2, 'United Kingdom', '1757680676_417bbbad8db576302ebf.webp', '1757680676_1c51d7521975dd05480f.webp', '', '#', 'active', '2025-09-12 12:37:56', '2025-09-12 12:37:56'),
(3, 'Germany', '1757680824_bcb63d4ee50e35a848d9.webp', '1757680824_3bf8e60b12ffdb30b7ac.webp', 'European Union', '#', 'active', '2025-09-12 12:40:24', '2025-09-12 12:41:27'),
(4, 'Guerncy', '1757680935_1042405c713ccb663146.webp', '1757680935_c3b957ee4825a2888780.webp', '', '#', 'active', '2025-09-12 12:42:15', '2025-09-12 12:42:15'),
(5, 'Japan', '1757680982_e26bb44e5cf1961bddb6.webp', '1757680982_6a4c429942d44e8bec65.webp', '', '#', 'active', '2025-09-12 12:43:02', '2025-09-12 12:43:02'),
(6, 'Comming Soon', '1757683723_015ebd9d2b41f57c782c.png', '1757681267_d44ea44ab91a7ccf3550.png', '', '', 'coming_soon', '2025-09-12 12:44:58', '2025-09-12 13:28:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-07-16-001107', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1752624884, 1),
(2, '2025-07-16-003131', 'App\\Database\\Migrations\\CreatePasswordResetsTable', 'default', 'App', 1752625935, 2),
(3, '2025-08-04-102545', 'App\\Database\\Migrations\\VirtualAddresses', 'default', 'App', 1754303260, 3),
(4, '2025-08-04-142316', 'App\\Database\\Migrations\\CreateFontsTable', 'default', 'App', 1754328468, 4),
(5, '2025-08-10-101319', 'App\\Database\\Migrations\\CreateShopperRequests', 'default', 'App', 1754821159, 5),
(6, '2025-08-10-101336', 'App\\Database\\Migrations\\CreateShopperItems', 'default', 'App', 1754821179, 6),
(7, '2025-08-20-122420', 'App\\Database\\Migrations\\CreateShippingBookingsTable', 'default', 'App', 1755692716, 7),
(9, '2025-08-20-000001', 'Modules\\Blog\\Database\\Migrations\\CreateBlogPosts', 'default', 'Modules\\Blog', 1755738253, 8),
(10, '2025-09-11-072857', 'App\\Database\\Migrations\\CreateHeroSection', 'default', 'App', 1757581704, 9),
(11, '2025-09-12-090120', 'App\\Database\\Migrations\\CreateHowItWorks', 'default', 'App', 1757668486, 10),
(12, '2025-09-12-101142', 'App\\Database\\Migrations\\CreateLocationsTable', 'default', 'App', 1757671930, 11),
(13, '2025-09-12-130138', 'App\\Database\\Migrations\\CreateDeliveredToday', 'default', 'App', 1757682219, 12),
(14, '2025-09-12-144241', 'App\\Database\\Migrations\\CreatePromoCardsTable', 'default', 'App', 1757688217, 13),
(15, '2025-09-12-145900', 'App\\Database\\Migrations\\CreatePromoCardsTable', 'default', 'App', 1757689159, 14),
(16, '2025-09-13-081735', 'App\\Database\\Migrations\\AddBackgroundToPromoCards', 'default', 'App', 1757751508, 15),
(17, '2025-09-14-135234', 'App\\Database\\Migrations\\CreateHowItWorksSections', 'default', 'App', 1757858017, 16),
(18, '2025-09-14-135301', 'App\\Database\\Migrations\\CreateHowItWorksSteps', 'default', 'App', 1757858017, 16),
(19, '2025-09-14-135323', 'App\\Database\\Migrations\\CreateWhyChoose', 'default', 'App', 1757858017, 16),
(20, '2025-10-12-103514', 'App\\Database\\Migrations\\CreateWarehousesTable', 'default', 'App', 1760265342, 17);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`) VALUES
(1, 'Test Page', '<h1>Hello VvvebJs!</h1>');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'jawad@mail.com', '80d138a7543968831fe83c9e679d1ab1590f85adb36f4dda247f91d772cd94e8', '2025-08-15 22:54:20'),
(2, 'codewithja@gmail.com', '84c223fef2c223a00f8d239076f2082634d1fd6d114b3b87a8e0b8d37922635d', '2025-10-12 17:13:42'),
(3, 'codewithja@gmail.com', '5843f495bc7f1f357c018cdcac64dac09253149ff72aa7390d7807b5c5a519ee', '2025-10-12 17:15:31'),
(4, 'codewithja@gmail.com', 'b254c214e12a66c901d972402b532688ad494159f54ebf37ae5cc7a04be08c04', '2025-10-12 17:21:26'),
(5, 'codewithja@gmail.com', 'ce27b9ac416e32d50694e7b47f4eb4c43f537e691024c0f9aa0f42a36603449e', '2025-10-12 17:22:05'),
(6, 'codewithja@gmail.com', 'f9d97c5686dac6419383f5e56e1759fda146d1a71bad458d6281714bf423a150', '2025-10-12 17:22:30'),
(7, 'codewithja@gmail.com', '0901e5a132d074d60edc1465bf33cfcd813052f433b1d567b6f461807186edb0', '2025-10-12 17:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `author_id` int(11) UNSIGNED DEFAULT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `thumbnail`, `slug`, `excerpt`, `content`, `status`, `author_id`, `category_id`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Occaecat tempora qua', '', 'occaecat-tempora-qua', NULL, '<h2>macc for sale</h2><p>mac for sale</p><figure class=\"image\"><img style=\"aspect-ratio:5184/3456;\" src=\"http://localhost:8080/uploads/blog/1755738310_e2cfbc3fa5294696e57e.jpg\" width=\"5184\" height=\"3456\"></figure>', 'published', NULL, 2, NULL, '2025-08-21 01:08:44', '2025-08-21 01:12:52', '2025-08-21 01:12:52'),
(2, 'blog', '1755738799_a1719063006f04e80bf3.jpg', 'blog', NULL, '<p>blog</p><figure class=\"image\"><img style=\"aspect-ratio:2400/1350;\" src=\"http://localhost:8080/uploads/blog/1755738796_7b650be29a5fc0a4f0f2.jpg\" width=\"2400\" height=\"1350\"></figure>', 'published', NULL, 1, NULL, '2025-08-21 01:13:19', '2025-08-21 01:13:19', NULL),
(3, 'blogs update here', '1755742947_3d287ecb088e03abc316.jpg', 'blogs-update-here', NULL, '<h2>our new set up</h2><p>our new set up is working fine</p><p>&nbsp;</p>', 'published', NULL, 1, '2025-08-21 02:22:27', '2025-08-21 02:22:27', '2025-08-21 02:22:27', NULL),
(5, 'bloggg', '1755752060_f004f28395e3400d2c6c.jpg', 'bloggg', NULL, '<p>blog is blog</p>', 'published', 4, 1, '2025-08-21 04:54:20', '2025-08-21 04:54:20', '2025-08-22 10:33:27', NULL),
(6, 'Ut quos sunt in beat', '1755752100_ad24bc974b83c2045928.jpg', 'blog-about-is', NULL, '<h3>blog about tourism</h3><p>here is the best one</p><p>&nbsp;</p>', 'published', 4, 1, NULL, '2025-08-21 04:55:00', '2025-08-21 06:38:15', NULL),
(7, 'Magna aut sed laboru', '1755858238_5ef93c5222137e468aba.jpg', 'blog-is-good-s', NULL, '<p>Welcome to our blog</p><p>out blog is about everything you need</p>', 'published', 4, 2, NULL, '2025-08-22 10:23:59', '2025-08-22 10:30:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promo_cards`
--

CREATE TABLE `promo_cards` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `button_text` varchar(100) NOT NULL,
  `button_url` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `background` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `promo_cards`
--

INSERT INTO `promo_cards` (`id`, `title`, `description`, `button_text`, `button_url`, `image`, `background`, `created_at`, `updated_at`) VALUES
(1, 'Free Return to Sender', 'Shop with confidence, because the returns are on us! You heard it right, if there is any reason why you want to return your item to the sender, we won’t charge a fee for that. This means ZERO risk for you! ', 'Free Return', 'http://localhost:8080/admin/cms/promo-cards/create', '1757844452_26792d5f759fca2b0bb3.webp', '1757845094_757365a5370614b16a3a.webp', '2025-09-14 10:07:33', '2025-09-14 10:18:14'),
(2, 'Price Match Guarantee', 'Quality usually comes at a higher price, but not always and forward2me is the perfect example! Having a top-rated service at a competitive price might seem surprising but IT\'S A FACT! \r\n\r\n', 'Match Prices', 'https://shippex.online/shipping/rates', '1760288707_151362c243f0423f8b18.webp', '1760288707_119a2015b9884508cab1.webp', '2025-10-12 17:05:07', '2025-10-12 17:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_bookings`
--

CREATE TABLE `shipping_bookings` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `origin_line_1` varchar(255) NOT NULL,
  `origin_city` varchar(100) NOT NULL,
  `origin_state` varchar(100) DEFAULT NULL,
  `origin_postal` varchar(20) DEFAULT NULL,
  `origin_country` varchar(5) NOT NULL,
  `dest_line_1` varchar(255) NOT NULL,
  `dest_city` varchar(100) NOT NULL,
  `dest_state` varchar(100) DEFAULT NULL,
  `dest_postal` varchar(20) DEFAULT NULL,
  `dest_country` varchar(5) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `category` varchar(64) DEFAULT NULL,
  `length` decimal(10,2) NOT NULL,
  `width` decimal(10,2) NOT NULL,
  `height` decimal(10,2) NOT NULL,
  `courier_name` varchar(255) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `delivery_time` varchar(100) NOT NULL,
  `status` enum('pending','canceled','accepted','shipping','shipped','delivered') NOT NULL DEFAULT 'pending',
  `currency` varchar(10) NOT NULL,
  `purchase_invoice` varchar(255) DEFAULT NULL,
  `payment_status` enum('paid','unpaid','canceled') DEFAULT NULL,
  `total_charge` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `payment_info` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shipping_bookings`
--

INSERT INTO `shipping_bookings` (`id`, `user_id`, `origin_line_1`, `origin_city`, `origin_state`, `origin_postal`, `origin_country`, `dest_line_1`, `dest_city`, `dest_state`, `dest_postal`, `dest_country`, `weight`, `category`, `length`, `width`, `height`, `courier_name`, `service_name`, `delivery_time`, `status`, `currency`, `purchase_invoice`, `payment_status`, `total_charge`, `description`, `created_at`, `updated_at`, `payment_info`) VALUES
(15, 4, 'Street 9', 'NYC', 'NY', '10003', 'US', 'North london', 'NYC', 'NY', '07008', 'US', '1.00', 'home_appliances', '20.00', '12.00', '21.00', 'USPS', 'USPS - Ground Advantage', '2 - 5 days', 'delivered', 'AED', NULL, NULL, '22.15', 'USPS - Ground Advantage (2-5 working days) No additional taxes to be paid at delivery', '2025-08-24 18:25:36', '2025-08-24 22:57:59', ''),
(16, 6, 'street 9', 'NYC', 'NY', '10003', 'US', 'Streee 11', 'NYC', 'NY', '07008', 'US', '1.00', 'audio_video', '12.00', '21.00', '20.00', 'USPS', 'USPS - Priority Mail', '1 - 3 days', 'accepted', 'AED', '1759763640_7f5145a2dfaafbe7db32.jpg', NULL, '25.60', 'USPS - Priority Mail (1-3 working days) No additional taxes to be paid at delivery', '2025-08-24 23:52:40', '2025-10-06 15:29:18', ''),
(17, 4, 'street', 'NYC', 'NY', '10003', 'US', 'Street 9', 'Paris', 'Bourg-la-Reine', '92340', 'PK', '10.00', 'watches', '12.00', '5.00', '2.00', 'UPS', 'UPS Worldwide Expedited®', '7 - 7 days', 'delivered', 'AED', NULL, NULL, '609.69', 'UPS Worldwide Expedited® (7 working days) Estimated  118.33 tax & duty due on delivery (Tax handling fees may apply)', '2025-08-26 10:18:48', '2025-08-26 10:44:17', ''),
(18, 6, 'Streetn9', 'NY', 'NY', '10003', 'US', 'Street 9', 'New York', 'NY', '07008', 'US', '1.00', 'toys', '12.00', '20.00', '21.00', 'UPS', 'UPS® Ground Saver', '1 - 3 days', 'shipping', 'AED', '1757952844_fc104ed7da76b4382583.webp', 'paid', '23.17', 'UPS® Ground Saver (1-3 working days) No additional taxes to be paid at delivery', '2025-09-15 15:52:42', '2025-10-06 13:15:15', '{\"id\":\"0NY70248VK675463J\",\"intent\":\"CAPTURE\",\"status\":\"COMPLETED\",\"payment_source\":{\"paypal\":{\"email_address\":\"codewithja@gmail.com\",\"account_id\":\"ZAN4KREDN2GE2\",\"account_status\":\"UNVERIFIED\",\"name\":{\"given_name\":\"jawad\",\"surname\":\"alizada\"},\"address\":{\"country_code\":\"US\"}}},\"purchase_units\":[{\"reference_id\":\"default\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"23.17\"},\"payee\":{\"email_address\":\"sb-5jor244198538@business.example.com\",\"merchant_id\":\"WQ9TKJCTBVXJC\"},\"soft_descriptor\":\"PAYPAL *TEST STORE\",\"shipping\":{\"name\":{\"full_name\":\"jawad alizada\"},\"address\":{\"address_line_1\":\"street 9\",\"address_line_2\":\"bldg\",\"admin_area_2\":\"NYC\",\"admin_area_1\":\"NY\",\"postal_code\":\"10003\",\"country_code\":\"US\"}},\"payments\":{\"captures\":[{\"id\":\"8W804871CU807841F\",\"status\":\"COMPLETED\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"23.17\"},\"final_capture\":true,\"seller_protection\":{\"status\":\"ELIGIBLE\",\"dispute_categories\":[\"ITEM_NOT_RECEIVED\",\"UNAUTHORIZED_TRANSACTION\"]},\"seller_receivable_breakdown\":{\"gross_amount\":{\"currency_code\":\"USD\",\"value\":\"23.17\"},\"paypal_fee\":{\"currency_code\":\"USD\",\"value\":\"1.30\"},\"net_amount\":{\"currency_code\":\"USD\",\"value\":\"21.87\"}},\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/8W804871CU807841F\",\"rel\":\"self\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/8W804871CU807841F\\/refund\",\"rel\":\"refund\",\"method\":\"POST\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/0NY70248VK675463J\",\"rel\":\"up\",\"method\":\"GET\"}],\"create_time\":\"2025-10-06T13:15:14Z\",\"update_time\":\"2025-10-06T13:15:14Z\"}]}}],\"payer\":{\"name\":{\"given_name\":\"jawad\",\"surname\":\"alizada\"},\"email_address\":\"codewithja@gmail.com\",\"payer_id\":\"ZAN4KREDN2GE2\",\"address\":{\"country_code\":\"US\"}},\"create_time\":\"2025-10-06T13:14:09Z\",\"update_time\":\"2025-10-06T13:15:14Z\",\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/0NY70248VK675463J\",\"rel\":\"self\",\"method\":\"GET\"}]}'),
(19, 4, 'Street ', 'NYC', 'NY', '10003', 'US', 'Street 9', 'NYC', 'NY', '07008', 'US', '2.00', 'cameras', '20.00', '20.00', '10.00', 'UPS', 'UPS® Ground Saver', '1 - 3 days', 'accepted', 'AED', NULL, NULL, '23.21', 'UPS® Ground Saver (1-3 working days) No additional taxes to be paid at delivery', '2025-10-02 12:54:02', '2025-10-02 12:54:48', ''),
(20, 4, 'Street 9', 'NYC', 'NY', '10003', 'US', 'Stree 9', 'NYC', 'NY', '07008', 'US', '1.00', 'accessory_no_battery', '20.00', '20.00', '20.00', 'FedEx', 'FedEx Ground® Economy', '4 - 4 days', 'pending', 'AED', NULL, NULL, '26.04', 'FedEx Ground® Economy (4 working days) No additional taxes to be paid at delivery', '2025-10-06 10:57:36', '2025-10-06 14:58:21', ''),
(21, 4, 'street ', 'NYC', 'NY', '10003', 'US', 'Street 9', 'NYC', 'NY', '07008', 'US', '1.00', 'computers_laptops', '12.00', '10.00', '21.00', 'USPS', 'USPS - Ground Advantage Signature', '2 - 5 days', 'pending', 'AED', NULL, NULL, '36.18', 'USPS - Ground Advantage Signature (2-5 working days) No additional taxes to be paid at delivery', '2025-10-06 18:14:47', '2025-10-06 18:14:47', ''),
(22, 4, 'street', 'NYC', 'NY', '10003', 'US', 'Stree ', 'NYC', 'NY', '07008', 'US', '2.00', 'computers_laptops', '12.00', '20.00', '21.00', 'USPS', 'USPS - Priority Mail', '1 - 3 days', 'pending', 'AED', NULL, NULL, '26.70', 'USPS - Priority Mail (1-3 working days) No additional taxes to be paid at delivery', '2025-10-11 17:50:00', '2025-10-11 17:50:00', ''),
(23, 6, 'street ', 'NYC', 'NY', '10003', 'US', 'Street', 'NYC', 'NY', '07008', 'US', '2.00', 'computers_laptops', '20.00', '20.00', '20.00', 'USPS', 'USPS - Priority Mail Signature', '1 - 3 days', 'shipping', 'AED', '1760205532_05a5ecd26d3ee09b1bf6.jpg', 'paid', '41.50', 'USPS - Priority Mail Signature (1-3 working days) No additional taxes to be paid at delivery', '2025-10-11 17:56:17', '2025-10-11 18:00:27', '{\"id\":\"26K660229P687794D\",\"intent\":\"CAPTURE\",\"status\":\"COMPLETED\",\"payment_source\":{\"paypal\":{\"email_address\":\"jawadalizada1@gmail.com\",\"account_id\":\"F63Y7FCAJBAH6\",\"account_status\":\"UNVERIFIED\",\"name\":{\"given_name\":\"Jawad\",\"surname\":\"Alizada\"},\"address\":{\"country_code\":\"US\"}}},\"purchase_units\":[{\"reference_id\":\"default\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"41.50\"},\"payee\":{\"email_address\":\"sb-5jor244198538@business.example.com\",\"merchant_id\":\"WQ9TKJCTBVXJC\"},\"soft_descriptor\":\"PAYPAL *TEST STORE\",\"shipping\":{\"name\":{\"full_name\":\"Jawad Alizada\"},\"address\":{\"address_line_1\":\"Street 9\",\"address_line_2\":\"bldg\",\"admin_area_2\":\"NYC\",\"admin_area_1\":\"NY\",\"postal_code\":\"10003\",\"country_code\":\"US\"}},\"payments\":{\"captures\":[{\"id\":\"4LM53108K6364850F\",\"status\":\"COMPLETED\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"41.50\"},\"final_capture\":true,\"seller_protection\":{\"status\":\"ELIGIBLE\",\"dispute_categories\":[\"ITEM_NOT_RECEIVED\",\"UNAUTHORIZED_TRANSACTION\"]},\"seller_receivable_breakdown\":{\"gross_amount\":{\"currency_code\":\"USD\",\"value\":\"41.50\"},\"paypal_fee\":{\"currency_code\":\"USD\",\"value\":\"1.94\"},\"net_amount\":{\"currency_code\":\"USD\",\"value\":\"39.56\"}},\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/4LM53108K6364850F\",\"rel\":\"self\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/4LM53108K6364850F\\/refund\",\"rel\":\"refund\",\"method\":\"POST\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/26K660229P687794D\",\"rel\":\"up\",\"method\":\"GET\"}],\"create_time\":\"2025-10-11T18:00:27Z\",\"update_time\":\"2025-10-11T18:00:27Z\"}]}}],\"payer\":{\"name\":{\"given_name\":\"Jawad\",\"surname\":\"Alizada\"},\"email_address\":\"jawadalizada1@gmail.com\",\"payer_id\":\"F63Y7FCAJBAH6\",\"address\":{\"country_code\":\"US\"}},\"create_time\":\"2025-10-11T17:59:33Z\",\"update_time\":\"2025-10-11T18:00:28Z\",\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/26K660229P687794D\",\"rel\":\"self\",\"method\":\"GET\"}]}');

-- --------------------------------------------------------

--
-- Table structure for table `shopper_items`
--

CREATE TABLE `shopper_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `request_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(1024) DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shopper_items`
--

INSERT INTO `shopper_items` (`id`, `request_id`, `name`, `url`, `size`, `color`, `instructions`, `quantity`, `created_at`) VALUES
(2, 2, 'Jolie Ward', 'https://www.qyk.com.au', 'Neque repellendus C', 'Eaque eaque fugit n', 'Quas ex eos et possi', 442, NULL),
(4, 3, 'Maxine Morse', 'https://www.befy.net', 'Praesentium nostrud ', 'Expedita perspiciati', 'Do nesciunt omnis o', 256, NULL),
(5, 4, 'pen', 'https://www.pen.com', 'small', 'red', 'please gift wrap', 1, NULL),
(7, 1, 'Joseph Guzman', 'https://www.loharykywepup.me.uk', 'Id sint ex occaecat ', 'Est sit commodi ven', 'Qui obcaecati quis q', 2, NULL),
(8, 5, 'Macbook Pro', 'https://www.apple.com', '12', 'red', 'Please gift wrap', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shopper_requests`
--

CREATE TABLE `shopper_requests` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `status` enum('saved','pending','wait_for_payment','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) DEFAULT NULL,
  `payment_info` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `use_another_retailer` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_description` varchar(255) DEFAULT NULL,
  `delivery_notes` text DEFAULT NULL,
  `is_saved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shopper_requests`
--

INSERT INTO `shopper_requests` (`id`, `user_id`, `status`, `payment_status`, `payment_info`, `price`, `use_another_retailer`, `delivery_description`, `delivery_notes`, `is_saved`, `created_at`, `updated_at`) VALUES
(1, 6, 'saved', NULL, NULL, NULL, 0, 'Aspernatur Nam nostr', 'Facilis rerum perfer', 1, '2025-08-10 10:25:16', '2025-08-26 10:29:37'),
(2, 6, 'wait_for_payment', NULL, NULL, 200, 1, 'Aut rerum aut non ex', 'Modi qui ex nobis ve', 0, '2025-08-11 09:09:27', '2025-10-06 13:19:17'),
(3, 6, 'pending', NULL, NULL, NULL, 1, 'Quod dolorem aut rep', 'Excepturi architecto', 0, '2025-08-11 09:27:34', '2025-08-11 09:27:34'),
(4, 6, 'processing', 'paid', '{\"id\":\"3RX37202MW237084A\",\"intent\":\"CAPTURE\",\"status\":\"COMPLETED\",\"payment_source\":{\"paypal\":{\"email_address\":\"mail@gmail.com\",\"account_id\":\"CQWTNAWBGSMMW\",\"account_status\":\"UNVERIFIED\",\"name\":{\"given_name\":\"Jawad\",\"surname\":\"Alizada\"},\"address\":{\"country_code\":\"US\"}}},\"purchase_units\":[{\"reference_id\":\"default\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"302.00\"},\"payee\":{\"email_address\":\"sb-5jor244198538@business.example.com\",\"merchant_id\":\"WQ9TKJCTBVXJC\"},\"soft_descriptor\":\"PAYPAL *TEST STORE\",\"shipping\":{\"name\":{\"full_name\":\"Jawad Alizada\"},\"address\":{\"address_line_1\":\"stree 9\",\"address_line_2\":\"bldg\",\"admin_area_2\":\"NYC\",\"admin_area_1\":\"NY\",\"postal_code\":\"10003\",\"country_code\":\"US\"}},\"payments\":{\"captures\":[{\"id\":\"5HG29991A4375142Y\",\"status\":\"COMPLETED\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"302.00\"},\"final_capture\":true,\"seller_protection\":{\"status\":\"ELIGIBLE\",\"dispute_categories\":[\"ITEM_NOT_RECEIVED\",\"UNAUTHORIZED_TRANSACTION\"]},\"seller_receivable_breakdown\":{\"gross_amount\":{\"currency_code\":\"USD\",\"value\":\"302.00\"},\"paypal_fee\":{\"currency_code\":\"USD\",\"value\":\"11.03\"},\"net_amount\":{\"currency_code\":\"USD\",\"value\":\"290.97\"}},\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/5HG29991A4375142Y\",\"rel\":\"self\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/5HG29991A4375142Y\\/refund\",\"rel\":\"refund\",\"method\":\"POST\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/3RX37202MW237084A\",\"rel\":\"up\",\"method\":\"GET\"}],\"create_time\":\"2025-10-06T14:09:39Z\",\"update_time\":\"2025-10-06T14:09:39Z\"}]}}],\"payer\":{\"name\":{\"given_name\":\"Jawad\",\"surname\":\"Alizada\"},\"email_address\":\"mail@gmail.com\",\"payer_id\":\"CQWTNAWBGSMMW\",\"address\":{\"country_code\":\"US\"}},\"create_time\":\"2025-10-06T14:08:42Z\",\"update_time\":\"2025-10-06T14:09:39Z\",\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/3RX37202MW237084A\",\"rel\":\"self\",\"method\":\"GET\"}]}', 302, 0, 'first class', 'send everything together', 0, '2025-08-24 23:08:31', '2025-10-06 14:09:39'),
(5, 6, 'wait_for_payment', NULL, NULL, 900, 0, 'First class', 'Send everything together', 0, '2025-10-11 18:03:04', '2025-10-11 18:03:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `lastname`, `email`, `password`, `role`, `created_at`) VALUES
(4, 'alizada11', 'Jawad', 'Alizada', 'jawad@mail.com', '$2y$10$5PNxcTmHjpyxSBBfcOIroeVRrAVfeU1i1rvEcSxEH7R5CUMKLoEbq', 'admin', '2025-08-09 20:14:23'),
(6, 'alizada', 'jawad', 'alizada', 'codewithja@gmail.com', '$2y$10$kubmufR2g7S464phhtXHcOY823uNWCQJGzb/Of6x7/im.2/WMyO4e', 'customer', '2025-08-10 08:50:02');

-- --------------------------------------------------------

--
-- Table structure for table `virtual_addresses`
--

CREATE TABLE `virtual_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(16) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address_line` text NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `virtual_addresses`
--

INSERT INTO `virtual_addresses` (`id`, `user_id`, `code`, `country`, `city`, `address_line`, `postal_code`, `phone`, `is_default`, `created_at`, `updated_at`) VALUES
(2, 4, 'JP', 'Japan', 'Shibayama-machi, Sambu-gun', 'Address Line 1: Iwayama Imorido 114-7 IACT-S  \r\nAddress Line 2: Suite #BXA609  \r\nPrefecture: Chiba  \r\n', '289-1608', '000000000000', 1, '2025-08-12 10:59:48', '2025-10-02 12:17:27'),
(3, 4, 'DE', 'Germany', 'Schwedt  ', 'Address Line 1: Schwetder Allee 23  \r\nAddress Line 2: Forward2Me 422245  \r\nState/County: Brandenburg  \r\n', '16303', '+48746601138', 1, '2025-08-12 11:16:32', '2025-10-02 12:16:06'),
(4, 4, 'UK', 'United Kingdom', 'Preston  ', 'Address Line 1: 422245 York House  \r\nAddress Line 2: Green Lane West  \r\nCounty: Lancashire  \r\n', 'PR3 1NJ  ', '+441995606060', 1, '2025-08-15 10:02:24', '2025-10-02 12:15:05'),
(5, 4, 'US', 'United States', 'Delaware (DE)  ', 'Address Line 1: 807B Kiamensi Rd  \r\nAddress Line 2: C-422245  \r\n', '19804  ', '+12083286027', 1, '2025-09-15 15:48:30', '2025-10-02 12:14:00'),
(6, 4, 'AT', 'Austria', 'Rastenfeld  ', 'Address Line 1: Rastenfeld 151  \r\nAddress Line 2: Suite #BXA609  \r\n\r\n', '3532  ', '0000000000', 1, '2025-10-02 12:18:23', '2025-10-02 12:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(10) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `hero_image` varchar(255) NOT NULL,
  `hero_title` varchar(255) NOT NULL,
  `hero_description_1` text DEFAULT NULL,
  `hero_description_2` text DEFAULT NULL,
  `hero_cta_text` varchar(100) NOT NULL DEFAULT 'START SAVING',
  `hero_cta_link` varchar(255) DEFAULT NULL,
  `brands_title` varchar(255) DEFAULT NULL,
  `brands_text` text DEFAULT NULL,
  `brands_image` varchar(255) DEFAULT NULL,
  `shipping_text` varchar(255) DEFAULT NULL,
  `payment_text` varchar(255) DEFAULT NULL,
  `bottom_title` varchar(255) DEFAULT NULL,
  `bottom_paragraph_1` text DEFAULT NULL,
  `bottom_paragraph_2` text DEFAULT NULL,
  `bottom_cta_text` varchar(100) DEFAULT NULL,
  `bottom_cta_link` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `country_code`, `country_name`, `banner_image`, `hero_image`, `hero_title`, `hero_description_1`, `hero_description_2`, `hero_cta_text`, `hero_cta_link`, `brands_title`, `brands_text`, `brands_image`, `shipping_text`, `payment_text`, `bottom_title`, `bottom_paragraph_1`, `bottom_paragraph_2`, `bottom_cta_text`, `bottom_cta_link`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'AT', 'Austria', '1760276658_550b0bae96ee27007af1.webp', '1760276658_2de5781ccce3d0b20993.webp', 'SHOP ONLINE IN AUSTRIA AND SHIP WORLDWIDE', 'Everything Austria has to offer is ready and waiting. Unlock Austrian parcel forwarding with forward2me and shop online as if you lived there. Austria is home to a mix of traditional and modern brands, offering high-quality products in fashion, electronics, and lifestyle goods.\r\n', 'We are one of the leading international parcel forwarding services, with years of experience shipping parcels worldwide. With our Austrian forwarding address, you can shop from trusted retailers and popular marketplaces across Europe — from everyday essentials to premium items — all delivered safely to your doorstep.', 'Start Shippin', '/shipping/rates', 'Austrian Brands We Reship', 'Your Austrian forwarding address lets you shop from some of Europe’s most respected and reliable brands. Unlock Amazon.at international shipping or shop from Zalando Austria, MediaMarkt, Conrad, Intersport, and Billa Online.\r\n<p>\r\nOur package forwarding service brings the best Austrian brands to your fingertips. Buy from trending beauty and lifestyle brands like Rosenrot, Logona, or discover unique Austrian artisans on Etsy Austria and eBay.at who normally don’t ship internationally.\r\n</p><p>\r\nWith Austrian parcel forwarding, you can also shop for the latest tech from Conrad, Electronic4You, or explore Austrian fashion from LIEBESKIND, Wolford, and Swarovski.\r\n</p><p>\r\nWhatever you’re after, as an international shopper, you can now access Austrian online stores and order to the UK, Europe, Asia, Australia, and beyond — quickly, safely, and reliably.</p>', '1760276658_615ecd71e4ceaa06fe38.webp', 'Numquam iste quae fu', 'Nihil consequat Ill', 'Why Use Austrian Parcel Forwarding', 'Our Austrian parcel forwarding service lets you access a wide variety of products from Austria that you may not find locally. By shopping online from Austria, you can explore both popular retailers and local specialty brands that offer premium quality and unique selections. Whether you’re looking for cutting-edge electronics, stylish clothing, or traditional Austrian goods, we make it simple for international shoppers.', 'Get a parcel forwarding address in Austria and shop at Amazon.at, Ebay.at, or directly from Austrian brands. Receive toys and games from Ravensburger Austria, books from Thalia, or the latest fashion from Görtz Austria and Wolford, all shipped to your country safely and efficiently.', 'Start Shipping Now', '/shipping/rates', 1, '2025-10-12 11:55:48', '2025-10-12 13:44:18'),
(2, 'US', 'United States', '1760271475_8de66e11a31f5573aa3a.webp', '1760271475_ebd8b140d085bba39c16.webp', 'SHOP ONLINE IN USA AND SHIP WORLDWIDE', 'Everything the US has to offer is ready and waiting. Unlock US parcel forwarding with forward2me and you can shop like you live there. The US has one of the largest markets in the world, with an incredible variety of products on offer from some of the biggest brands on the planet.', 'We are one of the leading international parcel forwarding services with years of experience shipping parcels. We offer parcel forwarding services that ship products from famous brands all over the world from the UK, EU, Turkey, and Japan.', 'START SAVING', '/services', 'US Brands We Reship', 'Your US forwarding address lets you shop at the biggest brands in the world. Unlock US Amazon international shipping or shop from Nike US, Adidas, Zappos, Target, Best Buy and Walmart.\r\n\r\nWe offer a package forwarding service that puts the best brands at your fingertips. You can buy from the trending brands in beauty, like Rare Beauty or Glossier or buy from tiny independent sellers on Etsy and eBay who wouldn’t normally offer international shipping. Package forwarding services let you buy the latest tech from brands like Apple and GameStop or stock up on US fashion from Hollister, Vans, or Nordstrom Rack.\r\n\r\nWhatever you’re after, as an international shopper, you can now access US online shops and order all around the world, to UK, Europe, Asia, Australia, and more.', '1760271475_db08dcbdc23d892ec2d7.webp', '', '', 'Why Use European Parcel Forwarding', 'Our Europe parcel forwarding service lets you get hold of all kinds of items you never thought you could. If you buy goods online from Europe you’ll be able to shop at a wide range of stores and brands that just may not be available where you live. Whether you’re after the best in European technology or stylish clothing, we’re here to help.', '', 'Start Shoipping', '/shipping/rates', 1, '2025-10-12 12:17:55', '2025-10-12 12:17:55'),
(4, 'DE', 'Germany', '1760274458_3e84e1a4ace47831844d.webp', '1760274458_d25498e0b327335df915.webp', 'Germany Ware Houses', 'Everything Germany has to offer is ready and waiting. Unlock German parcel forwarding with forward2me and shop online as if you lived there. Germany is home to some of Europe’s most respected brands, offering world-class quality, precision, and innovation across fashion, electronics, and home goods.', 'We are one of the leading international parcel forwarding services with years of experience shipping parcels worldwide. With our German forwarding address, you can shop from trusted retailers and popular marketplaces across the EU — from electronics and luxury goods to everyday essentials. Enjoy reliable delivery and fast international shipping from Germany to anywhere in the world.', 'Start Shipping', '/shipping/rates', 'German Brands We Reship', 'Your German forwarding address lets you shop from some of Europe’s most trusted and innovative brands. Unlock German Amazon.de international shipping or shop from Zalando, Otto, MediaMarkt, Saturn, Adidas, and Puma.\r\n<p>\r\nOur package forwarding service puts the best of Germany right at your fingertips. Buy from trending beauty and lifestyle brands like Nivea, Weleda, and Sebamed, or discover local artisans and unique sellers on Etsy Germany and eBay.de who don’t normally offer international shipping.\r\n\r\nWith German parcel forwarding, you can also get access to top tech retailers like Conrad, Cyberport, and Notebooksbilliger, or explore German fashion from Hugo Boss, Esprit, and s.Oliver.\r\n</p><p>\r\nWhatever you’re looking for, as an international shopper, you can now access German online stores and order to the UK, Europe, Asia, Australia, and beyond — quickly, safely, and affordably.\r\n</p>', '1760274966_c8f24e96f8c3264ff6b3.webp', 'Numquam iste quae fu', 'Nihil consequat Ill', 'Why Use German Parcel Forwarding?', 'Our German parcel forwarding service lets you access a wide range of products from Germany that you might not find locally. By shopping online from Germany, you can explore top retailers, popular marketplaces, and unique local brands that deliver quality, innovation, and style. Whether you’re after cutting-edge technology, premium fashion, or specialty goods, we make it easy for international shoppers to get what they want.', 'Get a parcel forwarding address in Germany and shop at Amazon.de, Ebay.de, or from iconic German brands. Receive toys and games from Steiff and Ravensburger, books from Hugendubel, and the latest fashion from Görtz. All delivered quickly and reliably to your doorstep anywhere in the world.', 'Start Shopping Now', '/shopper', 1, '2025-10-12 11:55:48', '2025-10-12 13:21:49'),
(5, 'JP', 'Japan', '1760278584_d65a8eb2b5dbb74661b7.webp', '1760278584_fb9346072b9b5b2324fc.webp', 'SHOP ONLINE IN JAPAN AND SHIP WORLDWIDE', 'Everything Japan has to offer is ready and waiting. Unlock Japanese parcel forwarding with forward2me and shop online as if you lived there. Japan is renowned for cutting-edge electronics, unique fashion, and high-quality lifestyle products.\r\n', 'We are one of the leading international parcel forwarding services, with years of experience shipping parcels worldwide. With our Japanese forwarding address, you can shop from trusted retailers and exclusive brands across Japan — all delivered safely to your doorstep.', 'Start Shopping Now', '/shopper', 'Japan Brands We Reship', 'Your Japanese forwarding address lets you shop from some of Japan’s most popular and trusted brands. Unlock Amazon Japan international shipping or shop from Uniqlo, Rakuten, Muji, Bic Camera, Yodobashi, and Don Quijote.\r\n<p>\r\nOur package forwarding service puts the best Japanese products at your fingertips. Buy trending beauty and lifestyle items from Shiseido, DHC, or discover unique Japanese sellers on Mercari Japan and Rakuten Ichiba.\r\n</p>\r\nWith Japanese parcel forwarding, you can also access electronics from Sony, Nintendo, or stock up on Japanese fashion and accessories from GU, Comme des Garçons, and Issey Miyake.\r\n<p>\r\nWhatever you’re after, as an international shopper, you can now access Japanese online stores and order to Europe, the US, Australia, and Asia — quickly, safely, and reliably.\r\n</p>', '1760278584_9903697b6a9d529c2b7d.webp', 'Numquam iste quae fu', 'Nihil consequat Ill', 'Why Use Japanese Parcel Forwarding', 'Our Japanese parcel forwarding service lets you access a wide range of Japanese products that are otherwise hard to get internationally. From electronics to fashion to traditional goods, Japan’s online market is vast and unique.\r\n', 'Get a parcel forwarding address in Japan and shop at Amazon Japan, Rakuten, or from Japanese brands directly. Receive electronics from Sony and Nintendo, collectibles from Bandai, or fashion from Uniqlo and Comme des Garçons, all shipped securely to your country.', 'Start Shipping', '/shipping/rates', 1, '2025-10-12 11:55:48', '2025-10-12 14:16:24'),
(6, 'UK', 'United Kingdom', '1760278409_3c417549b3128359a989.webp', '1760278409_5edd1ba83d9ea1221c91.webp', 'SHOP ONLINE IN UTED KINGDOM AND SHIP WORLDWIDE', 'Everything the UK has to offer is ready and waiting. Unlock UK parcel forwarding with forward2me and shop online as if you lived there. The UK is home to globally recognized brands and a diverse online market offering electronics, fashion, beauty, and more.', 'We are one of the leading international parcel forwarding services, with years of experience shipping parcels worldwide. With our UK forwarding address, you can shop from trusted retailers and popular marketplaces across Europe and beyond — all delivered safely to your doorstep.', 'Start Shoppint', '/shoppre', 'UK Brands We Reship', 'Your UK forwarding address lets you shop from some of the world’s most trusted brands. Unlock Amazon UK international shipping or shop from John Lewis, Marks & Spencer, Argos, Currys, Boots, and ASOS.\r\n<p>\r\nOur package forwarding service puts the best UK brands at your fingertips. Buy trending beauty and lifestyle products from The Body Shop, Charlotte Tilbury, or discover local independent sellers on Etsy UK and eBay UK.\r\n</p>\r\nWith UK parcel forwarding, you can also access top tech retailers like Apple UK and Game, or stock up on UK fashion from Topshop, H&M UK, or River Island.\r\n<p>\r\nWhatever you’re after, as an international shopper, you can now access UK online stores and order to Europe, Asia, Australia, and the US — quickly, safely, and reliably.\r\n</p>', '1760278409_d98d60bb0269176aa92c.webp', 'Numquam iste quae fu', 'Nihil consequat Ill', 'Why Use UK Parcel Forwarding', 'Our UK parcel forwarding service lets you access a wide variety of products from the UK that you may not find locally. By shopping online from the UK, you can explore top retailers, local specialists, and international brands that deliver quality and style.\r\n', 'Get a parcel forwarding address in the UK and shop at Amazon UK, eBay UK, or directly from UK brands. Receive toys and games from LEGO UK, books from Waterstones, and the latest fashion from Marks & Spencer and Topshop, all shipped securely to your country.', 'Start Shipping Now', '/shipping/rates', 1, '2025-10-12 11:55:48', '2025-10-12 14:13:29');

-- --------------------------------------------------------

--
-- Table structure for table `why_choose`
--

CREATE TABLE `why_choose` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `order` int(3) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `why_choose`
--

INSERT INTO `why_choose` (`id`, `title`, `description`, `icon`, `order`, `created_at`, `updated_at`) VALUES
(1, '30 DAYS FREE STORAGE', 'We’ll hold your packages so you can have your shipment sent at the most convenient time for you.', '1757874690_feb0e028ce6508752f38.svg', 0, '2025-09-14 18:21:48', '2025-09-14 18:31:30'),
(2, 'BEST PRICE GUARANTEED', 'With our price match and refund guarantee, you can be sure you are always getting the best rates.', '1757875100_48499102c34bcba3372a.svg', 0, '2025-09-14 18:38:20', '2025-09-14 18:38:20'),
(3, 'MULTIPLE ADDRESSES', 'Ship to all our warehouses using a single account. All destinations are just a click away.', '1757875330_7a2eb300d3ed518949fc.svg', 0, '2025-09-14 18:42:10', '2025-09-14 18:42:10'),
(4, 'ENVIRONMENTALLY RESPONSIBLE', 'Focused on sustainability, we integrate environmental considerations into all aspects of our operations.', '1757875399_ec6082f8164060f75187.svg', 0, '2025-09-14 18:43:19', '2025-09-14 18:43:19'),
(5, 'INNOVATIVE SOFTWARE SOLUTIONS', 'We use state-of-the-art parcel forwarding software to minimize processing times and ensure a seamless shipping process.\r\n\r\n', '1757924140_9e56cb6dd3f9b4dab1cf.svg', 0, '2025-09-15 08:15:40', '2025-09-15 08:15:40'),
(6, 'NO SUBSCRIPTION REQUIRED', 'Shippex accounts are free. Use yours whenever you need to without committing to subscriptions or monthly fees.', '1757924171_b2dba547e248634822a0.svg', 0, '2025-09-15 08:16:11', '2025-09-15 08:16:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `booking_status_history`
--
ALTER TABLE `booking_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_fk` (`book_id`),
  ADD KEY `admin_fk` (`changed_by`);

--
-- Indexes for table `delivered_today`
--
ALTER TABLE `delivered_today`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fonts`
--
ALTER TABLE `fonts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_section`
--
ALTER TABLE `hero_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `how_it_works`
--
ALTER TABLE `how_it_works`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `how_it_works_sections`
--
ALTER TABLE `how_it_works_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `how_it_works_steps`
--
ALTER TABLE `how_it_works_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `how_it_works_steps_section_id_foreign` (`section_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_fk` (`category_id`);

--
-- Indexes for table `promo_cards`
--
ALTER TABLE `promo_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_bookings`
--
ALTER TABLE `shipping_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_fk` (`user_id`);

--
-- Indexes for table `shopper_items`
--
ALTER TABLE `shopper_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shopper_items_request_id_foreign` (`request_id`);

--
-- Indexes for table `shopper_requests`
--
ALTER TABLE `shopper_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `virtual_addresses`
--
ALTER TABLE `virtual_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `virtual_addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `country_code` (`country_code`);

--
-- Indexes for table `why_choose`
--
ALTER TABLE `why_choose`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_status_history`
--
ALTER TABLE `booking_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `delivered_today`
--
ALTER TABLE `delivered_today`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `fonts`
--
ALTER TABLE `fonts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hero_section`
--
ALTER TABLE `hero_section`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `how_it_works`
--
ALTER TABLE `how_it_works`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `how_it_works_sections`
--
ALTER TABLE `how_it_works_sections`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `how_it_works_steps`
--
ALTER TABLE `how_it_works_steps`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `promo_cards`
--
ALTER TABLE `promo_cards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipping_bookings`
--
ALTER TABLE `shipping_bookings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `shopper_items`
--
ALTER TABLE `shopper_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shopper_requests`
--
ALTER TABLE `shopper_requests`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `virtual_addresses`
--
ALTER TABLE `virtual_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `why_choose`
--
ALTER TABLE `why_choose`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking_status_history`
--
ALTER TABLE `booking_status_history`
  ADD CONSTRAINT `admin_fk` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_fk` FOREIGN KEY (`book_id`) REFERENCES `shipping_bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `how_it_works_steps`
--
ALTER TABLE `how_it_works_steps`
  ADD CONSTRAINT `how_it_works_steps_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `how_it_works_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `category_fk` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shipping_bookings`
--
ALTER TABLE `shipping_bookings`
  ADD CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shopper_items`
--
ALTER TABLE `shopper_items`
  ADD CONSTRAINT `shopper_items_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `shopper_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `virtual_addresses`
--
ALTER TABLE `virtual_addresses`
  ADD CONSTRAINT `virtual_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
