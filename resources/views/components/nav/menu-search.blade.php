<div class="menu-search-container px-3 py-3" style="border-bottom: 1px solid rgba(0, 0, 0, 0.08);">
    <div class="position-relative">
        <input 
            type="text" 
            id="menu-search-input" 
            class="menu-search-input" 
            placeholder="Search menu items..."
            autocomplete="off"
        />
        <i class="ri-search-line menu-search-icon"></i>
        <button 
            type="button" 
            id="menu-search-clear" 
            class="menu-search-clear d-none"
            aria-label="Clear search"
        >
            <i class="ri-close-line"></i>
        </button>
    </div>
</div>

<style>
    .menu-search-container {
        background-color: rgba(255, 255, 255, 0.5);
    }
    
    .menu-search-input {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.5rem 2.5rem 0.5rem 2.5rem;
        font-size: 0.875rem;
        color: #1a202c;
        background-color: #ffffff;
        transition: all 0.2s ease;
    }
    
    .menu-search-input::placeholder {
        color: #9ca3af;
    }
    
    .menu-search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
        background-color: #ffffff;
    }
    
    .menu-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1rem;
        pointer-events: none;
    }
    
    .menu-search-clear {
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        padding: 0.25rem;
        cursor: pointer;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
    }
    
    .menu-search-clear:hover {
        color: #667eea;
    }
    
    .menu-search-clear i {
        font-size: 1.125rem;
    }
    
    .menu-item-hidden {
        display: none !important;
    }
    
    .menu-item-highlight {
        background-color: rgba(102, 126, 234, 0.1) !important;
        border-radius: 4px;
    }
    
    .menu-item-highlight .nav-link,
    .menu-item-highlight .menu-link {
        color: #667eea !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('menu-search-input');
    const clearButton = document.getElementById('menu-search-clear');
    const menuContainer = document.getElementById('navbar-nav');
    
    if (!searchInput || !clearButton || !menuContainer) {
        console.warn('Menu search elements not found');
        return;
    }
    
    function getMenuItemText(item) {
        // Get text from span element (most menu items have spans)
        const span = item.querySelector('span[data-key]');
        if (span) {
            return span.textContent.trim();
        }
        
        // Fallback: get text from link, excluding icons
        const link = item.querySelector('.menu-link, .nav-link');
        if (link) {
            const clone = link.cloneNode(true);
            const icons = clone.querySelectorAll('i');
            icons.forEach(icon => icon.remove());
            return clone.textContent.trim();
        }
        
        return '';
    }
    
    function getAllMenuTexts(item, searchLower) {
        const texts = [];
        const itemText = getMenuItemText(item).toLowerCase();
        if (itemText) {
            texts.push(itemText);
        }
        
        // Get all nested item texts
        const nestedItems = item.querySelectorAll('.menu-dropdown .nav-item');
        nestedItems.forEach(nestedItem => {
            const nestedText = getMenuItemText(nestedItem).toLowerCase();
            if (nestedText) {
                texts.push(nestedText);
            }
        });
        
        return texts;
    }
    
    function itemMatchesSearch(item, searchLower) {
        const texts = getAllMenuTexts(item, searchLower);
        return texts.some(text => text.includes(searchLower));
    }
    
    function expandMenuItem(item) {
        const menuLink = item.querySelector('.menu-link');
        const dropdown = item.querySelector('.menu-dropdown');
        
        if (menuLink && dropdown) {
            menuLink.classList.remove('collapsed');
            menuLink.setAttribute('aria-expanded', 'true');
            dropdown.classList.add('show');
        }
    }
    
    function expandAllParents(item) {
        let current = item;
        while (current && current !== menuContainer) {
            expandMenuItem(current);
            current = current.parentElement?.closest('.nav-item');
        }
    }
    
    function filterMenuItems(searchTerm) {
        const searchLower = searchTerm.toLowerCase().trim();
        const allNavItems = Array.from(menuContainer.querySelectorAll('.nav-item'));
        
        // Remove all highlights
        menuContainer.querySelectorAll('.menu-item-highlight').forEach(el => {
            el.classList.remove('menu-item-highlight');
        });
        
        if (!searchTerm) {
            // Show all items
            allNavItems.forEach(item => {
                item.classList.remove('menu-item-hidden');
            });
            clearButton.classList.add('d-none');
            return;
        }
        
        // Find matching items
        const matchingItems = [];
        const matchingItemIds = new Set();
        
        allNavItems.forEach(item => {
            if (itemMatchesSearch(item, searchLower)) {
                matchingItems.push(item);
                matchingItemIds.add(item);
                
                // Also mark all parent items
                let parent = item.parentElement?.closest('.nav-item');
                while (parent) {
                    matchingItemIds.add(parent);
                    parent = parent.parentElement?.closest('.nav-item');
                }
            }
        });
        
        // Show/hide items
        allNavItems.forEach(item => {
            if (matchingItemIds.has(item)) {
                item.classList.remove('menu-item-hidden');
                
                // Highlight if it directly matches
                const itemText = getMenuItemText(item).toLowerCase();
                if (itemText.includes(searchLower)) {
                    item.classList.add('menu-item-highlight');
                }
                
                // Expand if it has children
                expandMenuItem(item);
                
                // Highlight matching nested items
                const nestedItems = item.querySelectorAll('.menu-dropdown .nav-item');
                nestedItems.forEach(nestedItem => {
                    const nestedText = getMenuItemText(nestedItem).toLowerCase();
                    if (nestedText.includes(searchLower)) {
                        nestedItem.classList.add('menu-item-highlight');
                    }
                });
            } else {
                // Check if parent is visible
                const parent = item.parentElement?.closest('.nav-item');
                if (!parent || matchingItemIds.has(parent)) {
                    // Keep visible if parent matches
                    item.classList.remove('menu-item-hidden');
                } else {
                    item.classList.add('menu-item-hidden');
                }
            }
        });
        
        // Expand all parent menus of matching items
        matchingItems.forEach(item => {
            expandAllParents(item);
        });
        
        clearButton.classList.toggle('d-none', matchingItems.length === 0);
    }
    
    // Search input event
    searchInput.addEventListener('input', function(e) {
        filterMenuItems(e.target.value);
    });
    
    // Clear button event
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        filterMenuItems('');
        searchInput.focus();
    });
    
    // Clear on Escape key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            searchInput.value = '';
            filterMenuItems('');
            searchInput.blur();
        }
    });
});
</script>
