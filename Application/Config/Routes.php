<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

$route['404'] = "Error/notFound";

$route["default_method"] = "index";
$route["default_controller"] = "FrontPage";

/**
 * ----------------------------------------------------
 */
/**
 * TinyMCE Editor
 */
$route["tiny_mce/(?<path>.+)\.(?<filetype>js|html|htm|css|gif)"]["*"] = "\\JackAssPHP\\Library\\TinyMce";
/* TinyMCE end */

$route[".+\.(png|gif|jpg|jpeg)"]["GET"] = "\\JackAssPHP\\Core\\Files/image";
$route["(?<dirname>[\w\d//]+/+)*(?<filename>[\w\.-]{2,})\.(js)"]["*"] = "\\JackAssPHP\\Core\\Files/javascript";
$route["(?<filename>[\w\.-]{2,})\.(css)"]["*"] = "\\JackAssPHP\\Core\\Files/css";

$route["impressum/"]["*"] = "FrontPage/impressum";
$route["loot/delete_player/(?<char_id>[0-9]{1,3})"]["*"] = "Loot/deletePlayer";
$route["loot/edit_player/"]["*"] = "Loot/editPlayer";
$route["loot/drop_player/(?<row_id>[0-9]{1,7})/"]["*"] = "Loot/dropPlayerfromEvent";
$route["loot/add_char_to_event/(?<char_id>[0-9]{1,5})/(?<event_id>[0-9]{1,5})/"]["*"] = "Loot/addPlayerToEvent";
$route["loot/create_player/"]["*"] = "Loot/save_new_player";
$route["loot/kill/"]["POST"] = "Loot/killKing";
$route["loot/move/"]["POST"] = "Loot/movePlayer";
$route["loot/playerlist/(?<event_id>[0-9]+)"]["*"] = "Loot/player_menu";
$route["loot/eventlist/(?<event_id>[0-9]+)"]["*"] = "Loot/player_menu";
$route["loot/menu/create_player/"]["*"] = "Loot/create_player_menu";
$route["loot/menu/(?<event_id>[0-9]+)/?"]["*"] = "Loot/player_menu";
$route["loot/delete_event/"]["*"] = "Loot/deleteEvent";
$route["loot/edit_event/"]["*"] = "Loot/editEvent";
$route["loot/create_event/"]["*"] = "Loot/createEvent";
$route["loot/char_log/(?<char_id>[0-9]+)/"]["GET"] = "Loot/getCharLog";
$route["loot/event_log/(?<event_id>[0-9]+)/"]["GET"] = "Loot/getEventLog";
$route["loot/(?<event_id>[0-9]+)?"]["*"] = "Loot";

$route["progress/switch_status/(?<name>[a-zA-Z_]+)/"]["*"] = "Progress/switchState";
$route["progress/"]["*"] = "Progress";

$route["rank/remove_auto_insert/"]["POST"] = "Ranks/removeAutoInsert";
$route["rank/add_auto_insert/"]["POST"] = "Ranks/addAutoInsert";
$route["rank/hide/"]["POST"] = "Ranks/hideRank";
$route["rank/show/"]["POST"] = "Ranks/showRank";

$route["news/edit/(?<Id>\d+)/"]["POST"] = "News/update";
$route["news/create/(?<type>\d)/"]["POST"] = "News/create";
$route["news/delete/"]["POST"] = "News/delete";

$route["profil/change/(?<data>name|email|login|password)/"]["POST"] = "Profil/changeData";
$route["profil/"]["*"] = "Profil";

$route["langswitcher/"]["*"] = "LangSwitcher";
//$route["rooster/change_new_member_setting/"]["*"] = "Rooster/changeNewMemberSetting";
//$route["rooster/show_rank/"]["POST"] = "Rooster/displayRank";
//$route["rooster/hide_rank/"]["POST"] = "Rooster/hideRank";
//$route["rooster/add_rank/"]["POST"] = "Rooster/addRank";
//$route["rooster/"]["*"] = "Rooster";

