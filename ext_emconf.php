<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Frontend ViewHelpers by Jens Mittag',
    'description' => 'A custom collection of Typo3 Viewhelpers',
    'category' => 'template',
    'author' => 'Jens Mittag',
    'author_company' => '',
    'author_email' => 'kontakt@jensmittag.de',
    'dependencies' => '',
    'clearCacheOnLoad' => 1,
    'version' => '1.0.0',
    'constraints' => array(
        'depends' => array(
            'php' => '5.5.0-0.0.0',
            'typo3' => '7.4.0-0.0.0',
			'news' => '3.2.1-0.0.0',
			'fluid' => '7.4.0-0.0.0',
			'extbase' => '7.4.0-0.0.0',
        ),
    )
);
