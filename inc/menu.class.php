<?php

/**
 * Class PluginVideoprojecteursMenu
 */
class PluginVideoprojecteursMenu extends CommonGLPI
{
    public static $rightname = 'plugin_videoprojecteurs';

   /**
    * @return translated
    */
    public static function getMenuName()
    {
        return _n('Vidéo-projecteur', 'Vidéo-projecteurs', 2, 'videoprojecteurs');
    }

   /**
    * @return array
    */
    public static function getMenuContent()
    {
        $menu = array();
        $menu['title'] = self::getMenuName();
        $menu['page'] = "/plugins/videoprojecteurs/front/videoprojecteur.php";
        $menu['links']['search'] = PluginVideoprojecteursVideoprojecteur::getSearchURL(false);
        if (PluginVideoprojecteursVideoprojecteur::canCreate()) {
            $menu['links']['add'] = PluginVideoprojecteursVideoprojecteur::getFormURL(false);
        }

        return $menu;
    }

    public static function removeRightsFromSession()
    {
        if (isset($_SESSION['glpimenu']['assets']['types']['PluginVideoprojecteursMenu'])) {
            unset($_SESSION['glpimenu']['assets']['types']['PluginVideoprojecteursMenu']);
        }
        if (isset($_SESSION['glpimenu']['assets']['content']['pluginvideoprojecteursmenu'])) {
            unset($_SESSION['glpimenu']['assets']['content']['pluginvideoprojecteursmenu']);
        }
    }
}
