<?php

require_once 'autoload.php';
$feefo = new \aw\feefo\Feefo('www.originalcottages.co.uk/suffolksecrets', 'erm2rude');

echo 'Feefo Integration Test';

$feefo->setOrderRef('ORDERREF')
    ->setName('NAME')
    ->setEmail('email@email.com')
    ->setDescription('DESCRIPTION')
    ->setServiceRating('+')
    ->setServiceComment('COMMENT')
    ->setCategory('CATEGORY');
    
echo $feefo->getCommentUrl();