<?php 
require_once 'class/ar/Produto.php';
require_once 'class/Factory/Connection.php';

try 
{
	$conn = Connection::open('estoque');
	Produto::setConnection($conn);

    $p1 = new Produto;
	$p1->descricao 			= 	'Vinho Brasileiro Tinto Merlot';
	$p1->estoque 			=	10;
	$p1->preco_custo		=	12;
	$p1->preco_venda		=	18;
	$p1->codigo_barras 		=	'1234556778';
	$p1->data_cadastro		= 	date('Y-m-d');
	$p1->origem				=	'N';
	$p1->save();

}
catch (Exception $e)
{
	print $e->getMessage();
}


