**BRITT** 
- Ik heb al een functie getUserDetails() aangemaakt die jij ook best gebruikt voor het profiel te updaten, zo hebben we geen duplicate functies.
- Op het profiel maak je al een profielfoto aan, deze mag nog leeg zijn. De username & bio moeten ook getoond worden op het profiel vanuit de database
  en deze moeten kunnen worden aangepast. Gebruik het design can Quintt. Je kan de backend al doen zonder enige frontend, dat hangt af van je persoonlijke voorkeur.
- TIP: $_SESSION & ga spieken in promptDetails.php

**QUINTT**
- Design van het profiel moet bevatten: een profielfoto, username & bio. In een latere deadline moeten de prompts van dit profiel
  ook getoond worden, hou hier rekening mee.

**BRITT & QUINTT**
- Neem de code van login.php, signup.php, User.php, verification.php eens rustig door. Dit zijn mijn features van deadline-1. 
  Als je deze eens lees heb je misschien al een beter idee hoe je je eigen features maakt. 

- Moderator.php, Prompt.php, loadPromptsToApprove.php, promptDetails.php, showcase.php & ajax folder is al voor deadline-2. Ik heb dit al gemerged omdat
  het jullie deel van de code niet lastig valt maar het wel Britt kon helpen zoals de getUserDetails() functie die nu al klaar staat xD

- Als iets niet lukt, gebruik chatGPT/php.net/google/youtube of stel een vraag op stackoverflow. Of je stuurt me even een berichtje dan help ik

- De mappen js, json & vendor hebben te maken met de installatie van sendgrid(email provider) & tailwindcss. Niets van aantrekken

TODO deadline 1:
---------------------
**QUINTT**
- profile design maken voor Britt

**BRITT**
- update profile
- profile frontend doen adhv Quintt zijn figma design
- logout

TODO deadline 2:
------------------
**TIBO**
- verified users should be able to post prompts without approval needed
- better notifications for users on prompt approval instead of error message
- frontend for prompt approval & list of prompts to approve

DONE:
------------------
- register
- email verification
- Login
- list with to approve prompts for mods
- infinite scroll prompts
- ability to approve prompts
- verify user after 3 approved prompts
- password reset
- user should only be able to login when email is confirmed

RULES:
-----------------
- classes met hoofdletter vb. class User
- comments plaatsen waar nodig - iedereen moet je code kunnen begrijpen door deze te lezen
- engels voor variabele namen, interface in het engels
- commits in het engels
- commit often
- gebruik conventional commits(feat: chore: refactor: etc.)
- gebruik OOP! klasses die nodig zijn: User, Moderator, Prompt, Comment, Like, Report
- deel je databank aanpassingen met de rest of export deze en stuur het door!
- maak een nieuwe branch aan wanneer je denkt dat dit nodig is, wanneer je deze wil mergen bespreek je dit met de rest. 
- format je code - de code moet georganiseerd en leesbaar zijn

CONVENTIONAL COMMITS:
---------------------------
- nieuwe feature: feat
- bug fix: fix
- documentatie(comments): docs
- een variabele/functie naam aanpassen: refactor
- other changes: chore:
- teruggaan naar een vorige commit: revert

IMPORTANT GIT COMMANDS:
-------------------------
- git status
- git add ..
- git commit -m ".."
- git push -u origin ..
- git pull --rebase(alle changes op deze branch die op github staan maar niet in jou file komen erbij)
- git reset --hard(alle changes sinds laatste commit gaan weg)

VERANDEREN VAN BRANCH:
--------------------------
- CTRL+SHIFT+P --> Git: checkout to --> kies je branch. 

BRANCH AANMAKEN:
--------------------------
- doe best in github zelf, niet in vscode
- linksboven op main klikken --> view all branches --> rechtsboven new branch --> in vscode veranderen naar die branch.

BRANCH MERGEN:
------------------------
- In github zelf, check met de rest

TIPS:
--------------------------
-  om makkelijk en snel te commiten gebruik Ctrl+Shift+G. Dan schrijf je je commit, klik je op '+' bij de files die je in de commit wilt steken
   en dan klik je commit. Om te pushen klik je de sync knop. Dan hoef je de terminal niet te gebruiken