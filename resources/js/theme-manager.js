/**
 * Theme Manager - Dark/Light Mode Toggle
 * Mengelola tema aplikasi dengan localStorage persistence
 */

export class ThemeManager {
    constructor() {
        this.storageKey = 'resto-theme';
        this.theme = this.getStoredTheme() || 'light'; // Default: light
        this.init();
    }

    init() {
        this.applyTheme(this.theme);
        this.setupListeners();
    }

    getStoredTheme() {
        try {
            return localStorage.getItem(this.storageKey);
        } catch (e) {
            return null;
        }
    }

    setStoredTheme(theme) {
        try {
            localStorage.setItem(this.storageKey, theme);
        } catch (e) {
            console.warn('Unable to store theme preference');
        }
    }

    applyTheme(theme) {
        this.theme = theme;
        document.documentElement.setAttribute('data-theme', theme);
        this.setStoredTheme(theme);
        
        // Dispatch event untuk komponen lain
        window.dispatchEvent(new CustomEvent('theme-changed', { 
            detail: { theme } 
        }));
    }

    toggle() {
        const newTheme = this.theme === 'light' ? 'dark' : 'light';
        this.applyTheme(newTheme);
        this.showToast(newTheme);
        return newTheme;
    }

    setTheme(theme) {
        if (theme === 'light' || theme === 'dark') {
            this.applyTheme(theme);
        }
    }

    getCurrentTheme() {
        return this.theme;
    }

    isDark() {
        return this.theme === 'dark';
    }

    isLight() {
        return this.theme === 'light';
    }

    setupListeners() {
        // Listen untuk perubahan system preference (opsional)
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            mediaQuery.addEventListener('change', (e) => {
                // Hanya auto-switch jika user belum pernah set manual
                if (!this.getStoredTheme()) {
                    this.applyTheme(e.matches ? 'dark' : 'light');
                }
            });
        }
    }

    showToast(theme) {
        const message = theme === 'dark' 
            ? '🌙 Mode Gelap Aktif' 
            : '☀️ Mode Terang Aktif';
        
        if (window.showToast) {
            window.showToast(message, 'info');
        }
    }

    // Alpine.js integration
    alpineData() {
        return {
            theme: this.theme,
            isDark: this.isDark(),
            isLight: this.isLight(),
            
            toggle() {
                const newTheme = window.themeManager.toggle();
                this.theme = newTheme;
                this.isDark = newTheme === 'dark';
                this.isLight = newTheme === 'light';
            },
            
            setTheme(theme) {
                window.themeManager.setTheme(theme);
                this.theme = theme;
                this.isDark = theme === 'dark';
                this.isLight = theme === 'light';
            }
        };
    }
}

// Initialize theme manager
window.themeManager = new ThemeManager();

// Export for Alpine.js
export function themeToggle() {
    return window.themeManager.alpineData();
}
