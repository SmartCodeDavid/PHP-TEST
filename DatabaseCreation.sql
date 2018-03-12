CREATE DATABASE IF NOT EXISTS `php_test`;
USE `php_test`;

CREATE TABLE tour(
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(256) NOT NULL,
    `itinerary` text NOT NULL,
    `status` tinyint NOT NULL,
     PRIMARY KEY (`id`)
);

CREATE TABLE tour_date(
    `id` int NOT NULL AUTO_INCREMENT,
    `tour_id` int NOT NULL,
    `date` date NOT NULL,
    `status` tinyint NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tour_id`) REFERENCES tour(`id`)
);

CREATE TABLE t_booking(
    `id` int NOT NULL AUTO_INCREMENT,
    `tour_id` int NOT NULL,
    `tour_date` date NOT NULL,
    `status` tinyint,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tour_id`) REFERENCES tour(`id`)
);

CREATE TABLE passenger(
    `id` int NOT NULL AUTO_INCREMENT,
    `given_name` varchar(128) NOT NULL,
    `surname` varchar(64) NOT NULL,
    `email` varchar(128) NOT NULL,
    `mobile` varchar(16) NOT NULL,
    `passport` varchar(16) NOT NULL,
    `birth_date` date NOT NULL,
    `status` tinyint NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE t_booking_passenger(
    `id` int NOT NULL AUTO_INCREMENT,
    `booking_id` int NOT NULL,
    `passenger_id` int NOT NULL,
    `sepecial_request` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`booking_id`) REFERENCES t_booking(`id`),
    FOREIGN KEY (`passenger_id`) REFERENCES passenger(`id`)
);


CREATE TABLE t_invoice(
    `id` int NOT NULL AUTO_INCREMENT,
    `booking_id` int NOT NULL,
    `amount` double NOT NULL,
    `status` tinyint NOT NULL,
    PRIMARY KEY(`id`),
    FOREIGN KEY (`booking_id`) REFERENCES t_booking(`id`)
);







