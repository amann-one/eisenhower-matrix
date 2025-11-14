# Internationalisierung (i18n) - Anleitung

## Übersicht

Das Eisenhower Matrix Plugin ist jetzt vollständig übersetzbar! Alle Texte im Plugin verwenden WordPress-Übersetzungsfunktionen.

## Ordnerstruktur

```
eisenhower-matrix/
├── languages/
│   ├── eisenhower-matrix.pot           (Template-Datei)
│   ├── eisenhower-matrix-de_DE.po      (Deutsche Übersetzung)
│   ├── eisenhower-matrix-de_DE.mo      (Kompilierte deutsche Übersetzung)
│   ├── eisenhower-matrix-en_US.po      (Englische Übersetzung)
│   └── eisenhower-matrix-en_US.mo      (Kompilierte englische Übersetzung)
```

## Neue Dateien

### 1. includes/class-i18n.php
Lädt die Übersetzungsdateien beim Plugin-Start.

### 2. Aktualisierte Klassen
Alle Klassen verwenden jetzt Übersetzungsfunktionen:
- `class-post-type.php` - Mit `__()`
- `class-taxonomies.php` - Mit `__()`
- `class-meta-boxes.php` - Mit `esc_html_e()` und `esc_html__()`
- `class-shortcode.php` - Mit `esc_html_e()` und `esc_html__()`
- `class-drag-drop.php` - Mit `__()` und `wp_localize_script()`

### 3. Übersetzungsdateien
- `eisenhower-matrix.pot` - Template für neue Übersetzungen
- `eisenhower-matrix-de_DE.po` - Deutsche Übersetzung
- `eisenhower-matrix-en_US.po` - Englische Übersetzung

## Neue Sprache hinzufügen

### Methode 1: Mit Poedit (empfohlen)

1. **Poedit herunterladen**: https://poedit.net/
2. **POT-Datei öffnen**: `languages/eisenhower-matrix.pot`
3. **Neue Übersetzung erstellen**: Datei → Neue Übersetzung aus POT-Datei
4. **Sprache wählen**: z.B. Französisch (fr_FR)
5. **Übersetzen**: Alle Texte übersetzen
6. **Speichern**: Als `eisenhower-matrix-fr_FR.po`
7. **MO-Datei**: Wird automatisch erstellt (`eisenhower-matrix-fr_FR.mo`)

### Methode 2: Mit Loco Translate Plugin

1. **Plugin installieren**: Loco Translate im WordPress-Backend
2. **Navigation**: Loco Translate → Plugins → Eisenhower Matrix
3. **Neue Sprache**: "New Language" klicken
4. **Locale wählen**: z.B. `fr_FR` für Französisch
5. **Übersetzen**: Im Editor alle Texte übersetzen
6. **Speichern**: Automatisch als PO und MO

### Methode 3: Manuell

1. **PO-Datei kopieren**: 
   ```bash
   cp languages/eisenhower-matrix-de_DE.po languages/eisenhower-matrix-fr_FR.po
   ```

2. **Header anpassen**:
   ```
   "Language: fr_FR\n"
   "Language-Team: French\n"
   ```

3. **Texte übersetzen**: Alle `msgstr ""` mit Übersetzungen füllen

4. **MO-Datei kompilieren**:
   ```bash
   msgfmt -o eisenhower-matrix-fr_FR.mo eisenhower-matrix-fr_FR.po
   ```

## Verwendete Übersetzungsfunktionen

### In PHP

```php
// Einfache Übersetzung (gibt String zurück)
__('Text', 'eisenhower-matrix')

// Übersetzung ausgeben
_e('Text', 'eisenhower-matrix')

// Übersetzung + HTML-Escape (gibt String zurück)
esc_html__('Text', 'eisenhower-matrix')

// Übersetzung + HTML-Escape ausgeben
esc_html_e('Text', 'eisenhower-matrix')

// Übersetzung + Attribut-Escape
esc_attr__('Text', 'eisenhower-matrix')
```

### In JavaScript

Texte werden via `wp_localize_script()` übergeben:

```javascript
// In PHP
wp_localize_script('em-drag-drop', 'emDragDrop', array(
    'messages' => array(
        'success' => __('Erfolgreich!', 'eisenhower-matrix')
    )
));

// In JavaScript
console.log(emDragDrop.messages.success);
```

## Text Domain

**Wichtig**: Überall im Plugin wird die Text Domain `'eisenhower-matrix'` verwendet.

## POT-Datei aktualisieren

Wenn neue Texte hinzugefügt wurden:

### Mit WP-CLI

```bash
wp i18n make-pot . languages/eisenhower-matrix.pot
```

### Mit Poedit

1. POT-Datei öffnen
2. Katalog → Aus Quellen aktualisieren
3. Speichern

### Manuell

Neue `msgid` Einträge zur POT-Datei hinzufügen:

```
#: includes/neue-datei.php
msgid "Neuer Text"
msgstr ""
```

## Unterstützte Sprachen

Aktuell:
- ✅ Deutsch (de_DE)
- ✅ Englisch (en_US)

Einfach hinzuzufügen:
- Französisch (fr_FR)
- Spanisch (es_ES)
- Italienisch (it_IT)
- usw.

## Testen

1. **WordPress-Sprache ändern**: 
   - Einstellungen → Allgemein → Sprache der Website

2. **Plugin neu laden**:
   - Plugin deaktivieren und wieder aktivieren

3. **Cache leeren**:
   - Bei aktiviertem Caching-Plugin

4. **Prüfen**:
   - Alle Texte sollten in der gewählten Sprache erscheinen

## Troubleshooting

### Übersetzungen werden nicht angezeigt

1. **MO-Datei vorhanden?**
   ```bash
   ls -la languages/
   ```

2. **Dateirechte prüfen**
   ```bash
   chmod 644 languages/*.mo
   chmod 644 languages/*.po
   ```

3. **Cache leeren**
   - WordPress Object Cache
   - Browser Cache

4. **Debug aktivieren**
   ```php
   // In wp-config.php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```

### Neue Texte fehlen

1. POT-Datei aktualisieren
2. PO-Dateien mit neuer POT synchronisieren
3. Neue Texte übersetzen
4. MO-Dateien neu kompilieren

## Best Practices

✅ **Immer Text Domain angeben**: `'eisenhower-matrix'`
✅ **Keine HTML in Übersetzungen**: HTML außerhalb halten
✅ **Kontext nutzen**: Bei mehrdeutigen Begriffen
✅ **Platzhalter verwenden**: `sprintf(__('Hallo %s', 'eisenhower-matrix'), $name)`
✅ **Pluralformen**: `_n()` für Singular/Plural

❌ **Niemals**: Texte direkt concatenieren
❌ **Niemals**: Variablen in Übersetzungsfunktionen
❌ **Niemals**: HTML in `msgid`

## Weitere Ressourcen

- [WordPress i18n Handbook](https://developer.wordpress.org/plugins/internationalization/)
- [Poedit](https://poedit.net/)
- [Loco Translate](https://wordpress.org/plugins/loco-translate/)
- [GlotPress](https://wordpress.org/plugins/glotpress/)
