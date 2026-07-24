# QUICKREF AI -- contao-lsjs4c Bundle

Kurzreferenz für Agents, die an dieser Contao-Erweiterung (`leadingsystems/contao-lsjs4c`)
arbeiten. Ziel ist ein schneller, verlässlicher Überblick über Zweck, Aufbau und die
Verdrahtung des Bundles. Die interne Mechanik von LSJS (Kompilierung/Binder) ist in
eigenen Quellen dokumentiert und wird hier nur verortet, nicht dupliziert.

Alle Pfade sind relativ zur Bundle-Wurzel (dort liegen `composer.json`, `README.md`,
`CHANGELOG.md` und diese QUICKREF), sofern nicht anders angegeben. Projekt-Pfade wie
`files/`, `vendor/`, `assets/js` beziehen sich auf die Contao-Projektwurzel.

---

## 1. Überblick und Zweck

- Contao-Bundle (`type: contao-bundle`), Paket `leadingsystems/contao-lsjs4c`. Es
  vereinfacht die Nutzung von LSJS in Contao ("LSJS for Contao").
- Aufgabe: LSJS-Apps und Core-Customizations pro Contao-Layout auswählbar machen, sie
  kompilieren lassen und in die Seite einbinden.
- Kein Standalone-Support: laut `README.md` assistiert es anderen Leading-Systems-
  Erweiterungen (v. a. Merconis); technisch standalone nutzbar, aber ohne Support.
