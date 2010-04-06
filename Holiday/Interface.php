<?php
/**
 * Holiday Interface
 * 
 * @author andy.roberts
 */

interface Holiday_Interface
{

    /**
     * Retrieve a list of holidays for supplied year
     * 
     * @param int $year Holiday year
     * @param int $subsitute Subsitute holiday on weekend
     */
    public function getHoliday($year);

    /**
     * Add a single holiday event
     * 
     * @param string $name
     * @param int $timestamp
     */
    public function addHoliday($name, $timestamp);

    /**
     * Remove single holiday event
     * 
     * @param int $timestamp
     */
    public function removeHoliday($timestamp);
}