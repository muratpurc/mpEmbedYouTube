# CONTENIDO CMS Modul mp_embed_youtube zum Einbinden von YouTube Videos

## Beschreibung

Mit dem Modul `mp_embed_youtube` lassen sich auf einfache Weise YouTube Videos
in CONTENIDO per iframe Einbinden. Das Modul generiert einen iframe Tag zum
gewünschten Video, das identisch zur der YouTube eigenen Embed-Methode ist.

Modul-Features:
- Eingabe von verschiedenen URL-Formaten,
  z. B. https://www.youtube.com/watch?v=WxnN05vOuSM oder https://youtu.be/WxnN05vOuSM
- Auswahl von vorgegeben Video-Maßen oder manuelle Angabe der Maße
- Option zum Aktivieren/Deaktivieren von Video-Empfehlungen am Ende des Videos
- Option zur Anzeige der Player Steuerung
- Ausgabe des iframe auch über http (nicht empfohlen)
- Steuerung des Privatsphäre-Modus (privacy-enhanced mode)
- Datenschutzkonformes Einbinden von YouTube Videos. Dabei kann ein Vorschaubild vom 
  Upload-Verzeichnis gewählt werden oder das Vorschaubild wird von YouTube herunterladen
  und auf dem Server gespeichert.

----

## Voraussetzungen

- CONTENIDO >= 4.10.*
- PHP >= 7.1 und PHP < 8.2
- PHP cURL-Erweiterung für den Download von Vorschaubildern von YouTube
- CONTENIDO Plugin "Mp Dev Tools" >= 0.1.0

Das benötigte Plugin (Package "mp_dev_tools.zip") von der [GitHub-Seite](https://github.com/muratpurc/CONTENIDO-plugin-mp_dev_tools/releases)
herunterladen und in CONTENIDO installieren.

----

## Installation/Verwendung

Den Modulordner "mp_embed_youtube" samt aller Inhalte in das Modulverzeichnis
des Mandanten "cms/data/modules" kopieren.
Danach sollte man im Backend die Funktion "Module synchronisieren" unter
"Style -> Module" ausführen.

----

## Changelog

**2023-03-22 mpEmbedYouTube Modul 0.3.0 (für CONTENIDO 4.10.x)**
- change: Überarbeiten des Modulcodes, Verwendung des Plugins "Mp Dev Tools"
- new: Datenschutzkonformes Einbinden von YouTube Videos
- new: Auswahl eines Vorschaubildes oder Download des Vorschaubildes von YouTube
  auf den Server

**2019-11-14 mpEmbedYouTube Modul 0.2.0 (für CONTENIDO 4.9.x - 4.10.x)**
- change: Überarbeiten des Modulcodes, portieren in CONTENIDO >= 4.9.x
- change: Verwendung aktuellerer Video-Maße
- change: Per Default HTTPS Urls verwenden
- new: Option zur Anzeige der Player Steuerung

**2012-12-08 mpEmbedYouTube Modul 0.1**
- Erste Veröffentlichung des mpEmbedYouTube Moduls

----

## mp_embed_youtube Modul Links

- [GitHub-Seite](https://github.com/muratpurc/mpEmbedYouTube)

- [CONTENIDO Forum unter "CONTENIDO 4.10 -> Module und Plugins"](https://forum.contenido.org/viewtopic.php?t=43775)

- [CONTENIDO Forum unter "CONTENIDO 4.8 -> Module und Plugins"](https://forum.contenido.org/viewtopic.php?t=32538)
