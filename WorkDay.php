<?php

/**
 * Calculate the number of working days between two dates
 * 
 * This is achieved by a simple algorithm which calculates 
 * the number of complete weeks and remaining days within 
 * any given period.
 * 
 * Each complete week is multiplied by the number of
 * working days, and the remaining days enumerated.
 * 
 * Public holidays are included in the calculation.
 * 
 * @author andy.roberts
 */

class WorkDay
{
    
    const DAY = 86400;
    const WEEK = 604800;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

    /**
     * The start date timestamp
     * 
     * @var int
     */
    private $_startDate;

    /**
     * The end date timestamp
     * 
     * @var int
     */
    private $_endDate;

    /**
     * List of non-working days.
     * 
     * @var array
     */
    private $_nonWorkingDay = array();

    /**
     * Holiday Class
     * 
     * @var Holiday_Interface
     */
    private $_holiday;

    /**
     * List of holidays, indexed by year
     * 
     * @var array
     */
    private $_holidayList = array();

    /**
     * Include end day in calculation (+ 1 day)
     * 
     * @var bool
     */
    private $_includeEndDay = false;

    /**
     * Constructor
     * 
     * @param Holiday_Interface $holiday
     * @param int $params Parameters for calculating work days.
     */
    public function __construct(Holiday_Interface $holiday, $params = array())
    {
        
        $this->_nonWorkingDay = array(
            self::SATURDAY , 
            self::SUNDAY
        );
        
        if (isset($params['includeEndDay'])) {
            $this->_includeEndDay = ($params['includeEndDay'] == true) ? true : false;
        }
        
        $this->_holiday = $holiday;
    }

    /**
     * Count the number of working days
     * 
     * @param mixed $startDate Start date as timestamp or string, as per strtotime()
     * @param mixed $endDate End date as timestamp or string, as per strtotime()
     * @param int $holidays Include holidays in calculation
     */
    public function count($startDate, $endDate, $countHolidays = true)
    {
        
        $this->_startDate = $this->_parseDate($startDate);
        $this->_endDate = $this->_parseDate($endDate);
        
        $difference = $this->_endDate - $this->_startDate;
        
        if ($this->_includeEndDay) {
            $difference += self::DAY;
        }
        
        $days = floor($difference / self::DAY);
        $weeks = floor($difference / self::WEEK);
        
        // Calculate the working days for complete weeks and remaining days
        $workDays = ($weeks * (7 - count($this->_nonWorkingDay)));
        $remainingDays = ($days % 7);
        
        // Subtract any work days from the remaining days
        if ($remainingDays > 0) {
            $x = $this->_dayOfWeek($this->_startDate) + $remainingDays;
            foreach ($this->_nonWorkingDay as $nonWorkDay) {
                if ($x > $nonWorkDay) {
                    $remainingDays --;
                }
            }
            $workDays += $remainingDays;
        }
        
        // Subtract any public holidays which fall within the date range
        if ($countHolidays == true) {
            $holidays = $this->_holidayInDateRange($this->_startDate, $this->_endDate);
            $workDays -= count($holidays);
        }
        
        return $workDays;
    }

    /**
     * Calculate the number of holidays between two dates
     * 
     * @param unknown_type $startDate
     * @param unknown_type $endDate
     */
    private function _holidayInDateRange($startDate, $endDate)
    {
        return array_filter($this->_findHolidays($startDate, $endDate), array(
            $this , 
            '_holidayInDateRangeComparator'
        ));
    }

    /*
	 * Compare if a holiday exists between two dates
	 * 
	 * @param int $startDate
	 * @param inr $endDate
	 */
    private function _holidayInDateRangeComparator($holiday)
    {
        return ($holiday['timestamp'] >= $this->_startDate && $holiday['timestamp'] <= $this->_endDate);
    }

    /**
     * Find public and bank holidays
     * 
     * @param unknown_type $startDate
     * @param unknown_type $endDate
     */
    private function _findHolidays($startDate, $endDate)
    {
        
        $holidays = array();
        
        for ($year = date('Y', $startDate); $year <= date('Y', $endDate); $year ++) {
            
            // Find holidays for years which have not already been enumerated
            if (! array_key_exists($year, $this->_holidayList)) {
                foreach ($this->_holiday->getHoliday($year) as $holiday) {
                    if (! array_intersect(array(
                        
                        $this->_dayOfWeek($holiday['timestamp'])
                    ), $this->_nonWorkingDay)) {
                        $this->_holidayList[$year][$holiday['timestamp']] = $holiday;
                    }
                }
            
            }
            
            $holidays = array_merge($holidays, $this->_holidayList[$year]);
        }
        
        return $holidays;
    }

    /**
     * Parses a date and returns a valid timestamp
     * 
     * @see http://uk.php.net/manual/en/function.strtotime.php
     * @param mixed $date
     * @return int A valid timestamp
     */
    private function _parseDate($date)
    {
        if (is_int($date)) {
            return $date;
        }
        return strtotime($date, 0);
    }

    /**
     * Return ISO-8601 representation of a weekday
     * 
     * @param int $timestamp
     * @return int week day number
     */
    private function _dayOfWeek($timestamp)
    {
        return date('N', $timestamp);
    }
}