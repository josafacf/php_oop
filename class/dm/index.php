<?php 
require_once 'class/dm/Produto.php';
require_once 'class/dm/Venda.php';
require_once 'class/dm/VendaMapper.php';

try 
{

	$p1 = new Produto;
	$p1->id 		= 1;
	$p1->preco 		= 12;

	$p2 = new Produto;
	$p2->id 		= 2;
	$p2->preco      = 14;

	$venda = new Venda;

	// Adiciona alguns produtos
	$venda->addItem(10, $p1);
	$venda->addItem(20, $p2);

	$servername = "localhost";
	$username = "root";
	$password = "";

	$conn = new PDO("mysql:host=$servername;dbname=estoque", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    VendaMapper::setConnection($conn);

    // Salva venda
    VendaMapper::save($venda);
}
catch (Exception $e)
{
	print $e->getMessage();
}


