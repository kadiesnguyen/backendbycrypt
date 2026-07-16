-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 21, 2026 at 05:06 PM
-- Server version: 8.0.45
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trading`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_018e1166eaa3ee9744252f8f75d306aa7c9b2c06', 'i:4;', 1773115180),
('laravel_cache_018e1166eaa3ee9744252f8f75d306aa7c9b2c06:timer', 'i:1773115180;', 1773115180),
('laravel_cache_01fe857cae31b0428163b510c73bbb909b1fed64', 'i:1;', 1772508836),
('laravel_cache_01fe857cae31b0428163b510c73bbb909b1fed64:timer', 'i:1772508836;', 1772508836),
('laravel_cache_03779618d8d305bc4fa9c96ea72e54883a8f234a', 'i:1;', 1773276652),
('laravel_cache_03779618d8d305bc4fa9c96ea72e54883a8f234a:timer', 'i:1773276652;', 1773276652),
('laravel_cache_038987f4bcf43fab3f4c28bfe59f67dc8768baf6', 'i:1;', 1773857907),
('laravel_cache_038987f4bcf43fab3f4c28bfe59f67dc8768baf6:timer', 'i:1773857907;', 1773857907),
('laravel_cache_05263b0f0ec8fb3263b39c08c6123c47cc868803', 'i:1;', 1773695542),
('laravel_cache_05263b0f0ec8fb3263b39c08c6123c47cc868803:timer', 'i:1773695542;', 1773695542),
('laravel_cache_052d70a2d775c8ddf555c5588a2af3277ce8d2a3', 'i:1;', 1773928746),
('laravel_cache_052d70a2d775c8ddf555c5588a2af3277ce8d2a3:timer', 'i:1773928746;', 1773928746),
('laravel_cache_05bed39348958a22f63d7f616d3a2e9578213bd4', 'i:6;', 1773212720),
('laravel_cache_05bed39348958a22f63d7f616d3a2e9578213bd4:timer', 'i:1773212720;', 1773212720),
('laravel_cache_06716202bac5a09aebed18bda0875bf4f7be4f08', 'i:1;', 1773637775),
('laravel_cache_06716202bac5a09aebed18bda0875bf4f7be4f08:timer', 'i:1773637775;', 1773637775),
('laravel_cache_06ff05a4e7ab6b65d453d81a2fe2b9cdf12bd540', 'i:1;', 1773756873),
('laravel_cache_06ff05a4e7ab6b65d453d81a2fe2b9cdf12bd540:timer', 'i:1773756873;', 1773756873),
('laravel_cache_0715f2a650769fd5aeb274118bae91ff82ef1a23', 'i:1;', 1773283552),
('laravel_cache_0715f2a650769fd5aeb274118bae91ff82ef1a23:timer', 'i:1773283552;', 1773283552),
('laravel_cache_0725272cfd28e2c4b9f992788db58cad3b865d10', 'i:1;', 1773279732),
('laravel_cache_0725272cfd28e2c4b9f992788db58cad3b865d10:timer', 'i:1773279732;', 1773279732),
('laravel_cache_07a85bd180d31c968e6dc5989ac4de434918dd41', 'i:11;', 1773210986),
('laravel_cache_07a85bd180d31c968e6dc5989ac4de434918dd41:timer', 'i:1773210986;', 1773210986),
('laravel_cache_07beb0b7d2ca6a0176445ba972711d71a0bd5f8a', 'i:1;', 1773949178),
('laravel_cache_07beb0b7d2ca6a0176445ba972711d71a0bd5f8a:timer', 'i:1773949178;', 1773949178),
('laravel_cache_07e36f3d3204d0840764f13d325f8846d09137d7', 'i:1;', 1773750048),
('laravel_cache_07e36f3d3204d0840764f13d325f8846d09137d7:timer', 'i:1773750048;', 1773750048),
('laravel_cache_07f0dcd626f3d7d3493272415afbd9379f8fdd48', 'i:1;', 1773369331),
('laravel_cache_07f0dcd626f3d7d3493272415afbd9379f8fdd48:timer', 'i:1773369331;', 1773369331),
('laravel_cache_084b9745b8ec6e02258b328baf59b3e3f12c33c8', 'i:3;', 1773792468),
('laravel_cache_084b9745b8ec6e02258b328baf59b3e3f12c33c8:timer', 'i:1773792468;', 1773792468),
('laravel_cache_08abd95dee823b8d7444b577b30f6bbbfbb3194d', 'i:1;', 1774005174),
('laravel_cache_08abd95dee823b8d7444b577b30f6bbbfbb3194d:timer', 'i:1774005174;', 1774005174),
('laravel_cache_08f9ae3cae278e471fe7bcc069a48c499a28fc59', 'i:2;', 1773247629),
('laravel_cache_08f9ae3cae278e471fe7bcc069a48c499a28fc59:timer', 'i:1773247629;', 1773247629),
('laravel_cache_0c0f737ca5667c0c30ec83947139c1795c465ceb', 'i:1;', 1773277821),
('laravel_cache_0c0f737ca5667c0c30ec83947139c1795c465ceb:timer', 'i:1773277821;', 1773277821),
('laravel_cache_0dcf66ac753b310861adc8fbb5ae3b45b2a3ea4a', 'i:1;', 1773829821),
('laravel_cache_0dcf66ac753b310861adc8fbb5ae3b45b2a3ea4a:timer', 'i:1773829821;', 1773829821),
('laravel_cache_0de4bb0339125791deed0915c2c4d855c3b5eabe', 'i:1;', 1773205779),
('laravel_cache_0de4bb0339125791deed0915c2c4d855c3b5eabe:timer', 'i:1773205779;', 1773205779),
('laravel_cache_0e07ce39e3634227653c53d1951e2c39c6a87c9e', 'i:1;', 1773214705),
('laravel_cache_0e07ce39e3634227653c53d1951e2c39c6a87c9e:timer', 'i:1773214705;', 1773214705),
('laravel_cache_0fc5a06bb26724f7f2a272efee433922c93751ba', 'i:1;', 1773285715),
('laravel_cache_0fc5a06bb26724f7f2a272efee433922c93751ba:timer', 'i:1773285715;', 1773285715),
('laravel_cache_1153e60ed8c3a7d2d8978bff37801ec02a0cba22', 'i:2;', 1773555754),
('laravel_cache_1153e60ed8c3a7d2d8978bff37801ec02a0cba22:timer', 'i:1773555754;', 1773555754),
('laravel_cache_1185401df4fc07ec0f2e42c538ab6b1bb1388264', 'i:4;', 1773252868),
('laravel_cache_1185401df4fc07ec0f2e42c538ab6b1bb1388264:timer', 'i:1773252868;', 1773252868),
('laravel_cache_11e3541b17d49d351d1d274be46059798e0d7b84', 'i:1;', 1772766718),
('laravel_cache_11e3541b17d49d351d1d274be46059798e0d7b84:timer', 'i:1772766718;', 1772766718),
('laravel_cache_13b901af9fdad971cb5a5b7a4bd65375e1c6eda6', 'i:1;', 1773216915),
('laravel_cache_13b901af9fdad971cb5a5b7a4bd65375e1c6eda6:timer', 'i:1773216915;', 1773216915),
('laravel_cache_14eabe3c4e906833f89652338e4349b645042434', 'i:1;', 1773284397),
('laravel_cache_14eabe3c4e906833f89652338e4349b645042434:timer', 'i:1773284397;', 1773284397),
('laravel_cache_14eef294b8897eaf1ce816ab1538194af6d760c6', 'i:1;', 1773789318),
('laravel_cache_14eef294b8897eaf1ce816ab1538194af6d760c6:timer', 'i:1773789318;', 1773789318),
('laravel_cache_1532a5458aa7e70395bb318830c58e20bf3ec639', 'i:1;', 1773288138),
('laravel_cache_1532a5458aa7e70395bb318830c58e20bf3ec639:timer', 'i:1773288138;', 1773288138),
('laravel_cache_15e0cbfb5b3a02f41387abbba261981067c38d39', 'i:1;', 1773420101),
('laravel_cache_15e0cbfb5b3a02f41387abbba261981067c38d39:timer', 'i:1773420101;', 1773420101),
('laravel_cache_1617de662b6017949f5416a21c1c84bfc97b0955', 'i:1;', 1773293593),
('laravel_cache_1617de662b6017949f5416a21c1c84bfc97b0955:timer', 'i:1773293593;', 1773293593),
('laravel_cache_16548fa22f0c013587b0e81d95aae84009451f45', 'i:1;', 1773756874),
('laravel_cache_16548fa22f0c013587b0e81d95aae84009451f45:timer', 'i:1773756874;', 1773756874),
('laravel_cache_169f317d8762ff4e7c23e72f780ba673d55a2d94', 'i:4;', 1773926879),
('laravel_cache_169f317d8762ff4e7c23e72f780ba673d55a2d94:timer', 'i:1773926879;', 1773926879),
('laravel_cache_18877767f196084bd9921869e745d4fbf22d00e0', 'i:1;', 1773890689),
('laravel_cache_18877767f196084bd9921869e745d4fbf22d00e0:timer', 'i:1773890689;', 1773890689),
('laravel_cache_18d2814c5cc08dd621b0a1ba546a1ed2f9e7eccd', 'i:2;', 1773790968),
('laravel_cache_18d2814c5cc08dd621b0a1ba546a1ed2f9e7eccd:timer', 'i:1773790968;', 1773790968),
('laravel_cache_1ae69a929d19fa6f8ffb41a7b903fa2ad5b0b847', 'i:1;', 1773725356),
('laravel_cache_1ae69a929d19fa6f8ffb41a7b903fa2ad5b0b847:timer', 'i:1773725356;', 1773725356),
('laravel_cache_1afc315d9d4df941474abb910dbe453f41702323', 'i:4;', 1773790934),
('laravel_cache_1afc315d9d4df941474abb910dbe453f41702323:timer', 'i:1773790934;', 1773790934),
('laravel_cache_1b33c1d8994eb0ab8ace01904651f0dac50f86de', 'i:1;', 1773279985),
('laravel_cache_1b33c1d8994eb0ab8ace01904651f0dac50f86de:timer', 'i:1773279985;', 1773279985),
('laravel_cache_1b5ee83b75b55b3e6407f3577ec6737e3eb409b7', 'i:3;', 1772510848),
('laravel_cache_1b5ee83b75b55b3e6407f3577ec6737e3eb409b7:timer', 'i:1772510848;', 1772510848),
('laravel_cache_2060ed236b3d8fb3b299e6126247314efe319b05', 'i:2;', 1773972901),
('laravel_cache_2060ed236b3d8fb3b299e6126247314efe319b05:timer', 'i:1773972901;', 1773972901),
('laravel_cache_20a3c218f32716f6c5557de1679c384160becf34', 'i:1;', 1773829818),
('laravel_cache_20a3c218f32716f6c5557de1679c384160becf34:timer', 'i:1773829818;', 1773829818),
('laravel_cache_259d4d4b6b5ad78e0aa34cd4eecc829ee9ef9c05', 'i:2;', 1773732310),
('laravel_cache_259d4d4b6b5ad78e0aa34cd4eecc829ee9ef9c05:timer', 'i:1773732310;', 1773732310),
('laravel_cache_260e7647ea5119fbf57e4cde8f12e135ca9c8055', 'i:1;', 1773361834),
('laravel_cache_260e7647ea5119fbf57e4cde8f12e135ca9c8055:timer', 'i:1773361834;', 1773361834),
('laravel_cache_28f6aacc583aa4634ea7e10d0181f40edab27d1d', 'i:1;', 1772602761),
('laravel_cache_28f6aacc583aa4634ea7e10d0181f40edab27d1d:timer', 'i:1772602761;', 1772602761),
('laravel_cache_29dfd0d733248524227335639aec09169c415bb8', 'i:2;', 1773213608),
('laravel_cache_29dfd0d733248524227335639aec09169c415bb8:timer', 'i:1773213608;', 1773213608),
('laravel_cache_29ee7b55c48956e06b5f53f14b4c6bf448d16ead', 'i:2;', 1773901217),
('laravel_cache_29ee7b55c48956e06b5f53f14b4c6bf448d16ead:timer', 'i:1773901217;', 1773901217),
('laravel_cache_2c5ce7a32e52729c4df74a8a07b2cd49f57a6b60', 'i:1;', 1773249026),
('laravel_cache_2c5ce7a32e52729c4df74a8a07b2cd49f57a6b60:timer', 'i:1773249026;', 1773249026),
('laravel_cache_2c60059edb70f4db238385276f9364afce1f0cfb', 'i:1;', 1773218429),
('laravel_cache_2c60059edb70f4db238385276f9364afce1f0cfb:timer', 'i:1773218429;', 1773218429),
('laravel_cache_2ca0bfd333af4ac54793360504304c8042486a21', 'i:1;', 1773213295),
('laravel_cache_2ca0bfd333af4ac54793360504304c8042486a21:timer', 'i:1773213295;', 1773213295),
('laravel_cache_2d3c2b3253eb34fc566d1c3fd3018ca191013d03', 'i:1;', 1773276590),
('laravel_cache_2d3c2b3253eb34fc566d1c3fd3018ca191013d03:timer', 'i:1773276590;', 1773276590),
('laravel_cache_2efda97e4a7a2b79afaf562b2bf91881ccf60545', 'i:3;', 1773247762),
('laravel_cache_2efda97e4a7a2b79afaf562b2bf91881ccf60545:timer', 'i:1773247762;', 1773247762),
('laravel_cache_2f465a1cb84080aaa111c582c703789f4f90a654', 'i:1;', 1773887956),
('laravel_cache_2f465a1cb84080aaa111c582c703789f4f90a654:timer', 'i:1773887956;', 1773887956),
('laravel_cache_300122350a32530a2c2103b46ed07eacdc82aff8', 'i:5;', 1772525558),
('laravel_cache_300122350a32530a2c2103b46ed07eacdc82aff8:timer', 'i:1772525558;', 1772525558),
('laravel_cache_30f6761c35d02421aec38fc9912cbfff0ac40415', 'i:1;', 1773195322),
('laravel_cache_30f6761c35d02421aec38fc9912cbfff0ac40415:timer', 'i:1773195322;', 1773195322),
('laravel_cache_31245d76f6412a53e183b74b003cf6416c95078e', 'i:1;', 1773297721),
('laravel_cache_31245d76f6412a53e183b74b003cf6416c95078e:timer', 'i:1773297721;', 1773297721),
('laravel_cache_31bb387c56f40c58f9b82da3ba8130b7c9534723', 'i:1;', 1773748151),
('laravel_cache_31bb387c56f40c58f9b82da3ba8130b7c9534723:timer', 'i:1773748151;', 1773748151),
('laravel_cache_329d47d72c82ff1601131f4164a48d75bff01f72', 'i:2;', 1773725424),
('laravel_cache_329d47d72c82ff1601131f4164a48d75bff01f72:timer', 'i:1773725424;', 1773725424),
('laravel_cache_32c628559b7d6ba293b29333e3e1cfbc91ee725e', 'i:1;', 1773748707),
('laravel_cache_32c628559b7d6ba293b29333e3e1cfbc91ee725e:timer', 'i:1773748707;', 1773748707),
('laravel_cache_32c815cb9d8b6bf735da962308092318a89128c2', 'i:2;', 1773280912),
('laravel_cache_32c815cb9d8b6bf735da962308092318a89128c2:timer', 'i:1773280912;', 1773280912),
('laravel_cache_348d616cce2357783808ed34e1d8c9b5a43f682e', 'i:1;', 1773285995),
('laravel_cache_348d616cce2357783808ed34e1d8c9b5a43f682e:timer', 'i:1773285995;', 1773285995),
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab', 'i:2;', 1773297649),
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1773297649;', 1773297649),
('laravel_cache_369db1b8f9831dd2dca58b3243fb4859862dfe4a', 'i:2;', 1773214760),
('laravel_cache_369db1b8f9831dd2dca58b3243fb4859862dfe4a:timer', 'i:1773214760;', 1773214760),
('laravel_cache_36ac36028526da10153e0771a1c913d41edf7e6e', 'i:1;', 1772895090),
('laravel_cache_36ac36028526da10153e0771a1c913d41edf7e6e:timer', 'i:1772895090;', 1772895090),
('laravel_cache_38dbe43a3218d65a28fcdd723cae82194f9fb15c', 'i:3;', 1774074924),
('laravel_cache_38dbe43a3218d65a28fcdd723cae82194f9fb15c:timer', 'i:1774074924;', 1774074924),
('laravel_cache_397b942466bddbe0e495cad94808c7b08061f627', 'i:1;', 1773074836),
('laravel_cache_397b942466bddbe0e495cad94808c7b08061f627:timer', 'i:1773074836;', 1773074836),
('laravel_cache_3adcd1b9f5e57e446cb843b40f529e2f327b1e01', 'i:2;', 1773799605),
('laravel_cache_3adcd1b9f5e57e446cb843b40f529e2f327b1e01:timer', 'i:1773799605;', 1773799605),
('laravel_cache_3ce74083bedd10e9f3b30ca68d9cc922f1e9e791', 'i:2;', 1772602962),
('laravel_cache_3ce74083bedd10e9f3b30ca68d9cc922f1e9e791:timer', 'i:1772602962;', 1772602962),
('laravel_cache_3d070b99a23bebe79ca213eeec2b487328783833', 'i:6;', 1773205777),
('laravel_cache_3d070b99a23bebe79ca213eeec2b487328783833:timer', 'i:1773205777;', 1773205777),
('laravel_cache_3ead28f890ec0f5b363587e15d61e0b4dca2ee6d', 'i:3;', 1772766747),
('laravel_cache_3ead28f890ec0f5b363587e15d61e0b4dca2ee6d:timer', 'i:1772766747;', 1772766747),
('laravel_cache_3efb4b07bc81155064fafb8f91371fe82b99613b', 'i:1;', 1774044581),
('laravel_cache_3efb4b07bc81155064fafb8f91371fe82b99613b:timer', 'i:1774044581;', 1774044581),
('laravel_cache_3f52f895e262ab2777fcecea27a2540728b8459c', 'i:1;', 1773379950),
('laravel_cache_3f52f895e262ab2777fcecea27a2540728b8459c:timer', 'i:1773379950;', 1773379950),
('laravel_cache_3f8e3568ca2baba7fd1ac8bfebd16b1cf3f7c01d', 'i:2;', 1773285922),
('laravel_cache_3f8e3568ca2baba7fd1ac8bfebd16b1cf3f7c01d:timer', 'i:1773285922;', 1773285922),
('laravel_cache_41158a00d4d0ec2b2928cd82a03d84399475e701', 'i:3;', 1773218264),
('laravel_cache_41158a00d4d0ec2b2928cd82a03d84399475e701:timer', 'i:1773218264;', 1773218264),
('laravel_cache_415515f2a359ae25b0a83fc3d466b4f351bd7bd0', 'i:3;', 1773247631),
('laravel_cache_415515f2a359ae25b0a83fc3d466b4f351bd7bd0:timer', 'i:1773247631;', 1773247631),
('laravel_cache_4579735dd6601b15600c2579cbf279ffabf4431c', 'i:1;', 1773887957),
('laravel_cache_4579735dd6601b15600c2579cbf279ffabf4431c:timer', 'i:1773887957;', 1773887957),
('laravel_cache_463bc541e6e3f1568774144c71b685f80b01eb3e', 'i:1;', 1773280712),
('laravel_cache_463bc541e6e3f1568774144c71b685f80b01eb3e:timer', 'i:1773280712;', 1773280712),
('laravel_cache_47865009b7d67c26c78bb97fda6ff4c477c893f1', 'i:1;', 1773919050),
('laravel_cache_47865009b7d67c26c78bb97fda6ff4c477c893f1:timer', 'i:1773919050;', 1773919050),
('laravel_cache_479a2bc68555dca2f9a6cf21ce6e4fef6ce5c6b0', 'i:1;', 1773288416),
('laravel_cache_479a2bc68555dca2f9a6cf21ce6e4fef6ce5c6b0:timer', 'i:1773288416;', 1773288416),
('laravel_cache_499d5cd7365605fbb4ad7feec0769c34be4e3cf5', 'i:2;', 1772766741),
('laravel_cache_499d5cd7365605fbb4ad7feec0769c34be4e3cf5:timer', 'i:1772766741;', 1772766741),
('laravel_cache_4c27b17452957557e9ed5dee828004a79f02ad4a', 'i:5;', 1773197990),
('laravel_cache_4c27b17452957557e9ed5dee828004a79f02ad4a:timer', 'i:1773197990;', 1773197990),
('laravel_cache_4d5ee75f40bd4ca7b73f1af26f989bfb7f2208ec', 'i:2;', 1773213130),
('laravel_cache_4d5ee75f40bd4ca7b73f1af26f989bfb7f2208ec:timer', 'i:1773213130;', 1773213130),
('laravel_cache_4e3cf31825592ef79da19282ee821534b99292ea', 'i:1;', 1773072607),
('laravel_cache_4e3cf31825592ef79da19282ee821534b99292ea:timer', 'i:1773072607;', 1773072607),
('laravel_cache_4e720ccdeb37d32c729f8600e8960438473e51f5', 'i:1;', 1772853134),
('laravel_cache_4e720ccdeb37d32c729f8600e8960438473e51f5:timer', 'i:1772853134;', 1772853134),
('laravel_cache_4f642f2c385f549f6970c67622e0877331b3878c', 'i:1;', 1773279843),
('laravel_cache_4f642f2c385f549f6970c67622e0877331b3878c:timer', 'i:1773279843;', 1773279843),
('laravel_cache_5396558c04020c7d5829282c8c655b79eba8c85a', 'i:1;', 1773213159),
('laravel_cache_5396558c04020c7d5829282c8c655b79eba8c85a:timer', 'i:1773213159;', 1773213159),
('laravel_cache_55c44dac43b7bc84447ca48dfd6c558c4420fa62', 'i:5;', 1773158649),
('laravel_cache_55c44dac43b7bc84447ca48dfd6c558c4420fa62:timer', 'i:1773158649;', 1773158649),
('laravel_cache_576d17850d5781e516b77b5da37bfc4f76116ae1', 'i:1;', 1773112938),
('laravel_cache_576d17850d5781e516b77b5da37bfc4f76116ae1:timer', 'i:1773112938;', 1773112938),
('laravel_cache_57e09250865e1806bb66b655ac6312f9988aa7d4', 'i:2;', 1773214426),
('laravel_cache_57e09250865e1806bb66b655ac6312f9988aa7d4:timer', 'i:1773214426;', 1773214426),
('laravel_cache_5843264ef1aef6a62b73123c9cd79d848220ce53', 'i:2;', 1773584043),
('laravel_cache_5843264ef1aef6a62b73123c9cd79d848220ce53:timer', 'i:1773584043;', 1773584043),
('laravel_cache_58562fb2db9f0d4c8bc86a123eeab41cc49eb6b1', 'i:3;', 1773883367),
('laravel_cache_58562fb2db9f0d4c8bc86a123eeab41cc49eb6b1:timer', 'i:1773883367;', 1773883367),
('laravel_cache_59a459c21fe854393fc86ce33a81dd805fa5535d', 'i:1;', 1773573745),
('laravel_cache_59a459c21fe854393fc86ce33a81dd805fa5535d:timer', 'i:1773573745;', 1773573745),
('laravel_cache_5a2568c2befef506adc3d9708459ec74e8b8d545', 'i:1;', 1774077490),
('laravel_cache_5a2568c2befef506adc3d9708459ec74e8b8d545:timer', 'i:1774077490;', 1774077490),
('laravel_cache_5addd6db7046cde6b175e950695c26819cf3dc45', 'i:2;', 1772595343),
('laravel_cache_5addd6db7046cde6b175e950695c26819cf3dc45:timer', 'i:1772595343;', 1772595343),
('laravel_cache_5b2184a2a0091ab43fbbfef1160f78d9104f03a8', 'i:1;', 1773048085),
('laravel_cache_5b2184a2a0091ab43fbbfef1160f78d9104f03a8:timer', 'i:1773048085;', 1773048085),
('laravel_cache_5c22eb27157f35c2053b5b6e770b81b095b92639', 'i:1;', 1773157956),
('laravel_cache_5c22eb27157f35c2053b5b6e770b81b095b92639:timer', 'i:1773157956;', 1773157956),
('laravel_cache_5c7cc02a773afe40988657aa6e003ceaf04ba026', 'i:1;', 1773285472),
('laravel_cache_5c7cc02a773afe40988657aa6e003ceaf04ba026:timer', 'i:1773285472;', 1773285472),
('laravel_cache_5edbf13e2201592160afd2498cf64aa8c18e414a', 'i:1;', 1773830145),
('laravel_cache_5edbf13e2201592160afd2498cf64aa8c18e414a:timer', 'i:1773830145;', 1773830145),
('laravel_cache_5fb73bc90df0f63a179e9b7d47e3a572f2cfe700', 'i:1;', 1773290833),
('laravel_cache_5fb73bc90df0f63a179e9b7d47e3a572f2cfe700:timer', 'i:1773290833;', 1773290833),
('laravel_cache_61575f30cb98747eea197c3bd9e7923f2182f7d8', 'i:1;', 1773539026),
('laravel_cache_61575f30cb98747eea197c3bd9e7923f2182f7d8:timer', 'i:1773539026;', 1773539026),
('laravel_cache_629f8071747f0be6e8b01ccd6dd79c5637043002', 'i:1;', 1773288387),
('laravel_cache_629f8071747f0be6e8b01ccd6dd79c5637043002:timer', 'i:1773288387;', 1773288387),
('laravel_cache_6465302c846f0e3aab248f78b8bb90c0fe922f63', 'i:1;', 1773968836),
('laravel_cache_6465302c846f0e3aab248f78b8bb90c0fe922f63:timer', 'i:1773968836;', 1773968836),
('laravel_cache_6566503b12f60e931d81ce9866949e4af6949f80', 'i:1;', 1773769117),
('laravel_cache_6566503b12f60e931d81ce9866949e4af6949f80:timer', 'i:1773769117;', 1773769117),
('laravel_cache_65f944ba30aa56448a2cfa4ece0e0b836c838ed9', 'i:1;', 1773285715),
('laravel_cache_65f944ba30aa56448a2cfa4ece0e0b836c838ed9:timer', 'i:1773285715;', 1773285715),
('laravel_cache_6659c3d6a3537ee3d2d4c1514267b49234d99a6d', 'i:1;', 1773938763),
('laravel_cache_6659c3d6a3537ee3d2d4c1514267b49234d99a6d:timer', 'i:1773938763;', 1773938763),
('laravel_cache_66b6251ad9129a0830ec8e32351ace78fe59c8bc', 'i:6;', 1773247653),
('laravel_cache_66b6251ad9129a0830ec8e32351ace78fe59c8bc:timer', 'i:1773247653;', 1773247653),
('laravel_cache_6841bf54f9d0530b852707d8273311f821d3dafd', 'i:1;', 1773860688),
('laravel_cache_6841bf54f9d0530b852707d8273311f821d3dafd:timer', 'i:1773860688;', 1773860688),
('laravel_cache_6b3cc77fb6c7ecd55bfa1c74ba1b87cf21b9ee96', 'i:2;', 1773113928),
('laravel_cache_6b3cc77fb6c7ecd55bfa1c74ba1b87cf21b9ee96:timer', 'i:1773113928;', 1773113928),
('laravel_cache_6b7c944e2b6686f1e00c59d70ef1053baa62aca7', 'i:2;', 1773556879),
('laravel_cache_6b7c944e2b6686f1e00c59d70ef1053baa62aca7:timer', 'i:1773556879;', 1773556879),
('laravel_cache_6bcfea086029c9dc35b1dc192c687a90a8b49073', 'i:2;', 1773790934),
('laravel_cache_6bcfea086029c9dc35b1dc192c687a90a8b49073:timer', 'i:1773790934;', 1773790934),
('laravel_cache_6bf56d1132147e8bffb8c4cac9c48416a6972c77', 'i:1;', 1773186068),
('laravel_cache_6bf56d1132147e8bffb8c4cac9c48416a6972c77:timer', 'i:1773186068;', 1773186068),
('laravel_cache_6c8712f6bff1188cbad03b4bfab789a5e812ecc4', 'i:1;', 1773285995),
('laravel_cache_6c8712f6bff1188cbad03b4bfab789a5e812ecc4:timer', 'i:1773285995;', 1773285995),
('laravel_cache_6d5db0e809f71a43d3bada01e4c1c4d4b501b435', 'i:9;', 1773297783),
('laravel_cache_6d5db0e809f71a43d3bada01e4c1c4d4b501b435:timer', 'i:1773297783;', 1773297783),
('laravel_cache_6ea0175baa4e36d76aa0c9854ee680ec1f425add', 'i:1;', 1773213607),
('laravel_cache_6ea0175baa4e36d76aa0c9854ee680ec1f425add:timer', 'i:1773213607;', 1773213607),
('laravel_cache_7110e0d3f236986f20f4297a48a536d8fac5c411', 'i:1;', 1772526845),
('laravel_cache_7110e0d3f236986f20f4297a48a536d8fac5c411:timer', 'i:1772526845;', 1772526845),
('laravel_cache_73a5e508dc515348f2645ea7a4813b0b992fa365', 'i:1;', 1774021576),
('laravel_cache_73a5e508dc515348f2645ea7a4813b0b992fa365:timer', 'i:1774021576;', 1774021576),
('laravel_cache_73f6505d33a4cf760cd0fa3d59a834d6ff00083c', 'i:1;', 1773926882),
('laravel_cache_73f6505d33a4cf760cd0fa3d59a834d6ff00083c:timer', 'i:1773926882;', 1773926882),
('laravel_cache_75681739a857113cf449f21f2967f1b016d2dea1', 'i:1;', 1773629727),
('laravel_cache_75681739a857113cf449f21f2967f1b016d2dea1:timer', 'i:1773629727;', 1773629727),
('laravel_cache_75ab321ea3ab33c0b2f948239b42dc970522ad3d', 'i:1;', 1773285395),
('laravel_cache_75ab321ea3ab33c0b2f948239b42dc970522ad3d:timer', 'i:1773285395;', 1773285395),
('laravel_cache_762ec4c945c2e8c793b2882ae7f0d11d07d49e60', 'i:1;', 1773878202),
('laravel_cache_762ec4c945c2e8c793b2882ae7f0d11d07d49e60:timer', 'i:1773878202;', 1773878202),
('laravel_cache_77c7b1be6926e0c896d8dab69cb6ef8918780ea9', 'i:3;', 1773471525),
('laravel_cache_77c7b1be6926e0c896d8dab69cb6ef8918780ea9:timer', 'i:1773471525;', 1773471525),
('laravel_cache_784582eeab5dbd4a1a6fa81c4b201071e36d837c', 'i:2;', 1773658304),
('laravel_cache_784582eeab5dbd4a1a6fa81c4b201071e36d837c:timer', 'i:1773658304;', 1773658304),
('laravel_cache_7876fe199a7b94c6c78d5dd3ef38709562adffe6', 'i:1;', 1773624639),
('laravel_cache_7876fe199a7b94c6c78d5dd3ef38709562adffe6:timer', 'i:1773624639;', 1773624639),
('laravel_cache_7947d67012a9c3740f0e513f4732b1472d430d74', 'i:1;', 1773790934),
('laravel_cache_7947d67012a9c3740f0e513f4732b1472d430d74:timer', 'i:1773790934;', 1773790934),
('laravel_cache_7968577886188015a8f17fe137af4ea31788c4d2', 'i:1;', 1773230288),
('laravel_cache_7968577886188015a8f17fe137af4ea31788c4d2:timer', 'i:1773230288;', 1773230288),
('laravel_cache_7a1db6a5b086a3b5934a0b1eaa13ffcfaaa4366c', 'i:1;', 1773903669),
('laravel_cache_7a1db6a5b086a3b5934a0b1eaa13ffcfaaa4366c:timer', 'i:1773903669;', 1773903669),
('laravel_cache_7a98eaa97a2de41bcef8f4918bfbd4ecbdf1a005', 'i:1;', 1773216301),
('laravel_cache_7a98eaa97a2de41bcef8f4918bfbd4ecbdf1a005:timer', 'i:1773216301;', 1773216301),
('laravel_cache_7ad344617985acc633fdf2178a64f4ef7ba6d990', 'i:1;', 1773252106),
('laravel_cache_7ad344617985acc633fdf2178a64f4ef7ba6d990:timer', 'i:1773252106;', 1773252106),
('laravel_cache_7b4a8e9b93cbc36c445cddb9c8f65cf792ea5316', 'i:1;', 1773790969),
('laravel_cache_7b4a8e9b93cbc36c445cddb9c8f65cf792ea5316:timer', 'i:1773790969;', 1773790969),
('laravel_cache_7bfcada3b04c84e4de9e650ad07104487454e924', 'i:2;', 1773121006),
('laravel_cache_7bfcada3b04c84e4de9e650ad07104487454e924:timer', 'i:1773121006;', 1773121006),
('laravel_cache_806638aac02b7e6e55672010bf05b09cf3c8de95', 'i:1;', 1773162917),
('laravel_cache_806638aac02b7e6e55672010bf05b09cf3c8de95:timer', 'i:1773162917;', 1773162917),
('laravel_cache_81175529f64d3865f2f560e9d5ae08c762a8ed35', 'i:1;', 1773279843),
('laravel_cache_81175529f64d3865f2f560e9d5ae08c762a8ed35:timer', 'i:1773279843;', 1773279843),
('laravel_cache_81e0192e7062ebbd91b8ba0ec5d909e1bb5e4e13', 'i:2;', 1773722012),
('laravel_cache_81e0192e7062ebbd91b8ba0ec5d909e1bb5e4e13:timer', 'i:1773722012;', 1773722012),
('laravel_cache_83ead8fe64a041034fee3bf90aee427afe0bd1e6', 'i:1;', 1773113873),
('laravel_cache_83ead8fe64a041034fee3bf90aee427afe0bd1e6:timer', 'i:1773113873;', 1773113873),
('laravel_cache_85973681901926426e78fbb4e0e5947281f0812a', 'i:1;', 1773740755),
('laravel_cache_85973681901926426e78fbb4e0e5947281f0812a:timer', 'i:1773740755;', 1773740755),
('laravel_cache_85a16e106f85edf4b1ac392608bec69dffae8c53', 'i:1;', 1773288546),
('laravel_cache_85a16e106f85edf4b1ac392608bec69dffae8c53:timer', 'i:1773288546;', 1773288546),
('laravel_cache_876aaf79845a1f1329210532f7c459956287eee5', 'i:3;', 1773072553),
('laravel_cache_876aaf79845a1f1329210532f7c459956287eee5:timer', 'i:1773072553;', 1773072553),
('laravel_cache_892fe677d0fefe6adc0e988fbfd272f09e8e7dc6', 'i:2;', 1773287921),
('laravel_cache_892fe677d0fefe6adc0e988fbfd272f09e8e7dc6:timer', 'i:1773287921;', 1773287921),
('laravel_cache_8985392bc4eaa4911c5d199baf8dd247f36ae66d', 'i:1;', 1773996171),
('laravel_cache_8985392bc4eaa4911c5d199baf8dd247f36ae66d:timer', 'i:1773996171;', 1773996171),
('laravel_cache_89d109630393abff46498f1f783e4591fd1dc4fe', 'i:2;', 1773117860),
('laravel_cache_89d109630393abff46498f1f783e4591fd1dc4fe:timer', 'i:1773117860;', 1773117860),
('laravel_cache_8a8d56b11ef3b3122e095b23cdcb5b4c6eae03a6', 'i:1;', 1773286639),
('laravel_cache_8a8d56b11ef3b3122e095b23cdcb5b4c6eae03a6:timer', 'i:1773286639;', 1773286639),
('laravel_cache_8ac05599790dbf911e1ca059179283226172627c', 'i:1;', 1773072590),
('laravel_cache_8ac05599790dbf911e1ca059179283226172627c:timer', 'i:1773072590;', 1773072590),
('laravel_cache_8babb16b46d7d836c1bde976b2f98bc698b98282', 'i:1;', 1773718158),
('laravel_cache_8babb16b46d7d836c1bde976b2f98bc698b98282:timer', 'i:1773718158;', 1773718158),
('laravel_cache_8d677dcc9869bb030efa87e5858de64ae35d3ad7', 'i:2;', 1772512140),
('laravel_cache_8d677dcc9869bb030efa87e5858de64ae35d3ad7:timer', 'i:1772512140;', 1772512140),
('laravel_cache_8dca0626d362fce467d12c778d6b0bbe646ebc32', 'i:1;', 1773728557),
('laravel_cache_8dca0626d362fce467d12c778d6b0bbe646ebc32:timer', 'i:1773728557;', 1773728557),
('laravel_cache_901f7e38ccdae80e3e41ded92dc2c306abbb666b', 'i:2;', 1773287779),
('laravel_cache_901f7e38ccdae80e3e41ded92dc2c306abbb666b:timer', 'i:1773287779;', 1773287779),
('laravel_cache_90405d0a3ca5bbb311adface06b2fede30d7e90a', 'i:1;', 1772857445),
('laravel_cache_90405d0a3ca5bbb311adface06b2fede30d7e90a:timer', 'i:1772857445;', 1772857445),
('laravel_cache_90a193f1339b1cab7234bcbc07628134c9ce6f76', 'i:3;', 1773205778),
('laravel_cache_90a193f1339b1cab7234bcbc07628134c9ce6f76:timer', 'i:1773205778;', 1773205778),
('laravel_cache_90fcf533a0f312e919d63c31a7025d1587e3c2ae', 'i:1;', 1773132182),
('laravel_cache_90fcf533a0f312e919d63c31a7025d1587e3c2ae:timer', 'i:1773132182;', 1773132182),
('laravel_cache_91617010483c478bf56ad1962f0a5b9e01a9619e', 'i:1;', 1773728559),
('laravel_cache_91617010483c478bf56ad1962f0a5b9e01a9619e:timer', 'i:1773728559;', 1773728559),
('laravel_cache_930163e7b3fd3f6e332c007eedaf2e3faf75db32', 'i:21;', 1773075024),
('laravel_cache_930163e7b3fd3f6e332c007eedaf2e3faf75db32:timer', 'i:1773075024;', 1773075024),
('laravel_cache_96081891720a6540da920339cc7ccda13cfd0a94', 'i:1;', 1773629729),
('laravel_cache_96081891720a6540da920339cc7ccda13cfd0a94:timer', 'i:1773629729;', 1773629729),
('laravel_cache_978377c2f51a6af498830b4ab0ceb43269b1ad15', 'i:1;', 1773047791),
('laravel_cache_978377c2f51a6af498830b4ab0ceb43269b1ad15:timer', 'i:1773047791;', 1773047791),
('laravel_cache_98e1780e3189985c87c4f2e8799143fc9abef74a', 'i:1;', 1773162904),
('laravel_cache_98e1780e3189985c87c4f2e8799143fc9abef74a:timer', 'i:1773162904;', 1773162904),
('laravel_cache_98f92bf9d7ea6084ba218d57e72bff83108a8688', 'i:3;', 1773976497),
('laravel_cache_98f92bf9d7ea6084ba218d57e72bff83108a8688:timer', 'i:1773976497;', 1773976497),
('laravel_cache_9911aafcb43408fe48605bf3ede0d0606de9cb24', 'i:1;', 1773721256),
('laravel_cache_9911aafcb43408fe48605bf3ede0d0606de9cb24:timer', 'i:1773721256;', 1773721256),
('laravel_cache_99d263068ba2e855f7d6d6adf460f5fe5c0b23ca', 'i:2;', 1773293595),
('laravel_cache_99d263068ba2e855f7d6d6adf460f5fe5c0b23ca:timer', 'i:1773293595;', 1773293595),
('laravel_cache_9abde4be916b9adc1bfefe14ce037c6e5711cc1b', 'i:2;', 1773218265),
('laravel_cache_9abde4be916b9adc1bfefe14ce037c6e5711cc1b:timer', 'i:1773218265;', 1773218265),
('laravel_cache_9b8baf4effe4edd684fb7c3289e83a440fba64ba', 'i:1;', 1773248928),
('laravel_cache_9b8baf4effe4edd684fb7c3289e83a440fba64ba:timer', 'i:1773248928;', 1773248928),
('laravel_cache_9ea58adc8f0942ba26a446f4c77b59ad8dec7932', 'i:1;', 1773213955),
('laravel_cache_9ea58adc8f0942ba26a446f4c77b59ad8dec7932:timer', 'i:1773213955;', 1773213955),
('laravel_cache_9fe36e993bb561ad08df0230d8fb324e0f252a26', 'i:3;', 1773725045),
('laravel_cache_9fe36e993bb561ad08df0230d8fb324e0f252a26:timer', 'i:1773725045;', 1773725045),
('laravel_cache_a15124a0271e04a506adb3af44ae8d8546c3e4c8', 'i:1;', 1773832528),
('laravel_cache_a15124a0271e04a506adb3af44ae8d8546c3e4c8:timer', 'i:1773832528;', 1773832528),
('laravel_cache_a269e5d9fd9c6cda9c58a8b1b8cabac1b31792ce', 'i:1;', 1772511770),
('laravel_cache_a269e5d9fd9c6cda9c58a8b1b8cabac1b31792ce:timer', 'i:1772511770;', 1772511770),
('laravel_cache_a3d75079f1a8c25629c62e5e76e43ae4878a5b18', 'i:2;', 1773213646),
('laravel_cache_a3d75079f1a8c25629c62e5e76e43ae4878a5b18:timer', 'i:1773213646;', 1773213646),
('laravel_cache_a4c19431e24f50e30ea7ebefea8975fa658af256', 'i:1;', 1773925646),
('laravel_cache_a4c19431e24f50e30ea7ebefea8975fa658af256:timer', 'i:1773925646;', 1773925646),
('laravel_cache_a85070a460c6b947ccca30158218a91615066689', 'i:1;', 1774035318),
('laravel_cache_a85070a460c6b947ccca30158218a91615066689:timer', 'i:1774035318;', 1774035318),
('laravel_cache_aa3ef7ff3894dba212e38ab0c27b0af53f0b4052', 'i:1;', 1773756873),
('laravel_cache_aa3ef7ff3894dba212e38ab0c27b0af53f0b4052:timer', 'i:1773756873;', 1773756873),
('laravel_cache_aa5e0de76197ec6f15f56045826a4ae68fb7c178', 'i:2;', 1773815996),
('laravel_cache_aa5e0de76197ec6f15f56045826a4ae68fb7c178:timer', 'i:1773815996;', 1773815996),
('laravel_cache_aa9db2c1a387fa29ad5e93f7bce304e7d2bb4748', 'i:1;', 1773739450),
('laravel_cache_aa9db2c1a387fa29ad5e93f7bce304e7d2bb4748:timer', 'i:1773739450;', 1773739450),
('laravel_cache_ac1e2dfb90196b8e7a6faa54450a35b6f098df03', 'i:1;', 1774060474),
('laravel_cache_ac1e2dfb90196b8e7a6faa54450a35b6f098df03:timer', 'i:1774060474;', 1774060474),
('laravel_cache_ac61ab0d927b5874f8666234d1ac55d1715b0f69', 'i:1;', 1773195108),
('laravel_cache_ac61ab0d927b5874f8666234d1ac55d1715b0f69:timer', 'i:1773195108;', 1773195108),
('laravel_cache_ac9bfa6996ed37ae40800b11657b1df49a853ce9', 'i:1;', 1773048085),
('laravel_cache_ac9bfa6996ed37ae40800b11657b1df49a853ce9:timer', 'i:1773048085;', 1773048085),
('laravel_cache_acb8dcadfbe86f37d1c78d2a245f86226dc6a50d', 'i:1;', 1774015507),
('laravel_cache_acb8dcadfbe86f37d1c78d2a245f86226dc6a50d:timer', 'i:1774015507;', 1774015507),
('laravel_cache_ae80af3df8a6b71edf7ff07ecf6522dce8c00893', 'i:2;', 1773279702),
('laravel_cache_ae80af3df8a6b71edf7ff07ecf6522dce8c00893:timer', 'i:1773279702;', 1773279702),
('laravel_cache_aecee6ca27d05bf49285adb3422bd62efaa7973d', 'i:1;', 1773197532),
('laravel_cache_aecee6ca27d05bf49285adb3422bd62efaa7973d:timer', 'i:1773197532;', 1773197532),
('laravel_cache_b1803ec00cfdd4b80592a9ef80172e87066472f3', 'i:3;', 1773959003),
('laravel_cache_b1803ec00cfdd4b80592a9ef80172e87066472f3:timer', 'i:1773959003;', 1773959003),
('laravel_cache_b1b251ad6b140a5534112938e40b3114426ff28f', 'i:4;', 1773728558),
('laravel_cache_b1b251ad6b140a5534112938e40b3114426ff28f:timer', 'i:1773728558;', 1773728558),
('laravel_cache_b250e878815c81a54d9a4708e9884f699fa3e08c', 'i:1;', 1773217982),
('laravel_cache_b250e878815c81a54d9a4708e9884f699fa3e08c:timer', 'i:1773217982;', 1773217982),
('laravel_cache_b25ee2c51fb6730c082d402cce0002e0a3dee40a', 'i:1;', 1773279896),
('laravel_cache_b25ee2c51fb6730c082d402cce0002e0a3dee40a:timer', 'i:1773279896;', 1773279896),
('laravel_cache_b274496a2f043f13f2cf81d310bdbcb373cc0021', 'i:1;', 1774028434),
('laravel_cache_b274496a2f043f13f2cf81d310bdbcb373cc0021:timer', 'i:1774028434;', 1774028434),
('laravel_cache_b38f94b565243b7254dfc615e579fb8fcab6d65f', 'i:1;', 1772607553),
('laravel_cache_b38f94b565243b7254dfc615e579fb8fcab6d65f:timer', 'i:1772607553;', 1772607553),
('laravel_cache_b40b4601202aba23607319f700042b5ffbda0c87', 'i:2;', 1773573742),
('laravel_cache_b40b4601202aba23607319f700042b5ffbda0c87:timer', 'i:1773573742;', 1773573742),
('laravel_cache_b4de140e6e6d6cda512ccc44c25d602439d07677', 'i:1;', 1773285597),
('laravel_cache_b4de140e6e6d6cda512ccc44c25d602439d07677:timer', 'i:1773285597;', 1773285597),
('laravel_cache_b5eae3104975c1f64dfe423009870ca55e41a6fb', 'i:4;', 1773756873),
('laravel_cache_b5eae3104975c1f64dfe423009870ca55e41a6fb:timer', 'i:1773756873;', 1773756873),
('laravel_cache_b8585645a73fd750e2b9cf1487c3c142f8b890d2', 'i:1;', 1773742661),
('laravel_cache_b8585645a73fd750e2b9cf1487c3c142f8b890d2:timer', 'i:1773742661;', 1773742661),
('laravel_cache_ba14ad8fb4563bfb9286333cde04eccfd7d12a83', 'i:1;', 1773706408),
('laravel_cache_ba14ad8fb4563bfb9286333cde04eccfd7d12a83:timer', 'i:1773706408;', 1773706408),
('laravel_cache_bad67f9e9ad6af2375b5bb20c9cd23aef378b486', 'i:1;', 1773810197),
('laravel_cache_bad67f9e9ad6af2375b5bb20c9cd23aef378b486:timer', 'i:1773810197;', 1773810197),
('laravel_cache_bbb5a99f1a27224246a6af72d161ff31004e4337', 'i:1;', 1773718558),
('laravel_cache_bbb5a99f1a27224246a6af72d161ff31004e4337:timer', 'i:1773718558;', 1773718558),
('laravel_cache_bda086fbeb02707e82eecf0f903fcdc0b6984d3d', 'i:1;', 1773213904),
('laravel_cache_bda086fbeb02707e82eecf0f903fcdc0b6984d3d:timer', 'i:1773213904;', 1773213904),
('laravel_cache_bdaa6459cdd0bffbb4dbaae25797e789d19bc5fe', 'i:4;', 1773725354),
('laravel_cache_bdaa6459cdd0bffbb4dbaae25797e789d19bc5fe:timer', 'i:1773725354;', 1773725354),
('laravel_cache_bde875d363a4d3481f9e471c897d7be1c7d4dff1', 'i:1;', 1773786996),
('laravel_cache_bde875d363a4d3481f9e471c897d7be1c7d4dff1:timer', 'i:1773786996;', 1773786996),
('laravel_cache_c1cb30d4eb81da881ee243e45d763effedf722bd', 'i:2;', 1772512430),
('laravel_cache_c1cb30d4eb81da881ee243e45d763effedf722bd:timer', 'i:1772512430;', 1772512430),
('laravel_cache_c2aeba44dffdd129e67b8dcb43819a576bc70522', 'i:2;', 1773843475),
('laravel_cache_c2aeba44dffdd129e67b8dcb43819a576bc70522:timer', 'i:1773843475;', 1773843475),
('laravel_cache_c2c1e936e315cf3d9f1b5b5be56954a2fdc62399', 'i:2;', 1773288543),
('laravel_cache_c2c1e936e315cf3d9f1b5b5be56954a2fdc62399:timer', 'i:1773288543;', 1773288543),
('laravel_cache_c325611cf05a9065b1bcde3b37a0c24f027a4fab', 'i:2;', 1773756874),
('laravel_cache_c325611cf05a9065b1bcde3b37a0c24f027a4fab:timer', 'i:1773756874;', 1773756874),
('laravel_cache_c4bfb4708230c93e036ae44a17cc6bf3852aa8b8', 'i:1;', 1772895232),
('laravel_cache_c4bfb4708230c93e036ae44a17cc6bf3852aa8b8:timer', 'i:1772895232;', 1772895232),
('laravel_cache_c5dc98a61ce7b190df46e3b217d6604551d55254', 'i:1;', 1773857905),
('laravel_cache_c5dc98a61ce7b190df46e3b217d6604551d55254:timer', 'i:1773857905;', 1773857905),
('laravel_cache_c6cf9ce994c9e08c589ffc8212d847db60e9428a', 'i:1;', 1773195166),
('laravel_cache_c6cf9ce994c9e08c589ffc8212d847db60e9428a:timer', 'i:1773195166;', 1773195166),
('laravel_cache_c6d912446ecf92bfeb78195e5f8ba22d671ac05c', 'i:1;', 1773217981),
('laravel_cache_c6d912446ecf92bfeb78195e5f8ba22d671ac05c:timer', 'i:1773217981;', 1773217981),
('laravel_cache_c6de9c22d2060cafaf156dca0a81314bf6d30438', 'i:1;', 1773917135),
('laravel_cache_c6de9c22d2060cafaf156dca0a81314bf6d30438:timer', 'i:1773917135;', 1773917135),
('laravel_cache_c99a38ab45cffd09e4ab755daecd7b916e848703', 'i:1;', 1773732309),
('laravel_cache_c99a38ab45cffd09e4ab755daecd7b916e848703:timer', 'i:1773732309;', 1773732309),
('laravel_cache_cb0b77f954a3dedc9d7f12637a3b3fcdabfdb57e', 'i:1;', 1773114989),
('laravel_cache_cb0b77f954a3dedc9d7f12637a3b3fcdabfdb57e:timer', 'i:1773114989;', 1773114989),
('laravel_cache_cb5a241c2ba259fceae722fe132d0ece3bb4b547', 'i:1;', 1773995838),
('laravel_cache_cb5a241c2ba259fceae722fe132d0ece3bb4b547:timer', 'i:1773995838;', 1773995838),
('laravel_cache_cb9744f629e395620a4b591e8bacfaef3bff6fb5', 'i:1;', 1774031425),
('laravel_cache_cb9744f629e395620a4b591e8bacfaef3bff6fb5:timer', 'i:1774031425;', 1774031425),
('laravel_cache_cbf607c9ddcec68746614d531668e420a3d1223f', 'i:1;', 1773843473),
('laravel_cache_cbf607c9ddcec68746614d531668e420a3d1223f:timer', 'i:1773843473;', 1773843473),
('laravel_cache_cc08b95a618281e48d53a8cba1aff177ac50c298', 'i:1;', 1773188641),
('laravel_cache_cc08b95a618281e48d53a8cba1aff177ac50c298:timer', 'i:1773188641;', 1773188641),
('laravel_cache_cc2f3cb68cfdfd6c80d023cb3d0d184ef6d8a9d6', 'i:1;', 1773276886),
('laravel_cache_cc2f3cb68cfdfd6c80d023cb3d0d184ef6d8a9d6:timer', 'i:1773276886;', 1773276886),
('laravel_cache_cc8c154bdfe7ebf27ba367e9d3777a078348a76d', 'i:3;', 1773288185),
('laravel_cache_cc8c154bdfe7ebf27ba367e9d3777a078348a76d:timer', 'i:1773288185;', 1773288185),
('laravel_cache_cc95e6d89ed1e1b4470593a43de472dc3e850606', 'i:1;', 1773158696),
('laravel_cache_cc95e6d89ed1e1b4470593a43de472dc3e850606:timer', 'i:1773158696;', 1773158696),
('laravel_cache_cd2e7aea986df4599fbe3936ffc13579457415ea', 'i:1;', 1774048699),
('laravel_cache_cd2e7aea986df4599fbe3936ffc13579457415ea:timer', 'i:1774048699;', 1774048699),
('laravel_cache_ce196b56a50fe5eb7f2a7b4b9087ecbbcb231926', 'i:2;', 1773230291),
('laravel_cache_ce196b56a50fe5eb7f2a7b4b9087ecbbcb231926:timer', 'i:1773230291;', 1773230291),
('laravel_cache_cf2f395f6494c21a77c931163a4be6f992ccfec2', 'i:3;', 1773725412),
('laravel_cache_cf2f395f6494c21a77c931163a4be6f992ccfec2:timer', 'i:1773725412;', 1773725412),
('laravel_cache_cf88327d243f0305cc1183eabefeef6b8c1de50c', 'i:3;', 1773241839),
('laravel_cache_cf88327d243f0305cc1183eabefeef6b8c1de50c:timer', 'i:1773241838;', 1773241839),
('laravel_cache_d0dc890b95118296d206f607f166e032a586f5a0', 'i:1;', 1773214138),
('laravel_cache_d0dc890b95118296d206f607f166e032a586f5a0:timer', 'i:1773214138;', 1773214138),
('laravel_cache_d19e82c939a082f692089e0e7c2b2ecbff33b670', 'i:1;', 1773072551),
('laravel_cache_d19e82c939a082f692089e0e7c2b2ecbff33b670:timer', 'i:1773072551;', 1773072551),
('laravel_cache_d4c36c8c79b7a4011f640d974aa5635f99f222ab', 'i:1;', 1773854609),
('laravel_cache_d4c36c8c79b7a4011f640d974aa5635f99f222ab:timer', 'i:1773854609;', 1773854609),
('laravel_cache_d5372c191037afc2a974fc88d6fbf927f8b95893', 'i:1;', 1773689797),
('laravel_cache_d5372c191037afc2a974fc88d6fbf927f8b95893:timer', 'i:1773689797;', 1773689797),
('laravel_cache_d5d89169802ad9c512ab5412a6da41ef2a2d9b2e', 'i:4;', 1773213296),
('laravel_cache_d5d89169802ad9c512ab5412a6da41ef2a2d9b2e:timer', 'i:1773213296;', 1773213296),
('laravel_cache_d6c10ba0436be6270fce0e46fa7cea5ca9c88369', 'i:1;', 1773923702),
('laravel_cache_d6c10ba0436be6270fce0e46fa7cea5ca9c88369:timer', 'i:1773923702;', 1773923702),
('laravel_cache_d75d9596c135aac67f1dbfb3c28f96ad3d25d0fd', 'i:1;', 1773725362),
('laravel_cache_d75d9596c135aac67f1dbfb3c28f96ad3d25d0fd:timer', 'i:1773725362;', 1773725362),
('laravel_cache_d7a5080b66f3e67fa8f30b025e7e17265d2c5c66', 'i:1;', 1773289097),
('laravel_cache_d7a5080b66f3e67fa8f30b025e7e17265d2c5c66:timer', 'i:1773289097;', 1773289097),
('laravel_cache_d82c6f3cd30d85f893597af3545eb6d6e887ac4a', 'i:1;', 1773200303),
('laravel_cache_d82c6f3cd30d85f893597af3545eb6d6e887ac4a:timer', 'i:1773200303;', 1773200303),
('laravel_cache_d88e53e4baebf13973de3183bff6cb1ea44f5dc1', 'i:1;', 1773742024),
('laravel_cache_d88e53e4baebf13973de3183bff6cb1ea44f5dc1:timer', 'i:1773742024;', 1773742024),
('laravel_cache_da31c3392a29ab6487d9df1ef12b0e6c9ea8a85f', 'i:2;', 1773288546),
('laravel_cache_da31c3392a29ab6487d9df1ef12b0e6c9ea8a85f:timer', 'i:1773288546;', 1773288546),
('laravel_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:3;', 1773114554),
('laravel_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1773114554;', 1773114554),
('laravel_cache_dad71214fc22812e32b88b0ff1a2be0e7f009c09', 'i:1;', 1773114574),
('laravel_cache_dad71214fc22812e32b88b0ff1a2be0e7f009c09:timer', 'i:1773114574;', 1773114574),
('laravel_cache_dae8cfd34e97737e26a141eda761c259559887d0', 'i:1;', 1773429476),
('laravel_cache_dae8cfd34e97737e26a141eda761c259559887d0:timer', 'i:1773429476;', 1773429476),
('laravel_cache_db46e2652c80273f023d4739bd608710a92141e8', 'i:1;', 1773283926),
('laravel_cache_db46e2652c80273f023d4739bd608710a92141e8:timer', 'i:1773283926;', 1773283926),
('laravel_cache_db5981699d2e4303e7cce6b44052d711e0913114', 'i:2;', 1773286637),
('laravel_cache_db5981699d2e4303e7cce6b44052d711e0913114:timer', 'i:1773286637;', 1773286637),
('laravel_cache_dc18d8606f41eb996c30527b6a0138253cc00796', 'i:1;', 1773277821),
('laravel_cache_dc18d8606f41eb996c30527b6a0138253cc00796:timer', 'i:1773277821;', 1773277821),
('laravel_cache_dca79bf3f07c62dfa9968fba6bfafb38ce234cac', 'i:1;', 1773072603),
('laravel_cache_dca79bf3f07c62dfa9968fba6bfafb38ce234cac:timer', 'i:1773072603;', 1773072603),
('laravel_cache_ddc7af027b5f071608a4a81430903b76f7510572', 'i:1;', 1773281887),
('laravel_cache_ddc7af027b5f071608a4a81430903b76f7510572:timer', 'i:1773281887;', 1773281887),
('laravel_cache_ded6884a795b4167eb6c9e5e9b2bca478f2e6035', 'i:1;', 1773362177),
('laravel_cache_ded6884a795b4167eb6c9e5e9b2bca478f2e6035:timer', 'i:1773362177;', 1773362177),
('laravel_cache_e408d89ae85c9a0b6deaeeec2a3cf7eb0cf9c5fd', 'i:2;', 1773293704),
('laravel_cache_e408d89ae85c9a0b6deaeeec2a3cf7eb0cf9c5fd:timer', 'i:1773293704;', 1773293704),
('laravel_cache_e4c8b73f85848aa2f5f8a0928ebb0da5e5d4c92f', 'i:1;', 1773287843),
('laravel_cache_e4c8b73f85848aa2f5f8a0928ebb0da5e5d4c92f:timer', 'i:1773287843;', 1773287843),
('laravel_cache_e5a49839f94e03aaf9dceb67aa01b7e2c837ce6d', 'i:2;', 1773114765),
('laravel_cache_e5a49839f94e03aaf9dceb67aa01b7e2c837ce6d:timer', 'i:1773114765;', 1773114765),
('laravel_cache_e6b33bfa292accd4d4c3aef5d1291d03fa930334', 'i:1;', 1773552640),
('laravel_cache_e6b33bfa292accd4d4c3aef5d1291d03fa930334:timer', 'i:1773552640;', 1773552640),
('laravel_cache_e7ff3236ba9e8fdec55ab5c85627861b27c3c7d7', 'i:1;', 1773287701),
('laravel_cache_e7ff3236ba9e8fdec55ab5c85627861b27c3c7d7:timer', 'i:1773287701;', 1773287701),
('laravel_cache_e8d04586ebd84c4dd3698a78bf0d00b400752c76', 'i:1;', 1773860832),
('laravel_cache_e8d04586ebd84c4dd3698a78bf0d00b400752c76:timer', 'i:1773860832;', 1773860832),
('laravel_cache_ec8af2d13700fa87f3dbc6fab2aaa65d98d15cb7', 'i:2;', 1772594461),
('laravel_cache_ec8af2d13700fa87f3dbc6fab2aaa65d98d15cb7:timer', 'i:1772594461;', 1772594461),
('laravel_cache_ed06aeae05b3e03f476ee5cb980cfbe5cc85a26e', 'i:1;', 1774057246),
('laravel_cache_ed06aeae05b3e03f476ee5cb980cfbe5cc85a26e:timer', 'i:1774057246;', 1774057246),
('laravel_cache_ed4dd0b229adb9c1795320eab4a2fe9fe706a61d', 'i:1;', 1773645833),
('laravel_cache_ed4dd0b229adb9c1795320eab4a2fe9fe706a61d:timer', 'i:1773645833;', 1773645833),
('laravel_cache_eede50a3bd75297004be93b8c1f98e09a47aa74e', 'i:3;', 1773996314),
('laravel_cache_eede50a3bd75297004be93b8c1f98e09a47aa74e:timer', 'i:1773996314;', 1773996314),
('laravel_cache_ef1525bbb0227ff5df7da06da24e7032538c5719', 'i:2;', 1773931497),
('laravel_cache_ef1525bbb0227ff5df7da06da24e7032538c5719:timer', 'i:1773931497;', 1773931497),
('laravel_cache_ef425a078df9cad9fadf25d692f0b250417d322b', 'i:3;', 1773602350),
('laravel_cache_ef425a078df9cad9fadf25d692f0b250417d322b:timer', 'i:1773602350;', 1773602350),
('laravel_cache_email_verify_code:2781c6982e304b871a2c560d657e443e871d5068', 'a:3:{s:4:\"code\";s:6:\"800299\";s:5:\"email\";s:18:\"vp88info@gmail.com\";s:7:\"sent_at\";i:1773116190;}', 1773116490),
('laravel_cache_email_verify_code:82a30e7892e158f3aa0a6973d1294e34ead8176a', 'a:3:{s:4:\"code\";s:6:\"583628\";s:5:\"email\";s:23:\"abang06102006@gmail.com\";s:7:\"sent_at\";i:1773113870;}', 1773114170),
('laravel_cache_email_verify_code:b7a171c4598c0e59c4c34e206a19ab092fc8ed3d', 'a:3:{s:4:\"code\";s:6:\"701055\";s:5:\"email\";s:25:\"hotro.kienvanco@gmail.com\";s:7:\"sent_at\";i:1773114618;}', 1773114918),
('laravel_cache_email_verify_cooldown:200b2cd1e0b700accd75953f31129e8fa748c293', 'b:1;', 1773297742),
('laravel_cache_email_verify_cooldown:2781c6982e304b871a2c560d657e443e871d5068', 'b:1;', 1773116310),
('laravel_cache_email_verify_cooldown:40ac4d93db35e0ebf80094d924686723fff640cc', 'b:1;', 1772523636),
('laravel_cache_email_verify_cooldown:4c260b31536d8686bc73258c99cdae34f37f5fa5', 'b:1;', 1773022848),
('laravel_cache_email_verify_cooldown:61c02b8148aaae0d1ade4baae5e02794f80c0690', 'b:1;', 1773195496),
('laravel_cache_email_verify_cooldown:7189d9212e4137cd47ca871b0353a9d772e0667b', 'b:1;', 1773072611),
('laravel_cache_email_verify_cooldown:82a30e7892e158f3aa0a6973d1294e34ead8176a', 'b:1;', 1773113990),
('laravel_cache_email_verify_cooldown:b7a171c4598c0e59c4c34e206a19ab092fc8ed3d', 'b:1;', 1773114738),
('laravel_cache_email_verify_cooldown:cbeebb7e75c9d6d51aa7e84d33cab6a61fd55f54', 'b:1;', 1772766778),
('laravel_cache_email_verify_cooldown:e8fee31d08e52642dd03c72e05b31449c22fd3dc', 'b:1;', 1772510858),
('laravel_cache_email_verify:email:200b2cd1e0b700accd75953f31129e8fa748c293', 'i:1;', 1773301222),
('laravel_cache_email_verify:email:200b2cd1e0b700accd75953f31129e8fa748c293:timer', 'i:1773301222;', 1773301222),
('laravel_cache_email_verify:email:2781c6982e304b871a2c560d657e443e871d5068', 'i:2;', 1773119036),
('laravel_cache_email_verify:email:2781c6982e304b871a2c560d657e443e871d5068:timer', 'i:1773119036;', 1773119036),
('laravel_cache_email_verify:email:40ac4d93db35e0ebf80094d924686723fff640cc', 'i:1;', 1772527116),
('laravel_cache_email_verify:email:40ac4d93db35e0ebf80094d924686723fff640cc:timer', 'i:1772527116;', 1772527116),
('laravel_cache_email_verify:email:4c260b31536d8686bc73258c99cdae34f37f5fa5', 'i:1;', 1773026328),
('laravel_cache_email_verify:email:4c260b31536d8686bc73258c99cdae34f37f5fa5:timer', 'i:1773026328;', 1773026328),
('laravel_cache_email_verify:email:61c02b8148aaae0d1ade4baae5e02794f80c0690', 'i:1;', 1773198976),
('laravel_cache_email_verify:email:61c02b8148aaae0d1ade4baae5e02794f80c0690:timer', 'i:1773198976;', 1773198976),
('laravel_cache_email_verify:email:7189d9212e4137cd47ca871b0353a9d772e0667b', 'i:1;', 1773076091),
('laravel_cache_email_verify:email:7189d9212e4137cd47ca871b0353a9d772e0667b:timer', 'i:1773076091;', 1773076091),
('laravel_cache_email_verify:email:82a30e7892e158f3aa0a6973d1294e34ead8176a', 'i:1;', 1773117470),
('laravel_cache_email_verify:email:82a30e7892e158f3aa0a6973d1294e34ead8176a:timer', 'i:1773117470;', 1773117470),
('laravel_cache_email_verify:email:b7a171c4598c0e59c4c34e206a19ab092fc8ed3d', 'i:2;', 1773118068),
('laravel_cache_email_verify:email:b7a171c4598c0e59c4c34e206a19ab092fc8ed3d:timer', 'i:1773118068;', 1773118068),
('laravel_cache_email_verify:email:cbeebb7e75c9d6d51aa7e84d33cab6a61fd55f54', 'i:1;', 1772770258),
('laravel_cache_email_verify:email:cbeebb7e75c9d6d51aa7e84d33cab6a61fd55f54:timer', 'i:1772770258;', 1772770258),
('laravel_cache_email_verify:email:e8fee31d08e52642dd03c72e05b31449c22fd3dc', 'i:3;', 1772513777),
('laravel_cache_email_verify:email:e8fee31d08e52642dd03c72e05b31449c22fd3dc:timer', 'i:1772513777;', 1772513777),
('laravel_cache_email_verify:ip:104.22.66.76', 'i:1;', 1773118972),
('laravel_cache_email_verify:ip:104.22.66.76:timer', 'i:1773118972;', 1773118972),
('laravel_cache_email_verify:ip:104.23.175.185', 'i:1;', 1773118218),
('laravel_cache_email_verify:ip:104.23.175.185:timer', 'i:1773118218;', 1773118218),
('laravel_cache_email_verify:ip:162.158.162.21', 'i:1;', 1773118068),
('laravel_cache_email_verify:ip:162.158.162.21:timer', 'i:1773118068;', 1773118068),
('laravel_cache_email_verify:ip:162.158.170.81', 'i:1;', 1773198976),
('laravel_cache_email_verify:ip:162.158.170.81:timer', 'i:1773198976;', 1773198976),
('laravel_cache_email_verify:ip:162.159.98.41', 'i:1;', 1772514072),
('laravel_cache_email_verify:ip:162.159.98.41:timer', 'i:1772514072;', 1772514072),
('laravel_cache_email_verify:ip:172.68.164.50', 'i:1;', 1773119790),
('laravel_cache_email_verify:ip:172.68.164.50:timer', 'i:1773119790;', 1773119790),
('laravel_cache_email_verify:ip:172.68.211.146', 'i:1;', 1773076091),
('laravel_cache_email_verify:ip:172.68.211.146:timer', 'i:1773076091;', 1773076091),
('laravel_cache_email_verify:ip:172.68.211.147', 'i:1;', 1772513777),
('laravel_cache_email_verify:ip:172.68.211.147:timer', 'i:1772513777;', 1772513777),
('laravel_cache_email_verify:ip:172.69.131.201', 'i:1;', 1772770258),
('laravel_cache_email_verify:ip:172.69.131.201:timer', 'i:1772770258;', 1772770258),
('laravel_cache_email_verify:ip:172.69.166.110', 'i:1;', 1773117470),
('laravel_cache_email_verify:ip:172.69.166.110:timer', 'i:1773117470;', 1773117470),
('laravel_cache_email_verify:ip:172.69.166.111', 'i:1;', 1773119036),
('laravel_cache_email_verify:ip:172.69.166.111:timer', 'i:1773119036;', 1773119036),
('laravel_cache_email_verify:ip:172.71.211.15', 'i:1;', 1772514398),
('laravel_cache_email_verify:ip:172.71.211.15:timer', 'i:1772514398;', 1772514398),
('laravel_cache_email_verify:ip:172.71.219.108', 'i:1;', 1773301222),
('laravel_cache_email_verify:ip:172.71.219.108:timer', 'i:1773301222;', 1773301222),
('laravel_cache_email_verify:ip:172.71.81.237', 'i:1;', 1772527116),
('laravel_cache_email_verify:ip:172.71.81.237:timer', 'i:1772527116;', 1772527116),
('laravel_cache_f0c0f5562740fa0351c36042740c2bdbf5c601af', 'i:2;', 1773725414),
('laravel_cache_f0c0f5562740fa0351c36042740c2bdbf5c601af:timer', 'i:1773725414;', 1773725414),
('laravel_cache_f0f560b7e151daf8f1d20ffb34eb4a0b1845ad2a', 'i:1;', 1773433281),
('laravel_cache_f0f560b7e151daf8f1d20ffb34eb4a0b1845ad2a:timer', 'i:1773433281;', 1773433281),
('laravel_cache_f11205be57e1b15c7441eaa70895306fca5ecfca', 'i:2;', 1773287841),
('laravel_cache_f11205be57e1b15c7441eaa70895306fca5ecfca:timer', 'i:1773287841;', 1773287841),
('laravel_cache_f24773b8b69ccc105db2bd792e745ba327c0a1b1', 'i:1;', 1773725449),
('laravel_cache_f24773b8b69ccc105db2bd792e745ba327c0a1b1:timer', 'i:1773725449;', 1773725449),
('laravel_cache_f26caac994246648664706c5e65e51cff9d3756a', 'i:1;', 1773288652),
('laravel_cache_f26caac994246648664706c5e65e51cff9d3756a:timer', 'i:1773288652;', 1773288652),
('laravel_cache_f27d3f13b9e2b33bc753ec9a47befe15cff542a0', 'i:3;', 1773131375),
('laravel_cache_f27d3f13b9e2b33bc753ec9a47befe15cff542a0:timer', 'i:1773131375;', 1773131375),
('laravel_cache_f34fc1f2b90af699fa292ca6422817ccb7216df6', 'i:2;', 1773198257),
('laravel_cache_f34fc1f2b90af699fa292ca6422817ccb7216df6:timer', 'i:1773198257;', 1773198257),
('laravel_cache_f363b318f4d67be44bf3985bfd8ab410f34df21e', 'i:1;', 1773725434),
('laravel_cache_f363b318f4d67be44bf3985bfd8ab410f34df21e:timer', 'i:1773725434;', 1773725434),
('laravel_cache_f3c8a3a7ed9ef24487198248a78631ed23c6e8c6', 'i:1;', 1772952967),
('laravel_cache_f3c8a3a7ed9ef24487198248a78631ed23c6e8c6:timer', 'i:1772952967;', 1772952967),
('laravel_cache_f508fe962c41ceeff951da4d539d51dad267f7ce', 'i:1;', 1773411690),
('laravel_cache_f508fe962c41ceeff951da4d539d51dad267f7ce:timer', 'i:1773411690;', 1773411690),
('laravel_cache_f66bbf3971f3cf752b39df38300d0d21efc0d48a', 'i:1;', 1774026674),
('laravel_cache_f66bbf3971f3cf752b39df38300d0d21efc0d48a:timer', 'i:1774026674;', 1774026674),
('laravel_cache_f681f27f93bf9073aa238b86219e75a8bd39aa72', 'i:1;', 1773839281),
('laravel_cache_f681f27f93bf9073aa238b86219e75a8bd39aa72:timer', 'i:1773839281;', 1773839281),
('laravel_cache_f6bf0d1e30706d5de9664b11005f29151d15c9f9', 'i:1;', 1773249131),
('laravel_cache_f6bf0d1e30706d5de9664b11005f29151d15c9f9:timer', 'i:1773249131;', 1773249131);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_f6f26a628085537df41c340932419910ed457630', 'i:1;', 1773248676),
('laravel_cache_f6f26a628085537df41c340932419910ed457630:timer', 'i:1773248676;', 1773248676),
('laravel_cache_f755ea83d3c7e225ebab366d4ec7e9676cfb26bf', 'i:1;', 1773412563),
('laravel_cache_f755ea83d3c7e225ebab366d4ec7e9676cfb26bf:timer', 'i:1773412563;', 1773412563),
('laravel_cache_f7cf4c47c66cdaa68d1bff66e1d416b7ee7ad89f', 'i:1;', 1773286639),
('laravel_cache_f7cf4c47c66cdaa68d1bff66e1d416b7ee7ad89f:timer', 'i:1773286639;', 1773286639),
('laravel_cache_f96e553a8a58005ffeef59a3767857a82f2727ae', 'i:1;', 1773757973),
('laravel_cache_f96e553a8a58005ffeef59a3767857a82f2727ae:timer', 'i:1773757973;', 1773757973),
('laravel_cache_fa2c2bc4292dce60eae119fbc8dc8668de4d6518', 'i:1;', 1773830001),
('laravel_cache_fa2c2bc4292dce60eae119fbc8dc8668de4d6518:timer', 'i:1773830001;', 1773830001),
('laravel_cache_fa4122f0f3493562b2e70e53c4d6984e44977f75', 'i:1;', 1773203709),
('laravel_cache_fa4122f0f3493562b2e70e53c4d6984e44977f75:timer', 'i:1773203709;', 1773203709),
('laravel_cache_fb633ebdee3f63155bb9bb3767e9de25305b3e5f', 'i:1;', 1773931495),
('laravel_cache_fb633ebdee3f63155bb9bb3767e9de25305b3e5f:timer', 'i:1773931495;', 1773931495),
('laravel_cache_fbd941abef4e3efb72bb8c6777293522e36299a8', 'i:1;', 1773373975),
('laravel_cache_fbd941abef4e3efb72bb8c6777293522e36299a8:timer', 'i:1773373975;', 1773373975),
('laravel_cache_fc66dd3b3ac20e1e5325dbd5c701fdd95b7a343b', 'i:1;', 1774017673),
('laravel_cache_fc66dd3b3ac20e1e5325dbd5c701fdd95b7a343b:timer', 'i:1774017673;', 1774017673),
('laravel_cache_fcb07eea9f10a51518d7bb19b8e054c787a07e5c', 'i:1;', 1774035327),
('laravel_cache_fcb07eea9f10a51518d7bb19b8e054c787a07e5c:timer', 'i:1774035327;', 1774035327),
('laravel_cache_fd446c7cce0512002284fa43a20deecab925a32d', 'i:1;', 1773832754),
('laravel_cache_fd446c7cce0512002284fa43a20deecab925a32d:timer', 'i:1773832754;', 1773832754),
('laravel_cache_fec9cfba39413488f0aa2ef3fdb915356e979d04', 'i:1;', 1772519848),
('laravel_cache_fec9cfba39413488f0aa2ef3fdb915356e979d04:timer', 'i:1772519848;', 1772519848),
('laravel_cache_fede436d77bc89027f5757be85a96d6559f877b7', 'i:1;', 1773072574),
('laravel_cache_fede436d77bc89027f5757be85a96d6559f877b7:timer', 'i:1773072574;', 1773072574),
('laravel_cache_ff62440ee85ee2b773a0100d57d5271c80459e02', 'i:1;', 1772678213),
('laravel_cache_ff62440ee85ee2b773a0100d57d5271c80459e02:timer', 'i:1772678213;', 1772678213),
('laravel_cache_wDOfmblVmBKOA8lx', 's:7:\"forever\";', 2087873386);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0FZ9IvB08suyDKoa6XPcYQR58IdIv1FzUO83SIF5', NULL, '172.71.238.181', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid0RwOTBkSXo2U1hRNlFRUkFGcnVIaG1GcGZTSzR3bnJuTjVIOFloSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773742570),
('350b5d3hy8a8pVKMJnvFQkZua6ZKqacwcNY5nH5C', NULL, '172.71.219.108', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXNiSjFUUnVLSEFTVE5UMmN6UjM5WnNjSFEyWkV6M0lGYk9sRWRreiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773456619),
('8KHDo0SxKIG8MYj6ys6YRiWTqudngjU0GisSJjQq', NULL, '172.69.23.120', 'vercel-screenshot/1.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieGNHZzM3VXIwR2dIdXl5ZzlTeEtLU1FMV3JzRDhYVEhwRmlEVGdqTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773116188),
('9lNUhOItpUZ4Hd3flD3TxBOGiWwXchiQ0ISxr1ki', NULL, '108.162.245.180', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicHJGdjZ5anhWeXFLQzhubEZ5OUdEZGFaVktZZU15ZDVmWmdhWGl3dCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1774038105),
('aARCGgf11hRcwmYh7o8RiY7bzeGXtIOmxxT5FiAO', NULL, '104.22.62.28', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWZYSjY3eGVrdm9VVnBGcUpTTHBSWnZ6VE82S2NNc3hkRnl2TWdOeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773442075),
('aiKYmwMZdRj11RY2YKCdF42evDfHgkQRXulLihhP', NULL, '104.22.10.203', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHZPQWthNjJ6STExQTdpWnpEMnozallDZFNrTWxjN096cU9rV2JmRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773560678),
('avf4PByjJ03QHgHMUyVINxhGyU907tSw339azRGv', NULL, '172.70.100.109', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiREFGdGJwOExQVXpjVUhPTjVhVFVTbWxUeU9XVDFaN2IzNEluMDdxVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773711458),
('B6jRNO705kMlMbWkct4HYKBH5zbUyWbSwAplyhcF', NULL, '104.22.17.72', 'vercel-screenshot/1.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSmI4a2dlUjd2bjNmMEFlWmRLV3I1Rm9hVXlGZHF2cGVjY3U5emlaQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773116188),
('BnhBOoUV6ACN2lmeJ4Ndgvpkn2sqJq8i4vXLQNDa', NULL, '104.22.10.127', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQU1CUTd0azBOdWl1dnB2STJyUGZNU2ZtMWhrcWZjVFZJajhhV1dJWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773708837),
('deVm97JHYgJzir71F4sUAiXA3hxnkP0xu9ZuZEqm', NULL, '172.71.158.129', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWk9ucUZja3B2cHJZYjhkQ0xGOXRVaEd1UGJtODRXZjhMOGJkM1Q3ZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773306204),
('DlVidsBH0LUkFPuTXWS7TWxE7NWAClXjNbUwk1Zj', NULL, '104.22.17.72', 'vercel-screenshot/1.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid0YzZ3ZyU0F5VGdDMlJaN3RxdE0waWlZcnFWb1prc0Rmb2FaeXpQayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773288151),
('DQLJuMM8KXkKYH5gSOMZfbROIz8ANl2e8FNjkI5c', NULL, '172.69.221.11', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaGFjSW5DZk9iNjJWZmFuN2RpRG5Qc0I3WWN4eUhZQndkNWZZREYwNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773664769),
('dzByRISrYlDzOToUFapymBPrvHv0AyfYUI8j9u4u', NULL, '172.69.11.151', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWRYMEpwalBpY1BMTUphNDlTSlJQclN0MVVIbWpJUzFZQ0EzU2R0eiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9hcGkubWl0cmFkZWZvcmV4eC5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774050682),
('e0JeZnjM5HAu2VjCit7wZHtpQKjvsxJlYsHNK6Lf', NULL, '172.71.154.237', 'vercel-screenshot/1.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib3ZndXVvNUFwMTc0Y1FBa3J0cHppZkFpaGk0SU5ETEQybTdiZTd2MSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773205744),
('fJbu9REamXxDGtgav8ivorvVExFGr4Fsb4v50swx', NULL, '104.23.190.82', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDJIcVFZVDZxZ1FGTVViWWdFVXBaek91eEx3UWxsbkZFWkVuNzNOTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9hcGkubWl0cmFkZWZvcmV4eC5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1773626106),
('GrVUAE1IGUVL8Im9W1zEwVS4Eujk0ZJTuiNzMi2q', NULL, '162.159.106.75', 'Mozilla/5.0 (compatible; CMS-Checker/1.0; +https://example.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieHNySDJoeUlyOE80M2FncjFtS1EzNGtEMFVLQ3lRN05pQ3cxUUQ4aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9hcGkubWl0cmFkZWZvcmV4eC5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1773242218),
('IxluxIkMYn5vBqh7RPA8EOmi9BWIkZqhvuMvFBW6', NULL, '172.70.231.40', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidEFEcTZsZWVSVWQwUUxDcnhkSHdWeEpZQjI3WGplYmhqUFFBZFdCTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773637726),
('jkwGhEMQ6p5G948dW4sgRXW63PakW7wDsqWFb4uE', NULL, '172.71.203.125', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNlFmS0lYNktoUFhoazhnTUVwSVJhNnhESVdNa3pDT2VPeWhha1hFMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773626107),
('K22NtkU89nSN4fltYEv50kN9TKiKLsUCcudlSkHy', NULL, '162.158.48.223', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTnVJMXZacWNYRzREOFAyOUFTMmFLSWFReXFIckdLVEVDTXoyZnYwdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9hcGkubWl0cmFkZWZvcmV4eC5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1773894085),
('kCFlbNVlk1cLc6qPfRykkBqRIDbvPtTmAh0JSK8r', NULL, '104.22.56.191', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic2RIemZGbHlBVG5CbEdNMDloc0UxNXc1QzJMZTRWZ3dmOXZHN1dZNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9hcGkubWl0cmFkZWZvcmV4eC5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774044475),
('kEjaOnRCuQ9gAYBZ5rs4zQycznXBqH0YyGmwi9Mk', NULL, '172.70.46.133', 'Mozilla/5.0 (X11; openSUSE; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.132 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFBpNXR3Z1VIYmVTdGZsUFVJTnJGdEcxa3o2Rm16WXB2NnpTQXo4UiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773565514),
('kv1KfxPkibknFMc40bw5cX02tL4yJEPu7H1HK6Em', NULL, '104.23.253.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiakFUNXdGd0owU1lEbjd1aEE0UnlLYlExc0tVd0VHdU9NQUJKdHEwTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773738199),
('lAxxXpIvc9KtaJQwFLJKvsk8jU2B9dx5au0CNUBB', NULL, '162.158.202.112', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieDZ5N2U2NDVxZ3NlMGFGWmNnbENtZllKdkt2dzVZWUVycjBlTlp1QyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773469833),
('LZbalyIOUbVoG2PPy77xqlfLWHA0HFBBSwNJLUXq', NULL, '104.23.197.184', 'visionheight.com/scan Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1A2U3pRdlNTWjdiWGV0bmthRDhEU3ZNdVlJUXdlMlZpSHlKQkFYTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773653657),
('NqbfpYpJhNhx68L1wVpT2FjSr5WGDmqgRt8UimIn', NULL, '198.41.227.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.79 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWm1NZWNvWU96UTF1djNzTGNZYU1kVUEzeFRBR0MzbTdmNHp2TXFRRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773135322),
('qHpPk3gJKicZnWztLwo9NTp5Vb9O92SZEAsu1vOJ', NULL, '172.71.235.105', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoickgycmV0SHA4UTBkdm9mM1BUY2pmTDJFUVptWVpRRllZZjEwQUtOQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773740790),
('RgC3E8puUn18iMOiWNqQrItckWmr0FcpwR0QgYNQ', NULL, '162.158.107.49', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV0l5c2hkVlhCOVNxaVczRkVLZUlZcWRKR1g1VHZhSXBidXBlQzdjYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9hcGkubWl0cmFkZWZvcmV4eC5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1773364671),
('rHybXugkLpkmKXPLZukGw3ZvUQ4esGIVUAaLZBx7', NULL, '172.71.122.131', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYld4OVNwVUpJUGdCU0thc1RXV0dRU3owV21qTjdYNGpsTVhJYVl3NSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773817585),
('RlncPRLQA4w8VZg6TNMJ6D46M0S75DKGaKyAIqli', NULL, '172.71.154.237', 'vercel-screenshot/1.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidlRmQW5BMXJDVmZ1QnpBRDRkYkxtSzZmc2hvY0lXSlFxZ0drNDg2diI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773288150),
('rMOfKPQcGaGhbCYxBGJLqEFPnyAnKj4tnvAqQon5', NULL, '162.158.210.93', 'Mozilla/5.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUFAzVEY4VkNib0pMeG9SY3JKWjBJblRTR05QQzRJSVE0emQxRnRkbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773119559),
('ryLa1WTN5M8pm3ODS21jysNc4RH9lMs8mRmj3xdM', NULL, '162.158.86.229', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRW5XS3IxWXNhdnF0S1RvUlBZelp6QzJCVUdPMnhDMkJwQ0xYQlJ0RyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773632765),
('T2YpG2TGzWvpDXrNxefFTUT0tZL2ihlTPFu3Ep0H', NULL, '172.71.159.168', 'vercel-screenshot/1.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidGx1eHZCWVpWd3VtczdESVlyS2dPc09YeGxrbGJoVHdqbUVQV1h1dCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773205744),
('tq0Htu2jZgU32fwHvw0bO6Ul2rU42I5OYpYzxk3S', NULL, '172.71.123.61', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiektUelJ2ZW15RFpkTU5aMTNVSnRqZ29iaHdUOGJ6MjNJM2d2VTV1bCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773831139),
('Uk8m72JV5TIsDI9QhLQluFKLealIffeHBDxd63o3', NULL, '172.68.245.233', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibEc0U0hDUGdwc1B5Rk4zYjZjRWpnYlZMWnhRdEFOVXVlU1AyUlFZUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773992713),
('uwoKCShxgOArML9ll1t7M1Pu6jIKxfTVGjYnfMWo', NULL, '172.71.28.157', 'visionheight.com/scan Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTVDRmduT1dIV1JSYjdLaEgzTFBvOXplcXhOVEh3cU0wd2E3NjlYcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9hcGkubWl0cmFkZWZvcmV4eC5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1773653454),
('V8wR8n7TccaSoYPtdHlwdGghfz3Pf9E4r9MAJAin', NULL, '172.71.131.38', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVUw5ZVBNalVKTGFYeGdIVnN1Q21YaG5WVFN1aDd4Zk9sQXFMWTVlcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773470656),
('W7xxcVD3Ru0tj41rBcmCyMgBYZqFUNrel5GtxeTE', NULL, '172.69.58.158', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibzI4UGhvTWR2N1I4bFlnTXk0YVJIdDk3cGdTdE55MnBPWFd6WFRnNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9hcGkubWl0cmFkZWZvcmV4eC5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1773706116),
('XNoq7s3y7dXn5iFnafouWLcni4yBPNN9g8QsqJSD', NULL, '104.23.253.38', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXd4eUZVSk00TmMydklOV0VGTGY0akhkT2diZU1HZXJJcnppZ1BWcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773292057),
('ZMu5lpmMsItVrDhv6tBG11mtfVShzlD9frtKo2ww', NULL, '172.71.234.190', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHIyOHhMYjY3WklqeTRrSE81YU5zYWtIb3ZSeFZqTmZ1T1JHQzl2biI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773349817),
('ZP1ysTM8G9DtZOtu0WyYAxnXXmsaha1Zy14NE2zt', NULL, '172.69.67.155', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFhOOWJ6TVNuWVBSTjNVVDZPRkV4VTV0M2NGa3FMeGVzV3BiT0x4SCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vYXBpLm1pdHJhZGVmb3JleHguY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773135318);

-- --------------------------------------------------------

--
-- Table structure for table `tw_admin`
--

CREATE TABLE `tw_admin` (
  `id` int UNSIGNED NOT NULL,
  `email` varchar(200) NOT NULL DEFAULT '',
  `username` char(16) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `moble` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `sort` int UNSIGNED NOT NULL,
  `addtime` int UNSIGNED NOT NULL,
  `last_login_time` int UNSIGNED NOT NULL,
  `last_login_ip` int UNSIGNED NOT NULL,
  `endtime` int UNSIGNED NOT NULL,
  `status` int NOT NULL COMMENT '1 - ON\r\n2 - OFF',
  `level` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='管理员表';

--
-- Dumping data for table `tw_admin`
--

INSERT INTO `tw_admin` (`id`, `email`, `username`, `nickname`, `moble`, `password`, `sort`, `addtime`, `last_login_time`, `last_login_ip`, `endtime`, `status`, `level`) VALUES
(1, '88888888@qq.com', 'admin', '超级管理员', '13888888888', 'f379eaf3c831b04de153469d1bec345e', 0, 0, 1773719475, 172, 0, 1, 0),
(10, 'egamorft', 'egamorft', 'egamorft', '0', 'e10adc3949ba59abbe56e057f20f883e', 0, 1751379597, 1751422361, 172, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_adver`
--

