/**
 * Reusable Table Search and Filter Handler
 * Bisa digunakan untuk semua table dengan konfigurasi berbeda
 * 
 * Usage:
 * const handler = new TableHandler({
 *     tableId: 'userTable',
 *     ajaxUrl: '/users/search',
 *     filters: ['role', 'status'],
 *     columns: ['number', 'user', 'phone', 'date_birth', 'alamat', 'role', 'status', 'actions'],
 *     searchParam: 'q' // ✅ BARU: nama parameter search (default: 'search')
 * });
 */

class TableHandler {
    constructor(config) {
        console.log('🔧 TableHandler constructor called with config:', config);
        
        this.config = {
            tableId: null,
            ajaxUrl: null,
            filters: [],
            columns: [],
            debounceDelay: 400,
            pageSize: 10,
            searchParam: 'search', // ✅ Default parameter name untuk search
            ...config
        };

        console.log('📋 Final config:', this.config);

        // ✅ DOM elements - support multiple ID patterns for backward compatibility
        this.searchInput = document.getElementById('searchInput') || 
                          document.getElementById(`${this.config.tableId}SearchInput`) ||
                          document.querySelector('input[type="text"][placeholder*="Cari"]');
        
        this.tbody = document.querySelector(`#${this.config.tableId} tbody`);
        
        this.paginationContainer = document.querySelector('.pagination-container') ||
                                   document.querySelector(`#${this.config.tableId}-pagination`);

        // State
        this.debounceTimer = null;
        this.currentPage = 1;
        this.lastParams = {}; // ✅ Store untuk debugging

        console.log('📍 DOM Elements found:', {
            searchInput: !!this.searchInput,
            tbody: !!this.tbody,
            paginationContainer: !!this.paginationContainer
        });

        // Validate setup
        if (!this.tbody || !this.config.ajaxUrl) {
            console.error('❌ TableHandler: Missing required elements or config', {
                tbody: !!this.tbody,
                ajaxUrl: this.config.ajaxUrl
            });
            return;
        }

        this.init();
    }

    init() {
        console.log('🚀 Initializing TableHandler...');
        this.attachSearchListener();
        this.attachFilterListeners();
        this.attachPaginationListener();
        console.log('✅ TableHandler initialized');
    }

