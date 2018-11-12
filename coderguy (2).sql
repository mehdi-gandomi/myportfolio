-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2018 at 11:21 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coderguy`
--

-- --------------------------------------------------------

--
-- Table structure for table `cat_post`
--

CREATE TABLE `cat_post` (
  `cat_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cat_post`
--

INSERT INTO `cat_post` (`cat_id`, `post_id`) VALUES
(2, 3),
(3, 3),
(1, 2),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `cat_project`
--

CREATE TABLE `cat_project` (
  `project_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cat_project`
--

INSERT INTO `cat_project` (`project_id`, `cat_id`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `job_fields`
--

CREATE TABLE `job_fields` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `subtitle` varchar(30) NOT NULL,
  `content` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `job_fields`
--

INSERT INTO `job_fields` (`id`, `user_id`, `title`, `subtitle`, `content`) VALUES
(1, 2, 'طراحی وبسایت', 'طراحی فرانت اند', 'طراحی وب سایت، طراحی سایت یا همان Web Design، دقیقا به معنای تلاش برای ایجاد یک پایگاه اینترنتی برای ارائه خدمات، فروش محصول یا اطلاع رسانی است. طراحی وب سایت یک پروسه است که از لایه‌سازی صفحات وب، تو'),
(2, 2, 'برنامه نویسی ویندوز', 'برنامه نویسی دسکتاپ', 'بعدا کامل میشه؟؟؟؟؟!');

-- --------------------------------------------------------

--
-- Table structure for table `personal_info`
--

CREATE TABLE `personal_info` (
  `id` int(11) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `job` varchar(100) NOT NULL,
  `bio` text NOT NULL,
  `address` varchar(200) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `twitter` varchar(100) NOT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `telegram` varchar(100) DEFAULT NULL,
  `linkedin` varchar(100) DEFAULT NULL,
  `github` varchar(100) DEFAULT NULL,
  `pinterest` varchar(100) NOT NULL,
  `profile` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `personal_info`
--

INSERT INTO `personal_info` (`id`, `fname`, `lname`, `avatar`, `job`, `bio`, `address`, `email`, `phone`, `twitter`, `instagram`, `telegram`, `linkedin`, `github`, `pinterest`, `profile`) VALUES
(2, 'مهدی', 'گندمی', '1534263353avatar.jpg', 'طراح و برنامه نویس وب', 'مهدی گندمی هستم برنامه نویس و طراح وبسایت.در سال 1393 با انتخاب رشته کامپیوتر وارد هنرستان شدم و درسال 1395 نیز در کنکور کاردانی نرم افزار کامپیوتر شرکت کردم و در دانشگاه منتظری مشهد مشغول به تحصیل هستم.علاقه شدیدی به اینترنت اشیا و تکنولوژی های جدید دارم.با زبان ها و فریمورک های زیادی در زمینه وب کار کرده ام که ادامه مشاهده خواهید کرد.', '', 'mehdigandomi.contact@gmail.com', '09389318493', '', 'https://www.instagram.com/coderguy.ir/', 'https://t.me/Coder_guy', 'https://www.linkedin.com/in/mehdi-gandomi/', 'https://github.com/mehdi-gandomi', '', 'https://profile.ir/mehdi_gandomi');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_title` varchar(50) NOT NULL,
  `post_slug` varchar(50) NOT NULL,
  `post_thumbnail` varchar(200) NOT NULL,
  `preview_text` text NOT NULL,
  `post_content` text NOT NULL,
  `tags` varchar(200) NOT NULL,
  `categories_preview` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `post_title`, `post_slug`, `post_thumbnail`, `preview_text`, `post_content`, `tags`, `categories_preview`, `created_at`, `updated_at`) VALUES
(2, 2, 'تست', 'test', 'public/images/uploads/Screenshot (1).png', 'تست میشه2', '                                                                                                                                                                                                                                                                                                                                <h1 style="text-align: center">متن پست در اینجا قرار می گیرد</h1>\r\n\r\n                            \r\n\r\n                            \r\n\r\n                            \r\n\r\n                            \r\n\r\n                            \r\n\r\n                            \r\n\r\n                            \r\n\r\n                            \r\n\r\n                            \r\n\r\n                            ', 'وردپرس,برنامه نویسی,طراحی وب', 'طراحی وب,برنامه نویسی', '2018-10-30 16:44:50', '2018-09-17 16:19:11'),
(3, 2, 'تست', 'test-3', 'public/images/uploads/Screenshot (1).png', 'hkhfk', '                                <h1 style="text-align: center">متن پست در اینجا قرار می گیرد</h1>\r\n\r\n                            ', '', '', '2018-10-29 20:02:46', '2018-10-24 14:43:58');

-- --------------------------------------------------------

--
-- Table structure for table `post_categories`
--

