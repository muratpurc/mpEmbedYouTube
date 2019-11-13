CONTENIDO YouTube Video Modul mpEmbedYouTube 0.1 für CONTENIDO 4.8.x

################################################################################
TOC (Table of contents)

- BESCHREIBUNG
- INSTALLATION
- TIPPS & TRICKS
- CHANGELOG
- MPEMBEDYOUTUBE MODUL THEMEN IM CONTENIDO FORUM
- SCHLUSSBEMERKUNG


################################################################################
BESCHREIBUNG

Modul zum einfachen Einbinden von YouTube Videos in CONTENIDO per iframe. Das Modul generiert einen
iframe Tag zum gewünschten Video, das identisch zur der YouTube eigenen Embed-Methode ist.

Modul-Features:
- Eingabe von verschiedenen URL-Formaten, 
  z. B. http://www.youtube.com/watch?v=videoid oder http://youtu.be/videoid
- Auswahl von vorgegeben Video-Maße oder manuelle Angabe der Maße
- Option zum Aktivieren/Deaktivieren von Video-Empfehlungen am Ende des Videos
- Ausgabe des iframe auch über https
- Steuerung des Privatsphäre-Modus (privacy-enhanced mode)



################################################################################
INSTALLATION

Die im Modulpackage enthaltenen Dateien/Sourcen sind wie im Folgenden beschrieben zu installieren:

Der XML-Export (mpEmbedYouTube.xml) des mpEmbedYouTube Moduls, ist &uuml;ber das CONTENIDO-Backend
als Modul zu importieren.

Nach der Installation das Modul in einem Template einbinden, und im Artikel (welches das Template
verwendet) konfigurieren.



################################################################################
TIPPS & TRICKS

JavaScript-Fehlermeldung:
-------------------------
Im Frontend kann folgende JavaScript Fehlermeldung erscheinen:

    "Unsafe JavaScript attempt to access frame with URL {contenido_page_url} from frame with URL 
    {youtube_video_url}. Domains, protocols and ports must match."

Diese Fehlermeldung beeinflusst nicht die Ausgabe in der Seite und ist auch nicht sichbar für normale
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
MPEMBEDYOUTUBE MODUL THEMEN IM CONTENIDO FORUM

@todo



################################################################################
SCHLUSSBEMERKUNG

Benutzung des Moduls auf eigene Gefahr!

Murat Purc, murat@purc.de
