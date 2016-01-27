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
if (isset($_GET['year']) && $_GET['year'] != "") {
    $StartYear = $LastYear = $_GET['year'];
} else {
    $StartYear = $LastYear = date("Y");
}

$holidaylist = HolidayList($StartYear, $LastYear);

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
    //var_dump($FirstDate, $LastDate);
    
    $OneDay = 24 * 60 * 60;
    $HolidayCount = 0;
    $HolidayData = array();
    $workDate = $FirstDate;
    $strDate = "";
    $HolidayName = "";
    
    while ($workDate < $LastDate) {
        //var_dump($workDate);
        $strDate = date("Y/n/j", $workDate);
        $HolidayName = ktHolidayName($strDate);
        if ($HolidayName != "") {
            $HolidayCount++;
            $HolidayData[] = array('name'=>$HolidayName, 'date'=>$strDate);
        }
        $workDate += $OneDay;
    }
    
    return $HolidayData;
}
?><!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>祝日カレンダー</title>
</head>
<body>
    <h1>祝日カレンダー</h1>
    <form method="get">
        <select name="year" id="year">
            <?php for ($i = date("Y"); $i <= 2030; $i++) { ?>
            <option value="<?php echo $i; ?>" <?php if (isset($_GET['year']) && $_GET['year'] == $i) echo 'selected'; ?>><?php echo $i; ?>年</option>
            <?php } ?>
        </select>
        <button type="submit">検索</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>日付</th>
                <th>祝日</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($holidaylist as $val) { ?>
            <tr>
                <td><?php echo $val['date']; ?></td>
                <td><?php echo $val['name']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>