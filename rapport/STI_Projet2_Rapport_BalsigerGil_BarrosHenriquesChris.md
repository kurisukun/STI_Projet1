## STI Rapport



**Auteurs:** Balsiger Gil, Barros Henriques Chris 





## Introduction

Le projet vise à trouver des menaces existantes sur le projet 1 et à le modifier de telle sorte que l'application soit sécurisée sans pour autant que cela ne soit au détriment des fonctionnalités déjà mises en place. 



## Description du système



#### DFD

#### Identifier les biens



**Éléments du système:**

- Base de données des utilisateurs
- Base de données des messages
- Application Web

Chacun représente un service qui communique l'un avec l'autre et ils possèdent un lot de vulnérabilités qui leur est propre. 



**Rôle des utilisateurs:**

- Collaborateur (Écrire des messages, répondre, modification du mot de passe)
- Administrateur (Gestion des utilisateurs et rôles)



#### Définition du périmètre de sécurisation

Le but est de sécuriser l'application uniquement. On ne traitera pas les problèmes de vulnérabilité relatifs aux autres services, notamment la base de données (SQLite) ou nginx. 



## Sources de menace

On peut retrouver deux types de menace:



#### Hackers et script-kiddies

**Motivation**

- L'amusement ou la gloire

**Cible**

- Tous les éléments possibles du système

**Potentialité**

- Haute



#### Cybercriminels

**Motivation**

- Financières
- Nuire au business d'une entreprise

**Cible**

- Les credentials des clients
- Modification d'informations

**Potentialité**

- Moyenne



### Vulnérabilités possibles

- Injections SQL
- XSS
- CSRF
- Manque de temps
- Manque de connaissance en développement sécurisé
- Contrôle d'accès cassé
- Logiciels pas à jour



## Scénarios d'attaques

#### Scenario 1

Un utilisateur malveillant parvient à effectuer une injection SQL grâce au formulaire de connexion. Il peut alors obtenir des informations sur le compte administrateur en utilisant les bonnes requêtes SQL. Il peut ensuite se conecter à ce compte et modifier, supprimer les utilisateurs qu'il souhaite. Il pourrait aussi modifier le mot de passe d'un utilisateur pour mettre le mot de passe qu'il veut et pouvoir y accéder à son tour (ou bloquer l'accès du véritable utilisateur).

L'impact sur le business peut être très important car il peut rendre inaccessible tous les comptes existants s'il le souhaite ce qui impliquerait une  perte de crédibilité ainsi que l'impossibilité aux utilisateurs d'utiliser le service.

Les injections demandent de les faire à l'aveugle ou peut-être de faire des injections de second-ordre, ce qui requiert des connaissances, pas à la portée de script-kiddies mais plutôt de cybercriminels.



**Contre-mesures**

- Validation des inputs utilisateur dans les différents HTML forms
- Renforcer les contrôles d'accès





#### Scenario 2

On pourrait envoyer un message contenant du Javascript à l'administrateur à l'intérieur. Ce script pourrait permettre de créer un utilisateur ayant lui aussi les droits admin avec les crendentials voulus. Sinon il est bien entendu possible de simplement nuire à l'expérience utilisateur (faire apparaître des pop-up répétés)

L'impact sur le business est le même que pour le précédent.

Vu la facilité, toute personne voulant nuire peut effectuer ce genre d'attaque. La difficulté augmentant simplement selon l'importance de la nuisance engendrée.



**Contre-mesures**

- Empêcher l'interprétation des balises script pour empêcher le lancement de code Javascript





#### Scenario 3

Imaginons qu'un utilisateur malveillant envoie un mail contenant un lien amenant à un site malveillant. Le site ressemblerait en tout point au site-cible et l'administrateur essaierait de se connecter dessus comme il le fait d'habitude. L'attaquant récupérerait alors les credentials. 

L'impact sur le business est le même que pour les scénarios précédents.

Ce type d'attaque est plus compliqué à mettre en place car il demande de reproduire le site, de forger correctement une requête auprès du vrai site et tout cela sans que l'utilisateur ne se doute de quoi que ce soit.



**Contre-mesures**

- Mettre en place un système de tokens CSRF



#### Scenario 4

Un attaquant peut modifier le HTML pour que le bouton de suppression d'un message en supprime un qui ne lui apparatient pas. 

L'impact sur le business est plus minime mais nuit tout de même à l'expérience utilisateur.

Ce type d'attaque est très facile à mettre en place mais n'apporte pas grand chose en terme de données donc seuls les script-kiddies seraient intéressés.



**Contre-mesures**

- Vérifier que l'utilisateur demandant la suppression du dit message l'ait bel et bien dans sa boîte de réception



#### Scénario 5

Des vulnérabilités présentes dans les logiciels utilisés (PHP, nginx par exemple) pourraient être exploitées si ces derniers ne sont pas à jour. 

L'impact peut être très important car dépendant de failles de sécurité, c'est le système dans son ensemble qui peut tomber.

Les failles peuvent exister indépendamment d'un exploit. Cela peut donc dans certains cas demander beaucoup de connaissances, ce qui n'est à la portée que des hackers très expérimentés 



**Contre-mesures**

- Mettre les logiciels à jour



## Conclusion

