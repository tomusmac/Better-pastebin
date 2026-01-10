# 🚀 Paste System - Profesjonalna Platforma do Udostępniania Kodu

**Paste System** to zaawansowane, lekkie i bezpieczne rozwiązanie typu "Pastebin", zaprojektowane z myślą o programistach i administratorach systemów. Aplikacja łączy w sobie nowoczesny design (Ayu Dark) z potężnym backendem napisanym w czystym PHP, oferując szybkość działania bez narzutu ciężkich frameworków.

## ✨ Kluczowe Funkcjonalności

System został zbudowany z naciskiem na user experience (UX) oraz bezpieczeństwo danych.

### 🎥 Zaawansowana Obsługa Plików i Mediów
Aplikacja to nie tylko tekst. To pełnoprawna platforma do udostępniania treści multimedialnych:
*   **Wbudowany Przeglądarka PDF**: Pliki PDF są wyświetlane bezpośrednio w oknie przeglądarki, bez konieczności ich pobierania.
*   **Odtwarzacze Wideo i Audio**: Natywne wsparcie dla streamowania plików wideo (MP4, WebM) oraz audio (MP3, WAV) prosto z wklejki.
*   **Podgląd Obrazów**: Automatyczne wyświetlanie przesłanych grafik i zrzutów ekranu w wysokiej jakości.
*   **Hosting Plików**: Możliwość załączania dowolnych archiwów (ZIP, RAR) i dokumentów do pobrania.

### 🛡️ Bezpieczeństwo i Prywatność
*   **Burn After Reading (Spal po przeczytaniu)**: Unikalna funkcja pozwalająca na tworzenie jednorazowych linków. Po pierwszym otwarciu wklejka jest **trwale usuwana** z bazy danych (rekordy) oraz dysku serwera (załączniki). Idealne do przesyłania haseł i kluczy API.
*   **Szyfrowanie Wklejek**: Każda wklejka może zostać zabezpieczona indywidualnym hasłem. Treść jest dostępna tylko dla osób znających hasło.
*   **Site-Lock**: Możliwość założenia hasła na całą instancję serwisu (Private Mode), aby dostęp mieli tylko autoryzowani członkowie zespołu.  

### 💻 Nowoczesny Interfejs
*   **Motyw Ayu Dark**: Starannie dobrana paleta kolorów zmniejszająca zmęczenie oczu, inspirowana popularnym motywem edytorów kodu.
*   **Responsywność (RWD)**: Interfejs w pełni dostosowany do urządzeń mobilnych, tabletów i desktopów.
*   **Drag & Drop**: Intuicyjny system przesyłania plików – wystarczy przeciągnąć plik PDF, obrazek czy tekst na obszar edytora.

### ⚙️ Funkcje Edytora i Przeglądania
*   **Podświetlanie Składni**: Automatyczne wykrywanie i kolorowanie składni dla dziesiątek języków programowania.
*   **Niestandardowe Linki (Slugi)**: Użytkownik może zdefiniować własną końcówkę URLa (np. `twoja-domena.pl/prezentacja-v1`), zamiast losowego ciągu znaków.
*   **Surowy Podgląd (Raw Mode)**: Dostęp do czystego tekstu bez stylów CSS.
*   **Wersjonowanie Czasowe**: Ustawianie czasu wygasania wklejek (od 10 minut do wieczności).

---

## 🔧 Panel Administratora

Platforma wyposażona jest w rozbudowany panel zarządzania (`/admin.php`), który daje pełną kontrolę nad instancją:

1.  **Dashboard Statystyk**:
    *   Monitorowanie całkowitej liczby wklejek w systemie.
    *   Podgląd całkowitego rozmiaru bazy danych i plików na dysku.
    *   Analiza popularności serwisu (licznik wyświetleń).

2.  **Zarządzanie Treścią (CRUD)**:
    *   Przegląd wszystkich aktywnych wklejek w formie tabelarycznej.
    *   Sortowanie danych po dacie utworzenia, rozmiarze, tytule czy dacie wygaśnięcia.
    *   **Moderacja**: Możliwość natychmiastowego usunięcia dowolnej wklejki, która narusza regulamin lub jest niepożądana. Usunięcie jest definitywne (rekord DB + plik).

3.  **Bezpieczeństwo Panelu**:
    *   Panel chroniony jest niezależnym hasłem administracyjnym, oddzielonym od hasła dostępu do samej strony("Site Password").

---

## 🛠️ Wymagania Techniczne

Aplikacja jest wysoce kompatybilna i działa na większości standardowych hostingów współdzielonych oraz VPS.

*   **PHP**: Wersja 7.4 lub nowsza.
*   **Baza danych**: MySQL lub MariaDB.
*   **Serwer**: Apache (z `mod_rewrite`) lub Nginx.
*   **Rozszerzenia PHP**: `pdo`, `pdo_mysql`.

---

## 📦 Instalacja Krok po Kroku

### 1. Pobranie plików
Sklonuj repozytorium do katalogu publicznego swojego serwera WWW:
```bash
git clone https://github.com/twoj-user/paste.git
cd paste
```

### 2. Przygotowanie Bazy Danych
Utwórz nową bazę danych i zaimportuj strukturę tabel. Możesz to zrobić przez phpMyAdmin lub wiersz poleceń:
```bash
mysql -u uzytkownik -p nazwa_bazy < sql/schema.sql
```

### 3. Konfiguracja Aplikacji
Skopiuj lub edytuj plik `app/config.php` i wprowadź swoje dane dostępowe:

```php
$config = [
    'db_host' => 'localhost',
    'db_name' => 'twoja_baza_danych',
    'db_user' => 'uzytkownik_db',
    'db_pass' => 'silne_haslo_db',
    
    'require_auth' => false,       // Czy wymagać hasła do wejścia na stronę?
    'site_password' => 'view',     // Hasło globalne (jeśli require_auth = true)
    'admin_password' => 'adminAC', // Hasło do /admin.php (ZMIEŃ TO!)
    'language' => 'pl'             // Domyślny język (pl/en)
];
```

### 4. Uprawnienia Katalogów
Aplikacja musi mieć możliwość zapisu w katalogu `uploads`:
```bash
chmod 755 uploads
```

---

## 🔌 API REST

System udostępnia proste API dla programistów, pozwalające na automatyzację tworzenia wklejek.

**Endpoint**: `POST /api.php`

| Parametr | Typ | Wymagany | Opis |
| :--- | :--- | :--- | :--- |
| `text` | string | TAK | Treść wklejki. |
| `title` | string | NIE | Tytuł wklejki. |
| `extension` | string | NIE | Rozszerzenie składni (np. `json`, `py`, `sql`). |
| `password` | string | NIE | Hasło zabezpieczające wklejkę. |
| `burn` | bool | NIE | Wartość `1` aktywuje tryb "Burn after reading". |
| `expires` | int | NIE | Czas wygasania w minutach (0 = nigdy). |

### Przykład użycia (cURL)

```bash
# Szybkie wysłanie pliku logów
cat error.log | curl -F "text=<-" -F "title=Logi serwera" http://twoja-domena.pl/api.php
```

---

## 📄 Licencja

Projekt udostępniany na licencji MIT - możesz go dowolnie modyfikować i rozpowszechniać, zachowując informację o autorach.
