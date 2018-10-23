<?php

namespace Headline\Model;



class Type extends Base {

	/**
	 * @brief get all items assigned to a type id
	 * @details [long description]
	 * @return [description]
	 */
	public function getItemCollection($typeId, $limit=10) {

		$limit = (int) $limit;

		$query = '
			SELECT 
			item.id AS id, item.title as title, item.link as link, item.slug as slug, item.updated_at, item.active as active,
			type.data AS type
			FROM type

			LEFT JOIN item_2_type on type.id = item_2_type.type_id
			LEFT JOIN item on item_2_type.item_id = item.id

			WHERE type.id = :type_id AND item.id > 0
			GROUP BY item.updated_at
			ORDER BY item.updated_at DESC

			LIMIT 0, ' . $limit;

		$params = [
			'type_id' => $typeId
		];
 
		return $this->fetchAll($query, $params);		

	}



	/**
	 * create the join between an item and a type
	 */
	public function postItem2Type($itemId, $typeId) {

		$itemId = (int)$itemId;
		$typeId = (int)$typeId;
		$res = 0;

		if ($itemId > 0 && $typeId > 0) {

			$query = 'INSERT INTO item_2_type (item_id, type_id) VALUES (:item_id, :type_id)';
			$params = [
				'item_id' => $itemId, 
				'type_id' => $typeId
			];

			$stmt = $this->postOne($query, $params);
			$res = $stmt['id'];

		}

		return $res;
		
	}

	/**
	 * @brief check if a tag 2 type link exists
	 * @details [long description]
	 * @return [description]
	 */
	public function getTag2Type($tagId, $typeId) {

		$query = '
				SELECT tag_id, type_id
				FROM tag_2_type
				WHERE tag_id = :tag_id AND type_id = :type_id
				';
		$params = [
			'tag_id' => $tagId,
			'type_id' => $typeId
		];

		return $this->fetchOne($query, $params);

	}

	/**
	 * @brief create the tag 2 type link if not existing
	 * @details [long description]
	 * 
	 * @param d [description]
	 * @param d [description]
	 * @param d [description]
	 * @return [description]
	 */
	public function postTag2Type($tagId, $typeId) {

		//check if exists
		$res = $this->getTag2Type($tagId, $typeId);
		
		if (empty($res)) {
				
			$query = 'INSERT INTO tag_2_type (tag_id, type_id) VALUES (:tag_id, :type_id)';
			$params = [
				'tag_id' => $tagId, 
				'type_id' => $typeId
			];

			$stmt = $this->postOne($query, $params);
			$res = $this->getTag2Type($tagId, $typeId);

		}

		return $res;

	}


}
