<?php

namespace frontend\components;

use Yii;

class Declension {

	public static function end_restaurants($num) {
		$ost=$num%10;
		$ost100 = $num%100;
		if (($ost100<10 || $ost100>20) && $ost!=0) {
			switch ($ost) {
				case 1:	$end='';	break;
				case 2:
				case 3:
				case 4: $end='а'; break;
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:	$end='ов'; break;
			}
		} else $end='ов';
		return $end;
	}

	public static function end_rooms($num) {
		$ost=$num%10;
		$ost100 = $num%100;
		if (($ost100<10 || $ost100>20) && $ost!=0) {
			switch ($ost) {
				case 1:	$end='';	break;
				case 2:
				case 3:
				case 4: $end='а'; break;
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:	$end='ов'; break;
			}
		} else $end='ов';
		return $end;
	}

	/**
     * Функция возвращает окончание для множественного числа слова на основании числа и массива окончаний
     * @param  $number Integer Число на основе которого нужно сформировать окончание
     * @param  $endingsArray  Array Массив слов или окончаний для чисел (1, 4, 5),
     *         например array('яблоко', 'яблока', 'яблок')
     * @return String
     */
    public static function get_num_ending($number, $endingArray)
    {
        $number = $number % 100;
        if ($number>=11 && $number<=19) {
            $ending=$endingArray[2];
        } else {
            $i = $number % 10;
            switch ($i)
            {
                case (1): $ending = $endingArray[0]; break;
                case (2):
                case (3):
                case (4): $ending = $endingArray[1]; break;
                default: $ending=$endingArray[2];
            }
        }
        return $ending;
    }

	public static function get($count, $part, $withCount = false)
	{
		$parts = [
			'шат' => ['ер','ра','ров'],
			'зал' => ['','а','ов'],
			'площад' => ['ка','ки','ок'],
			'площ' => ['адку','адки','адок'],
			'отел' => ['ь','я','ей'],
			'лофт' => ['','а','ов'],
			'баз' => ['а','ы',''],
			'саун' => ['а','ы',''],
			'бан' => ['я','и','ь'],
			'бар' => ['','а','ов'],
			'веранд' => ['а','ы',''],
			'террас' => ['а','ы',''],
			'коттедж' => ['','а','ей'],
			'ресторан' => ['','а','ов'],
			'кафе' => ['','',''],
			'мест' => ['о', 'а', ''],
			'детск' => ['ая площадка', 'их площадки', 'их площадок'],
			'Найден' => ['а', 'о', 'о'],
			'банкетны' => ['й зал', 'х зала', 'х залов'],
			'гостиниц' => ['а', 'ы', ''],
		];
		if(empty($parts[$part])) {
			return ($withCount ? $count . ' ' : '') . 'мест' . self::get_num_ending($count, $parts['мест']);
		}
		return ($withCount ? $count . ' ' : '') . $part . self::get_num_ending($count, $parts[$part]);
	}

    public static function csrfToken(){
    	return Yii::$app->request->getCsrfToken();
    }

    /**
     * Функция возвращает ссылку на изображение, подменяя no_image на картинку ДР
     * @param  $img_src String Ссылка на изображение
     * @return String
     */
    public static function get_image_src($img_src)
    {
        return strrpos($img_src, 'no_photo.png') === false ? $img_src : '/img/no_photo_cover.png';
    }

	public static function dateWithRusMonth($unixstamp)
    {
        $d = date("j", $unixstamp);
        $m = date("m", $unixstamp);
        $y = date("Y", $unixstamp);
        $months = [
            '01' => 'января',
            '02' => 'февраля',
            '03' => 'марта',
            '04' => 'апреля',
            '05' => 'мая',
            '06' => 'июня',
            '07' => 'июля',
            '08' => 'августа',
            '09' => 'сентября',
            '10' => 'октября',
            '11' => 'ноября',
            '12' => 'декабря'
        ];
        return $d . ' ' . (isset($months[$m]) ? $months[$m] : '') . ' ' . $y;
    }
}