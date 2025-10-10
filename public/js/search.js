/**
 * Reusable Table Search and Filter Handler
 * Bisa digunakan untuk semua table dengan konfigurasi berbeda
 * 
 * Usage:
 * const handler = new TableHandler({
 *     tableId: 'userTable',
 *     ajaxUrl: '/users/search',
 *     filters: ['role', 'status'],
 *     columns: ['number', 'user', 'phone', 'date_birth', 'alamat', 'role', 'status', 'actions']
 * });
 */

class TableHandler {
    constructor(config) {
        this.config = {
            tableId: null,
            ajaxUrl: null,
            filters: [],
            columns: [],
            debounceDelay: 400,
            pageSize: 5,
            ...config
        };

        // DOM elements
        this.searchInput = document.getElementById(`${this.config.tableId}SearchInput`);
        this.tbody = document.querySelector(`#${this.config.tableId} tbody`);
        this.paginationContainer = document.querySelector(`#${this.config.tableId}-pagination`);

        // State
        this.debounceTimer = null;
        this.currentPage = 1;

        // Validate setup
        if (!this.tbody || !this.config.ajaxUrl) {
            console.error('TableHandler: Missing required elements or config', {
                tbody: !!this.tbody,
                ajaxUrl: this.config.ajaxUrl
            });
            return;
        }

        this.init();
    }

    init() {
        this.attachSearchListener();
        this.attachFilterListeners();
        this.attachPaginationListener();
    }

    attachSearchListener() {
        if (!this.searchInput) return;
        
        this.searchInput.addEventListener('input', () => {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => this.fetchData(1), this.config.debounceDelay);
        });
    }

    attachFilterListeners() {
        this.config.filters.forEach(filterName => {
            const filterId = this.slugify(filterName);
            const selectId = `${this.config.tableId}_${filterId}Filter`;
            const select = document.getElementById(selectId);

            if (select) {
                select.addEventListener('change', () => this.fetchData(1));
            }
        });
    }

    attachPaginationListener() {
        document.addEventListener('click', (e) => {
            const link = e.target.closest(`#${this.config.tableId}-pagination .pagination-link`);
            if (link) {
                e.preventDefault();
                const page = link.dataset.page;
                if (page) this.fetchData(page);
            }
        });
    }

    slugify(str) {
        return str.toLowerCase().replaceAll(' ', '_');
    }

    buildParams(page = 1) {
        const params = new URLSearchParams({
            search: this.searchInput?.value || '',
            page
        });

        // Add filter parameters
        this.config.filters.forEach(filterName => {
            const filterKey = filterName.toLowerCase();
            const selectId = `${this.config.tableId}_${this.slugify(filterName)}Filter`;
            const select = document.getElementById(selectId);

            if (select?.value) {
                params.append(filterKey, select.value);
            }
        });

        return params;
    }

    async fetchData(page = 1) {
        if (!this.tbody) return;

        this.showLoading();

        try {
            const params = this.buildParams(page);
            const response = await fetch(`${this.config.ajaxUrl}?${params.toString()}`);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            this.renderTable(data.items || []);
            this.renderPagination(data.pagination || {});
            this.currentPage = page;

        } catch (error) {
            console.error('Fetch error:', error);
            this.showError();
        }
    }

    renderTable(items) {
        if (!items?.length) {
            this.tbody.innerHTML = `
                <tr>
                    <td colspan="100%" class="text-center py-8 text-gray-500">
                        No data found
                    </td>
                </tr>`;
            return;
        }

        this.tbody.innerHTML = items.map(item => this.renderRow(item)).join('');
    }

    renderRow(item) {
        let row = `<tr class="hover:bg-gray-50">`;

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

            // Special handling for 'user' column (object with username and email)
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

        let html = `<td class="px-6 py-4 text-sm font-medium">
            <div class="flex items-center space-x-2">`;

        actions.forEach(action => {
            const iconMap = {
                edit: 'edit',
                delete: 'trash',
                view: 'eye'
            };

            const icon = iconMap[action.type] || 'circle';

            // Determine button class based on type
            let buttonClass = 'p-2 rounded-lg flex items-center transition-colors';
            if (action.type === 'edit') {
                buttonClass += ' text-blue-600 hover:text-blue-900 hover:bg-blue-50';
            } else if (action.type === 'delete') {
                buttonClass += ' text-red-600 hover:text-red-900 hover:bg-red-50';
            } else if (action.type === 'view') {
                buttonClass += ' text-green-600 hover:text-green-900 hover:bg-green-50';
            } else {
                buttonClass += ' text-gray-600 hover:text-gray-900 hover:bg-gray-50';
            }

            html += `
                <button onclick="${action.onclick || ''}"
                    class="${buttonClass}"
                    title="${action.title || action.type}">
                    <i class="fas fa-${icon}"></i>
                </button>`;
        });

        html += `</div></td>`;
        return html;
    }

    renderPagination(pagination) {
        if (!this.paginationContainer) return;

        const { current_page = 1, last_page = 1, from = 0, to = 0, total = 0 } = pagination;

        let html = `
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">${from}</span> to <span class="font-medium">${to}</span>
                    of <span class="font-medium">${total}</span> results
                </div>
                <div class="flex space-x-2">`;

        const btn = (page, text, active = false) => `
            <button class="pagination-link px-3 py-1 border rounded transition-colors ${
                active ? 'bg-blue-600 text-white border-blue-600' : 'hover:bg-gray-100 border-gray-300'
            }"
                data-page="${page}">${text}</button>`;

        if (current_page > 1) {
            html += btn(current_page - 1, '<i class="fas fa-chevron-left"></i>');
        }

        const max = 5;
        const start = Math.max(1, current_page - Math.floor(max / 2));
        const end = Math.min(last_page, start + max - 1);

        if (start > 1) {
            html += btn(1, '1') + (start > 2 ? '<span class="px-2">...</span>' : '');
        }

        for (let i = start; i <= end; i++) {
            html += btn(i, i, i === current_page);
        }

        if (end < last_page) {
            html += (end < last_page - 1 ? '<span class="px-2">...</span>' : '') + btn(last_page, last_page);
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
                    <td colspan="100%" class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </td>
                </tr>`;
        }
    }

    showError() {
        if (this.tbody) {
            this.tbody.innerHTML = `
                <tr>
                    <td colspan="100%" class="text-center py-8 text-red-600">
                        Error loading data
                    </td>
                </tr>`;
        }
    }

    // Public method untuk refresh data
    refresh(page = 1) {
        this.fetchData(page);
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