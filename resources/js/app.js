import './bootstrap';
import { dashboardManager } from './dashboard-manager.js';
import { ThemeManager, themeToggle } from './theme-manager.js';

// Theme manager is initialized by theme-manager.js before Alpine loads.

// Register Alpine.js components globally
document.addEventListener('alpine:init', () => {
    // Dashboard Manager akan tersedia sebagai x-data="dashboardManager()"
    window.dashboardManager = dashboardManager;
    
    // Theme Toggle akan tersedia sebagai x-data="themeToggle()"
    window.themeToggle = themeToggle;
});

