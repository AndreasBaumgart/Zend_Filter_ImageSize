<?php

/* -----------------------------------------------------------------------------
 * Environment setup.
 * ---------------------------------------------------------------------------*/
$includePath = array(get_include_path());
$includePath[] = dirname(__FILE__) . '/../';

// If ZendFramework is not in your include path by default then adjust the
// following according to your environment.
$includePath[] = '~/Workspace/ZendFramework/library';

set_include_path(join(PATH_SEPARATOR, $includePath));
