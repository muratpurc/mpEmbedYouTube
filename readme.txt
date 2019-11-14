CONTENIDO CMS Modul mpEmbedYouTube zum Einbinden von YouTube Videos

################################################################################
TOC (Table of contents)

- BESCHREIBUNG
- INSTALLATION
- TIPPS & TRICKS
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
- Ausgabe des iframe auch über https
- Steuerung des Privatsphäre-Modus (privacy-enhanced mode)


################################################################################
INSTALLATION

Den Modulordner "mp_embed_youtube" samt aller Inhalte in das Modulverzeichnis
des Mandanten "cms/data/modules" kopieren.
Danach sollte man im Backend die Funktion "Module synchronisieren" unter
"Style -> Module" ausführen.


################################################################################
TIPPS & TRICKS

JavaScript-Fehlermeldung:
-------------------------
Im Frontend kann folgende JavaScript Fehlermeldung erscheinen:

    "Unsafe JavaScript attempt to access frame with URL {contenido_page_url} from frame with URL
    {youtube_video_url}. Domains, protocols and ports must match."

Diese Fehlermeldung beeinflusst nicht die Ausgabe in der Seite und ist auch nicht sichtbar für normale
Seitenbesucher. Lediglich in der JavaScript-Konsole des Browsers kann man die Fehlermeldung sehen.

Es ist lediglich ein "Warnhinweis" des Browsers, weil vom YouTube Frame aus versucht wurde, auf das
übergeordnete Fenster zuzugreifen. Wer sich dabei nicht wohl fühl, kann gerne swfObject oder andere
Techniken für die Ausgabe verwenden...

Dazu gibt es auch einen umfassenden Beitrag bei stackoverflow:
http://stackoverflow.com/questions/6346176/youtube-embed-unsafe-javascript-attempt-to-access-frame


################################################################################
CHANGELOG

2012-12-08 mpEmbedYouTube Modul 0.1
    * Erste Veröffentlichung des mpEmbedYouTube Moduls


################################################################################
MPEMBEDYOUTUBE MODUL LIMKS

CONTENIDO Forum unter "CONTENIDO 4.8 -> Module und Plugins":
https://forum.contenido.org/viewtopic.php?t=32538

################################################################################
SCHLUSSBEMERKUNG

Benutzung des Moduls auf eigene Gefahr!

Murat Purç, murat@purc.de
