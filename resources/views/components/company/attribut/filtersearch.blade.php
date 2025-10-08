{{-- @props([
    'tableId',
    'types' => [],
    'ajaxUrl' => null,
])

<div class="bg-white rounded-xl shadow-sm p-6 mb-4 border border-gray-200">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Search input -->
            <div class="relative">
                <input
                    type="text"
                    id="{{ $tableId }}SearchInput"
                    placeholder="Cari nama perusahaan..."
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                />
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>

            <!-- Filter Jenis Perusahaan -->
            <select id="{{ $tableId }}TypeFilter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                <option value="">Semua Jenis</option>
                @foreach($types as $type)
                    <option value="{{ $type->company_type_id }}">{{ $type->type_name }}</option>
                @endforeach
            </select>

            <!-- Filter Status -->
            <select id="{{ $tableId }}StatusFilter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>
    </div>
</div>

<!-- Pagination Placeholder -->
<div class="pagination-container mt-4"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableId = '{{ $tableId }}';
    const ajaxUrl = '{{ $ajaxUrl }}';

    const input = document.getElementById(tableId + 'SearchInput');
    const typeFilter = document.getElementById(tableId + 'TypeFilter');
    const statusFilter = document.getElementById(tableId + 'StatusFilter');
    let debounceTimer;

    const tbody = document.querySelector(`#${tableId} tbody`);
    const paginationContainer = document.querySelector('.pagination-container');

    if (!tbody || !ajaxUrl) return;

    function fetchData(page = 1) {
        const search = input?.value || '';
        const type = typeFilter?.value || '';
        const status = statusFilter?.value || '';

        // Loading state
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-6"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>`;

        const query = new URLSearchParams({
            search,
            type,
            status,
            page,
        });

        fetch(`${ajaxUrl}?${query.toString()}`)
            .then(res => res.json())
            .then(data => {
                renderTable(data.data, data.meta);
                renderPagination(data.meta);
            })
            .catch(() => {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-red-600 py-6">Gagal memuat data</td></tr>`;
            });
    }

    function renderTable(companies, meta) {
        if (!companies.length) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-6 text-gray-500">Tidak ada data ditemukan.</td></tr>`;
            return;
        }

        let rows = '';
        companies.forEach((company, index) => {
            const rowNum = meta.from + index;
            rows += `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">${rowNum}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${company.company_name}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${company.company_type?.type_name ?? '-'}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${company.tier ?? '-'}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 truncate max-w-xs" title="${company.description ?? '-'}">${company.description ?? '-'}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${company.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${company.status.charAt(0).toUpperCase() + company.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button onclick="openEditCompanyModal(
                            '${company.company_id}',
                            '${company.company_name.replace(/'/g, "\\'")}',
                            '${company.company_type_id ?? ''}',
                            '${company.tier ?? ''}',
                            \`${(company.description ?? '').replace(/`/g, '\\`')}\`,
                            '${company.status}'
                        )" class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = rows;
    }

    function renderPagination(meta) {
        if (!paginationContainer) return;

        let html = `<div class="flex justify-center flex-wrap gap-1">`;

        if (meta.current_page > 1) {
            html += `<button class="pagination-link px-3 py-1 border rounded" data-page="${meta.current_page - 1}">Prev</button>`;
        }

        for (let i = 1; i <= meta.last_page; i++) {
            html += `<button class="pagination-link px-3 py-1 border rounded ${i === meta.current_page ? 'bg-blue-600 text-white' : ''}" data-page="${i}">${i}</button>`;
        }

        if (meta.current_page < meta.last_page) {
            html += `<button class="pagination-link px-3 py-1 border rounded" data-page="${meta.current_page + 1}">Next</button>`;
        }

        html += `</div>`;
        paginationContainer.innerHTML = html;
    }

    // Events
    input?.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchData(1), 400);
    });

    typeFilter?.addEventListener('change', () => fetchData(1));
    statusFilter?.addEventListener('change', () => fetchData(1));

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('pagination-link')) {
            e.preventDefault();
            const page = e.target.dataset.page;
            fetchData(parseInt(page));
        }
    });

    // Load initial data
    fetchData(1);
});
</script> --}}
