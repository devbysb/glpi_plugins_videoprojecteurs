<?php

/**
 * @return bool
 */
function plugin_videoprojecteurs_install()
{
    global $DB;

    include_once(GLPI_ROOT . "/plugins/videoprojecteurs/inc/profile.class.php");

    if (!TableExists("glpi_plugin_videoprojecteurs") && !TableExists("glpi_plugin_videoprojecteurs_videoprojecteurtypes")) {
        $DB->runFile(GLPI_ROOT . "/plugins/videoprojecteurs/sql/empty-1.0.0.sql");
    }

    PluginVideoprojecteursProfile::initProfile();
    PluginVideoprojecteursProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
    $migration = new Migration("1.0.0");
    $migration->dropTable('glpi_plugin_videoprojecteurs_profiles');

    return true;
}

/**
 * @return bool
 */
function plugin_videoprojecteurs_uninstall()
{
    global $DB;

    include_once(GLPI_ROOT . "/plugins/videoprojecteurs/inc/profile.class.php");
    include_once(GLPI_ROOT . "/plugins/videoprojecteurs/inc/menu.class.php");

    $tables = array(
        "glpi_plugin_videoprojecteurs_videoprojecteurs",
        "glpi_plugin_videoprojecteurs_videoprojecteurtypes",
        "glpi_plugin_videoprojecteurs_videoprojecteurmarques",
        "glpi_plugin_videoprojecteurs_videoprojecteurmodeles",
        "glpi_plugin_videoprojecteurs_configs",
        "glpi_plugin_videoprojecteurs_requests"
    );

    foreach ($tables as $table) {
        $DB->query("DROP TABLE IF EXISTS `$table`;");
    }

    $tables_glpi = array(
        "glpi_displaypreferences",
        "glpi_documents_items",
        "glpi_bookmarks",
        "glpi_logs",
        "glpi_items_tickets",
        "glpi_notepads",
        "glpi_dropdowntranslations"
    );

    foreach ($tables_glpi as $table_glpi) {
        $DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` LIKE 'PluginVideoprojecteurs%';");
    }

    if (class_exists('PluginDatainjectionModel')) {
        PluginDatainjectionModel::clean(array('itemtype' => 'PluginVideoprojecteursVideoprojecteur'));
    }


   //Delete rights associated with the plugin
   $profileRight = new ProfileRight();
    foreach (PluginVideoprojecteursProfile::getAllRights() as $right) {
        $profileRight->deleteByCriteria(array('name' => $right['field']));
    }

    PluginVideoprojecteursMenu::removeRightsFromSession();
    PluginVideoprojecteursProfile::removeRightsFromSession();

    return true;
}

/**
 * @param $types
 * @return mixed
 */
function plugin_videoprojecteurs_AssignToTicket($types)
{
    if (Session::haveRight("plugin_videoprojecteurs_open_ticket", "1")) {
        $types['PluginVideoprojecteursVideoprojecteur'] = PluginVideoprojecteursVideoprojecteur::getTypeName(2);
    }

    return $types;
}

// Define dropdown relations
/**
 * @return array
 */
function plugin_videoprojecteurs_getDatabaseRelations()
{
    $plugin = new Plugin();
    if ($plugin->isActivated("videoprojecteurs")) {
        return array(
            "glpi_plugin_videoprojecteurs_videoprojecteurtypes" => array(
                "glpi_plugin_videoprojecteurs_videoprojecteurs" => "plugin_videoprojecteurs_videoprojecteurtypes_id"
            ),
            "glpi_plugin_videoprojecteurs_videoprojecteurmarques" => array(
                "glpi_plugin_videoprojecteurs_videoprojecteurs" => "plugin_videoprojecteurs_videoprojecteurmarques_id"
            ),
            "glpi_plugin_videoprojecteurs_videoprojecteurmodeles" => array(
                "glpi_plugin_videoprojecteurs_videoprojecteurs" => "plugin_videoprojecteurs_videoprojecteurmodeles_id"
            ),
            "glpi_entities" => array(
                "glpi_plugin_videoprojecteurs_videoprojecteurs" => "entities_id",
                "glpi_plugin_videoprojecteurs_videoprojecteurtypes" => "entities_id"
            ),
            "glpi_locations" => array(
                "glpi_plugin_videoprojecteurs_videoprojecteurs" => "locations_id"
            ),
            "glpi_users" => array(
                "glpi_plugin_videoprojecteurs_videoprojecteurs" => "users_id"
            )
        );
    } else {
        return array();
    }
}

// Define Dropdown tables to be manage in GLPI :
/**
 * @return array
 */
function plugin_videoprojecteurs_getDropdown()
{
    $plugin = new Plugin();
    if ($plugin->isActivated("videoprojecteurs")) {
        return array(
            "PluginVideoprojecteursVideoprojecteurType" => PluginVideoprojecteursVideoprojecteurType::getTypeName(2),
            "PluginVideoprojecteursVideoprojecteurMarque" => PluginVideoprojecteursVideoprojecteurMarque::getMarqueName(2),
            "PluginVideoprojecteursVideoprojecteurModele" => PluginVideoprojecteursVideoprojecteurModele::getModeleName(2)
        );
    } else {
        return array();
    }
}


/**
 *
 */
function plugin_datainjection_populate_videoprojecteurs()
{
    global $INJECTABLE_TYPES;
    $INJECTABLE_TYPES['PluginVideoprojecteursVideoprojecteurInjection'] = 'videoprojecteurs';
}