CREATE TABLE `tw_adver` (
  `id` int UNSIGNED NOT NULL,
  `lang` varchar(20) NOT NULL DEFAULT 'zh-cn',
  `name` varchar(20) NOT NULL DEFAULT '',
  `subhead` varchar(20) NOT NULL COMMENT '副标题描述',
  `url` varchar(100) NOT NULL DEFAULT '',
  `img` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `sort` int UNSIGNED NOT NULL DEFAULT '0',
  `addtime` int UNSIGNED NOT NULL DEFAULT '0',
  `endtime` int UNSIGNED NOT NULL DEFAULT '0',
  `onlinetime` int UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `look` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 电脑端 1手机端'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='广告图片表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `tw_appc`
--

CREATE TABLE `tw_appc` (
  `id` int NOT NULL,
  `web_name` varchar(64) DEFAULT NULL,
  `web_title` varchar(64) DEFAULT NULL,
  `web_icp` varchar(64) DEFAULT NULL,
  `index_img` varchar(256) DEFAULT NULL,
  `pay` varchar(256) DEFAULT NULL,
  `withdraw_notice` varchar(256) DEFAULT NULL,
  `charge_notice` varchar(256) DEFAULT NULL,
  `show_coin` varchar(255) DEFAULT NULL,
  `show_market` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_appc`
--

INSERT INTO `tw_appc` (`id`, `web_name`, `web_title`, `web_icp`, `index_img`, `pay`, `withdraw_notice`, `charge_notice`, `show_coin`, `show_market`) VALUES
(2, '数字资产交易平台', '数字资产交易平台', '', '', '{\"alipay\":{\"name\":\"\\u5f20\\u4e09\",\"value\":\"123456@163.com\"},\"bank\":{\"name\":\"\\u674e\\u56db\",\"value\":\"88888888666666\"}}', '提现说明文字后台可配置', '充值说明文字 后台可配置', '[\"2\",\"39\",\"41\"]', '[\"1\",\"31\",\"33\"]');

-- --------------------------------------------------------

--
-- Table structure for table `tw_area`
--

CREATE TABLE `tw_area` (
  `id` int NOT NULL,
  `name_zh` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `name_en` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `name_abbr` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `international_area_code` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_area`
--

INSERT INTO `tw_area` (`id`, `name_zh`, `name_en`, `name_abbr`, `international_area_code`) VALUES
(1, '阿富汗', 'Afghanistan', 'AFG', '93'),
(2, '阿尔巴尼亚', 'Albania', 'ALB', '355'),
(3, '阿尔及利亚', 'Algeria', 'DZA', '213'),
(4, '安道尔', 'Andorra', 'AND', '376'),
(5, '安哥拉', 'Angola', 'AGO', '244'),
(6, '安提瓜和巴布达', 'Antigua and Barbuda', 'ATG', '1268'),
(7, '阿根廷', 'Argentina', 'ARG', '54'),
(8, '亚美尼亚', 'Armenia', 'ARM', '374'),
(9, '澳大利亚', 'Australia', 'AUS', '61'),
(10, '奥地利', 'Austria', 'AUT', '43'),
(11, '阿塞拜疆', 'Azerbaijan', 'AZE', '994'),
(12, '巴哈马', 'Bahamas', 'BHS', '1242'),
(13, '巴林', 'Bahrain', 'BHR', '973'),
(14, '孟加拉', 'Bangladesh', 'BGD', '880'),
(15, '巴巴多斯', 'Barbados', 'BRB', '1246'),
(16, '白俄罗斯', 'Belarus', 'BLR', '375'),
(17, '比利时', 'Belgium', 'BEL', '32'),
(18, '伯利兹', 'Belize', 'BLZ', '501'),
(19, '贝宁', 'Benin', 'BEN', '229'),
(20, '不丹', 'Bhutan', 'BTN', '975'),
(21, '玻利维亚', 'Bolivia', 'BOL', '591'),
(22, '波黑', 'Bosnia and Herzegovina', 'BIH', '387'),
(23, '博茨瓦纳', 'Botswana', 'BWA', '267'),
(24, '巴西', 'Brazil', 'BRA', '55'),
(25, '文莱', 'Brunei', 'BRN', '673'),
(26, '保加利亚', 'Bulgaria', 'BGR', '359'),
(27, '布基纳法索', 'Burkina Faso', 'BFA', '226'),
(28, '布隆迪', 'Burundi', 'BDI', '257'),
(29, '柬埔寨', 'Cambodia', 'KHM', '855'),
(30, '喀麦隆', 'Cameroon', 'CMR', '237'),
(31, '加拿大', 'Canada', 'CAN', '1'),
(32, '佛得角', 'Cape Verde', 'CPV', '238'),
(33, '中非', 'Central African Republic', 'CAF', '236'),
(34, '乍得', 'Chad', 'TCD', '235'),
(35, '智利', 'Chile', 'CHL', '56'),
(36, '中国', 'China', 'CHN', '86'),
(37, '哥伦比亚', 'Colombia', 'COL', '57'),
(38, '科摩罗', 'Comoros', 'COM', '269'),
(39, '刚果（布）', 'Congo (Brazzaville)', 'COG', '242'),
(40, '刚果（金）', 'Congo (Kinshasa)', 'COD', '243'),
(41, '库克群岛（新西兰）', 'Cook Islands', 'COK', '682'),
(42, '哥斯达黎加', 'Costa Rica', 'CRI', '506'),
(43, '科特迪瓦', 'Coate d\'Ivoire', 'CIV', '225'),
(44, '克罗地亚', 'Croatia', 'HRV', '385'),
(45, '古巴', 'Cuba', 'CUB', '53'),
(46, '塞浦路斯', 'Cyprus', 'CYP', '357'),
(47, '捷克', 'Czech Republic', 'CZE', '420'),
(48, '丹麦', 'Denmark', 'DNK', '45'),
(49, '吉布提', 'Djibouti', 'DJI', '253'),
(50, '多米尼克', 'Dominica', 'DMA', '1767'),
(51, '多米尼加', 'Dominican Republic', 'DOM', '1809'),
(52, '厄瓜多尔', 'Ecuador', 'ECU', '593'),
(53, '埃及', 'Egypt', 'EGY', '20'),
(54, '萨尔瓦多', 'El Salvador', 'SLV', '503'),
(55, '赤道几内亚', 'Equatorial Guinea', 'GNQ', '240'),
(56, '厄立特里亚', 'Eritrea', 'ERI', '291'),
(57, '爱沙尼亚', 'Estonia', 'EST', '372'),
(58, '埃塞俄比亚', 'Ethiopia', 'ETH', '251'),
(59, '斐济', 'Fiji', 'FJI', '679'),
(60, '芬兰', 'Finland', 'FIN', '358'),
(61, '法国', 'France', 'FRA', '33'),
(62, '加蓬', 'Gabon', 'GAB', '241'),
(63, '冈比亚', 'Gambia, The', 'GMB', '220'),
(64, '格鲁吉亚', 'Georgia', 'GEO', '995'),
(65, '德国', 'Germany', 'DEU', '49'),
(66, '加纳', 'Ghana', 'GHA', '233'),
(67, '希腊', 'Greece', 'GRC', '30'),
(68, '格林纳达', 'Grenada', 'GRD', '1473'),
(69, '危地马拉', 'Guatemala', 'GTM', '502'),
(70, '几内亚', 'Guinea', 'GIN', '224'),
(71, '几内亚比绍', 'Guinea-Bissau', 'GNB', '245'),
(72, '圭亚那', 'Guyana', 'GUY', '592'),
(73, '海地', 'Haiti', 'HTI', '509'),
(74, '洪都拉斯', 'Honduras', 'HND', '504'),
(75, '匈牙利', 'Hungary', 'HUN', '36'),
(76, '冰岛', 'Iceland', 'ISL', '354'),
(77, '印度', 'India', 'IND', '91'),
(78, '印尼', 'Indonesia', 'IDN', '62'),
(79, '伊朗', 'Iran', 'IRN', '98'),
(80, '伊拉克', 'Iraq', 'IRQ', '964'),
(81, '爱尔兰', 'Ireland', 'IRL', '353'),
(82, '以色列', 'Israel', 'ISR', '972'),
(83, '意大利', 'Italy', 'ITA', '39'),
(84, '牙买加', 'Jamaica', 'JAM', '1876'),
(85, '日本', 'Japan', 'JPN', '81'),
(86, '约旦', 'Jordan', 'JOR', '962'),
(87, '哈萨克斯坦', 'Kazakhstan', 'KAZ', '7'),
(88, '肯尼亚', 'Kenya', 'KEN', '254'),
(89, '基里巴斯', 'Kiribati', 'KIR', '686'),
(90, '科索沃', 'Kosovo', NULL, '384'),
(91, '科威特', 'Kuwait', 'KWT', '965'),
(92, '吉尔吉斯斯坦', 'Kyrgyzstan', 'KGZ', '996'),
(93, '老挝', 'Laos', 'LAO', '856'),
(94, '拉脱维亚', 'Latvia', 'LVA', '371'),
(95, '黎巴嫩', 'Lebanon', 'LBN', '961'),
(96, '莱索托', 'Lesotho', 'LSO', '266'),
(97, '利比里亚', 'Liberia', 'LBR', '231'),
(98, '利比亚', 'Libya', 'LBY', '218'),
(99, '列支敦士登', 'Liechtenstein', 'LIE', '423'),
(100, '立陶宛', 'Lithuania', 'LTU', '370'),
(101, '卢森堡', 'Luxembourg', 'LUX', '352'),
(102, '马其顿', 'Macedonia', 'MKD', '389'),
(103, '马达加斯加', 'Madagascar', 'MDG', '261'),
(104, '马拉维', 'Malawi', 'MWI', '265'),
(105, '马来西亚', 'Malaysia', 'MYS', '60'),
(106, '马尔代夫', 'Maldives', 'MDV', '960'),
(107, '马里', 'Mali', 'MLI', '223'),
(108, '马耳他', 'Malta', 'MLT', '356'),
(109, '毛里塔尼亚', 'Mauritania', 'MRT', '222'),
(110, '毛里求斯', 'Mauritius', 'MUS', '230'),
(111, '墨西哥', 'Mexico', 'MEX', '52'),
(112, '摩尔多瓦', 'Moldova', 'MDA', '373'),
(113, '摩纳哥', 'Monaco', 'MCO', '377'),
(114, '蒙古', 'Mongolia', 'MNG', '976'),
(115, '黑山', 'Montenegro', 'MNE', '382'),
(116, '摩洛哥', 'Morocco', 'MAR', '212'),
(117, '莫桑比克', 'Mozambique', 'MOZ', '258'),
(118, '缅甸', 'Myanmar', 'MMR', '95'),
(119, '纳米比亚', 'Namibia', 'NAM', '264'),
(120, '尼泊尔', 'Nepal', 'NPL', '977'),
(121, '荷兰', 'Netherlands', 'NLD', '31'),
(122, '新西兰', 'New Zealand', 'NZL', '64'),
(123, '尼加拉瓜', 'Nicaragua', 'NIC', '505'),
(124, '尼日尔', 'Niger', 'NER', '227'),
(125, '尼日利亚', 'Nigeria', 'NGA', '234'),
(126, '纽埃（新西兰）', 'Niue', 'NIU', '683'),
(127, '朝鲜', 'Nouth Korea', 'PRK', '850'),
(128, '挪威', 'Norway', 'NOR', '47'),
(129, '阿曼', 'Oman', 'OMN', '968'),
(130, '巴基斯坦', 'Pakistan', 'PAK', '92'),
(131, '帕劳', 'Palau', 'PLW', '680'),
(132, '巴勒斯坦', 'Palestine', 'PSE', '970'),
(133, '巴拿马', 'Panama', 'PAN', '507'),
(134, '巴布亚新几内亚', 'Papua New Guinea', 'PNG', '675'),
(135, '巴拉圭', 'Paraguay', 'PRY', '595'),
(136, '秘鲁', 'Peru', 'PER', '51'),
(137, '菲律宾', 'Philippines', 'PHL', '63'),
(138, '波兰', 'Poland', 'POL', '48'),
(139, '葡萄牙', 'Portugal', 'PRT', '351'),
(140, '卡塔尔', 'Qatar', 'QAT', '974'),
(141, '罗马尼亚', 'Romania', 'ROU', '40'),
(142, '俄罗斯', 'Russia', 'RUS', '7'),
(143, '卢旺达', 'Rwanda', 'RWA', '250'),
(144, '圣基茨和尼维斯', 'Saint Christopher and Nevis', 'KNA', '1869'),
(145, '圣卢西亚', 'Saint Lucia', 'LCA', '1758'),
(146, '圣文森特和格林纳丁斯', 'Saint Vincent and the Grenadines', 'VCT', '1784'),
(147, '萨摩亚', 'Samoa', 'WSM', '685'),
(148, '圣马力诺', 'San Marino', 'SMR', '378'),
(149, '沙特阿拉伯', 'Saudi Arabia', 'SAU', '966'),
(150, '塞内加尔', 'Senegal', 'SEN', '221'),
(151, '塞尔维亚', 'Serbia', 'SRB', '381'),
(152, '塞舌尔', 'Seychelles', 'SYC', '248'),
(153, '塞拉利昂', 'Sierra Leone', 'SLE', '232'),
(154, '新加坡', 'Singapore', 'SGP', '65'),
(155, '斯洛伐克', 'Slovakia', 'SVK', '421'),
(156, '斯洛文尼亚', 'Slovenia', 'SVN', '386'),
(157, '所罗门群岛', 'Solomon Islands', 'SLB', '677'),
(158, '索马里', 'Somali', 'SOM', '252'),
(159, '南非', 'South Africa', 'ZAF', '27'),
(160, '韩国', 'South Korea', 'KOR', '82'),
(161, '南苏丹', 'South Sudan', 'SSD', '211'),
(162, '西班牙', 'Spain', 'ESP', '34'),
(163, '斯里兰卡', 'Sri Lanka', 'LKA', '94'),
(164, '苏丹', 'Sudan', 'SDN', '249'),
(165, '苏里南', 'Suriname', 'SUR', '597'),
(166, '斯威士兰', 'Swaziland', 'SWZ', '268'),
(167, '瑞典', 'Sweden', 'SWE', '46'),
(168, '瑞士', 'Switzerland', 'CHE', '41'),
(169, '叙利亚', 'Syria', 'SYR', '963'),
(170, '台湾', 'Taiwan', 'TWN', '886'),
(171, '塔吉克斯坦', 'Tajikistan', 'TJK', '992'),
(172, '坦桑尼亚', 'Tanzania', 'TZA', '255'),
(173, '泰国', 'Thailand', 'THA', '66'),
(174, '东帝汶', 'Timor-Leste', 'TLS', '670'),
(175, '多哥', 'Togo', 'TGO', '228'),
(176, '汤加', 'Tonga', 'TON', '676'),
(177, '特立尼达和多巴哥', 'Trinidad and Tobago', 'TTO', '1868'),
(178, '突尼斯', 'Tunisia', 'TUN', '216'),
(179, '土耳其', 'Turkey', 'TUR', '90'),
(180, '土库曼斯坦', 'Turkmenistan', 'TKM', '993'),
(181, '乌干达', 'Uganda', 'UGA', '256'),
(182, '乌克兰', 'Ukraine', 'UKR', '380'),
(183, '阿联酋', 'United Arab Emirates', 'ARE', '971'),
(184, '英国', 'United Kingdom', 'GBR', '44'),
(185, '美国', 'United States', 'USA', '1'),
(186, '乌拉圭', 'Uruguay', 'URY', '598'),
(187, '乌兹别克斯坦', 'Uzbekistan', 'UZB', '998'),
(188, '瓦努阿图', 'Vanuatu', 'VUT', '678'),
(189, '委内瑞拉', 'Venezuela', 'VEN', '58'),
(190, '越南', 'Vietnam', 'VNM', '84'),
(191, '也门', 'Yemen', 'YEM', '967'),
(192, '赞比亚', 'Zambia', 'ZMB', '260'),
(193, '津巴布韦', 'Zimbabwe', 'ZWE', '263'),
(194, '美属萨摩亚（美国）', 'American Samoa', 'ASM', '1684'),
(195, '安圭拉 （英国）', 'Anguilla', 'AIA', '1264'),
(196, '阿鲁巴（荷兰）', 'Aruba', 'ABW', '297'),
(197, '百慕大（英国）', 'Bermuda', 'BMU', '1441'),
(198, '开曼群岛（英国）', 'Cayman Islands', 'CYM', '1345'),
(199, '福克兰群岛（英国、阿根廷争议）', 'Falkland Islands (Islas Malvinas)', 'FLK', '500'),
(200, '法罗群岛（丹麦）', 'Faroe Islands', 'FRO', '298'),
(201, '直布罗陀（英国）', 'Gibraltar', 'GIB', '350'),
(202, '格陵兰（丹麦）', 'Greenland', 'GRL', '299'),
(203, '香港（中国）', 'Hong Kong', 'HKG', '852'),
(204, '澳门（中国）', 'Macao', 'MAC', '853'),
(205, '蒙特塞拉特（英国）', 'Montserrat', 'MSR', '1664'),
(206, '新喀里多尼亚（法国）', 'New Caledonia', 'NCL', '687'),
(207, '波多黎各（美国）', 'Puerto Rico', 'PRI', '1787'),
(208, '托克劳（新西兰）', 'Tokelau', 'TKL', '690'),
(209, '特克斯和凯科斯群岛（英国）', 'Turks and Caicos Islands', 'TCA', '1649'),
(210, '留尼旺和马约特（法国）', NULL, NULL, '262'),
(211, '爱沙尼亚', 'Republic of Estonia', NULL, '372');

-- --------------------------------------------------------

--
-- Table structure for table `tw_auth_extend`
--

CREATE TABLE `tw_auth_extend` (
  `group_id` mediumint UNSIGNED NOT NULL COMMENT '用户id',
  `extend_id` mediumint UNSIGNED NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint UNSIGNED NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_auth_extend`
--

INSERT INTO `tw_auth_extend` (`group_id`, `extend_id`, `type`) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 2, 1),
(1, 2, 2),
(1, 3, 1),
(1, 3, 2),
(1, 4, 1),
(1, 37, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_auth_group`
--

CREATE TABLE `tw_auth_group` (
  `id` mediumint UNSIGNED NOT NULL COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(1000) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_auth_group`
--

INSERT INTO `tw_auth_group` (`id`, `module`, `type`, `title`, `description`, `status`, `rules`) VALUES
(1, 'admin', 1, 'superadmin', '超级管理员组,拥有系统所有权限', 1, '1519,1580,1581,1582,1624,1674,2268,2269,2275,2276,2277,2279,2281,2283,2284,2285,2286,2287,2288,2291,2292,2293,2300,2301,2302,2303,2304,2305,2306,2307,2308,2309,2310,2311,2312,2313,2314,2315,2316,2317,2318,2319,2320,2321,2322,2323,2324,2325,2326,2334,2335,2336,2339,2340,2341,2348,2349,2352,2356,2358,2359,2362,2365,2367,2370,2372,2373,2374,2375,2376,2379,2389,2390,2392,2393,2394,2397,2399,2406,2407,2408,2409,2410,2411,2412,2415,2416,2420,2422,2424,2425,2426,2427,2428,2429,2430,2431,2432,2434,2435,2438,2439,2440,2441,2443,2444,2445,2449,2451,2452,2453,2454,2455,2456,2457,2458,2459,2460,2461,2462,2463'),
(16, 'admin', 1, 'information', '文档组管理员', 1, '2275,2276,2285,2286,2287,2288,2289,2290,2318,2319,2321,2336,2339,2362,2375,2435,2438,2439,2440,2441'),
(17, 'admin', 1, 'kefu', '客户服务', 1, '2275,2276,2279,2285,2286,2287,2288,2289,2290,2310,2318,2319,2321,2336,2339,2349,2362,2375,2435,2438,2439,2440,2441'),
(18, 'admin', 1, 'shichang', '市场组管理员', 1, '1580,1581,1582,1624,1674,2268,2269,2275,2276,2277,2279,2285,2286,2287,2288,2289,2290,2291,2292,2300,2301,2302,2303,2304,2305,2306,2307,2308,2309,2310,2311,2312,2313,2314,2315,2316,2317,2318,2319,2321,2322,2323,2324,2325,2326,2334,2335,2336,2339,2340,2341,2348,2349,2356,2359,2362,2365,2367,2375,2376,2399,2420,2426,2432,2433,2434,2435,2438,2439,2440,2441,2451,2452,2456,2458,2461,2462,2463'),
(19, 'admin', 1, 'caiwu', '财务管理员', 1, '1519,2275,2279,2285,2293,2349,2422,2424,2427,2449,2453,2454,2455'),
(22, 'admin', 1, 'low', '只有查看交易市场权限', 1, '2275,2279,2285,2310,2349,2356,2359,2451,2452,2456,2458');

-- --------------------------------------------------------

--
-- Table structure for table `tw_auth_group_access`
--

CREATE TABLE `tw_auth_group_access` (
  `id` int UNSIGNED NOT NULL,
  `uid` int UNSIGNED NOT NULL,
  `group_id` mediumint UNSIGNED NOT NULL COMMENT '用户组id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_auth_group_access`
--

INSERT INTO `tw_auth_group_access` (`id`, `uid`, `group_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_auth_rule`
--

CREATE TABLE `tw_auth_rule` (
  `id` mediumint UNSIGNED NOT NULL COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_auth_rule`
--

INSERT INTO `tw_auth_rule` (`id`, `module`, `type`, `name`, `title`, `status`, `condition`) VALUES
(425, 'admin', 1, 'Admin/article/add', '新增', -1, ''),
(427, 'admin', 1, 'Admin/article/setStatus', '改变状态', -1, ''),
(428, 'admin', 1, 'Admin/article/update', '保存', -1, ''),
(429, 'admin', 1, 'Admin/article/autoSave', '保存草稿', -1, ''),
(430, 'admin', 1, 'Admin/article/move', '移动', -1, ''),
(432, 'admin', 2, 'Admin/Article/mydocument', '内容', -1, ''),
(437, 'admin', 1, 'Admin/Trade/config', '交易配置', -1, ''),
(449, 'admin', 1, 'Admin/Index/operate', '市场统计', -1, ''),
(455, 'admin', 1, 'Admin/Issue/config', '认购配置', -1, ''),
(457, 'admin', 1, 'Admin/Index/database/type/export', '数据备份', -1, ''),
(461, 'admin', 1, 'Admin/Article/chat', '聊天列表', -1, ''),
(464, 'admin', 1, 'Admin/Index/database/type/import', '数据还原', -1, ''),
(471, 'admin', 1, 'Admin/Mytx/config', '提现配置', -1, ''),
(472, 'admin', 2, 'Admin/Mytx/index', '提现', -1, ''),
(473, 'admin', 1, 'Admin/Config/market', '市场配置', -1, ''),
(477, 'admin', 1, 'Admin/User/myzr', '转入虚拟币', -1, ''),
(479, 'admin', 1, 'Admin/User/myzc', '转出虚拟币', -1, ''),
(482, 'admin', 2, 'Admin/ExtA/index', '扩展', -1, ''),
(488, 'admin', 1, 'Admin/Auth_manager/createGroup', '新增用户组', -1, ''),
(499, 'admin', 1, 'Admin/ExtA/index', '扩展管理', -1, ''),
(509, 'admin', 1, 'Admin/Article/adver_edit', '编辑', -1, ''),
(510, 'admin', 1, 'Admin/Article/adver_status', '修改', -1, ''),
(513, 'admin', 1, 'Admin/Issue/index_edit', '认购编辑', -1, ''),
(514, 'admin', 1, 'Admin/Issue/index_status', '认购修改', -1, ''),
(515, 'admin', 1, 'Admin/Article/chat_edit', '编辑', -1, ''),
(516, 'admin', 1, 'Admin/Article/chat_status', '修改', -1, ''),
(517, 'admin', 1, 'Admin/User/coin_edit', 'coin修改', -1, ''),
(519, 'admin', 1, 'Admin/Mycz/type_status', '状态修改', -1, ''),
(520, 'admin', 1, 'Admin/Issue/log_status', '认购状态', -1, ''),
(521, 'admin', 1, 'Admin/Issue/log_jiedong', '认购解冻', -1, ''),
(522, 'admin', 1, 'Admin/Tools/database/type/export', '数据备份', -1, ''),
(525, 'admin', 1, 'Admin/Config/coin_edit', '编辑', -1, ''),
(526, 'admin', 1, 'Admin/Config/coin_add', '编辑币种', -1, ''),
(527, 'admin', 1, 'Admin/Config/coin_status', '状态修改', -1, ''),
(528, 'admin', 1, 'Admin/Config/market_edit', '编辑', -1, ''),
(530, 'admin', 1, 'Admin/Tools/database/type/import', '数据还原', -1, ''),
(541, 'admin', 2, 'Admin/Trade/config', '交易', -1, ''),
(569, 'admin', 1, 'Admin/ADVERstatus', '修改', -1, ''),
(570, 'admin', 1, 'Admin/Tradelog/index', '交易记录', -1, ''),
(585, 'admin', 1, 'Admin/Config/mycz', '充值配置', -1, ''),
(590, 'admin', 1, 'Admin/Mycztype/index', '充值类型', -1, ''),
(600, 'admin', 1, 'Admin/Usergoods/index', '用户联系地址', -1, ''),
(1379, 'admin', 1, 'Admin/Bazaar/index', '集市管理', -1, ''),
(1405, 'admin', 1, 'Admin/Bazaar/config', '集市配置', -1, ''),
(1425, 'admin', 1, 'Admin/Bazaar/log', '集市记录', -1, ''),
(1451, 'admin', 1, 'Admin/Bazaar/invit', '集市推广', -1, ''),
(1519, 'admin', 2, 'Admin/Finance/index', '财务', 1, ''),
(1575, 'admin', 1, 'Admin/Shop/index', '商品管理', -1, ''),
(1578, 'admin', 1, 'Admin/Huafei/index', '充值记录', -1, ''),
(1579, 'admin', 1, 'Admin/Huafei/config', '充值配置', -1, ''),
(1580, 'admin', 1, 'Admin/Vote/index', '投票记录', 1, ''),
(1581, 'admin', 1, 'Admin/Vote/type', '投票配置', 1, ''),
(1582, 'admin', 1, 'Admin/Money/index', '理财中心', 1, ''),
(1599, 'admin', 1, 'Admin/Config/moble', '短信配置', -1, ''),
(1606, 'admin', 1, 'Admin/Shop/config', '商城配置', -1, ''),
(1607, 'admin', 1, 'Admin/Money/log', '理财记录', -1, ''),
(1623, 'admin', 1, 'Admin/Shop/type', '商品类型', -1, ''),
(1624, 'admin', 1, 'Admin/Fenhong/index', '分红配置', 1, ''),
(1625, 'admin', 1, 'Admin/Huafei/type', '充值金额', -1, ''),
(1626, 'admin', 1, 'Admin/Money/fee', '理财明细', -1, ''),
(1651, 'admin', 1, 'Admin/Shop/coin', '付款方式', -1, ''),
(1652, 'admin', 1, 'Admin/Huafei/coin', '付款方式', -1, ''),
(1673, 'admin', 1, 'Admin/Shop/log', '购物记录', -1, ''),
(1674, 'admin', 1, 'Admin/Fenhong/log', '分红记录', 1, ''),
(1681, 'admin', 1, 'Admin/Shop/goods', '收货地址', -1, ''),
(2092, 'admin', 2, 'Admin/Game/index', '应用', -1, ''),
(2168, 'admin', 1, 'Admin/Config/mobile', '短信配置', -1, ''),
(2251, 'admin', 1, 'Admin/Ptpbc/list', '点对点交易记录', -1, ''),
(2255, 'admin', 1, 'Admin/AuthManager/createGroup', '新增用户组', 1, ''),
(2256, 'admin', 1, 'Admin/AuthManager/editgroup', '编辑用户组', 1, ''),
(2257, 'admin', 1, 'Admin/AuthManager/writeGroup', '更新用户组', 1, ''),
(2258, 'admin', 1, 'Admin/AuthManager/changeStatus', '改变状态', 1, ''),
(2259, 'admin', 1, 'Admin/AuthManager/access', '访问授权', 1, ''),
(2260, 'admin', 1, 'Admin/AuthManager/category', '分类授权', 1, ''),
(2261, 'admin', 1, 'Admin/AuthManager/user', '成员授权', 1, ''),
(2262, 'admin', 1, 'Admin/AuthManager/tree', '成员列表授权', 1, ''),
(2263, 'admin', 1, 'Admin/AuthManager/group', '用户组', 1, ''),
(2264, 'admin', 1, 'Admin/AuthManager/addToGroup', '添加到用户组', 1, ''),
(2265, 'admin', 1, 'Admin/AuthManager/removeFromGroup', '用户组移除', 1, ''),
(2266, 'admin', 1, 'Admin/AuthManager/addToCategory', '分类添加到用户组', 1, ''),
(2267, 'admin', 1, 'Admin/AuthManager/addToModel', '模型添加到用户组', 1, ''),
(2268, 'admin', 1, 'Admin/Trade/status', '修改状态', 1, ''),
(2269, 'admin', 1, 'Admin/Trade/chexiao', '撤销挂单', 1, ''),
(2270, 'admin', 1, 'Admin/Shop/images', '图片', 1, ''),
(2271, 'admin', 1, 'Admin/Login/index', '用户登录', -1, ''),
(2272, 'admin', 1, 'Admin/Login/loginout', '用户退出', -1, ''),
(2273, 'admin', 1, 'Admin/User/setpwd', '修改管理员密码', -1, ''),
(2274, 'admin', 1, 'Admin/Analog/console', '行情调整', -1, ''),
(2275, 'admin', 2, 'Admin/Index/index', '首页', 1, ''),
(2276, 'admin', 2, 'Admin/Article/index', '内容', 1, ''),
(2277, 'admin', 2, 'Admin/User/index', '用户', 1, ''),
(2278, 'admin', 2, 'Admin/Finance/mycz', '财务', -1, ''),
(2279, 'admin', 2, 'Admin/Trade/index', '交易', 1, ''),
(2280, 'admin', 2, 'Admin/Issue/index', '应用', -1, ''),
(2281, 'admin', 2, 'Admin/Config/index', '系统', 1, ''),
(2282, 'admin', 2, 'Admin/Operate/index', '运营', -1, ''),
(2283, 'admin', 2, 'Admin/Tools/index', '数据备份', 1, ''),
(2284, 'admin', 2, 'Admin/Cloud/index', '扩展', 1, ''),
(2285, 'admin', 1, 'Admin/Index/index', '后台首页', 1, ''),
(2286, 'admin', 1, 'Admin/Article/index', '文章管理', 1, ''),
(2287, 'admin', 1, 'Admin/Article/edit', '编辑添加', 1, ''),
(2288, 'admin', 1, 'Admin/Text/index', '提示文字', 1, ''),
(2289, 'admin', 1, 'Admin/Text/edit', '编辑', -1, ''),
(2290, 'admin', 1, 'Admin/Text/status', '修改', -1, ''),
(2291, 'admin', 1, 'Admin/User/index', '用户管理', 1, ''),
(2292, 'admin', 1, 'Admin/User/config', '用户配置', 1, ''),
(2293, 'admin', 1, 'Admin/Finance/index', '财务明细', 1, ''),
(2294, 'admin', 1, 'Admin/Finance/myczTypeEdit', '编辑添加', 1, ''),
(2295, 'admin', 1, 'Admin/Cloud/index', '扩展', -1, ''),
(2296, 'admin', 1, 'Admin/Finance/config', '配置', 1, ''),
(2297, 'admin', 1, 'Admin/Tools/index', '清理缓存', -1, ''),
(2298, 'admin', 1, 'Admin/Finance/type', '类型', 1, ''),
(2299, 'admin', 1, 'Admin/Finance/type_status', '状态修改', 1, ''),
(2300, 'admin', 1, 'Admin/User/edit', '编辑添加', 1, ''),
(2301, 'admin', 1, 'Admin/User/status', '修改状态', 1, ''),
(2302, 'admin', 1, 'Admin/User/adminEdit', '编辑添加', 1, ''),
(2303, 'admin', 1, 'Admin/User/adminStatus', '修改状态', 1, ''),
(2304, 'admin', 1, 'Admin/User/authEdit', '编辑添加', 1, ''),
(2305, 'admin', 1, 'Admin/User/authStatus', '修改状态', 1, ''),
(2306, 'admin', 1, 'Admin/User/authStart', '重新初始化权限', 1, ''),
(2307, 'admin', 1, 'Admin/User/logEdit', '编辑添加', 1, ''),
(2308, 'admin', 1, 'Admin/User/logStatus', '修改状态', 1, ''),
(2309, 'admin', 1, 'Admin/User/qianbaoEdit', '编辑添加', 1, ''),
(2310, 'admin', 1, 'Admin/Trade/index', '委托管理', 1, ''),
(2311, 'admin', 1, 'Admin/User/qianbaoStatus', '修改状态', 1, ''),
(2312, 'admin', 1, 'Admin/User/bankEdit', '编辑添加', 1, ''),
(2313, 'admin', 1, 'Admin/User/bankStatus', '修改状态', 1, ''),
(2314, 'admin', 1, 'Admin/User/coinEdit', '编辑添加', 1, ''),
(2315, 'admin', 1, 'Admin/User/coinLog', '财产统计', 1, ''),
(2316, 'admin', 1, 'Admin/User/goodsEdit', '编辑添加', 1, ''),
(2317, 'admin', 1, 'Admin/User/goodsStatus', '修改状态', 1, ''),
(2318, 'admin', 1, 'Admin/Article/typeEdit', '编辑添加', 1, ''),
(2319, 'admin', 1, 'Admin/Article/youqingEdit', '编辑添加', 1, ''),
(2320, 'admin', 1, 'Admin/Config/index', '网站信息', 1, ''),
(2321, 'admin', 1, 'Admin/Article/adverEdit', '编辑添加', 1, ''),
(2322, 'admin', 1, 'Admin/User/authAccess', '访问授权', 1, ''),
(2323, 'admin', 1, 'Admin/User/authAccessUp', '访问授权修改', 1, ''),
(2324, 'admin', 1, 'Admin/User/authUser', '成员授权', 1, ''),
(2325, 'admin', 1, 'Admin/User/authUserAdd', '成员授权增加', 1, ''),
(2326, 'admin', 1, 'Admin/User/authUserRemove', '成员授权解除', 1, ''),
(2327, 'admin', 1, 'Admin/Operate/index', '推广奖励', -1, ''),
(2328, 'admin', 1, 'Admin/App/config', 'APP配置', -1, ''),
(2329, 'admin', 1, 'AdminUser/detail', '后台用户详情', -1, ''),
(2330, 'admin', 1, 'AdminUser/status', '后台用户状态', -1, ''),
(2331, 'admin', 1, 'AdminUser/add', '后台用户新增', -1, ''),
(2332, 'admin', 1, 'AdminUser/edit', '后台用户编辑', -1, ''),
(2333, 'admin', 1, 'Admin/Articletype/edit', '编辑', -1, ''),
(2334, 'admin', 1, 'Admin/Issue/index', '认购配置', 1, ''),
(2335, 'admin', 1, 'Admin/Issue/log', '认购记录', 1, ''),
(2336, 'admin', 1, 'Admin/Article/images', '上传图片', 1, ''),
(2337, 'admin', 1, 'Admin/Adver/edit', '编辑', 1, ''),
(2338, 'admin', 1, 'Admin/Adver/status', '修改', 1, ''),
(2339, 'admin', 1, 'Admin/Article/type', '栏目类型', 1, ''),
(2340, 'admin', 1, 'Admin/User/index_edit', '编辑', 1, ''),
(2341, 'admin', 1, 'Admin/User/index_status', '修改', 1, ''),
(2342, 'admin', 1, 'Admin/Finance/mycz', '人民币充值', -1, ''),
(2343, 'admin', 1, 'Admin/Finance/myczTypeStatus', '状态修改', 1, ''),
(2344, 'admin', 1, 'Admin/Finance/myczTypeImage', '上传图片', 1, ''),
(2345, 'admin', 1, 'Admin/Finance/mytxStatus', '修改状态', 1, ''),
(2346, 'admin', 1, 'Admin/Tools/dataExport', '备份数据库', -1, ''),
(2347, 'admin', 1, 'Admin/Tools/dataImport', '还原数据库', -1, ''),
(2348, 'admin', 1, 'Admin/User/admin', '管理员管理', 1, ''),
(2349, 'admin', 1, 'Admin/Trade/log', '成交记录', 1, ''),
(2350, 'admin', 1, 'Admin/Issue/edit', '认购编辑', -1, ''),
(2351, 'admin', 1, 'Admin/Issue/status', '认购修改', -1, ''),
(2352, 'admin', 1, 'Admin/Invit/config', '推广配置', 1, ''),
(2353, 'admin', 1, 'Admin/App/vip_config_list', 'APP等级', -1, ''),
(2354, 'admin', 1, 'Admin/Link/edit', '编辑', 1, ''),
(2355, 'admin', 1, 'Admin/Link/status', '修改', 1, ''),
(2356, 'admin', 1, 'Admin/Index/coin', '币种统计', 1, ''),
(2357, 'admin', 1, 'Admin/Cloud/update', '自动升级', -1, ''),
(2358, 'admin', 1, 'Admin/Config/hq', '行情配置', 1, ''),
(2359, 'admin', 1, 'Admin/Index/market', '市场统计', 1, ''),
(2360, 'admin', 1, 'Admin/Chat/edit', '编辑', 1, ''),
(2361, 'admin', 1, 'Admin/Chat/status', '修改', 1, ''),
(2362, 'admin', 1, 'Admin/Article/adver', '广告管理', 1, ''),
(2363, 'admin', 1, 'Admin/Trade/chat', '交易聊天', -1, ''),
(2364, 'admin', 1, 'Admin/Finance/myczType', '人民币充值方式', -1, ''),
(2365, 'admin', 1, 'Admin/Usercoin/edit', '财产修改', 1, ''),
(2366, 'admin', 1, 'Admin/Finance/mytxExcel', '导出选中', 1, ''),
(2367, 'admin', 1, 'Admin/User/auth', '权限列表', 1, ''),
(2368, 'admin', 1, 'Admin/Mycz/status', '修改', 1, ''),
(2369, 'admin', 1, 'Admin/Mycztype/status', '状态修改', 1, ''),
(2370, 'admin', 1, 'Admin/Config/contact', '客服配置', 1, ''),
(2371, 'admin', 1, 'Admin/App/adsblock_list', 'APP广告板块', -1, ''),
(2372, 'admin', 1, 'Admin/Tools/queue', '服务器队列', 1, ''),
(2373, 'admin', 1, 'Admin/Tools/qianbao', '钱包检查', 1, ''),
(2374, 'admin', 1, 'Admin/Cloud/game', '应用管理', 1, ''),
(2375, 'admin', 1, 'Admin/Article/youqing', '友情链接', 1, ''),
(2376, 'admin', 1, 'Admin/User/log', '登录日志', 1, ''),
(2377, 'admin', 1, 'Admin/Finance/mytx', '人民币提现', -1, ''),
(2378, 'admin', 1, 'Admin/Finance/mytxChuli', '正在处理', 1, ''),
(2379, 'admin', 1, 'Admin/Config/bank', '银行配置', 1, ''),
(2380, 'admin', 1, 'Admin/Config/bank_edit', '编辑', 1, ''),
(2381, 'admin', 1, 'Admin/Coin/edit', '编辑', 1, ''),
(2382, 'admin', 1, 'Admin/Coin/status', '状态修改', 1, ''),
(2383, 'admin', 1, 'Admin/Market/edit', '编辑市场', 1, ''),
(2384, 'admin', 1, 'Admin/Config/market_add', '状态修改', 1, ''),
(2385, 'admin', 1, 'Admin/Tools/invoke', '其他模块调用', -1, ''),
(2386, 'admin', 1, 'Admin/Tools/optimize', '优化表', -1, ''),
(2387, 'admin', 1, 'Admin/Tools/repair', '修复表', -1, ''),
(2388, 'admin', 1, 'Admin/Tools/del', '删除备份文件', -1, ''),
(2389, 'admin', 1, 'Admin/Tools/export', '数据备份', 1, ''),
(2390, 'admin', 1, 'Admin/Tools/import', '数据恢复', 1, ''),
(2391, 'admin', 1, 'Admin/Tools/excel', '导出数据库', -1, ''),
(2392, 'admin', 1, 'Admin/Tools/exportExcel', '导出Excel', 1, ''),
(2393, 'admin', 1, 'Admin/Tools/importExecl', '导入Excel', 1, ''),
(2394, 'admin', 1, 'Admin/Config/coin', '币种配置', 1, ''),
(2395, 'admin', 1, 'Admin/User/detail', '用户详情', -1, ''),
(2396, 'admin', 1, 'Admin/App/ads_user', 'APP广告用户', -1, ''),
(2397, 'admin', 1, 'Admin/Cloud/theme', '主题模板', 1, ''),
(2398, 'admin', 1, 'Admin/Trade/comment', '币种评论', -1, ''),
(2399, 'admin', 1, 'Admin/User/qianbao', '用户钱包', 1, ''),
(2400, 'admin', 1, 'Admin/Trade/market', '市场配置', -1, ''),
(2401, 'admin', 1, 'Admin/Finance/mytxConfig', '人民币提现配置', -1, ''),
(2402, 'admin', 1, 'Admin/Finance/mytxChexiao', '撤销提现', 1, ''),
(2403, 'admin', 1, 'Admin/Mytx/status', '状态修改', 1, ''),
(2404, 'admin', 1, 'Admin/Mytx/excel', '取消', 1, ''),
(2405, 'admin', 1, 'Admin/Mytx/exportExcel', '导入excel', -1, ''),
(2406, 'admin', 1, 'Admin/Menu/index', '菜单管理', 1, ''),
(2407, 'admin', 1, 'Admin/Menu/sort', '排序', 1, ''),
(2408, 'admin', 1, 'Admin/Menu/add', '添加', 1, ''),
(2409, 'admin', 1, 'Admin/Menu/edit', '编辑', 1, ''),
(2410, 'admin', 1, 'Admin/Cloud/kefu', '客服代码', 1, ''),
(2411, 'admin', 1, 'Admin/Menu/del', '删除', 1, ''),
(2412, 'admin', 1, 'Admin/Cloud/kefuUp', '使用', 1, ''),
(2413, 'admin', 1, 'Admin/Menu/toogleHide', '是否隐藏', -1, ''),
(2414, 'admin', 1, 'Admin/Menu/toogleDev', '是否开发', -1, ''),
(2415, 'admin', 1, 'Admin/Menu/importFile', '导入文件', 1, ''),
(2416, 'admin', 1, 'Admin/Menu/import', '导入', 1, ''),
(2417, 'admin', 1, 'Admin/Config/text', '提示文字', -1, ''),
(2418, 'admin', 1, 'Admin/Ptpbc/log', '点对点交易记录', -1, ''),
(2419, 'admin', 1, 'Admin/Ptpbc/index', '点对点交易', -1, ''),
(2420, 'admin', 1, 'Admin/User/bank', '提现地址', 1, ''),
(2421, 'admin', 1, 'Admin/Trade/invit', '交易推荐', -1, ''),
(2422, 'admin', 1, 'Admin/Finance/myzr', '虚拟币转入', 1, ''),
(2423, 'admin', 1, 'Admin/Finance/mytxQueren', '确认提现', 1, ''),
(2424, 'admin', 1, 'Admin/Finance/myzcQueren', '确认转出', 1, ''),
(2425, 'admin', 1, 'Admin/Config/qita', '系统配置', 1, ''),
(2426, 'admin', 1, 'Admin/User/coin', '用户财产', 1, ''),
(2427, 'admin', 1, 'Admin/Finance/myzc', '虚拟币转出', 1, ''),
(2428, 'admin', 1, 'Admin/Verify/code', '图形验证码', 1, ''),
(2429, 'admin', 1, 'Admin/Verify/mobile', '手机验证码', 1, ''),
(2430, 'admin', 1, 'Admin/Verify/email', '邮件验证码', 1, ''),
(2431, 'admin', 1, 'Admin/Config/daohang', '前端导航配置', 1, ''),
(2432, 'admin', 1, 'Admin/User/goods', '联系地址', 1, ''),
(2433, 'admin', 1, 'Admin/User/myzc_qr', '确认转出', 1, ''),
(2434, 'admin', 1, 'Admin/User/amountlog', '资金变更日志', 1, ''),
(2435, 'admin', 1, 'Admin/Article/status', '修改状态', 1, ''),
(2436, 'admin', 1, 'Admin/Finance/myczStatus', '修改状态', 1, ''),
(2437, 'admin', 1, 'Admin/Finance/myczQueren', '确认到账', 1, ''),
(2438, 'admin', 1, 'Admin/Article/typeStatus', '修改状态', 1, ''),
(2439, 'admin', 1, 'Admin/Article/youqingStatus', '修改状态', 1, ''),
(2440, 'admin', 1, 'Admin/Article/adverStatus', '修改状态', 1, ''),
(2441, 'admin', 1, 'Admin/Article/adverImage', '上传图片', 1, ''),
(2442, 'admin', 2, 'Admin/Finance/myzr', '财务', -1, ''),
(2443, 'admin', 1, 'Admin/Config/smss', '短信群发', 1, ''),
(2444, 'admin', 1, 'Admin/Config/dhfooter', '页脚导航配置', 1, ''),
(2445, 'admin', 1, 'Admin/Config/dhadmin', '后端导航配置', 1, ''),
(2446, 'admin', 2, 'Admin/Issue/log', '应用', -1, ''),
(2447, 'admin', 1, 'Admin/Exchange/myzr', '充值记录', -1, ''),
(2448, 'admin', 1, 'Admin/Exchange/myzc', '提现记录', -1, ''),
(2449, 'admin', 1, 'Admin/Exchange/agent', 'C2C代理商', 1, ''),
(2450, 'admin', 1, 'Admin/Exchange/log', 'C2C配置', -1, ''),
(2451, 'admin', 2, 'Admin/Invit/index', '运营', 1, ''),
(2452, 'admin', 1, 'Admin/Invit/index', '推广奖励', 1, ''),
(2453, 'admin', 1, 'Admin/Exchange/mycz', '充值记录', 1, ''),
(2454, 'admin', 1, 'Admin/Exchange/mytx', '提现记录', 1, ''),
(2455, 'admin', 1, 'Admin/Exchange/config', 'C2C配置', 1, ''),
(2456, 'admin', 1, 'Admin/Invit/mining', '交易挖矿', 1, ''),
(2457, 'admin', 1, 'Admin/Config/marketo', '市场配置', 1, ''),
(2458, 'admin', 1, 'Admin/Invit/recharge', '充值挖矿', 1, ''),
(2459, 'admin', 1, 'Admin/Config/joggle', '接口配置', 1, ''),
(2460, 'admin', 1, 'Admin/Tools/cache', '清理缓存', 1, ''),
(2461, 'admin', 1, 'Admin/Apps/index', '应用首页', 1, ''),
(2462, 'admin', 1, 'Admin/Config/mining', '交易挖矿', 1, ''),
(2463, 'admin', 2, 'Admin/Apps/index', '应用', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `tw_bborder`
--

CREATE TABLE `tw_bborder` (
  `id` int NOT NULL COMMENT '记录ID',
  `uid` int NOT NULL COMMENT '会员ID',
  `account` varchar(30) NOT NULL COMMENT '会员账号',
  `type` int NOT NULL COMMENT '订单类型1买2卖',
  `ordertype` int NOT NULL COMMENT '交易类别1限价2市价',
  `symbol` varchar(30) NOT NULL COMMENT '交易对',
  `coin` varchar(30) NOT NULL COMMENT '币名称',
  `coinnum` decimal(20,2) DEFAULT NULL COMMENT '交易币数量',
  `usdtnum` decimal(20,2) NOT NULL COMMENT '交易USDT数量',
  `price` decimal(20,2) DEFAULT NULL COMMENT '交易单价',
  `xjprice` decimal(20,2) NOT NULL COMMENT '限价单价',
  `addtime` datetime NOT NULL COMMENT '添加交易',
  `tradetime` datetime DEFAULT NULL COMMENT '成交时间',
  `fee` decimal(20,2) DEFAULT NULL COMMENT '手续费',
  `sxfbl` float(10,2) NOT NULL COMMENT '手续费比例',
  `status` int NOT NULL COMMENT '1委托2交易完成3已撤消'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='币币交易记录';

--
-- Dumping data for table `tw_bborder`
--

INSERT INTO `tw_bborder` (`id`, `uid`, `account`, `type`, `ordertype`, `symbol`, `coin`, `coinnum`, `usdtnum`, `price`, `xjprice`, `addtime`, `tradetime`, `fee`, `sxfbl`, `status`) VALUES
(41, 1, 'tungnh3011.work@gmail.com', 2, 2, 'BNB/USDT', 'bnb', 0.00, 0.06, 624.79, 624.79, '2026-03-07 21:08:49', '2026-03-07 21:08:49', 0.00, 0.00, 2),
(40, 1, 'tungnh3011.work@gmail.com', 1, 2, 'BTC/USDT', 'btc', 0.00, 6.78, 67802.76, 67802.76, '2026-03-07 21:08:27', '2026-03-07 21:08:27', 0.00, 0.00, 2),
(39, 1, 'tungnh3011.work@gmail.com', 1, 2, 'BTC/USDT', 'btc', 0.00, 67.80, 67800.43, 67800.43, '2026-03-07 21:02:06', '2026-03-07 21:02:06', 0.00, 0.00, 2),
(38, 1, 'tungnh3011.work@gmail.com', 1, 1, 'BTC/USDT', 'btc', 0.00, 135.00, 67500.00, 67489.98, '2026-03-07 18:25:38', '2026-03-08 01:34:16', 0.00, 0.00, 2),
(37, 1, 'tungnh3011.work@gmail.com', 1, 2, 'BTC/USDT', 'btc', 0.00, 68.14, 68142.35, 68142.35, '2026-03-07 18:15:08', '2026-03-07 18:15:08', 0.00, 0.00, 2),
(36, 1, 'tungnh3011.work@gmail.com', 1, 2, 'BTC/USDT', 'btc', 0.00, 100.00, 67865.71, 67865.71, '2026-03-07 18:06:16', '2026-03-07 18:06:16', 0.00, 0.00, 2),
(50, 506, 'kadiesnguyen@gmail.com', 1, 2, 'BTC/USDT', 'btc', 0.00, 18.73, 69639.45, 69639.45, '2026-03-10 11:10:33', '2026-03-10 11:10:33', 0.00, 0.00, 2),
(43, 1, 'tungnh3011.work@gmail.com', 1, 2, 'BTC/USDT', 'btc', 0.00, 67.14, 67135.00, 67135.00, '2026-03-08 08:31:51', '2026-03-08 08:31:51', 0.00, 0.00, 2),
(49, 506, 'kadiesnguyen@gmail.com', 1, 2, 'BTC/USDT', 'btc', 0.00, 25.03, 69531.40, 69531.40, '2026-03-10 11:09:43', '2026-03-10 11:09:43', 0.00, 0.00, 2),
(45, 1, 'tungnh3011.work@gmail.com', 1, 2, 'XRP/USDT', 'xrp', 10.00, 13.39, 1.34, 1.34, '2026-03-09 14:47:41', '2026-03-09 14:47:41', 0.00, 0.00, 2),
(46, 1, 'tungnh3011.work@gmail.com', 1, 1, 'XRP/USDT', 'xrp', 20.00, 26000.00, 1300.00, 1.34, '2026-03-09 14:48:03', '2026-03-09 14:48:16', 0.00, 0.00, 2),
(47, 1, 'tungnh3011.work@gmail.com', 1, 1, 'XRP/USDT', 'xrp', 10.00, 14000.00, 1400.00, 1.34, '2026-03-09 14:57:52', '2026-03-09 14:58:01', 0.00, 0.00, 2),
(48, 1, 'tungnh3011.work@gmail.com', 1, 1, 'XRP/USDT', 'xrp', 10.00, 13.00, 1.30, 0.00, '2026-03-09 14:58:44', NULL, 0.00, 0.00, 1),
(51, 506, 'kadiesnguyen@gmail.com', 1, 2, 'BTC/USDT', 'btc', 0.04, 2514.04, 69978.40, 69978.40, '2026-03-10 11:44:31', '2026-03-10 11:44:31', 0.00, 0.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tw_bbsetting`
--

CREATE TABLE `tw_bbsetting` (
  `id` int NOT NULL COMMENT '记录ID',
  `bb_kstime` varchar(30) NOT NULL COMMENT '开市时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='币币交易设置';

--
-- Dumping data for table `tw_bbsetting`
--

INSERT INTO `tw_bbsetting` (`id`, `bb_kstime`) VALUES
(1, '00:00~24:00');

-- --------------------------------------------------------

--
-- Table structure for table `tw_bill`
--

CREATE TABLE `tw_bill` (
  `id` int NOT NULL COMMENT '记录ID',
  `uid` int NOT NULL COMMENT '会员ID',
  `username` varchar(60) NOT NULL COMMENT '会员账号',
  `num` decimal(20,2) NOT NULL COMMENT '操作金额',
  `coinname` varchar(30) NOT NULL COMMENT '币名称',
  `afternum` decimal(20,2) NOT NULL COMMENT '操作后余额',
  `type` int NOT NULL COMMENT '1 Nạp\r\n2 Rút\r\n3 Đặt lệnh trade\r\n4 Trade thành công\r\n5 Mua máy đào\r\n6 Phần thưởng khi mua máy đào\r\n7 Thường điểm danh\r\n8 Giải phóng thu nhập bị đóng băng\r\n11 Trừ tiền đăng ký staking\r\n12 Tăng tiền đăng ký staking\r\n16 Hoàn tiền rút tiền (Rút tiền fail)\r\n17 Gửi tiền thành công',
  `addtime` datetime NOT NULL COMMENT '操作时间',
  `st` int NOT NULL COMMENT '1增加2减少',
  `remark` varchar(225) NOT NULL COMMENT '操作说明'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='操作日志';

--
-- Dumping data for table `tw_bill`
--

INSERT INTO `tw_bill` (`id`, `uid`, `username`, `num`, `coinname`, `afternum`, `type`, `addtime`, `st`, `remark`) VALUES
(133395, 1, 'tungnh3011.work@gmail.com', 1000.00, 'pi', 1000.00, 17, '2026-03-04 09:55:30', 1, 'Nạp tiền vào tài khoản'),
(133396, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 1000.00, 17, '2026-03-04 10:40:18', 1, 'Nạp tiền vào tài khoản'),
(133397, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 1000.00, 17, '2026-03-04 10:41:51', 1, 'Nạp tiền vào tài khoản'),
(133398, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 1000.00, 17, '2026-03-04 10:42:41', 1, 'Nạp tiền vào tài khoản'),
(133399, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 1000.00, 17, '2026-03-04 10:45:16', 1, 'Nạp tiền vào tài khoản'),
(133400, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 1000.00, 17, '2026-03-04 10:46:41', 1, 'Nạp tiền vào tài khoản'),
(133401, 1, 'tungnh3011.work@gmail.com', 50000.00, 'usdt', 99949999.00, 3, '2026-03-04 10:49:57', 2, 'Buy btc-usdt: placed an order'),
(133402, 1, 'tungnh3011.work@gmail.com', 57500.00, 'usdt', 100007499.00, 4, '2026-03-04 11:24:31', 1, 'Trade win bonus'),
(133403, 1, 'tungnh3011.work@gmail.com', 50000.00, 'usdt', 99957499.00, 3, '2026-03-04 11:32:36', 2, 'Buy btc-usdt: placed an order'),
(133404, 1, 'tungnh3011.work@gmail.com', 57500.00, 'usdt', 100014999.00, 4, '2026-03-04 11:42:31', 1, 'Trade win bonus'),
(133405, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 100014899.00, 3, '2026-03-04 13:52:21', 2, 'Buy btc-usdt: placed an order'),
(133406, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 100015009.00, 4, '2026-03-04 13:53:15', 1, 'Trade win bonus'),
(133407, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 100014909.00, 3, '2026-03-04 13:53:23', 2, 'Buy btc-usdt: placed an order'),
(133408, 1, 'tungnh3011.work@gmail.com', 50000.00, 'usdt', 99964909.00, 3, '2026-03-04 13:53:31', 2, 'Buy btc-usdt: placed an order'),
(133409, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 99965019.00, 4, '2026-03-04 13:54:16', 1, 'Trade win bonus'),
(133410, 1, 'tungnh3011.work@gmail.com', 50000.00, 'usdt', 99915019.00, 3, '2026-03-04 13:54:18', 2, 'Buy btc-usdt: placed an order'),
(133411, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 99914519.00, 3, '2026-03-04 13:54:34', 2, 'Buy btc-usdt: placed an order'),
(133412, 1, 'tungnh3011.work@gmail.com', 575.00, 'usdt', 99915094.00, 4, '2026-03-04 13:56:31', 1, 'Trade win bonus'),
(133413, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 99914594.00, 3, '2026-03-04 14:00:06', 2, 'Buy ltc-usdt: placed an order'),
(133414, 1, 'tungnh3011.work@gmail.com', 575.00, 'usdt', 99915169.00, 4, '2026-03-04 14:02:01', 1, 'Trade win bonus'),
(133415, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 99915069.00, 3, '2026-03-04 14:21:51', 2, 'Buy btc-usdt: placed an order'),
(133416, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 99915179.00, 4, '2026-03-04 14:22:46', 1, 'Trade win bonus'),
(133417, 1, 'tungnh3011.work@gmail.com', 2000.00, 'usdt', 995649.59, 5, '2026-03-06 13:27:22', 2, 'Buy mining machine: USDT Miner 1'),
(133418, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 994649.59, 5, '2026-03-06 13:38:22', 2, 'Buy mining machine: USDT Miner'),
(133419, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 994549.59, 3, '2026-03-07 08:54:43', 2, 'Buy btc-usdt: placed an order'),
(133420, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 994449.59, 3, '2026-03-07 08:55:24', 2, 'Buy btc-usdt: placed an order'),
(133421, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 994559.59, 4, '2026-03-07 08:55:46', 1, 'Trade win bonus'),
(133422, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 993559.59, 5, '2026-03-07 08:55:56', 2, 'Buy mining machine: USDT Miner'),
(133423, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 993669.59, 4, '2026-03-07 08:56:15', 1, 'Trade win bonus'),
(133424, 1, 'tungnh3011.work@gmail.com', 100000.00, 'usdt', 893669.59, 11, '2026-03-07 10:14:27', 2, 'Gói 45 ngày subscription'),
(133425, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 893169.59, 11, '2026-03-07 10:14:51', 2, 'Gửi 1 ngày subscription'),
(133426, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 893069.59, 3, '2026-03-07 12:57:29', 2, 'Buy btc-usdt: placed an order'),
(133427, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 892969.59, 2, '2026-03-07 13:13:46', 2, 'Withdrawal request'),
(133428, 1, 'tungnh3011.work@gmail.com', 222.00, 'usdt', 892747.59, 2, '2026-03-07 13:23:30', 2, 'Withdrawal request'),
(133429, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 891747.59, 11, '2026-03-07 13:30:48', 2, 'Gửi 3 ngày subscription'),
(133430, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 891805.59, 6, '2026-03-07 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133431, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 891834.39, 6, '2026-03-07 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133432, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891734.39, 3, '2026-03-07 16:42:22', 2, 'Buy btc-usdt: placed an order'),
(133433, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 890734.39, 5, '2026-03-07 16:42:38', 2, 'Buy mining machine: USDT Miner'),
(133434, 1, 'tungnh3011.work@gmail.com', 555.00, 'usdt', 890179.39, 11, '2026-03-07 16:42:44', 2, 'Gửi 1 ngày subscription'),
(133435, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 890079.39, 13, '2026-03-07 18:06:16', 2, 'Spot market buy spend USDT'),
(133436, 1, 'tungnh3011.work@gmail.com', 0.00, 'btc', 0.03, 13, '2026-03-07 18:06:16', 1, 'Spot market buy receive coin'),
(133437, 1, 'tungnh3011.work@gmail.com', 68.14, 'usdt', 890011.25, 13, '2026-03-07 18:15:08', 2, 'Spot market buy spend USDT'),
(133438, 1, 'tungnh3011.work@gmail.com', 0.00, 'btc', 0.03, 13, '2026-03-07 18:15:08', 1, 'Spot market buy receive coin'),
(133439, 1, 'tungnh3011.work@gmail.com', 135.00, 'usdt', 889876.25, 13, '2026-03-07 18:25:38', 2, 'Spot limit buy freeze USDT'),
(133440, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 889776.25, 3, '2026-03-07 20:55:13', 2, 'Buy btc-usdt: placed an order'),
(133441, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 889676.25, 3, '2026-03-07 20:56:04', 2, 'Buy bnb-usdt: placed an order'),
(133442, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 889786.25, 4, '2026-03-07 20:56:16', 1, 'Trade win bonus'),
(133443, 1, 'tungnh3011.work@gmail.com', 67.80, 'usdt', 889718.45, 13, '2026-03-07 21:02:06', 2, 'Spot market buy spend USDT'),
(133444, 1, 'tungnh3011.work@gmail.com', 0.00, 'btc', 0.03, 13, '2026-03-07 21:02:06', 1, 'Spot market buy receive coin'),
(133445, 1, 'tungnh3011.work@gmail.com', 6.78, 'usdt', 889711.67, 13, '2026-03-07 21:08:27', 2, 'Spot market buy spend USDT'),
(133446, 1, 'tungnh3011.work@gmail.com', 0.00, 'btc', 0.03, 13, '2026-03-07 21:08:27', 1, 'Spot market buy receive coin'),
(133447, 1, 'tungnh3011.work@gmail.com', 0.00, 'bnb', 0.54, 13, '2026-03-07 21:08:49', 2, 'Spot market sell spend coin'),
(133448, 1, 'tungnh3011.work@gmail.com', 0.06, 'usdt', 889711.73, 13, '2026-03-07 21:08:49', 1, 'Spot market sell receive USDT'),
(133449, 1, 'tungnh3011.work@gmail.com', 200.00, 'usdt', 889511.73, 13, '2026-03-07 21:16:07', 2, 'Spot limit buy freeze USDT'),
(133450, 1, 'tungnh3011.work@gmail.com', 0.00, 'btc', 0.03, 13, '2026-03-08 01:34:16', 1, 'Spot limit buy filled'),
(133451, 1, 'tungnh3011.work@gmail.com', 135.00, 'usdt', 889646.73, 13, '2026-03-08 01:34:16', 1, 'Spot limit buy refund'),
(133452, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 888646.73, 5, '2026-03-08 08:31:02', 2, 'Buy mining machine: USDT Miner'),
(133453, 1, 'tungnh3011.work@gmail.com', 1000.00, 'usdt', 887646.73, 5, '2026-03-08 08:31:03', 2, 'Buy mining machine: USDT Miner'),
(133454, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 887546.73, 3, '2026-03-08 08:31:17', 2, 'Buy btc-usdt: placed an order'),
(133455, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 887446.73, 3, '2026-03-08 08:31:26', 2, 'Buy btc-usdt: placed an order'),
(133456, 1, 'tungnh3011.work@gmail.com', 67.14, 'usdt', 887379.59, 13, '2026-03-08 08:31:51', 2, 'Spot market buy spend USDT'),
(133457, 1, 'tungnh3011.work@gmail.com', 0.00, 'btc', 0.03, 13, '2026-03-08 08:31:51', 1, 'Spot market buy receive coin'),
(133458, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 887279.59, 13, '2026-03-08 08:32:05', 2, 'Spot limit buy freeze USDT'),
(133459, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 887389.59, 4, '2026-03-08 08:32:31', 1, 'Trade win bonus'),
(133460, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 887418.39, 6, '2026-03-08 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133461, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 887318.39, 2, '2026-03-08 09:48:59', 2, 'Withdrawal request'),
(133462, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 887718.39, 8, '2026-03-08 10:30:01', 1, 'Gửi 1 ngày staking completed'),
(133463, 1, 'tungnh3011.work@gmail.com', 4.00, 'usdt', 887722.39, 12, '2026-03-08 10:30:01', 1, 'Gửi 1 ngày staking profit'),
(133464, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 887780.39, 6, '2026-03-08 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133465, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 887809.19, 6, '2026-03-08 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133466, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 887837.99, 6, '2026-03-08 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133467, 1, 'tungnh3011.work@gmail.com', 555.00, 'usdt', 888392.99, 8, '2026-03-08 17:00:01', 1, 'Gửi 1 ngày staking completed'),
(133468, 1, 'tungnh3011.work@gmail.com', 4.44, 'usdt', 888397.43, 12, '2026-03-08 17:00:01', 1, 'Gửi 1 ngày staking profit'),
(133469, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 888426.23, 6, '2026-03-09 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133470, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 888455.03, 6, '2026-03-09 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133471, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 888483.83, 6, '2026-03-09 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133472, 506, 'kadiesnguyen@gmail.com', 100.00, 'usdt', 100.00, 17, '2026-03-09 09:20:01', 1, 'Nạp tiền vào tài khoản'),
(133473, 1, 'tungnh3011.work@gmail.com', 13.39, 'usdt', 888470.44, 13, '2026-03-09 14:47:41', 2, 'Spot market buy spend USDT'),
(133474, 1, 'tungnh3011.work@gmail.com', 10.00, 'xrp', 10.00, 13, '2026-03-09 14:47:41', 1, 'Spot market buy receive coin'),
(133475, 1, 'tungnh3011.work@gmail.com', 26000.00, 'usdt', 862470.44, 13, '2026-03-09 14:48:03', 2, 'Spot limit buy freeze USDT'),
(133476, 1, 'tungnh3011.work@gmail.com', 20.00, 'xrp', 30.00, 13, '2026-03-09 14:48:16', 1, 'Spot limit buy filled'),
(133477, 1, 'tungnh3011.work@gmail.com', 25973.21, 'usdt', 888443.65, 13, '2026-03-09 14:48:16', 1, 'Spot limit buy refund'),
(133478, 1, 'tungnh3011.work@gmail.com', 14000.00, 'usdt', 874443.65, 13, '2026-03-09 14:57:52', 2, 'Spot limit buy freeze USDT'),
(133479, 1, 'tungnh3011.work@gmail.com', 10.00, 'xrp', 40.00, 13, '2026-03-09 14:58:01', 1, 'Spot limit buy filled'),
(133480, 1, 'tungnh3011.work@gmail.com', 13986.59, 'usdt', 888430.24, 13, '2026-03-09 14:58:01', 1, 'Spot limit buy refund'),
(133481, 1, 'tungnh3011.work@gmail.com', 13.00, 'usdt', 888417.24, 13, '2026-03-09 14:58:44', 2, 'Spot limit buy freeze USDT'),
(133482, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 888475.24, 6, '2026-03-09 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133483, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 888504.04, 6, '2026-03-09 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133484, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 888532.84, 6, '2026-03-09 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133485, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 888561.64, 6, '2026-03-10 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133486, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 888590.44, 6, '2026-03-10 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133487, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 888619.24, 6, '2026-03-10 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133488, 1, 'tungnh3011.work@gmail.com', 81.00, 'usdt', 888700.24, 17, '2026-03-10 10:32:44', 1, 'Admin cộng USDT thủ công'),
(133489, 507, 'cpttrading8386@gmail.com', 10000.00, 'usdt', 11000.00, 17, '2026-03-10 10:37:06', 1, 'Admin cộng USDT thủ công'),
(133490, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 888800.24, 16, '2026-03-10 11:05:43', 1, 'Rút tiền bị từ chối, tiền được trả lại'),
(133491, 1, 'tungnh3011.work@gmail.com', 222.00, 'usdt', 889022.24, 16, '2026-03-10 11:05:48', 1, 'Rút tiền bị từ chối, tiền được trả lại'),
(133492, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 889122.24, 16, '2026-03-10 11:05:52', 1, 'Rút tiền bị từ chối, tiền được trả lại'),
(133493, 506, 'kadiesnguyen@gmail.com', 25.03, 'usdt', 74.97, 13, '2026-03-10 11:09:43', 2, 'Spot market buy spend USDT'),
(133494, 506, 'kadiesnguyen@gmail.com', 0.00, 'btc', 0.00, 13, '2026-03-10 11:09:43', 1, 'Spot market buy receive coin'),
(133495, 506, 'kadiesnguyen@gmail.com', 18.73, 'usdt', 56.24, 13, '2026-03-10 11:10:33', 2, 'Spot market buy spend USDT'),
(133496, 506, 'kadiesnguyen@gmail.com', 0.00, 'btc', 0.00, 13, '2026-03-10 11:10:33', 1, 'Spot market buy receive coin'),
(133497, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 889022.24, 3, '2026-03-10 11:12:34', 2, 'Buy btc-usdt: placed an order'),
(133498, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 888922.24, 3, '2026-03-10 11:13:40', 2, 'Buy btc-usdt: placed an order'),
(133499, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 889032.24, 4, '2026-03-10 11:14:30', 1, 'Trade win bonus'),
(133500, 506, 'kadiesnguyen@gmail.com', 10000.00, 'usdt', 10056.24, 17, '2026-03-10 11:15:54', 1, 'Admin cộng USDT thủ công'),
(133501, 506, 'kadiesnguyen@gmail.com', 2514.04, 'usdt', 7542.19, 13, '2026-03-10 11:44:31', 2, 'Spot market buy spend USDT'),
(133502, 506, 'kadiesnguyen@gmail.com', 0.04, 'btc', 0.04, 13, '2026-03-10 11:44:31', 1, 'Spot market buy receive coin'),
(133503, 506, 'kadiesnguyen@gmail.com', 100.00, 'usdt', 7442.19, 3, '2026-03-10 12:32:00', 2, 'Buy btc-usdt: placed an order'),
(133504, 506, 'kadiesnguyen@gmail.com', 110.00, 'usdt', 7552.19, 4, '2026-03-10 12:33:00', 1, 'Trade win bonus'),
(133505, 506, 'kadiesnguyen@gmail.com', 500.00, 'usdt', 7052.19, 3, '2026-03-10 12:35:56', 2, 'Buy btc-usdt: placed an order'),
(133506, 506, 'kadiesnguyen@gmail.com', 100.00, 'usdt', 6952.19, 3, '2026-03-10 12:38:19', 2, 'Buy btc-usdt: placed an order'),
(133507, 506, 'kadiesnguyen@gmail.com', 110.00, 'usdt', 7062.19, 4, '2026-03-10 12:39:16', 1, 'Trade win bonus'),
(133508, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 888932.24, 3, '2026-03-10 12:42:01', 2, 'Buy btc-usdt: placed an order'),
(133509, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 889042.24, 4, '2026-03-10 12:43:01', 1, 'Trade win bonus'),
(133510, 1, 'tungnh3011.work@gmail.com', 5000.00, 'usdt', 884042.24, 3, '2026-03-10 12:45:29', 2, 'Buy btc-usdt: placed an order'),
(133511, 1, 'tungnh3011.work@gmail.com', 6000.00, 'usdt', 890042.24, 4, '2026-03-10 12:48:31', 1, 'Trade win bonus'),
(133512, 1, 'tungnh3011.work@gmail.com', 10000.00, 'usdt', 880042.24, 3, '2026-03-10 12:52:05', 2, 'Buy btc-usdt: placed an order'),
(133513, 1, 'tungnh3011.work@gmail.com', 12500.00, 'usdt', 892542.24, 4, '2026-03-10 12:57:01', 1, 'Trade win bonus'),
(133514, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 892442.24, 3, '2026-03-10 13:00:22', 2, 'Buy btc-usdt: placed an order'),
(133515, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 892342.24, 3, '2026-03-10 13:04:34', 2, 'Buy btc-usdt: placed an order'),
(133516, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 892242.24, 3, '2026-03-10 13:06:04', 2, 'Buy btc-usdt: placed an order'),
(133517, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 892142.24, 3, '2026-03-10 13:11:32', 2, 'Buy btc-usdt: placed an order'),
(133518, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 892252.24, 4, '2026-03-10 13:12:31', 1, 'Trade win bonus'),
(133519, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 891752.24, 3, '2026-03-10 13:15:03', 2, 'Buy btc-usdt: placed an order'),
(133520, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891652.24, 3, '2026-03-10 13:26:43', 2, 'Buy btc-usdt: placed an order'),
(133521, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 891762.24, 4, '2026-03-10 13:27:46', 1, 'Trade win bonus'),
(133522, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891662.24, 3, '2026-03-10 13:28:30', 2, 'Buy btc-usdt: placed an order'),
(133523, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891562.24, 3, '2026-03-10 13:29:36', 2, 'Buy btc-usdt: placed an order'),
(133524, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891462.24, 3, '2026-03-10 13:31:09', 2, 'Buy btc-usdt: placed an order'),
(133525, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891362.24, 3, '2026-03-10 13:32:40', 2, 'Buy btc-usdt: placed an order'),
(133526, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891262.24, 3, '2026-03-10 13:47:51', 2, 'Buy btc-usdt: placed an order'),
(133527, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891162.24, 3, '2026-03-10 13:48:59', 2, 'Buy btc-usdt: placed an order'),
(133528, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891062.24, 3, '2026-03-10 13:50:41', 2, 'Buy btc-usdt: placed an order'),
(133529, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 891172.24, 4, '2026-03-10 13:51:42', 1, 'Trade win bonus'),
(133530, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891072.24, 3, '2026-03-10 13:52:25', 2, 'Buy btc-usdt: placed an order'),
(133531, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 891182.24, 4, '2026-03-10 13:53:16', 1, 'Trade win bonus'),
(133532, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 891082.24, 3, '2026-03-10 13:54:33', 2, 'Buy btc-usdt: placed an order'),
(133533, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 890982.24, 3, '2026-03-10 13:55:53', 2, 'Buy btc-usdt: placed an order'),
(133534, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 890882.24, 3, '2026-03-10 13:57:18', 2, 'Buy btc-usdt: placed an order'),
(133535, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 890782.24, 3, '2026-03-10 13:59:54', 2, 'Buy btc-usdt: placed an order'),
(133536, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 890892.24, 4, '2026-03-10 14:00:46', 1, 'Trade win bonus'),
(133537, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 890792.24, 3, '2026-03-10 14:01:40', 2, 'Buy btc-usdt: placed an order'),
(133538, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 890692.24, 3, '2026-03-10 14:02:55', 2, 'Buy btc-usdt: placed an order'),
(133539, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 890592.24, 3, '2026-03-10 14:37:31', 2, 'Buy btc-usdt: placed an order'),
(133540, 1, 'tungnh3011.work@gmail.com', 50000.00, 'usdt', 840592.24, 3, '2026-03-10 14:40:09', 2, 'Buy btc-usdt: placed an order'),
(133541, 1, 'tungnh3011.work@gmail.com', 65000.00, 'usdt', 905592.24, 4, '2026-03-10 14:50:01', 1, 'Trade win bonus'),
(133542, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 905492.24, 3, '2026-03-10 15:42:46', 2, 'Buy btc-usdt: placed an order'),
(133543, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 905392.24, 3, '2026-03-10 15:43:55', 2, 'Buy btc-usdt: placed an order'),
(133544, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 905292.24, 3, '2026-03-10 15:45:20', 2, 'Buy btc-usdt: placed an order'),
(133545, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 905192.24, 3, '2026-03-10 16:02:08', 2, 'Buy btc-usdt: placed an order'),
(133546, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 905302.24, 4, '2026-03-10 16:03:01', 1, 'Trade win bonus'),
(133547, 506, 'kadiesnguyen@gmail.com', 100.00, 'usdt', 6962.19, 3, '2026-03-10 16:13:08', 2, 'Buy btc-usdt: placed an order'),
(133548, 506, 'kadiesnguyen@gmail.com', 100.00, 'usdt', 6862.19, 3, '2026-03-10 16:14:23', 2, 'Buy btc-usdt: placed an order'),
(133549, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 905360.24, 6, '2026-03-10 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133550, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 905389.04, 6, '2026-03-10 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133551, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 904889.04, 3, '2026-03-10 16:26:39', 2, 'Buy btc-usdt: placed an order'),
(133552, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 904389.04, 3, '2026-03-10 16:27:00', 2, 'Buy btc-usdt: placed an order'),
(133553, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 903889.04, 3, '2026-03-10 16:37:47', 2, 'Buy btc-usdt: placed an order'),
(133554, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 903389.04, 3, '2026-03-10 16:37:52', 2, 'Buy btc-usdt: placed an order'),
(133555, 1, 'tungnh3011.work@gmail.com', 575.00, 'usdt', 903964.04, 4, '2026-03-10 16:38:46', 1, 'Trade win bonus'),
(133556, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 903464.04, 3, '2026-03-10 16:41:11', 2, 'Buy btc-usdt: placed an order'),
(133557, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 902964.04, 3, '2026-03-10 16:41:14', 2, 'Buy btc-usdt: placed an order'),
(133558, 1, 'tungnh3011.work@gmail.com', 575.00, 'usdt', 903539.04, 4, '2026-03-10 16:42:16', 1, 'Trade win bonus'),
(133559, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 903567.84, 6, '2026-03-10 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133560, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 903596.64, 6, '2026-03-11 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133561, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 903625.44, 6, '2026-03-11 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133562, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 903654.24, 6, '2026-03-11 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133563, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 903554.24, 3, '2026-03-11 13:36:24', 2, 'Buy btc-usdt: placed an order'),
(133564, 1, 'tungnh3011.work@gmail.com', 110.00, 'usdt', 903664.24, 4, '2026-03-11 13:37:16', 1, 'Trade win bonus'),
(133565, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 903564.24, 3, '2026-03-11 14:30:27', 2, 'Buy btc-usdt: placed an order'),
(133566, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 903464.24, 2, '2026-03-11 15:12:40', 2, 'Withdrawal request'),
(133567, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 903522.24, 6, '2026-03-11 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133568, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 903551.04, 6, '2026-03-11 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133569, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 903579.84, 6, '2026-03-11 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133570, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 903608.64, 6, '2026-03-12 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133571, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 903637.44, 6, '2026-03-12 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133572, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 903666.24, 6, '2026-03-12 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133573, 506, 'kadiesnguyen@gmail.com', 100.00, 'usdt', 6962.19, 17, '2026-03-12 10:58:13', 1, 'Admin cộng USDT thủ công'),
(133574, 506, 'kadiesnguyen@gmail.com', 8.00, 'usdt', 6970.19, 17, '2026-03-12 10:58:38', 1, 'Admin cộng USDT thủ công'),
(133575, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 903566.24, 3, '2026-03-12 11:49:03', 2, 'Buy btc-usdt: placed an order'),
(133576, 1, 'tungnh3011.work@gmail.com', 100.00, 'usdt', 903466.24, 3, '2026-03-12 11:51:26', 2, 'Buy btc-usdt: placed an order'),
(133577, 1, 'tungnh3011.work@gmail.com', 50000.00, 'usdt', 853466.24, 3, '2026-03-12 12:12:32', 2, 'Buy btc-usdt: placed an order'),
(133578, 1, 'tungnh3011.work@gmail.com', 500.00, 'usdt', 852966.24, 3, '2026-03-12 13:23:15', 2, 'Buy btc-usdt: placed an order'),
(133579, 1, 'tungnh3011.work@gmail.com', 625.00, 'usdt', 853591.24, 4, '2026-03-12 13:24:16', 1, 'Trade win bonus'),
(133580, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 853649.24, 6, '2026-03-12 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133581, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 853678.04, 6, '2026-03-12 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133582, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 853706.84, 6, '2026-03-12 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133583, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 853735.64, 6, '2026-03-13 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133584, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 853764.44, 6, '2026-03-13 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133585, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 853793.24, 6, '2026-03-13 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133586, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 853851.24, 6, '2026-03-13 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133587, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 853880.04, 6, '2026-03-13 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133588, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 853908.84, 6, '2026-03-13 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133589, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 853937.64, 6, '2026-03-14 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133590, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 853966.44, 6, '2026-03-14 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133591, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 853995.24, 6, '2026-03-14 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133592, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 854053.24, 6, '2026-03-14 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133593, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854082.04, 6, '2026-03-14 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133594, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854110.84, 6, '2026-03-14 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133595, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854139.64, 6, '2026-03-15 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133596, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854168.44, 6, '2026-03-15 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133597, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854197.24, 6, '2026-03-15 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133598, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 854255.24, 6, '2026-03-15 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133599, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854284.04, 6, '2026-03-15 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133600, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854312.84, 6, '2026-03-15 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133601, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854341.64, 6, '2026-03-16 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133602, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854370.44, 6, '2026-03-16 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133603, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854399.24, 6, '2026-03-16 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133604, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 854457.24, 6, '2026-03-16 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133605, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854486.04, 6, '2026-03-16 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133606, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854514.84, 6, '2026-03-16 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133607, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854543.64, 6, '2026-03-17 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133608, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854572.44, 6, '2026-03-17 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133609, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854601.24, 6, '2026-03-17 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133610, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 854659.24, 6, '2026-03-17 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133611, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854688.04, 6, '2026-03-17 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133612, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854716.84, 6, '2026-03-17 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133613, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854745.64, 6, '2026-03-18 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133614, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854774.44, 6, '2026-03-18 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133615, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854803.24, 6, '2026-03-18 09:00:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133616, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 854861.24, 6, '2026-03-18 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133617, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854890.04, 6, '2026-03-18 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133618, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854918.84, 6, '2026-03-18 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133619, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854947.64, 6, '2026-03-19 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133620, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 854976.44, 6, '2026-03-19 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133621, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855005.24, 6, '2026-03-19 09:00:02', 1, 'Daily earning from Kuangji: USDT Miner'),
(133622, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 855063.24, 6, '2026-03-19 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133623, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855092.04, 6, '2026-03-19 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133624, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855120.84, 6, '2026-03-19 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133625, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855149.64, 6, '2026-03-20 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133626, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855178.44, 6, '2026-03-20 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133627, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855207.24, 6, '2026-03-20 09:05:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133628, 1, 'tungnh3011.work@gmail.com', 58.00, 'usdt', 855265.24, 6, '2026-03-20 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner 1'),
(133629, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855294.04, 6, '2026-03-20 16:15:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133630, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855322.84, 6, '2026-03-20 16:45:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133631, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855351.64, 6, '2026-03-21 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133632, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855380.44, 6, '2026-03-21 08:35:01', 1, 'Daily earning from Kuangji: USDT Miner'),
(133633, 1, 'tungnh3011.work@gmail.com', 28.80, 'usdt', 855409.24, 6, '2026-03-21 09:05:01', 1, 'Daily earning from Kuangji: USDT Miner');

-- --------------------------------------------------------

--
-- Table structure for table `tw_chat`
--

CREATE TABLE `tw_chat` (
  `id` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `tw_checkin_log`
--

CREATE TABLE `tw_checkin_log` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `streak` int UNSIGNED NOT NULL DEFAULT '0',
  `reward` decimal(18,2) NOT NULL,
  `checkin_date` date NOT NULL,
  `addtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `tw_coin`
--

CREATE TABLE `tw_coin` (
  `id` int UNSIGNED NOT NULL COMMENT 'ID',
  `name` varchar(50) NOT NULL COMMENT '币种代码',
  `czline` varchar(50) NOT NULL COMMENT '充值网络',
  `type` int NOT NULL COMMENT '币类型：1钱包币2平台币3认购币',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '币种名称',
  `sort` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `status` int UNSIGNED NOT NULL COMMENT '状态：1可用2禁用',
  `czstatus` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '充值状态：1正常2禁止',
  `czaddress` varchar(225) NOT NULL COMMENT '充值地址',
  `czminnum` float(10,2) NOT NULL COMMENT '最小充值数量',
  `txstatus` int UNSIGNED NOT NULL COMMENT '提币状态：1正常2禁止',
  `sxftype` int UNSIGNED NOT NULL DEFAULT '1' COMMENT '1按比例，2按数量',
  `txsxf` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '提币手续费比例',
  `txsxf_n` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '提币手续费数量',
  `txminnum` float(10,2) NOT NULL COMMENT '最小提币数量',
  `txmaxnum` float(10,2) NOT NULL COMMENT '最大提币数量',
  `bbsxf` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '币币手续费',
  `hysxf` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '合约手续费',
  `bank` float(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='币种配置表' ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_coin`
--

INSERT INTO `tw_coin` (`id`, `name`, `czline`, `type`, `title`, `sort`, `addtime`, `status`, `czstatus`, `czaddress`, `czminnum`, `txstatus`, `sxftype`, `txsxf`, `txsxf_n`, `txminnum`, `txmaxnum`, `bbsxf`, `hysxf`, `bank`) VALUES
(2, 'usdt', 'TRC20', 1, 'USDT', 1, '2025-11-17 11:49:00', 1, 1, 'TD4NNm9zuCHEpepMjWzJQyQEZ4Qph3SdUW', 100.00, 1, 1, 0.00, 0.00, 100.00, 1000000.00, 0.00, 0.00, 28000.00),
(3, 'btc', '', 1, 'BTC', 2, '2026-03-05 12:00:29', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(4, 'eth', '', 1, 'ETH', 3, '2026-03-05 12:00:21', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(5, 'ltc', '', 1, 'LTC', 5, '2026-03-05 12:00:13', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(6, 'sol', '', 1, 'SOL', 9, '2026-03-05 12:00:49', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(7, 'xrp', '', 1, 'XRP', 12, '2026-03-05 12:01:14', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(8, 'uni', '', 1, 'UNI', 6, '2026-03-05 12:01:39', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(9, 'paxg', '', 1, 'XAU', 7, '2026-03-05 12:01:57', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(10, 'bch', '', 1, 'BCH', 4, '2026-03-11 10:16:56', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(11, 'dot', '', 1, 'DOT', 8, '2026-03-11 10:18:32', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(12, 'trb', '', 1, 'TRB', 10, '2026-03-11 10:18:59', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(13, 'trx', '', 1, 'TRX', 11, '2026-03-11 10:19:28', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(14, 'trump', '', 1, 'TRUMP', 13, '2026-03-11 10:19:55', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(15, 'doge', '', 1, 'DOGE', 14, '2026-03-12 10:00:28', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(17, 'tao', '', 1, 'TAO', 17, '2026-03-12 10:03:10', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(18, 'link', '', 1, 'LINK', 18, '2026-03-12 10:04:42', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(19, 'apt', '', 1, 'APT', 19, '2026-03-12 10:05:15', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(20, 'bnb', '', 1, 'BNB', 19, '2026-03-12 10:06:07', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00),
(21, 'usdc', '', 1, 'USDC', 20, '2026-03-12 10:17:28', 1, 1, '', 1.00, 1, 1, 0.00, 0.00, 1.00, 1000000.00, 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `tw_coin_comment`
--

CREATE TABLE `tw_coin_comment` (
  `id` int UNSIGNED NOT NULL,
  `userid` int UNSIGNED NOT NULL,
  `coinname` varchar(50) NOT NULL,
  `content` varchar(500) NOT NULL,
  `cjz` int UNSIGNED NOT NULL,
  `tzy` int UNSIGNED NOT NULL,
  `xcd` int UNSIGNED NOT NULL,
  `sort` int UNSIGNED NOT NULL,
  `addtime` int UNSIGNED NOT NULL,
  `endtime` int UNSIGNED NOT NULL,
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `tw_coin_exchange_history`
--

CREATE TABLE `tw_coin_exchange_history` (
  `id` bigint UNSIGNED NOT NULL,
  `userid` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_coin` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_coin` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_amount` decimal(22,10) NOT NULL DEFAULT '0.0000000000',
  `to_amount` decimal(22,10) NOT NULL DEFAULT '0.0000000000',
  `from_rate_usdt` decimal(22,10) NOT NULL DEFAULT '0.0000000000',
  `to_rate_usdt` decimal(22,10) NOT NULL DEFAULT '0.0000000000',
  `usdt_amount` decimal(22,10) NOT NULL DEFAULT '0.0000000000',
  `addtime` datetime NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_coin_exchange_history`
--

INSERT INTO `tw_coin_exchange_history` (`id`, `userid`, `username`, `from_coin`, `to_coin`, `from_amount`, `to_amount`, `from_rate_usdt`, `to_rate_usdt`, `usdt_amount`, `addtime`, `status`) VALUES
(1, 1, 'tungnh3011.work@gmail.com', 'usdt', 'btc', 1000.0000000000, 0.0139082078, 1.0000000000, 71899.9900000000, 1000.0000000000, '2026-03-05 14:56:04', 1),
(2, 1, 'tungnh3011.work@gmail.com', 'usdt', 'bnb', 1000.0000000000, 1.5376335819, 1.0000000000, 650.3500000000, 1000.0000000000, '2026-03-05 15:02:30', 1),
(3, 1, 'tungnh3011.work@gmail.com', 'bnb', 'usdt', 1.0000000000, 650.5900000000, 650.5900000000, 1.0000000000, 650.5900000000, '2026-03-05 15:02:59', 1),
(4, 1, 'tungnh3011.work@gmail.com', 'usdt', 'btc', 1.0000000000, 0.0000138009, 1.0000000000, 72458.9200000000, 1.0000000000, '2026-03-05 16:49:15', 1),
(5, 1, 'tungnh3011.work@gmail.com', 'usdt', 'btc', 100.0000000000, 0.0014896270, 1.0000000000, 67130.9000000000, 100.0000000000, '2026-03-08 09:49:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_coin_json`
--

CREATE TABLE `tw_coin_json` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `data` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `sort` int UNSIGNED NOT NULL DEFAULT '0',
  `addtime` int UNSIGNED NOT NULL DEFAULT '0',
  `endtime` int UNSIGNED NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `tw_config`
--

CREATE TABLE `tw_config` (
  `id` int NOT NULL COMMENT '记录ID',
  `webname` varchar(225) NOT NULL COMMENT '网站名称',
  `webtitle` varchar(225) NOT NULL COMMENT '网络标题',
  `weblogo` varchar(225) NOT NULL COMMENT '手机端网站logo',
  `waplogo` varchar(225) NOT NULL COMMENT 'PC端网站logo',
  `webswitch` int NOT NULL COMMENT '网站开关1开2关',
  `websildea` varchar(225) NOT NULL COMMENT '手机端轮播图1',
  `websildeb` varchar(225) NOT NULL COMMENT '手机端轮播图2',
  `websildec` varchar(225) NOT NULL COMMENT '手机端轮播图2',
  `wapsilded` varchar(255) DEFAULT NULL,
  `webissue` varchar(225) NOT NULL COMMENT '手机端新币认购图片',
  `webkj` varchar(225) NOT NULL COMMENT '手机端矿机首页图片',
  `wapsildea` varchar(225) NOT NULL COMMENT 'PC端轮播图1',
  `wapsildeb` varchar(225) NOT NULL COMMENT 'PC端轮播图2',
  `wapsildec` varchar(225) NOT NULL COMMENT 'PC端轮播图3',
  `wapissue` varchar(225) NOT NULL COMMENT 'PC端新币认购图片',
  `wapkj` varchar(225) NOT NULL COMMENT 'PC端矿机首页图片',
  `webtjimgs` varchar(225) NOT NULL COMMENT '手机端推荐页面logo图片',
  `waptjimgs` varchar(225) NOT NULL COMMENT 'PC端推荐页面logo图片',
  `smsemail` varchar(60) NOT NULL COMMENT '短信发送邮箱',
  `emailcode` varchar(60) NOT NULL COMMENT '邮箱授权码',
  `smstemple` varchar(225) NOT NULL COMMENT '短信验证码模板',
  `tgtext` varchar(225) NOT NULL COMMENT '推荐页面推广语',
  `gfemail` varchar(100) NOT NULL COMMENT '官方客服邮箱',
  `footertext` varchar(225) NOT NULL COMMENT 'PC端下方文字',
  `regswitch` int NOT NULL COMMENT '注册开关',
  `tbswitch` int NOT NULL COMMENT '提币开关',
  `regjl` int NOT NULL COMMENT '注册是赠送体验矿机',
  `tymoney` decimal(20,2) NOT NULL COMMENT '注册赠送的体验金',
  `kefu` varchar(250) NOT NULL COMMENT '客服链接',
  `appeal` varchar(255) NOT NULL,
  `bank_name` text,
  `bank_acc_no` text,
  `bank_acc_name` text,
  `checkin_rewards` varchar(255) DEFAULT NULL,
  `checkin_notify` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `checkin_notify_status` int NOT NULL DEFAULT '1',
  `telegram` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='网站配置表';

--
-- Dumping data for table `tw_config`
--

INSERT INTO `tw_config` (`id`, `webname`, `webtitle`, `weblogo`, `waplogo`, `webswitch`, `websildea`, `websildeb`, `websildec`, `wapsilded`, `webissue`, `webkj`, `wapsildea`, `wapsildeb`, `wapsildec`, `wapissue`, `wapkj`, `webtjimgs`, `waptjimgs`, `smsemail`, `emailcode`, `smstemple`, `tgtext`, `gfemail`, `footertext`, `regswitch`, `tbswitch`, `regjl`, `tymoney`, `kefu`, `appeal`, `bank_name`, `bank_acc_no`, `bank_acc_name`, `checkin_rewards`, `checkin_notify`, `checkin_notify_status`, `telegram`) VALUES
(1, 'Mitrade Forex', 'Mitrade Forex', '6852b21d88c70.jpg', '6852b222a427e.jpg', 1, '68e11621ba89a.jpg', '685638d939f71.jpg', '6852b28e33382.png', '629c72e4af37b.png', '629733ae57b0f.png', '629734cf34b70.png', '6894588130f94.jpg', '685638d3e043b.jpg', '6852b2a4ada73.png', '61517780a59b2.jpg', '62973219c958a.png', '6852b270ad660.jpg', '6852b272cce9f.jpg', 'finamlosangeles@163.com', 'NLLYWEUOCXVFJAAG', '【FPTBIT】Your Verification code is', 'Chào mừng đến với website Mitrade Forex', 'admin@gmail.com', 'Chào mừng đến với website Mitrade Forex', 1, 2, 2, 0.00, 'admin1@gmail.com', 'admin2@gmail.com', 'VIKKI BANK', '383321189', 'NGUYEN THI KIM TUYEN', '0.8,1.8', '&lt;strong&gt;Khuyến Mãi Tích Lũy&lt;/strong&gt; &lt;br /&gt;\r\nNạp 5.000 USD nhận ngay 388 USD &lt;br /&gt;\r\nNạp 10.000 USD nhận ngay 888 USD &lt;br /&gt;\r\nNạp 20.000 USD nhận ngay 1.888 USD &lt;br /&gt;\r\nNạp 50.000 USD nhận ngay 3.888 USD &lt;br /&gt;\r\nNạp 100.000 USD nhận ngay 8.888 USD &lt;br /&gt;\r\nNạp 200.000 USD nhận ngay 18.888 USD &lt;br /&gt;\r\nNạp 500.000 USD nhận ngay 38.888 USD &lt;br /&gt;\r\nNạp 800.000 USD nhận ngay 88.888 USD &lt;br /&gt;\r\n&lt;strong&gt;Lưu ý :&lt;/strong&gt; Liên hệ hỗ trợ chăm sóc khách hàng để được hỗ trợ nhận khuyến mãi tích lũy !', 1, 'https://t.me/hehe');

-- --------------------------------------------------------

--
-- Table structure for table `tw_content`
--

CREATE TABLE `tw_content` (
  `id` int NOT NULL COMMENT 'ID',
  `title` varchar(225) NOT NULL COMMENT '标题',
  `img` varchar(225) NOT NULL COMMENT '公告图片',
  `content` text NOT NULL COMMENT '内容',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `status` int NOT NULL COMMENT '状态1显示2隐藏'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='公告内容';

--
-- Dumping data for table `tw_content`
--

INSERT INTO `tw_content` (`id`, `title`, `img`, `content`, `addtime`, `status`) VALUES
(18, 'Sạc lại nhanh USDT', '', '<script>\r\nwindow.location = \"https://www.afibbjgrubxx.com/join/XHJWZ\";\r\n</script>', '2025-09-29 20:58:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_ctmarket`
--

CREATE TABLE `tw_ctmarket` (
  `id` int UNSIGNED NOT NULL COMMENT 'ID',
  `coinname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '市场名称',
  `symbol` varchar(50) NOT NULL COMMENT '交易对',
  `title` varchar(50) NOT NULL COMMENT 'BTC/USDT格式',
  `status` int NOT NULL COMMENT '状态1正常2禁用',
  `state` int NOT NULL DEFAULT '1' COMMENT '交易状态1正常2禁止',
  `sort` int NOT NULL COMMENT '排序',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `logo` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='合约交易对配置' ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tw_ctmarket`
--

INSERT INTO `tw_ctmarket` (`id`, `coinname`, `name`, `symbol`, `title`, `status`, `state`, `sort`, `addtime`, `logo`) VALUES
(1, 'btc', 'btcusdt', 'BTCUSDT', 'BTC/USDT', 1, 1, 1, '2025-06-20 12:20:29', NULL),
(2, 'eth', 'ethusdt', 'eth-usdt', 'ETH/USDT', 1, 1, 2, '2025-06-18 21:40:07', NULL),
(3, 'bch', 'bchusdt', 'bch-usdt', 'BCH/USDT', 1, 1, 3, '2025-06-21 10:48:55', NULL),
(4, 'ltc', 'ltcusdt', 'ltc-usdt', 'LTC/USDT', 1, 1, 4, '2025-06-21 10:48:47', NULL),
(5, 'uni', 'uniusdt', 'uni-usdt', 'UNI/USDT', 1, 1, 5, '2021-08-31 17:21:45', NULL),
(11, 'xrp', 'xrpusdt', 'xrp-usdt', 'XRP/USDT', 1, 1, 11, '2025-06-16 22:18:58', NULL),
(6, 'xau', 'xauusdt', 'xau-usdt', 'XAU/USDT', 0, 1, 6, '2021-08-31 17:22:25', NULL),
(7, 'dot', 'dotusdt', 'dot-usdt', 'DOT/USDT', 1, 1, 7, '2021-09-20 23:52:18', NULL),
(10, 'trx', 'trxusdt', 'trx-usdt', 'TRX/USDT', 1, 1, 10, '2025-06-16 22:18:36', NULL),
(9, 'trb', 'trbusdt', 'trb-usdt', 'TRB/USDT', 1, 1, 9, '2025-06-16 22:18:13', NULL),
(8, 'sol', 'solusdt', 'sol-usdt', 'SOL/USDT', 1, 1, 8, '2025-06-16 22:17:23', NULL),
(12, 'trump', 'trumpusdt', 'trump-usdt', 'TRUMP/USDT', 1, 1, 12, '2025-06-16 22:18:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tw_daohang`
--

CREATE TABLE `tw_daohang` (
  `id` int UNSIGNED NOT NULL COMMENT '自增id',
  `lang` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'zh-cn',
  `name` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '名称',
  `title` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT 'url',
  `sort` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  `endtime` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '编辑时间',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '状态',
  `get_login` tinyint(1) NOT NULL DEFAULT '0',
  `access` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=gbk ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_daohang`
--

INSERT INTO `tw_daohang` (`id`, `lang`, `name`, `title`, `url`, `sort`, `addtime`, `endtime`, `status`, `get_login`, `access`) VALUES
(1, 'zh-cn', 'Trade_cn', '现货交易', '/Trade', 1, 1532772516, 0, 1, 0, 0),
(2, 'en-us', 'Trade_cn', 'TRADE', '/Trade', 1, 1532772535, 0, 1, 0, 0),
(3, 'zh-cn', 'C2C_cn', 'C2C交易', '/Exchange', 2, 1532772558, 0, 1, 0, 0),
(4, 'en-us', 'C2C_en', 'C2C', '/Exchange', 2, 1532772569, 0, 1, 0, 0),
(5, 'en-us', 'ICO_en', 'ICO', '/Issue', 9, 1532772585, 0, 1, 0, 0),
(6, 'zh-cn', 'ICO_cn', '新币预热', '/Issue', 9, 1532772596, 0, 1, 0, 0),
(7, 'zh-cn', 'VOTE_cn', '投票上币', '/Vote', 7, 1532772649, 0, 0, 0, 0),
(8, 'en-us', 'VOTE_en', 'VOTE', '/Vote', 7, 1532772675, 0, 0, 0, 0),
(11, 'zh-cn', 'Help_cn', '帮助中心', '/Support', 99, 1532772738, 0, 1, 0, 0),
(12, 'en-us', 'Help_en', 'HELP', '/Support', 99, 1532772753, 0, 1, 0, 0),
(15, 'zh-cn', 'Financing_cn', '币生币', '/Financing', 5, 1541438000, 0, 1, 0, 0),
(16, 'en-us', 'Financing_en', 'DEPOSIT REWARD', '/Financing', 5, 1541472739, 0, 1, 0, 0),
(17, 'zh-cn', 'OTC交易', 'OTC交易', '/Markethouse', 3, 1597053925, 0, 1, 0, 0),
(18, 'en-us', 'OTC', 'OTC', '/Markethouse', 3, 1597054768, 0, 1, 0, 0),
(19, 'zh-cn', '矿池挖矿', '矿池挖矿', '/Orepool', 6, 1597540099, 0, 1, 0, 0),
(20, 'en-us', 'Orepool', 'Orepool', '/Orepool', 6, 1597540154, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tw_djprofit`
--

CREATE TABLE `tw_djprofit` (
  `id` int NOT NULL COMMENT '记录ID',
  `uid` int NOT NULL COMMENT '会员ID',
  `username` varchar(30) NOT NULL COMMENT '会员账号',
  `num` decimal(12,2) NOT NULL COMMENT '冻结额度',
  `coin` varchar(30) NOT NULL COMMENT '币名称',
  `status` int NOT NULL COMMENT '状态1冻结中2已释放',
  `addtime` datetime NOT NULL COMMENT '冻结时间',
  `addday` date NOT NULL COMMENT '冻结日期',
  `thawtime` datetime NOT NULL COMMENT '解冻结时间',
  `thawday` date NOT NULL COMMENT '解冻日期',
  `remark` varchar(225) NOT NULL COMMENT '冻结说明'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='数字币冻结记录表';

-- --------------------------------------------------------

--
-- Table structure for table `tw_footer`
--

CREATE TABLE `tw_footer` (
  `id` int UNSIGNED NOT NULL,
  `lang` varchar(20) NOT NULL DEFAULT 'zh-cn',
  `name` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `img` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(200) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `sort` int UNSIGNED NOT NULL DEFAULT '0',
  `addtime` int UNSIGNED NOT NULL DEFAULT '0',
  `endtime` int UNSIGNED NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `get_login` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_footer`
--

INSERT INTO `tw_footer` (`id`, `lang`, `name`, `title`, `url`, `img`, `type`, `remark`, `sort`, `addtime`, `endtime`, `status`, `get_login`) VALUES
(1, 'zh-cn', 'About_cn', '关于我们', '/Support/index/articles/cid/6/id/15.html', '', '', '', 0, 1532768022, 0, 1, 0),
(2, 'en-us', 'About_en', 'About us', '/Support/index/articles/cid/6/id/15.html', '', '', '', 0, 1532768090, 0, 1, 0),
(3, 'zh-cn', 'Help_cn', '帮助中心', '/Support/index.html', '', '', '', 0, 1532768213, 0, 1, 0),
(4, 'en-us', 'Help_en', 'Help center', '/Support/index.html', '', '', '', 0, 1532768248, 0, 1, 0),
(5, 'zh-cn', 'Fees_cn', '交易费率', '/Support/Pages/fee.html', '', '', '', 0, 1532768275, 0, 1, 0),
(6, 'en-us', 'Fees_en', 'Fees', '/Support/Pages/fee.html', '', '', '', 0, 1532768435, 0, 1, 0),
(7, 'zh-cn', 'Token_cn', '上币申请', '/Support/index/articles/cid/6/id/22.html', '', '', '', 0, 1532768722, 0, 1, 0),
(8, 'en-us', 'Token_en', 'Business cooperation', '/Support/index/articles/cid/6/id/22.html', '', '', '', 0, 1532768911, 0, 1, 0),
(9, 'zh-cn', 'Legal_cn', '用户协议', '/Support/index/articles/cid/7/id/18.html', '', '', '', 0, 1532769282, 0, 1, 0),
(10, 'en-us', 'Legal_en', 'User Agreement', '/Support/index/articles/cid/7/id/18.html', '', '', '', 0, 1532769307, 0, 1, 0),
(11, 'zh-cn', 'Contact_cn', '联系我们', '/Support/index/articles/cid/6/id/16.html', '', '', '', 0, 1532769356, 0, 1, 0),
(12, 'en-us', 'Contact_en', 'Contact Us', '/Support/index/articles/cid/6/id/16.html', '', '', '', 0, 1532769373, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tw_hyorder`
--

CREATE TABLE `tw_hyorder` (
  `id` int NOT NULL COMMENT 'ID',
  `uid` int NOT NULL COMMENT '会员ID',
  `username` varchar(60) NOT NULL COMMENT '会员账号',
  `num` float(10,2) NOT NULL COMMENT '投资金额',
  `hybl` float(10,2) NOT NULL COMMENT '盈亏比例',
  `hyzd` int NOT NULL COMMENT '合约涨跌1买涨2买跌',
  `coinname` varchar(30) NOT NULL COMMENT '交易对',
  `status` int NOT NULL COMMENT '状态：1待结算2已结算3无效结算',
  `is_win` int NOT NULL COMMENT '盈亏状态：1盈利2亏损',
  `buytime` datetime NOT NULL COMMENT '购买时间',
  `selltime` datetime DEFAULT NULL COMMENT '结算时间',
  `intselltime` int NOT NULL COMMENT '结算时间戳',
  `buyprice` decimal(12,2) DEFAULT NULL COMMENT '建仓单价',
  `sellprice` decimal(12,2) DEFAULT NULL COMMENT '结算单价',
  `ploss` decimal(12,2) NOT NULL COMMENT '盈亏金额',
  `time` int NOT NULL COMMENT '结算分钟数',
  `kongyk` int NOT NULL COMMENT '控制盈亏1盈利2亏损0未指定',
  `invit` varchar(60) NOT NULL COMMENT '邀请码(上级)',
  `tznum` int NOT NULL DEFAULT '0' COMMENT '0未通知，1已通知'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='合约订单表';

--
-- Dumping data for table `tw_hyorder`
--

INSERT INTO `tw_hyorder` (`id`, `uid`, `username`, `num`, `hybl`, `hyzd`, `coinname`, `status`, `is_win`, `buytime`, `selltime`, `intselltime`, `buyprice`, `sellprice`, `ploss`, `time`, `kongyk`, `invit`, `tznum`) VALUES
(1962, 1, 'tungnh3011.work@gmail.com', 50000.00, 15.00, 2, 'BTC-USDT', 2, 1, '2026-03-04 10:49:57', '2026-03-04 10:59:57', 1772596797, 67614.40, 67560.90, 57500.00, 10, 0, '0', 1),
(1963, 1, 'tungnh3011.work@gmail.com', 50000.00, 15.00, 2, 'BTC-USDT', 2, 1, '2026-03-04 11:32:36', '2026-03-04 11:42:36', 1772599356, 67625.90, 67524.80, 57500.00, 10, 0, '0', 1),
(1964, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-04 13:52:21', '2026-03-04 13:53:21', 1772607201, 68507.20, 68508.40, 110.00, 1, 0, '0', 0),
(1965, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-04 13:53:23', '2026-03-04 13:54:23', 1772607263, 68504.70, 68505.70, 110.00, 1, 0, '0', 0),
(1966, 1, 'tungnh3011.work@gmail.com', 50000.00, 30.00, 2, 'BTC-USDT', 2, 2, '2026-03-04 13:53:31', '2026-03-04 14:03:31', 1772607811, 68512.30, 68607.10, 50000.00, 10, 0, '0', 0),
(1967, 1, 'tungnh3011.work@gmail.com', 50000.00, 30.00, 2, 'BTC-USDT', 2, 2, '2026-03-04 13:54:18', '2026-03-04 14:04:18', 1772607858, 68500.30, 68550.40, 50000.00, 10, 0, '0', 0),
(1968, 1, 'tungnh3011.work@gmail.com', 500.00, 15.00, 2, 'BTC-USDT', 2, 1, '2026-03-04 13:54:34', '2026-03-04 13:56:34', 1772607394, 68493.80, 68484.10, 575.00, 2, 0, '0', 0),
(1969, 1, 'tungnh3011.work@gmail.com', 500.00, 15.00, 1, 'LTC-USDT', 2, 1, '2026-03-04 14:00:06', '2026-03-04 14:02:06', 1772607726, 54.84, 54.89, 575.00, 2, 0, '0', 0),
(1970, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-04 14:21:51', '2026-03-04 14:22:51', 1772608971, 68458.00, 68468.90, 110.00, 1, 0, '0', 0),
(1971, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-07 08:54:43', '2026-03-07 08:55:43', 1772848543, 68326.90, 68327.60, 110.00, 1, 0, '0', 0),
(1972, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 2, 'BTC-USDT', 2, 1, '2026-03-07 08:55:24', '2026-03-07 08:56:24', 1772848584, 68324.80, 68315.40, 110.00, 1, 0, '0', 0),
(1973, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-07 12:57:29', '2026-03-07 12:58:29', 1772863109, 68031.20, 67851.60, 100.00, 1, 0, '0', 0),
(1974, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-07 16:42:22', '2026-03-07 16:43:22', 1772876602, 67983.90, 67960.70, 100.00, 1, 0, '0', 0),
(1975, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-07 20:55:13', '2026-03-07 20:56:13', 1772891773, 67923.70, 67926.50, 110.00, 1, 0, '0', 0),
(1976, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BNB-USDT', 2, 2, '2026-03-07 20:56:04', '2026-03-07 20:57:04', 1772891824, 626.70, 626.70, 100.00, 1, 0, '0', 0),
(1977, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-08 08:31:17', '2026-03-08 08:32:17', 1772933537, 67142.50, 67118.60, 100.00, 1, 0, '0', 0),
(1978, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 2, 'BTC-USDT', 2, 1, '2026-03-08 08:31:26', '2026-03-08 08:32:26', 1772933546, 67126.20, 67108.80, 110.00, 1, 0, '0', 0),
(1979, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 11:12:34', '2026-03-10 11:13:34', 1773116014, 69728.90, 69717.50, 100.00, 1, 0, '0', 1),
(1980, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 11:13:40', '2026-03-10 11:14:40', 1773116080, 69731.00, 69739.00, 110.00, 1, 0, '0', 0),
(1981, 506, 'kadiesnguyen@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 12:32:00', '2026-03-10 12:33:00', 1773120780, 70089.70, 70095.50, 110.00, 1, 0, '0', 0),
(1982, 506, 'kadiesnguyen@gmail.com', 500.00, 15.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 12:35:56', '2026-03-10 12:37:56', 1773121076, 70094.60, 69982.20, 500.00, 2, 0, '0', 0),
(1983, 506, 'kadiesnguyen@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 12:38:19', '2026-03-10 12:39:19', 1773121159, 69960.00, 69999.60, 110.00, 1, 0, '0', 1),
(1984, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 12:42:01', '2026-03-10 12:43:01', 1773121381, 69941.50, 69949.80, 110.00, 1, 0, '0', 0),
(1985, 1, 'tungnh3011.work@gmail.com', 5000.00, 20.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 12:45:29', '2026-03-10 12:48:29', 1773121709, 69943.40, 69946.00, 6000.00, 3, 0, '0', 0),
(1986, 1, 'tungnh3011.work@gmail.com', 10000.00, 25.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 12:52:05', '2026-03-10 12:57:05', 1773122225, 69934.10, 69967.10, 12500.00, 5, 0, '0', 0),
(1987, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 13:00:22', '2026-03-10 13:01:22', 1773122482, 69971.10, 69928.60, 100.00, 1, 0, '0', 0),
(1988, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 13:04:34', '2026-03-10 13:05:34', 1773122734, 69916.60, 69899.90, 100.00, 1, 0, '0', 0),
(1989, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 13:06:04', '2026-03-10 13:07:04', 1773122824, 69921.20, 69915.60, 100.00, 1, 0, '0', 0),
(1990, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 13:11:32', '2026-03-10 13:12:32', 1773123152, 69863.20, 69880.70, 110.00, 1, 0, '0', 0),
(1991, 1, 'tungnh3011.work@gmail.com', 500.00, 15.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 13:15:03', '2026-03-10 13:17:03', 1773123423, 69879.20, 69815.70, 500.00, 2, 0, '0', 0),
(1992, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 13:26:43', '2026-03-10 13:27:43', 1773124063, 70002.10, 70115.90, 110.00, 1, 0, '0', 0),
(1993, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 13:28:30', '2026-03-10 13:29:30', 1773124170, 70154.40, 70134.90, 100.00, 1, 0, '0', 0),
(1994, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 13:29:36', '2026-03-10 13:30:36', 1773124236, 70130.90, 70107.50, 100.00, 1, 0, '0', 0),
(1995, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 13:31:09', '2026-03-10 13:32:09', 1773124329, 70156.30, 70112.60, 100.00, 1, 0, '0', 0),
(1996, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 13:32:40', '2026-03-10 13:33:40', 1773124420, 70075.80, 70039.90, 100.00, 1, 0, '0', 0),
(1997, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 2, 'BTC-USDT', 2, 2, '2026-03-10 13:47:51', '2026-03-10 13:48:51', 1773125331, 69965.50, 69974.00, 100.00, 1, 0, '0', 0),
(1998, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 2, 'BTC-USDT', 2, 2, '2026-03-10 13:48:59', '2026-03-10 13:49:59', 1773125399, 69970.60, 69987.50, 100.00, 1, 0, '0', 0),
(1999, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 2, 'BTC-USDT', 2, 1, '2026-03-10 13:50:41', '2026-03-10 13:51:41', 1773125501, 70018.50, 69957.60, 110.00, 1, 0, '0', 0),
(2000, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 13:52:25', '2026-03-10 13:53:25', 1773125605, 69923.40, 69929.70, 110.00, 1, 0, '0', 0),
(2001, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 13:54:33', '2026-03-10 13:55:33', 1773125733, 69927.20, 69893.00, 100.00, 1, 0, '0', 0),
(2002, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 2, 'BTC-USDT', 2, 2, '2026-03-10 13:55:53', '2026-03-10 13:56:53', 1773125813, 69888.00, 69890.50, 100.00, 1, 0, '0', 0),
(2003, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 2, 'BTC-USDT', 2, 2, '2026-03-10 13:57:18', '2026-03-10 13:58:18', 1773125898, 69884.50, 69892.40, 100.00, 1, 0, '0', 0),
(2004, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 2, 'BTC-USDT', 2, 1, '2026-03-10 13:59:54', '2026-03-10 14:00:54', 1773126054, 69969.90, 69933.30, 110.00, 1, 0, '0', 0),
(2005, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 2, 'BTC-USDT', 2, 2, '2026-03-10 14:01:40', '2026-03-10 14:02:40', 1773126160, 69858.00, 69932.60, 100.00, 1, 0, '0', 0),
(2006, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 14:02:55', '2026-03-10 14:03:55', 1773126235, 69898.40, 69835.80, 100.00, 1, 0, '0', 0),
(2007, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 14:37:31', '2026-03-10 14:38:31', 1773128311, 70303.90, 70296.50, 100.00, 1, 0, '0', 0),
(2008, 1, 'tungnh3011.work@gmail.com', 50000.00, 30.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 14:40:09', '2026-03-10 14:50:09', 1773129009, 70351.30, 70409.30, 65000.00, 10, 0, '0', 0),
(2009, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 15:42:46', '2026-03-10 15:43:46', 1773132226, 71012.50, 70983.60, 100.00, 1, 0, '0', 1),
(2010, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 15:43:55', '2026-03-10 15:44:55', 1773132295, 70971.40, 70939.90, 100.00, 1, 0, '0', 0),
(2011, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 15:45:20', '2026-03-10 15:46:20', 1773132380, 70881.70, 70818.30, 100.00, 1, 0, '0', 0),
(2012, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-10 16:02:08', '2026-03-10 16:03:08', 1773133388, 70712.40, 70723.50, 110.00, 1, 0, '0', 0),
(2013, 506, 'kadiesnguyen@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 16:13:08', '2026-03-10 16:14:08', 1773134048, 70833.20, 70822.20, 100.00, 1, 0, '0', 0),
(2014, 506, 'kadiesnguyen@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 16:14:23', '2026-03-10 16:15:23', 1773134123, 70820.30, 70800.00, 100.00, 1, 0, '0', 0),
(2015, 1, 'tungnh3011.work@gmail.com', 500.00, 15.00, 2, 'BTC-USDT', 2, 2, '2026-03-10 16:26:39', '2026-03-10 16:27:39', 1773134859, 71091.70, 71099.60, 500.00, 1, 0, '0', 1),
(2016, 1, 'tungnh3011.work@gmail.com', 500.00, 15.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 16:27:00', '2026-03-10 16:28:00', 1773134880, 71090.30, 71216.70, 500.00, 1, 0, '0', 1),
(2017, 1, 'tungnh3011.work@gmail.com', 500.00, 15.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 16:37:47', '2026-03-10 16:38:47', 1773135527, 71120.10, 71094.20, 500.00, 1, 0, '0', 1),
(2018, 1, 'tungnh3011.work@gmail.com', 500.00, 15.00, 2, 'BTC-USDT', 2, 1, '2026-03-10 16:37:52', '2026-03-10 16:38:52', 1773135532, 71100.60, 71094.20, 575.00, 1, 0, '0', 1),
(2019, 1, 'tungnh3011.work@gmail.com', 500.00, 15.00, 2, 'BTC-USDT', 2, 1, '2026-03-10 16:41:11', '2026-03-10 16:42:11', 1773135731, 71121.40, 71092.80, 575.00, 1, 0, '0', 1),
(2020, 1, 'tungnh3011.work@gmail.com', 500.00, 15.00, 1, 'BTC-USDT', 2, 2, '2026-03-10 16:41:14', '2026-03-10 16:42:14', 1773135734, 71126.10, 71092.80, 500.00, 1, 0, '0', 1),
(2021, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 1, '2026-03-11 13:36:24', '2026-03-11 13:37:24', 1773211044, 69911.20, 69884.90, 110.00, 1, 0, '0', 0),
(2022, 1, 'tungnh3011.work@gmail.com', 100.00, 10.00, 1, 'BTC-USDT', 2, 2, '2026-03-11 14:30:27', '2026-03-11 14:31:27', 1773214287, 69778.90, 69758.90, 100.00, 1, 0, '0', 0),
(2023, 1, 'tungnh3011.work@gmail.com', 100.00, 25.00, 1, 'BTC-USDT', 2, 2, '2026-03-12 11:49:03', '2026-03-12 11:49:33', 1773290973, 69353.10, 69337.40, 100.00, 1, 0, '0', 1),
(2024, 1, 'tungnh3011.work@gmail.com', 100.00, 25.00, 1, 'BTC-USDT', 2, 2, '2026-03-12 11:51:26', '2026-03-12 11:51:56', 1773291116, 69345.70, 69349.80, 100.00, 1, 0, '0', 1),
(2025, 1, 'tungnh3011.work@gmail.com', 50000.00, 60.00, 1, 'BTC-USDT', 2, 2, '2026-03-12 12:12:32', '2026-03-12 12:15:32', 1773292532, 69447.40, 69375.80, 50000.00, 3, 1, '0', 1),
(2026, 1, 'tungnh3011.work@gmail.com', 500.00, 25.00, 2, 'BTC-USDT', 2, 1, '2026-03-12 13:23:15', '2026-03-12 13:24:15', 1773296655, 69526.60, 69538.20, 625.00, 1, 1, '0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_hysetting`
--

CREATE TABLE `tw_hysetting` (
  `id` int NOT NULL COMMENT 'ID',
  `hy_sxf` float(10,2) NOT NULL COMMENT '交易手续费',
  `hy_time` varchar(225) NOT NULL COMMENT '合约时间组',
  `hy_ykbl` varchar(225) NOT NULL COMMENT '盈亏比例组',
  `hy_tzed` varchar(225) NOT NULL COMMENT '投资额度组',
  `hy_kstime` varchar(225) NOT NULL COMMENT '开市时间',
  `hy_ksid` varchar(225) NOT NULL COMMENT '亏损ID组',
  `hy_ylid` varchar(225) NOT NULL COMMENT '盈利ID组',
  `hy_fkgl` varchar(225) NOT NULL COMMENT '风控概率组',
  `hy_min` varchar(255) NOT NULL COMMENT '合约最低投资额',
  `checkin_rewards` varchar(255) DEFAULT '0,0,0,0,0,0,0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='合约参数';

--
-- Dumping data for table `tw_hysetting`
--

INSERT INTO `tw_hysetting` (`id`, `hy_sxf`, `hy_time`, `hy_ykbl`, `hy_tzed`, `hy_kstime`, `hy_ksid`, `hy_ylid`, `hy_fkgl`, `hy_min`, `checkin_rewards`) VALUES
(1, 0.00, '0.5,1,1.5,2,3,6,7,9,10', '25,25,35,45,60,70,75,80,90', '100,500,5000,10000,50000,100000,500000', '00:00~24:00', '', 'minhhieu2912@gmail.com', '50', '100', '10,20,30,40,50,60,70');

-- --------------------------------------------------------

--
-- Table structure for table `tw_hy_result_queue`
--

CREATE TABLE `tw_hy_result_queue` (
  `id` bigint UNSIGNED NOT NULL,
  `round_no` bigint UNSIGNED NOT NULL COMMENT 'So phien trong hang doi',
  `result` enum('WIN','LOSS') NOT NULL COMMENT 'Ket qua ep',
  `addtime` int UNSIGNED NOT NULL COMMENT 'Unix timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tw_issue`
--

CREATE TABLE `tw_issue` (
  `id` int UNSIGNED NOT NULL COMMENT '记录ID',
  `name` varchar(255) NOT NULL COMMENT '认购项目标题',
  `min` decimal(20,2) UNSIGNED NOT NULL COMMENT '最小购买值',
  `max` decimal(20,2) UNSIGNED NOT NULL COMMENT '最大购买值',
  `open` int DEFAULT NULL COMMENT 'Thời gian mở từ bắt đầu đến kết thúc (INT)',
  `percent` float DEFAULT NULL COMMENT 'Tỷ lệ đạt được',
  `imgs` varchar(225) NOT NULL COMMENT '币图片',
  `content` text NOT NULL COMMENT '币说明',
  `addtime` datetime NOT NULL COMMENT '发布日期',
  `status` int NOT NULL COMMENT '状态1正常2隐藏',
  `state` int NOT NULL COMMENT '状态1启用认购2禁止认购'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='认购发行表' ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_issue`
--

INSERT INTO `tw_issue` (`id`, `name`, `min`, `max`, `open`, `percent`, `imgs`, `content`, `addtime`, `status`, `state`) VALUES
(3, 'Gói 45 ngày', 100000.00, 999999.00, 45, 25, '68cad4d4cd8e1.png', 'Sự ra đời của blockchain đánh dấu sự khởi đầu của việc xây dựng một Internet thực sự đáng tin cậy. Bằng cách xem xét kỹ lưỡng sự phát triển và phát triển của blockchain, có thể thấy rằng trọng tâm của blockchain là nó có thể thiết lập sự tin cậy ngang hàng đáng tin cậy trong mạng, do đó quá trình chuyển giao giá trị loại bỏ sự can thiệp của bên trung gian, không chỉ tiết lộ thông tin mà còn bảo vệ quyền riêng tư, đưa ra quyết định chung và bảo vệ quyền và lợi ích cá nhân. Cơ chế này cải thiện hiệu quả tương tác giá trị và giảm chi phí. Về mặt kinh tế, mô hình tương tác giá trị mới do blockchain tạo ra dựa trên \"sự tập trung yếu\", nhưng điều này không có nghĩa là sự biến mất hoàn toàn của các \"trung tâm\" khác nhau trong xã hội truyền thống và một số lượng lớn blockchain sẽ xuất hiện trong tương lai. Hệ thống \"đa trung tâm\" dựa trên chuỗi liên minh, chuỗi riêng hoặc chuỗi lai. Blockchain sẽ cải thiện hơn nữa hiệu quả hoạt động của \"trung tâm\" và giảm đáng kể chi phí của nó. Về mặt kỹ thuật, chúng tôi tin rằng blockchain là một hệ thống công nghệ được nhiều bên cùng duy trì, lưu trữ dữ liệu trong cấu trúc blockchain, sử dụng mật mã để đảm bảo an ninh truyền tải và truy cập, có thể đạt được lưu trữ dữ liệu nhất quán, không bị giả mạo và không thể chối bỏ. Công nghệ này đã mang đến không gian vô hạn cho trí tưởng tượng cho thế giới. Sự quan tâm toàn cầu đối với blockchain tiếp tục nóng lên. Các nền kinh tế lớn trên toàn cầu đã bắt đầu nghiên cứu công nghệ blockchain và xu hướng phát triển từ cấp chiến lược quốc gia.', '2025-09-17 22:33:41', 2, 1),
(4, 'Gửi 15 ngày', 10000.00, 999999.00, 15, 15, '68cad4e55dfdb.png', 'Sự ra đời của blockchain đánh dấu sự khởi đầu của việc xây dựng một Internet thực sự đáng tin cậy. Bằng cách xem xét kỹ lưỡng sự phát triển và phát triển của blockchain, có thể thấy rằng trọng tâm của blockchain là nó có thể thiết lập sự tin cậy ngang hàng đáng tin cậy trong mạng, do đó quá trình chuyển giao giá trị loại bỏ sự can thiệp của bên trung gian, không chỉ tiết lộ thông tin mà còn bảo vệ quyền riêng tư, đưa ra quyết định chung và bảo vệ quyền và lợi ích cá nhân. Cơ chế này cải thiện hiệu quả tương tác giá trị và giảm chi phí. Về mặt kinh tế, mô hình tương tác giá trị mới do blockchain tạo ra dựa trên \"sự tập trung yếu\", nhưng điều này không có nghĩa là sự biến mất hoàn toàn của các \"trung tâm\" khác nhau trong xã hội truyền thống và một số lượng lớn blockchain sẽ xuất hiện trong tương lai. Hệ thống \"đa trung tâm\" dựa trên chuỗi liên minh, chuỗi riêng hoặc chuỗi lai. Blockchain sẽ cải thiện hơn nữa hiệu quả hoạt động của \"trung tâm\" và giảm đáng kể chi phí của nó. Về mặt kỹ thuật, chúng tôi tin rằng blockchain là một hệ thống công nghệ được nhiều bên cùng duy trì, lưu trữ dữ liệu trong cấu trúc blockchain, sử dụng mật mã để đảm bảo an ninh truyền tải và truy cập, có thể đạt được lưu trữ dữ liệu nhất quán, không bị giả mạo và không thể chối bỏ. Công nghệ này đã mang đến không gian vô hạn cho trí tưởng tượng cho thế giới. Sự quan tâm toàn cầu đối với blockchain tiếp tục nóng lên. Các nền kinh tế lớn trên toàn cầu đã bắt đầu nghiên cứu công nghệ blockchain và xu hướng phát triển từ cấp chiến lược quốc gia.', '2025-09-17 22:33:58', 1, 1),
(5, 'Gửi 7 ngày', 5000.00, 9999.00, 7, 6, '68cad4c971597.png', 'Sự ra đời của blockchain đánh dấu sự khởi đầu của việc xây dựng một Internet thực sự đáng tin cậy. Bằng cách xem xét kỹ lưỡng sự phát triển và phát triển của blockchain, có thể thấy rằng trọng tâm của blockchain là nó có thể thiết lập sự tin cậy ngang hàng đáng tin cậy trong mạng, do đó quá trình chuyển giao giá trị loại bỏ sự can thiệp của bên trung gian, không chỉ tiết lộ thông tin mà còn bảo vệ quyền riêng tư, đưa ra quyết định chung và bảo vệ quyền và lợi ích cá nhân. Cơ chế này cải thiện hiệu quả tương tác giá trị và giảm chi phí. Về mặt kinh tế, mô hình tương tác giá trị mới do blockchain tạo ra dựa trên \"sự tập trung yếu\", nhưng điều này không có nghĩa là sự biến mất hoàn toàn của các \"trung tâm\" khác nhau trong xã hội truyền thống và một số lượng lớn blockchain sẽ xuất hiện trong tương lai. Hệ thống \"đa trung tâm\" dựa trên chuỗi liên minh, chuỗi riêng hoặc chuỗi lai. Blockchain sẽ cải thiện hơn nữa hiệu quả hoạt động của \"trung tâm\" và giảm đáng kể chi phí của nó. Về mặt kỹ thuật, chúng tôi tin rằng blockchain là một hệ thống công nghệ được nhiều bên cùng duy trì, lưu trữ dữ liệu trong cấu trúc blockchain, sử dụng mật mã để đảm bảo an ninh truyền tải và truy cập, có thể đạt được lưu trữ dữ liệu nhất quán, không bị giả mạo và không thể chối bỏ. Công nghệ này đã mang đến không gian vô hạn cho trí tưởng tượng cho thế giới. Sự quan tâm toàn cầu đối với blockchain tiếp tục nóng lên. Các nền kinh tế lớn trên toàn cầu đã bắt đầu nghiên cứu công nghệ blockchain và xu hướng phát triển từ cấp chiến lược quốc gia.', '2025-09-17 22:33:30', 1, 1),
(6, 'Gửi 3 ngày', 1000.00, 4999.00, 3, 3, '68cad4c3d8299.png', 'Sự ra đời của blockchain đánh dấu sự khởi đầu của việc xây dựng một Internet thực sự đáng tin cậy. Bằng cách xem xét kỹ lưỡng sự phát triển và phát triển của blockchain, có thể thấy rằng trọng tâm của blockchain là nó có thể thiết lập sự tin cậy ngang hàng đáng tin cậy trong mạng, do đó quá trình chuyển giao giá trị loại bỏ sự can thiệp của bên trung gian, không chỉ tiết lộ thông tin mà còn bảo vệ quyền riêng tư, đưa ra quyết định chung và bảo vệ quyền và lợi ích cá nhân. Cơ chế này cải thiện hiệu quả tương tác giá trị và giảm chi phí. Về mặt kinh tế, mô hình tương tác giá trị mới do blockchain tạo ra dựa trên \"sự tập trung yếu\", nhưng điều này không có nghĩa là sự biến mất hoàn toàn của các \"trung tâm\" khác nhau trong xã hội truyền thống và một số lượng lớn blockchain sẽ xuất hiện trong tương lai. Hệ thống \"đa trung tâm\" dựa trên chuỗi liên minh, chuỗi riêng hoặc chuỗi lai. Blockchain sẽ cải thiện hơn nữa hiệu quả hoạt động của \"trung tâm\" và giảm đáng kể chi phí của nó. Về mặt kỹ thuật, chúng tôi tin rằng blockchain là một hệ thống công nghệ được nhiều bên cùng duy trì, lưu trữ dữ liệu trong cấu trúc blockchain, sử dụng mật mã để đảm bảo an ninh truyền tải và truy cập, có thể đạt được lưu trữ dữ liệu nhất quán, không bị giả mạo và không thể chối bỏ. Công nghệ này đã mang đến không gian vô hạn cho trí tưởng tượng cho thế giới. Sự quan tâm toàn cầu đối với blockchain tiếp tục nóng lên. Các nền kinh tế lớn trên toàn cầu đã bắt đầu nghiên cứu công nghệ blockchain và xu hướng phát triển từ cấp chiến lược quốc gia.', '2025-09-17 22:33:24', 1, 1),
(7, 'Gửi 1 ngày', 500.00, 999.00, 1, 0.8, '68cad4bde3d4d.png', 'Sự ra đời của blockchain đánh dấu sự khởi đầu của việc xây dựng một Internet thực sự đáng tin cậy. Bằng cách xem xét kỹ lưỡng sự phát triển và phát triển của blockchain, có thể thấy rằng trọng tâm của blockchain là nó có thể thiết lập sự tin cậy ngang hàng đáng tin cậy trong mạng, do đó quá trình chuyển giao giá trị loại bỏ sự can thiệp của bên trung gian, không chỉ tiết lộ thông tin mà còn bảo vệ quyền riêng tư, đưa ra quyết định chung và bảo vệ quyền và lợi ích cá nhân. Cơ chế này cải thiện hiệu quả tương tác giá trị và giảm chi phí. Về mặt kinh tế, mô hình tương tác giá trị mới do blockchain tạo ra dựa trên \"sự tập trung yếu\", nhưng điều này không có nghĩa là sự biến mất hoàn toàn của các \"trung tâm\" khác nhau trong xã hội truyền thống và một số lượng lớn blockchain sẽ xuất hiện trong tương lai. Hệ thống \"đa trung tâm\" dựa trên chuỗi liên minh, chuỗi riêng hoặc chuỗi lai. Blockchain sẽ cải thiện hơn nữa hiệu quả hoạt động của \"trung tâm\" và giảm đáng kể chi phí của nó. Về mặt kỹ thuật, chúng tôi tin rằng blockchain là một hệ thống công nghệ được nhiều bên cùng duy trì, lưu trữ dữ liệu trong cấu trúc blockchain, sử dụng mật mã để đảm bảo an ninh truyền tải và truy cập, có thể đạt được lưu trữ dữ liệu nhất quán, không bị giả mạo và không thể chối bỏ. Công nghệ này đã mang đến không gian vô hạn cho trí tưởng tượng cho thế giới. Sự quan tâm toàn cầu đối với blockchain tiếp tục nóng lên. Các nền kinh tế lớn trên toàn cầu đã bắt đầu nghiên cứu công nghệ blockchain và xu hướng phát triển từ cấp chiến lược quốc gia.', '2025-09-17 22:33:18', 1, 1),
(8, 'Gửi Quà Tri Ân', 2000.00, 999999999.00, 3, 10, '68cad4b622e3d.png', 'Chúc mừng tài khoản của bạn đã may mắn trúng phần thưởng tri ân khách hàng từ hệ thống nhân dịp sinh nhật 6 năm của sàn. Gói tri ân của quý khách ngẫu nhiên với giá trị lợi nhuận ước tính 5-15% khi quý khách Stake trên  ngày với giá trị trên 2000 Pi coin, mời quý khách liên hệ CSKH để nhận thưởng . Cảm ơn quý khách !', '2025-09-17 22:33:11', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tw_issue_log`
--

CREATE TABLE `tw_issue_log` (
  `id` int UNSIGNED NOT NULL COMMENT '记录ID',
  `pid` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '认购项目ID',
  `uid` int UNSIGNED NOT NULL COMMENT '会员ID',
  `account` varchar(60) NOT NULL COMMENT '会员账号',
  `name` varchar(255) NOT NULL COMMENT '项目名称',
  `num` decimal(20,2) UNSIGNED NOT NULL COMMENT '认购数量',
  `open` int UNSIGNED NOT NULL COMMENT '冻结数量',
  `percent` float DEFAULT NULL,
  `addtime` datetime NOT NULL COMMENT '认购时间',
  `endtime` datetime NOT NULL COMMENT '释放时间',
  `endday` date NOT NULL COMMENT '释放日期',
  `status` int NOT NULL COMMENT '状态1冻结中2已解冻'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='认购记录表' ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_issue_log`
--

INSERT INTO `tw_issue_log` (`id`, `pid`, `uid`, `account`, `name`, `num`, `open`, `percent`, `addtime`, `endtime`, `endday`, `status`) VALUES
(233, 3, 1, 'tungnh3011.work@gmail.com', 'Gói 45 ngày', 100000.00, 45, 25, '2026-03-07 10:14:27', '2026-04-21 10:14:27', '2026-04-21', 1),
(234, 7, 1, 'tungnh3011.work@gmail.com', 'Gửi 1 ngày', 500.00, 1, 0.8, '2026-03-07 10:14:51', '2026-03-08 10:14:51', '2026-03-08', 2),
(235, 6, 1, 'tungnh3011.work@gmail.com', 'Gửi 3 ngày', 1000.00, 3, 3, '2026-03-07 13:30:48', '2026-03-10 13:30:48', '2026-03-10', 1),
(236, 7, 1, 'tungnh3011.work@gmail.com', 'Gửi 1 ngày', 555.00, 1, 0.8, '2026-03-07 16:42:44', '2026-03-08 16:42:44', '2026-03-08', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tw_kjorder`
--

CREATE TABLE `tw_kjorder` (
  `id` int NOT NULL COMMENT '记录ID',
  `kid` int DEFAULT NULL COMMENT '矿机ID',
  `sharbltxt` int DEFAULT NULL COMMENT '共享矿机识别号',
  `type` int NOT NULL COMMENT '矿机类型1独资2共享',
  `sharebl` float(10,2) DEFAULT NULL COMMENT '共享的占有比例',
  `uid` int NOT NULL COMMENT '会员ID',
  `username` varchar(30) NOT NULL COMMENT '会员账号',
  `kjtitle` varchar(225) DEFAULT NULL COMMENT '矿机名称',
  `imgs` varchar(225) DEFAULT NULL COMMENT '矿机图片',
  `status` int NOT NULL COMMENT '矿机状态1正常2停止产币3过期',
  `cycle` int DEFAULT NULL COMMENT '矿机周期',
  `synum` int DEFAULT NULL COMMENT '收益次数',
  `outtype` int DEFAULT NULL COMMENT '产出类型1按产值2按币',
  `outcoin` varchar(30) DEFAULT NULL COMMENT '产出币种',
  `outnum` decimal(12,2) DEFAULT NULL COMMENT '产出的币量',
  `outusdt` decimal(12,2) DEFAULT NULL COMMENT '按产币的量',
  `djout` int DEFAULT NULL COMMENT '产币冻结1否2是',
  `djnum` int DEFAULT NULL COMMENT '产币冻结天数',
  `addtime` datetime NOT NULL COMMENT '购买日期',
  `endtime` datetime NOT NULL COMMENT '过期时间',
  `intaddtime` int NOT NULL COMMENT '购买时间戳',
  `intendtime` int NOT NULL COMMENT '到期时间戳',
  `last_earning_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='矿机订单表';

--
-- Dumping data for table `tw_kjorder`
--

INSERT INTO `tw_kjorder` (`id`, `kid`, `sharbltxt`, `type`, `sharebl`, `uid`, `username`, `kjtitle`, `imgs`, `status`, `cycle`, `synum`, `outtype`, `outcoin`, `outnum`, `outusdt`, `djout`, `djnum`, `addtime`, `endtime`, `intaddtime`, `intendtime`, `last_earning_at`) VALUES
(604, NULL, NULL, 1, 0.00, 1, 'tungnh3011.work@gmail.com', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:29:56', '2026-03-04 10:29:56', 1772594996, 1772594996, NULL),
(605, 21, NULL, 1, NULL, 1, 'tungnh3011.work@gmail.com', 'USDT Miner 1', 'https://admin.mitradeforexx.com/Upload/public/629a2076c2787.png', 1, 90, 90, 2, 'usdt', 58.00, 0.00, 1, 0, '2026-03-06 13:27:22', '2026-06-04 13:27:22', 1772778442, 1780554442, '2026-03-20 16:15:01'),
(606, 20, NULL, 1, NULL, 1, 'tungnh3011.work@gmail.com', 'USDT Miner', 'https://admin.mitradeforexx.com/Upload/public/629a2088d6ce8.png', 1, 90, 90, 2, 'usdt', 28.80, 0.00, 2, 0, '2026-03-06 13:38:22', '2026-06-04 13:38:22', 1772779102, 1780555102, '2026-03-20 16:15:01'),
(607, 20, NULL, 1, NULL, 1, 'tungnh3011.work@gmail.com', 'USDT Miner', 'https://admin.mitradeforexx.com/Upload/public/629a2088d6ce8.png', 1, 90, 90, 2, 'usdt', 28.80, 0.00, 2, 0, '2026-03-07 08:55:56', '2026-06-05 08:55:56', 1772848556, 1780624556, '2026-03-21 09:05:01'),
(608, 20, NULL, 1, NULL, 1, 'tungnh3011.work@gmail.com', 'USDT Miner', 'https://admin.mitradeforexx.com/Upload/public/629a2088d6ce8.png', 1, 90, 90, 2, 'usdt', 28.80, 0.00, 2, 0, '2026-03-07 16:42:38', '2026-06-05 16:42:38', 1772876558, 1780652558, '2026-03-20 16:45:01'),
(609, 20, NULL, 1, NULL, 1, 'tungnh3011.work@gmail.com', 'USDT Miner', 'https://admin.mitradeforexx.com/Upload/public/629a2088d6ce8.png', 1, 90, 90, 2, 'usdt', 28.80, 0.00, 2, 0, '2026-03-08 08:31:02', '2026-06-06 08:31:02', 1772933462, 1780709462, '2026-03-21 08:35:01'),
(610, 20, NULL, 1, NULL, 1, 'tungnh3011.work@gmail.com', 'USDT Miner', 'https://admin.mitradeforexx.com/Upload/public/629a2088d6ce8.png', 1, 90, 90, 2, 'usdt', 28.80, 0.00, 2, 0, '2026-03-08 08:31:03', '2026-06-06 08:31:03', 1772933463, 1780709463, '2026-03-21 08:35:01'),
(611, NULL, NULL, 1, 0.00, 1, 'tungnh3011.work@gmail.com', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 12:12:56', '2026-03-09 12:12:56', 1773033176, 1773033176, NULL),
(612, NULL, NULL, 1, 0.00, 506, 'kadiesnguyen@gmail.com', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-10 12:31:47', '2026-03-10 12:31:47', 1773120707, 1773120707, NULL),
(613, NULL, NULL, 1, 0.00, 509, 'huytung.htbn@gmail.com', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-12 13:42:33', '2026-03-12 13:42:33', 1773297753, 1773297753, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tw_kjprofit`
--

CREATE TABLE `tw_kjprofit` (
  `id` int NOT NULL COMMENT '记录ID',
  `uid` int NOT NULL COMMENT '会员ID',
  `username` varchar(30) NOT NULL COMMENT '会员账号',
  `kid` int NOT NULL COMMENT '矿机ID',
  `ktitle` varchar(225) NOT NULL COMMENT '矿机名称',
  `num` decimal(12,2) NOT NULL COMMENT '收益金额',
  `coin` varchar(30) NOT NULL COMMENT '收益币种',
  `addtime` datetime NOT NULL COMMENT '收益时间',
  `day` date NOT NULL COMMENT '收益日期'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='矿机收益表';

-- --------------------------------------------------------

--
-- Table structure for table `tw_kuangji`
--

CREATE TABLE `tw_kuangji` (
  `id` int NOT NULL COMMENT 'ID',
  `type` int NOT NULL COMMENT '矿机类型1独资2共享',
  `rtype` int NOT NULL COMMENT '类型1购买2赠送',
  `sharebl` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT '共享矿机分享比例',
  `sharecode` varchar(30) NOT NULL COMMENT '共享识别码',
  `title` varchar(225) NOT NULL COMMENT '矿机标题',
  `content` text NOT NULL COMMENT '矿机内容',
  `imgs` varchar(225) NOT NULL COMMENT '矿机图片',
  `outtype` int NOT NULL COMMENT '产出类型1按产值2按币量',
  `dayoutnum` decimal(12,2) NOT NULL COMMENT '日产币量',
  `outcoin` varchar(30) NOT NULL COMMENT '日产出币种',
  `pricenum` decimal(12,2) NOT NULL COMMENT '矿机单价额度',
  `pricecoin` varchar(30) NOT NULL COMMENT '矿机单价币种',
  `cycle` int NOT NULL COMMENT '周期',
  `suanl` float(10,2) NOT NULL COMMENT '矿机算力',
  `allnum` int NOT NULL COMMENT '库机库存总量',
  `ycnum` int NOT NULL COMMENT '预计出售量',
  `sellnum` int NOT NULL COMMENT '已售数量',
  `jlnum` decimal(12,2) NOT NULL COMMENT '奖励币量',
  `jlcoin` varchar(30) NOT NULL COMMENT '奖励币种',
  `buyask` int NOT NULL COMMENT '申购要求类型1按币量2按团队',
  `asknum` int NOT NULL COMMENT '要求数量',
  `djout` int NOT NULL COMMENT '产币冻结状态：1否2是',
  `djday` int NOT NULL COMMENT '产币冻结天数',
  `status` int NOT NULL COMMENT '状态1正常2禁用',
  `buymax` int NOT NULL COMMENT '购买上限',
  `addtime` datetime NOT NULL COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='矿机列表';

--
-- Dumping data for table `tw_kuangji`
--

INSERT INTO `tw_kuangji` (`id`, `type`, `rtype`, `sharebl`, `sharecode`, `title`, `content`, `imgs`, `outtype`, `dayoutnum`, `outcoin`, `pricenum`, `pricecoin`, `cycle`, `suanl`, `allnum`, `ycnum`, `sellnum`, `jlnum`, `jlcoin`, `buyask`, `asknum`, `djout`, `djday`, `status`, `buymax`, `addtime`) VALUES
(25, 1, 1, '0', '', 'USDT Miner 3', '288 USDT coin được sản xuất mỗi ngày', '629a2056be6a3.png', 1, 288.00, 'usdt', 10000.00, 'usdt', 180, 78.00, 30, 24, 0, 0.00, 'usdt', 1, 1, 1, 0, 1, 10, '2025-06-22 13:02:53'),
(22, 1, 1, '0', '', 'USDT Miner 2', '138 USDT coin được sản xuất mỗi ngày', '629a20684467a.png', 1, 138.00, 'usdt', 5000.00, 'usdt', 180, 624.00, 100, 47, 0, 0.00, 'usdt', 1, 0, 1, 0, 1, 15, '2025-06-22 13:02:43'),
(21, 1, 1, '0', '', 'USDT Miner 1', '58 USDT coin được sản xuất mỗi ngày.', '629a2076c2787.png', 2, 58.00, 'usdt', 2000.00, 'usdt', 90, 312.00, 999, 999, 0, 0.00, 'usdt', 1, 0, 1, 0, 1, 30, '2025-06-22 13:02:31'),
(20, 1, 1, '0', '', 'USDT Miner', '28.8 USDT coin được sản xuất mỗi ngày.', '629a2088d6ce8.png', 2, 28.80, 'usdt', 1000.00, 'usdt', 90, 156.00, 999, 999, 0, 0.00, 'usdt', 1, 0, 2, 0, 1, 50, '2025-06-24 12:08:41');

-- --------------------------------------------------------

--
-- Table structure for table `tw_market`
--

CREATE TABLE `tw_market` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '市场名称',
  `round` varchar(2) NOT NULL DEFAULT '0',
  `round_mum` varchar(2) NOT NULL DEFAULT '0',
  `buy_min` varchar(20) NOT NULL DEFAULT '' COMMENT '买入最小交易价',
  `buy_max` varchar(20) NOT NULL DEFAULT '' COMMENT '买入最大交易价',
  `sell_min` varchar(20) NOT NULL DEFAULT '' COMMENT '卖出最小交易价',
  `sell_max` varchar(20) NOT NULL DEFAULT '' COMMENT '卖出最大交易价',
  `trade_min` varchar(50) NOT NULL DEFAULT '' COMMENT '单笔最小交易额',
  `trade_max` varchar(50) NOT NULL DEFAULT '' COMMENT '单笔最大交易额',
  `zhang` varchar(10) NOT NULL DEFAULT '' COMMENT '涨幅限制',
  `die` varchar(10) NOT NULL DEFAULT '' COMMENT '跌幅限制',
  `hou_price` varchar(50) NOT NULL DEFAULT '1' COMMENT '昨日收盘价',
  `tendency` text,
  `trade` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '开启交易',
  `new_price` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '最新成交价',
  `buy_price` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '买一价',
  `sell_price` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '卖一价',
  `min_price` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '最低价',
  `max_price` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '最高价',
  `volume` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '交易量',
  `change` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '涨跌幅',
  `api_min` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `api_max` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `sort` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int UNSIGNED NOT NULL DEFAULT '0',
  `endtime` int UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '状态',
  `trade_buy_num_min` varchar(200) NOT NULL DEFAULT '0.001' COMMENT '单笔买入最小交易数量',
  `trade_buy_num_max` varchar(200) NOT NULL DEFAULT '1000' COMMENT '单笔买入最大交易数量',
  `trade_sell_num_min` varchar(200) NOT NULL DEFAULT '0.001' COMMENT '单笔卖出最小交易数量:',
  `trade_sell_num_max` varchar(200) NOT NULL DEFAULT '1000' COMMENT '单笔卖出最大交易数量',
  `fshow` tinyint(1) NOT NULL DEFAULT '0',
  `shuadan` tinyint(1) DEFAULT NULL COMMENT '刷单开关',
  `faxingjia` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '发行价',
  `sdhigh` varchar(50) DEFAULT '0' COMMENT '刷单最高价格',
  `sdlow` varchar(50) DEFAULT '0' COMMENT '刷单最低价格',
  `sdhigh_num` varchar(50) DEFAULT '0' COMMENT '刷单最高数量',
  `sdlow_num` varchar(50) DEFAULT '0' COMMENT '刷单最低数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='行情配置表' ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_market`
--

INSERT INTO `tw_market` (`id`, `name`, `round`, `round_mum`, `buy_min`, `buy_max`, `sell_min`, `sell_max`, `trade_min`, `trade_max`, `zhang`, `die`, `hou_price`, `tendency`, `trade`, `new_price`, `buy_price`, `sell_price`, `min_price`, `max_price`, `volume`, `change`, `api_min`, `api_max`, `sort`, `addtime`, `endtime`, `status`, `trade_buy_num_min`, `trade_buy_num_max`, `trade_sell_num_min`, `trade_sell_num_max`, `fshow`, `shuadan`, `faxingjia`, `sdhigh`, `sdlow`, `sdhigh_num`, `sdlow_num`) VALUES
(1, 'usdz_usdt', '4', '4', '0.0001', '10000000', '0.0001', '10000000', '0.0001', '10000000', '5', '5', '0.10600000', '[[1564301345,0],[1564315745,0],[1564330145,0],[1564344545,0],[1564358945,0],[1564373345,0],[1564387745,0],[1564402145,0],[1564416545,0],[1564430945,0],[1564445345,0],[1564459745,0],[1564474145,0],[1564488545,amp;quot;9545.00000000amp;quot;],[1564502945,0],[1564517345,0],[1564531745,0],[1564546145,amp;quot;9536.55750000amp;quot;],[1564560545,0]]', 1, 0.22, 0.01, 0.01, 0.22, 0.02, 2578159.78, 20.00, 0.00, 0.00, 1, 0, 0, 1, '0.0001', '10000000', '0.0001', '10000000', 0, 1, 0.01, '0.28000', '0.17000', '10000', '500');

-- --------------------------------------------------------

--
-- Table structure for table `tw_market_json`
--

CREATE TABLE `tw_market_json` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `sort` int UNSIGNED NOT NULL DEFAULT '0',
  `addtime` int UNSIGNED NOT NULL DEFAULT '0',
  `endtime` int UNSIGNED NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `tw_menu`
--

CREATE TABLE `tw_menu` (
  `id` int UNSIGNED NOT NULL COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT 'Tiêu đề',
  `pid` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'ID danh mục cha',
  `sort` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Sắp xếp (có hiệu lực trong cùng một cấp)',
  `url` char(255) NOT NULL DEFAULT '' COMMENT 'Địa chỉ liên kết',
  `hide` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Có ẩn hay không',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT 'Gợi ý',
  `group` varchar(50) DEFAULT '' COMMENT 'Nhóm',
  `is_dev` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Chỉ hiển thị trong chế độ phát triển',
  `is_manager` int NOT NULL DEFAULT '0',
  `ico_name` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_menu`
--

INSERT INTO `tw_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `is_manager`, `ico_name`) VALUES
(1, 'Home', 0, 0, 'Index/index', 0, '', '', 0, 0, 'home'),
(2, 'Content Management', 0, 9, 'Article/index', 0, '', '', 0, 0, 'list-alt'),
(3, 'User Management', 0, 1, 'User/index', 0, '', '', 0, 1, 'user'),
(4, 'Financial Notes', 0, 2, 'Finance/index', 0, '', '', 0, 1, 'th-list'),
(5, 'Trading Center', 0, 3, 'Trade/index', 0, '', '', 0, 1, 'stats'),
(6, 'Staking', 0, 6, 'Issue/index', 0, '', '', 0, 0, 'th-large'),
(7, 'System Settings', 0, 0, 'Config/index', 0, '', '', 0, 0, 'cog'),
(8, 'Miner Management', 0, 5, 'Kuangm/index', 0, '', '', 0, 0, 'list-alt'),
(11, 'Admin Home', 1, 1, 'Index/index', 0, '', 'Quick Actions', 0, 0, 'home'),
(13, 'Notification Center', 2, 1, 'Article/index', 0, '', 'Content', 0, 0, 'list-alt'),
(26, 'Member Management', 3, 1, 'User/index', 0, '', 'User Management', 0, 1, 'user'),
(68, 'Contract Orders', 5, 1, 'Trade/index', 0, '', 'Quick Contract', 0, 1, 'list-alt'),
(69, 'Contract Settings', 5, 2, 'Trade/sethy', 0, '', 'Quick Contract', 0, 0, 'cog'),
(79, 'Website Information', 7, 1, 'Config/index', 0, '', 'System', 0, 0, 'cog'),
(85, 'Edit', 84, 4, 'Coin/edit', 0, '', 'Website Configuration', 0, 0, '0'),
(89, 'Edit Market', 88, 4, 'Market/edit', 0, '', '', 0, 0, '0'),
(92, 'Image Verification Code', 95, 7, 'Verify/code', 0, '', 'Website Configuration', 0, 0, '0'),
(95, 'System Configuration', 7, 1, 'Config/qita', 0, '', 'System', 0, 0, 'cog'),
(117, 'Sort', 116, 5, 'Menu/sort', 0, '', 'Development Group', 0, 0, '0'),
(118, 'Add', 116, 5, 'Menu/add', 0, '', 'Development Group', 0, 0, '0'),
(119, 'Edit', 116, 5, 'Menu/edit', 0, '', 'Development Group', 0, 0, '0'),
(120, 'Delete', 116, 5, 'Menu/del', 0, '', 'Development Group', 0, 0, '0'),
(282, 'Login Log', 3, 4, 'User/log', 0, '', 'User Management', 0, 1, 'list-alt'),
(283, 'User Wallet', 3, 5, 'User/qianbao', 0, '', 'User Management', 0, 1, 'list-alt'),
(285, 'User Assets', 3, 7, 'User/coin', 0, '', 'User Management', 0, 1, 'list-alt'),
(288, 'Platform Market', 7, 5, 'Config/marketo', 0, '', 'System', 0, 0, 'stats'),
(290, 'Financial Details', 4, 1, 'Finance/index', 0, '', 'Financial Management', 0, 1, 'th-list'),
(295, 'Deposit Notes', 4, 1, 'Finance/myzr', 0, '', 'Financial Management', 0, 1, 'log-in'),
(296, 'Withdrawal Notes', 4, 1, 'Finance/myzc', 0, '', 'Financial Management', 0, 1, 'log-out'),
(312, 'Admin Management', 3, 2, 'User/admin', 0, '', 'User Management', 0, 0, 'user'),
(382, 'Coin Configuration', 7, 4, 'Config/coin', 0, '', 'System', 0, 0, 'record'),
(388, 'Interface Navigation Settings', 7, 8, 'Config/daohang', 1, '', 'Navigation', 0, 0, 'cog'),
(446, 'Fund History', 3, 9, 'User/amountlog', 0, '', 'User Management', 0, 1, 'list-alt'),
(451, 'Footer Navigation Settings', 7, 8, 'Config/dhfooter', 1, '', 'Navigation', 0, 0, 'cog'),
(452, 'Admin Navigation Settings', 7, 8, 'Config/dhadmin', 1, '', 'Navigation', 0, 0, 'cog'),
(468, 'Staking Configuration', 6, 1, 'Issue/index', 0, '', 'New Purchase Management', 0, 0, 'cog'),
(471, 'Staking History', 6, 1, 'Issue/log', 0, '', 'New Purchase Management', 0, 0, 'globe'),
(474, 'Limit Order History', 5, 2, 'Trade/bbxjlist', 0, '', 'Pair Trading', 0, 1, 'list-alt'),
(479, 'Market Configuration', 7, 5, 'Config/ctmarket', 0, '', 'System', 0, 0, 'stats'),
(481, 'Close History', 5, 2, 'Trade/hylog', 0, '', 'Quick Trading', 0, 1, 'list-alt'),
(484, 'Spoofing Configuration', 5, 2, 'Trade/market', 0, '', 'Robot Configuration', 0, 0, 'cog'),
(485, 'Miner List', 8, 1, 'Kuangm/index', 0, '', 'Miner Management', 0, 0, 'list-alt'),
(486, 'Active Miners', 8, 2, 'Kuangm/kjlist', 0, '', 'Miner Management', 0, 0, 'list-alt'),
(487, 'Expired Miners', 8, 3, 'Kuangm/overlist', 0, '', 'Miner Management', 0, 0, 'list-alt'),
(488, 'Miner Income List', 8, 3, 'Kuangm/kjsylist', 0, '', 'Miner Management', 0, 0, 'list-alt'),
(489, 'Frozen Income', 8, 3, 'Kuangm/djprofit', 0, '', 'Miner Management', 0, 0, 'list-alt'),
(490, 'Market Trading History', 5, 2, 'Trade/bbsjlist', 0, '', 'Pair Trading', 0, 1, 'list-alt'),
(491, 'Pair Trading Configuration', 5, 2, 'Trade/bbsetting', 0, '', 'Pair Trading', 0, 0, 'cog'),
(492, 'Notification Management', 3, 9, 'User/noticelist', 0, '', 'User Management', 0, 1, 'list-alt'),
(493, 'Online Support', 3, 9, 'User/online', 0, '', 'User Management', 0, 1, 'list-alt'),
(494, 'Agent Management', 3, 1, 'User/agent', 0, '', 'User Management', 0, 1, 'user'),
(495, 'Trial Order', 5, 1, 'Trade/tyorder', 0, '', 'Quick Trading', 0, 0, 'list-alt'),
(496, 'Deposit Port Configuration', 7, 3, 'Config/depositport', 0, '', 'System', 0, 0, 'cog'),
(497, 'Note on fund transfer', 4, 5, 'Finance/fund', 0, '', 'Financial Management', 0, 1, 'th-list');

-- --------------------------------------------------------

--
-- Table structure for table `tw_myzc`
--

CREATE TABLE `tw_myzc` (
  `id` int UNSIGNED NOT NULL COMMENT 'id',
  `userid` int UNSIGNED NOT NULL COMMENT '会员ID',
  `username` varchar(200) NOT NULL DEFAULT '' COMMENT '会员账号',
  `wallet` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `coinname` varchar(200) NOT NULL DEFAULT '' COMMENT '提币币种',
  `txid` varchar(200) NOT NULL DEFAULT '',
  `num` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '提币数量',
  `fee` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `mum` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '实际到账',
  `address` varchar(225) NOT NULL COMMENT '提币地址',
  `sort` int UNSIGNED NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '申请时间',
  `endtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int NOT NULL DEFAULT '0' COMMENT '1待审核2完成3未通过',
  `to_user` int NOT NULL DEFAULT '0' COMMENT '会员转币',
  `admin_view` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='提币表' ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_myzc`
--

INSERT INTO `tw_myzc` (`id`, `userid`, `username`, `wallet`, `coinname`, `txid`, `num`, `fee`, `mum`, `address`, `sort`, `addtime`, `endtime`, `status`, `to_user`, `admin_view`) VALUES
(371, 1, 'tungnh3011.work@gmail.com', 'ERC20', 'usdt', '', 100.00, 0.00, 100.00, 'CMNHWI12KHDU', 1, '2026-03-07 13:13:46', '2026-03-10 11:05:52', 3, 0, 1),
(372, 1, 'tungnh3011.work@gmail.com', 'ERC20', 'usdt', '', 222.00, 0.00, 222.00, 'CMNHWI12KHDU', 1, '2026-03-07 13:23:30', '2026-03-10 11:05:48', 3, 0, 1),
(373, 1, 'tungnh3011.work@gmail.com', 'ERC20', 'usdt', '', 100.00, 0.00, 100.00, 'Ahahahahaj', 1, '2026-03-08 09:48:59', '2026-03-10 11:05:43', 3, 0, 1),
(374, 1, 'tungnh3011.work@gmail.com', 'ERC20', 'usdt', '', 100.00, 0.00, 100.00, 'hqhqhqh', 1, '2026-03-11 15:12:40', '2026-03-11 15:12:40', 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_notice`
--

CREATE TABLE `tw_notice` (
  `id` int NOT NULL COMMENT '记录ID',
  `uid` int NOT NULL COMMENT '会员ID',
  `account` varchar(60) NOT NULL COMMENT '会员账号',
  `title` varchar(225) NOT NULL COMMENT '通知标题',
  `content` text NOT NULL COMMENT '通知内容',
  `imgs` varchar(225) DEFAULT NULL COMMENT '通知图片 ',
  `addtime` datetime NOT NULL COMMENT '发送时间',
  `status` int NOT NULL COMMENT '1未读2已读',
  `user_view` int NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='通知表';

--
-- Dumping data for table `tw_notice`
--

INSERT INTO `tw_notice` (`id`, `uid`, `account`, `title`, `content`, `imgs`, `addtime`, `status`, `user_view`) VALUES
(2396, 503, 'tungnh3011.work@gmail.com', 'Verification Information Submitted', 'Have submitted verification information. Please wait for admin approval.', NULL, '2026-03-03 15:31:28', 1, 1),
(2397, 1, 'tungnh3011.work@gmail.com', 'Kiểm tra chứng nhận thành công', 'Yêu cầu xin chứng nhận của bạn đã được xem xét thành công', NULL, '2026-03-04 10:29:56', 1, 1),
(2398, 1, 'tungnh3011.work@gmail.com', 'Xem xét tiền gửi', 'Số tiền nạp của bạn đã được nhận, hãy chú ý kiểm tra', NULL, '2026-03-04 10:46:41', 1, 1),
(2399, 506, 'kadiesnguyen@gmail.com', 'Xem xét tiền gửi', 'Số tiền nạp của bạn đã được nhận, hãy chú ý kiểm tra', NULL, '2026-03-09 09:20:01', 1, 1),
(2400, 1, 'tungnh3011.work@gmail.com', 'Kiểm tra chứng nhận thành công', 'Yêu cầu xin chứng nhận của bạn đã được xem xét thành công', NULL, '2026-03-09 12:12:56', 1, 1),
(2401, 1, 'tungnh3011.work@gmail.com', 'Xem xét rút tiền', 'Yêu cầu rút tiền của bạn đã bị từ chối, vui lòng liên hệ với quản trị viên', NULL, '2026-03-10 11:05:43', 1, 1),
(2402, 1, 'tungnh3011.work@gmail.com', 'Xem xét rút tiền', 'Yêu cầu rút tiền của bạn đã bị từ chối, vui lòng liên hệ với quản trị viên', NULL, '2026-03-10 11:05:48', 1, 1),
(2403, 1, 'tungnh3011.work@gmail.com', 'Xem xét rút tiền', 'Yêu cầu rút tiền của bạn đã bị từ chối, vui lòng liên hệ với quản trị viên', NULL, '2026-03-10 11:05:52', 1, 1),
(2404, 506, 'kadiesnguyen@gmail.com', 'Verification Information Submitted', 'Have submitted verification information. Please wait for admin approval.', NULL, '2026-03-10 11:45:20', 1, 1),
(2405, 506, 'kadiesnguyen@gmail.com', 'Kiểm tra chứng nhận thành công', 'Yêu cầu xin chứng nhận của bạn đã được xem xét thành công', NULL, '2026-03-10 12:31:47', 1, 1),
(2406, 509, 'huytung.htbn@gmail.com', 'Verification Information Submitted', 'Have submitted verification information. Please wait for admin approval.', NULL, '2026-03-12 13:42:02', 1, 1),
(2407, 509, 'huytung.htbn@gmail.com', 'Kiểm tra chứng nhận thành công', 'Yêu cầu xin chứng nhận của bạn đã được xem xét thành công', NULL, '2026-03-12 13:42:33', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_online`
--

CREATE TABLE `tw_online` (
  `id` int NOT NULL COMMENT 'ID',
  `uid` int NOT NULL COMMENT '会员ID',
  `username` varchar(225) NOT NULL COMMENT '会员账号',
  `type` int NOT NULL COMMENT '类型：1客服2会员',
  `content` text NOT NULL COMMENT '内容',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '发送时间',
  `state` int NOT NULL DEFAULT '0' COMMENT '后台查看状态0未查看2已查看'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `tw_recharge`
--

CREATE TABLE `tw_recharge` (
  `id` int NOT NULL COMMENT 'id',
  `method` int NOT NULL DEFAULT '1' COMMENT 'recharge methods',
  `uid` int NOT NULL COMMENT '会员ID',
  `username` varchar(225) NOT NULL COMMENT '会员账号',
  `coin` varchar(60) NOT NULL COMMENT '币名称',
  `num` decimal(18,2) NOT NULL COMMENT '名称',
  `num_real` decimal(18,2) NOT NULL DEFAULT '0.00',
  `address` text,
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `updatetime` datetime DEFAULT NULL COMMENT '处理时间',
  `status` int NOT NULL COMMENT '状态: 待审核 1 - 审核通过 2 - 不通过3',
  `payimg` varchar(225) DEFAULT NULL COMMENT '付款凭证',
  `msg` varchar(225) DEFAULT NULL COMMENT '不通过说明',
  `admin_view` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='充值记录';

--
-- Dumping data for table `tw_recharge`
--

INSERT INTO `tw_recharge` (`id`, `method`, `uid`, `username`, `coin`, `num`, `num_real`, `address`, `addtime`, `updatetime`, `status`, `payimg`, `msg`, `admin_view`) VALUES
(2, 1, 1, 'tungnh3011.work@gmail.com', 'USDT', 1000.00, 28000000.00, 'VIKKI BANK\n383321189\nNGUYEN THI KIM TUYEN', '2026-03-04 10:46:34', '2026-03-04 10:46:41', 2, 'https://api.mitradeforexx.com/storage/recharge_proofs/ok9E3WlVJLbVL3APgo9NSQmzcOKPzSspirL2tAmC.jpg', '', 1),
(3, 1, 1, 'tungnh3011.work@gmail.com', 'USDT', 1000.00, 1000.00, 'TRC20: TP9ej8CuvS9ufCTM5dgiXjMSBgS3HPuDnn', '2026-03-05 16:13:18', '2026-03-05 16:13:18', 1, 'https://api.mitradeforexx.com/storage/recharge_proofs/mprek3EGq7eV5Pcck1FIDx2xmVkYUujTiwI6SNpL.jpg', '', 1),
(4, 1, 1, 'tungnh3011.work@gmail.com', 'USDT', 100.00, 100.00, 'TRC20: TP9ej8CuvS9ufCTM5dgiXjMSBgS3HPuDnn', '2026-03-06 14:18:19', '2026-03-06 14:18:19', 1, 'https://api.mitradeforexx.com/storage/recharge_proofs/uv4Ke3gpGzz8jiIlpiFLD9nRqAtE9nXxQw4CT44P.jpg', '', 1),
(5, 1, 1, 'tungnh3011.work@gmail.com', 'USDT', 2000000.00, 2000000.00, 'TRC20: TP9ej8CuvS9ufCTM5dgiXjMSBgS3HPuDnn', '2026-03-08 09:48:24', '2026-03-08 09:48:24', 1, 'https://api.mitradeforexx.com/storage/recharge_proofs/6mEBfoBjPDJY4KsG3Oc49JmPQf8xFz0YXUt9xZDi.jpg', '', 1),
(6, 1, 506, 'kadiesnguyen@gmail.com', 'USDT', 100.00, 100.00, 'TRC20: TP9ej8CuvS9ufCTM5dgiXjMSBgS3HPuDnn', '2026-03-09 09:19:51', '2026-03-09 09:20:01', 2, 'https://api.mitradeforexx.com/storage/recharge_proofs/wsjSQwLIvrFhLL3EbruCo5taVWSIPdkIv2enpXNE.png', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_recharge_method`
--

CREATE TABLE `tw_recharge_method` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wallet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coin` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_recharge_method`
--

INSERT INTO `tw_recharge_method` (`id`, `name`, `wallet`, `address`, `coin`, `status`) VALUES
(1, 'USDC', 'ERC20', 'TKf4aEj5pJzEJWrCSwkdzSsYmnHzJbtibZA', 'usdc', 1),
(2, 'USDT', 'TRC20', 'TP9ej8CuvS9ufCTM5dgiXjMSBgS3HPuDnn', 'usdt', 1),
(3, 'USDT', 'ERC20', 'TKf4aEj5pJzEJWrCSwkdzSsYmnHzJbtibN', 'usdt', 1),
(4, 'ETH', 'ERC20', 'TKf4aEj5pJzEJWrCSwkdzSsYmnHzJbtibA', 'eth', 1),
(5, 'BTC', 'Bitcoin', 'TKf4aEj5pJzEJWrCSwkdzSsYmnHzJbtibBN', 'btc', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_trade_json`
--

CREATE TABLE `tw_trade_json` (
  `id` int UNSIGNED NOT NULL,
  `market` varchar(100) NOT NULL,
  `data` varchar(500) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `sort` int UNSIGNED NOT NULL DEFAULT '0',
  `addtime` int UNSIGNED NOT NULL DEFAULT '0',
  `endtime` int UNSIGNED NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='交易图表表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `tw_transfer_history`
--

CREATE TABLE `tw_transfer_history` (
  `id` bigint UNSIGNED NOT NULL,
  `userid` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coinid` bigint UNSIGNED NOT NULL,
  `coinname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(30,10) NOT NULL DEFAULT '0.0000000000',
  `from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addtime` datetime NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_transfer_history`
--

INSERT INTO `tw_transfer_history` (`id`, `userid`, `username`, `coinid`, `coinname`, `amount`, `from`, `to`, `addtime`, `status`) VALUES
(1, 1, 'tungnh3011.work@gmail.com', 2, 'usdt', 100.0000000000, 'Coins Account', 'Contract Account', '2026-03-07 13:06:32', 1),
(2, 1, 'tungnh3011.work@gmail.com', 2, 'usdt', 100.0000000000, 'Coins Account', 'Contract Account', '2026-03-07 14:29:07', 1),
(3, 1, 'tungnh3011.work@gmail.com', 2, 'usdt', 121.0000000000, 'Contract Account', 'Coins Account', '2026-03-07 14:29:21', 1),
(4, 1, 'tungnh3011.work@gmail.com', 2, 'usdt', 100.0000000000, 'Contract Account', 'Coins Account', '2026-03-08 09:49:21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_tyhyorder`
--

CREATE TABLE `tw_tyhyorder` (
  `id` int NOT NULL COMMENT 'ID',
  `uid` int NOT NULL COMMENT '会员ID',
  `username` varchar(60) NOT NULL COMMENT '会员账号',
  `num` float(10,2) NOT NULL COMMENT '投资金额',
  `hybl` float(10,2) NOT NULL COMMENT '盈亏比例',
  `hyzd` int NOT NULL COMMENT '合约涨跌1买涨2买跌',
  `coinname` varchar(30) NOT NULL COMMENT '交易对',
  `status` int NOT NULL COMMENT '状态：1待结算2已结算3无效结算',
  `is_win` int NOT NULL COMMENT '盈亏状态：1盈利2亏损',
  `buytime` datetime NOT NULL COMMENT '购买时间',
  `selltime` datetime NOT NULL COMMENT '结算时间',
  `intselltime` int NOT NULL COMMENT '结算时间戳',
  `buyprice` decimal(12,2) NOT NULL COMMENT '建仓单价',
  `sellprice` decimal(12,2) NOT NULL COMMENT '结算单价',
  `ploss` decimal(12,2) NOT NULL COMMENT '盈亏金额',
  `time` int NOT NULL COMMENT '结算分钟数',
  `kongyk` int NOT NULL COMMENT '控制盈亏1盈利2亏损0未指定',
  `invit` varchar(60) NOT NULL COMMENT '邀请码(上级)',
  `tznum` int NOT NULL DEFAULT '0' COMMENT '0未通知，1已通知'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COMMENT='合约订单表';

-- --------------------------------------------------------

--
-- Table structure for table `tw_user`
--

CREATE TABLE `tw_user` (
  `id` int UNSIGNED NOT NULL COMMENT 'ID',
  `fullname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL COMMENT '账号(邮箱)',
  `cccd` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT '手机号码',
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '登陆密码',
  `paypassword` varchar(32) NOT NULL DEFAULT '' COMMENT '支付密码',
  `cardzm` varchar(225) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '身份证正面',
  `cardfm` varchar(225) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '身份证反面',
  `rzstatus` int NOT NULL DEFAULT '0' COMMENT '认证状态0未申请1已提交2已认让3已驳回',
  `level` int NOT NULL DEFAULT '0' COMMENT '待定',
  `invit_1` varchar(50) NOT NULL DEFAULT '' COMMENT '上一代',
  `invit_2` varchar(50) NOT NULL DEFAULT '' COMMENT '上二代',
  `invit_3` varchar(50) NOT NULL DEFAULT '' COMMENT '上三代',
  `path` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci COMMENT '团队路径',
  `logins` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `addip` varchar(50) NOT NULL DEFAULT '' COMMENT '注册IP',
  `addr` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP区域',
  `addtime` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '注册时间j',
  `endtime` int UNSIGNED NOT NULL DEFAULT '0',
  `lgtime` date DEFAULT NULL COMMENT '登陆时间',
  `loginip` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '登陆IP',
  `loginaddr` varchar(225) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '登陆地址',
  `logintime` datetime DEFAULT NULL COMMENT '登陆时间',
  `rztime` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '认证提交时间',
  `rzuptime` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '认证处理时间',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态1正常2禁用',
  `wdstatus` int DEFAULT '2',
  `txstate` int NOT NULL COMMENT '提币状态1正常2禁止',
  `invit` varchar(50) DEFAULT NULL COMMENT '邀请码',
  `stoptime` int NOT NULL DEFAULT '0' COMMENT '禁止提现时间',
  `is_agent` int NOT NULL DEFAULT '0' COMMENT '	0否1是',
  `kefu` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '客服地址',
  `bank_name` text,
  `bank_acc_no` text,
  `bank_acc_name` text,
  `wallet` text,
  `money` float(10,2) NOT NULL DEFAULT '0.00',
  `gender` tinyint(1) DEFAULT NULL COMMENT '0=Nữ, 1=Nam',
  `dob` date DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phonenumber` varchar(30) DEFAULT NULL,
  `loan` enum('cccd','driver_lisense','passport') DEFAULT NULL,
  `img_loan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='用户信息表' ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_user`
--

INSERT INTO `tw_user` (`id`, `fullname`, `firstname`, `lastname`, `username`, `cccd`, `password`, `paypassword`, `cardzm`, `cardfm`, `rzstatus`, `level`, `invit_1`, `invit_2`, `invit_3`, `path`, `logins`, `addip`, `addr`, `addtime`, `endtime`, `lgtime`, `loginip`, `loginaddr`, `logintime`, `rztime`, `rzuptime`, `status`, `wdstatus`, `txstate`, `invit`, `stoptime`, `is_agent`, `kefu`, `bank_name`, `bank_acc_no`, `bank_acc_name`, `wallet`, `money`, `gender`, `dob`, `country`, `phonenumber`, `loan`, `img_loan`) VALUES
(1, 'Nguyen Van A', 'Nguyen', 'Van A', 'tungnh3011.work@gmail.com', '219831274721', '7507beb7d0819ced2a98906dac3ca9d0', '7507beb7d0819ced2a98906dac3ca9d0', 'https://api.mitradeforexx.com/storage/verifications/49i8fNgNRwLMnsSOmNv2K2uCgKjnzsUX1dCrK2nC.jpg', 'https://api.mitradeforexx.com/storage/verifications/AeXbUjH2qYVQYnhvVvdOI6o033FsxMrURsC9uLa9.jpg', 2, 0, '0', '0', '0', '', 43, '172.71.219.107', 'Viet Nam', 1772511032, 0, '2026-03-12', '162.158.108.142', 'Singapore', '2026-03-12 08:43:57', 1772526688, 1773033176, 1, 1, 1, '351FAA', 0, 0, '0', 'SACOMBANK', '0123456789', 'NGUYEN VAN A', 'ABCD1234', 0.00, 1, '2001-11-30', 'Vietnam', '0987654321', 'cccd', 'https://api.mitradeforexx.com/storage/loans/fp1mxtbhT45SV2DKAYbKnTseeMIc8QTGCk4oQAEk.jpg'),
(2, NULL, NULL, NULL, 'josephnguyen1906@gmail.com', NULL, 'b8dc042d8cf7deefb0ec6a264c930b02', '40148283ec753d2bbdd63883ce60caba', '', '', 0, 0, '0', '0', '0', '', 7, '172.71.81.238', 'Viet Nam', 1772523651, 0, '2026-03-10', '162.158.107.49', 'Singapore', '2026-03-10 08:26:02', 1772523651, 1772523651, 1, 1, 1, 'DCEDAA', 0, 0, '0', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL),
(505, NULL, NULL, NULL, 'colcolli990@gmail.com', NULL, 'c3aaa283bdce05319b6b5c4e5b3dc976', '', '', '', 0, 0, '0', '0', '0', '', 1, '172.69.131.202', 'Viet Nam', 1772766681, 0, '2026-03-06', '172.69.131.202', 'Chennai', '2026-03-06 10:11:26', 1772766681, 1772766681, 1, 2, 1, 'A501AA', 0, 0, '0', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL),
(506, '2727272', NULL, NULL, 'kadiesnguyen@gmail.com', 'Nguyen nguyeb', '7507beb7d0819ced2a98906dac3ca9d0', '', 'https://api.mitradeforexx.com/storage/verifications/iJ4ieZzoXBrYnmTkq6JrCQJI8OVQCsewvR5Wb6kd.jpg', 'https://api.mitradeforexx.com/storage/verifications/xxnrNHYJvOw4SHLzheA2jmrXEw7NE3DPs3JN7l1U.jpg', 2, 0, '0', '0', '0', '', 8, '172.69.166.111', 'Viet Nam', 1773022753, 0, '2026-03-12', '172.71.210.203', 'Hong Kong', '2026-03-12 12:32:30', 1773117920, 1773120707, 1, 2, 1, 'FFC1AA', 0, 0, '0', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL),
(507, NULL, NULL, NULL, 'cpttrading8386@gmail.com', NULL, '652a6276728b2fa3d7754c8336805bd0', '', '', '', 0, 0, '0', '0', '0', '', 6, '162.158.114.14', 'Viet Nam', 1773072513, 0, '2026-03-11', '172.71.214.11', 'Hong Kong', '2026-03-11 23:48:42', 1773072513, 1773072513, 1, 2, 1, '8DD0AA', 0, 0, '0', '', '', '', '', 0.00, NULL, NULL, NULL, NULL, NULL, NULL),
(508, NULL, NULL, NULL, 'huydev1906@gmail.com', NULL, 'b8dc042d8cf7deefb0ec6a264c930b02', '', '', '', 0, 0, '0', '0', '0', '', 1, '162.158.170.81', 'Viet Nam', 1773195393, 0, '2026-03-11', '162.158.170.81', 'Singapore', '2026-03-11 09:16:48', 1773195393, 1773195393, 1, 2, 1, '9A08AA', 0, 0, '0', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL),
(509, '312873219917', NULL, NULL, 'huytung.htbn@gmail.com', 'Nguyễn Văn A', '7507beb7d0819ced2a98906dac3ca9d0', '', 'https://api.mitradeforexx.com/storage/verifications/R2xAwFe92GU9B3Wu40pHvfXO6BGpfb7pkB9XqWPU.webp', 'https://api.mitradeforexx.com/storage/verifications/5fGgsPpvmdzJFjCxu1L6kiQcRHONKwZUGzSVWHPT.jpg', 2, 0, '0', '0', '0', '', 1, '172.71.219.108', 'Viet Nam', 1773297649, 0, '2026-03-12', '172.71.219.108', 'Hong Kong', '2026-03-12 13:41:01', 1773297722, 1773297753, 1, 2, 1, 'F13E02', 0, 0, '0', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tw_user_coin`
--

CREATE TABLE `tw_user_coin` (
  `id` int UNSIGNED NOT NULL,
  `userid` int UNSIGNED NOT NULL,
  `usdt` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `usdt_d` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `btc` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `btc_d` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `eth` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `eth_d` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `ltc` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `ltc_d` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `sol` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `sol_d` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `xrp` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `xrp_d` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `uni` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `uni_d` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `xau` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `xau_d` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `bch` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `bchd` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `dot` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `dotd` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `trb` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `trbd` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `trx` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `trxd` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `trump` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `trumpd` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `doge` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `doged` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `shibd` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `tao` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `taod` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `link` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `linkd` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `apt` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `aptd` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `bnb` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `bnbd` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `usdc` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000',
  `usdcd` decimal(20,10) UNSIGNED NOT NULL DEFAULT '0.0000000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='用户币种表' ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_user_coin`
--

INSERT INTO `tw_user_coin` (`id`, `userid`, `usdt`, `usdt_d`, `btc`, `btc_d`, `eth`, `eth_d`, `ltc`, `ltc_d`, `sol`, `sol_d`, `xrp`, `xrp_d`, `uni`, `uni_d`, `xau`, `xau_d`, `bch`, `bchd`, `dot`, `dotd`, `trb`, `trbd`, `trx`, `trxd`, `trump`, `trumpd`, `doge`, `doged`, `shibd`, `tao`, `taod`, `link`, `linkd`, `apt`, `aptd`, `bnb`, `bnbd`, `usdc`, `usdcd`) VALUES
(1, 1, 855409.2384230000, 13.0000000000, 0.0299851339, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 40.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.5375335819, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000),
(2, 2, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000),
(1020, 505, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000),
(1021, 506, 6970.1916855500, 0.0000000000, 0.0365550000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000),
(1022, 507, 11000.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000),
(1023, 508, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000),
(1024, 509, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000, 0.0000000000);

-- --------------------------------------------------------

--
-- Table structure for table `tw_user_log`
--

CREATE TABLE `tw_user_log` (
  `id` int UNSIGNED NOT NULL,
  `userid` int UNSIGNED NOT NULL,
  `type` varchar(200) NOT NULL DEFAULT '',
  `remark` varchar(200) NOT NULL DEFAULT '',
  `addip` varchar(200) NOT NULL DEFAULT '',
  `addr` varchar(200) NOT NULL DEFAULT '',
  `sort` int UNSIGNED NOT NULL DEFAULT '0',
  `addtime` int UNSIGNED NOT NULL DEFAULT '0',
  `endtime` int UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='用户记录表' ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tw_user_log`
--

INSERT INTO `tw_user_log` (`id`, `userid`, `type`, `remark`, `addip`, `addr`, `sort`, `addtime`, `endtime`, `status`) VALUES
(10409, 503, 'Đăng ký', 'Đăng ký tài khoản mới', '172.71.219.107', 'Hong Kong', 0, 1772511032, 0, 1),
(10410, 503, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.179.168', 'Hong Kong', 0, 1772513354, 0, 1),
(10411, 503, 'Đăng nhập', 'Đăng nhập bằng email', '172.68.164.50', 'Singapore', 0, 1772517359, 0, 1),
(10412, 503, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.124.217', 'Singapore', 0, 1772521448, 0, 1),
(10413, 504, 'Đăng ký', 'Đăng ký tài khoản mới', '172.71.81.238', 'Singapore', 0, 1772523651, 0, 1),
(10414, 504, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.81.238', 'Singapore', 0, 1772523660, 0, 1),
(10415, 504, 'Đăng nhập', 'Đăng nhập bằng email', '172.70.208.25', 'Singapore', 0, 1772525495, 0, 1),
(10416, 503, 'Đăng nhập', 'Đăng nhập bằng email', '162.159.98.41', 'Hong Kong', 0, 1772526093, 0, 1),
(10417, 503, 'Xác minh tài khoản', 'Gửi thông tin xác minh thành công', '162.159.98.40', 'Unknown', 0, 1772526688, 0, 1),
(10418, 503, 'Đổi mật khẩu', 'Đổi mật khẩu thành công', '162.159.98.40', 'Unknown', 0, 1772526710, 0, 1),
(10419, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.159.98.40', 'Hong Kong', 0, 1772526831, 0, 1),
(10420, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.193.111', 'Hong Kong', 0, 1772594832, 0, 1),
(10421, 2, 'Đăng nhập', 'Đăng nhập bằng email', '172.70.143.224', 'Singapore', 0, 1772602921, 0, 1),
(10422, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.69.166.110', 'Singapore', 0, 1772604499, 0, 1),
(10423, 1, 'Đăng nhập', 'Đăng nhập bằng email', '104.23.175.184', 'Singapore', 0, 1772606954, 0, 1),
(10424, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.108.112', 'Singapore', 0, 1772608771, 0, 1),
(10425, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.162.20', 'Singapore', 0, 1772676706, 0, 1),
(10426, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.162.21', 'Singapore', 0, 1772677167, 0, 1),
(10427, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.159.98.41', 'Hong Kong', 0, 1772695042, 0, 1),
(10428, 2, 'Đăng nhập', 'Đăng nhập bằng email', '172.69.166.111', 'Singapore', 0, 1772695315, 0, 1),
(10429, 2, 'Đăng nhập', 'Đăng nhập bằng email', '172.69.166.111', 'Singapore', 0, 1772701425, 0, 1),
(10430, 1, 'Đăng nhập', 'Đăng nhập bằng email', '108.162.227.135', 'Singapore', 0, 1772702811, 0, 1),
(10431, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.69.166.111', 'Singapore', 0, 1772760094, 0, 1),
(10432, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.70.147.179', 'Singapore', 0, 1772760450, 0, 1),
(10433, 505, 'Đăng ký', 'Đăng ký tài khoản mới', '172.69.131.202', 'Chennai', 0, 1772766681, 0, 1),
(10434, 505, 'Đăng nhập', 'Đăng nhập bằng email', '172.69.131.202', 'Chennai', 0, 1772766686, 0, 1),
(10435, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.88.73', 'Singapore', 0, 1772782602, 0, 1),
(10436, 2, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.88.72', 'Singapore', 0, 1772786700, 0, 1),
(10437, 2, 'Đổi mật khẩu', 'Đổi mật khẩu thành công', '172.69.166.110', 'Unknown', 0, 1772786765, 0, 1),
(10438, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.81.237', 'Singapore', 0, 1772787772, 0, 1),
(10439, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.193.111', 'Hong Kong', 0, 1772790840, 0, 1),
(10440, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.69.176.111', 'Singapore', 0, 1772848464, 0, 1),
(10441, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.68.211.147', 'Hong Kong', 0, 1772852058, 0, 1),
(10442, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.70.208.24', 'Singapore', 0, 1772852771, 0, 1),
(10443, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.162.20', 'Singapore', 0, 1772854663, 0, 1),
(10444, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.214.11', 'Hong Kong', 0, 1772881554, 0, 1),
(10445, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.69.166.111', 'Singapore', 0, 1772888894, 0, 1),
(10446, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.108.113', 'Singapore', 0, 1772933424, 0, 1),
(10447, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.70.143.225', 'Singapore', 0, 1772952907, 0, 1),
(10448, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.68.164.51', 'Singapore', 0, 1772960593, 0, 1),
(10449, 506, 'Đăng ký', 'Đăng ký tài khoản mới', '172.69.166.111', 'Singapore', 0, 1773022753, 0, 1),
(10450, 506, 'Đăng nhập', 'Đăng nhập bằng email', '172.69.166.111', 'Singapore', 0, 1773022762, 0, 1),
(10451, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.114.14', 'Hong Kong', 0, 1773042391, 0, 1),
(10452, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.179.167', 'Hong Kong', 0, 1773042505, 0, 1),
(10453, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.124.216', 'Singapore', 0, 1773043384, 0, 1),
(10454, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.88.73', 'Singapore', 0, 1773044301, 0, 1),
(10455, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.124.217', 'Singapore', 0, 1773048338, 0, 1),
(10456, 507, 'Đăng ký', 'Đăng ký tài khoản mới', '162.158.114.14', 'Hong Kong', 0, 1773072513, 0, 1),
(10457, 507, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.114.14', 'Hong Kong', 0, 1773072542, 0, 1),
(10458, 506, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.214.11', 'Hong Kong', 0, 1773074931, 0, 1),
(10459, 2, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.107.49', 'Singapore', 0, 1773105962, 0, 1),
(10460, 506, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.108.113', 'Singapore', 0, 1773114154, 0, 1),
(10461, 506, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.81.152', 'Singapore', 0, 1773114910, 0, 1),
(10462, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.70.147.179', 'Singapore', 0, 1773115881, 0, 1),
(10463, 506, 'Đăng nhập', 'Đăng nhập bằng email', '108.162.227.135', 'Singapore', 0, 1773116162, 0, 1),
(10464, 506, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.193.111', 'Hong Kong', 0, 1773117823, 0, 1),
(10465, 506, 'Xác minh tài khoản', 'Gửi thông tin xác minh thành công', '162.158.193.111', 'Unknown', 0, 1773117920, 0, 1),
(10466, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.108.112', 'Singapore', 0, 1773120668, 0, 1),
(10467, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.108.112', 'Singapore', 0, 1773120970, 0, 1),
(10468, 1, 'Đăng nhập', 'Đăng nhập bằng email', '104.23.175.185', 'Singapore', 0, 1773121291, 0, 1),
(10469, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.219.108', 'Hong Kong', 0, 1773134698, 0, 1),
(10470, 507, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.219.107', 'Hong Kong', 0, 1773157910, 0, 1),
(10471, 507, 'Đăng nhập', 'Đăng nhập bằng email', '172.68.225.93', 'Hong Kong', 0, 1773157912, 0, 1),
(10472, 507, 'Đăng nhập', 'Đăng nhập bằng email', '172.68.225.93', 'Hong Kong', 0, 1773157912, 0, 1),
(10473, 508, 'Đăng ký', 'Đăng ký tài khoản mới', '162.158.170.81', 'Singapore', 0, 1773195393, 0, 1),
(10474, 508, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.170.81', 'Singapore', 0, 1773195408, 0, 1),
(10475, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.159.98.41', 'Hong Kong', 0, 1773196404, 0, 1),
(10476, 506, 'Đăng nhập', 'Đăng nhập bằng email', '172.70.147.178', 'Singapore', 0, 1773198197, 0, 1),
(10477, 1, 'Đăng nhập', 'Đăng nhập bằng email', '104.23.175.194', 'Singapore', 0, 1773198673, 0, 1),
(10478, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.124.217', 'Singapore', 0, 1773210971, 0, 1),
(10479, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.108.112', 'Singapore', 0, 1773213359, 0, 1),
(10480, 507, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.214.11', 'Hong Kong', 0, 1773247721, 0, 1),
(10481, 507, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.214.11', 'Hong Kong', 0, 1773247722, 0, 1),
(10482, 1, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.219.108', 'Hong Kong', 0, 1773279663, 0, 1),
(10483, 1, 'Đăng nhập', 'Đăng nhập bằng email', '162.158.108.142', 'Singapore', 0, 1773279837, 0, 1),
(10484, 506, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.210.203', 'Hong Kong', 0, 1773293550, 0, 1),
(10485, 509, 'Đăng ký', 'Đăng ký tài khoản mới', '172.71.219.108', 'Hong Kong', 0, 1773297649, 0, 1),
(10486, 509, 'Đăng nhập', 'Đăng nhập bằng email', '172.71.219.108', 'Hong Kong', 0, 1773297661, 0, 1),
(10487, 509, 'Xác minh tài khoản', 'Gửi thông tin xác minh thành công', '162.158.178.88', 'Unknown', 0, 1773297723, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tw_user_qianbao`
--

CREATE TABLE `tw_user_qianbao` (
  `id` int UNSIGNED NOT NULL COMMENT 'ID',
  `userid` int UNSIGNED NOT NULL COMMENT '会员ID',
  `coinname` varchar(200) NOT NULL COMMENT '会员账号',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '币名称',
  `remark` varchar(200) NOT NULL COMMENT '地址备注',
  `czline` varchar(200) NOT NULL COMMENT '充值网络',
  `addr` varchar(200) NOT NULL DEFAULT '' COMMENT '提币地址',
  `sort` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` datetime(6) NOT NULL COMMENT '添加时间',
  `status` int NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='用户钱包表' ROW_FORMAT=COMPACT;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tw_admin`
--
ALTER TABLE `tw_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `tw_adver`
--
ALTER TABLE `tw_adver`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- Indexes for table `tw_appc`
--
ALTER TABLE `tw_appc`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `tw_area`
--
ALTER TABLE `tw_area`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `tw_auth_extend`
--
ALTER TABLE `tw_auth_extend`
  ADD UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`) USING BTREE,
  ADD KEY `uid` (`group_id`) USING BTREE,
  ADD KEY `group_id` (`extend_id`) USING BTREE;

--
-- Indexes for table `tw_auth_group`
--
ALTER TABLE `tw_auth_group`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `tw_auth_group_access`
--
ALTER TABLE `tw_auth_group_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  ADD KEY `group_id` (`group_id`) USING BTREE,
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `tw_auth_rule`
--
ALTER TABLE `tw_auth_rule`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `module` (`module`,`status`,`type`) USING BTREE;

--
-- Indexes for table `tw_bborder`
--
ALTER TABLE `tw_bborder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_bbsetting`
--
ALTER TABLE `tw_bbsetting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_bill`
--
ALTER TABLE `tw_bill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_checkin_log`
--
ALTER TABLE `tw_checkin_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uid_checkin_date` (`uid`,`checkin_date`);

--
-- Indexes for table `tw_coin`
--
ALTER TABLE `tw_coin`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `tw_coin_comment`
--
ALTER TABLE `tw_coin_comment`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `userid` (`userid`) USING BTREE;

--
-- Indexes for table `tw_coin_exchange_history`
--
ALTER TABLE `tw_coin_exchange_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_userid` (`userid`),
  ADD KEY `idx_addtime` (`addtime`),
  ADD KEY `idx_from_to` (`from_coin`,`to_coin`);

--
-- Indexes for table `tw_coin_json`
--
ALTER TABLE `tw_coin_json`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- Indexes for table `tw_config`
--
ALTER TABLE `tw_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_content`
--
ALTER TABLE `tw_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_ctmarket`
--
ALTER TABLE `tw_ctmarket`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `tw_daohang`
--
ALTER TABLE `tw_daohang`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `tw_djprofit`
--
ALTER TABLE `tw_djprofit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_footer`
--
ALTER TABLE `tw_footer`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `tw_hyorder`
--
ALTER TABLE `tw_hyorder`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status_intselltime` (`status`,`intselltime`);

--
-- Indexes for table `tw_hysetting`
--
ALTER TABLE `tw_hysetting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_hy_result_queue`
--
ALTER TABLE `tw_hy_result_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status_round` (`round_no`),
  ADD KEY `idx_addtime` (`addtime`);

--
-- Indexes for table `tw_issue`
--
ALTER TABLE `tw_issue`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `tw_issue_log`
--
ALTER TABLE `tw_issue_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `userid` (`uid`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- Indexes for table `tw_kjorder`
--
ALTER TABLE `tw_kjorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_kjprofit`
--
ALTER TABLE `tw_kjprofit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_kuangji`
--
ALTER TABLE `tw_kuangji`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_market`
--
ALTER TABLE `tw_market`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `tw_market_json`
--
ALTER TABLE `tw_market_json`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- Indexes for table `tw_menu`
--
ALTER TABLE `tw_menu`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE;

--
-- Indexes for table `tw_myzc`
--
ALTER TABLE `tw_myzc`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `userid` (`userid`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `coinname` (`coinname`) USING BTREE;

--
-- Indexes for table `tw_notice`
--
ALTER TABLE `tw_notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_online`
--
ALTER TABLE `tw_online`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_recharge`
--
ALTER TABLE `tw_recharge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_recharge_method`
--
ALTER TABLE `tw_recharge_method`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_coin` (`coin`);

--
-- Indexes for table `tw_trade_json`
--
ALTER TABLE `tw_trade_json`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `market` (`market`) USING BTREE;

--
-- Indexes for table `tw_transfer_history`
--
ALTER TABLE `tw_transfer_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transfer_userid` (`userid`),
  ADD KEY `idx_transfer_coinid` (`coinid`),
  ADD KEY `idx_transfer_addtime` (`addtime`);

--
-- Indexes for table `tw_tyhyorder`
--
ALTER TABLE `tw_tyhyorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tw_user`
--
ALTER TABLE `tw_user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `username_2` (`username`),
  ADD UNIQUE KEY `cccd` (`cccd`),
  ADD KEY `username` (`username`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `invit_1` (`invit_1`),
  ADD KEY `invit_2` (`invit_2`),
  ADD KEY `invit_3` (`invit_3`);

--
-- Indexes for table `tw_user_coin`
--
ALTER TABLE `tw_user_coin`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `userid` (`userid`) USING BTREE;

--
-- Indexes for table `tw_user_log`
--
ALTER TABLE `tw_user_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `userid` (`userid`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- Indexes for table `tw_user_qianbao`
--
ALTER TABLE `tw_user_qianbao`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `userid` (`userid`) USING BTREE,
  ADD KEY `coinname` (`coinname`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tw_admin`
--
ALTER TABLE `tw_admin`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tw_adver`
--
ALTER TABLE `tw_adver`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tw_appc`
--
ALTER TABLE `tw_appc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tw_area`
--
ALTER TABLE `tw_area`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `tw_auth_group`
--
ALTER TABLE `tw_auth_group`
  MODIFY `id` mediumint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tw_auth_group_access`
--
ALTER TABLE `tw_auth_group_access`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tw_auth_rule`
--
ALTER TABLE `tw_auth_rule`
  MODIFY `id` mediumint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键', AUTO_INCREMENT=2464;

--
-- AUTO_INCREMENT for table `tw_bborder`
--
ALTER TABLE `tw_bborder`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT '记录ID', AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `tw_bbsetting`
--
ALTER TABLE `tw_bbsetting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT '记录ID', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tw_bill`
--
ALTER TABLE `tw_bill`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT '记录ID', AUTO_INCREMENT=133634;

--
-- AUTO_INCREMENT for table `tw_checkin_log`
--
ALTER TABLE `tw_checkin_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3522;

--
-- AUTO_INCREMENT for table `tw_coin`
--
ALTER TABLE `tw_coin`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tw_coin_comment`
--
ALTER TABLE `tw_coin_comment`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tw_coin_exchange_history`
--
ALTER TABLE `tw_coin_exchange_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tw_coin_json`
--
ALTER TABLE `tw_coin_json`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tw_config`
--
ALTER TABLE `tw_config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT '记录ID', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tw_content`
--
ALTER TABLE `tw_content`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tw_ctmarket`
--
ALTER TABLE `tw_ctmarket`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tw_daohang`
--
ALTER TABLE `tw_daohang`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tw_djprofit`
--
ALTER TABLE `tw_djprofit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT '记录ID';

--
-- AUTO_INCREMENT for table `tw_footer`
--
ALTER TABLE `tw_footer`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tw_hyorder`
--
ALTER TABLE `tw_hyorder`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2027;

--
-- AUTO_INCREMENT for table `tw_hysetting`
--
ALTER TABLE `tw_hysetting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tw_hy_result_queue`
--
ALTER TABLE `tw_hy_result_queue`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tw_issue`
--
ALTER TABLE `tw_issue`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tw_issue_log`
--
ALTER TABLE `tw_issue_log`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID', AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT for table `tw_kjorder`
--
ALTER TABLE `tw_kjorder`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT '记录ID', AUTO_INCREMENT=614;

--
-- AUTO_INCREMENT for table `tw_kjprofit`
--
ALTER TABLE `tw_kjprofit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT '记录ID';

--
-- AUTO_INCREMENT for table `tw_kuangji`
--
ALTER TABLE `tw_kuangji`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tw_market`
--
ALTER TABLE `tw_market`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tw_market_json`
--
ALTER TABLE `tw_market_json`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tw_menu`
--
ALTER TABLE `tw_menu`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文档ID', AUTO_INCREMENT=498;

--
-- AUTO_INCREMENT for table `tw_myzc`
--
ALTER TABLE `tw_myzc`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=375;

--
-- AUTO_INCREMENT for table `tw_notice`
--
ALTER TABLE `tw_notice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT '记录ID', AUTO_INCREMENT=2408;

--
-- AUTO_INCREMENT for table `tw_online`
--
ALTER TABLE `tw_online`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tw_recharge`
--
ALTER TABLE `tw_recharge`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tw_recharge_method`
--
ALTER TABLE `tw_recharge_method`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tw_trade_json`
--
ALTER TABLE `tw_trade_json`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tw_transfer_history`
--
ALTER TABLE `tw_transfer_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tw_tyhyorder`
--
ALTER TABLE `tw_tyhyorder`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT for table `tw_user`
--
ALTER TABLE `tw_user`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=510;

--
-- AUTO_INCREMENT for table `tw_user_coin`
--
ALTER TABLE `tw_user_coin`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1025;

--
-- AUTO_INCREMENT for table `tw_user_log`
--
ALTER TABLE `tw_user_log`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10488;

--
-- AUTO_INCREMENT for table `tw_user_qianbao`
--
ALTER TABLE `tw_user_qianbao`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
