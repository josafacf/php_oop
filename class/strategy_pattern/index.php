<?php 
require_once 'class/strategy_pattern/Produto.php';
require_once 'class/strategy_pattern/Connection.php';
require_once 'class/strategy_pattern/Transaction.php';
require_once 'class/strategy_pattern/Logger.php';
require_once 'class/strategy_pattern/LoggerTXT.php';
//require_once 'class/strategy_pattern/LoggerXML.php';

try 
{
	Transaction::open('estoque');
	Transaction::setLogger(new LoggerTXT('class/strategy_pattern/tmp/log.txt'));
	//Transaction::setLogger(new LoggerXML('class/strategy_pattern/tmp/log.xml'));
	Transaction::log('Inserido produto novo');

    $p1 = new Produto;
	$p1->descricao 			= 	'Chocolate Amargo2';
	$p1->estoque 			=	85;
	$p1->preco_custo		=	41;
	$p1->preco_venda		=	3;
	$p1->codigo_barras 		=	'223454565';
	$p1->data_cadastro		= 	date('Y-m-d');
	$p1->origem				=	'N';
	$p1->save();

	Transaction::close();
}
catch (Exception $e)
{
	print $e->getMessage();
}


