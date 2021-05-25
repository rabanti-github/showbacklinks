<?php

class ShowBackLinksHooks
{

    public static function onSkinAfterContent(&$data, Skin $skin)
    {
        global $wgOut, $wgTitle;
	    
	if($wgTitle->isSpecialPage())
		return;

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

		$parser  = \MediaWiki\MediaWikiServices::getInstance()->getParser();
		$parsed  =  $parser->parse("__NOEDITSECTION__".$linksTitlePrefix,$wgTitle,new ParserOptions())->getText();		
        $parsed .=  $parser->parse($linksTitleSuffix.$text,$wgTitle,new ParserOptions())->getText();						
		$data = $wgOut->addHTML($parsed); 
        
        return true;
    }

    public static function onBeforePageDisplay(OutputPage $out, Skin $skin)
    {
		$out->addModules( ['ext.ShowBackLinks.scripts'] ) ;
		$out->addModules( ['ext.ShowBackLinks.styles'] ) ;
    }
}
