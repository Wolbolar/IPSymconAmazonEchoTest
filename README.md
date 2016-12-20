# IPSymconAmazonEchoTest
===

Modul für IP-Symcon ab Version 4.1 ermöglicht die Kommunikation mit dem Amazon Echo.

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Voraussetzungen](#2-voraussetzungen)  
3. [Installation](#3-installation)  
4. [Funktionsreferenz](#4-funktionsreferenz)
5. [Konfiguration](#5-konfiguration)  
6. [Anhang](#6-anhang) 

## 1. Funktionsumfang

Testkonfigurator zur Diskussion

## 2. Voraussetzungen

 - IPS 4.1

## 3. Installation

### a. Laden des Moduls

 Wir wechseln zu IP-Symcon (Ver. 4.1) und fügen unter Kerninstanzen über _*Modules*_ -> Hinzufügen das Modul hinzu mit der URL
```
git://github.com/Wolbolar/IPSymconAmazonEchoTest.git
```	

### b. Einrichtung in IPS

In IP-Symcon wird eine Konfigurator Instanz angelegt. Um die Konfigurator Instanz zu erstellen wechseln wir unter Konfigurator Instanzen
und erzeugen mit *CTRL+1* eine neue Instanz. In der Auswahl ist als Hersteller Amazon eingeben.


### c. Konfiguration von Amazon Echo

Zunächst legen wir fest wieviele Geräte von Echo geschaltet werden sollen. Hier wählen wir für jedes Gerät das durch Echo geschaltet werden soll
den Echo Namen und den Echo Gerätetyp aus und wählen dann die zu steuernde IP-Symcon Instanz aus.


## 4. Funktionsreferenz

### Amazon Echo Konfigurator
 
Beschreibung Funktion
```php
AmazonEcho_Funktionsname(integer $InstanceID)
```   
Parameter _$InstanceID_ __*ObjektID*__ der Amazon Echo Instanz
  

## 5. Konfiguration:

### Amazon Echo Konfigurator:

| Eigenschaft | Typ     | Standardwert | Funktion                                                        |
| :---------: | :-----: | :----------: | :-------------------------------------------------------------: |
| username    | string  | 		       | username für Microsoft Flow zur Authentifizierung bei IP-Symcon |
| password    | string  |              | password für Microsoft Flow zur Authentifizierung bei IP-Symcon |

username und password sind vorab eingestellt können aber individuell angepasst werden.

## 6. Anhang

###  a. GUIDs und Datenaustausch:

#### Amazon Echo Konfigurator:

GUID: `{112A6FCE-1ACA-405D-A630-31D9867AE1D2}` 



