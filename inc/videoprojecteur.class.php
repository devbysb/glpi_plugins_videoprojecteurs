<?php


if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginVideoprojecteursVideoprojecteur
 */
class PluginVideoprojecteursVideoprojecteur extends CommonDBTM
{
    public $dohistory = true;
    public static $rightname = "plugin_videoprojecteurs";
    protected $usenotepad = true;

   /**
    * @param int $nb
    * @return translated
    */
    public static function getTypeName($nb = 0)
    {
        return _n('Vidéo-projecteur', 'Vidéo-projecteurs', $nb, 'videoprojecteurs');
    }


   /**
    * @return array
    */
    public function getSearchOptions()
    {
        $tab = array();

        $tab['common'] = self::getTypeName(2);

        $tab[1]['table'] = $this->getTable();
        $tab[1]['field'] = 'name';
        $tab[1]['name'] = __('Nom');
        $tab[1]['datatype'] = 'itemlink';
        $tab[1]['itemlink_type'] = $this->getType();

        $tab[2]['table'] = 'glpi_plugin_videoprojecteurs_videoprojecteurtypes';
        $tab[2]['field'] = 'name';
        $tab[2]['name'] = __('Type');
        $tab[2]['datatype'] = 'dropdown';

        $tab[3]['table'] = 'glpi_plugin_videoprojecteurs_videoprojecteurmarques';
        $tab[3]['field'] = 'name';
        $tab[3]['name'] = __('Marque');
        $tab[3]['datatype'] = 'dropdown';

        $tab[4]['table'] = 'glpi_plugin_videoprojecteurs_videoprojecteurmodeles';
        $tab[4]['field'] = 'name';
        $tab[4]['name'] = __('Modèle');
        $tab[4]['datatype'] = 'dropdown';

        $tab[6]['table'] = $this->getTable();
        $tab[6]['field'] = 'serial';
        $tab[6]['name'] = __('Numéro de série');

        $tab[7]['table'] = $this->getTable();
        $tab[7]['field'] = 'date_affectation';
        $tab[7]['name'] = __('Date d\'affectation', 'videoprojecteurs');
        $tab[7]['datatype'] = 'date';

        $tab[9]['table'] = 'glpi_locations';
        $tab[9]['field'] = 'completename';
        $tab[9]['name'] = __('Lieu');
        $tab[9]['datatype'] = 'dropdown';

        $tab[10]['table'] = 'glpi_states';
        $tab[10]['field'] = 'completename';
        $tab[10]['name'] = __('Statut');
        $tab[10]['datatype'] = 'dropdown';

        $tab[11]['table'] = $this->getTable();
        $tab[11]['field'] = 'comment';
        $tab[11]['name'] = __('Commentaires');
        $tab[11]['datatype'] = 'text';

        $tab[12]['table'] = $this->getTable();
        $tab[12]['field'] = 'is_helpdesk_visible';
        $tab[12]['name'] = __('Associable à un ticket');
        $tab[12]['datatype'] = 'bool';

        $tab[13]['table'] = 'glpi_users';
        $tab[13]['field'] = 'name';
        $tab[13]['name'] = __('Utilisateur');
        $tab[13]['datatype'] = 'dropdown';
        $tab[13]['right'] = 'all';

        $tab[14]['table'] = $this->getTable();
        $tab[14]['field'] = 'date_mod';
        $tab[14]['name'] = __('Dernière modification');
        $tab[14]['datatype'] = 'datetime';
        $tab[14]['massiveaction'] = false;

        $tab[15]['table'] = $this->getTable();
        $tab[15]['field'] = 'salle';
        $tab[15]['name'] = __('Salle');

        $tab[30]['table'] = $this->getTable();
        $tab[30]['field'] = 'id';
        $tab[30]['name'] = __('ID');
        $tab[30]['datatype'] = 'number';

        $tab[80]['table'] = 'glpi_entities';
        $tab[80]['field'] = 'completename';
        $tab[80]['name'] = __('Entité');
        $tab[80]['datatype'] = 'dropdown';

        $tab[81]['table'] = 'glpi_entities';
        $tab[81]['field'] = 'entities_id';
        $tab[81]['name'] = __('Entité') . "-" . __('ID');

        $tab[82]['table'] = $this->getTable();
        $tab[82]['field'] = 'is_bookable';
        $tab[82]['name'] = __('Réservable', 'videoprojecteurs');
        $tab[82]['datatype'] = 'bool';

        return $tab;
    }

    /**
    * @param array $options
    * @return array
    */
    public function defineTabs($options = array())
    {
        $ong = array();
        $this->addDefaultFormTab($ong);
        //$this->addStandardTab('PluginVideoprojecteursReturn', $ong, $options);
        $this->addStandardTab('Ticket', $ong, $options);
        $this->addStandardTab('Item_Problem', $ong, $options);
        $this->addStandardTab('Notepad', $ong, $options);
        $this->addStandardTab('Log', $ong, $options);

        return $ong;
    }

