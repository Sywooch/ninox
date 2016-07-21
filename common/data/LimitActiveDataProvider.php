<?php

namespace common\data;

use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\QueryInterface;

/**
 * Created by PhpStorm.
 * User: alone
 * Date: 20.07.16
 * Time: 18:28
 */
class LimitActiveDataProvider extends ActiveDataProvider{

	public $showPages = 1; //Количество показываемых страниц

	/**
	 * @inheritdoc
	 */
	protected function prepareModels()
	{
		if (!$this->query instanceof QueryInterface) {
			throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
		}
		$query = clone $this->query;
		if (($pagination = $this->getPagination()) !== false) {
			$pagination->totalCount = $this->getTotalCount();
			$query->limit($pagination->getLimit() * $this->showPages)
				->offset($pagination->getOffset() - ($pagination->getPageSize() * ($this->showPages - 1)));
		}
		if (($sort = $this->getSort()) !== false) {
			$query->addOrderBy($sort->getOrders());
		}

		return $query->all($this->db);
	}

}