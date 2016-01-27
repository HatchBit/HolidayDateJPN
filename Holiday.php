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

if (isset($_GET['date']) && !empty($_GET['date'])) {
    $strDate = strval($_GET['date']);
} elseif (isset($_POST['date']) && !empty($_POST['date'])) {
    $strDate = strval($_POST['date']);
} else {
    exit();
}

$HolidayName = ktHolidayName($strDate);

if ($HolidayName != "") {
    echo $HolidayName;
} else {
    echo "";
}
exit();

/*====================
  AFTER ACTIONS
  ====================*/

/*====================
  FUNCTIONS
  ====================*/