CREATE TABLE `post_categories` (
  `cat_id` int(11) NOT NULL,
  `cat_slug` varchar(50) NOT NULL,
  `cat_title` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_categories`
--

INSERT INTO `post_categories` (`cat_id`, `cat_slug`, `cat_title`) VALUES
(1, 'web-development', 'طراحی وب'),
(2, 'programming', 'برنامه نویسی'),
(3, 'tips-and-tricks', 'ترفندهای کامپیوتر');

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `state` varchar(1) NOT NULL,
  `reply_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project_title` varchar(50) NOT NULL,
  `project_image` varchar(100) NOT NULL,
  `project_link` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `project_description` text NOT NULL,
  `project_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_title`, `project_image`, `project_link`, `location`, `project_description`, `project_date`) VALUES
(1, 'وبسایت پتروپارت', 'petropart.JPG', 'http://2.180.3.203:8010', 'استان خراسان رضوی - شهر مشهد', 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد. در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد. لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد. در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.', '2018-08-29 13:22:39'),
(2, 'پروژه مدیریت کارگاه', 'kargah.jpg', 'http://google.com', 'خراسان رضوی - شهر مشهد', 'بعدا کامل میشه', '2018-08-30 23:00:29');

-- --------------------------------------------------------

--
-- Table structure for table `project_categories`
--

CREATE TABLE `project_categories` (
  `id` int(11) NOT NULL,
  `cat_title` varchar(50) NOT NULL,
  `cat_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project_categories`
--

INSERT INTO `project_categories` (`id`, `cat_title`, `cat_name`) VALUES
(1, 'وب', 'web');

-- --------------------------------------------------------

--
-- Table structure for table `project_comments`
--

CREATE TABLE `project_comments` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `reply_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `project_comments`
--

INSERT INTO `project_comments` (`id`, `project_id`, `username`, `email`, `message`, `state`, `reply_to`) VALUES
(1, 2, 'مهدی', 'coderguy1999@gmail.com', 'ممنون از پستتون', 0, 0),
(3, 2, 'مهدی گندمی', 'coderguy1999@gmail.com', 'مهدی گندمی هستم . طراح و برنامه نویس وب. تفریح من یادگیری چیزای جدیده :)', 1, 0),
(4, 1, 'مهدی گندمی', 'coderguy1999@gmail.com', 'مهدی گندمی هستم . طراح و برنامه نویس وب. تفریح من یادگیری چیزای جدیده :)', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `project_thumbs`
--

CREATE TABLE `project_thumbs` (
  `project_id` int(11) NOT NULL,
  `thumb` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project_thumbs`
--

INSERT INTO `project_thumbs` (`project_id`, `thumb`) VALUES
(2, 'Screenshot (2).png'),
(2, 'Screenshot (3).png');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL,
  `skill_title` varchar(40) NOT NULL,
  `color` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `skill_title`, `color`) VALUES
(1, 'HTML', '#F1662A'),
(2, 'CSS', '#33A9DC'),
(3, 'Javascript', '#F5DE19'),
(4, 'PHP', '#4F5B93'),
(5, 'Nodejs', '#90C53F'),
(6, 'Vuejs', '#41B883'),
(8, 'Python', '#366E9D'),
(9, 'Bootstrap', '#5F2BAB'),
(10, 'REST Api', '#2199e8'),
(11, 'Materialize', '#EE6E73'),
(12, 'SQL', '#2677C7'),
(13, 'Mongodb', '#4F9546'),
(14, 'SEO', '#9300f4'),
(15, 'Android', '#4de027'),
(16, 'laravel', '#fe452f');

-- --------------------------------------------------------

--
-- Table structure for table `skill_project`
--

CREATE TABLE `skill_project` (
  `project_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skill_project`
--

INSERT INTO `skill_project` (`project_id`, `skill_id`) VALUES
(1, 4),
(1, 1),
(1, 2),
(1, 3),
(1, 9),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 9);

-- --------------------------------------------------------

--
-- Table structure for table `skill_user`
--

CREATE TABLE `skill_user` (
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `skill_value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skill_user`
--

INSERT INTO `skill_user` (`user_id`, `skill_id`, `skill_value`) VALUES
(2, 1, 85),
(2, 2, 65),
(2, 3, 75),
(2, 4, 80),
(2, 5, 70),
(2, 6, 70),
(2, 8, 50),
(2, 9, 80),
(2, 10, 60),
(2, 11, 70),
(2, 12, 90),
(2, 13, 55),
(2, 16, 60);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `avatar` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fname`, `lname`, `avatar`) VALUES
(1, 'coderguy', '$2y$10$VBbDGXJV.pfFpnoXeJsQFudxx7rKBDwOzkM8CVIPoaajCWjwTcKr2', 'مهدی ', 'گندمی', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cat_post`
--
ALTER TABLE `cat_post`
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `cat_project`
--
ALTER TABLE `cat_project`
  ADD KEY `project_id` (`project_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `job_fields`
--
ALTER TABLE `job_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `personal_info`
--
ALTER TABLE `personal_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `post_categories`
--
ALTER TABLE `post_categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_categories`
--
ALTER TABLE `project_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_commets_ibfk_1` (`project_id`),
  ADD KEY `reply_to` (`reply_to`);

--
-- Indexes for table `project_thumbs`
--
ALTER TABLE `project_thumbs`
  ADD KEY `project_thumbs_ibfk_1` (`project_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`);

--
-- Indexes for table `skill_project`
--
ALTER TABLE `skill_project`
  ADD KEY `skill_id` (`skill_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `skill_user`
--
ALTER TABLE `skill_user`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `skii_user_ibfk_2` (`skill_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `personal_info`
--
ALTER TABLE `personal_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `post_categories`
--
ALTER TABLE `post_categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `project_categories`
--
ALTER TABLE `project_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `project_comments`
--
ALTER TABLE `project_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `cat_post`
--
ALTER TABLE `cat_post`
  ADD CONSTRAINT `cat_post_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `post_categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cat_post_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cat_project`
--
ALTER TABLE `cat_project`
  ADD CONSTRAINT `cat_project_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cat_project_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `project_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_fields`
--
ALTER TABLE `job_fields`
  ADD CONSTRAINT `job_fields_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `personal_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD CONSTRAINT `project_comments_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_thumbs`
--
ALTER TABLE `project_thumbs`
  ADD CONSTRAINT `project_thumbs_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `skill_project`
--
ALTER TABLE `skill_project`
  ADD CONSTRAINT `skill_project_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `skill_project_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `skill_user`
--
ALTER TABLE `skill_user`
  ADD CONSTRAINT `skill_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `personal_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `skill_user_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
