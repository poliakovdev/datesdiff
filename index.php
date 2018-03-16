<?php

    class Dates{
        public $years; /** years quantity between dates */
        public $months; /** months quantity between dates */
        public $days; /** days quantity between dates */
        public $total_days; /** summary quantity days between dates */
        public $invert; /** true, if start date > end date */

        /** @var array of days in months */
        public $days_in_months = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        /** limit days for lofty year, for increase from 28 to 29 February*/
        const DAYS_LIMIT = 58;
        /** quantity days in year */
        const DAYS_IN_YEAR = 365;

        public function __construct(string $date_first, string $date_second){
            $date_first_arr = $this->arr_int($date_first);
            $date_second_arr = $this->arr_int($date_second);

            $this->years = $this->difference($date_first_arr[0], $date_second_arr[0]);
            $this->months = $this->difference($date_first_arr[1], $date_second_arr[1]);
            $this->days = $this->difference($date_first_arr[2], $date_second_arr[2]);

            $total_days_in_date_1 = $this->total_days_in_date($date_first_arr);
            $total_days_in_date_2 = $this->total_days_in_date($date_second_arr);
            $this->total_days = $this->difference_in_days($total_days_in_date_1, $total_days_in_date_2);
        }

        /**
         * write years
         */
        public function write_years(){
            echo $this->years;
        }

        /**
         * write months
         */
        public function write_months(){
            echo $this->months;
        }

        /**
         * write days
         */
        public function write_days(){
            echo $this->days;
        }

        /**
         * write total days between dates
         */
        public function write_total_days(){
            echo $this->total_days;
        }

        /**
         * write 1 if date_start > date_end
         */
        public function write_invert(){
            echo $this->invert;
        }

        /**
         * @return array of validation input date value
         */
        protected function date_validate(string $date_string){
            $pattern = '/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/';
            preg_match($pattern, $date_string) or die("Wrong date format!!!");
        }

        /**
         * @return array of int values
         */
        protected function arr_int(string $date_string){
            $this->date_validate($date_string);
            $date_arr = explode("-", $date_string);
            $count = count($date_arr);
            for($i = 0; $i < $count; $i++){
                $date_arr[$i] = intval($date_arr[$i]);
            }
            return $date_arr;
        }

        /**
         * @return int difference between input values
         */        
        protected function difference(int $date_start, int $date_end){
            $difference = $date_start - $date_end;
            return $difference;
        }

        /**
         * @return array of values in days
         */
        protected function total_days_in_date(array $date_arr){
            $year = $date_arr[0];
            $month = $date_arr[1];
            $day = $date_arr[2];

            if($month > 1){
                for($i = 0; $i < $month - 1; $i++){
                    /** number of days in full months */
                    $days_in_months+= $this->days_in_months[$i];
                }
            }
            for($i = 1; $i < $year; $i++){
                if($i % 4 == 0){
                    /** lofty days in date */
                    $lofty_days += 1;
                }
            }
            if($days_in_months + $day > self::DAYS_LIMIT){
                /** if days in lofty year more, than 58
                lofty day will increase by 1 */
                $lofty_days+= 1;
            }
            
            $year_days = ($year - 1) * self::DAYS_IN_YEAR;
            $days_count = $year_days + $lofty_days + $days_in_months + $day;
            return $days_count;
        }

        /**
         * @return int difference between days
         */
        protected function difference_in_days(int $days_start, int $days_end){
            $days_difference = $days_start - $days_end;
            $this->invert = ($days_difference > 0) ? true : false;
            return $days_difference;
        }
    }