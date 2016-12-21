<?php

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginVideoprojecteursVideoprojecteurMarque
 */
class PluginVideoprojecteursVideoprojecteurMarque extends CommonDropdown
{

    static $rightname = "dropdown";
    var $can_be_translated = true;

   /**
    * @param int $nb
    * @return translated
    */
    static function getMarqueName($nb = 0)
    {
        return _n('Marque de vidéo-projecteur', 'Marques de vidéo-projecteurs', $nb, 'videoprojecteurs');
    }


}
