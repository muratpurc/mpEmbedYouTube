CONTENIDO CMS Modul mpEmbedYouTube zum Einbinden von YouTube Videos

################################################################################
TOC (Table of contents)

- BESCHREIBUNG
- INSTALLATION
- CHANGELOG
- MPEMBEDYOUTUBE MODUL LIMKS
- SCHLUSSBEMERKUNG


################################################################################
BESCHREIBUNG

Modul zum einfachen Einbinden von YouTube Videos in CONTENIDO per iframe. Das Modul generiert einen
iframe Tag zum gewünschten Video, das identisch zur der YouTube eigenen Embed-Methode ist.

Modul-Features:
- Eingabe von verschiedenen URL-Formaten,
  z. B. https://www.youtube.com/watch?v=WxnN05vOuSM oder https://youtu.be/WxnN05vOuSM
- Auswahl von vorgegeben Video-Maßen oder manuelle Angabe der Maße
- Option zum Aktivieren/Deaktivieren von Video-Empfehlungen am Ende des Videos
- Option zur Anzeige der Player Steuerung
- Ausgabe des iframe auch über http (nicht empfohlen)
- Steuerung des Privatsphäre-Modus (privacy-enhanced mode)


################################################################################
INSTALLATION

Den Modulordner "mp_embed_youtube" samt aller Inhalte in das Modulverzeichnis
des Mandanten "cms/data/modules" kopieren.
Danach sollte man im Backend die Funktion "Module synchronisieren" unter
"Style -> Module" ausführen.


################################################################################
CHANGELOG

2019-11-14 mpEmbedYouTube Modul 0.2.0 (für CONTENIDO 4.9.x - 4.10.x)
    * change: Überarbeiten des Modulcodes, portieren in CONTENIDO >= 4.9.x
    * change: Verwendung aktuellerer Video-Maße
    * change: Per defeault HTTPS Urls verwenden
    * new:    Option zur Anzeige der Player Steuerung
2012-12-08 mpEmbedYouTube Modul 0.1
    * Erste Veröffentlichung des mpEmbedYouTube Moduls


################################################################################
MPEMBEDYOUTUBE MODUL LIMKS

CONTENIDO Forum unter "CONTENIDO 4.8 -> Module und Plugins":
https://forum.contenido.org/viewtopic.php?t=32538

CONTENIDO Forum unter "CONTENIDO 4.10 -> Module und Plugins":
https://forum.contenido.org/viewtopic.php?t=43775


################################################################################
SCHLUSSBEMERKUNG

Benutzung des Moduls auf eigene Gefahr!

Murat Purç, murat@purc.de
