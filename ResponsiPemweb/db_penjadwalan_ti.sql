-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2024 at 01:35 PM
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
-- Database: `db_penjadwalan_ti`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `hapus_asisten_praktikum` (IN `p_nim` VARCHAR(30))   BEGIN
    DECLARE rollback_action BOOLEAN DEFAULT 0;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    DELETE FROM tb_jadwal WHERE nim = p_nim;

    DELETE FROM tb_asisten_praktikum WHERE nim = p_nim;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `hapus_matakuliah` (IN `kode_mk_param` VARCHAR(30))   BEGIN
    DECLARE continue_delete BOOLEAN DEFAULT TRUE;
    
    START TRANSACTION;

    SELECT COUNT(*) INTO continue_delete FROM tb_matakuliah WHERE kode_mk = kode_mk_param;

    IF continue_delete THEN
        DELETE FROM tb_input_kelas WHERE kode_mk = kode_mk_param;
        DELETE FROM tb_jadwal WHERE kode_mk = kode_mk_param;
        DELETE FROM tb_matakuliah WHERE kode_mk = kode_mk_param;
        
        COMMIT;
    ELSE
        ROLLBACK;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `hapus_ruangan` (IN `p_kode_ruang` VARCHAR(10))   BEGIN
    DECLARE exit handler for sqlexception
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    DECLARE exit handler for sqlwarning
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    DELETE FROM tb_jadwal WHERE kode_ruang = p_kode_ruang;
    DELETE FROM tb_ruangan WHERE kode_ruang = p_kode_ruang;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tambah_asisten_praktikum` (IN `p_nim` VARCHAR(30), IN `p_nama` VARCHAR(50))   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    
    INSERT INTO tb_asisten_praktikum (nim, nama) VALUES (p_nim, p_nama);
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tambah_matakuliah` (IN `kode_mk_baru` VARCHAR(30), IN `nama_matakuliah_baru` VARCHAR(50))   BEGIN
    DECLARE i INT DEFAULT 1;
    
    START TRANSACTION;
    
    INSERT INTO tb_matakuliah (kode_mk, nama_matakuliah) VALUES (kode_mk_baru, nama_matakuliah_baru);
    
    WHILE i <= 4 DO
        INSERT INTO tb_input_kelas (kode_mk) VALUES (kode_mk_baru);
        SET i = i + 1;
    END WHILE;
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tambah_ruangan` (IN `p_nama_ruangan` VARCHAR(50))   BEGIN
    DECLARE v_count INT;
    DECLARE v_kode_ruang VARCHAR(10);
    DECLARE v_nama_ruangan_first_chars VARCHAR(10);
    DECLARE v_word VARCHAR(50);
    DECLARE v_word_count INT;
    DECLARE v_word_index INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    SELECT COUNT(*) INTO v_count FROM tb_ruangan;

    IF v_count = 0 THEN
        SET v_kode_ruang = 'R01';
    ELSE
        SET v_kode_ruang = CONCAT('R0', v_count + 1);
    END IF;

    SET v_nama_ruangan_first_chars = '';
    SET v_word_index = 1;
    SET v_word_count = LENGTH(p_nama_ruangan) - LENGTH(REPLACE(p_nama_ruangan, ' ', '')) + 1;

    WHILE v_word_index <= v_word_count DO
        SET v_word = SUBSTRING_INDEX(SUBSTRING_INDEX(p_nama_ruangan, ' ', v_word_index), ' ', -1);
        SET v_nama_ruangan_first_chars = CONCAT(v_nama_ruangan_first_chars, UPPER(SUBSTRING(v_word, 1, 1)));
        SET v_word_index = v_word_index + 1;
    END WHILE;

    SET v_kode_ruang = CONCAT(v_kode_ruang, v_nama_ruangan_first_chars);

    INSERT INTO tb_ruangan (kode_ruang, nama_ruangan) VALUES (v_kode_ruang, p_nama_ruangan);

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_asisten_praktikum` (IN `p_nim` VARCHAR(30), IN `p_new_name` VARCHAR(50))   BEGIN
    DECLARE rollback_action BOOLEAN DEFAULT 0;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    UPDATE tb_asisten_praktikum SET nama = p_new_name WHERE nim = p_nim;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_konfigurasi` (IN `new_populationSize` INT, IN `new_mutationRate` INT, IN `new_generations` INT)   BEGIN
    UPDATE tb_konfigurasi
    SET 
        populationSize = new_populationSize,
        mutationRate = new_mutationRate,
        generations = new_generations;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_nama_matakuliah` (IN `p_kode_mk` VARCHAR(30), IN `p_nama_matakuliah` VARCHAR(50))   BEGIN
    DECLARE rollback_action BOOLEAN DEFAULT 0;
    
    START TRANSACTION;
    
    UPDATE tb_matakuliah
    SET nama_matakuliah = p_nama_matakuliah
    WHERE kode_mk = p_kode_mk;
    
    IF rollback_action THEN
        ROLLBACK;
    ELSE
        COMMIT;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_nama_ruangan` (IN `p_kode_ruang` VARCHAR(10), IN `p_nama_ruangan_baru` VARCHAR(50))   BEGIN
    DECLARE exit handler for sqlexception
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    DECLARE exit handler for sqlwarning
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    UPDATE tb_ruangan
    SET nama_ruangan = p_nama_ruangan_baru
    WHERE kode_ruang = p_kode_ruang;

    COMMIT;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `count_matakuliah_ampu` (`nim_asisten` VARCHAR(20)) RETURNS INT(11)  BEGIN
    DECLARE count_mk INT;
    
/*Penerapan fungsi agregat count (Bab2)*/
    SELECT COUNT(kode_mk)
    INTO count_mk
    FROM tb_jadwal
    WHERE nim = nim_asisten;
    
    RETURN count_mk;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `count_matakuliah_matkul` (`kode_mk_input` VARCHAR(20)) RETURNS INT(11)  BEGIN
    DECLARE count_mk INT;
    
/*Penerapan fungsi agregat count (Bab2)*/
    SELECT COUNT(kode_mk)
    INTO count_mk
    FROM tb_jadwal
    WHERE kode_mk = kode_mk_input;
    
    RETURN count_mk;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `count_matakuliah_ruang` (`kode_ruang_input` VARCHAR(20)) RETURNS INT(11)  BEGIN
    DECLARE count_mk INT;
    
/*Penerapan fungsi agregat count (Bab2)*/
    SELECT COUNT(kode_mk)
    INTO count_mk
    FROM tb_jadwal
    WHERE kode_ruang = kode_ruang_input;
    
    RETURN count_mk;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_asisten_praktikum`
