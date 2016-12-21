<?php

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginVideoprojecteursProfile
 */
class PluginVideoprojecteursProfile extends CommonDBTM
{
    static $rightname = "profile";

   /**
    * @param CommonGLPI $item
    * @param int $withtemplate
    * @return string|translated
    */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if ($item->getType() == 'Profile') {
            return PluginVideoprojecteursVideoprojecteur::getTypeName(2);
        }
        return '';
    }

   /**
    * @param CommonGLPI $item
    * @param int $tabnum
    * @param int $withtemplate
    * @return bool
    */
    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if ($item->getType() == 'Profile') {
            $ID = $item->getID();
            $prof = new self();

            self::addDefaultProfileInfos(
                $ID,
                array(
                    'plugin_videoprojecteurs' => 0,
                    'plugin_videoprojecteurs_open_ticket' => 0
                )
            );
            $prof->showForm($ID);
        }
        return true;
    }

   /**
    * @param $ID
    */
    static function createFirstAccess($ID)
    {
        //85
        self::addDefaultProfileInfos(
            $ID,
            array(
                'plugin_videoprojecteurs' => 127,
                'plugin_videoprojecteurs_open_ticket' => 1
            ),
            true
        );
    }

   /**
    * @param $profiles_id
    * @param $rights
    * @param bool $drop_existing
    * @internal param $profile
    */
    static function addDefaultProfileInfos($profiles_id, $rights, $drop_existing = false)
    {
        $profileRight = new ProfileRight();
        foreach ($rights as $right => $value) {
            if( countElementsInTable('glpi_profilerights',
                "`profiles_id`='$profiles_id' AND `name`='$right'") && $drop_existing
            ) {
                $profileRight->deleteByCriteria(array('profiles_id' => $profiles_id, 'name' => $right));
            }
            if(!countElementsInTable('glpi_profilerights',
               "`profiles_id`='$profiles_id' AND `name`='$right'")
            ) {
                $myright['profiles_id'] = $profiles_id;
                $myright['name'] = $right;
                $myright['rights'] = $value;
                $profileRight->add($myright);

                //Add right to the current session
                $_SESSION['glpiactiveprofile'][$right] = $value;
            }
        }
    }

   /**
    * Show profile form
    *
    * @param int $profiles_id
    * @param bool $openform
    * @param bool $closeform
    * @return nothing
    * @internal param int $items_id id of the profile
    * @internal param value $target url of target
    *
    */
    function showForm($profiles_id = 0, $openform = TRUE, $closeform = TRUE)
    {

        echo "<div class='firstbloc'>";
        if (($canedit = Session::haveRightsOr(self::$rightname, array(CREATE, UPDATE, PURGE)))
         && $openform
        ) {
             $profile = new Profile();
             echo "<form method='post' action='" . $profile->getFormURL() . "'>";
        }

        $profile = new Profile();
        $profile->getFromDB($profiles_id);
        $rights = $this->getAllRights();
        $profile->displayRightsChoiceMatrix(
            $rights,
            array(
                'canedit' => $canedit,
                'default_class' => 'tab_bg_2',
                'title' => __('General')
            )
        );
        echo "<table class='tab_cadre_fixehov'>";
            echo "<tr class='tab_bg_1'><th colspan='4'>" . __('Helpdesk') . "</th></tr>\n";
                $effective_rights = ProfileRight::getProfileRights($profiles_id, array('plugin_videoprojecteurs_open_ticket'));
            echo "<tr class='tab_bg_2'>";
                echo "<td width='20%'>" . __('Associable items to a ticket') . "</td>";
                echo "<td colspan='5'>";
                    Html::showCheckbox(
                        array(
                            'name' => '_plugin_videoprojecteurs_open_ticket',
                            'checked' => $effective_rights['plugin_videoprojecteurs_open_ticket']
                        )
                    );
                echo "</td>";
            echo "</tr>\n";
        echo "</table>";

        if ($canedit
         && $closeform
        ) {
            echo "<div class='center'>";
            echo Html::hidden('id', array('value' => $profiles_id));
            echo Html::submit(_sx('button', 'Save'), array('name' => 'update'));
            echo "</div>\n";
            Html::closeForm();
        }
        echo "</div>";
        return;
    }

   /**
    * @param bool $all
    * @return array
    */
    static function getAllRights($all = false)
    {
        $rights = array(
            array(
                'itemtype' => 'PluginVideoprojecteursVideoprojecteur',
                'label' => _n('Videoprojecteur', 'Videoprojecteurs', 2, 'videoprojecteurs'),
                'field' => 'plugin_videoprojecteurs'
            )
        );

        if ($all) {
            $rights[] = array(
                'itemtype' => 'PluginVideoprojecteursVideoprojecteur',
                'label' => __('Associable items to a ticket'),
                'field' => 'plugin_videoprojecteurs_open_ticket');
        }

        return $rights;
    }

   /**
    * Init profiles
    *
    * @param $old_right
    * @return int
    */

    static function translateARight($old_right)
    {
        switch ($old_right) {
            case '':
                return 0;
            case 'r':
                return READ;
            case 'w':
                return ALLSTANDARDRIGHT + READNOTE + UPDATENOTE;
            case '0':
            case '1':
                return $old_right;
            default:
                return 0;
        }
    }

   /**
    * @since 0.85
    * Migration rights from old system to the new one for one profile
    * @param $profiles_id the profile ID
    * @return bool
    */
    static function migrateOneProfile($profiles_id)
    {
        global $DB;
        //Cannot launch migration if there's nothing to migrate...

        if (!TableExists('glpi_plugin_videoprojecteurs_profiles')) {
            return true;
        }

        foreach ($DB->request('glpi_plugin_videoprojecteurs_profiles', "`profiles_id`='$profiles_id'") as $profile_data)
        {
            $matching = array(
                'videoprojecteurs' => 'plugin_videoprojecteurs',
                'open_ticket' => 'plugin_videoprojecteurs_open_ticket'
            );
            $current_rights = ProfileRight::getProfileRights($profiles_id, array_values($matching));
            foreach ($matching as $old => $new) {
                if (!isset($current_rights[$old])) {
                    $query = "UPDATE `glpi_profilerights` 
                             SET `rights`='" . self::translateARight($profile_data[$old]) . "' 
                             WHERE `name`='$new' AND `profiles_id`='$profiles_id'";
                    $DB->query($query);
                }
            }
        }
        return;
    }

   /**
    * Initialize profiles, and migrate it necessary
    */
    static function initProfile()
    {
        global $DB;
        $profile = new self();

        //Add new rights in glpi_profilerights table
        foreach ($profile->getAllRights(true) as $data) {
            if (countElementsInTable("glpi_profilerights", "`name` = '" . $data['field'] . "'") == 0) {
                ProfileRight::addProfileRights(array($data['field']));
            }
        }

        //Migration old rights in new ones
        foreach ($DB->request("SELECT `id` FROM `glpi_profiles`") as $prof) {
            self::migrateOneProfile($prof['id']);
        }
        foreach ($DB->request("SELECT *
                           FROM `glpi_profilerights` 
                           WHERE `profiles_id`='" . $_SESSION['glpiactiveprofile']['id'] . "' 
                              AND `name` LIKE '%plugin_videoprojecteurs%'") as $prof) {
             $_SESSION['glpiactiveprofile'][$prof['name']] = $prof['rights'];
        }
    }


    static function removeRightsFromSession()
    {
        foreach (self::getAllRights(true) as $right) {
            if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
                unset($_SESSION['glpiactiveprofile'][$right['field']]);
            }
        }
    }
}