- PHP-Namespace: `LeadingSystems\LSJS4CBundle\` (`psr-4` auf `src/`); sowie `classmap`
  auf `src/Resources/contao/` (mit Ausschluss von `config/`, `dca/`, `languages/`,
  `templates/`).
- Abhängigkeiten (`composer.json`):
  - `contao/core-bundle` (`^4.13 || ^5.0`)
  - `leadingsystems/lsjs` (`^3.1`) -- LSJS-Framework inkl. Binder/Kompilierung
  - `leadingsystems/contao-helpers` (`^2 || ^3`)
  - `php` (`^7.0 || ^8.0`)
- Contao 4.13 und Contao 5 werden unterstützt.

---

## 2. Einordnung: Wer macht was

Zwei Ebenen sind sauber getrennt:

- **LSJS (Paket `leadingsystems/lsjs`) = Framework und Kompilierung.** Stellt den Binder
  bereit (`assets/lsjs/core/appBinder/binderController.php`, Klasse
  `\lsjs_binderController`) und ist host-unabhängig.
- **LSJS4C (dieses Bundle) = Contao-Integration.** Bietet die Backend-Auswahl am Layout,
  verdrahtet sich über Contao-Hooks und -Events und ruft den Binder zur Kompilierung auf.
  Die interne LSJS-Mechanik kennt es nicht.

Dieselbe Abgrenzung beschreibt die Theme-QUICKREF (Abschnitt 6): dort ist `contao-lsjs4c`
die "Bereitstellung/Kompilierung" in Contao und kennt das `customCode`-Muster nicht.

---

## 3. Verzeichnis-Landkarte (`src/`)

- `LeadingSystemsLSJS4CBundle.php` -- Bundle-Klasse.
- `ContaoManager/Plugin.php` -- Contao-Manager-Plugin; lädt nach `ContaoCoreBundle`.
- `DependencyInjection/LeadingSystemsLSJS4CExtension.php` -- lädt `services.yml`.
- `EventListener/GetPageLayoutListener.php` -- `getPageLayout`-Hook (siehe Abschnitt 5).
- `EventListener/GeneratePageListener.php` -- `generatePage`-Hook (siehe Abschnitt 5).
- `EventSubscriber/LsjsInsertionSubscriber.php` -- Backend-Einbindung (siehe Abschnitt 6).
- `Migration/CoreAndAppPathMigration.php` -- Feld-Migration (siehe Abschnitt 7).
- `Resources/config/services.yml` -- Service-Definitionen (siehe Abschnitt 8).
- `Resources/contao/dca/tl_layout.php` -- Layout-Felder + Options-Callbacks (Abschnitt 4).
- `Resources/contao/languages/de|en/tl_layout.php` -- Feld-Labels und Legende.

---

## 4. Backend-Konfiguration (`tl_layout`)

Das Bundle erweitert `tl_layout` per `PaletteManipulator` um die Legende `lsjs4c_legend`
("LSJS") mit folgenden Feldern:

- `lsjs4c_loadLsjs` (`checkbox`): LSJS-Core-JavaScript laden. Voraussetzung, um in diesem
  Layout überhaupt LSJS-Apps zu nutzen.
- `lsjs4c_appsToLoad` (`checkboxWizard`, `multiple`/`sortable`, `blob`): Auswahl der zu
  ladenden LSJS-App-Verzeichnisse.
- `lsjs4c_coreCustomizationsToLoad` (`checkboxWizard`, `blob`): Auswahl der zu ladenden
  Core-Customization-Verzeichnisse.
- `lsjs4c_debugMode` (`checkbox`): Debug-Modus (Template-Pfade im Output sichtbar).
- `lsjs4c_noMinifier` (`checkbox`): Minifier abschalten.

**App-/Core-Erkennung** (Options-Callbacks in Klasse `tl_layout`): Ein Symfony `Finder`
durchsucht die Projektverzeichnisse `files/` und `vendor/` rekursiv (Tiefe >= 1) nach
Verzeichnissen, deren Name mit `lsjs-app` (Apps) bzw. `lsjs-core` (Core-Customizations)
beginnt. Die gefundenen relativen Pfade werden als Auswahloptionen angeboten. Bereits
gespeicherte Pfade, die noch existieren, bleiben in der Auswahl erhalten.

---

## 5. Frontend-Laufzeit

- **`getPageLayout`-Hook** (`GetPageLayoutListener::getLayoutSettingsForGlobalUse`):
  kopiert die Layout-Einstellungen (`lsjs4c_loadLsjs`, `lsjs4c_appsToLoad`,
  `lsjs4c_coreCustomizationsToLoad`, `lsjs4c_debugMode`, `lsjs4c_noMinifier`) nach
  `$GLOBALS['lsjs4c_globals']` zur globalen Weiterverwendung.
- **`generatePage`-Hook** (`GeneratePageListener::insertLsjs`): läuft nur, wenn
  `lsjs4c_loadLsjs` gesetzt ist. Lädt den Binder und erzeugt zwei Kompilate:
  1. **Core** inklusive der ausgewählten `pathsToCoreCustomizations` (ohne Apps).
  2. **Apps** aus den ausgewählten `pathsToApps` (ohne Core/Core-Module).
  Beide Kompilate werden unter `assets/js` erzeugt und ihr (projekt-relativer) Pfad wird
  über `$GLOBALS['TL_JAVASCRIPT']` eingebunden. `debug` und `no-minifier` stammen aus den
  Layout-Einstellungen.
- Die gespeicherten Pfade sind relativ zur Projektwurzel und werden zur Laufzeit mit
  `kernel.project_dir` absolut gemacht.

---

## 6. Backend-Laufzeit

- `LsjsInsertionSubscriber` (`kernel.event_subscriber`, Methode
  `onKernelControllerArguments`): Bei einem Backend-Hauptrequest mit angemeldetem
  Backend-User wird der LSJS-Core kompiliert (ohne Apps, mit `debug`, ohne Minifier) und
  über `$GLOBALS['TL_JAVASCRIPT']` eingebunden. Dadurch steht der LSJS-Core auch im
  Contao-Backend zur Verfügung.

---

## 7. Datenbank und Migration

- Die Felder gehören zu `tl_layout`; ihr SQL ist im DCA definiert (`char(1)` für die
  Checkboxen, `blob NULL` für die Auswahl-Felder). Das Schema wird über den DCA-Updater
  gepflegt (keine separate DDL-Migration).
- `CoreAndAppPathMigration` (`contao.migration`, `priority: 0`): überführt die alte
  Feldstruktur (Einzelfelder `lsjs4c_coreCustomizationToLoad[TextPath]`,
  `lsjs4c_appCustomizationToLoad[TextPath]`, `lsjs4c_appToLoad[TextPath]`) in die neuen
  Blob-Felder `lsjs4c_coreCustomizationsToLoad` und `lsjs4c_appsToLoad` (serialisierte
  Pfad-Arrays), wandelt dabei File-IDs in `files/`-Pfade um und entfernt anschließend die
  Altspalten. Sie läuft nur, wenn die Altfelder mit Daten vorhanden sind und die Neufelder
  fehlen -- relevant beim Upgrade auf die Multi-Auswahl (CHANGELOG `v3.0.4`).

---

## 8. Service-Verdrahtung (`services.yml`)

- `_defaults`: `autowire: true`, `autoconfigure: true`, `public: false`.
- Explizite Registrierung mit explizit gesetzten, verhaltenskritischen Tags:
  - `GetPageLayoutListener` -- `contao.hook`, `getPageLayout`.
  - `GeneratePageListener` -- `contao.hook`, `generatePage`.
  - `LsjsInsertionSubscriber` -- `kernel.event_subscriber` (Argumente u. a.
    `@contao.framework`, `@contao.routing.scope_matcher`,
    `@contao.security.token_checker`, `%contao.web_dir%`, `%kernel.project_dir%`).
  - `CoreAndAppPathMigration` -- `contao.migration`, `priority: 0`.
- Das entspricht der Service-Policy (explizite Tags statt Verlass auf `autoconfigure`) aus
  dem Skill `contao-development`.

---

## 9. Auffindungs- und Namenskonventionen

- **Apps**: Verzeichnisse mit Namenspräfix `lsjs-app` unter `files/` oder `vendor/`
  (Tiefe >= 1) werden automatisch zur Auswahl angeboten.
- **Core-Customizations**: analog Verzeichnisse mit Präfix `lsjs-core`.
- Damit werden theme- und projekteigene Apps ohne weitere Registrierung auswählbar; die
  Theme-QUICKREF (Abschnitt 6) liefert Custom-JS folgerichtig als
  `lsjs-app-customCode-*`.
- Abgrenzung: LSJS4C **findet und kompiliert** nur. Ob ein App-Modul zur Laufzeit greift,
  hängt von LSJS-internen Konventionen ab (z. B. Modulname-Präfix `customCode`) -- siehe
  LSJS-QUICKREF.

---

## 10. Querverweise und Geltungsbereich

Diese QUICKREF beschreibt die **Contao-Integration**. Die zugrunde liegenden Mechaniken
liegen in eigenen Quellen:

- LSJS-Framework, Binder/Kompilierung und `customCode`/`libraryLoader`:
  `assets/lsjs/QUICKREF_AI.md` (Hauptprojekt). LSJS ist host-unabhängig.
- Einbettung von Custom-JS in einem Theme: Theme-QUICKREF, Abschnitt 6.
- Allgemeine Contao-Standards (Services, DCA, Migrations): Skill `contao-development`.
- Abhängige Pakete: `leadingsystems/lsjs` (Framework/Binder),
  `leadingsystems/contao-helpers`.

Bewusst ausgeklammert: die interne Mechanik von LSJS (Kompilierung, Modul-Lifecycle).
Bei Widerspruch zwischen dieser Datei und den genannten Quellen gilt die jeweils
spezialisierte Quelle.
