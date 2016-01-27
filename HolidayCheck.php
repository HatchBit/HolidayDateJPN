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
define('MONDAY', 1);
define('TUESDAY', 2);
define('WEDNESDAY', 3);

define('cstImplementTheLawOfHoliday', strtotime("1948/7/20"));  // 祝日法施行
define('cstAkihitoKekkon', strtotime("1959/4/10"));             // 明仁親王の結婚の儀
define('cstShowaTaiso', strtotime("1989/2/24"));                // 昭和天皇大喪の礼
define('cstNorihitoKekkon', strtotime("1993/6/9"));             // 徳仁親王の結婚の儀
define('cstSokuireiseiden', strtotime("1990/11/12"));           // 即位礼正殿の儀
define('cstImplementHoliday', strtotime("1973/4/12"));          // 振替休日施行

/*====================
  BEFORE ACTIONS
  ====================*/

/*====================
  MAIN ACTIONS
  ====================*/

/*====================
  AFTER ACTIONS
  ====================*/

/*====================
  FUNCTIONS
  ====================*/

// [$prmDate]には "yyyy/m/d"形式の日付文字列を渡す
function ktHolidayName($prmDate) {
    $MyDate = strtotime($prmDate);
    $HolidayName = prvHolidayChk($MyDate);
    $YesterDay = "";
    $HolidayName_ret = "";
    
    if ($HolidayName == "") {
        if (date("N", $MyDate) == MONDAY) {
            // 月曜以外は振替休日判定不要
            // 5/6(火,水)の判定はprvHolidayChkで処理済
            // 5/6(月)はここで判定する
            if ($MyDate >= cstImplementHoliday) {
                $YesterDay = mktime(0,0,0,date("n",$MyDate),date("j",$MyDate),date("Y",$MyDate));
                $HolidayName = prvHolidayChk($YesterDay);
                if ($HolidayName != "") {
                    $HolidayName_ret = "振替休日";
                } else {
                    $HolidayName_ret = "";
                }
            } else {
                $HolidayName_ret = "";
            }
        } else {
            $HolidayName_ret = "";
        }
    } else {
        $HolidayName_ret = $HolidayName;
    }
    
    return $HolidayName_ret;
}


