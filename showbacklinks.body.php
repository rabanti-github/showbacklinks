<?php

class ShowBackLinksHooks
{

    public static function onSkinAfterContent(&$data, Skin $skin)
    {
        global $wgOut, $wgTitle;
        $tMain = Title::newFromText(wfMessage("mainpage")->text());




        $linksTitlePrefix = "== " . wfMessage("whatlinkshere")->text(); // . $collapseControl . "";
        $collapseControl = "<span title=\"" . wfMessage('showbacklinks-toggle-text')->text() . "\" alt=\"showbacklinksToogleCollapse['showbacklinks-arrow', 'showbacklinks-container']\" id=\"showbacklinks-arrow\" class=\"showbacklinks-arrow-default\" id=\"backlink-colapse-trigger\">&#x25BC;</span> ";
        $linksTitleSuffix = " ==\n<div id=\"showbacklinks-container\">\n";

        $text = "";
        $links = $wgTitle->getLinksTo();
        usort($links, function ($a, $b) {
            strcmp($a->getText(), $b->getText());
        });

        foreach ($links as $t) {
            if ($t == $wgTitle || $t->getText() == $tMain || !$t->exists() || ($t->getNamespace() !== NS_MAIN)) {
                continue;
            }
            if ($t->isRedirect()) {
                $text .= "* [[" . $t->getText() . "]] (" . wfMessage('showbacklinks-redirect')->text() . ")\n";
                foreach ($t->getLinksTo() as $st) {
                    if ($st == $wgTitle || $st->getText() == $tMain || !$st->exists() || ($st->getNamespace() !== NS_MAIN) || ($st->isRedirect())) {
                        continue;
                    }
                    $text .= "** [[" . $st->getText() . "]]\n";
                }
                continue;
            }
            $text .= "* [[" . $t->getText() . "]]\n";
        }
        if (strlen($text) == 0) {
            $text = wfMessage('showbacklinks-no-backlinks')->text();
        }
        $text = $text . "</div>";

        $data = $wgOut->parse($linksTitlePrefix)
            . $wgOut->AddHTML($collapseControl)
            . $wgOut->parse($linksTitleSuffix . $text);
        $data = $wgOut->parse($linksTitlePrefix . $collapseControl . $linksTitleSuffix . $text); // This approach is not working yet
        //$data = $wgOut->parse($linksTitle . $text);
        return true;
    }

    public static function onBeforePageDisplay(OutputPage $out, Skin $skin)
    {
        $out->addModuleStyles('ext.ShowBackLinks.styles');
        $out->addModuleScripts('ext.ShowBackLinks.scripts');
    }
}
