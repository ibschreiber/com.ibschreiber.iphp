<?php

require_once 'PHPUnit\Framework\TestCase.php';
require_once 'WorkDay.php';
require_once 'Holiday\EnglandWales.php';

/**
 * Working Days Test Case
 */
class WorkDayTest extends PHPUnit_Framework_TestCase
{

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        date_default_timezone_set('Europe/London');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {

    }

    /**
     * Working days in a single month
     * 
     * January 2010
     * 
     * 31 days
     * 21 working days
     * 1 holiday (New Years Day)
     */
    public function testWorkingDayInSingleMonth()
    {
        $workDay = new WorkDay(new Holiday_EnglandWales());
        $this->assertEquals($workDay->count('2010-01-01', '2010-01-31'), 20);
    }

    /**
     * Working days excluding holidays in a single month 
     * 
     * January 2010
     * 
     * 31 days
     * 21 working days
     */
    public function testWorkingDayInSingleMonthWithoutHoliday()
    {
        $workDay = new WorkDay(new Holiday_EnglandWales());
        $this->assertEquals($workDay->count('2010-01-01', '2010-01-31', false), 21);
    }

    /**
     * Working days in a month range
     * 
     * 1st January 2010 - May 31st 2010
     * 
     * 150 days
     * 106 working days
     * 5 holidays
     * New Years Day, Good Friday, Easter Monday, 
     * Early May Bank Holiday, Spring Bank Holiday 
     */
    
    public function testWorkingDayInMonthRange()
    {
        $workDay = new WorkDay(new Holiday_EnglandWales());
        $this->assertEquals($workDay->count('2010-01-01', '2010-05-31'), 101);
    }

    /**
     * Working days in a month range ending on a weekend.
     * 
     * 1st January 2010 - May 1st 2010
     * 
     * 120 days
     * 85 working days (excluding 1st May 2010)
     * 3 holidays
     * New Years Day, Good Friday, Easter Monday
     */
    public function testWorkingDayInMonthRangeEndingOnWeekend()
    {
        $workDay = new WorkDay(new Holiday_EnglandWales());
        $this->assertEquals($workDay->count('2010-01-01', '2010-05-01'), 82);
    }

    /**
     * Working days in a year range
     * 
     * 1st January 2010 - 1st January 2011
     * 
     * 365 days
     * 261 working days
     * 8 holidays
     * 
     * New Years Day 2010, Good Friday, Easter Monday, 
     * Early May Bank Holiday, Spring Bank Holiday, 
     * Summer Bank Holiday, Christmas Day, Boxing Day
     * 
     * New Years Day 2011 falls on Saturday, so day in lieu
     * will be Monday, 3rd January 2011.
     */
    public function testWorkingDayInYearlyRange()
    {
        $workDay = new WorkDay(new Holiday_EnglandWales());
        $this->assertEquals($workDay->count('2010-01-01', '2011-01-01'), 253);
    }

    /**
     * Working days in a spread bi-yearly range
     * 
     * 1st January 2010 - 1st January 2012
     * 
     * 1096 days
     * 781 working days (excluding 1st January 2012)
     * 25 holidays
     * 
     * New Years Day, Good Friday, Easter Monday, 
     * Early May Bank Holiday, Spring Bank Holiday, 
     * Summer Bank Holiday, Christmas Day, Boxing Day
     * 
     * Also the exceptional addition of a one time event,
     * the Queens Diamond Jubilee.
     */
    
    public function testWorkingDayInBiYearlyRangeWithCustomHoliday()
    {
        $holiday = new Holiday_EnglandWales();
        $holiday->addHoliday('Queens Diamond Jubilee', strtotime('2012-06-05'));
        
        $workDay = new WorkDay($holiday);
        $this->assertEquals($workDay->count('2010-01-01', '2012-12-31'), 756);
    }

    /**
     * Public Holidays with Subsitutes
     * 
     * Test public holidays for 2010
     */
    
    public function testPublicHolidayWithSubstitutes()
    {
        
        $holiday = new Holiday_EnglandWales();
        $dates = array();
        
        foreach ($holiday->getHoliday('2010') as $holiday) {
            $dates[] = $holiday['date'];
        }
        
        $testDates = array(
            '2010-01-01' , 
            '2010-04-04' , 
            '2010-04-02' , 
            '2010-04-05' , 
            '2010-05-03' , 
            '2010-05-31' , 
            '2010-08-30' , 
            '2010-12-27' , 
            '2010-12-28'
        );
        $this->assertEquals($dates, $testDates);
    }

    /**
     * Public Holidays with Subsitutes
     * 
     * Test public holidays for 2010
     */
    
    public function testPublicHolidayWithoutSubstitutes()
    {
        
        $holiday = new Holiday_EnglandWales();
        $holiday->disableSubstituteDays();
        
        $dates = array();
        
        foreach ($holiday->getHoliday('2010') as $holiday) {
            $dates[] = $holiday['date'];
        }
        
        $testDates = array(
            '2010-01-01' , 
            '2010-04-04' , 
            '2010-04-02' , 
            '2010-04-05' , 
            '2010-05-03' , 
            '2010-05-31' , 
            '2010-08-30' , 
            '2010-12-25' , 
            '2010-12-26'
        );
        $this->assertEquals($dates, $testDates);
    }
}