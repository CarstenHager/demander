**Aktueller Stand: Product Manager Beta 4 / Demander Beta 2.** Systematische Prüfung fehlender Array-Schlüssel und optionaler Konfiguration; siehe TYPO3-12-BETA4.md. Die unten aufgeführten älteren Prüfergebnisse sind historisch; der aktuelle Prüflauf steht im Beta-4-Bericht.

**Beta 3:** Zusätzlich Warnung bei fehlenden renderingStacks und PHP-8-Konvertierung von false zu Array im RenderMultipleViewHelper korrigiert. Siehe TYPO3-12-BETA3.md.

**Aktualisierung Beta 2:** Siehe `TYPO3-12-BETA2.md` für Controller-Fix und Korrektur des MM-Primärschlüssels.

# Demander und Product Manager für TYPO3 12.4 — Demander Beta 2 / Product Manager Beta 4

Stand: 08.10.2026. Individueller Port auf Basis der bereitgestellten Entwicklungsstände; kein offizielles Pixelant-Release. Ziel ist ausschließlich TYPO3 12.4, nicht TYPO3 13.

## Pakete

| Extension | Extension-Manager-Version | Composer-Version | Status |
|---|---|---|---|
| demander | 0.3.1 | 0.3.1-beta2 | Beta |
| pxa_product_manager | 12.0.1 | 12.0.1-beta4 | Beta |

Voraussetzungen: TYPO3 12.4 und PHP 8.1–8.3; Product Manager benötigt Demander aus diesem Paket sowie PHP intl. Die Laufzeittests wurden mit TYPO3 **12.4.37**, PHP **8.3.6** und SQLite durchgeführt. PHP 8.1/8.2 und andere TYPO3-Patchstände wurden nicht separat ausgeführt. Der Core-Patchstand ist eine Testbasis, keine Empfehlung zur Wahl des produktiven Patchstands.

## Installation ohne Composer

1. Auf einer Kopie der bestehenden Installation beginnen; Datenbank und bisherige Extension-Verzeichnisse sichern.
2. Beide ZIPs entpacken. Die enthaltenen Verzeichnisse gehören nach `typo3conf/ext/demander/` und `typo3conf/ext/pxa_product_manager/`. Vorhandene Extension-Verzeichnisse vollständig ersetzen, nicht lediglich Dateien darüberkopieren.
3. Demander vor Product Manager aktivieren. Keine zweite Kopie derselben Extension parallel aktiv halten.
4. TYPO3-Datenbankanalyse ausführen. Vorgeschlagene Löschungen separat prüfen; keine pauschale Entfernung vorhandener Produktdaten.
5. Klasseninformationen und alle Caches neu aufbauen. Falls eine CLI verfügbar ist: `typo3/sysext/core/bin/typo3 dumpautoload`, danach `cache:flush` und `cache:warmup`.
6. Das statische Product-Manager-TypoScript weiterhin einbinden. Für die mitgelieferten HTML-Templates muss die übliche RTE-Konfiguration, z. B. aus `fluid_styled_content`, vorhanden sein.
7. Detailseitenzuordnung am Produkt prüfen. Alternativ wird `plugin.tx_pxaproductmanager.settings.pids.singleViewPid` bzw. `pxapm_singleViewPid` in der Site-Konfiguration verwendet. Auf der Zielseite muss das passende Detail-Plugin liegen; normale Seiten verwenden ProductShow, Produkt-Anzeigeseiten mit Doktype 9 ProductRender.

## Installation mit Composer

Beide Verzeichnisse beispielsweise nach `packages/demander` und `packages/pxa_product_manager` entpacken und im **Projekt** ein Path-Repository ergänzen:

```json
{
  "repositories": [
    {"type": "path", "url": "packages/*", "options": {"symlink": false}}
  ],
  "require": {
    "pixelant/demander": "0.3.1-beta2",
    "pixelant/pxa-product-manager": "12.0.1-beta4"
  }
}
```

