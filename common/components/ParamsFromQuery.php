<?php

namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\models\Filter;
use common\models\Slices;
use common\models\Pages;

class ParamsFromQuery extends BaseObject{

	public $params_filter, $params_api, $listing_url, $seo;

	public function __construct($getQuery, $filter_model, $slices_model) {
		$return = [
			'params_api' => [],
			'params_filter' => []
		];
		$temp = [];
		foreach ($filter_model as $filter_row) {
			if(array_key_exists($filter_row->alias, $getQuery)){
				$queryArr = explode(',', $getQuery[$filter_row->alias]);
				$return['params_filter'][$filter_row->alias] = $queryArr;

				foreach ($filter_row->items as $filter_item) {
					if(in_array($filter_item->value, $queryArr)){
						$base_arr = json_decode($filter_item->api_arr, true);
						$api_arr = [];

						foreach ($base_arr as $value) {
							//echo '<pre>';
							//print_r($value);
							//echo '</pre>';
							//exit;
							if(!is_array($value['value'])) $value['value'] = [$value['value']]; 
							if(isset($return['params_api'][$value['key']])){
								$return['params_api'][$value['key']] = array_merge($return['params_api'][$value['key']], $value['value']);
							}
							else{
								$return['params_api'][$value['key']] = array_merge([], $value['value']);
							}
						}
					}
				}										
			}
		}

		foreach ($return['params_api'] as $key => $value_arr) {
			$this->params_api .= '&'.$key.'=';
			if(is_array($value_arr)){
				foreach ($value_arr as $value) {
					
					$this->params_api .= $value.',';
				}
			}
			else{
				$this->params_api .= $value_arr;
			}	
		}



		if(isset($getQuery['page']) && $getQuery['page'] != 1){
			$page_param = '?page='.$getQuery['page'].'&';
		}
		else{
			if(count($return['params_filter']) > 0){
				$page_param = '?';
			}
			else{
				$page_param = '';
			}			
		}

		foreach ($return['params_filter'] as $key => $value) {
			$temp[$key] = '';
			foreach ($value as $filter_value) {
				$temp[$key] .= $filter_value.',';
			}
			$temp[$key] = rtrim($temp[$key], ',');
		}
		$slice_alias = false;		
		foreach ($slices_model as $key => $value) {
			$temp2 = json_decode($value->params, true);
			if(count(array_merge(array_diff_assoc($temp,$temp2),array_diff_assoc($temp2,$temp))) == 0){
				$slice_alias = $value->alias;
				$this->seo = [
					'h1' => $value->h1,
		            'title' => $value->title,
		            'description' => $value->description,
		            'keywords' => $value->keywords,
		            'text_top' => $value->text_top,
		            'text_bottom' => $value->text_bottom,
		            'img_alt' => $value->img_alt,
				];
			}
		}
		if($slice_alias){
			$this->listing_url = $slice_alias.'/'.$page_param;
		}
		else{
			$this->seo = Pages::find()->where(['name' => 'listing'])->one();
			$this->listing_url = $page_param;
			foreach ($temp as $key => $value) {
				$this->listing_url .= $key.'='.$value;
				$this->listing_url .= '&';
			}
		}

		$this->listing_url = rtrim($this->listing_url, '&');
		$this->listing_url = rtrim($this->listing_url, '?');

		$this->params_filter = $return['params_filter'];
	}

	public static function isSlice($filter){
		$slice_model = Slices::find()->all();
		$slice_alias = false;
		$temp = $filter;
		foreach ($slice_model as $key => $value) {
			$temp2 = json_decode($value->params, true);
			if(count(array_merge(array_diff_assoc($temp,$temp2),array_diff_assoc($temp2,$temp))) == 0){
				$slice_alias = $value->alias;
			}
		}
		return $slice_alias;
	}

}