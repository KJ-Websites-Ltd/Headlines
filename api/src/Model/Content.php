<?php

namespace Headline\Model;

class Content extends Base {

	/**
	 * @brief get some content for an item based on the item id and what tags are assigned
	 * @details [long description]
	 * @return [description]
	 */
	public function getData($itemId, $typeId) {

		$query = '
			SELECT content.data
			FROM item_2_content
			LEFT JOIN content ON item_2_content.content_id = content.id
			WHERE item_2_content.item_id = :item_id AND content.type_id = :type_id
			ORDER BY item_2_content.id
		';

		$params = [
			'item_id' => $itemId,
			'type_id' => $typeId,
		];

		return $this->fetchOne($query, $params)['data'];

	}

	public function findContent($itemId, $typeId) {

		$query = '
			SELECT content.id
			FROM item_2_content
			LEFT JOIN content ON item_2_content.content_id = content.id
			WHERE item_2_content.item_id = :item_id AND content.type_id = :type_id
			';
		$params = [
			'item_id' => $itemId,
			'type_id' => $typeId,
		];

		return $this->fetchOne($query, $params)['id'];

	}

	public function postSingle($data, $typeId, $itemId) {

		$id = $this->findContent($itemId, $typeId);

		if ($id > 0) {

			//do an update
			$query = 'UPDATE content
				SET data = :data
				WHERE id = :id
				';
			$params = ['data' => $data, 'id' => $id];
			$this->postOne($query, $params);

		} else {
			
			//do an insert into the content table
			$query = 'INSERT INTO content (data, type_id) VALUES (:data, :type_id)';
			$params = [
				'data' => $data,
				'type_id' => $typeId,
			];

			$res = $this->postOne($query, $params);
			$id = $res['id'];

			//now link the item_2_content
			$query = 'INSERT INTO item_2_content (item_id, content_id) VALUES (:item_id, :content_id)';
			$params = [
				'item_id' => $itemId,
				'content_id' => $id,
			];
			$this->postOne($query, $params);			
		
		}

		return $id;

	}

}
