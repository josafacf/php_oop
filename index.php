<?php 
require_once 'class/ar_layer_supertype/Transaction.php';
require_once 'class/ar_layer_supertype/Connection.php';
require_once 'class/ar_layer_supertype/Logger.php';
require_once 'class/ar_layer_supertype/LoggerTXT.php';
//require_once 'class/ar_layer_supertype/LoggerXML.php';
require_once 'class/ar_layer_supertype/Record.php';
require_once 'class/ar_layer_supertype/Produto.php';

try 
{
	Transaction::open('estoque');
	Transaction::setLogger(new LoggerTXT('class/ar_layer_supertype/tmp/log.txt'));
	//Transaction::setLogger(new LoggerXML('class/ar_layer_supertype/tmp/log.xml'));
	Transaction::log('Inserido produto novo');

    $p1 = new Produto;
	$p1->descricao 			= 	'Cerveja Artsanal IPA';
	$p1->estoque 			=	11;
	$p1->preco_custo		=	21;
	$p1->preco_venda		=	3;
	$p1->codigo_barras 		=	'928422535';
	$p1->data_cadastro		= 	date('Y-m-d');
	$p1->origem				=	'N';
	$p1->store();

	Transaction::close();
}
catch (Exception $e)
{
	print $e->getMessage();
}


