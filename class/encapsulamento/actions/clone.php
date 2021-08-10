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

    $p1 = Produto::find(2);
    $p2 = clone $p1;
    $p2->descricao .=' (clonado2)';
    $p2->store();

	Transaction::close();
}
catch (Exception $e)
{
	Transaction::rollback();	
	print $e->getMessage();
}


