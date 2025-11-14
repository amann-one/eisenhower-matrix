# Responsive Design Guide - Eisenhower Matrix

## Übersicht

Das Eisenhower Matrix Plugin ist jetzt vollständig responsive und für alle Bildschirmgrößen optimiert.

## Breakpoints

Das Plugin verwendet folgende Breakpoints:

### Desktop
- **> 1024px**: Volle Desktop-Ansicht mit 2-Spalten-Grid

### Tablet
- **768px - 1024px**: Optimierte Tablet-Ansicht
  - Reduzierte Abstände
  - Angepasste Schriftgrößen
  - 2-Spalten-Grid

### Mobile (Portrait)
- **< 768px**: Einspaltiges Layout
  - Vertikales Stapeln der Quadranten
  - Touch-optimierte Elemente (min. 44px Höhe)
  - Größere Tippflächen
  - Kompaktere Darstellung

### Mobile (Landscape)
- **< 900px im Querformat**: 2-Spalten-Layout
  - Optimiert für Landscape-Modus
  - Reduzierte Höhen

### Kleine Smartphones
- **< 480px**: Extra-kompakte Darstellung
  - Minimale Abstände
  - Kleinere Schriften
  - Optimierte Touch-Targets

## Responsive Features

### ✅ Adaptive Layout
- Desktop: 2 Spalten (2x2 Grid)
- Tablet: 2 Spalten (optimiert)
- Mobile Portrait: 1 Spalte (vertikal gestapelt)
- Mobile Landscape: 2 Spalten (kompakt)

### ✅ Touch-Optimierung
- Mindest-Touch-Target: 44x44px (Apple HIG)
- Drag-Indikator immer sichtbar auf Touch-Geräten
- Touch-Feedback bei Interaktion
- Keine Hover-Effekte auf reinen Touch-Geräten

### ✅ Typografie
- Fluid Font Sizes
- Lesbare Schriftgrößen auf allen Geräten
- Desktop: 1.4rem Headlines
- Tablet: 1.3rem Headlines
- Mobile: 1.1-1.2rem Headlines
- Kleine Smartphones: 1.1rem Headlines

### ✅ Spacing
- Proportionale Abstände für alle Bildschirmgrößen
- Desktop: 1.5rem Grid-Gap
- Tablet: 1.25rem Grid-Gap
- Mobile: 1rem Grid-Gap
- Klein: 0.75rem Grid-Gap

### ✅ Interaktionen
- Drag & Drop funktioniert auf Touch-Geräten
- Touch-optimierte Benachrichtigungen
- Volle Breite Notifications auf Mobile
- Reduzierte Animationen bei Bedarf

### ✅ Accessibility
- `prefers-reduced-motion` Support
- Semantisches HTML
- Touch-Target-Größen nach WCAG
-