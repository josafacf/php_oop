<?php 
require_once 'class/encapsulamento/Transaction.php';
require_once 'class/encapsulamento/Connection.php';
require_once 'class/encapsulamento/Logger.php';
require_once 'class/encapsulamento/LoggerTXT.php';
//require_once 'class/encapsulamento/LoggerXML.php';
require_once 'class/encapsulamento/Record.php';
require_once 'class/encapsulamento/Produto.php';

try 
{
    Transaction::open('estoque');
    Transaction::setLogger(new LoggerTXT('class/encapsulamento/tmp/log_protect.txt'));
    //Transaction::setLogger(new LoggerXML('class/encapsulamento/tmp/log.xml'));
    Transaction::log('Protegendo o acesso a um produto');

    $p1 = Produto::find(22);
    $p1->estoque = 'dois';
    $p1->store();

    Transaction::close();
}
catch (Exception $e)
{
    Transaction::rollback();    
    print $e->getMessage();
}