Diese Einträge mit der vorhandenen Projektdatei zusammenführen; die Projektdatei nicht durch das Beispiel ersetzen. Anschließend die beiden Pakete mit ihren erforderlichen Abhängigkeiten aktualisieren, Datenbankanalyse und Cache-Aufbau ausführen. Die Versionsangaben in den Paketdateien sind für die lokalen ZIP-/Path-Pakete bewusst enthalten; es wurde nichts auf Packagist oder TER veröffentlicht.

## Wesentliche Änderungen

- Entfernte Extbase-ObjectManager-Nutzung durch TYPO3 Dependency Injection bzw. GeneralUtility ersetzt; Service-Sichtbarkeit und Interface-Aliase ergänzt.
- Controller-Aktionen auf PSR-7-Antworten und internes Weiterleiten auf ForwardResponse umgestellt.
- Signal/Slot-Verwendung durch PSR-14-Ereignisse ersetzt. Projektspezifische alte Slot-Anbindungen müssen auf die neuen Ereignisse umgestellt werden; unter anderem `Pixelant\PxaProductManager\Event\Controller\AfterDemandCreationEvent`.
- Doctrine-DBAL-Abfragen und Ergebnisverarbeitung angepasst; vollständiges Extbase-Produktmapping korrigiert.
- Kategorie-UND-Filter, Attributfilter und Unterabfragen für verfügbare Filteroptionen korrigiert.
- Demander verarbeitet GET/POST, verschachtelte Bedingungen und Dezimalwerte; Sortierung wird gegen konfigurierte Felder/Richtungen geprüft. Request-Werte können keine SQL-Konfiguration überschreiben. Werte werden über die aktive DBAL-Verbindung quotiert.
- Dynamische Attribut-TCA wird nach dem Bootstrap aufgebaut, damit die TCA-/Container-Kompilierung keine unerlaubten Datenbankzugriffe ausführt.
- FlexForms, Attributtypen und Kategorie-Konfiguration an TYPO3 12 angepasst.
- Eigener Seitentyp über PageDoktypeRegistry registriert; Backend-Header und Linkvorschau auf PSR-14 umgestellt.
- Linkbrowser auf TYPO3-12-RecordList und Seitenbaum-Webkomponente angepasst; benötigte Backend-JavaScripts als ES-Module registriert.
- Preisformatierung an TYPO3s Locale-Objekt angepasst; Produktlinkerzeugung und Fallback auf konfigurierte Detailseiten korrigiert.
- CLI-Befehle liefern reguläre Exit-Codes. Die Vererbung initialisiert den benötigten Backend-Kontext und verarbeitet FormData auch ohne Backend-Route; DataHandler-Fehler führen zu einem Fehlerstatus und der betreffende Queue-Eintrag bleibt erhalten.
- Der zusätzliche Fork-Commit `e74b7fdfa72b7f67017ba5d5695bc88f751e259b` wurde als Vergleich herangezogen, nicht ungeprüft vollständig übernommen.

## Frühere Prüfungen des Grundports (Beta 1)