//$route["upload/"]["POST"] = "Rooster/upload";

$route["update/"]["*"] = "\\JackAssPHP\\Core\\Update";

$route["config/edit/(?<name>[a-zA-Z_]+)/"]["*"] = "SystemConfig/edit";
$route["config/"]["*"] = "SystemConfig";

$route["create_group/"]["POST"] = "Group/create";
$route["edit_group/"]["POST"] = "Group/edit";
$route["delete_group/"]["POST"] = "Group/delete";
$route["get_group_user/"]["POST"] = "User/getGroupUser/";
$route["add_user_to_group/"]["POST"] = "User/addToGroup/";
$route["remove_user_from_group/"]["POST"] = "User/removeFromGroup/";
$route["get_user_groups/"]["POST"] = "User/getUserGroups";
$route["edit_user/"]["*"] = "User/editUser";
$route["delete_user/"]["*"] = "User/deleteUser";
$route["create_user/"]["POST"] = "User/createUser";

$route["rights/show_rights_menu/(?<type>[ug])/(?<id>[0-9]+)/"]["GET"] = "Rights/showRightsMenu";
/* Gruppenrecht hinzufügen */
$route["rights/addright/g/(?<group_id>[0-9]+)/(?<right_name>[a-zA-Z\_\-]+)/"]["*"] = "Rights/addGroupRight";
/* Gruppenrecht entfernen */
$route["rights/removeright/g/(?<group_id>[0-9]+)/(?<right_name>[a-zA-Z\_\-]+)/"]["*"] = "Rights/deleteGroupRight";
/* Benutzerrechte hinzufügen */
$route["rights/removeright/u/(?<user_id>[0-9]+)/(?<right_name>[a-zA-Z\_\-]+)/"]["*"] = "Rights/deleteUserRight";
/* Benutzerrechte entfernen */
$route["rights/addright/u/(?<user_id>[0-9]+)/(?<right_name>[a-zA-Z\_\-]+)/"]["*"] = "Rights/addUserRight";

//$route["rights/get/list/"]["*"] = "Rights/getAllRights";
$route["rights/"]["*"] = "Rights";

$route["menue-links/order/(?<Id>\d+)/(?<newPosition>\d+)/"]["GET"] = "MenueLinks/editOrder";
$route["menue-links/name/(?<Id>\d+)/"]["*"] = "MenueLinks/editName";
$route["menue-links/path/(?<Id>\d+)/"]["*"] = "MenueLinks/editPath";
$route["menue-links/delete/(?<id>\d+)"]["GET"] = "MenueLinks/deleteItem";
$route["menue-links/add/"]["POST"] = "MenueLinks/addItem";
$route["menue-links/right/(?<item>\d+)/(?<right>\d+)/"]["GET"] = "MenueLinks/editRight";
$route["menue-links/"]["*"] = "MenueLinks";

/**
 * Charakter bearbeiten, löschen, erstellen.
 */
//$route["char_manager/"]["*"] = "CharManager";
/**
 * Das Admin Dashboard
 */
$route["admin/demo/"]["*"] = "Admin/getDemo";
$route["admin/"]["*"] = "Admin";

/**
 * Captcha anzeigen
 */
$route["captcha/show/"]["*"] = "Captcha/index";
$route["captcha/verify/"]["*"] = "Captcha/verify";

$route["login/check/(?<token>[a-zA-Z0-9]+)"]["*"] = "Login/checkLogin";
$route["login/?(?<small>\d)?"]["*"] = "Login";

$route["register/save/(?<token>[\w]+)"]["POST"] = "Register/save";
$route["register/check/uname/"]["POST"] = "Register/nameUnused";
$route["register/check/email/"]["POST"] = "Register/emailUnused";
$route["register/validate_email/(?<key>\w+)/"]["GET"] = "Register/validate";
$route["register/"]["*"] = "Register";

$route["password_recovery/"]["*"] = "Recovery";

$route["logout/?"]["*"] = "Login/logout";
/**
 * catch all request
 */
$route[".*"]["*"] = "FrontPage";
