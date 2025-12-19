<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="shadow-sm rounded container-fluid p-4">
    <!-- Header with Stats -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card border-sm shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <h1 class="h3 mb-0 me-3">Search Results</h1>
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    <i class="fas fa-search me-1"></i> <?= count($results) ?> found
                                </span>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <p class="text-muted mb-0">
                                    Searching in: <span class="badge bg-info"><?= get_search_type_label($model_type ?? 'unknown') ?></span>
                                </p>
                                <span class="text-muted">•</span>
                                <p class="text-muted mb-0">
                                    For: "<strong class="text-primary"><?= esc($query) ?></strong>"
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <?php if (!empty($results)): ?>
        <div class="row ">
            <?php
            $stats = [];
            foreach ($results as $result) {
                $type = $result['_search_type'] ?? 'unknown';
                $stats[$type] = ($stats[$type] ?? 0) + 1;
            }
            ?>

        </div>
    <?php endif; ?>

    <!-- Search Refinement -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Refine Search</label>
                            <!-- Update the input group in your HTML -->
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text"
                                    id="liveSearch"
                                    class="form-control border-start-0 ps-0"
                                    placeholder="Type to filter results..."
                                    value="<?= esc($query) ?>"
                                    autocomplete="off">
                                <span class="input-group-text bg-white border-start-0" style="min-width: 40px;">
                                    <div id="searchLoading" class="d-none">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Filter by Type</label>
                            <div class="d-flex flex-wrap gap-2" id="typeFilters">
                                <button class="btn btn-sm btn-outline-secondary active" data-filter="all">
                                    All <span class="badge bg-secondary ms-1"><?= count($results) ?></span>
                                </button>
                                <?php foreach ($stats as $type => $count): ?>
                                    <button class="btn btn-sm btn-outline-<?= get_search_type_color($type) ?>" data-filter="<?= $type ?>">
                                        <i class="<?= get_search_type_icon($type) ?> me-1"></i>
                                        <?= get_search_type_label($type) ?>
                                        <span class="badge bg-<?= get_search_type_color($type) ?> ms-1"><?= $count ?></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="row">
        <div class="col-12">
            <?php if (empty($results)): ?>
                <!-- Empty State -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="empty-state-icon mb-4">
                            <i class="fas fa-search fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">No results found</h4>
                        <p class="text-muted mb-4">Try adjusting your search terms or search in a different category</p>
                        <a href="<?= esc($back_url) ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Return to Dashboard
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- View Toggle -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Showing <?= count($results) ?> results</h6>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary active" id="cardViewBtn" data-view="cards">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="tableViewBtn" data-view="table">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>

                <!-- Cards View -->
                <div id="cardsView" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4">
                    <?php foreach ($results as $result): ?>
                        <?php
                        $type = $result['_search_type'] ?? 'unknown';
                        $color = get_search_type_color($type);
                        $icon = get_search_type_icon($type);
                        $label = get_search_type_label($type);
                        $title = get_search_result_title($result);
                        $keyFields = get_search_key_fields($result);
                        ?>
                        <div class="col search-result" data-type="<?= $type ?>">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <!-- Card Header -->
                                <div class="card-header border-0 pb-0 pt-3 px-3 bg-transparent">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="badge bg-<?= $color ?> bg-opacity-10 text-<?= $color ?> rounded-pill px-3 py-2 mb-2 d-inline-flex align-items-center">
                                                <i class="<?= $icon ?> me-2"></i>
                                                <?= $label ?>
                                            </span>
                                            <h6 class="card-title mb-1 fw-semibold"><?= esc($title) ?></h6>
                                            <small class="text-muted">ID: #<?= $result['id'] ?? 'N/A' ?></small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted p-0" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="<?= base_url($detail_url . '/' . ($result['id'] ?? '')) ?>">
                                                        <i class="fas fa-eye me-2"></i>View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fas fa-edit me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body pt-2 px-3">
                                    <!-- Key Fields -->
                                    <div class="key-fields mb-3">
                                        <?php foreach ($keyFields as $key => $value): ?>
                                            <?php if ($value): ?>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-light text-dark rounded-pill px-2 py-1 me-2" style="font-size: 0.7rem;">
                                                        <?= esc($key) ?>
                                                    </span>
                                                    <span class="text-truncate small"><?= esc($value) ?></span>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Status Badge -->
                                    <?php if (isset($result['status'])): ?>
                                        <?php
                                        $statusColor = 'secondary';
                                        $status = strtolower($result['status']);
                                        if (in_array($status, ['completed', 'delivered', 'success'])) $statusColor = 'success';
                                        elseif (in_array($status, ['active', 'accepted', 'approved'])) $statusColor = 'primary';
                                        elseif (in_array($status, ['pending', 'processing'])) $statusColor = 'warning';
                                        elseif (in_array($status, ['cancelled', 'rejected', 'failed'])) $statusColor = 'danger';
                                        ?>
                                        <div class="mb-3">
                                            <span class="badge bg-<?= $statusColor ?> bg-opacity-10 text-<?= $statusColor ?> rounded-pill px-3 py-1">
                                                Status: <?= ucfirst($result['status']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Dates -->
                                    <div class="d-flex justify-content-between align-items-center text-muted small">
                                        <?php if (isset($result['created_at'])): ?>
                                            <span title="Created">
                                                <i class="fas fa-calendar-plus me-1"></i>
                                                <?= date('M d, Y', strtotime($result['created_at'])) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (isset($result['updated_at']) && $result['updated_at'] != $result['created_at']): ?>
                                            <span title="Updated">
                                                <i class="fas fa-history me-1"></i>
                                                <?= date('M d', strtotime($result['updated_at'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="card-footer border-0 pt-0 px-3 pb-3 bg-transparent">
                                    <div class="d-grid">
                                        <a href="<?= base_url($detail_url . '/' . ($result['id'] ?? '')) ?>"
                                            class="btn btn-outline-<?= $color ?> btn-sm">
                                            <i class="fas fa-external-link-alt me-2"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Table View (Hidden) -->
                <div id="tableView" class="card border-0 shadow-sm d-none">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Type</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $result): ?>
                                    <?php
                                    $type = $result['_search_type'] ?? 'unknown';
                                    $color = get_search_type_color($type);
                                    $icon = get_search_type_icon($type);
                                    $title = get_search_result_title($result);
                                    ?>
                                    <tr class="search-result" data-type="<?= $type ?>">
                                        <td>
                                            <span class="badge bg-light text-dark">#<?= $result['id'] ?? '-' ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper bg-<?= $color ?> bg-opacity-10 text-<?= $color ?> rounded p-2 me-3">
                                                    <i class="<?= $icon ?>"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?= get_search_type_label($type) ?></div>
                                                    <small class="text-muted"><?= esc($title) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <?php
                                                $displayCount = 0;
                                                foreach ($result as $key => $value):
                                                    if (
                                                        !in_array($key, ['id', '_search_type', '_model_class', 'created_at', 'updated_at', 'password'])
                                                        && $value
                                                        && strlen($value) < 50
                                                        && $displayCount < 2
                                                    ):
                                                        $displayCount++;
                                                ?>
                                                        <div class="mb-1">
                                                            <span class="text-muted"><?= ucfirst(str_replace('_', ' ', $key)) ?>:</span>
                                                            <span class="ms-1 fw-medium"><?= esc($value) ?></span>
                                                        </div>
                                                <?php
                                                    endif;
                                                endforeach;
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (isset($result['status'])): ?>
                                                <?php
                                                $statusColor = 'secondary';
                                                $status = strtolower($result['status']);
                                                if (in_array($status, ['completed', 'delivered'])) $statusColor = 'success';
                                                elseif (in_array($status, ['active', 'accepted'])) $statusColor = 'primary';
                                                elseif ($status === 'pending') $statusColor = 'warning';
                                                elseif ($status === 'cancelled') $statusColor = 'danger';
                                                ?>
                                                <span class="badge bg-<?= $statusColor ?>"><?= ucfirst($result['status']) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= isset($result['created_at']) ? date('M d, Y', strtotime($result['created_at'])) : '-' ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= base_url($detail_url . '/' . ($result['id'] ?? '')) ?>"
                                                    class="btn btn-action view" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .search-result {
        transition: all 0.3s ease;
    }

    .hover-lift {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .icon-wrapper {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-icon {
        opacity: 0.2;
    }

    .key-fields .badge {
        font-size: 0.65rem;
        font-weight: 500;
    }

    /* Type colors */
    .bg-purple {
        background-color: #6f42c1 !important;
    }

    .text-purple {
        color: #6f42c1 !important;
    }

    /* Smooth transitions */
    .card,
    .btn,
    .badge {
        transition: all 0.2s ease;
    }


    /* Add this to your existing styles */
    #searchLoading {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #searchLoading .spinner-border {
        width: 1rem;
        height: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .key-fields {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        .card-header .d-flex {
            flex-direction: column;
            align-items: start !important;
        }

        .card-header .dropdown {
            align-self: flex-end;
            margin-top: -2rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const liveSearch = document.getElementById('liveSearch');
        const searchLoading = document.getElementById('searchLoading');
        const typeFilterBtns = document.querySelectorAll('#typeFilters button');
        const cardViewBtn = document.getElementById('cardViewBtn');
        const tableViewBtn = document.getElementById('tableViewBtn');
        const cardsView = document.getElementById('cardsView');
        const tableView = document.getElementById('tableView');
        const searchResults = document.querySelectorAll('.search-result');

        let currentView = 'cards';
        let activeFilter = 'all';

        // Debug logging
        console.log('Search page initialized');
        console.log('Live search element:', liveSearch);
        console.log('Search loading element:', searchLoading);

        // Live Search
        if (liveSearch) {
            console.log('Live search initialized, current value:', JSON.stringify(liveSearch.value));

            // Store reference to the input
            const searchInput = liveSearch;

            // Create the handler function
            async function handleSearchInput(event) {
                const input = event.target || searchInput;
                const query = input.value ? input.value.trim() : '';

                console.log('=== SEARCH INPUT HANDLER ===');
                console.log('Event type:', event.type);
                console.log('Target:', input);
                console.log('Target value:', JSON.stringify(input.value));
                console.log('Query:', JSON.stringify(query));
                console.log('Query length:', query.length);

                // Visual feedback
                input.style.borderColor = query.length >= 2 ? '#0d6efd' : '#6c757d';

                // Clear loading if shown
                if (searchLoading) searchLoading.classList.add('d-none');

                // If query is empty or too short, show all results
                if (!query || query.length < 2) {
                    console.log('Query too short, showing original results');
                    showAllOriginalResults();
                    filterByType(activeFilter);
                    return;
                }

                // Show loading
                if (searchLoading) {
                    searchLoading.classList.remove('d-none');
                    console.log('Showing loading for query:', query);
                }

                try {
                    console.log('Making AJAX request...');
                    const url = `<?= base_url("search/live") ?>?q=${encodeURIComponent(query)}&_=${Date.now()}`;
                    console.log('URL:', url);

                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    console.log('Response status:', response.status);
                    const data = await response.json();
                    console.log('Response data:', data);

                    if (searchLoading) searchLoading.classList.add('d-none');

                    if (data?.results?.length) {
                        console.log('Updating UI with', data.results.length, 'results');
                        updateResultsUI(data.results, data.detail_url || '<?= $detail_url ?>');
                    } else {
                        console.log('No results found');
                        showNoResults();
                    }

                } catch (error) {
                    console.error('Search error:', error);
                    if (searchLoading) searchLoading.classList.add('d-none');
                    showError('Search failed. Please try again.');
                }
            }

            // Bind the handler
            const debouncedHandler = debounce(handleSearchInput, 500);

            // Add event listener
            liveSearch.addEventListener('input', debouncedHandler);

            // Also add click event for testing
            liveSearch.addEventListener('click', function(e) {
                console.log('CLICK - Value:', JSON.stringify(this.value));
            });

            // Add change event
            liveSearch.addEventListener('change', function(e) {
                console.log('CHANGE - Value:', JSON.stringify(this.value));
            });
        }

        // Type Filtering
        typeFilterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                typeFilterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                activeFilter = this.dataset.filter;
                filterByType(activeFilter);
            });
        });

        // View Toggle
        cardViewBtn?.addEventListener('click', function() {
            if (currentView === 'table') {
                cardViewBtn.classList.add('active');
                tableViewBtn.classList.remove('active');
                cardsView.classList.remove('d-none');
                tableView.classList.add('d-none');
                currentView = 'cards';
            }
        });

        tableViewBtn?.addEventListener('click', function() {
            if (currentView === 'cards') {
                tableViewBtn.classList.add('active');
                cardViewBtn.classList.remove('active');
                tableView.classList.remove('d-none');
                cardsView.classList.add('d-none');
                currentView = 'table';
            }
        });

        // Store original HTML for reset
        const originalCardsHTML = cardsView.innerHTML;
        const originalTableHTML = tableView.innerHTML;

        // Helper Functions
        function showAllOriginalResults() {
            cardsView.innerHTML = originalCardsHTML;
            tableView.innerHTML = originalTableHTML;
            filterByType(activeFilter);
        }

        function filterByType(type) {
            const results = currentView === 'cards' ?
                cardsView.querySelectorAll('.search-result') :
                tableView.querySelectorAll('.search-result');

            let visibleCount = 0;

            results.forEach(result => {
                if (type === 'all' || result.dataset.type === type) {
                    result.style.display = '';
                    visibleCount++;
                } else {
                    result.style.display = 'none';
                }
            });

            // Update count in active filter button
            const activeBtn = document.querySelector(`[data-filter="${type}"]`);
            if (activeBtn) {
                const badge = activeBtn.querySelector('.badge');
                if (badge) badge.textContent = visibleCount;
            }

            // Update "All" button count
            const allBtn = document.querySelector('[data-filter="all"]');
            if (allBtn) {
                const allBadge = allBtn.querySelector('.badge');
                if (allBadge) allBadge.textContent = results.length;
            }

            console.log(`Filtered by ${type}: ${visibleCount} visible`);
        }

        function updateResultsUI(results, detailUrl) {
            console.log('Updating UI with results:', results);

            if (currentView === 'cards') {
                updateCardsView(results, detailUrl);
            } else {
                updateTableView(results, detailUrl);
            }

            // Update filter counts
            updateFilterCounts(results);
        }

        function updateCardsView(results, detailUrl) {
            if (!cardsView) {
                console.error('Cards view container not found');
                return;
            }

            if (results.length === 0) {
                cardsView.innerHTML = `
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No matching results found</h5>
                            <p class="text-muted small">Try different search terms</p>
                        </div>
                    </div>
                </div>
            `;
                return;
            }

            let html = '';

            results.forEach(result => {
                const type = result._search_type || 'unknown';
                const color = getTypeColor(type);
                const icon = getTypeIcon(type);
                const label = getTypeLabel(type);
                const title = getResultTitle(result);
                const keyFields = getKeyFields(result);

                let statusBadge = '';
                if (result.status) {
                    const statusColor = getStatusColor(result.status);
                    statusBadge = `
                    <div class="mb-2">
                        <span class="badge bg-${statusColor} bg-opacity-10 text-${statusColor} rounded-pill px-3 py-1">
                            Status: ${ucfirst(result.status)}
                        </span>
                    </div>
                `;
                }

                let keyFieldsHTML = '';
                Object.entries(keyFields).forEach(([key, value]) => {
                    if (value) {
                        keyFieldsHTML += `
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-light text-dark rounded-pill px-2 py-1 me-2" style="font-size: 0.7rem;">
                                ${key}
                            </span>
                            <span class="text-truncate small">${escapeHtml(value)}</span>
                        </div>
                    `;
                    }
                });

                let dateHTML = '';
                if (result.created_at) {
                    const date = new Date(result.created_at);
                    dateHTML = `
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span title="Created">
                            <i class="fas fa-calendar-plus me-1"></i>
                            ${date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                        </span>
                    </div>
                `;
                }

                html += `
                <div class="col search-result" data-type="${type}">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-header border-0 pb-0 pt-3 px-3 bg-transparent">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge bg-${color} bg-opacity-10 text-${color} rounded-pill px-3 py-2 mb-2 d-inline-flex align-items-center">
                                        <i class="${icon} me-2"></i>
                                        ${label}
                                    </span>
                                    <h6 class="card-title mb-1 fw-semibold">${escapeHtml(title)}</h6>
                                    <small class="text-muted">ID: #${result.id || 'N/A'}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-2 px-3">
                            ${keyFieldsHTML ? `<div class="key-fields mb-3">${keyFieldsHTML}</div>` : ''}
                            ${statusBadge}
                            ${dateHTML}
                        </div>
                        <div class="card-footer border-0 pt-0 px-3 pb-3 bg-transparent">
                            <div class="d-grid">
                                <a href="${detailUrl}/${result.id}" 
                                   class="btn btn-outline-${color} btn-sm">
                                    <i class="fas fa-external-link-alt me-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            });

            cardsView.innerHTML = html;
            console.log('Cards view updated with', results.length, 'results');
        }

        function updateTableView(results, detailUrl) {
            if (!tableView) {
                console.error('Table view container not found');
                return;
            }

            const tbody = tableView.querySelector('tbody');
            if (!tbody) {
                console.error('Table body not found');
                return;
            }

            if (results.length === 0) {
                tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No matching results found</h5>
                    </td>
                </tr>
            `;
                return;
            }

            let html = '';

            results.forEach(result => {
                const type = result._search_type || 'unknown';
                const color = getTypeColor(type);
                const icon = getTypeIcon(type);
                const title = getResultTitle(result);

                let statusBadge = '';
                if (result.status) {
                    const statusColor = getStatusColor(result.status);
                    statusBadge = `<span class="badge bg-${statusColor}">${ucfirst(result.status)}</span>`;
                } else {
                    statusBadge = '<span class="badge bg-secondary">N/A</span>';
                }

                // Get 2 display fields
                let displayFields = [];
                for (const [key, value] of Object.entries(result)) {
                    if (!['id', '_search_type', '_model_class', 'created_at', 'updated_at', 'status'].includes(key) &&
                        value && value.toString().length < 50 && displayFields.length < 2) {
                        displayFields.push({
                            key,
                            value
                        });
                    }
                }

                let displayHTML = '';
                displayFields.forEach(field => {
                    displayHTML += `
                    <div class="mb-1">
                        <span class="text-muted">${ucfirst(field.key.replace(/_/g, ' '))}:</span>
                        <span class="ms-1 fw-medium">${escapeHtml(field.value)}</span>
                    </div>
                `;
                });

                let dateHTML = '-';
                if (result.created_at) {
                    const date = new Date(result.created_at);
                    dateHTML = date.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    });
                }

                html += `
                <tr class="search-result" data-type="${type}">
                    <td><span class="badge bg-light text-dark">#${result.id || '-'}</span></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-${color} bg-opacity-10 text-${color} rounded p-2 me-3">
                                <i class="${icon}"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">${getTypeLabel(type)}</div>
                                <small class="text-muted">${escapeHtml(title)}</small>
                            </div>
                        </div>
                    </td>
                    <td><div class="small">${displayHTML}</div></td>
                    <td>${statusBadge}</td>
                    <td><small class="text-muted">${dateHTML}</small></td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="${detailUrl}/${result.id}" 
                               class="btn btn-outline-primary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
            });

            tbody.innerHTML = html;
            console.log('Table view updated with', results.length, 'results');
        }

        function updateFilterCounts(results) {
            const counts = {};
            results.forEach(r => {
                const type = r._search_type || 'unknown';
                counts[type] = (counts[type] || 0) + 1;
            });

            // Update all filter buttons
            typeFilterBtns.forEach(btn => {
                const type = btn.dataset.filter;
                const badge = btn.querySelector('.badge');
                if (badge) {
                    badge.textContent = type === 'all' ? results.length : (counts[type] || 0);
                }
            });
        }

        function showNoResults() {
            const message = `
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No results found</h5>
                        <p class="text-muted small">Try different search terms</p>
                    </div>
                </div>
            </div>
        `;

            if (currentView === 'cards') {
                cardsView.innerHTML = message;
            } else {
                const tbody = tableView.querySelector('tbody');
                if (tbody) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No results found</h5>
                        </td>
                    </tr>
                `;
                }
            }
        }

        function showError(message) {
            const error = `
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${message}
                </div>
            </div>
        `;

            if (currentView === 'cards') {
                cardsView.innerHTML = error;
            } else {
                const tbody = tableView.querySelector('tbody');
                if (tbody) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                ${message}
                            </div>
                        </td>
                    </tr>
                `;
                }
            }
        }

        // Utility functions
        function getTypeColor(type) {
            const colors = {
                'user': 'success',
                'package': 'primary',
                'shipping': 'info',
                'shopper_request': 'warning',
                'warehouse_request': 'secondary',
                'combine_request': 'purple',
                'dispose_request': 'danger'
            };
            return colors[type] || 'dark';
        }

        function getTypeIcon(type) {
            const icons = {
                'user': 'fas fa-user',
                'package': 'fas fa-box',
                'shipping': 'fas fa-shipping-fast',
                'shopper_request': 'fas fa-shopping-cart',
                'warehouse_request': 'fas fa-warehouse',
                'combine_request': 'fas fa-boxes',
                'dispose_request': 'fas fa-trash'
            };
            return icons[type] || 'fas fa-search';
        }

        function getTypeLabel(type) {
            const labels = {
                'user': 'User',
                'package': 'Package',
                'shipping': 'Shipping',
                'shopper_request': 'Shopper Request',
                'warehouse_request': 'Warehouse Request',
                'combine_request': 'Combine Request',
                'dispose_request': 'Dispose Request'
            };
            return labels[type] || 'Item';
        }

        function getResultTitle(result) {
            const type = result._search_type || 'unknown';

            switch (type) {
                case 'user':
                    return trim(`${result.firstname || ''} ${result.lastname || ''}`) || result.username || `User #${result.id}`;
                case 'package':
                    let title = 'Package';
                    if (result.retailer) title += ` from ${result.retailer}`;
                    if (result.tracking_number) title += ` (${result.tracking_number})`;
                    return title;
                case 'shipping':
                    let shippingTitle = 'Shipping';
                    if (result.courier_name) shippingTitle += ` via ${result.courier_name}`;
                    if (result.dest_city) shippingTitle += ` to ${result.dest_city}`;
                    return shippingTitle;
                case 'shopper_request':
                    return `Shopper Request #${result.id}`;
                case 'warehouse_request':
                    return `Warehouse Request #${result.id}`;
                case 'combine_request':
                    return `Combine Request #${result.id}`;
                case 'dispose_request':
                    return `Dispose Request #${result.id}`;
                default:
                    return `Item #${result.id}`;
            }
        }

        function getKeyFields(result) {
            const type = result._search_type || 'unknown';
            const fields = {};

            switch (type) {
                case 'user':
                    if (result.email) fields.Email = result.email;
                    if (result.phone_number) fields.Phone = result.phone_number;
                    if (result.role) fields.Role = result.role;
                    break;
                case 'package':
                    if (result.status) fields.Status = result.status;
                    if (result.tracking_number) fields.Tracking = result.tracking_number;
                    if (result.weight) fields.Weight = `${result.weight}kg`;
                    break;
                case 'shipping':
                    if (result.status) fields.Status = result.status;
                    if (result.courier_name) fields.Courier = result.courier_name;
                    if (result.total_charge) fields.Amount = `${result.currency || ''} ${result.total_charge}`;
                    break;
                default:
                    if (result.status) fields.Status = result.status;
                    if (result.user_id) fields.User = `#${result.user_id}`;
            }

            return fields;
        }

        function getStatusColor(status) {
            const s = (status || '').toLowerCase();
            if (['completed', 'delivered', 'success', 'paid'].includes(s)) return 'success';
            if (['active', 'accepted', 'approved', 'shipped'].includes(s)) return 'primary';
            if (['pending', 'processing', 'waiting'].includes(s)) return 'warning';
            if (['cancelled', 'rejected', 'failed', 'unpaid'].includes(s)) return 'danger';
            return 'secondary';
        }

        function ucfirst(str) {
            return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
        }

        function trim(str) {
            return str ? str.trim() : '';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    });
    console.log('Debug Info:');
    console.log('Base URL:', '<?= base_url() ?>');
    console.log('Live Search URL:', '<?= base_url("search/live") ?>');
    console.log('Detail URL:', '<?= $detail_url ?>');
    console.log('Results Count:', <?= count($results) ?>);
</script>

<?= $this->endSection() ?>