function prvHolidayChk($MyDate) {
    $MyYear = date("Y", $MyDate);
    $MyMonth = date("n", $MyDate);    // MyMonth:1 - 12
    $MyDay = date("j", $MyDate);
    $NumberOfWeek = "";
    $MyAutumnEquinox = "";
    $result = "";
    
    if ($MyDate < cstImplementTheLawOfHoliday) {
        return "";// 祝日法施行(1948/7/20)以前
    }
    
    switch ($MyMonth) {
        case 1:
            if ($MyDay == 1) {
                $result = "元旦";
            } else {
                if ($MyYear >= 2000) {
                    $NumberOfWeek = floor(($MyDay - 1) / 7) + 1;
                    if (($NumberOfWeek == 2) && (date("N", $MyDate) == MONDAY)) {
                        $result = "成人の日";
                    }
                } else {
                    if ($MyDay == 15) {
                        $result = "成人の日";
                    }
                }
            }
            break;
        case 2:
            if ($MyDay == 11) {
                if ($MyYear >= 1967) {
                    $result = "建国記念の日";
                }
            } else {
                if ($MyDate == cstShowaTaiso) {
                    $result = "昭和天皇の大喪の礼";
                }
            }
            break;
        case 3:
            if ($MyDay == prvDayOfSpringEquinox($MyYear)) {
                $result = "春分の日";
            }
            break;
        case 4:
            if ($MyDay == 29) {
                if ($MyYear >= 2007) {
                    $result = "昭和の日";
                } else {
                    if ($MyYear >= 1989) {
                        $result = "みどりの日";
                    } else {
                        $result = "天皇誕生日";
                    }
                }
            } else {
                if ($MyDate == cstAkihitoKekkon) {
                    $result = "皇太子明仁親王の結婚の儀";
                }
            }
            break;
        case 5:
            switch ($MyDay) {
                case 3:
                    $result = "憲法記念日";
                    break;
                case 4:
                    if ($MyYear >= 2007) {
                        $result = "みどりの日";
                    } else {
                        if ($MyYear >= 1986) {
                            if (date("N", $MyDate) > MONDAY) {
                                $result = "国民の休日";
                            }
                        }
                    }
                    break;
                case 5:
                    $result = "こどもの日";
                    break;
                case 6:
                    if ($MyYear >= 2007) {
                        if ((date("N", $MyDate) == TUESDAY) || (date("N", $MyDate) == WEDNESDAY)) {
                            $result = "振替休日"; // [5/3,5/4が日曜]ケースのみ、ここで判定
                        }
                    }
                    break;
            }
            break;
        case 6:
            if ($MyDate == cstNorihitoKekkon) {
                $result = "皇太子徳仁親王の結婚の儀";
            }
            break;
        case 7:
            if ($MyYear >= 2003) {
                $NumberOfWeek = floor(($MyDay - 1) / 7) + 1;
                if ($NumberOfWeek == 3 && date("N", $MyDate) == MONDAY) {
                    $result = "海の日";
                }
            } else {
                if ($MyYear >= 1996) {
                    if ($MyDay == 20) {
                        $result = "海の日";
                    }
                }
            }
            break;
        case 8:
            if ($MyDay == 11) {
                if ($MyYear >= 2006) {
                    $result = "山の日";
                }
            }
            break;
        case 9:
            //第３月曜日(15〜21)と秋分日(22〜24)が重なる事はない
            $MyAutumnEquinox = prvDayOfAutumnEquinox($MyYear);
            if ($MyDay == $MyAutumnEquinox) {// 1948〜2150以外は[99]が返るので､必ず≠になる
                $result = "秋分の日";
            } else {
                if ($MyYear >= 2003) {
                    $NumberOfWeek = floor(($MyDay - 1) / 7) + 1;
                    if ($NumberOfWeek == 3 && date("N", $MyDate) == MONDAY) {
                        $result = "敬老の日";
                    } else {
                        if (date("N", $MyDate) == TUESDAY) {
                            if ($MyDay == ($MyAutumnEquinox - 1)) {
                                $result = "国民の休日";
                            }
                        }
                    }
                } else {
                    if ($MyYear >= 1996) {
                        if ($MyDay == 15) {
                            $result = "敬老の日";
                        }
                    }
                }
            }
            break;
        case 10:
            if ($MyYear >= 2000) {
                $NumberOfWeek = floor(($MyDay - 1) / 7) + 1;
                if ($NumberOfWeek == 2 && date("N", $MyDate) == MONDAY) {
                    $result = "体育の日";
                }
            } elseif ($MyYear >= 1966) {
                if ($MyDay == 10) {
                    $result = "体育の日";
                }
            }
            break;
        case 11:
            if ($MyDay == 3) {
                $result = "文化の日";
            } elseif ($MyDay == 23) {
                $result = "勤労感謝の日";
            } elseif ($MyDate == cstSokuireiseiden) {
                $result = "即位礼正殿の儀";
            }
            break;
        case 12:
            if ($MyDay == 23) {
                if ($MyYear >= 1989) {
                    $result = "天皇誕生日";
                }
            }
            break;
    }
    return $result;
}

//===================================================================
// 春分/秋分日の略算式は
// 『海上保安庁水路部 暦計算研究会編 新こよみ便利帳』
// で紹介されている式です。
function prvDayOfSpringEquinox($MyYear) {
    $SpringEquinox_ret == 99;
    if ($MyYear <= 1947) {
        $SpringEquinox_ret = 99;//祝日法施行前
    } else {
        $myy = $MyYear - 1980;
        if ($MyYear <= 1979) {
            $SpringEquinox_ret = floor(20.8357 + (0.242194 * $myy) - floor($myy / 4));
        } elseif ($MyYear <= 2099) {
            $SpringEquinox_ret = floor(20.8431 + (0.242194 * $myy) - floor($myy / 4));
        } elseif ($MyYear <= 2150) {
            $SpringEquinox_ret = floor(21.851 + (0.242194 * $myy) - floor($myy / 4));
        } else {
            $SpringEquinox_ret = 99;//2151年以降は略算式が無いので不明
        }
    }
    return $SpringEquinox_ret;
}
//=====================================================================
function prvDayOfAutumnEquinox($MyYear) {
    $AutumnEquinox_ret = 99;
    if ($MyYear <= 1947) {
        $AutumnEquinox_ret = 99;//祝日法施行前
    } else {
        $myy = $MyYear - 1980;
        if ($MyYear <= 1979) {
            $AutumnEquinox_ret = floor(23.2588 + (0.242194 * $myy) - floor($myy / 4));
        } elseif ($MyYear <= 2099) {
            $AutumnEquinox_ret = floor(23.2488 + (0.242194 * $myy) - floor($myy / 4));
        } elseif ($MyYear <= 2150) {
            $AutumnEquinox_ret = floor(24.2488 + (0.242194 * $myy) - floor($myy / 4));
        } else {
            $AutumnEquinox_ret = 99;//2151年以降は略算式が無いので不明
        }
    }
    return $AutumnEquinox_ret;
}


