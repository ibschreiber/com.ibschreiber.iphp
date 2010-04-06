<?php

require_once ('Interface.php');

/**
 * Public and bank holidays in England and Wales
 * 
 * @author andy.roberts
 */

class Holiday_EnglandWales implements Holiday_Interface
{
    
    const DAY = 86400;
    const MONDAY = 1;
    const SATURDAY = 6;
    const SUNDAY = 7;

    /**
     * Public and bank holiday dates
     * 
     * @var array
     */
    protected $_dates = array();

    /**
     * Public and  bank holiday names
     * 
     * @var array
     */
    protected $_holidays = array();

    /**
     * Public and bank holiday substitutes
     * 
     * @var boolean
     */
    protected $_substitute = array(
        'newYearDay' , 'christmasDay' , 
        'boxingDay'
    );

    /**
     * Constructor
     * 
     */
    public function __construct()
    {
        
        $this->_holidays = array(
            
            'newYearDay' => 'New Year\'s Day' , 
            'easterSunday' => 'Easter Sunday' , 
            'goodFriday' => 'Good Friday' , 
            'easterMonday' => 'Easter Monday' , 
            'earlyMayBankHoliday' => 'Early May Bank Holiday' , 
            'springBankHoliday' => 'Spring Bank Holiday' , 
            'summerBankHoliday' => 'Summer Bank Holiday' , 
            'christmasDay' => 'Christmas Day' , 
            'boxingDay' => 'Boxing Day'
        );
    }

    /**
     * Disable substitute holidays
     * 
     * @return void
     */
    
    public function disableSubstituteDays()
    {
        $this->_substitute = array();
    }

    /**
     * Get public and bank holidays for a specific year
     * 
     * @param int $year
     */
    public function getHoliday($year)
    {
        
        foreach ($this->_holidays as $key => $name) {
            $this->addHoliday($name, $this->{$key}($year), $key);
        }
        
        $this->_substituteHolidays($year);
        
        return $this->_dates[$year];
    }

    /**
     * Add single holiday event
     * 
     * @param string $holiday Name of event
     * @param int $timestamp Date in seconds
     */
    public function addHoliday($name, $timestamp)
    {
        $this->_dates[date('Y', $timestamp)][$timestamp] = array(
            'name' => $name , 
            'date' => date('Y-m-d', $timestamp) , 
            'timestamp' => $timestamp
        );
    }

    /**
     * Remove single holiday event
     * 
     * @param string $timestamp
     * @return boolean
     */
    public function removeHoliday($timestamp)
    {
        
        $year = date('Y', $timestamp);
        
        if (array_key_exists($timestamp, $this->_dates[$year])) {
            unset($this->_dates[$year][$timestamp]);
            return true;
        }
        
        return false;
    }

    /**
     * New Years Day
     * 
     * @param string $year
     * @return timestamp
     */
    public function newYearDay($year)
    {
        return mktime(0, 0, 0, 1, 1, $year);
    }

    /**
     * Good Friday
     *
     * @param string $year
     * @return timestamp
     */
    public function goodFriday($year)
    {
        return $this->easterSunday($year) - (self::DAY * 2);
    }

    /**
     * Easter Monday
     *
     * @param string $year
     * @return timestamp
     */
    public function easterMonday($year)
    {
        return $this->easterSunday($year) + self::DAY;
    }

    /**
     * Early May Bank Holiday : First Monday of May
     * 
     * @param string $year
     * @return timestamp
     */
    public function earlyMayBankHoliday($year)
    {
        return $this->_calculateFloatingHoliday(self::MONDAY, 1, 5, $year);
    }

    /**
     * Spring Bank Holiday : Last Monday of May
     * 
     * @param string $year
     * @return timestamp
     */
    public function springBankHoliday($year)
    {
        return $this->_calculatefloatingHoliday(self::MONDAY, 5, 5, $year);
    }

    /**
     * Summer Bank Holiday : Last Monday of August
     * 
     * @param string $year
     * @return timestamp
     */
    public function summerBankHoliday($year)
    {
        return $this->_calculatefloatingHoliday(self::MONDAY, 5, 8, $year);
    }

