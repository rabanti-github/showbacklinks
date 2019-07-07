<?php

class ShowBackLinksHooks
{

    public static function onSkinAfterContent(&$data, Skin $skin)
    {
        global $wgOut, $wgTitle;
        $tMain = Title::newFromText(wfMessage("mainpage")->text());
        $linksTitlePrefix = "== " . wfMessage("whatlinkshere")->text() . " ==";
        $collapseControl = " <span title=\"" . wfMessage('showbacklinks-toggle-hint')->text()
            . "\" onClick=\"showbacklinksToogleCollapse('backlink-colapse-trigger', 'showbacklinks-container', '"
            . wfMessage('showbacklinks-toggle-visible')->text() . "', '"
            . wfMessage('showbacklinks-toggle-hidden')->text()
            . "')\" class=\"showbacklinks-trigger-default\" id=\"backlink-colapse-trigger\">[ "
            . wfMessage('showbacklinks-toggle-visible')->text()
            . " ]</span> ";
        $linksTitleSuffix = "<div id=\"showbacklinks-container\" class=\"toccolours mw-collapsible-content\" style=\"width:400px; overflow:auto;\">\n";

        $text = "";
        $links = $wgTitle->getLinksTo();
        usort($links, function ($a, $b) {
            return strcmp($a->getText(), $b->getText());
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
                    $text .= "** [[" . $st->getText() . "]] (" . wfMessage('showbacklinks-links-of-redirect')->text() . ")\n";
                }
                continue;
            }
            $text .= "* [[" . $t->getText() . "]]\n";
        }
        if (strlen($text) == 0) {
            $text = wfMessage('showbacklinks-no-backlinks')->text();
        }
        $text = $text . "</div>";

        //      . $wgOut->parse($linksTitlePrefix . $collapseControl . $linksTitleSuffix . $text);
        $data = $wgOut->parse($linksTitlePrefix) .  $collapseControl . $wgOut->parse($linksTitleSuffix . $text); // This approach is a hack until the MW-native collapse function is working properly 
        return true;
    }

    public static function onBeforePageDisplay(OutputPage $out, Skin $skin)
    {
        $out->addModuleStyles('ext.ShowBackLinks.styles');
        $out->addModuleScripts('ext.ShowBackLinks.scripts');
    }
}
