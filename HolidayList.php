<?php
/**
 * 祝日カレンダー
 *
 * 日本の祝日です。
 *
 * @package     HolidayDateJPN
 * @author      Y.Yajima <yajima@hatchbit.jp>
 * @copyright   2016, HatchBit & Co.
 * @license     http://www.hatchbit.jp/resource/license.html
 * @link        http://www.hatchbit.jp
 * @since       Version 1.0
 * @filesource
 */
/*====================
  DEFINE
  ====================*/

/*====================
  BEFORE ACTIONS
  ====================*/
require_once "./HolidayCheck.php";

/*====================
  MAIN ACTIONS
  ====================*/

/*====================
  AFTER ACTIONS
  ====================*/

/*====================
  FUNCTIONS
  ====================*/
function HolidayList($StartYear, $LastYear) {
    if (empty($StartYear)) $StartYear = date("Y");
    if (empty($LastYear)) $LastYear = date("Y");
    
    $FirstDate = strtotime(strval($StartYear)."/1/1");
    $LastDate = strtotime(strval($LastYear + 1)."/1/1");
    
    $OneDay = 24 * 60 * 60;
    $HolidayCount = 0;
    $HolidayData = array();
    $workDate = $FirstDate;
    $strDate = "";
    $HolidayName = "";
    
    while ($workDate < $LastDate) {
        $strDate = date("Y/n/j", $workDate);
        $HolidayName = ktHolidayName($strDate);
        if ($HolidayName != "") {
            $HolidayCount++;
            $HolidayData[] = array($HolidayName, $strDate);
        }
        $workDate += $OneDay;
    }
    
    return $HolidayData;
}