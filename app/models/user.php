<?php
namespace App\models;
use PDO;
use Aura\SqlQuery\QueryFactory;

class User {

	private $queryFactory, $pdo;

	public function _construct(QueryFactory $queryFactory, PDO $pdo){
		$this->pdo = $pdo;
		$this->queryFactory = $queryFactory;
	}

	public function getAllUsers(){
		$select = $this->queryFactory->newSelect();
		$select->cols(['*'])
			   ->from('users');
		$sth = $this->pdo->prepare($select->getStatement());
		$sth->execute($select->getBindValues());
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

}