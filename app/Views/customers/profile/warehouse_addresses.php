<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card container px-0">
    <!-- Header Section -->

    <div class="card-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="page-title-section">
                    <h1 class="page-title"><i class="fas fa-warehouse me-2 text-white"></i> Our Warehouse Locations</h1>
                    <span class=" mb-0 ">Find our global network of shipping facilities</span>
                </div>
                <div class="d-none d-md-block">
                    <span class="badge bg-shippex-primary"><?= count($warehouses) ?> Locations</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Enhanced Tabs Navigation -->
    <div class="card-body  mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">

            <div class="d-none d-md-block">
                <i class="fas fa-info-circle text-shippex-primary me-1"></i>
                <small class="text-muted">Click on a country to view details</small>
            </div>
        </div>
        <div class="nav-scrollable">
            <ul class="nav nav-pills flex-nowrap" id="warehouseTabs" role="tablist">
                <?php foreach ($warehouses as $index => $wh): ?>

                    <?php
                    $code = strtolower($wh['code'] ?? 'us');

                    // Fix or map invalid country codes
                    $map = [
                        'uk' => 'gb', // show UK flag for 'uk'
                        'gp' => 'gb', // treat 'gp' as UK
                    ];

                    $flagCode = $map[$code] ?? $code;
                    ?>


                    <li class="nav-item me-2 flex-shrink-0" role="presentation">
                        <button class="nav-link d-flex align-items-center <?= $index === 0 ? 'active' : '' ?>"
                            id="tab-<?= $index ?>"
                            data-bs-toggle="pill"
                            data-bs-target="#content-<?= $index ?>"
                            type="button"
                            role="tab"
                            aria-controls="content-<?= $index ?>"
                            aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
                            <span class="fi fi-<?= $flagCode ?> me-2"></span>
                            <?= esc($wh['country']) ?>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="px-4 tab-content" id="warehouseTabsContent">
        <?php foreach ($warehouses as $index => $wh): ?>
            <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                id="content-<?= $index ?>"
                role="tabpanel"
                aria-labelledby="tab-<?= $index ?>">

                <div class="card border-0 shadow-hover">
                    <div class="card-header bg-shippex-gradient text-white position-relative">
                        <h4 class="mb-0">
                            <i class="fas fa-warehouse me-2"></i>
                            <?= esc($wh['city']) ?>, <?= esc($wh['country']) ?>
                        </h4>
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-white text-shippex-primary">ID: WH-<?= $wh['id'] ?? '001' ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column - Information -->
                            <div class="col-md-7">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="info-item">
                                            <div class="icon-circle bg-shippex-light text-shippex-primary">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-shippex-primary mb-2">Address</h6>
                                                <p class="mb-1"><?= esc($wh['address_line']) ?></p>
                                                <p class="mb-1"><?= esc($wh['city']) ?>, <?= esc($wh['postal_code']) ?></p>
                                                <p class="mb-0"><?= esc($wh['country']) ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="info-item">
                                            <div class="icon-circle bg-shippex-light text-shippex-primary">
                                                <i class="fas fa-phone-alt"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-shippex-primary mb-2">Contact</h6>
                                                <p class="mb-0"><?= esc($wh['phone']) ?></p>
                                                <?php if (!empty($wh['email'])): ?>
                                                    <p class="mb-0 mt-1">
                                                        <a href="mailto:<?= esc($wh['email']) ?>" class="text-shippex-primary">
                                                            <small><i class="fas fa-envelope me-1"></i> <?= esc($wh['email']) ?></small>
                                                        </a>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>



                                    <?php if (!empty($wh['operating_hours'])): ?>
                                        <div class="col-12">
                                            <div class="info-item">
                                                <div class="icon-circle bg-shippex-light text-shippex-primary">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <div>
                                                    <h6 class="text-shippex-primary mb-2">Operating Hours</h6>
                                                    <p class="mb-0"><?= esc($wh['operating_hours']) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($wh['services'])): ?>
                                        <div class="col-12">
                                            <div class="info-item">
                                                <div class="icon-circle bg-shippex-light text-shippex-primary">
                                                    <i class="fas fa-concierge-bell"></i>
                                                </div>
                                                <div>
                                                    <h6 class="text-shippex-primary mb-2">Services Offered</h6>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <?php $services = explode(',', $wh['services']); ?>
                                                        <?php foreach ($services as $service): ?>
                                                            <span class="badge bg-shippex-light text-shippex-primary"><?= trim($service) ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Right Column - Map and Actions -->
                            <div class="col-md-5">
                                <div class="map-container mb-4">
                                    <div class="map-placeholder bg-shippex-light rounded">
                                        <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                            <i class="fas fa-map-marked-alt text-shippex-primary mb-2" style="font-size: 2rem;"></i>
                                            <p class="text-muted mb-0 text-center">Interactive map of<br><?= esc($wh['city']) ?>, <?= esc($wh['country']) ?></p>
                                            <button class="btn btn-sm btn-shippex-primary mt-2">
                                                <i class="fas fa-directions me-1"></i> Get Directions
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="action-buttons">
                                    <div class="d-grid ">
                                        <?php
                                        $request = $requestMap[$wh['id']] ?? null;
                                        if ($request): ?>
                                            <a class="btn btn-outline-shippex-primary ">


                                                <?php if ($request['status'] == 'pending'): ?>
                                                    Requested <i class="fas fa-info-circle"
                                                        data-bs-toggle="tooltip"
                                                        title="Request pending. Requested on: <?= date('Y-m-d H:i', strtotime($request['created_at'])) ?>"></i>
                                                <?php elseif ($request['status'] == 'accepted'): ?>
                                                    Your Own it <i class="fas fa-check-circle text-success"
                                                        data-bs-toggle="tooltip"
                                                        title="Request accepted"></i>
                                                <?php elseif ($request['status'] == 'rejected'): ?>
                                                    Rejected <i class="fas fa-times-circle text-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Request rejected: <?= esc($request['rejectation_reason']) ?>"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a class="btn btn-outline-shippex-primary warehouse-select-btn"
                                            data-warehouse-id="<?= $wh['id'] ?? '' ?>">
                                            <i class="fas fa-paper-plane"></i> Request Warehouse
                                        </a>
                                    <?php endif; ?>

                                    <!-- Replace your current download button with this: -->
                                    <a href="javascript:void(0)"
                                        class="btn btn-outline-shippex-primary download-page-btn"
                                        data-filename="warehouse_<?= esc(strtolower($wh['city'])) ?>_<?= esc(strtolower($wh['country'])) ?>"
                                        data-title="Warehouse - <?= esc($wh['city']) ?>, <?= esc($wh['country']) ?>">
                                        <i class="fas fa-download me-2"></i> Download Details
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    :root {
        --shippex-primary: #4E148C;
        --shippex-secondary: #FF6600;
        --shippex-light: #F0E6FF;
        --shippex-gradient: linear-gradient(120deg, #4E148C, #6A3FB8);
    }

    .bg-shippex-primary {
        background-color: var(--shippex-primary) !important;
    }

    .text-shippex-primary {
        color: var(--shippex-primary) !important;
    }

    .bg-shippex-light {
        background-color: var(--shippex-light) !important;
    }

    .bg-shippex-gradient {
        background: var(--shippex-gradient) !important;
    }

    .btn-shippex-primary {
        background-color: var(--shippex-primary);
        color: white;
        border: none;
    }

    .btn-shippex-primary:hover {
        background-color: #3A0C6E;
        color: white;
    }

    .btn-outline-shippex-primary {
        color: var(--shippex-primary);
        border-color: var(--shippex-primary);
    }

    .btn-outline-shippex-primary:hover {
        background-color: var(--shippex-primary);
        color: white;
    }

    .nav-pills .nav-link.active {
        background-color: var(--shippex-primary);
        color: white;
        box-shadow: 0 4px 8px rgba(78, 20, 140, 0.2);
    }

    .nav-pills .nav-link {
        color: var(--shippex-primary);
        border: 1px solid #dee2e6;
        transition: all 0.3s;
    }

    .nav-pills .nav-link:hover {
        border-color: var(--shippex-primary);
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .card {
        border-radius: 0.75rem;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .shadow-hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .shadow-hover:hover {
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
        transform: translateY(-3px);
    }

    .map-placeholder {
        height: 200px;
        border: 2px dashed #D6C6FF;
        padding: 1rem;
    }

    .nav-scrollable {
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 10px;
    }

    .nav-scrollable::-webkit-scrollbar {
        height: 6px;
    }

    .nav-scrollable::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .nav-scrollable::-webkit-scrollbar-thumb {
        background: #c5b4e3;
        border-radius: 10px;
    }

    .flag-icon {
        width: 20px;
        height: 15px;
        border-radius: 2px;
        display: inline-block;
        background-size: cover;
    }
</style>


<?= $this->endSection() ?>