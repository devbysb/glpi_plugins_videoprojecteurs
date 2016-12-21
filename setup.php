<?php

// Init the hooks of the plugins -Needed
function plugin_init_videoprojecteurs()
{
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['videoprojecteurs'] = true;
    $PLUGIN_HOOKS['assign_to_ticket']['videoprojecteurs'] = true;
    $PLUGIN_HOOKS['change_profile']['videoprojecteurs'] = array('PluginVideoprojecteursProfile', 'initProfile');

    if (Session::getLoginUserID()) {
        Plugin::registerClass('PluginVideoprojecteursVideoprojecteur', array(
         'linkuser_types' => true,
         'helpdesk_visible_types' => true,
         'ticket_types' => true,
         ));

        Plugin::registerClass('PluginVideoprojecteursProfile', array('addtabon' => 'Profile'));

        if (class_exists('PluginResourcesResource')) {
            PluginResourcesResource::registerType('PluginVideoprojecteursVideoprojecteur');
        }

        $plugin = new Plugin();
        if (!$plugin->isActivated('environment') && Session::haveRight("plugin_videoprojecteurs", READ)) {
            $PLUGIN_HOOKS['menu_toadd']['videoprojecteurs'] = array('assets' => 'PluginVideoprojecteursMenu');
            $PLUGIN_HOOKS['helpdesk_menu_entry']['videoprojecteurs'] = '/front/wizard.php';
        }

        if (Session::haveRight("plugin_videoprojecteurs", UPDATE)) {
            $PLUGIN_HOOKS['use_massive_action']['videoprojecteurs'] = 1;
        }

        if (class_exists('PluginVideoprojecteursVideoprojecteur')) { // only if plugin activated
            $PLUGIN_HOOKS['plugin_datainjection_populate']['videoprojecteurs'] = 'plugin_datainjection_populate_videoprojecteurs';
        }

         // Import from Data_Injection plugin
         $PLUGIN_HOOKS['migratetypes']['videoprojecteurs'] = 'plugin_datainjection_migratetypes_videoprojecteurs';
         $PLUGIN_HOOKS['redirect_page']['videoprojecteurs'] = 'front/wizard.php';
    }
}

// Get the name and the version of the plugin - Needed

/**
 * @return array
 */
function plugin_version_videoprojecteurs()
{
    return array(
         'name' => _n('Videoprojecteur', 'Videoprojecteurs', 2, 'videoprojecteurs'),
         'version' => '1.0.0',
         'author' => "Dev",
         'license' => 'GPLv2+',
         'homepage' => 'https://github.com/eldiablo62/glpi_plugins_videoprojecteurs.git',
         'minGlpiVersion' => '9.1',
      );
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
/**
 * @return bool
 */
function plugin_videoprojecteurs_check_prerequisites()
{
    if (version_compare(GLPI_VERSION, '9.1', 'lt') || version_compare(GLPI_VERSION, '9.2', 'ge')) {
        _e('This plugin requires GLPI >= 9.1', 'videoprojecteurs');
        return false;
    }
    return true;
}

// Uninstall process for plugin : need to return true if succeeded
//may display messages or add to message after redirect
/**
 * @return bool
 */
function plugin_videoprojecteurs_check_config()
{
    return true;
}

/**
 * @param $types
 * @return mixed
 */
function plugin_datainjection_migratetypes_videoprojecteurs($types)
{
    $types[1600] = 'PluginVideoprojecteursVideoprojecteur';
    return $types;
}
