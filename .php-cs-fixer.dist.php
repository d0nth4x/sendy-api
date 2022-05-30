<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PhpCsFixer' => true,
    'no_superfluous_phpdoc_tags' => false,
])
    ->setFinder($finder)
;
