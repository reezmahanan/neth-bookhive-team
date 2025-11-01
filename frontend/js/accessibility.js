/**
 * Accessibility Enhancements for NETH Bookhive
 * Provides keyboard navigation, ARIA support, and screen reader improvements
 */

// Detect keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Tab') {
        document.body.classList.add('keyboard-user');
    }
});

document.addEventListener('mousedown', function() {
    document.body.classList.remove('keyboard-user');
});

// Initialize accessibility features
document.addEventListener('DOMContentLoaded', function() {
    initializeARIA();
    setupKeyboardNavigation();
    addSkipLink();
    enhanceFormAccessibility();
    setupModalAccessibility();
    announcePageChanges();
});

/**
 * Initialize ARIA attributes for dynamic content
 */
function initializeARIA() {
    // Add ARIA labels to buttons without visible text
    document.querySelectorAll('button, a').forEach(element => {
        // If button has only an icon, add aria-label
        const hasOnlyIcon = element.querySelector('i, svg') && !element.textContent.trim();
        if (hasOnlyIcon && !element.getAttribute('aria-label')) {
            const icon = element.querySelector('i');
            if (icon) {
                const iconClass = icon.className;
                if (iconClass.includes('cart')) {
                    element.setAttribute('aria-label', 'Shopping Cart');
                } else if (iconClass.includes('heart')) {
                    element.setAttribute('aria-label', 'Add to Wishlist');
                } else if (iconClass.includes('search')) {
                    element.setAttribute('aria-label', 'Search');
                } else if (iconClass.includes('user')) {
                    element.setAttribute('aria-label', 'User Account');
                } else if (iconClass.includes('minus')) {
                    element.setAttribute('aria-label', 'Decrease Quantity');
                } else if (iconClass.includes('plus')) {
                    element.setAttribute('aria-label', 'Increase Quantity');
                }
            }
        }
    });

    // Add role and aria-label to navigation
    const nav = document.querySelector('nav');
    if (nav && !nav.getAttribute('role')) {
        nav.setAttribute('role', 'navigation');
        nav.setAttribute('aria-label', 'Main navigation');
    }

    // Add aria-label to search form
    const searchForm = document.querySelector('form[action*="search"]');
    if (searchForm && !searchForm.getAttribute('aria-label')) {
        searchForm.setAttribute('aria-label', 'Search books');
    }

    // Mark current page in navigation
    const currentPath = window.location.pathname;
    document.querySelectorAll('nav a').forEach(link => {
        if (link.pathname === currentPath) {
            link.setAttribute('aria-current', 'page');
        }
    });

    // Add role to main content area
    const main = document.querySelector('main') || document.querySelector('.container');
    if (main && !main.getAttribute('role')) {
        main.setAttribute('role', 'main');
        main.id = 'main-content';
    }

    // Add aria-live region for notifications
    if (!document.getElementById('aria-live-region')) {
        const liveRegion = document.createElement('div');
        liveRegion.id = 'aria-live-region';
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        document.body.appendChild(liveRegion);
    }
}

/**
 * Setup keyboard navigation
 */
function setupKeyboardNavigation() {
    // Escape key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.querySelector('.modal[role="dialog"]:not([hidden])');
            if (modal) {
                const closeButton = modal.querySelector('[data-dismiss="modal"], .modal-close');
                if (closeButton) closeButton.click();
            }

            const mobileMenu = document.querySelector('.mobile-menu.active');
            if (mobileMenu) {
                mobileMenu.classList.remove('active');
            }
        }
    });

    // Enter key on buttons
    document.querySelectorAll('[role="button"]').forEach(button => {
        button.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        // Make it keyboard focusable
        if (!button.hasAttribute('tabindex')) {
            button.setAttribute('tabindex', '0');
        }
    });

    // Arrow key navigation for product grids
    const bookCards = document.querySelectorAll('.book-card');
    if (bookCards.length > 0) {
        bookCards.forEach((card, index) => {
            card.addEventListener('keydown', function(e) {
                let targetIndex;
                const cols = window.innerWidth >= 768 ? 4 : 1;

                switch(e.key) {
                    case 'ArrowRight':
                        targetIndex = index + 1;
                        break;
                    case 'ArrowLeft':
                        targetIndex = index - 1;
                        break;
                    case 'ArrowDown':
                        targetIndex = index + cols;
                        break;
                    case 'ArrowUp':
                        targetIndex = index - cols;
                        break;
                }

                if (targetIndex !== undefined && bookCards[targetIndex]) {
                    e.preventDefault();
                    bookCards[targetIndex].focus();
                }
            });

            // Make cards keyboard focusable
            if (!card.hasAttribute('tabindex')) {
                card.setAttribute('tabindex', '0');
            }
        });
    }
}

/**
 * Add skip to main content link
 */
function addSkipLink() {
    if (!document.querySelector('.skip-to-main')) {
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.className = 'skip-to-main';
        skipLink.textContent = 'Skip to main content';
        skipLink.addEventListener('click', function(e) {
            e.preventDefault();
            const main = document.getElementById('main-content');
            if (main) {
                main.setAttribute('tabindex', '-1');
                main.focus();
                window.scrollTo({
                    top: main.offsetTop,
                    behavior: 'smooth'
                });
            }
        });
        document.body.insertBefore(skipLink, document.body.firstChild);
    }
}