- PHP-Syntaxprüfung: **223 Dateien**, keine Syntaxfehler.
- JSON, YAML, XML und XLIFF geparst; die zwei neuen Backend-ES-Module auf JavaScript-Syntax geprüft.
- Beide Composer-Dateien anhand des Schemas validiert. Hinweis zur expliziten Paketversion, keine Schemafehler.
- PSR-12-Prüfung der Klassen: **0 Fehler**, **28 Warnungen zur Zeilenlänge** im vorangegangenen Prüflauf. Anschließend erfolgten noch kleine CLI-Anpassungen und eine erneute PHP-Syntaxprüfung; kein abschließender erneuter PHPCS-Lauf.
- TYPO3-Autoload, Dependency-Injection-Container, Cache-Aufbau und Datenbankschema in einer echten TYPO3-Testinstallation ausgeführt.
- Demander/Repository-Tests: Sichtbarkeit versteckter und zukünftiger Produkte, Kategorie-UND, Attribut-UND, Apostrophe und SQL-artige Filterwerte, Dezimalbereiche, verschachteltes ODER, GET/POST-Priorität, fehlende Parameter, Zählung und verfügbare Filteroptionen, vollständige Extbase-Zuordnung, dynamische TCA, Middleware-Aufräumen bei Exceptions, erlaubte/unerlaubte Sortierung und zusätzliche ODER-Bedingungen.
- Backend: Speichern mit DataHandler, Vererbung auf Unterprodukte, FormData-Kompilierung für alle zehn Attributtypen, serverseitige Linkbrowser-Ausgabe und PSR-14-Linkvorschau.
- HTTP: Produktlisten-HTML, Produktdetail-HTML, Produkt-JSON und Filter-JSON; jeweils HTTP 200 und geprüfte Inhalte.
- CLI: Start/Ausführung aller vier Befehle mit Testdaten bzw. leeren Ergebnismengen. Zusätzlich echte Queue-Verarbeitung: Unterproduktpreis von 9 auf den Elternproduktpreis 31,25 geändert.
- Mehrere Prüf- und Korrekturrunden; der abschließende Lauf von PHP-/Konfigurationsprüfung, Demander-/Backend-Tests und HTTP-Prüfungen war erfolgreich.

## Frühere Scannerbefunde und weiterhin geltende Grenzen

Der TYPO3-12-Extension-Scanner hat zuletzt **191 PHP-Dateien** außerhalb der historischen Tests untersucht und **30 Kandidaten** gemeldet: 9 starke und 21 schwache Treffer. Die starken Treffer betreffen noch in TYPO3 12 vorhandene, aber veraltete APIs: sieben Aufrufe von `getFileFieldTCAConfig`, `BackendWorkspaceRestriction` sowie die verbliebene alte BrowserTreeView-Klasse, die der neue Linkbrowser nicht mehr verwendet. Schwache Treffer sind teils Namensüberschneidungen, teils weitere Deprecations wie `getTreeList`. Das Paket ist daher **nicht deprecation-frei und nicht für TYPO3 13 freigegeben**. Die behobenen entfernten APIs wurden durch Laufzeittests ergänzt; ein Scanner allein beweist keine vollständige Kompatibilität.

Solr wurde auf ausdrücklichen Wunsch aus dem Prüfumfang ausgeschlossen. Vorhandener Solr-Code bleibt erhalten.

Nicht vollständig geprüft: MySQL/MariaDB/PostgreSQL, Upload/Bildverarbeitung mit realen Dateien, mehrsprachige Inhalte, Workspaces und spezielle Redakteursrechte, Sitemap-Ausgabe, Importer, sämtliche projektspezifischen Templates/Events sowie interaktive Browser-Bedienung der JavaScripts. Die neue Linkbrowser-Ausgabe wurde serverseitig geprüft, nicht durch einen vollständigen Klicktest im Browser.

Die historischen PHPUnit-/Nimut-Testdateien und ihre Entwicklungswerkzeuge sind mitgeliefert, aber nicht vollständig auf einen neuen Test-Framework-Stand portiert oder als Testsuite ausgeführt. Die oben beschriebenen Funktionstests waren separate Prüfungen in der TYPO3-Testinstallation. Insbesondere der schon zuvor als WIP bezeichnete Duplikat-Reparaturbefehl wurde nicht als allgemeines Datenbereinigungswerkzeug freigegeben; getestet wurde sein Start ohne zu bereinigende Duplikate.

Die registrierten ProductList-, ProductShow-, ProductRender- und CustomProductList-Plugins bleiben erhalten. Historische, nicht registrierte Controller wie CategoryController sowie individuelle Alt-Registrierungen sind nicht Teil der Funktionsfreigabe.

Vor dem Live-Wechsel sind deshalb ein Test mit einer Kopie deiner Datenbank und ein Abnahmelauf mit deinen Templates erforderlich. Die Pakete sind als nachvollziehbar geprüfte **Beta für die Testinstallation** gedacht.