--

CREATE TABLE `tb_asisten_praktikum` (
  `nim` varchar(30) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_asisten_praktikum`
--

INSERT INTO `tb_asisten_praktikum` (`nim`, `nama`) VALUES
('203010503009', ' Resha Ananda Rahman1'),
('203010503019', 'Rholand Deo Eka Putra'),
('203020503029', 'Bayu Dwi Yulianto'),
('203030503085', 'Fikri Firadus'),
('213010503003', 'Kevin Wilbert Sachio'),
('213010503008', 'Via Windiana'),
('213010503014', 'Evita Cahyani'),
('213020503018', 'Aldoni Fahreza'),
('213020503020', 'Bayu Pratama'),
('213020503027', 'Recal Prasetyo'),
('213020503058', 'Jordan Setiawan Nanyan'),
('213020503061', 'Gabriel Dwi Putra Krisma Rusan'),
('213020503083', 'Mohammad Alif Tuharea'),
('213030503098', 'Samuel Fernando'),
('213030503104', '   Euriqo Diaz1'),
('213030503108', 'Ricard Jonathan'),
('213030503111', 'Michael Henokh'),
('213030503116', 'okta panji winata'),
('213030503120', 'Hardi'),
('213030503123', 'Elieser Dawson Frizt Simangunsong'),
('213030503131', 'Samuel Raka Yustianto'),
('213030503133', 'Risky Prasetyo'),
('213030503156', 'Dedan Adam'),
('213030503160', 'Thomas Richard Wini Ngele'),
('223020503002', 'Safitri Jaya'),
('223020503059', ' Ryan Delon Pratama'),
('DBC117040', 'Harits Wahid Vijayanto'),
('DBC117085', 'Gabriel Padma'),
('DBC117087', 'Yoga Pratama Salim');

-- --------------------------------------------------------

--
-- Table structure for table `tb_hari`
--

CREATE TABLE `tb_hari` (
  `id_hari` varchar(30) NOT NULL,
  `hari` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_hari`
--

INSERT INTO `tb_hari` (`id_hari`, `hari`) VALUES
('H1', 'senin'),
('H2', 'selasa'),
('H3', 'rabu'),
('H4', 'kamis'),
('H5', 'jumat');

-- --------------------------------------------------------

--
-- Table structure for table `tb_input_kelas`
--

CREATE TABLE `tb_input_kelas` (
  `id_input` int(11) NOT NULL,
  `kode_mk` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_input_kelas`
--

INSERT INTO `tb_input_kelas` (`id_input`, `kode_mk`) VALUES
(5, '1DCP182032'),
(6, '1DCP182032'),
(7, '1DCP182032'),
(8, '1DCP182032'),
(9, '1DCP314032'),
(10, '1DCP314032'),
(11, '1DCP314032'),
(12, '1DCP314032'),
(25, '1DCP487878'),
(26, '1DCP487878'),
(27, '1DCP487878'),
(28, '1DCP487878'),
(13, '1DCP584032'),
(14, '1DCP584032'),
(15, '1DCP584032'),
(16, '1DCP584032'),
(17, '1DCP644032'),
(18, '1DCP644032'),
(19, '1DCP644032'),
(20, '1DCP644032'),
(29, 'AKB4848'),
(30, 'AKB4848'),
(31, 'AKB4848'),
(32, 'AKB4848');

--
-- Triggers `tb_input_kelas`
--
DELIMITER $$
CREATE TRIGGER `set_id_input` BEFORE INSERT ON `tb_input_kelas` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    /*Penerapan Fungsi Agregat (Bab 2)*/
    SELECT MAX(id_input) INTO last_id FROM tb_input_kelas;

    IF last_id IS NULL THEN
        SET NEW.id_input = 1;
    ELSE
        SET NEW.id_input = last_id + 1;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_jadwal`
--

CREATE TABLE `tb_jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `kode_mk` varchar(30) NOT NULL,
  `kode_ruang` varchar(30) NOT NULL,
  `id_hari` varchar(30) NOT NULL,
  `id_jam` varchar(11) NOT NULL,
  `nim` varchar(30) NOT NULL,
  `best_generation` int(11) NOT NULL,
  `fitness_score` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_jadwal`
--

INSERT INTO `tb_jadwal` (`id_jadwal`, `kode_mk`, `kode_ruang`, `id_hari`, `id_jam`, `nim`, `best_generation`, `fitness_score`) VALUES
(1, '1DCP584032', 'R05LJ1', 'H3', 'T04', '213020503027', 1, '0.5'),
(2, '1DCP644032', 'R05LJ1', 'H4', 'T02', '213030503104', 1, '0.5'),
(3, '1DCP182032', 'R03LP2', 'H5', 'T03', '213030503104', 1, '0.5'),
(4, '1DCP584032', 'R01LDS', 'H1', 'T01', '203010503019', 1, '0.5'),
(5, 'AKB4848', 'R01LDS', 'H4', 'T02', '213030503108', 1, '0.5'),
(6, '1DCP584032', 'R07LK1', 'H5', 'T02', '213030503123', 1, '0.5'),
(8, '1DCP487878', 'R02LP1', 'H5', 'T04', '213030503111', 1, '0.5'),
(9, '1DCP314032', 'R05LJ1', 'H5', 'T01', '213020503058', 1, '0.5'),
(10, '1DCP182032', 'R01LDS', 'H3', 'T01', '213030503131', 1, '0.5'),
(11, '1DCP314032', 'R01LDS', 'H5', 'T04', '213020503018', 1, '0.5'),
(12, '1DCP314032', 'R07LK1', 'H4', 'T03', '213030503123', 1, '0.5'),
(13, '1DCP487878', 'R07LK1', 'H4', 'T02', '213010503008', 1, '0.5'),
(14, 'AKB4848', 'R02LP1', 'H2', 'T02', '213020503018', 1, '0.5'),
(15, '1DCP487878', 'R02LP1', 'H2', 'T02', '213010503008', 1, '0.5'),
(16, '1DCP314032', 'R02LP1', 'H5', 'T02', '203030503085', 1, '0.5'),
(17, '1DCP487878', 'R02LP1', 'H4', 'T03', '213030503131', 1, '0.5'),
(18, '1DCP644032', 'R02LP1', 'H5', 'T03', '213010503014', 1, '0.5'),
(19, '1DCP644032', 'R01LDS', 'H1', 'T02', 'DBC117087', 1, '0.5'),
(20, '1DCP584032', 'R01LDS', 'H1', 'T03', '203010503009', 1, '0.5'),
(21, '1DCP182032', 'R03LP2', 'H3', 'T04', '213010503014', 1, '0.5'),
(23, 'AKB4848', 'R07LK1', 'H3', 'T02', '213020503027', 1, '0.5'),
(24, '1DCP182032', 'R02LP1', 'H2', 'T03', '203010503019', 1, '0.5');

-- --------------------------------------------------------

--
-- Table structure for table `tb_jam_praktikum`
--

CREATE TABLE `tb_jam_praktikum` (
  `id_jam` varchar(11) NOT NULL,
  `jam` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_jam_praktikum`
--

INSERT INTO `tb_jam_praktikum` (`id_jam`, `jam`) VALUES
('T01', '07:00 - 09:00'),
('T02', '09:00 - 12:00'),
('T03', '12:00 - 14:00'),
('T04', '15:00 - 17:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_konfigurasi`
--

CREATE TABLE `tb_konfigurasi` (
  `populationSize` int(11) NOT NULL,
  `mutationRate` int(11) NOT NULL,
  `generations` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_konfigurasi`
--

INSERT INTO `tb_konfigurasi` (`populationSize`, `mutationRate`, `generations`) VALUES
(20, 10, 200);

-- --------------------------------------------------------

--
-- Table structure for table `tb_matakuliah`
--

CREATE TABLE `tb_matakuliah` (
  `kode_mk` varchar(30) NOT NULL,
  `nama_matakuliah` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_matakuliah`
--

INSERT INTO `tb_matakuliah` (`kode_mk`, `nama_matakuliah`) VALUES
('1DCP182032', 'Struktur Data'),
('1DCP314032', ' Jaringan Komputer I'),
('1DCP487878', ' Algoritma dan Pemrograman II'),
('1DCP584032', 'Basis Data II'),
('1DCP644032', 'Pemrograman Web dan Mobile'),
('AKB4848', '  Algoritma dan Pemrograman Spatial I');

-- --------------------------------------------------------

--
-- Table structure for table `tb_ruangan`
--

CREATE TABLE `tb_ruangan` (
  `kode_ruang` varchar(10) NOT NULL,
  `nama_ruangan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_ruangan`
--

INSERT INTO `tb_ruangan` (`kode_ruang`, `nama_ruangan`) VALUES
('R01LDS', ' Lab Data Science'),
('R02LP1', 'Lab Pemrograman 1'),
('R03LP2', 'Lab Pemrograman 2'),
('R05LJ1', 'Lab Jaringan 1'),
('R06LF1', 'Lab FT 10'),
('R07LK1', '  Lab Komputer 1');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `email` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nim` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`email`, `nama`, `nim`, `password`) VALUES
('a@gmail.com', 'Mordekaiser Von Noxus', '223020503180', '$2y$10$oqdRbsGiCz/rmWM0xQf6OufVSQxOfG5rZ97AGYc90C7UMILSefc9i'),
('admin@gmail.com', 'admin', '08', '$2y$10$mBeSrjbImAOUu5DjcZis3eYnu.jkw8p56L6Vqk.Ofj/wsnxqNMqSe'),
('d@gmail.com', 'delon', '223020503057', '$2y$10$iRX7NEVjkvEHWr4jdN3sreYW2IzsPTJcGb/CRzbtjMcSJHxg7OYvK'),
('g@gmail.com', 'Mister Grace', '223020503062', '$2y$10$4fAEDR1lsY3G7z9sEF6d5OQY8Z8HNxGqQtPV8O1tGn4kO7AcEvbkq'),
('j@gmail.com', 'Jessie JKT48', '203020503050', '$2y$10$uL9.94xy52vVmoSfOfiUFO..N.zVwRJ/OS.1Fe7B6exbkidf7oT2a'),
('v@gmail.com', 'vivy flourite', '224069', '$2y$10$6AQK1SNB.JMb465PLH7Alu8kKw9RPITVJmSYUFOZP9bIV1ab6comu'),
('z@gmail.com', 'Zara Adhisty Zara', '223020503001', '$2y$10$EOmQ1TsS/0DTMwgQu5n8QOXBQFUKVZtmeXppk8lXeGePWbAV7QoPO');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_jadwal_detail`
-- (See below for the actual view)
--
CREATE TABLE `view_jadwal_detail` (
`nama_matakuliah` varchar(50)
,`nama_ruangan` varchar(50)
,`hari` varchar(30)
,`jam` varchar(30)
,`nama_asisten` varchar(50)
,`best_generation` int(11)
,`fitness_score` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_matakuliah`
-- (See below for the actual view)
--
CREATE TABLE `view_matakuliah` (
`kode_mk` varchar(30)
,`nama_matakuliah` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_ruangan`
-- (See below for the actual view)
--
CREATE TABLE `view_ruangan` (
`kode_ruang` varchar(10)
,`nama_ruangan` varchar(50)
);

-- --------------------------------------------------------

--
-- Structure for view `view_jadwal_detail`
--
DROP TABLE IF EXISTS `view_jadwal_detail`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_jadwal_detail`  AS SELECT `mk`.`nama_matakuliah` AS `nama_matakuliah`, `ru`.`nama_ruangan` AS `nama_ruangan`, `h`.`hari` AS `hari`, `jp`.`jam` AS `jam`, `ap`.`nama` AS `nama_asisten`, `j`.`best_generation` AS `best_generation`, `j`.`fitness_score` AS `fitness_score` FROM (((((`tb_jadwal` `j` join `tb_matakuliah` `mk` on(`j`.`kode_mk` = `mk`.`kode_mk`)) join `tb_ruangan` `ru` on(`j`.`kode_ruang` = `ru`.`kode_ruang`)) join `tb_hari` `h` on(`j`.`id_hari` = `h`.`id_hari`)) join `tb_jam_praktikum` `jp` on(`j`.`id_jam` = `jp`.`id_jam`)) join `tb_asisten_praktikum` `ap` on(`j`.`nim` = `ap`.`nim`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_matakuliah`
--
DROP TABLE IF EXISTS `view_matakuliah`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_matakuliah`  AS SELECT `tb_matakuliah`.`kode_mk` AS `kode_mk`, `tb_matakuliah`.`nama_matakuliah` AS `nama_matakuliah` FROM `tb_matakuliah` ;

-- --------------------------------------------------------

--
-- Structure for view `view_ruangan`
--
DROP TABLE IF EXISTS `view_ruangan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_ruangan`  AS SELECT `tb_ruangan`.`kode_ruang` AS `kode_ruang`, `tb_ruangan`.`nama_ruangan` AS `nama_ruangan` FROM `tb_ruangan` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_asisten_praktikum`
--
ALTER TABLE `tb_asisten_praktikum`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `tb_hari`
--
ALTER TABLE `tb_hari`
  ADD PRIMARY KEY (`id_hari`);

--
-- Indexes for table `tb_input_kelas`
--
ALTER TABLE `tb_input_kelas`
  ADD PRIMARY KEY (`id_input`),
  ADD KEY `kode_mk` (`kode_mk`);

--
-- Indexes for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `kode_mk` (`kode_mk`),
  ADD KEY `kode_ruang` (`kode_ruang`),
  ADD KEY `id_jam` (`id_jam`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_hari` (`id_hari`);

--
-- Indexes for table `tb_jam_praktikum`
--
ALTER TABLE `tb_jam_praktikum`
  ADD PRIMARY KEY (`id_jam`);

--
-- Indexes for table `tb_matakuliah`
--
ALTER TABLE `tb_matakuliah`
  ADD PRIMARY KEY (`kode_mk`);

--
-- Indexes for table `tb_ruangan`
--
ALTER TABLE `tb_ruangan`
  ADD PRIMARY KEY (`kode_ruang`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_input_kelas`
--
ALTER TABLE `tb_input_kelas`
  ADD CONSTRAINT `tb_input_kelas_ibfk_1` FOREIGN KEY (`kode_mk`) REFERENCES `tb_matakuliah` (`kode_mk`);

--
-- Constraints for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  ADD CONSTRAINT `tb_jadwal_ibfk_1` FOREIGN KEY (`kode_mk`) REFERENCES `tb_matakuliah` (`kode_mk`),
  ADD CONSTRAINT `tb_jadwal_ibfk_2` FOREIGN KEY (`kode_ruang`) REFERENCES `tb_ruangan` (`kode_ruang`),
  ADD CONSTRAINT `tb_jadwal_ibfk_3` FOREIGN KEY (`id_jam`) REFERENCES `tb_jam_praktikum` (`id_jam`),
  ADD CONSTRAINT `tb_jadwal_ibfk_4` FOREIGN KEY (`nim`) REFERENCES `tb_asisten_praktikum` (`nim`),
  ADD CONSTRAINT `tb_jadwal_ibfk_5` FOREIGN KEY (`id_hari`) REFERENCES `tb_hari` (`id_hari`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
