# Bynfony - Le bar sur symfony

> Evaluation API Platform

Projet par :
- Félix Laviéville
- Lucie Ehrsam

Réalisé en peer coding.

## Rôles

Les rôles sont :
- Patron : ROLE_PATRON
- Serveur : ROLE_WAITER
- Barman : ROLE_BARMAN

Il existe une route pour chaque rôle pour l'authentification dans l'export postman, à partir du moment où les utilisateurs sont créés, pour faciliter les échanges de token.

Vous pouvez donc enregistrer vos utilisateurs types dans le payload des routes pour accélerer les tests.

## Entité et comportement

### User

- Un utilisateur peut être de type Patron, Serveur ou Barman.
- Un utilisateur peut être associé à plusieurs commandes, en tant que author pour un serveur, ou en temps qu'assigné pour un barman.


### Boisson

- Une boisson peut être associée à plusieurs commandes.
- Une boisson peut être créée, modifiée ou supprimée par un barman.

### Media

- Un media est associé à une boisson.
- Un media peut être créé, modifié ou supprimé par un barman.
- Une fois associé, le media et la boisson sont liées, ce qui implique une suppression du media si la boisson est supprimée, et inversement.

### Commande

- Une commande est créée par un serveur.
- Une commande peut être modifiée par un serveur, tant qu'elle n'est pas payée.
- Une commande peut être payée par un serveur.
- Une commande ne peut être modifiée par un barman, il ne peut que la marquer comme prête.
- Il est impossible d'ajouter plusieurs fois la même boisson à une commande.
