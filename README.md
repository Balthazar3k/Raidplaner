Core v1.0 für ilch CMS 1.1p (www.ilch.de):
_____________________________________________________________

Beschreibung:
---------------------------------------
Kleines Framework

Dieses Modul eignet sich um schneller Module zu Schreiben in ilch.
Man sollte sich mit OOP auskennen.

Entwickelt
---------------------------------------
• von "Balthazar3k"
• auf Basis von IlchClan 1.1p
• Es werden keine Dateien Überschrieben.

Installation:
---------------------------------------
• Alle Files Hochladen
• Den folgenden Code in die "include/includes/loader.php" einfügen

        /* Balthazar3k Core */
        require_once ('include/angelo.b3k/core.php');
        $core = new Core();

        $core->header()
            ->set('font-awesome/css/font-awesome.min.css')

            ->set('jquery/js/jquery-1.10.2.js')
            ->set('jquery/js/jquery-ui-1.10.4.custom.min.js')
            ->set('jquery/css/ui-darkness/jquery-ui-1.10.4.custom.min.css')

            ->set('core/core.js');

        $core->header()->get('font-awesome', 'jquery', 'core');

Schnittstellen:
---------------------------------------

$core->db();
$core->func();
$core->permission();
$core->confirm();
$core->header();
$core->smarty();

Bekannte Einschränkungen / Fehler:
---------------------------------------
• Es sind zur Zeit keine Fehler bekannt!

Haftungsausschluss:
---------------------------------------
Ich übernehme keine Haftung für Schäden, die durch dieses Skript entstehen.
Benutzung ausschließlich AUF EIGENE GEFAHR!

!!! vor der Installation Empfehle ich einen Datenbank & FTP Backup zu machen.


Fehler bitte an http://balthazar3k.funpic.de ( im Forum unter Raidplaner ).
