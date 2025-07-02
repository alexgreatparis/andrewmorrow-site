// ===== GESTIONNAIRE DE THÈMES =====

class ThemeManager {
    constructor() {
        this.currentTheme = 'classic';
        this.themes = ['classic', 'vip', 'minimal'];
        this.init();
    }

    init() {
        // Charger le thème sauvegardé ou utiliser le thème par défaut
        const savedTheme = localStorage.getItem('2faces-theme');
        if (savedTheme && this.themes.includes(savedTheme)) {
            this.currentTheme = savedTheme;
        }
        
        this.applyTheme(this.currentTheme);
        this.createThemeSelector();
        this.updateThemeButton();
    }

    createThemeSelector() {
        // Créer le sélecteur de thème s'il n'existe pas
        if (!document.querySelector('.theme-selector')) {
            const selector = document.createElement('div');
            selector.className = 'theme-selector';
            selector.innerHTML = `
                <button class="theme-btn" data-theme="classic">Classic</button>
                <button class="theme-btn" data-theme="vip">Premium</button>
                <button class="theme-btn" data-theme="minimal">Minimal</button>
            `;
            
            document.body.appendChild(selector);
            
            // Ajouter les événements
            selector.addEventListener('click', (e) => {
                if (e.target.classList.contains('theme-btn')) {
                    const theme = e.target.dataset.theme;
                    this.switchTheme(theme);
                }
            });
        }
    }

    switchTheme(theme) {
        if (this.themes.includes(theme)) {
            this.currentTheme = theme;
            this.applyTheme(theme);
            this.saveTheme(theme);
            this.updateThemeButton();
            
            // Animation de transition
            this.addTransitionEffect();
        }
    }

    applyTheme(theme) {
        // Supprimer tous les liens CSS de thème existants
        const existingLinks = document.querySelectorAll('link[data-theme]');
        existingLinks.forEach(link => link.remove());
        
        // Ajouter le nouveau lien CSS
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = `themes/${theme}.css`;
        link.dataset.theme = theme;
        link.id = 'theme-stylesheet';
        
        document.head.appendChild(link);
        
        // Ajouter une classe au body pour le thème actuel
        document.body.className = `theme-${theme}`;
    }

    saveTheme(theme) {
        localStorage.setItem('2faces-theme', theme);
    }

    updateThemeButton() {
        const buttons = document.querySelectorAll('.theme-btn');
        buttons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.theme === this.currentTheme) {
                btn.classList.add('active');
            }
        });
    }

    addTransitionEffect() {
        // Ajouter une classe temporaire pour l'animation
        document.body.classList.add('theme-transitioning');
        
        setTimeout(() => {
            document.body.classList.remove('theme-transitioning');
        }, 300);
    }

    // Méthode pour obtenir le thème actuel
    getCurrentTheme() {
        return this.currentTheme;
    }

    // Méthode pour changer de thème programmatiquement
    setTheme(theme) {
        this.switchTheme(theme);
    }
}

// Initialiser le gestionnaire de thèmes quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    window.themeManager = new ThemeManager();
});

// Exposer le gestionnaire globalement
window.ThemeManager = ThemeManager; 