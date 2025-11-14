# iOS Troubleshooting Guide

## Problem: Farben werden auf iPhone nicht angezeigt

### Mögliche Ursachen und Lösungen:

### 1. CSS Cache-Problem
**Symptom**: Alte Styles werden geladen

**Lösung**:
1. In Safari: Einstellungen → Safari → Verlauf und Websitedaten löschen
2. Alternativ: Hard Reload mit `Cmd + Shift + R` (auf Mac mit Safari)
3. WordPress Cache leeren (falls Caching-Plugin aktiv)

### 2. CSS Vendor Prefixes fehlen
**Symptom**: Gradients werden nicht gerendert

**Lösung**: Bereits implementiert in der aktualisierten CSS-Datei:
```css
.quadrant.q1 {
    background-color: #ffe5e5; /* Fallback */
    background: -webkit-linear-gradient(135deg, #fff 0%, #ffe5e5 100%);
    background: linear-gradient(135deg, #fff 0%, #ffe5e5 100%);
}
```

### 3. Border-Width zu dünn
**Symptom**: Rahmen nicht sichtbar auf Retina-Display

**Lösung**: Border-Width auf 3px erhöht (bereits implementiert)

### 4. CSS-Datei wird nicht geladen
**Symptom**: Gar keine Styles vorhanden

**Prüfung**:
1. Safari auf iPhone öffnen
2. Entwicklertools auf Mac aktivieren
3. iPhone per USB verbinden
4. Safari → Entwicklertools → [Dein iPhone] → [Dein Tab]
5. Netzwerk-Tab prüfen ob frontend.css geladen wird

**Lösung**:
- Prüfe Dateipfad in `class-assets.php`
- Prüfe Dateirechte: `chmod 644 assets/frontend.css`
- Version erhöhen um Cache zu umgehen

### 5. WordPress Theme überschreibt Styles
**Symptom**: Theme-CSS hat höhere Spezifität

**Lösung**: Erhöhe CSS-Spezifität:
```css
.eisenhower-matrix .quadrant.q1 {
    /* Styles hier */
}
```

### 6. iOS Safari Rendering-Bug
**Symptom**: Farben flackern oder erscheinen nicht

**Lösung**: Hardware-Beschleunigung forcieren:
```css
.quadrant {
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
}
```

## Debugging auf iOS

### Remote Debugging mit Safari
1. **iPhone vorbereiten**:
   - Einstellungen → Safari → Erweitert → Web-Inspektor aktivieren

2. **Mac vorbereiten**:
   - Safari → Einstellungen → Erweitert → Entwicklermenü anzeigen

3. **Verbinden**:
   - iPhone per USB verbinden
   - Auf iPhone: Safari öffnen, Seite laden
   - Auf Mac: Safari → Entwicklertools → [iPhone Name] → [Tab Name]

4. **Console prüfen**:
   - CSS-Fehler werden hier angezeigt
   - Network-Tab zeigt ob Dateien geladen werden

### Häufige iOS Safari Console-Fehler

```
Failed to load resource: net::ERR_FILE_NOT_FOUND
→ CSS-Datei wird nicht gefunden, Pfad prüfen

WebKit discarded an unload event listener
→ Normal, kann ignoriert werden

[blocked] The page at ... was blocked
→ Mixed Content (HTTP/HTTPS), alle Ressourcen über HTTPS laden
```

## Quick Fix für iOS

Falls nichts hilft, hier eine iOS-spezifische CSS-Datei:

**Erstelle**: `assets/ios-fix.css`

```css
/* iOS Safari Fixes */
.quadrant {
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-font-smoothing: antialiased;
}

/* Fallback ohne Gradients */
.quadrant.q1 {
    background-color: #ffe5e5 !important;
    border: 3px solid #e74c3c !important;
}

.quadrant.q2 {
    background-color: #e8f8f0 !important;
    border: 3px solid #27ae60 !important;
}

.quadrant.q3 {
    background-color: #fff3e0 !important;
    border: 3px solid #f39c12 !important;
}

.quadrant.q4 {
    background-color: #f5f5f5 !important;
    border: 3px solid #95a5a6 !important;
}
```

**Lade in** `class-assets.php`:

```php
public function enqueue_frontend_assets() {
    wp_enqueue_style(
        'em-frontend-css',
        EM_PLUGIN_URL . 'assets/frontend.css',
        array(),
        EM_VERSION
    );
    
    // iOS Fix
    wp_enqueue_style(
        'em-ios-fix',
        EM_PLUGIN_URL . 'assets/ios-fix.css',
        array('em-frontend-css'),
        EM_VERSION
    );
}
```

## Test-Checkliste für iOS

- [ ] Seite im Safari auf iPhone öffnen
- [ ] Cache gelöscht
- [ ] Quadranten haben farbige Rahmen (3px dick)
- [ ] Quadranten haben farbigen Hintergrund
- [ ] Überschriften in korrekter Farbe
- [ ] Drag-Indikator (⋮⋮) sichtbar
- [ ] Touch funktioniert (44px Touch-Targets)
- [ ] Landscape-Modus funktioniert
- [ ] Benachrichtigungen erscheinen

## iOS Safari Besonderheiten

### Viewport-Probleme
iOS Safari hat eine flexible URL-Leiste die Viewport-Höhe verändert:

```css
/* Verwende min-height statt height */
.quadrant {
    min-height: 200px; /* Nicht: height: 200px; */
}
```

### Touch-Delay
iOS hat 300ms Touch-Delay. Wird automatisch entfernt durch:

```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

### Scroll-Momentum
Für smooth Scrolling:

```css
.eisenhower-matrix {
    -webkit-overflow-scrolling: touch;
}
```

### Active State
Buttons brauchen explizites active styling:

```css
.task-item:active {
    background: rgba(52, 152, 219, 0.1);
}
```

## Bekannte iOS Safari Bugs

### 1. CSS Grid in iOS < 14
**Problem**: Grid-Layout funktioniert nicht

**Lösung**: Flexbox Fallback:
```css
@supports not (display: grid) {
    .matrix-grid {
        display: flex;
        flex-wrap: wrap;
    }
    .quadrant {
        flex: 0 0 calc(50% - 1rem);
    }
}
```

### 2. Position Fixed Bug
**Problem**: Fixed Elements springen beim Scrollen

**Lösung**: Bereits implementiert mit transform

### 3. Border-Radius mit Overflow
**Problem**: Border-Radius wird nicht applied

**Lösung**: Bereits im CSS enthalten

## Weitere Hilfe

Falls das Problem weiterhin besteht:

1. **Screenshot machen**: Zeigt was sichtbar ist
2. **Console-Log teilen**: Fehlermeldungen
3. **iOS Version prüfen**: Einstellungen → Allgemein → Info
4. **Safari Version prüfen**: Normalerweise = iOS Version

## Alternativen

Falls gar nichts funktioniert:

### Option 1: Einfache Farben ohne Gradients
Bearbeite `frontend.css`:
```css
.quadrant.q1 { background: #ffe5e5; }
.quadrant.q2 { background: #e8f8f0; }
.quadrant.q3 { background: #fff3e0; }
.quadrant.q4 { background: #f5f5f5; }
```

### Option 2: Nur farbige Rahmen
```css
.quadrant { background: white; }
.quadrant.q1 { border: 4px solid #e74c3c; }
.quadrant.q2 { border: 4px solid #27ae60; }
.quadrant.q3 { border: 4px solid #f39c12; }
.quadrant.q4 { border: 4px solid #95a5a6; }
```