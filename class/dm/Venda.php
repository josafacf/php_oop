<?php 
class Venda 
{
	private $id;
	private $items;

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function addItem($quantidade, Produto $produto)
	{
		$this->items[] = array($quantidade, $produto);
	}

	public function getItems()
	{
		return $this->items;
	}

}