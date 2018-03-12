<?php
    class Passenger {
        private $id;
        private $given_name;
        private $surname;
        private $email;
        private $mobile;
        private $passport;
        private $birth_date;
        private $status;
        
   
    
        
    
        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }
    
        /**
         * @return mixed
         */
        public function getGiven_name()
        {
            return $this->given_name;
        }
    
        /**
         * @return mixed
         */
        public function getSurname()
        {
            return $this->surname;
        }
    
        /**
         * @return mixed
         */
        public function getEmail()
        {
            return $this->email;
        }
    
        /**
         * @return mixed
         */
        public function getMobile()
        {
            return $this->mobile;
        }
    
        /**
         * @return mixed
         */
        public function getPassport()
        {
            return $this->passport;
        }
    
        /**
         * @return mixed
         */
        public function getBirth_date()
        {
            return $this->birth_date;
        }
    
        /**
         * @return mixed
         */
        public function getStatus()
        {
            return $this->status;
        }
    
        public function __construct($id, $given_name, $surname, $email, $mobile, $passport, 
            $birth_date, $status) {
            
            $this->id = $id;
            $this->given_name = $given_name;
            $this->surname = $surname;
            $this->email = $email;
            $this->mobile = $mobile;
            $this->passport = $passport;
            $this->birth_date = $birth_date;
            $this->status = $status;
        }
    }

?>