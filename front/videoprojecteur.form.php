<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 videoprojecteurs plugin for GLPI
 Copyright (C) 2009-2016 by the videoprojecteurs Development Team.

 https://github.com/InfotelGLPI/videoprojecteurs
 -------------------------------------------------------------------------

 LICENSE
      
 This file is part of videoprojecteurs.

 videoprojecteurs is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 videoprojecteurs is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with videoprojecteurs. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

include('../../../inc/includes.php');

if (!isset($_GET["id"])) $_GET["id"] = "";
if (!isset($_GET["withtemplate"])) $_GET["withtemplate"] = "";

$videoprojecteur = new PluginVideoprojecteursVideoprojecteur();

if (isset($_POST["add"])) {

   $videoprojecteur->check(-1, CREATE, $_POST);
   $newID = $videoprojecteur->add($_POST);
   if ($_SESSION['glpibackcreated']) {
      Html::redirect($videoprojecteur->getFormURL() . "?id=" . $newID);
   }
   Html::back();

} else if (isset($_POST["delete"])) {

   $videoprojecteur->check($_POST['id'], DELETE);
   $videoprojecteur->delete($_POST);
   $videoprojecteur->redirectToList();

} else if (isset($_POST["restore"])) {

   $videoprojecteur->check($_POST['id'], PURGE);
   $videoprojecteur->restore($_POST);
   $videoprojecteur->redirectToList();

} else if (isset($_POST["purge"])) {

   $videoprojecteur->check($_POST['id'], PURGE);
   $videoprojecteur->delete($_POST, 1);
   $videoprojecteur->redirectToList();

} else if (isset($_POST["update"])) {

   $videoprojecteur->check($_POST['id'], UPDATE);
   $videoprojecteur->update($_POST);
   Html::back();

} else {

   $videoprojecteur->checkGlobal(READ);

   $plugin = new Plugin();
   if ($plugin->isActivated("environment")) {
      Html::header(PluginVideoprojecteursVideoprojecteur::getTypeName(2), '', "assets", "pluginenvironmentdisplay", "videoprojecteurs");
   } else {
      Html::header(PluginVideoprojecteursVideoprojecteur::getTypeName(2), '', "assets", "pluginvideoprojecteursmenu");
   }
   $videoprojecteur->display($_GET);

   Html::footer();
}