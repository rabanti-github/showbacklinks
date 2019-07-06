<?php

class ShowBackLinksHooks
{

    public static function onSkinAfterContent(&$data, Skin $skin)
    {
        global $wgOut, $wgTitle;
        $tMain = Title::newFromText(wfMessage("mainpage")->text());
        $collapseControl = "<span id=\"backlink-colapse-trigger\">▼</span> ";
        $linksTitle = "== " . wfMessage("whatlinkshere")->text() .$collapseControl. " ==\n<div id=\"backlink-container\">";
       
        $text = "";
        foreach ($wgTitle->getLinksTo() as $t) {
            if ($t == $wgTitle || $t->getText() == $tMain || !$t->exists() || ($t->getNamespace() !== NS_MAIN)) {
                continue;
            }
            if ($t->isRedirect()) {
                $text .= "* [[" . $t->getText() . "]] (" . wfMessage('sbl-redirect')->text(). ")\n";
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
            $text = wfMessage('sbl-no-backlinks')->text();
        }
        $text = $text . "</div>";
        $data = $wgOut->parse($linksTitle . $text);
        return true;
    }
}
