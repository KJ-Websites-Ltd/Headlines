<?php

namespace Headline\Model;

class Tag extends Base {

	/**
	 * create or update multiple tags posted
	 */
	public function postMultiple($tags, $typeId, $itemId) {

		if (!empty($tags)) {

			//delete all item links first, this means the tags will be removed if not existing in post
			$this->deleteItem2TagByType($itemId, $typeId);

			$tags = json_decode($tags);
			foreach ($tags AS $t) {
				$this->postSingle($t, $typeId, $itemId);
			}

		}

	}

	/**
	 * delete all item_2_tag links by finding the appropriate tag type and then removing thse
	 */
	public function deleteItem2TagByType($itemId, $typeId) {

		$query = '
			SELECT item_2_tag.id, item_2_tag.item_id, item_2_tag.tag_id
			FROM item_2_tag
			LEFT JOIN tag_2_type ON item_2_tag.tag_id = tag_2_type.tag_id
			WHERE item_2_tag.item_id = :item_id AND tag_2_type.type_id = :type_id
		';

		$params = [
			'item_id' => $itemId,
			'type_id' => $typeId,
		];

		$res = $this->fetchAll($query, $params);

		foreach ($res AS $r) {
			$query = 'DELETE FROM item_2_tag WHERE id = :id';
			$params = [
				'id' => $r['id'],
			];
			$this->postOne($query, $params);
		}

	}

	/**
	 *
	 */
	public function postSingle($tag, $typeId, $itemId) {

		//find if tag exists
		$cTag = $this->getDataByString($tag);

		//if not create it and return
		if (empty($cTag)) {
			$cTag = $this->createTag($tag);
		}

		$this->getContainer()->get('headlineModelType')->postTag2Type($cTag['id'], $typeId);
		$this->postItem2Tag($cTag['id'], $itemId);

	}

	/**
	 * @brief [brief description]
	 * @details [long description]
	 * @return [description]
	 */
	private function getItem2Tag($tagId, $itemId) {

		$query = '
					SELECT tag_id, item_id
					FROM item_2_tag
					WHERE tag_id = :tag_id AND item_id = :item_id
					';

		$params = [
			'tag_id' => $tagId,
			'item_id' => $itemId,
		];

		return $this->fetchOne($query, $params);

	}

	/**
	 *
	 */
	private function postItem2Tag($tagId, $itemId) {

		$res = $this->getItem2Tag($tagId, $itemId);
		if (empty($res)) {
			$query = 'INSERT INTO item_2_tag (item_id, tag_id) VALUES (:item_id, :tag_id)';
			$params = [
				'item_id' => $itemId,
				'tag_id' => $tagId,
			];

			$this->postOne($query, $params);

			$res = $this->getItem2Tag($tagId, $itemId);
		}

		return $res;

	}

	private function createTag($tag) {

		if (!empty($tag)) {
			$query = 'INSERT INTO tag (data) VALUES (:data)';
			$params = [
				'data' => $tag,
			];
	
			$stmt = $this->postOne($query, $params);
			$id = $stmt['id'];
			
			return $this->getDataByString($tag);
		}

		

	}

	public function getDataByString($string) {

		$query = '
			SELECT tag.id, tag.data
			FROM tag
			WHERE tag.data = :tag_data
		';

		$params = [
			'tag_data' => $string,
		];

		return $this->fetchOne($query, $params);

	}

	/**
	 * return tags matching a string
	 */
	public function findDataByString($string) {

		$query = '
			SELECT tag.id, tag.data AS tag, type.data AS type
			FROM item_2_tag
			LEFT JOIN tag ON item_2_tag.tag_id = tag.id
			LEFT JOIN tag_2_type on tag.id = tag_2_type.tag_id
			LEFT JOIN type on tag_2_type.type_id = type.id
			WHERE tag.data LIKE :tag_data
			ORDER BY tag.data
		';

		$params = [
			'tag_data' => '%' . $string . '%',
		];

		return $this->fetchAll($query, $params);


	}

	/**
	 * @brief get an items tags by item id
	 * @details [long description]
	 * @return [description]
	 */
	public function getData($itemId) {

		$query = '
			SELECT tag.id, tag.data AS tag, type.data AS type
			FROM item_2_tag
			LEFT JOIN tag ON item_2_tag.tag_id = tag.id
			LEFT JOIN tag_2_type on tag.id = tag_2_type.tag_id
			LEFT JOIN type on tag_2_type.type_id = type.id
			WHERE item_2_tag.item_id = :item_id
			ORDER BY tag.data
		';

		$params = [
			'item_id' => $itemId,
		];

		return $this->fetchAll($query, $params);


	}

	/**
	 * return all tags by item id and type id
	 */
	public function getDataByItemAndType($itemId, $typeId) {

		$query = '
			SELECT tag.data
			FROM tag
			LEFT JOIN item_2_tag ON tag.id = item_2_tag.tag_id
			LEFT JOIN tag_2_type ON tag.id = tag_2_type.tag_id
			WHERE item_2_tag.item_id = :item_id AND tag_2_type.type_id = :type_id
		';

		$params = [
			'item_id' => $itemId,
			'type_id' => $typeId,
		];

		$res = [];

		foreach ($this->fetchAll($query, $params) AS $d) {
			$res[] = $d['data'];
		}

		return $res;

	}

	/**
	 * return all tags by type
	 */
	public function getDataByType($type) {

		$query = '
			SELECT tag.id, tag.data AS tag, type.data AS type
			FROM item_2_tag
			LEFT JOIN tag ON item_2_tag.tag_id = tag.id
			LEFT JOIN tag_2_type on tag.id = tag_2_type.tag_id
			LEFT JOIN type on tag_2_type.type_id = type.id
			WHERE type.data = :type
			ORDER BY tag.data
		';

		$params = [
			'type' => $type,
		];

		return $this->fetchAll($query, $params);

	}

	/**
	 * @brief get all items assigned to a tag by type
	 * @details [long description]
	 * @return [description]
	 */
	public function getItemCollectionByData($tag, $type) {

		$query = '
			SELECT
			item.id AS id, item.title as title, item.slug as slug, item.updated_at, item.active as active,
			tag.data AS tag,
			type.data AS type
			FROM tag

			LEFT JOIN tag_2_type ON tag.id = tag_2_type.tag_id
			LEFT JOIN type ON tag_2_type.type_id = type.id

			LEFT JOIN item_2_tag on tag.id = item_2_tag.tag_id
			LEFT JOIN item on item_2_tag.item_id = item.id

			WHERE tag.data = :tag_data
			GROUP BY item.id
			HAVING type.data = :type_data
		';

		$params = [
			'tag_data' => $tag,
			'type_data' => $type,
		];

		return $this->fetchAll($query, $params);

	}

	/**
	 * @brief get all items assigned to a tag by type
	 * @details [long description]
	 * @return [description]
	 */
	public function getItemCollection($tag) {

		$query = '
			SELECT
			item.id AS id, item.title as title, item.slug as slug, item.updated_at, item.active as active, item.link as link,
			tag.data AS tag,
			type.data AS type
			FROM tag

			LEFT JOIN tag_2_type ON tag.id = tag_2_type.tag_id
			LEFT JOIN type ON tag_2_type.type_id = type.id

			LEFT JOIN item_2_tag on tag.id = item_2_tag.tag_id
			LEFT JOIN item on item_2_tag.item_id = item.id

			WHERE tag.data = :tag_data
			GROUP BY item.id
			ORDER BY item.updated_at DESC
		';

		$params = [
			'tag_data' => $tag,
		];

		return $this->fetchAll($query, $params);

	}

}
