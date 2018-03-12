<?php
//    `id` int NOT NULL AUTO_INCREMENT,
//    `name` varchar(256) NOT NULL,
//    `itinerary` text NOT NULL,
//    `status` tinyint NOT NULL,
    class TourInfo {
        private $id;
        private $name;
        private $itinerary;
        private $status;
        
        public function getId(){ return $this->id; }
        public function getName(){ return $this->name; }
        public function getItinerary(){ return $this->itinerary; }
        public function getStatus(){ return $this->status; }
        
        public function __construct($id, $name, $itinerary, $status) {
            $this->id = $id;
            $this->name = $name;
            $this->itinerary = $itinerary;
            $this->status = $status;
        }
    }
?>