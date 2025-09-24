/**
 * Vanilla JavaScript implementation for theme appearance management
 * @typedef {'light' | 'dark' | 'system'} Appearance
 */

/**
 * Updates the theme based on the selected appearance
 * @param {Appearance} value - The appearance value
 */
export function updateTheme(value) {
    if (typeof window === 'undefined') {
        return;
    }

    if (value === 'system') {
        const mediaQueryList = window.matchMedia('(prefers-color-scheme: dark)');
        const systemTheme = mediaQueryList.matches ? 'dark' : 'light';

        document.documentElement.classList.toggle('dark', systemTheme === 'dark');
    } else {
        document.documentElement.classList.toggle('dark', value === 'dark');
    }
}

/**
 * Sets a cookie with the specified name and value
 * @param {string} name - Cookie name
 * @param {string} value - Cookie value
 * @param {number} days - Expiry days (default: 365)
 */
const setCookie = (name, value, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

/**
 * Gets the media query for dark mode preference
 * @returns {MediaQueryList|null}
 */
const mediaQuery = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return window.matchMedia('(prefers-color-scheme: dark)');
};

/**
 * Gets the stored appearance from localStorage
 * @returns {Appearance|null}
 */
const getStoredAppearance = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return localStorage.getItem('appearance');
};

/**
 * Handles system theme change events
 */
const handleSystemThemeChange = () => {
    const currentAppearance = getStoredAppearance();

    updateTheme(currentAppearance || 'system');
};

/**
 * Initializes the theme system
 */
export function initializeTheme() {
    if (typeof window === 'undefined') {
        return;
    }

    // Initialize theme from saved preference or default to system
    const savedAppearance = getStoredAppearance();
    updateTheme(savedAppearance || 'system');

    // Set up system theme change listener
    mediaQuery()?.addEventListener('change', handleSystemThemeChange);
}

// Global appearance state
let currentAppearance = 'system';
const appearanceListeners = new Set();

/**
 * Initializes the appearance from localStorage
 */
function initializeAppearance() {
    if (typeof window !== 'undefined') {
        const savedAppearance = localStorage.getItem('appearance');
        if (savedAppearance) {
            currentAppearance = savedAppearance;
        }
    }
}

/**
 * Notifies all listeners of appearance changes
 * @param {Appearance} newAppearance 
 */
function notifyListeners(newAppearance) {
    appearanceListeners.forEach(listener => {
        try {
            listener(newAppearance);
        } catch (error) {
            console.error('Error in appearance listener:', error);
        }
    });
}

/**
 * Updates the appearance setting
 * @param {Appearance} value - The new appearance value
 */
function updateAppearance(value) {
    currentAppearance = value;

    // Store in localStorage for client-side persistence
    if (typeof window !== 'undefined') {
        localStorage.setItem('appearance', value);
    }

    // Store in cookie for SSR
    setCookie('appearance', value);

    updateTheme(value);

    // Notify listeners
    notifyListeners(value);
}

/**
 * Gets the current appearance setting
 * @returns {Appearance}
 */
function getAppearance() {
    return currentAppearance;
}

/**
 * Subscribes to appearance changes
 * @param {function} callback - Callback function to call on appearance change
 * @returns {function} Unsubscribe function
 */
function onAppearanceChange(callback) {
    appearanceListeners.add(callback);
    
    return () => {
        appearanceListeners.delete(callback);
    };
}

/**
 * Creates an appearance manager (replaces the Vue composable)
 * @returns {Object} Appearance manager with reactive-like behavior
 */
export function useAppearance() {
    // Initialize if not already done
    if (currentAppearance === 'system' && typeof window !== 'undefined') {
        initializeAppearance();
    }

    return {
        get appearance() {
            return currentAppearance;
        },
        updateAppearance,
        getAppearance,
        onAppearanceChange
    };
}

// Initialize appearance on module load
if (typeof window !== 'undefined') {
    initializeAppearance();
}
