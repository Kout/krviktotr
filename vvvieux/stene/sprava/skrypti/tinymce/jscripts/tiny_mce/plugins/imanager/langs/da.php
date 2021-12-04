<?php
	// ================================================
	// PHP image manager - iManager
	// ================================================
	// iManager Danish dialog
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: LGPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.0                   Date: 07/09/2005
	// ================================================

	//-------------------------------------------------------------------------
	// charset to be used in dialogs
	$lang_charset = 'iso-8859-1';
	// text direction for the current language to be used in dialogs
	$lang_direction = 'ltr';
	//-------------------------------------------------------------------------

	// language text data array
	// first dimension - block, second - exact phrase
	//-------------------------------------------------------------------------
	// iManager
	$lang_data = array (
		'imanager' => array (
		//-------------------------------------------------------------------------
		// common - im
		'im_001' => 'Billed manager',
		'im_002' => 'iManager',
		'im_003' => 'Menu',
		'im_004' => 'Velkommen',
		'im_005' => 'Indsæt',
		'im_006' => 'Fortryd',
		'im_007' => 'Indsæt',
		'im_008' => 'Indsæt/rediger billede',
		'im_009' => 'Egenskaber',
		'im_010' => 'Billed egenskaber',
		'im_011' => 'Værktøjer',
		'im_012' => 'Billed værktøjer',
		'im_013' => 'Popup',
		'im_014' => 'Billed popup',
		'im_015' => 'Om iManager',
		'im_016' => 'Sektion',
		'im_096' => 'Please wait while loading...',
		'im_097' => 'Klik for at ændre farve',
		'im_098' =>	'Åben sektion',
		'im_099' => 'Luk sektion',
		//-------------------------------------------------------------------------
		// insert/change screen - in
		'in_001' => 'Indsæt/rediger billede',
		'in_002' => 'Bibliotek',
		'in_003' => 'Vælg et billede bibliotek',
		'in_004' => 'Billeder',
		'in_005' => 'Se prøve',
		'in_006' => 'Slet billede',
		'in_007' => 'Klik for at se større billede',
		'in_008' => 'Åben sektion for at uploaded/omdøbe eller slette billede.',
		'in_009' => 'Information',
		'in_010' => 'Popup',
		'in_013' => 'Opret link til et billede så det åbnes i et nyt vindue.',
		'in_014' => 'Fjern popup link',
		'in_015' => 'File',
		'in_016' => 'Omdøb',
		'in_017' => 'Omdøb billede',
		'in_018' => 'Upload',
		'in_019' => 'Upload billede',
		'in_020' => 'Størrelse(r)',
		'in_021' => 'Maker den ønskede størrelse(r) som skal skabes når billede(r) uploades',
		'in_022' => 'Original',
		'in_023' => 'Billedet vil blive cropped',
		'in_024' => 'Slet',
		'in_025' => 'Bibliotek',
		'in_026' => 'Klik for at oprette et bibliotek',
		'in_027' => 'Opret et bibliotek',
		'in_028' => 'Brede',
		'in_029' => 'Højde',
		'in_030' => 'Type',
		'in_031' => 'Størrelse',
		'in_032' => 'Navn',
		'in_033' => 'Oprettet',
		'in_034' => 'Ændret',
		'in_035' => 'Billed info',
		'in_036' => 'Klik på billedet for at lukke vinduet',
		'in_037' => 'Klik for at skifte til billed valgs visning',
		'in_099' => 'Standard',
		//-------------------------------------------------------------------------
		// properties, attributes - at
		'at_001' => 'Billed egenskaber',
		'at_002' => 'Kilde',
		'at_003' => 'Titel',
		'at_004' => 'TITEL - vis billed beskrivelse ved mouseover',
		'at_005' => 'Beskrivelse',
		'at_006' => 'ALT -  tekst udskiftning i stedet for billede',
		'at_007' => 'Stil',
		'at_008' => 'Vær sikker på de markerede stile eksistere i din stylesheet!',
		'at_009' => 'CSS-stil',
		'at_010' => 'Egenskaber',
		'at_011' => 'Opsætning \'opstilling\', \'bord\', \'h-afstand\', og \'v-afstand\' indstillinger for billed elementer understøttes ikke af XHTML 1.0 Strict DTD. Brug  istedet CSS-style.',
		'at_012' => 'Opstilling',
		'at_013' => 'Standard',
		'at_014' => 'Venstre',
		'at_015' => 'Højre',
		'at_016' => 'top',
		'at_017' => 'midten',
		'at_018' => 'bunden',
		'at_019' => 'absolute midt',
		'at_020' => 'tekst top',
		'at_021' => 'grund linien',
		'at_022' => 'Størrelse',
		'at_023' => 'Brede',
		'at_024' => 'Højde',
		'at_025' => 'Bord',
		'at_026' => 'V-afstand',
		'at_027' => 'H-afstand',
		'at_028' => 'Se prøve',
		'at_029' => 'Klik for at indsætte special karatker ind i titel feltet',
		'at_030' => 'Klik for at indsætte special karakter ind i beskrivelses feltet',
		'at_031' => 'Sæt billed størrelsen til standard værdi',
		'at_099' => 'Standard',
		//-------------------------------------------------------------------------
		// toolbox - tb
		'tb_001' => 'Udseende',
		'tb_002' => 'Ændre størrelsen',
		'tb_003' => 'Crop',
		'tb_004' => 'Retninger',
		'tb_005' => 'Filtre',
		//'tb_006' => 'Dynamic text',
		'tb_007' => 'Vandmærke',
		'tb_008' => 'Maske',
		'tb_009' => 'Wizard',
		'tb_010' => 'Vis nuværrende indstillinger',
		'tb_011' => 'Kilde',
		'tb_012' => 'Information',
		'tb_013' => 'Sti',
		'tb_014' => 'File',
		'tb_015' => 'Hvis afkrydset (Standard indstillinger), Så vil miniature (ændret billede) blive renderet til en file. Ellers vil miniature blive skabt dynamisk.',
		'tb_016' => 'Overskriv',
		'tb_017' => 'Hvis afkrydset vil værktøjskasse ændringer blive tilføjet det eksisterende billede - Det originale billede vil blive overskrevet! Hvis ikke markeret vil værktøjskasse ændringer blive insat og billedet gemmes som ny file.',
		'tb_018' => 'Format',
		'tb_019' => 'Vælg billed format - (standard: jpeg)',
		'tb_020' => 'Kvalitet',
		'tb_021' => 'Miniature',
		'tb_022' => 'Hvis afkrydset, vil eksisterende minature billede blive opdateret. Ellers vil der blive lavet et nyt miniature billede!',
		'tb_023' => 'opdater',
		'tb_024' => 'Se prøve',
		'tb_025' => 'Klik for fuld størrelse',
		'tb_026' => 'Overlag/underlag',
		'tb_096' => 'Tilføj værktøjskasse ændringer til billedet - opdater (se prøve) vinduet',
		'tb_097' => 'Gem værktøjskasse ændringer - opdater billed file',
		'tb_098' => 'opdater',
		'tb_099' => 'Godkend',
		//-------------------------------------------------------------------------
		// resize - rs
		'rs_001' => 'Ændre størrelse',
		'rs_002' => 'Ændre størrelse',
		'rs_003' => 'Brede',
		'rs_004' => 'Højde',
		'rs_005' => 'Klik for at nulstille størrelse',
		'rs_006' => 'Størrelser',
		'rs_007' => 'Behold',
		'rs_008' => 'Behold aspect ratio - Billed proportioner ændres IKKE.',
		'rs_009' => 'Behold aspect ratio',
		'rs_010' => 'Ignorer',
		'rs_011' => 'Ignorer proportioner - slå proportioner fra således at ændre størrelse og strækning af billede følger brede og højde værdier - dette kan medføre forvrængning af billedet.',
		'rs_012' => 'ignore inverteret udseende',
		'rs_013' => 'Påtving',
		'rs_014' => 'Påtving inverteret udseende',
		'rs_015' => 'Påtving inverteret udseende - behold proportioner på billedet og lav en fast størrelse for miniature!',
		'rs_016' => 'Farve',
		'rs_017' => 'Beskær',
		'rs_018' => 'zoom beskær til ny størrelse',
		'rs_019' => 'Zoom beskær - dette vil automatisk beskære større størrelse således billedet vil fylde den smallere størrelse.',
		'rs_020' => 'Forstør',
		'rs_021' => 'Tillad forstørrelse',
		'rs_022' => 'Tillad forstørrelse af billede - dette kan foresage forvrængning af billedet!',
		'rs_099' => 'Standard',
		//-------------------------------------------------------------------------
		// crop interface - ci
		'ci_001' => 'Beskær værktøjet',
		'ci_002' => 'Brug \'shift\' eller \'ctrl\' knappen til at ændre størrelsen på det beskårende område',
		'ci_003' => 'Nulstil værktøjet',
		'ci_004' => 'Se prøve af det beskårende område',
		'ci_005' => 'Luk værktøjet',
		//-------------------------------------------------------------------------
		// crop - cr
		'cr_001' => 'Beskær',
		'cr_002' => 'Beskær',
		'cr_003' => 'Åben beskær værktøjet',
		'cr_004' => 'Luk beskær værktøjet',
		'cr_005' => 'Beskær værktøjet - Indtast dine værdier manulelt eller brug beskær værktøjet.Hvis du arbejder med beskær værktøjet, brug \'shift\' eller \'ctrl\' knappen for at ændre det beskårende område',
		'cr_006' => 'Brede',
		'cr_007' => 'Højde',
		'cr_008' => 'Top',
		'cr_009' => 'Venstre',
		'cr_010' => 'Inverter',
		'cr_011' => 'Sæt beskær inverteret område til (standard: 4 : 3)',
		'cr_012' => 'ingen',
		'cr_099' => 'Standard',
		//-------------------------------------------------------------------------
		// orientation - or
		'or_001' => 'Retning',
		'or_002' => 'Retning',
		'or_003' => 'Brug enten \'flip\' eller \'roter\' til at ændre billed retningen',
		'or_004' => 'Flip',
		'or_005' => 'Horizontal',
		'or_006' => 'Vertikal',
		'or_007' => 'Begge',
		'or_008' => 'Roter',
		'or_009' => 'Hvis sat til \'EXIF\', vil evt. kamera retningen blive brugt',
		'or_010' => 'Vinkel',
		'or_011' => 'Roter via vinkel: vinklen på roteringen i grader; positiv = mod uret, negativ = med uret',
		'or_012' => 'Auto',
		'or_013' => 'Auto rotering: sat til exif info, for at bruge EXIF retning gemt af kamera. Kan også sættes til +180 grader; or -180 grader; ved landskab, or +90 grader; or -90 grader; for portræt. Positive værdier for med uret negative værdier for mod uret.',
		'or_014' => '+ 90 grader;',
		'or_015' => '- 90 grader;',
		'or_016' => '+ 180 grader;',
		'or_017' => '- 180 grader;',
		'or_018' => 'exif info',
		'or_019' => 'Farve',
		'or_020' => 'portræt',
		'or_021' => 'landskab',
		'or_022' => 'kamera',
		'or_099' => 'Standard',
		//-------------------------------------------------------------------------
		// colorize - co
		'co_001' => 'Filtere',
		'co_002' => 'Filtere',
		'co_003' => 'Filtere - tilføj filtere til billedet',
		'co_004' => 'Effekter',
		'co_005' => 'Effekter - tilføj farver til billedet',
		'co_006' => 'Pift op',
		'co_007' => 'Pift op - øg billed kvaliteten',
		//-------------------------------------------------------------------------
		// effects - ef
		'ef_001' => 'Effekter',
		'ef_002' => 'Effekter',
		'ef_003' => 'Gråtone skala',
		'ef_099' => 'Standard',
		'ef_004' => 'Negativ',
		'ef_005' => 'B&amp;W',
		'ef_006' => 'Sepia',
		'ef_007' => 'Sepia - Sepia toning bliver brugt af fotografer til at lave varme print farver. Sepia-toning giver dit billede et antikt udseende.',
		'ef_008' => 'Intensitet',
		'ef_009' => 'Farve',
		'ef_010' => 'Farve - farvelæg billedet',
		'ef_011' => 'Intensitet',
		//-------------------------------------------------------------------------
		// touchup - to
		'to_001' => 'Pift op',
		'to_002' => 'Pift op',
		'to_003' => 'Gamma',
		'to_004' => 'Saturation',
		'to_005' => 'Maske',
		'to_006' => 'Sharpening filters emphasize the edges, or the differences 
between adjacent light and dark sample points in an image. The Unsharp Mask 
filter has parameters that allow it to affect only the edges in the image, 
and to exclude the smoother low-contrast areas.',
		'to_007' => 'Af-maske',
		'to_008' => 'Radius',
		'to_009' => 'Radius kontrolere hvor brede kant tykkelsen bliver, og Radius 
= 1.0 er standard størrelse, brede 0.6 til 2.0 er altid nyttige.',
		'to_010' => 'Mængde',
		'to_011' => 'Mængde is like a volume control, exaggerating the edge 
differences (how much darker and how much lighter the edge borders become). 
Amount interacts with Radius as to degree of sharpening, but it does not 
affect the width of the edge rims. Amount has a large effect, and values of 
80 to 120 are normally usable if the Radius isn\'t too large.',
		'to_012' => 'Threshold',
		'to_013' => 'Threshold specifies how far apart adjacent tonal values have 
to be (values of 0..255) before the filter does anything to the edges, 
before it is judged to be an edge at all. Low values should sharpen more 
because fewer areas are excluded. Higher threshold values exclude areas of 
lower contrast.',
		'to_014' => 'Auto kontrast',
		'to_015' => 'auto kontrast',
		'to_016' => 'Auto kontrast',
		'to_017' => 'Kanal',
		//'to_018' => '',
		'to_019' => 'alle',
		'to_020' => 'rød',
		'to_021' => 'grøn',
		'to_022' => 'blå',
		'to_023' => 'Min.',
		//'to_024' => '',
		'to_025' => 'Max.',
		//'to_026' => '',
		'to_027' => 'auto',
		'to_028' => 'Slør',
		'to_029' => 'Slør blødgør focus på det valgte billede.',
		'to_030' => 'Balance',
		'to_031' => 'Hvid balance',
		'to_032' => 'Balance - dette filter forsøger at holde lysstyrken konstant 
så teoretisk kan alle gråtoner bruges.',
		'to_098' => 'baggrund - kun hvis jpeg',
		'to_099' => 'Standard',
		//-------------------------------------------------------------------------
		// watermark - wm
		'wm_001' => 'Vandmærke',
		'wm_002' => 'Vandmærke',
		'wm_003' => 'Tilføjer enten \'tekst\' eller \'billede\' vandmærke til 
billedet',
		'wm_004' => 'Type',
		'wm_005' => 'tekst',
		'wm_006' => 'Billede',
		'wm_007' => 'Vælg den vandmærke type der skal tilføjes billedet',
		'wm_008' => 'Tekst',
		'wm_009' => 'Indtast vandmærke tekst der skal tilføjes billedet',
		'wm_010' => 'Klik her for at tilføje special karakter til tekst feltet',
		'wm_011' => 'Farve',
		'wm_012' => 'Skrifttype',
		'wm_013' => 'system',
		'wm_014' => 'true type',
		'wm_015' => 'Vælg enten pre-installerede system skrifttyper eller true 
type for andre skrifttyper',
		'wm_016' => 'Skrifttype',
		'wm_017' => 'Størrelse',
		'wm_018' => 'Vinkel',
		'wm_019' => 'Opacity',
		'wm_020' => 'Margin',
		'wm_021' => 'Opstilling',
		'wm_022' => 'top-venstre',
		'wm_023' => 'top',
		'wm_024' => 'top-højre',
		'wm_025' => 'venstre',
		'wm_026' => 'højre',
		'wm_027' => 'bunden til venstre',
		'wm_028' => 'bund',
		'wm_029' => 'bunden til højre',
		'wm_030' => 'tiltet',
		'wm_031' => 'tekst farve',
		'wm_032' => 'Font',
		'wm_099' => 'Standard',
		//-------------------------------------------------------------------------
		// overlay - ov
		'ov_001' => 'Overlag',
		'ov_002' => 'Overlag',
		'ov_003' => 'Overlag - lægger et billede oven på miniaturen, eller lægger 
miniaturen oven på et andet billede.',
		'ov_004' => 'Arranger',
		'ov_005' => 'forrest',
		'ov_006' => 'bagerst',
		'ov_007' => 'Arranger - Hvis sat til \'forrest\', vil laget blive lagt 
oven på billedet (standard). Hvis sat til \'bagerst\', vil dit billede blive 
lagt oven på laget.',
		'ov_008' => 'Gennemsigtighed',
		'ov_009' => 'Margin',
		'ov_010' => 'Bibliotek',
		'ov_099' => 'Standard',
		//-------------------------------------------------------------------------
		// mask - ms
		'ms_001' => 'Maske',
		'ms_002' => 'Billed maske',
		'ms_003' => 'Ved at bruge masker vist neden for kan du skabe fantastiske, 
og spændende billeder',
		'ms_004' => 'Farve',
		'ms_099' => 'Standard',
		//-------------------------------------------------------------------------
		// image wizard - wz
		'wz_001' => 'Troldmand',
		'wz_002' => 'Billed troldmand',
		'wz_003' => 'Billed troldmand - Tillader dig at tilføje nogle effekter som 
f.eks, bevel, Rammer, Indsæt skygge, bord, runde hjørner, og ellipse',
		'wz_004' => 'Skrå kant',
		'wz_005' => 'Skrå kant - tilføjer en hævet sunket effekt til dit billede. 
Breden af bevel er variabel ligesom faverne for hævet og sænket effekten.',
		'wz_006' => 'Ramme',
		'wz_007' => 'Ramme - en simpel farvet billed ramme tegne rund om billedet 
og som giver effekten af løft ved en mørk og en lys kant. Både farven på den 
mørke og lyse kant kan ændres',
		'wz_008' => 'Skygge',
		'wz_009' => 'Skygge - skygge effekt fra to kanter med fade farve.',
		'wz_010' => 'Bord',
		'wz_011' => 'Bord runde hjørner. Variabler bruges til at ændre rundingen 
på hjørnet, baggrunds farven og bord breden.',
		'wz_012' => 'Runde hjørne',
		'wz_013' => 'Runde hjørner - bruges til at afrunde hjørner på billedet. 
Variabler bruges til at ændre rundingen på hjørnet, baggrunds farven og 
anti-alias breden.',
		'wz_014' => 'Ellipse',
		'wz_015' => 'Ellipse - ellipse er specielt god til sider der bruger runde 
hjørne m.m i designet. Bruger i øjeblikket en farve til bagrunden.',
		'wz_099' => 'Standard',
		//-------------------------------------------------------------------------
		// bevel - be
		'be_001' => 'Skrå kant',
		'be_002' => 'Skrå kant',
		'be_003' => 'Brede',
		'be_004' => 'Lys',
		'be_005' => 'toppen til venstre',
		'be_006' => 'Mørk',
		'be_007' => 'bunden til højre',
		//-------------------------------------------------------------------------
		// frame - fr
		'fr_001' => 'Ramme',
		'fr_002' => 'Ramme',
		'fr_003' => 'Bord',
		'fr_004' => 'bord brede',
		'fr_005' => 'Bevel',
		'fr_006' => 'bevel brede',
		'fr_007' => 'Hoved ramme',
		'fr_008' => 'Hoved ramme',
		'fr_009' => 'Skrå kant',
		'fr_010' => 'Spot på skrå kant',
		'fr_011' => 'Skygge',
		'fr_012' => 'Skygge skrå kant',
		//-------------------------------------------------------------------------
		// drop shadow - ds
		'sh_001' => 'Skygge',
		'sh_002' => 'Indsæt skygge',
		'sh_003' => 'Brede',
		'sh_004' => 'Margin',
		'sh_005' => 'Vinkel',
		'sh_006' => 'Udtone',
		'sh_007' => 'Farve',
		'sh_008' => 'Skygge farve',
		//-------------------------------------------------------------------------
		// round edges - re
		'br_001' => 'Bord',
		'br_002' => 'Runde border',
		'br_003' => 'Brede',
		'br_004' => 'X-radius',
		'br_005' => 'Y-radius',
		'br_006' => 'Kant farve',
		'br_007' => 'Hvis breden er &gt; 0',
		'br_008' => 'Farve',
		//-------------------------------------------------------------------------
		// round corners - rc
		'rc_001' => 'Hjørne',
		'rc_002' => 'Runde hjørner',
		'rc_003' => 'X-radius',
		'rc_004' => 'Y-radius',
		'rc_005' => 'Farve',
		//-------------------------------------------------------------------------
		// ellipse - el
		'el_001' => 'Ellipse',
		'el_002' => 'Ellipse',
		'el_003' => 'Farve',
		//-------------------------------------------------------------------------
		// toolbox settings - se
		'se_001' => 'Værktøjskasse indstillinger',
		'se_002' => 'Værktøjskasse indstillinger',
		'se_099' => 'Skift til sektion',
		//-------------------------------------------------------------------------
		// error messages - er
		'er_001' => 'Fejl',
		'er_002' => 'Der er ikke valgt noget billede - Vælg et billede',
		'er_003' => 'Breden er ikke et tal',
		'er_004' => 'Højden er ikke et tal',
		'er_005' => 'Borden er ikke et tal',
		'er_006' => 'Den horisontal afstand er ikke et tal',
		'er_007' => 'Den vertikal afstand er ikke et tal',
		'er_008' => 'Klik OK for at slette',
		'er_009' => 'Omdøbning af miniature er ikke tilladt! Omdøb kilde billedet for at omdøbe minature navnet.',
		'er_010' => 'Klik OK for at omdøbe billedet til',
		'er_011' => 'Der er ikke indtastet noget navn eller navnet er ikke ændret!',
		'er_012' => 'Forstørrelse af billede er ikke tilladt! Afkryds FORSTØR for at tillade forstørrelse af billede.',
		'er_013' => 'Forkert farve kode',
		'er_014' => 'Indsæt nyt file navn!',
		'er_015' => 'Indsæt et godkendt file navn!',
		'er_016' => 'Miniature er ikke tilgængelig! Sæt miniature størrelsen i config filen for at tillade minature billeder.',
		'er_017' => 'Der er ikke valgt noget vandmærke billede!',
		'er_018' => 'Der er ikke valgt nogen mask!',
		'er_020' => 'Der er ikke valgt en funktion fra værktøjskassen! Prøv igen...',
		'er_021' => 'Klik OK for at uploade billede(er).',
		'er_022' => 'Uploader billede - Vent venligst...',
		'er_023' => 'Der er ikke valgt noget billede eller file størrelsen er ikke sat.',
		'er_024' => 'File',
		'er_025' => 'Eksistere allerede! Klik OK for at overskrive filen...',
		'er_026' => 'Indsæt nyt file navn!',
		'er_027' => 'Biblioteket eksistere ikke fysisk',
		'er_028' => 'Der opstod en fejl under file upload. Prøv igen.',
		'er_029' => 'Forkert billed file type',
		'er_030' => 'Slet fejlede! Prøv igen.',
		'er_031' => 'Overskriv',
		'er_032' => 'Filter opsætning blev ikke fundet',
		'er_033' => 'Værktøjskasse opsætning blev ikke fundet',
		'er_034' => 'Top værdien er ugyldig! Max. værdien er',
		'er_035' => 'Venstre værdien er ugyldig! Max. værdien er',
		'er_036' => 'Beskær dimensioner er større end kilde dimensioner',
		'er_037' => 'Sektion er ikke mulig i \"STANDALONE\" tilstand!',
		'er_038' => 'Fuld prøve størrelse virker kun for billeder der er større end (Se Prøve) størrelsen!',
		'er_040' => 'Der er ikke valgt noget overlags billede!',
		'er_041' => 'Omdøb fejlede! Prøv igen.',
		'er_042' => 'Oprettelse af bibliotek! Prøv igen.',
		'er_043' => 'Forstørrelse er ikke tilladt!',
		'er_044' => 'Fejl ved oprettelse af billed liste!',
		//'er_099' => '',
	  ),
	  
//-------------------------------------------------------------------------
	  // color picker
		'colorpicker'	=> array (
		'title' 		=> 'Farve vælger',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Fortryd',
	  ),
	  
//-------------------------------------------------------------------------
	  // symbols
		'symbols'		=> array (
		'title' 		=> 'Symboler',
		'ok' 			=> 'OK',
		'cancel' 		=> 'Fortryd',
	  ),
	  
//-------------------------------------------------------------------------
	  // examples
		'examples'		=> array (
		'ex_001' 		=> 'Eksempler',
		'ex_002' 		=> 'iManager eksempler',
		'ex_003' 		=> 'Luk',
	  ),
	)
?>