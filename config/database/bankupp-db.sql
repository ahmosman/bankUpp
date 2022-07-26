-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 26 Lip 2022, 18:35
-- Wersja serwera: 10.4.18-MariaDB
-- Wersja PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `bankupp`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_code` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` double NOT NULL,
  `account_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `account`
--

INSERT INTO `account` (`id`, `user_id`, `account_code`, `balance`, `account_type_id`) VALUES
(33, 39, '54138830050148674241747198', 90, 1),
(34, 40, '73975153198452597958591661', 110, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `account_type`
--

CREATE TABLE `account_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_bonus` int(11) NOT NULL,
  `css_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `account_type`
--

INSERT INTO `account_type` (`id`, `name`, `start_bonus`, `css_class`) VALUES
(1, 'KONTO STANDARD', 100, 'account-type-1'),
(2, 'KONTO EXTRA', 300, 'account-type-2'),
(3, 'KONTO PLATINUM', 500, 'account-type-3');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `transfer_history`
--

CREATE TABLE `transfer_history` (
  `id` int(11) NOT NULL,
  `to_account_id` int(11) NOT NULL,
  `from_account_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `transfer_history`
--

INSERT INTO `transfer_history` (`id`, `to_account_id`, `from_account_id`, `amount`, `date`) VALUES
(1, 33, 34, 2, '2022-07-26 17:44:08'),
(2, 34, 33, 12, '2022-07-26 17:44:20');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesel` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `phone_account_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`, `pesel`, `phone_number`, `address_id`, `is_verified`, `phone_account_id`) VALUES
(1, 'admin@bankupp.com', '[\"ROLE_ADMIN\"]', '$2y$13$ORFYKbLbADY5qvch7ew3auZeSjMLtvxUE/RAgmivSBgadD/mTJiii', 'Jan', 'Kowalski', '32132143175', '534654732', 1, 1, NULL),
(38, 'banker@bankupp.com', '[\"ROLE_BANKER\"]', '$2y$13$HgCAx6YY0wPnzwjNz22geuOPONhee1KQFAUJexWTxHMUoh93t7JZO', 'Katarzyna', 'Nowak', '65432737838', '654732817', 32, 1, NULL),
(39, 'rob.user@bankupp.com', '[\"ROLE_BANK_USER\"]', '$2y$13$gBT2zqo0bXVSa2APAOJYOur7F/TG6qx7tLqr2MW8jHvpY5mb2iBJK', 'Robert', 'Jakubowski', '76546532112', '765832172', 33, 1, 33),
(40, 'ann.user@bankupp.com', '[\"ROLE_BANK_USER\"]', '$2y$13$Q6rFqeYxmiyEWKOU4o/6J.keeCFzeo1vT0QgP7vdnqILVkflF1DEy', 'Anna', 'Adamowicz', '84848392929', '723456712', 34, 1, 34);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_address`
--

CREATE TABLE `user_address` (
  `id` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `user_address`
--

INSERT INTO `user_address` (`id`, `address`, `postal_code`, `city`) VALUES
(1, 'Rybaki 19', '61-884', 'Poznań'),
(32, 'Mickiewicza 14', '64-000', 'Kościan'),
(33, 'Fredry 13', '61-701', 'Poznań'),
(34, 'Os. Kwiatowe 10', '62-200', 'Modliszewo');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7D3656A4A76ED395` (`user_id`),
  ADD KEY `IDX_7D3656A4C6798DB` (`account_type_id`);

--
-- Indeksy dla tabeli `account_type`
--
ALTER TABLE `account_type`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `transfer_history`
--
ALTER TABLE `transfer_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_980F0200BC58BDC7` (`to_account_id`),
  ADD KEY `IDX_980F0200B0CF99BD` (`from_account_id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D6493931747B` (`pesel`),
  ADD UNIQUE KEY `UNIQ_8D93D649B360992F` (`phone_account_id`),
  ADD KEY `IDX_8D93D649F5B7AF75` (`address_id`);

--
-- Indeksy dla tabeli `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT dla tabeli `account_type`
--
ALTER TABLE `account_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `transfer_history`
--
ALTER TABLE `transfer_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT dla tabeli `user_address`
--
ALTER TABLE `user_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `FK_7D3656A4A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_7D3656A4C6798DB` FOREIGN KEY (`account_type_id`) REFERENCES `account_type` (`id`);

--
-- Ograniczenia dla tabeli `transfer_history`
--
ALTER TABLE `transfer_history`
  ADD CONSTRAINT `FK_980F0200B0CF99BD` FOREIGN KEY (`from_account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_980F0200BC58BDC7` FOREIGN KEY (`to_account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649B360992F` FOREIGN KEY (`phone_account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8D93D649F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `user_address` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