/**
 * Enhance form accessibility
 */
function enhanceFormAccessibility() {
    // Associate labels with inputs
    document.querySelectorAll('input, textarea, select').forEach(input => {
        const id = input.id || `input-${Math.random().toString(36).substr(2, 9)}`;
        input.id = id;

        // Find or create label
        let label = document.querySelector(`label[for="${id}"]`);
        if (!label) {
            const parentLabel = input.closest('label');
            if (parentLabel) {
                label = parentLabel;
            } else {
                // Check for placeholder as fallback
                const placeholder = input.getAttribute('placeholder');
                if (placeholder && !input.getAttribute('aria-label')) {
                    input.setAttribute('aria-label', placeholder);
                }
            }
        }

        // Add required indicator
        if (input.required && label && !label.classList.contains('required')) {
            label.classList.add('required');
        }

        // Add aria-required
        if (input.required) {
            input.setAttribute('aria-required', 'true');
        }

        // Add aria-invalid for validation
        input.addEventListener('invalid', function() {
            this.setAttribute('aria-invalid', 'true');
        });

        input.addEventListener('input', function() {
            if (this.validity.valid) {
                this.removeAttribute('aria-invalid');
            }
        });
    });

    // Announce form errors to screen readers
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const invalidInputs = form.querySelectorAll(':invalid');
            if (invalidInputs.length > 0) {
                const errorMessage = `Form has ${invalidInputs.length} error${invalidInputs.length > 1 ? 's' : ''}. Please correct the highlighted fields.`;
                announceToScreenReader(errorMessage);
                
                // Focus first invalid input
                invalidInputs[0].focus();
            }
        });
    });
}

/**
 * Setup modal dialog accessibility
 */
function setupModalAccessibility() {
    // Trap focus within modal
    document.addEventListener('focus', function(e) {
        const modal = document.querySelector('.modal[role="dialog"]:not([hidden])');
        if (modal && !modal.contains(e.target)) {
            e.stopPropagation();
            const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusable.length > 0) {
                focusable[0].focus();
            }
        }
    }, true);

    // Return focus to trigger element when modal closes
    let lastFocusedElement;
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'hidden') {
                const modal = mutation.target;
                if (modal.hasAttribute('hidden') && lastFocusedElement) {
                    lastFocusedElement.focus();
                    lastFocusedElement = null;
                } else if (!modal.hasAttribute('hidden')) {
                    lastFocusedElement = document.activeElement;
                    // Focus first focusable element in modal
                    const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                    if (focusable.length > 0) {
                        focusable[0].focus();
                    }
                }
            }
        });
    });

    document.querySelectorAll('[role="dialog"]').forEach(modal => {
        observer.observe(modal, { attributes: true });
    });
}

/**
 * Announce page changes to screen readers
 */
function announcePageChanges() {
    // Monitor cart count changes
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'characterData') {
                    const count = cartCount.textContent;
                    announceToScreenReader(`Cart updated. ${count} item${count !== '1' ? 's' : ''} in cart.`);
                }
            });
        });
        observer.observe(cartCount, { childList: true, characterData: true, subtree: true });
    }

    // Monitor toast notifications
    const toastObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.classList && node.classList.contains('toast')) {
                    const message = node.textContent;
                    announceToScreenReader(message);
                }
            });
        });
    });
    toastObserver.observe(document.body, { childList: true, subtree: true });
}

/**
 * Announce message to screen readers
 * @param {string} message - Message to announce
 */
function announceToScreenReader(message) {
    const liveRegion = document.getElementById('aria-live-region');
    if (liveRegion) {
        liveRegion.textContent = message;
        // Clear after 3 seconds
        setTimeout(() => {
            liveRegion.textContent = '';
        }, 3000);
    }
}

/**
 * Add alt text to images missing it
 */
function ensureImageAltText() {
    document.querySelectorAll('img').forEach(img => {
        if (!img.hasAttribute('alt')) {
            // Try to get meaningful alt text from context
            const caption = img.closest('figure')?.querySelector('figcaption')?.textContent;
            const title = img.getAttribute('title');
            const filename = img.src.split('/').pop().split('.')[0];
            
            img.alt = caption || title || filename.replace(/-|_/g, ' ');
        }
    });
}

// Run image check on load and when new images are added
document.addEventListener('DOMContentLoaded', ensureImageAltText);
new MutationObserver(ensureImageAltText).observe(document.body, { childList: true, subtree: true });

/**
 * Enhance tables with ARIA
 */
function enhanceTableAccessibility() {
    document.querySelectorAll('table').forEach(table => {
        if (!table.hasAttribute('role')) {
            table.setAttribute('role', 'table');
        }
        
        // Add caption if missing
        if (!table.querySelector('caption')) {
            const heading = table.previousElementSibling;
            if (heading && heading.matches('h1, h2, h3, h4, h5, h6')) {
                const caption = document.createElement('caption');
                caption.className = 'sr-only';
                caption.textContent = heading.textContent;
                table.insertBefore(caption, table.firstChild);
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', enhanceTableAccessibility);

// Export functions for use in other scripts
window.accessibility = {
    announce: announceToScreenReader,
    initializeARIA: initializeARIA
};
