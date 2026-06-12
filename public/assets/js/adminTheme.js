/**
 * Admin Theme System
 * Handles light/dark theme switching with localStorage
 */

const AdminTheme = {
  // Default theme
  defaultTheme: 'light',

  /**
   * Initialize theme system
   */
  init() {
    const savedTheme = localStorage.getItem('admin_theme') || this.defaultTheme;
    this.setTheme(savedTheme);
    this.watchSystemTheme();
  },

  /**
   * Set theme
   * @param {string} theme - Theme name (light, dark, or system)
   */
  setTheme(theme) {
    if (theme === 'system') {
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      theme = prefersDark ? 'dark' : 'light';
    }

    localStorage.setItem('admin_theme', theme);
    document.documentElement.setAttribute('data-theme', theme);
    document.body.classList.remove('theme-light', 'theme-dark');
    document.body.classList.add(`theme-${theme}`);

    // Update theme in all iframes if needed
    this.updateThemeInDocument(theme);
  },

  /**
   * Update theme in document
   */
  updateThemeInDocument(theme) {
    // Apply CSS variables for theming
    if (theme === 'dark') {
      document.documentElement.style.colorScheme = 'dark';
    } else {
      document.documentElement.style.colorScheme = 'light';
    }
  },

  /**
   * Get current theme
   */
  getTheme() {
    return localStorage.getItem('admin_theme') || this.defaultTheme;
  },

  /**
   * Toggle theme
   */
  toggleTheme() {
    const current = this.getTheme();
    const newTheme = current === 'light' ? 'dark' : 'light';
    this.setTheme(newTheme);
    return newTheme;
  },

  /**
   * Watch for system theme changes
   */
  watchSystemTheme() {
    const savedTheme = localStorage.getItem('admin_theme');
    if (savedTheme === 'system' || !savedTheme) {
      window
        .matchMedia('(prefers-color-scheme: dark)')
        .addEventListener('change', (e) => {
          const newTheme = e.matches ? 'dark' : 'light';
          this.setTheme(newTheme);
        });
    }
  },

  /**
   * Listen for theme changes
   */
  onChange(callback) {
    const observer = new MutationObserver(() => {
      const theme = document.documentElement.getAttribute('data-theme');
      callback(theme);
    });

    observer.observe(document.documentElement, {
      attributes: true,
      attributeFilter: ['data-theme'],
    });
  },
};

// Initialize on document ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    AdminTheme.init();
  });
} else {
  AdminTheme.init();
}
