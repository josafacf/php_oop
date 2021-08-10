<?php 
require_once 'class/transacoes/Produto.php';
require_once 'class/transacoes/Connection.php';
require_once 'class/transacoes/Transaction.php';

try 
{

	Transaction::open('estoque');

    $p1 = new Produto;
	$p1->descricao 			= 	'Chocolate Amargo';
	$p1->estoque 			=	80;
	$p1->preco_custo		=	4;
	$p1->preco_venda		=	7;
	$p1->codigo_barras 		=	'574563534534';
	$p1->data_cadastro		= 	date('Y-m-d');
	$p1->origem				=	'N';
	$p1->save();

	Transaction::close();
}
catch (Exception $e)
{
	print $e->getMessage();
}