    attachSearchListener() {
        if (!this.searchInput) {
            console.warn('⚠️ Search input not found');
            return;
        }
        
        console.log('✅ Attaching search listener to:', this.searchInput.id);
        
        this.searchInput.addEventListener('input', (e) => {
            console.log('🔍 Search input event:', e.target.value);
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                console.log('⏱️ Search debounce executed');
                this.fetchData(1);
            }, this.config.debounceDelay);
        });
    }

    attachFilterListeners() {
        console.log('📌 Attaching filter listeners for:', this.config.filters);
        
        this.config.filters.forEach(filterName => {
            // ✅ Try multiple ID patterns for flexibility
            const possibleIds = [
                `filter${filterName.charAt(0).toUpperCase() + filterName.slice(1)}`, // filterSales
                `${this.config.tableId}_${this.slugify(filterName)}Filter`, // salesVisitTable_salesFilter
                filterName // sales (direct)
            ];

            let filterElement = null;
            
            // Try to find element by data-filter attribute first (most reliable)
            filterElement = document.querySelector(`[data-filter="${filterName}"]`);
            
            // If not found, try by ID
            if (!filterElement) {
                for (const id of possibleIds) {
                    filterElement = document.getElementById(id);
                    if (filterElement) {
                        console.log(`✅ Filter found by ID: ${id} for filter: ${filterName}`);
                        break;
                    }
                }
            } else {
                console.log(`✅ Filter found by data-filter: ${filterName}`);
            }

            if (filterElement) {
                console.log(`✅ Attaching listener to filter: ${filterName}`, {
                    id: filterElement.id,
                    currentValue: filterElement.value
                });
                
                filterElement.addEventListener('change', (e) => {
                    console.log(`🔄 Filter changed: ${filterName} = ${e.target.value}`);
                    this.fetchData(1);
                });
            } else {
                console.warn(`⚠️ Filter element NOT found for: ${filterName}`);
            }
        });
    }

    attachPaginationListener() {
        document.addEventListener('click', (e) => {
            const link = e.target.closest('.pagination-link');
            if (link) {
                e.preventDefault();
                const page = link.dataset.page;
                if (page) {
                    console.log('📄 Pagination clicked, page:', page);
                    this.fetchData(page);
                }
            }
        });
    }

    slugify(str) {
        return str.toLowerCase().replaceAll(' ', '_');
    }

    buildParams(page = 1) {
        const params = new URLSearchParams();
        
        // ✅ Page
        params.append('page', page);
        
        // ✅ Search keyword - use configured parameter name
        if (this.searchInput?.value && this.searchInput.value.trim() !== '') {
            params.append(this.config.searchParam, this.searchInput.value.trim());
            console.log(`✅ Search param added: ${this.config.searchParam} = ${this.searchInput.value.trim()}`);
        }

        // ✅ Add filter parameters
        this.config.filters.forEach(filterName => {
            // Try to find filter element by data-filter attribute first
            let filterElement = document.querySelector(`[data-filter="${filterName}"]`);
            
            // Fallback to ID-based search
            if (!filterElement) {
                const possibleIds = [
                    `filter${filterName.charAt(0).toUpperCase() + filterName.slice(1)}`,
                    `${this.config.tableId}_${this.slugify(filterName)}Filter`,
                    filterName
                ];
                
                for (const id of possibleIds) {
                    filterElement = document.getElementById(id);
                    if (filterElement) break;
                }
            }

            if (filterElement && filterElement.value && 
                filterElement.value !== '' && filterElement.value !== 'all') {
                // ✅ Use filter name as-is (lowercase) untuk konsistensi dengan backend
                params.append(filterName.toLowerCase(), filterElement.value);
                console.log(`✅ Filter param added: ${filterName.toLowerCase()} = ${filterElement.value}`);
            }
        });

        return params;
    }

    async fetchData(page = 1) {
        if (!this.tbody) {
            console.error('❌ tbody not found');
            return;
        }

        console.log('📡 Fetching data for page:', page);
        
        this.showLoading();

        try {
            const params = this.buildParams(page);
            this.lastParams = Object.fromEntries(params); // Store for debugging
            
            const url = `${this.config.ajaxUrl}?${params.toString()}`;
            console.log('🌐 Fetch URL:', url);
            console.log('📤 Params:', this.lastParams);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            console.log('📥 Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();
            console.log('✅ Data received:', {
                items: data.items?.length || 0,
                total: data.pagination?.total || 0
            });
            
            if (data.debug) {
                console.log('🐛 Backend debug:', data.debug);
            }

            this.renderTable(data.items || []);
            this.renderPagination(data.pagination || {});
            this.currentPage = page;

        } catch (error) {
            console.error('❌ Fetch error:', error);
            this.showError(error.message);
        }
    }

    renderTable(items) {
        if (!items?.length) {
            console.log('ℹ️ No items to render');
            this.tbody.innerHTML = `
                <tr>
                    <td colspan="${this.config.columns.length}" class="text-center py-12">
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="width: 6rem; height: 6rem; border-radius: 9999px; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: #d1d5db;"></i>
                            </div>
                            <h3 style="font-size: 1.125rem; font-weight: 500; color: #111827; margin: 0 0 0.25rem 0;">Tidak Ada Data</h3>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Tidak ada data yang sesuai dengan pencarian atau filter Anda</p>
                        </div>
                    </td>
                </tr>`;
            return;
        }

        console.log(`✅ Rendering ${items.length} items`);
        this.tbody.innerHTML = items.map(item => this.renderRow(item)).join('');
    }

    renderRow(item) {
        let row = `<tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.15s;" 
                       onmouseover="this.style.backgroundColor='#f9fafb'" 
                       onmouseout="this.style.backgroundColor='#ffffff'">`;

        this.config.columns.forEach(col => {
            if (col === 'actions') return;

            let value = item[col];

            // Handle nested values
            if (value === undefined && col.includes('.')) {
                value = this.getNestedValue(item, col);
            }

            // Default to '-' if empty
            if (value === undefined || value === null || value === '') {
                value = '-';
            }

            // ✅ Special handling for 'sales' column (SalesVisit specific)
            if (col === 'sales' && typeof item.sales === 'object' && item.sales !== null) {
                const username = item.sales.username || '-';
                const email = item.sales.email || 'No email';
                row += `
                    <td style="padding: 0.5rem 0.75rem; white-space: nowrap;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 2rem; height: 2rem; flex-shrink: 0;">
                                <div style="width: 2rem; height: 2rem; border-radius: 9999px; background-color: #e0e7ff; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-tie" style="color: #6366f1; font-size: 0.75rem;"></i>
                                </div>
                            </div>
                            <div style="margin-left: 0.5rem;">
                                <div style="font-size: 0.8125rem; font-weight: 500; color: #111827;">${this.escapeHTML(username)}</div>
                                <div style="font-size: 0.6875rem; color: #6b7280;">${this.escapeHTML(email)}</div>
                            </div>
                        </div>
                    </td>`;
                return;
            }

            // ✅ Special handling for 'user' column (User management specific)
            if (col === 'user' && typeof item.user === 'object' && item.user !== null) {
                const username = item.user.username || '-';
                const email = item.user.email || '-';
                row += `
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">${this.escapeHTML(username)}</div>
                                <div class="text-sm text-gray-500">${this.escapeHTML(email)}</div>
                            </div>
                        </div>
                    </td>`;
                return;
            }

            // ✅ Special handling for 'customer' column
            if (col === 'customer') {
                row += `
                    <td style="padding: 0.5rem 0.75rem; white-space: nowrap;">
                        <div style="font-size: 0.8125rem; font-weight: 500; color: #111827;">${this.escapeHTML(String(value))}</div>
                    </td>`;
                return;
            }

            // ✅ Special handling for 'company' column
            if (col === 'company') {
                row += `
                    <td style="padding: 0.5rem 0.75rem;">
                        <div style="font-size: 0.8125rem; color: #111827;">${this.escapeHTML(String(value))}</div>
                    </td>`;
                return;
            }

            // ✅ Special handling for 'location' column
            if (col === 'location') {
                row += `
                    <td style="padding: 0.5rem 0.75rem;">
                        <div style="font-size: 0.8125rem; color: #111827;">${this.escapeHTML(String(value))}</div>
                    </td>`;
                return;
            }

            // ✅ Special handling for 'visit_date' column
            if (col === 'visit_date') {
                row += `
                    <td style="padding: 0.5rem 0.75rem; white-space: nowrap;">
                        <div style="font-size: 0.8125rem; color: #111827;">
                            ${value !== '-' ? `
                                <div style="display: flex; align-items: center; gap: 0.375rem;">
                                    <i class="fas fa-calendar" style="color: #9ca3af; font-size: 0.6875rem;"></i>
                                    <span>${this.escapeHTML(String(value))}</span>
                                </div>
                            ` : '<span style="color: #9ca3af;">-</span>'}
                        </div>
                    </td>`;
                return;
            }

            // ✅ Special handling for 'purpose' column
            if (col === 'purpose') {
                row += `
                    <td style="padding: 0.5rem 0.75rem;">
                        <div style="font-size: 0.8125rem; color: #374151; max-width: 16rem;">
                            ${value !== '-' ? `
                                <span style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="${this.escapeHTML(String(value))}">
                                    ${this.escapeHTML(String(value))}
                                </span>
                            ` : '<span style="color: #9ca3af;">-</span>'}
                        </div>
                    </td>`;
                return;
            }

            // ✅ Special handling for 'follow_up' column
            if (col === 'follow_up') {
                const isYes = String(value).toLowerCase() === 'ya' || String(value).toLowerCase() === 'yes';
                row += `
                    <td style="padding: 0.5rem 0.75rem; white-space: nowrap;">
                        ${isYes ? `
                            <span style="display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 500; background-color: #d1fae5; color: #065f46;">
                                <i class="fas fa-check-circle" style="margin-right: 0.25rem; font-size: 0.625rem;"></i>
                                Ya
                            </span>
                        ` : `
                            <span style="display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 500; background-color: #f3f4f6; color: #374151;">
                                <i class="fas fa-times-circle" style="margin-right: 0.25rem; font-size: 0.625rem;"></i>
                                Tidak
                            </span>
                        `}
                    </td>`;
                return;
            }

            // Role badge
            if (col === 'role') {
                row += `<td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        ${this.escapeHTML(String(value))}
                    </span>
                </td>`;
                return;
            }

            // Status badge
            if (col === 'status') {
                const isActive = String(value).toLowerCase() === 'active';
                const bgColor = isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                row += `<td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-medium ${bgColor}">
                        ${this.escapeHTML(String(value))}
                    </span>
                </td>`;
                return;
            }

            // Address with special formatting
            if (col === 'alamat') {
                let alamatHtml = `<div class="text-sm text-gray-900">`;

                if (String(value) !== '-' && String(value).includes(' - ')) {
                    const [wilayah, detail] = String(value).split(' - ');
                    alamatHtml += `
                        <div class="font-medium">${this.escapeHTML(wilayah)}</div>
                        <div class="text-xs text-gray-600 mt-1">${this.escapeHTML(detail)}</div>
                    `;
                } else {
                    alamatHtml += this.escapeHTML(String(value));
                }

                alamatHtml += `</div>`;
                row += `<td class="px-6 py-4">${alamatHtml}</td>`;
                return;
            }

            // Number column
            if (col === 'number') {
                row += `<td style="padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #111827; white-space: nowrap;">
                    <span style="font-weight: 500;">${this.escapeHTML(String(value))}</span>
                </td>`;
                return;
            }

            // Default column
            row += `<td class="px-6 py-4 text-sm text-gray-700">${this.escapeHTML(String(value))}</td>`;
        });

        // Actions column
        if (this.config.columns.includes('actions')) {
            row += this.renderActions(item.actions || []);
        }

        row += `</tr>`;
        return row;
    }

    renderActions(actions) {
        if (!Array.isArray(actions)) actions = [];

        let html = `<td style="padding: 0.5rem 0.75rem; text-align: right; white-space: nowrap;">
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.375rem;">`;

        actions.forEach(action => {
            const iconMap = {
                edit: 'edit',
                delete: 'trash',
                view: 'eye'
            };

            const icon = iconMap[action.type] || 'circle';

            if (action.type === 'edit') {
                html += `
                    <button 
                        onclick="${action.onclick || ''}"
                        style="color: #2563eb; background: transparent; border: none; padding: 0.375rem; border-radius: 0.375rem; cursor: pointer; transition: all 0.15s; font-size: 0.875rem;"
                        onmouseover="this.style.backgroundColor='#dbeafe'; this.style.color='#1e40af';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#2563eb';"
                        title="${action.title || 'Edit'}">
                        <i class="fas fa-${icon}"></i>
                    </button>`;
            } else if (action.type === 'delete') {
                html += `
                    <button 
                        onclick="${action.onclick || ''}"
                        style="color: #dc2626; background: transparent; border: none; padding: 0.375rem; border-radius: 0.375rem; cursor: pointer; transition: all 0.15s; font-size: 0.875rem;"
                        onmouseover="this.style.backgroundColor='#fee2e2'; this.style.color='#991b1b';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#dc2626';"
                        title="${action.title || 'Delete'}">
                        <i class="fas fa-${icon}"></i>
                    </button>`;
            } else if (action.type === 'view') {
                html += `
                    <button 
                        onclick="${action.onclick || ''}"
                        class="p-2 rounded-lg flex items-center transition-colors text-green-600 hover:text-green-900 hover:bg-green-50"
                        title="${action.title || 'View'}">
                        <i class="fas fa-${icon}"></i>
                    </button>`;
            }
        });

        html += `</div></td>`;
        return html;
    }

    renderPagination(pagination) {
        if (!this.paginationContainer) {
            console.warn('⚠️ Pagination container not found');
            return;
        }

        const { current_page = 1, last_page = 1, from = 0, to = 0, total = 0 } = pagination;

        console.log('📄 Rendering pagination:', { current_page, last_page, from, to, total });

        // Hide if only 1 page
        if (last_page <= 1) {
            this.paginationContainer.style.display = 'none';
            return;
        }

        this.paginationContainer.style.display = 'block';

        let html = `
            <div style="padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div style="font-size: 0.875rem; color: #6b7280;">
                    Showing <span style="font-weight: 500;">${from}</span> to <span style="font-weight: 500;">${to}</span>
                    of <span style="font-weight: 500;">${total}</span> results
                </div>
                <div style="display: flex; gap: 0.5rem;">`;

        const btn = (page, text, active = false) => `
            <button class="pagination-link" data-page="${page}"
                style="padding: 0.5rem 0.75rem; border: 1px solid ${active ? '#6366f1' : '#e5e7eb'}; border-radius: 0.375rem; background: ${active ? '#6366f1' : 'white'}; color: ${active ? 'white' : '#374151'}; cursor: pointer; transition: all 0.2s; font-weight: ${active ? '500' : '400'};"
                ${!active ? `onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='white'"` : ''}>
                ${text}
            </button>`;

        if (current_page > 1) {
            html += btn(current_page - 1, '<i class="fas fa-chevron-left"></i>');
        }

        const max = 5;
        const start = Math.max(1, current_page - Math.floor(max / 2));
        const end = Math.min(last_page, start + max - 1);

        if (start > 1) {
            html += btn(1, '1') + (start > 2 ? '<span style="padding: 0 0.5rem; color: #9ca3af;">...</span>' : '');
        }

        for (let i = start; i <= end; i++) {
            html += btn(i, i, i === current_page);
        }

        if (end < last_page) {
            html += (end < last_page - 1 ? '<span style="padding: 0 0.5rem; color: #9ca3af;">...</span>' : '') + btn(last_page, last_page);
        }

        if (current_page < last_page) {
            html += btn(current_page + 1, '<i class="fas fa-chevron-right"></i>');
        }

        html += '</div></div>';
        this.paginationContainer.innerHTML = html;
    }

    escapeHTML(str) {
        const div = document.createElement('div');
        div.textContent = String(str);
        return div.innerHTML;
    }

    getNestedValue(obj, path) {
        try {
            return path.split('.').reduce((val, key) =>
                val && val[key] !== undefined ? val[key] : undefined, obj);
        } catch {
            return undefined;
        }
    }

    showLoading() {
        if (this.tbody) {
            this.tbody.innerHTML = `
                <tr>
                    <td colspan="${this.config.columns.length}" class="text-center py-8">
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 2.5rem; color: #9ca3af; margin-bottom: 0.75rem;"></i>
                            <p style="color: #6b7280; font-size: 0.875rem;">Loading...</p>
                        </div>
                    </td>
                </tr>`;
        }
    }

    showError(message = 'Error loading data') {
        if (this.tbody) {
            this.tbody.innerHTML = `
                <tr>
                    <td colspan="${this.config.columns.length}" class="text-center py-8">
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 2.5rem; color: #ef4444; margin-bottom: 0.75rem;"></i>
                            <p style="color: #dc2626; font-weight: 500;">${this.escapeHTML(message)}</p>
                        </div>
                    </td>
                </tr>`;
        }
    }

    // ✅ Public method untuk refresh data
    refresh(page = 1) {
        this.fetchData(page);
    }

    // ✅ Helper untuk debugging
    getLastParams() {
        return this.lastParams;
    }

    // ✅ Helper untuk manual search
    search(keyword) {
        if (this.searchInput) {
            this.searchInput.value = keyword;
            this.fetchData(1);
        }
    }
}

/**
 * Delete Handler Function
 * Bisa digunakan untuk semua delete operations
 */
function deleteRecord(recordId, deleteRoute, csrfToken, onSuccess = null) {
    if (!confirm('Are you sure you want to delete this record?')) {
        return;
    }

    fetch(deleteRoute, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Call callback if provided
        if (onSuccess && typeof onSuccess === 'function') {
            onSuccess(data);
        } else {
            // Default: refresh page
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting record');
    });
}

console.log('✅ TableHandler class loaded and ready');