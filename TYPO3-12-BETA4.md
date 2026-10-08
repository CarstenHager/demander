# TYPO3 12.4 – Product Manager Beta 4 / Demander Beta 2

Stand: 08.10.2026. Individueller Port; kein offizielles Pixelant-Release.

## Anlass und Korrektur

Die bisherigen Tests verwendeten überwiegend vollständige TypoScript-Konfigurationen. Deshalb wurden die Warnungen bei fehlendem customProductsList, renderingStacks und listView nicht alle vor der Auslieferung erkannt. Diese Prüfung wurde jetzt um fehlende, leere und teilweise konfigurierte Einstellungen erweitert.

Die erneute Quellcode-Durchsicht umfasste Array-Zugriffe in den Klassen beider Extensions: Controller, Ressourcen, ViewHelper, Datenverarbeitung, Repository-Filter, Backend-Konfiguration, Hooks, Vererbungswerkzeuge und Demander. Verpflichtende, vom TYPO3-Core bereitgestellte Datenstrukturen und obligatorische ViewHelper-Argumente wurden nicht pauschal durch leere Werte ersetzt. Solr bleibt wie vereinbart außerhalb des Prüfumfangs.

Behobene Fehlerklassen:
- Fehlende listView-, filtering-, productOrderings-, Kategorien- und Lazy-Loading-Einstellungen; definierte Standardwerte für Limit (12), Sortierung (name/asc) und Filterverknüpfung (and). Explizite gültige Einstellungen bleiben erhalten.
- Fehlende oder unvollständige Sortieroptionen, Rendering-Stacks und Produktattribute; fehlende Bildkonfiguration und optionaler Template-Layout-Wert.
- Fehlende Menüdaten, FlexForm-Teilstrukturen und Backend-AJAX-Eingaben.
- Unvollständige TCA-Auswahllisten, Attributsets und Kategorien-Seitenrelationen.
- Unvollständige Filterobjekte: ungültige Filter ergeben keine Treffer; fehlende optionale Verknüpfung verwendet AND. Leere SQL-Bedingungsgruppen werden nicht angehängt.
- Produktvererbung: fehlende Typ-/Produkt-/Befehlsdaten und synthetische Attributfelder; Übergabe temporärer Ausdrücke an Referenzfunktionen korrigiert. DataHandler bekommt bei fehlender cmd- oder data-Gruppe ein leeres Array.
- FormDataCompiler verwendet die TYPO3-12-Aufrufsignatur; obsolete dynamische thisScript-Eigenschaft am Linkbrowser entfernt.
- Demander: fehlende select-items, Demand-Provider-Konfiguration und unvollständige Eigenschaftsbezeichnungen.

Die bisherigen Korrekturen für customProductsList, renderingStacks und den MM-Primärschlüssel sind enthalten.

## Aktuell ausgeführte Prüfungen

Testumgebung: tatsächlicher TYPO3-Core **12.4.37**, PHP **8.3.6**, SQLite. Keine Verbindung zur produktiven Datenbank des Nutzers.

| Prüfung | Ergebnis |
|---|---|
| PHP-Syntax sämtlicher 223 PHP-Dateien beider Pakete | bestanden |
| JSON, XML/XLIFF, YAML und zwei neue Backend-JavaScript-Module | bestanden |
| Repository/Demander einschließlich ungültiger Filterdaten | 19 Prüfungen bestanden |
| Backend-Speichern, Eltern-Kind-Vererbung, zehn Attributtypen, Linkbrowser und PSR-14-Vorschau | 14 Prüfungen bestanden |
| Regressionen customProductsList und MM-Schlüssel | 3 Prüfungen bestanden |
| Regressionen renderingStacks | 4 Prüfungen bestanden |
| Fehlende/partielle Einstellungen, Backend-Daten und Demander-Konfiguration | 35 Prüfungen bestanden |
| Vier registrierte Frontend-Plugins mit vollständigen und entfernten Einstellungen/Stacks sowie beide JSON-Endpunkte ohne Einstellungen | 10 HTTP-Prüfungen bestanden |
| CLI-Vererbung: Kinderpreis 9 auf Elternpreis 31,25 aktualisiert | bestanden |
| ZIP-CRC und byteweiser Vergleich zum Arbeitsstand | bestanden |

Damit wurden **86 konkrete Prüfungen** zusätzlich zu den Syntax- und Formatprüfungen erfolgreich ausgeführt. Bei den gezielten PHP-Regressionstests führen PHP-Warnings und Notices zu Testfehlern. Bei den HTTP-Prüfungen behandelt die Testinstallation diese Meldungen als Ausnahmen. Deprecations sind davon getrennt: Das Paket enthält weiterhin in TYPO3 12 erlaubte, für spätere Versionen veraltete APIs, insbesondere getFileFieldTCAConfig.

