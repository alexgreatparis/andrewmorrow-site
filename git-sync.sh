#!/bin/bash

echo "🧠 [Git Smart Sync]"

# 1. Affiche le remote et la branche courante
echo "📍 Remote :"
git remote -v

echo -e "\n📦 Branche locale :"
git branch --show-current

# 2. Statut local
echo -e "\n📌 Statut local :"
git status -sb

# 3. Diagnostique la synchro
echo -e "\n📤 Commits LOCAUX non poussés :"
git log --oneline origin/$(git branch --show-current)..HEAD || echo "Aucun"

echo -e "\n📥 Commits DISTANTS non tirés :"
git log --oneline HEAD..origin/$(git branch --show-current) || echo "Aucun"

# 4. Ajoute tous les nouveaux fichiers (hors .DS_Store)
echo -e "\n➕ Ajout des nouveaux fichiers (hors .DS_Store)..."
find . -name ".DS_Store" -delete
git add .

# 5. Propose un message de commit intelligent
echo -e "\n📝 Saisir un message de commit (laisser vide pour 'Mise à jour auto') :"
read -r commit_msg
if [ -z "$commit_msg" ]; then
  commit_msg="Mise à jour auto $(date '+%Y-%m-%d %H:%M')"
fi

git commit -m "$commit_msg" || echo "Aucun changement à committer."

# 6. Pousse sur la branche courante
echo -e "\n🚀 Push vers le dépôt distant..."
git push

# 7. Statut final
echo -e "\n✅ Statut final :"
git status -sb 