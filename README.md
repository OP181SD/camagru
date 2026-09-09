# Camagru

Application web de création et de partage de photos, développée à l'École 42. L'utilisateur capture une image via sa webcam ou en téléverse une, y superpose des stickers en temps réel, puis publie le montage dans une galerie communautaire où chacun peut liker et commenter.

---

## Fonctionnalités clés

- **Authentification complète** — inscription en deux étapes, vérification d'email, login classique, réinitialisation de mot de passe par email.
- **Studio de capture** — prise de photo par webcam ou upload, superposition de stickers déplaçables en direct, prévisualisation avant publication.
- **Galerie communautaire** — feed des publications, pagination, galerie personnelle des montages de l'utilisateur.
- **Interactions** — likes et commentaires sur chaque publication, notifications.
- **Commentaires sécurisés** (anti-XSS) sous chaque montage.
- **Profils utilisateurs** — édition du profil, photo, gestion du compte ; suppression du contenu possédé.
- **Notifications email** — à la réception d'un like ou d'un commentaire.

---

## Stack technique

| Couche | Technologies |
|---|---|
| Frontend | HTML · CSS · JavaScript vanilla |
| Backend | PHP (routeur & architecture MVC maison) · Apache |
| Base de données | PostgreSQL |
| Média | Capture webcam (getUserMedia) · manipulation Canvas |
| Infra | Docker · Docker Compose |

---

## Sécurité

Mots de passe hachés, protection XSS sur les entrées utilisateur et commentaires, requêtes paramétrées (PDO) contre l'injection SQL, validation stricte des formulaires, vérification d'email obligatoire, contrôle des uploads.
