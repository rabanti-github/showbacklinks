<?php

if (function_exists('wfLoadExtension')) {
    wfLoadExtension('ShowBackLinks');

    $dir = dirname(__FILE__) . '/';

    // Path to I18N
    $wgMessagesDirs['ShowBackLinks'] = __DIR__ . '/i18n';

    return;
} else {
    die('This version of the ShowBackLinks extension requires MediaWiki 1.25+');
}