    /**
     * This function generates the Christmas holidays for the supplied $year
     *
     * @param string $year
     * @return array
     */
    public function christmasDay($year)
    {
        return mktime(0, 0, 0, 12, 25, $year);
    }

    /**
     * This function generates the Boxing day holidays for the supplied $year
     *
     * @param string $year
     * @return array
     */
    
    public function boxingDay($year)
    {
        return mktime(0, 0, 0, 12, 26, $year);
    }

    /**
     * Calculates date for Easter using the Gaussian algorithm.
     *
     * The year must be inside the range for Unix timestamps 
     * between 1970 snd 2037.
     * 
     * @param int $year year
     * @see http://www.smart.net/~mmontes/ortheast.html
     * @return   timestamp
     */
    public function easterSunday($year)
    {
        $offset = mktime(0, 0, 0, 3, 21, $year);
        
        if (function_exists('easter_days')) {
            return $offset + (easter_days($year) * self::DAY);
        }
        
        $julianOffset = 13;
        if ($year > 2100) {
            $julianOffset = 14;
        }
        $a = $year % 19;
        $b = $year % 4;
        $c = $year % 7;
        $ra = (19 * $a + 16);
        $r4 = $ra % 30;
        $rb = 2 * $b + 4 * $c + 6 * $r4;
        $r5 = $rb % 7;
        $rc = $r4 + $r5 + $julianOffset;
        
        return $offset + ($rc * self::DAY);
    }

    /**
     * Subsitute pre-defined public or bank holidays that fall on 
     * the weekend with the next available week day, typically 
     * Monday.
     * 
     * @param int $year
     */
    private function _substituteHolidays($year)
    {
        if (count($this->_substitute) > 0) {
            foreach ($this->_substitute as $key) {
                
                $timestamp = $this->{$key}($year);
                
                if (in_array($this->_dayOfWeek($timestamp), array(
                    
                    self::SATURDAY , 
                    self::SUNDAY
                ))) {
                    $this->removeHoliday($timestamp);
                    $this->addHoliday($this->_holidays[$key], $this->_nextSubstituteDay($timestamp));
                }
            }
        }
    }

    /**
     * Determine the next substitute day
     * 
     * @param int $timestamp
     */
    private function _nextSubstituteDay($timestamp)
    {
        $nextMonday = strtotime('next Monday', $timestamp);
        
        if ($this->_dayOfWeek($timestamp) == self::SATURDAY) {
            return $nextMonday;
        }
        
        if ($this->_dayOfWeek($timestamp) == self::SUNDAY) {
            if (array_key_exists($nextMonday, $this->_dates[date('Y', $timestamp)])) {
                return $nextMonday + self::DAY;
            }
            
            return $nextMonday;
        }
        
        return $timestamp;
    }

    /**
     * Determine the date of a floating holiday for a given month 
     * and year
     * 
     * The ordinal will indicate the nth position of the week day 
     * in the month. 
     * 
     * @param int $weekDay Week day number
     * @param int $ordinal Numeric ordinal of a week day occurance
     * @param int $month The month number
     * @param int $year The year 
     */
    private function _calculateFloatingHoliday($weekDay, $ordinal, $month, $year)
    {
        // Calculate the earliest date for the nth weekday occurance
        $earliestDay = 1 + 7 * ($ordinal - 1);
        $earliestWeekDay = date('w', mktime(0, 0, 0, $month, $earliestDay, $year));
        
        // Find the offset between the weekday and nth weekday
        if ($weekDay == $earliestWeekDay) {
            $offset = 0;
        } else {
            if ($weekDay < $earliestWeekDay) {
                $offset = $weekDay + (7 - $earliestWeekDay);
            } else {
                $offset = ($weekDay + (7 - $earliestWeekDay)) - 7;
            }
        }
        
        $date = mktime(0, 0, 0, $month, ($earliestDay + $offset), $year);
        return $date;
    }

    /**
     * ISO 8601 representation of a weekday
     * 
     * @param timestamp $timestamp
     * @return int
     */
    private function _dayOfWeek($timestamp)
    {
        return date('N', $timestamp);
    }
}