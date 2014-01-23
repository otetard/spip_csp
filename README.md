Plugin CSP pour SPIP 3
======================

Ce *plugin* permettant de configurer une politique CSP dans SPIP.

## CSP, Content Security Policy

CSP est un mécanisme de sécurité permettant de restreindre l'origine du contenu (tel qu'un script JavaScript, une feuille de style etc.) dans une page web à certains sites autorisés. Cela permet de mieux se prémunir d'une éventuelle faille XSS. (source [Wikipédia](https://fr.wikipedia.org/wiki/Content_Security_Policy)

Tous les navigateurs web ne prennent pas en charge ce standard (voir [Can I use](http://caniuse.com/contentsecuritypolicy)).

Le principe de CSP est d'envoyer au navigateur une en-tête HTTP (``Content-Security-Policy``) indiquant les emplacements autorisés pour le chargement des resources (images, scripts, etc.).

## Avancement

Actuellement, le *plugin* permet d'appliquer une politique CSP. Il faut pour cela aller dans le menu « Configuration », puis « Content Security Policy ».

Il est aussi possible d'activer un collecteur CSP depuis l'interface de configuration. Cela permet de consulter les violations de la politique de sécurité. Les données collectées sont disponibles dans le menu « Maintenance » puis « Rapports CSP ».

## En savoir plus

Voici quelques sources / références à propos de CSP :
- [An Introduction to Content Security Policy](http://www.html5rocks.com/en/tutorials/security/content-security-policy/)
- Laurent Butti, *Content Security Policy en tant que prévention des XSS : Théorie et pratique*, Misc 71
- [Module Ruby SecureHeaders développé par Twitter](https://github.com/twitter/secureheaders)