Die älteren Scanner- und PHPCS-Zahlen im allgemeinen README stammen aus dem Grundport. Diese beiden Werkzeuge wurden für Beta 4 nicht erneut ausgeführt; sie werden ausdrücklich nicht als neuer erfolgreicher Prüflauf ausgegeben.

## Aktualisieren und Daten erhalten

1. Beide vorhandenen Extension-Verzeichnisse sichern und durch die vollständigen Verzeichnisse aus diesen ZIPs ersetzen. Eigene lokale Anpassungen vorher übernehmen.
2. TYPO3-Klasseninformationen und alle Caches neu aufbauen; bei klassischer Installation bei Bedarf dumpautoload, danach cache:flush und cache:warmup.
3. Bestehende TypoScript-Einbindung, Fluid-Templates und Produktseitenzuordnungen beibehalten. Der Schutz vor fehlenden Einstellungen ersetzt diese Konfiguration nicht; fehlende Rendering-Stacks können weiterhin zu fehlenden Inhaltsblöcken führen.
4. Für diese Korrekturrunde ist **keine Datenbankbereinigung und kein Löschen von Tabellen/Feldern notwendig**. ext_tables.sql ist gegenüber Product Manager Beta 3 bzw. Demander Beta 1 unverändert. Die Produkte müssen nicht neu eingepflegt werden.

Composer-Versionen: pixelant/demander 0.3.1-beta2 und pixelant/pxa-product-manager 12.0.1-beta4. Im Extension Manager bleiben die numerischen Versionen 0.3.1 bzw. 12.0.1 mit Status Beta; deshalb beide Verzeichnisse tatsächlich ersetzen.

## Grenzen

Die Tests sind deutlich breiter als zuvor, jedoch keine Garantie für sämtliche projektspezifischen Kombinationen. Keine Tests gegen die Datenbank, Templates und Zusatzextensions der Zielinstallation. MySQL/MariaDB/PostgreSQL, echte Datei-Uploads/Bildverarbeitung, Mehrsprachigkeit, Workspaces, spezielle Redakteursrechte, Importer, Sitemap und interaktive JavaScript-Bedienung wurden nicht vollständig ausgeführt. Historische nicht registrierte Controller sowie Solr sind nicht funktional freigegeben. Keine Freigabe für TYPO3 13. Ein Abnahmetest mit einer Kopie der vorhandenen V11-Daten bleibt notwendig.

## Geänderte PHP-Klassen gegenüber den zuletzt gelieferten Paketen

### pxa_product_manager: 29 Dateien

- Classes/Attributes/ConfigurationProvider/AbstractProvider.php
- Classes/Attributes/ConfigurationProvider/ConfigurationProviderFactory.php
- Classes/Attributes/ConfigurationProvider/InputProvider.php
- Classes/Attributes/ConfigurationProvider/LabelProvider.php
- Classes/Backend/FormDataProvider/AttributeValueFormDataProvider.php
- Classes/Backend/FormDataProvider/NewAttributeRelationRecordsDataProvider.php
- Classes/Command/UpdateInheritanceCommand.php
- Classes/Configuration/Flexform/StructureLoader.php
- Classes/Controller/AbstractController.php
- Classes/Controller/Api/AbstractBaseLazyLoadingController.php
- Classes/Controller/Api/LazyAvailableFiltersController.php
- Classes/Controller/Backend/AttributeIdentifierController.php
- Classes/Controller/CategoryController.php
- Classes/Controller/LazyProductController.php
- Classes/Controller/ProductDisplayController.php
- Classes/Controller/ProductRenderController.php
- Classes/DataProcessing/AddProductToMenuProcessor.php
- Classes/Domain/Repository/ProductRepository.php
- Classes/Domain/Resource/Product.php
- Classes/Hook/ItemsProcFunc/GeneralItemsProcFunc.php
- Classes/Hook/ItemsProcFunc/ProductItemsProcFunc.php
- Classes/Hook/ProcessDatamap/AttributeTypeValidationProcessDatamap.php
- Classes/Hook/ProcessDatamap/ProductInheritanceProcessDatamap.php
- Classes/LinkHandler/AbstractLinkHandler.php
- Classes/UserFunction/TCA/CategoryUserFunction.php
- Classes/Utility/DataInheritanceUtility.php
- Classes/Utility/ExtensionUtility.php
- Classes/Utility/TcaUtility.php
- Classes/ViewHelpers/RenderMultipleViewHelper.php

### demander: 3 Dateien

- Classes/Service/DemandService.php
- Classes/Utility/ConfigurationUtility.php
- Classes/Utility/DemandArrayUtility.php

