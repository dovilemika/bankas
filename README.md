Bank Application
========================

Daryta remiantis Symfony demo aplikacija.

Requirements
------------

  * PHP 7.3 or higher;
  * PDO-SQLite PHP extension enabled;
  * and the [usual Symfony application requirements][2].

Installation
------------

[Download Symfony][4] to install the `symfony` binary on your computer.

Usage
-----

If you have [installed Symfony][4] binary, run this command:

```bash
$ symfony serve
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

If you don't have the Symfony binary installed, run `php -S localhost:8000 -t public/`
to use the built-in PHP web server or [configure a web server][3] like Nginx or
Apache to run the application.

Reikalavimai
-----


- [atlikta] Administracinės	dalies	funkcijos prieinamos tik autentifikuotam vartotojui.

    Vartotojas nuvedamas į prisijungimo puslapį http://127.0.0.1:8000/en/login.


- [atlikta] Jeigu vartotojas nėra autentifikuotas, pradiniame puslapyje rodoma	prisijungimo forma


- [atlikta] Matyti savo banko sąskaitos likutį


-  [atlikta] Atlikti pinigų pervedimą kitam sistemos vartotojui nurodant banko sąskaitos nr.,	vardą,	 pavardę, mokėjimo paskirtį.

    Rodomi galimi variantai (išskyrus dabartinę sąskaitą), iš kurių pasirenkama. 


-  [atlikta] Atšaukti	pavedimą, kol	jis nėra	 patvirtintas (pavedimas patvirtinamas praėjus 2 min.	po pavedimo išsaugojimo sistemoje)


-  [atlikta] Gauti pinigus iš kito vartotojo.


-  [atlikta] Pervesti pinigus tarp savo sąskaitų

    Galimuose variantuose rodoma ir kita vartotojo sąskaita.


-  [atlikta] Generuoti banko išrašą, kuriame būtų	pateikta informacija apie bankines operacijas(įplaukos, mokėjimai).

    Paspaudus ant sąskaitos, rodomas tos sąskaitos pervedimų sąrašas


-  [neatlikta] Administracinės srities vartotojo	sąsajai	turi būti panaudotas aukščiau	pateiktas	HTML5/CSS/JS šablonas.

    Naudotas bootstrap šablonas

Kiti reikalavimai:


-  [atlikta] Visa informacija saugoma realiacinėje duomenų bazėje
    Yra lentelės user, account, ir transfer
   

-  [atlikta] Sistema programuojama naudojant PHP OOP


-  [atlikta] Sistemoje	turi būti registruoti 3 vartotojai
    
    1. username: dovile, password: kitten
    2. username: tomas, password: kitten
    3. username: jonas, password: kitten

-  [atlikta] Kiekvienas vartotojas turi	turėti po dvi sąskaitas, kurių vieną nustatyta,	 kaip pagrindinė.


-  [atlikta] Kiekvieno	vartotojo pagrindinė sąskaita	po pirmo prisijungimo	papildoma 500	EUR (automatiškai).


-  [atlikta] Kiekvienas vartotojas mato tik savo duomenis.


-  [atlikta] Pavedimo	forma turi būti	validuojama, banko sąskaita	turi atitikti IBAN formatą,	jeigu lėšų pavedimui neužtenka, pavedimas	nevykdomas, apie tai pranešant vartotojui,	negali likti neužpildytų	laukų.
    
    Banko sąskaita atitinka IBAN formatą automatiškai, kadangi pasirenkama iš sąrašo. Visi kiti validavimai įvykdyti 
    

-  [atlikta] Kiekvienas vartotojas turi	 atlikti,	bent po 3 pavedimus.


-  [neatlikta] Bankinis pavedimas turi būti padengtas Unit testu


-  [neatlikta] Banko išrašo formavimas turi būti padengtas Dusk testu


-  [atlikta] Visas užduoties kodas turi būti nuotolinėje GIT repositorijoje.