   /**
    * @param $ID
    * @param array $options
    * @return bool
    */
    public function showForm($ID, $options = array())
    {
        $this->initForm($ID, $options);
        $this->showFormHeader($options);

        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Nom') . "</td>";
            echo "<td>";
                Html::autocompletionTextField($this, "name");
            echo "</td>";

            echo "<td>" . __('Utilisateur') . "</td><td>";
                User::dropdown(
                    array(
                        'value' => $this->fields["users_id"],
                        'entity' => $this->fields["entities_id"],
                        'right' => 'all'
                    )
                );
            echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Lieu') . "</td><td>";
                Location::dropdown(
                    array(
                        'value' => $this->fields["locations_id"],
                        'entity' => $this->fields["entities_id"]
                    )
                );
            echo "</td>";

            echo "<td>" . __('Type') . "</td><td>";
                Dropdown::show(
                    'PluginVideoprojecteursVideoprojecteurType',
                    array(
                        'name' => "plugin_videoprojecteurs_videoprojecteurtypes_id",
                        'value' => $this->fields["plugin_videoprojecteurs_videoprojecteurtypes_id"]
                    )
                );
            echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Salle') . "</td>";
            echo "<td>";
                Html::autocompletionTextField($this, "salle");
            echo "</td>";

            echo "<td>" . __('Marque') . "</td><td>";
                Dropdown::show(
                    'PluginVideoprojecteursVideoprojecteurMarque',
                    array(
                        'name' => "plugin_videoprojecteurs_videoprojecteurmarques_id",
                        'value' => $this->fields["plugin_videoprojecteurs_videoprojecteurmarques_id"]
                    )
                );
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Statut') . "</td><td>";
                State::dropdown(
                    array(
                        'value' => $this->fields["states_id"]
                    )
                );
            echo "</td>";

            echo "<td>" . __('Modèle') . "</td><td>";
                Dropdown::show(
                    'PluginVideoprojecteursVideoprojecteurModele',
                    array(
                        'name' => "plugin_videoprojecteurs_videoprojecteurmodeles_id",
                        'value' => $this->fields["plugin_videoprojecteurs_videoprojecteurmodeles_id"]
                    )
                );
            echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Date d\'affectation', 'videoprojecteurs') . "</td>";
            echo "<td>";
                Html::showDateFormItem("date_affectation", $this->fields["date_affectation"], true, true);
            echo "</td>";

            echo "<td>" . __('Numéro de série') . "</td>";
            echo "<td>";
                 Html::autocompletionTextField($this, "serial");
            echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Associable à un ticket') . "</td><td>";
                Dropdown::showYesNo('is_helpdesk_visible', $this->fields['is_helpdesk_visible']);
            echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Réservable', 'videoprojecteurs') . "</td><td>";
                Dropdown::showYesNo('is_bookable', $this->fields['is_bookable']);
            echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Commentaires') . "</td>";
            echo "<td class='center' colspan='3'>"
                    ."<textarea cols='115' rows='5' name='comment' >"
                        .$this->fields["comment"]
                    ."</textarea>";
            echo "</td>";
        echo "</tr>";

        $this->showFormButtons($options);

        return true;
    }


   //Massive Action
   /**
    * @param null $checkitem
    * @return an
    */
    public function getSpecificMassiveActions($checkitem = null)
    {
        $isadmin = static::canUpdate();
        $actions = parent::getSpecificMassiveActions($checkitem);

        if (Session::haveRight('transfer', READ && Session::isMultiEntitiesMode() && $isadmin)
         && Session::isMultiEntitiesMode()
         && $isadmin
        ) {
           $actions['PluginVideoprojecteursVideoprojecteur'.MassiveAction::CLASS_ACTION_SEPARATOR.'transfer'] = __('Transfer');
        }
        return $actions;
    }


   /**
    * @param MassiveAction $ma
    * @return bool|false
    */
    public static function showMassiveActionsSubForm(MassiveAction $ma)
    {
        switch ($ma->getAction()) {
            case "transfer":
                Dropdown::show('Entity');
                echo Html::submit(_x('button', 'Post'), array('name' => 'massiveaction'));
                return true;
                break;
        }
        return parent::showMassiveActionsSubForm($ma);
    }

    /**
     * @since version 0.85
     *
     * @see CommonDBTM::processMassiveActionsForOneItemtype()
     * @param MassiveAction $ma
     * @param CommonDBTM $item
     * @param array $ids
     * @return nothing|void
     */
    public static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item, array $ids)
    {
        switch ($ma->getAction()) {
            case "transfer":
                $input = $ma->getInput();
                if ($item->getType() == 'PluginVideoprojecteursVideoprojecteur') {
                    foreach ($ids as $key) {
                        $item->getFromDB($key);
                        $values["id"] = $key;
                        $values["entities_id"] = $input['entities_id'];
                        if ($item->update($values)) {
                            $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                        } else {
                            $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                        }
                    }
                }
                break;
        }
        return;
    }

}
