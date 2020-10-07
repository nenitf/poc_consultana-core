<?php

// Script necessáario para colorir terminal do Windows com o composer

unset($argv[0]);
system(join(DIRECTORY_SEPARATOR, array('vendor', 'bin', 'phpunit')) . ' --color=always ' . join(' ', $argv));

