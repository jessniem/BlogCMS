# BlogCMS

Skoluppgift:
Vi valde att bygga en blogg åt illustratören Angelina Fredriksson för att få joba med riktig kundfeedback och ha en målbild att utgå ifrån.

Mål:
Målet med uppgiften att den studerande ska:

Kunna arbeta i projekt med hjälp av agila metoder
Kunna arbeta i projekt med hjälp av versionshantering
Få en fördjupad kunskap om PHP-utveckling
Genomföra ett projekt från start till mål med planering
Kunna ta fram en databasdesign för en större uppgift
Kunna göra ett tidsestimat och jämföra med den faktiska tidsåtgången

Metod
De studerande ska lämna in:

Påskriver gruppkontakt - finns i klassrummet. Gör detta innan ni sätter igång med arbetet.
Projektplanering
Tidsestimat och utfall
Wireframes för mobil, tablet och desktop
Projektfiler
Databasdesign
Projektet uppladdat på ett webbhotell
Uppgiftsbeskrivning
Projektuppgiften är att göra en blogg och att tillämpa principer för agila metoder och UX i genomförandet av projektet. Dvs. arbetsmetodiken ska följa principerna som ni gått igenom i agila metoder-kursen och er design (inkl. wireframes) ska vara anpassade såväl utifrån ett UX-perspektiv som tillgänglighetsperspektiv.


Krav på innehåll för G:
Bloggen ska kunna ha ett obegränsat antal inlägg
Till varje inlägg ska besökare på sidan kunna lägga kommentarer. En kommentar består av datum, text, namn på avsändaren, avsändarens e-post samt webbsida.
Man ska kunna logga in på bloggen och då bli en inloggad användare.
En inloggad användare ska kunna skapa nya, redigera samt ta bort inlägg i bloggen.
Ett inlägg ska bestå av rubrik, datum, text, författarens namn, författarens e-post samt webbsida. Dessa uppgifter ska kopplas ihop via databasen och ID:n, och inte hårdkodas in för varje inlägg.
En inloggad användare ska kunna få ut följande statistik:
Hur många blogginlägg man har totalt på bloggen
Hur många kommentarer man har totalt på bloggen
Hur många kommentarer det finns i snitt på varje inlägg
Varje inlägg ska kunna tillhöra en specifik kategori. Kategorierna ska vara förutbestämda och behöver inte gå att redigera.
En besökare på sidan ska kunna visa enbart inlägg inom en viss kategori.
I frontend ska inläggen visas med det senaste inlägget högst upp.
Under varje inlägg ska det dessutom finnas en länk som lyder "kommentera". När man klickar på länken ska man komma till en sida som visar inlägget man vill kommentera samt ett kommentarsformulär.
Koden ska ha en korrekt struktur, såväl HTML, Sass som PHP.
Det ska finnas länkar till de övriga sidorna genom att använda include-funktionen i menyn.

Krav på innehåll för VG
Utöver kraven för G gäller:
Koden ska hålla mycket god kvalitet och vara väldokumenterad enligt principer för koddokumentation (t.ex. funktionskommentarer)
Under varje inlägg ska det finnas en länk som lyder "Visa kommentarer (X)" där X ersätts med antalet kommentarer som inlägget har.
På startsidan ska bara de fem senaste inläggen visas. Under och över listan med inlägg ska det vara möjligt att bläddra mellan sidorna (paginering).
När man är inloggad och skriver ett inlägg ska man kunna spara det som utkast, dvs. det ska inte synas på startsidan.
Som inloggad användare ska man bara kunna radera sina egna inlägg och kommentarer som hör till inlägg som man själv har gjort. Det ska finnas en super-administratör som kan radera alla inlägg och kommentarer.
På bloggen ska en besökare kunna visa alla inlägg från en viss månad i en lista. I den listan ska enbart månader som har inlägg visas.
Kommentarsfält ska valideras med hjälp av regex. Primärt e-postadressen, dvs. kommentaren ska inte sättas in i databasen om man inte har skrivit en korrekt utformad e-postadress.
Kategoriarkiv ska gå att sortera på datum, stigande eller fallande, genom att klicka på en knapp.
Tekniska krav
En del av dessa krav är från projektarbet i HTML- & CSS-kursen, för att ni ska få koll på dem samt i viss mån repetera och integrera.

Använda såväl floats som flexbox i designen
Använda en CSS3-animeringsteknik
Använda pseudoklasser
Använda Noramlize eller Reset
Använda ett CSS-ramverk, eller motivera varför man inte har valt det - båda går lika bra
Sidan ska vara testad och fungera i samtliga stora webbläsare
Sidan ska vara validerad
Sidan ska vara tillgänglighetsanpassad
Sidan ska vara sökmotoroptimerad utifrån taggstruktur
Sass
Koden ska vara väldokumenterad
Koden ska vara prydligt skriven och korrekt tabbad
Koden ska vara lättläst och konsekvent
Ni ska använda Git för att versionshantera koden
Grundläggande säkerhet ska vara inbyggt i systemet. T.ex. ska man inte kunna redigera ett inlägg om man inte är inloggad.
