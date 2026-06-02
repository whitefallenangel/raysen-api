<?php

namespace app\controllers;

use app\kernel\common\controller\AppController;
use app\models\Building;
use app\models\LandPlot;
use app\models\Locality;
use app\models\Company\Company;
use app\enum\GeolocationRules\DistrictTypeEnum;
use app\enum\GeolocationRules\MoscowRegionEnum;
use app\enum\Building\BuildingClassEnum;
use app\enum\Building\BuildingLineEnum;
use app\enum\Building\BuildingTypeEnum;
use app\enum\Offer\OfferSigningContractEnum;
use app\enum\Offer\OfferTypeEnum;
use yii\web\NotFoundHttpException;

class ComplexNewController extends AppController
{
    /**
     * @param int $id
     * @return Complex
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        return $this->findModel($id);
    }

    /**
     * @param int $id
     * @return Building or LandPlot
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id)
    {
		$districtTypes = DistrictTypeEnum::labels();
		$moscowRegionEnum = MoscowRegionEnum::labels();
		$buildingClassEnum = BuildingClassEnum::labels();
		$buildingLineEnum = BuildingLineEnum::labels();
		$buildingTypeEnum = BuildingTypeEnum::labels();
		$offerSigningContractEnum = OfferSigningContractEnum::labels();
		// Try to get Building
		$model = Building::find()
			->with('location.region')
			->with('location.district')
			->with('location.metro')
			->with('offer')
			->with('offer.buildingObject')
			->with('communications')
			->with('parking')
			->with('entrance')
			->with('liftingMechanisms')
			->with('security')
			->with('railway')
			->where(['building.id' => $id])
			->asArray()
			->one();
		if ($model !== null) {
			//$model['location']['location_district_type_title'] = $districtTypes;
			$model['object_id'] = $id;
			$model['location']['location_district_type_title'] = !empty($model['location']['location_district_type']) ? $districtTypes[$model['location']['location_district_type']] : null;
			// Получение данных по коммуникациям
			foreach($model['communications'] ?? [] as $key => $communication) {
				$model[$key] = $model['communications'][$key] ?? null;
			}
			// Получение данных по безопастности
			if (!empty($model['security']) && !empty($model['security']['security_object_attributes'])) {
				$securityAttributes = json_decode($model['security']['security_object_attributes'], true);
				foreach($securityAttributes ?? [] as $key => $arrt) {
					$model['security_' . $key] = $securityAttributes[$key] ?? null;
				}
			}
			// Получение данных по парковке
			if (!empty($model['parking']) && !empty($model['parking']['parking_object_attributes'])) {
				$parkingAttributes = json_decode($model['parking']['parking_object_attributes'], true);
				foreach($parkingAttributes ?? [] as $key => $arrt) {
					$model['parking_' . $key . '_is_exist'] = $arrt['is_exist'] ?? null;
				}
			}
			// Получение данных по въезду
			if (!empty($model['entrance']) && !empty($model['entrance']['parking_object_attributes'])) {
				$entranceAttributes = json_decode($model['entrance']['parking_object_attributes'], true);
				foreach($entranceAttributes ?? [] as $key => $arrt) {
					// $model['entrance_' . $key . '_is_exist'] = $arrt['is_exist'] ?? null;
				}
			}
			if (!empty($model['location']['location_locality'])) {
				//$model['location']['locality_data'] = Locality::findOne($model['location']['location_locality']);
			}
			// Получение данных по ж/д ветке
			if (!empty($model['railway'])) {
				$railwayAttributes = json_decode($model['railway']['railway_object_attributes'], true);
				foreach($railwayAttributes ?? [] as $key => $arrt) {
					$model['railway_' . $key] = $arrt;
				}
			}
			// Получение данных по подъемным механизмам
			if (!empty($model['liftingMechanisms'])) {
				foreach($model['liftingMechanisms'] as $key => $mechanism) {
					$mechanismAttributes = json_decode($mechanism['mechanism_object_attributes'], true);
					foreach($mechanismAttributes ?? [] as $key2 => $arrt) {
						$model['mechanism_' . $mechanism['mechanism_object_type']][$mechanism['id']]['mechanisms_' . $key2] = $arrt;
					}
				}
			}

			// Получение данных про управляющую компанию
			if (!empty($model['building_management_company'])) {
				$model['management_company_data'] = Company::findOne($model['building_management_company']);
			}
			// Получение данных о владельцах
			$model['owners_data'] = [];
			if (!empty($model['building_owner'])) {
				if ($ownersList = json_decode($model['building_owner'])) {
					$model['owners_data'] = Company::findAll($ownersList);
					/* $model['owners_data'] = Company::find()
						->where(['id' => $ownersList])
						->with('activeRequests')
						->all(); */
					//foreach ($model['owners_data'] as &$owner) {
					//	$owner['extra_fields'] = $owner->extraFields();
					//}
				} else {
					$model['owners_data'] = Company::findAll($model['building_owner']);
				}
			}
			// json_decode $model['location']['location_direction_mo']
			$model['location']['location_direction_mo_title'] = !empty($model['location']['location_direction_mo']) ? $moscowRegionEnum[$model['location']['location_direction_mo']] : null;
			$model['building_class'] = !empty($model['building_class']) ? ($buildingClassEnum[$model['building_class']] ?? null) : null;
			$model['building_line'] = !empty($model['building_line']) ? ($buildingLineEnum[$model['building_line']] ?? null) : null;
			$model['building_type'] = !empty($model['building_type']) ? ($buildingTypeEnum[$model['building_type']] ?? null) : null;

			$model['offerTypeEnum'] = OfferTypeEnum::labels();
			// Получение данных по минимальномым и максимальномым данным предложения
			$model['offer_obj_storage_area_min'] = 0;
			$model['offer_obj_storage_area_max'] = 0;
			$model['offer_obj_office_area_min'] = 0;
			$model['offer_obj_office_area_max'] = 0;
			$model['offer_obj_retail_area_min'] = 0;
			$model['offer_obj_retail_area_max'] = 0;
			$model['offer_obj_technical_area_min'] = 0;
			$model['offer_obj_technical_area_max'] = 0;
			$model['offer_obj_public_area_min'] = 0;
			$model['offer_obj_public_area_max'] = 0;
			$model['offer_obj_height_min'] = 0;
			$model['offer_obj_height_max'] = 0;

			$model['offer_rent_area_min'] = 0;
			$model['offer_rent_area_max'] = 0;
			$model['offer_sale_area_min'] = 0;
			$model['offer_sale_area_max'] = 0;
			$model['offer_rent_price_min'] = 0;
			$model['offer_sale_price_min'] = 0;
			if (!empty($model['offer'])) {
				foreach($model['offer'] as &$offer) {
					$offer['offer_type_title'] = !empty($offer['offer_type']) ? $model['offerTypeEnum'][$offer['offer_type']] : null;
					$offer['offer_signing_contract_title'] = !empty($model['offer_signing_contract']) ? $offerSigningContractEnum[$model['offer_signing_contract']] : 'Не подписан';

					if (!empty($offer['buildingObject'])) {
						foreach($offer['buildingObject'] as &$object) {
							$photos = json_decode($object['b_obj_photo'] ?? '', true);
							$object['b_obj_photo_first'] = !empty($photos) ? reset($photos) : '';

							$model['offer_obj_storage_area_min'] = !empty($model['offer_obj_storage_area_min']) && !empty($object['b_obj_storage_square_min']) ? min($model['offer_obj_storage_area_min'], $object['b_obj_storage_square_min']) : max($model['offer_obj_storage_area_min'], $object['b_obj_storage_square_min']);
							$model['offer_obj_storage_area_max'] = max($model['offer_obj_storage_area_max'], $object['b_obj_storage_square_max']);

							$model['offer_obj_office_area_min'] = !empty($model['offer_obj_office_area_min']) && !empty($object['b_obj_office_square_min']) ? min($model['offer_obj_office_area_min'], $object['b_obj_office_square_min']) : max($model['offer_obj_office_area_min'], $object['b_obj_office_square_min']);
							$model['offer_obj_office_area_max'] = max($model['offer_obj_office_area_max'], $object['b_obj_office_square_max']);

							$model['offer_obj_retail_area_min'] = !empty($model['offer_obj_retail_area_min']) && !empty($object['b_obj_retail_square_min']) ? min($model['offer_obj_retail_area_min'], $object['b_obj_retail_square_min']) : max($model['offer_obj_retail_area_min'], $object['b_obj_retail_square_min']);
							$model['offer_obj_retail_area_max'] = max($model['offer_obj_retail_area_max'], $object['b_obj_retail_square_max']);

							$model['offer_obj_technical_area_min'] = !empty($model['offer_obj_technical_area_min']) && !empty($object['b_obj_technical_square_min']) ? min($model['offer_obj_technical_area_min'], $object['b_obj_technical_square_min']) : max($model['offer_obj_technical_area_min'], $object['b_obj_technical_square_min']);
							$model['offer_obj_technical_area_max'] = max($model['offer_obj_technical_area_max'], $object['b_obj_technical_square_max']);

							$model['offer_obj_public_area_min'] = !empty($model['offer_obj_public_area_min']) && !empty($object['b_obj_public_square_min']) ? min($model['offer_obj_public_area_min'], $object['b_obj_public_square_min']) : max($model['offer_obj_public_area_min'], $object['b_obj_public_square_min']);
							$model['offer_obj_public_area_max'] = max($model['offer_obj_public_area_max'], $object['b_obj_public_square_max']);

							$model['offer_obj_height_min'] = !empty($model['offer_obj_height_min']) && !empty($object['b_obj_ceiling_height_min']) ? min($model['offer_obj_height_min'], $object['b_obj_ceiling_height_min']) : max($model['offer_obj_height_min'], $object['b_obj_ceiling_height_min']);
							$model['offer_obj_height_max'] = max($model['offer_obj_height_max'], $object['b_obj_ceiling_height_max']);
						}
					}
					$offer['offer_min_area'] = $model['offer_obj_storage_area_min'] + $model['offer_obj_office_area_min'] + $model['offer_obj_retail_area_min'] + $model['offer_obj_technical_area_min'] + $model['offer_obj_public_area_min'];
					$offer['offer_max_area'] = $model['offer_obj_storage_area_max'] + $model['offer_obj_office_area_max'] + $model['offer_obj_retail_area_max'] + $model['offer_obj_technical_area_max'] + $model['offer_obj_public_area_min'];

					if ($offer['offer_type'] == OfferTypeEnum::RENT) {
						$model['offer_rent_area_min'] = !empty($model['offer_rent_area_min']) && !empty($offer['offer_min_area']) ? min($model['offer_rent_area_min'], $offer['offer_min_area']) : max($model['offer_rent_area_min'], $offer['offer_min_area']);

						$model['offer_rent_area_max'] = max($model['offer_rent_area_max'], $offer['offer_max_area']);

						if (!empty($offer['offer_full_price_min'])) {
							$model['offer_rent_price_min'] = !empty($model['offer_rent_price_min']) ? min($model['offer_rent_price_min'], $offer['offer_full_price_min']) : max($model['offer_rent_price_min'], $offer['offer_full_price_min']);
						} elseif (!empty($model['offer_office_price_min']) || !empty($offer['offer_technical_price_min'])) {
							$sumPrice = $model['offer_office_price_min'] + $offer['offer_technical_price_min'];

							$model['offer_rent_price_min'] = !empty($model['offer_rent_price_min']) ? min($model['offer_rent_price_min'], $sumPrice) : max($model['offer_rent_price_min'], $sumPrice);							
						}
					} elseif ($offer['offer_type'] == OfferTypeEnum::SALE) {
						$model['offer_sale_area_min'] = !empty($model['offer_sale_area_min']) && !empty($offer['offer_min_area']) ? min($model['offer_sale_area_min'], $offer['offer_min_area']) : max($model['offer_sale_area_min'], $offer['offer_min_area']);
						$model['offer_sale_area_max'] = max($model['offer_sale_area_max'], $offer['offer_max_area']);

						if (!empty($offer['offer_full_price_min'])) {
							$model['offer_sale_price_min'] = !empty($model['offer_sale_price_min']) ? min($model['offer_sale_price_min'], $offer['offer_full_price_min']) : max($model['offer_sale_price_min'], $offer['offer_full_price_min']);
						} elseif (!empty($model['offer_office_price_min']) || !empty($offer['offer_technical_price_min'])) {
							$sumPrice = $model['offer_office_price_min'] + $offer['offer_technical_price_min'];

							$model['offer_sale_price_min'] = !empty($model['offer_sale_price_min']) ? min($model['offer_sale_price_min'], $sumPrice) : max($model['offer_sale_price_min'], $sumPrice);
						}
					}
				}

			}
			unset($model['communications']);
			unset($model['security']);
			return $model;
		}
		// Try to get LandPlot if no Building 
		$model = LandPlot::find()
			->with('location.region')
			->with('location.district')
			->with('location.metro')
			->with('offer')
			->with('landPlotObject')
			->with('communications')
			->with('security')
			->with('railway')
			->where(['land_plot.id' => $id])
			->asArray()
			->one();
		if ($model !== null) {
			$model['object_id'] = $id;

			return $model;
		}

        throw new NotFoundHttpException('Object not found!');
    }
}
