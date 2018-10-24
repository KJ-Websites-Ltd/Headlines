<?php

namespace Headline\Model;


class Item extends Base {

	const fields   = 'item.id, item.title, item.slug, item.link, item.author, item.created_at, item.updated_at, item.active, type.data AS type';

	/**
	 * @brief get a single item by id
	 * @details [long description]
	 * @return [description]
	 */
	public function getSingle($id) {

		$query = '
			SELECT '.self::fields.'
			FROM item 
			LEFT JOIN item_2_type ON item.id = item_2_type.item_id
			LEFT JOIN type ON item_2_type.type_id = type.id
			WHERE item.id = :id
			';
		$params = ['id' => $id];

		return $this->fetchOne($query, $params);

		
	}

	
	/**
	 * @brief get a single item by slug and type
	 * @details [long description]
	 * @return [description]
	 */
	public function getSingleBySlug($slug, $type) {

		$query = '
			SELECT '.self::fields.'
			FROM item 
			LEFT JOIN item_2_type ON item.id = item_2_type.item_id
			LEFT JOIN type ON item_2_type.type_id = type.id
			WHERE item.slug = :slug AND type.id = :type_id
			';

		$params = [
			'slug' => $slug,
			'type_id' => $type
		];

		return $this->fetchOne($query, $params);
		
	}

	/**
	 * 
	 */
	public function postSingle($data, $typeId) {

		$id = (int)$data['id'] || 0;
		

		if ($id > 0) {

			//do an update

			$query = 'UPDATE item SET ';
			foreach ($data AS $k => $v) {
				$query .= ' '.$k.'=:'.$k.',';
			}
			$query = rtrim($query, ',');
			$query .= ' WHERE id=:id';

			$this->postOne($query, $data);

		} else {
			// do an insert

			//create slug
			
			$data['slug'] = $this->getSlugify()->slugify($data['title']);
			

			if (!$this->checkDuplicateSlug($data)) {

			unset($data['id']);
			
			$data['created_at'] = time();
			$data['updated_at'] = time();

			$query = 'INSERT INTO item (';
			foreach ($data AS $k => $v) {
				$query .= $k.',';
			}
			$query = rtrim($query, ',');
			$query .= ') VALUES (';

			foreach ($data AS $k => $v) {
				$query .= ':' . $k . ',';
			}
			$query = rtrim($query, ',');
			$query .= ')';

			$stmt = $this->postOne($query, $data);
			$id = $stmt['id'];


			}

			

		}
		

		return $id;

	}

	/**
	 * @brief [brief description]
	 * @details [long description]
	 * @return [description]
	 */
	private function checkDuplicateSlug($data) {

	
		$query = 'SELECT COUNT(id) AS count FROM item WHERE slug LIKE :slug';
		$params = [
			'slug' => $data['slug'] . '%'
		];
		$res = true;

		$data = $this->fetchOne($query, $params)['count'];

		if ($data > 0) {

			$res = false;

			//do not create duplicates

			//$data['slug'] = $data['slug'] .'_' . ($res + 1);
		}

		return $data;

	}




	

	


	


	

}