<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Config - endpoints (server-side base_url is used)
    const BULK_INFO_URL = '<?= base_url('packages/bulk-info') ?>';
    const SUBMIT_URL = '<?= base_url('packages/dispose-return-submit') ?>';

    // Elements
    const disposeModalEl = document.getElementById('disposeReturnBulkModal');
    if (!disposeModalEl) {
      console.error('#disposeReturnBulkModal not found. Please add the modal HTML.');
      return;
    }
    const disposeForm = document.getElementById('disposeReturnBulkForm');
    const tableBody = document.querySelector('#disposeReturnTable tbody');
    const bulkBtn = document.getElementById('bulkDisposeReturnBtn');
    const applyType = document.getElementById('bulkApplyType');
    const applyBtn = document.getElementById('applyToAllBtn');

    const bsModal = new bootstrap.Modal(disposeModalEl);

    // Helper: collect selected package ids from page checkboxes
    function getSelectedPackageIds() {
      return Array.from(document.querySelectorAll('.package-checkbox'))
        .filter(cb => cb.checked)
        .map(cb => cb.value);
    }

    // Core: open modal for array of ids
    async function openDisposeReturnModalForIds(ids = []) {
      if (!Array.isArray(ids) || ids.length === 0) {
        return alert('Please select at least one package.');
      }

      tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4">Loading packages…</td></tr>';
      bsModal.show();

      try {
        const res = await fetch(BULK_INFO_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            package_ids: ids
          })
        });

        const json = await res.json();
        if (!json.success) {
          tableBody.innerHTML = `<tr><td colspan="5" class="text-danger">${json.message || 'Failed to load packages'}</td></tr>`;
          return;
        }

        // build rows
        tableBody.innerHTML = '';
        json.packages.forEach((p, idx) => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
          <td>${idx + 1}</td>
          <td>#${p.id} <div class="small text-muted">${p.retailer ? p.retailer : ''} ${p.tracking_number ? '— ' + p.tracking_number : ''}</div></td>
          <td>${p.status || ''}</td>
          <td>
            <input type="hidden" name="package_ids[]" value="${p.id}">
            <select name="request_type[]" class="form-select form-select-sm">
              <option value="disposed">Dispose</option>
              <option value="returned">Return</option>
            </select>
          </td>
          <td><input name="reason[]" type="text" class="form-control form-control-sm" required placeholder="Reason for this package"></td>
        `;
          tableBody.appendChild(tr);
        });

        // focus first reason
        const firstReason = tableBody.querySelector('input[name="reason[]"]');
        if (firstReason) firstReason.focus();

      } catch (err) {
        console.error(err);
        tableBody.innerHTML = '<tr><td colspan="5" class="text-danger">Server error while loading packages.</td></tr>';
      }
    }

    // open modal for currently selected checkboxes
    window.openDisposeReturnModal = function() {
      const ids = getSelectedPackageIds();
      openDisposeReturnModalForIds(ids);
    };

    // wire bulk button
    if (bulkBtn) {
      bulkBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.openDisposeReturnModal();
      });
    }

    // wire per-package buttons (delegated)
    document.body.addEventListener('click', function(e) {
      const btn = e.target.closest('.dispose-single-btn');
      if (!btn) return;
      e.preventDefault();
      const id = btn.dataset.packageId || btn.getAttribute('data-package-id');
      if (!id) return console.warn('dispose-single-btn missing data-package-id');
      openDisposeReturnModalForIds([id]);
    });

    // apply action to all rows inside modal
    if (applyBtn) {
      applyBtn.addEventListener('click', function() {
        const val = applyType.value;
        if (!val) return;
        tableBody.querySelectorAll('select[name="request_type[]"]').forEach(s => s.value = val);
      });
    }

    // submit handler
    if (disposeForm) {
      disposeForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // validation: ensure every reason non-empty
        const reasons = Array.from(tableBody.querySelectorAll('input[name="reason[]"]'));
        for (const r of reasons) {
          if (!r.value.trim()) {
            Swal.fire({
              icon: 'warning',
              title: 'Missing reason',
              text: 'Please provide a reason for every package.',
              confirmButtonColor: '#ff6600', // Optional: custom color
            });
            return;
          }
        }

        // build payload arrays
        const package_ids = Array.from(disposeForm.querySelectorAll('input[name="package_ids[]"]')).map(i => i.value);
        const request_type = Array.from(disposeForm.querySelectorAll('select[name="request_type[]"]')).map(s => s.value);
        const reason = reasons.map(i => i.value);



        // Before your fetch code
        Swal.fire({
          title: 'Are you sure?',
          text: "You are about to dismess or return this packae.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, submit it!',
          cancelButtonText: 'Cancel'
        }).then(async (result) => {
          if (result.isConfirmed) {
            // The user clicked "Yes"
            // send
            try {
              const res = await fetch(SUBMIT_URL, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                  package_ids,
                  request_type,
                  reason
                })
              });
              const json = await res.json();
              if (json.success) {
                Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  html: json.message || 'Requests submitted successfully',
                  confirmButtonColor: '#28a745', // green success button
                }).then(() => {
                  bsModal.hide();
                  // Uncheck selected rows
                  document.querySelectorAll('.package-checkbox:checked').forEach(cb => cb.checked = false);
                  // Refresh page to reflect new requests/statuses
                  location.reload();
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops!',
                  html: json.message || 'Failed to submit requests',
                  confirmButtonColor: '#ff6600', // red error button
                });
              }

            } catch (err) {
              console.error(err);
              Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Server error while submitting requests.',
                confirmButtonColor: '#ff6600'
              });
            }
          }
        });




      });
    }

    // Accessibility: close modal cleanup when hidden
    disposeModalEl.addEventListener('hidden.bs.modal', () => {
      tableBody.innerHTML = '';
      disposeForm.classList.remove('was-validated');
      if (applyType) applyType.value = '';
    });

  });
</script>

<script>
  const packagesData = <?= json_encode($packages); ?>;
</script>


<script>
  // Enhanced JavaScript for the combine modal with package count validation
  document.addEventListener('DOMContentLoaded', function() {
    let combineModalInstance = null;
    let selectedPackagesMap = new Map();

    function openCombineModal(packageIds) {
      const modalEl = document.getElementById('combineRepackModal');
      const warehouse_id = document.getElementById('warehouse_id').value;

      if (!combineModalInstance) {
        combineModalInstance = new bootstrap.Modal(modalEl);
      }

      // Populate packages grid
      updatePackagesGrid(packageIds);

      // Update selection count and validation
      updateSelectionCount(packageIds.length);
      validatePackageCount(packageIds.length);

      // Auto-calculate dimensions if enabled and enough packages
      if (document.getElementById('autoCalculate').checked && packageIds.length >= 2) {
        calculateCombinedDimensions(packageIds);
      } else {
        resetDimensions();
      }

      // Submit handler
      document.getElementById('submitCombineRequest').onclick = function() {
        if (packageIds.length < 2) {
          Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Please select at least 2 packages to combine.',
            confirmButtonColor: '#ff6600'
          });
          return;
        }

        const dimensions = getCurrentDimensions();
        console.log(dimensions);
        // Before your fetch code
        Swal.fire({
          title: 'Are you sure?',
          text: "You are about to submit this request.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, submit it!',
          cancelButtonText: 'Cancel'
        }).then(async (result) => {

          if (result.isConfirmed) {
            console.log('bye');
            fetch('/packages/combine-request', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                  package_ids: packageIds,
                  warehouse_id: warehouse_id,
                  dimensions: dimensions
                })
              })
              .then(r => r.json())
              .then(res => {
                if (res.status === 'success') {
                  Swal.fire({
                    icon: 'success',
                    title: 'Request Sent',
                    text: 'Combine & Repack request submitted successfully!',
                    confirmButtonColor: '#28a745'
                  }).then(() => {
                    combineModalInstance.hide();
                    // Refresh page to reflect new requests/statuses
                    location.reload();
                  });
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Error:' + res.message,
                    confirmButtonColor: '#ff6600'
                  });
                }
              })
              .catch(error => {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops!',
                  text: 'Network error: ' + error.message,
                  confirmButtonColor: '#ff6600'
                });
              });
          }
        });

      };

      combineModalInstance.show();
    }

    function updatePackagesGrid(packageIds) {
      const grid = document.getElementById('selectedPackagesGrid');
      grid.innerHTML = '';

      if (packageIds.length === 0) {
        grid.innerHTML = `
        <div class="empty-state">
          <i class="fas fa-box-open text-muted mb-3" style="font-size: 2rem;"></i>
          <p class="text-muted mb-0">No packages selected</p>
        </div>
      `;
        return;
      }

      packageIds.forEach(id => {
        const packageCard = createPackageCard(id);
        grid.appendChild(packageCard);
      });
    }

    function createPackageCard(packageId) {
      const pkg = packagesData.find(p => p.id == packageId);

      let detailsText = '';
      if (pkg) {
        const weight = parseFloat(pkg.weight) || 0;
        const length = parseFloat(pkg.length) || 0;
        const width = parseFloat(pkg.width) || 0;
        const height = parseFloat(pkg.height) || 0;
        detailsText = `${weight.toFixed(1)}kg • ${length}×${width}×${height}cm`;
      }

      const card = document.createElement('div');
      card.className = 'package-card';
      card.innerHTML = `
          <div class="package-icon">
            <i class="fas fa-box"></i>
          </div>
          <div class="package-info">
            <div class="package-id">#${packageId}</div>
            <div class="package-details">${detailsText}</div>
          </div>
          <button class="remove-package" onclick="removePackage('${packageId}')">
            <i class="fas fa-times"></i>
          </button>
        `;
      return card;
    }


    function updateSelectionCount(count) {
      const badge = document.getElementById('selectedCountBadge');
      badge.textContent = count;

      // Update badge color based on count
      if (count < 2) {
        badge.className = 'badge bg-warning ms-2';
      } else {
        badge.className = 'badge bg-primary ms-2';
      }
    }

    function validatePackageCount(count) {
      const submitButton = document.getElementById('submitCombineRequest');
      const validationAlert = document.getElementById('packageValidationAlert');
      const dimensionsSection = document.querySelector('.dimensions-config-section');
      const previewSection = document.querySelector('.combined-preview-section');

      if (count < 2) {
        // Show validation alert
        validationAlert.style.display = 'block';

        // Disable submit button
        submitButton.disabled = true;

        // Hide dimensions and preview sections
        dimensionsSection.style.display = 'none';
        previewSection.style.display = 'none';
      } else {
        // Hide validation alert
        validationAlert.style.display = 'none';

        // Enable submit button
        submitButton.disabled = false;

        // Show dimensions and preview sections
        dimensionsSection.style.display = 'block';
        previewSection.style.display = 'block';
      }
    }

    function calculateCombinedDimensions(packageIds) {
      if (packageIds.length < 2) {
        resetDimensions();
        return;
      }

      let totalLength = 0;
      let totalWidth = 0;
      let totalHeight = 0;
      let totalWeight = 0;

      packageIds.forEach(id => {
        const pkg = packagesData.find(p => p.id == id);
        if (pkg) {
          totalLength += parseFloat(pkg.length) || 0;
          totalWidth += parseFloat(pkg.width) || 0;
          totalHeight += parseFloat(pkg.height) || 0;
          totalWeight += parseFloat(pkg.weight) || 0;
        }
      });

      // Optionally, add some padding or container dimensions
      const padding = 2; // e.g., 2 cm extra for repack
      totalLength += padding;
      totalWidth += padding;
      totalHeight += padding;

      // Update inputs
      document.getElementById('inputLength').value = totalLength.toFixed(1);
      document.getElementById('inputWidth').value = totalWidth.toFixed(1);
      document.getElementById('inputHeight').value = totalHeight.toFixed(1);
      document.getElementById('inputWeight').value = totalWeight.toFixed(1);

      // Update preview
      updatePreview(totalLength, totalWidth, totalHeight, totalWeight);
    }


    function updatePreview(length, width, height, weight) {
      document.getElementById('previewLength').textContent = length.toFixed(1) + ' cm';
      document.getElementById('previewWidth').textContent = width.toFixed(1) + ' cm';
      document.getElementById('previewHeight').textContent = height.toFixed(1) + ' cm';
      document.getElementById('previewWeight').textContent = weight.toFixed(1) + ' kg';

      const volume = (length * width * height) / 1000000; // Convert to m³
      document.getElementById('previewVolume').textContent = volume.toFixed(3) + ' m³';
    }

    function getCurrentDimensions() {
      return {
        length: parseFloat(document.getElementById('inputLength').value) || 0,
        width: parseFloat(document.getElementById('inputWidth').value) || 0,
        height: parseFloat(document.getElementById('inputHeight').value) || 0,
        weight: parseFloat(document.getElementById('inputWeight').value) || 0
      };
    }



    function resetDimensions() {
      document.getElementById('inputLength').value = '';
      document.getElementById('inputWidth').value = '';
      document.getElementById('inputHeight').value = '';
      document.getElementById('inputWeight').value = '';
      updatePreview(0, 0, 0, 0);
    }

    // Event listeners for dimension inputs
    document.querySelectorAll('.dimension-input').forEach(input => {
      input.addEventListener('input', function() {
        const dimensions = getCurrentDimensions();
        if (dimensions.length && dimensions.width && dimensions.height && dimensions.weight) {
          updatePreview(dimensions.length, dimensions.width, dimensions.height, dimensions.weight);
        }
      });
    });

    // Auto-calculate toggle
    document.getElementById('autoCalculate').addEventListener('change', function() {
      if (this.checked) {
        const selectedPackages = Array.from(selectedPackagesMap.keys());
        if (selectedPackages.length >= 2) {
          calculateCombinedDimensions(selectedPackages);
        }
      } else {
        resetDimensions();
      }
    });

    // Clear selection button
    document.getElementById('clearSelection').addEventListener('click', function() {
      selectedPackagesMap.clear();
      updatePackagesGrid([]);
      updateSelectionCount(0);
      validatePackageCount(0);
      resetDimensions();
    });

    // Make functions available globally for onclick handlers
    window.removePackage = function(packageId) {
      selectedPackagesMap.delete(packageId);
      const selectedPackages = Array.from(selectedPackagesMap.keys());
      updatePackagesGrid(selectedPackages);
      updateSelectionCount(selectedPackages.length);
      validatePackageCount(selectedPackages.length);

      if (document.getElementById('autoCalculate').checked && selectedPackages.length >= 2) {
        calculateCombinedDimensions(selectedPackages);
      } else {
        resetDimensions();
      }
    };

    // Update the existing openCombineModal function to use selectedPackagesMap
    const originalOpenCombineModal = window.openCombineModal;
    window.openCombineModal = function(packageIds) {
      selectedPackagesMap = new Map(packageIds.map(id => [id, true]));
      openCombineModal(packageIds);
    };
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // const selectAll = document.getElementById('select-all-ready');
    const checkboxes = document.querySelectorAll('.package-checkbox');
    const bulkCard = document.getElementById('bulkActionsCard');
    const selectionCount = document.getElementById('selectionCount');
    const bulkButtons = document.querySelectorAll('.bulk-action');
    const modalEl = document.getElementById('bulkActionModal');
    const combineModal = document.getElementById('combineRepackModal');
    const modalTitle = document.getElementById('bulkActionModalLabel');
    const modalSubtitle = document.getElementById('modalSubtitle');
    const modalMessage = document.getElementById('modalMessage');

    // Initialize modal
    let modal = null;
    if (modalEl) {
      modal = new bootstrap.Modal(modalEl);
    }
    modalEl.addEventListener('show.bs.modal', () => {
      if (currentAction === 'ship') {
        loadCategories();
      }
    });

    // Wizard elements
    const stepDestination = document.getElementById('stepDestination');
    const stepRates = document.getElementById('stepRates');
    const prevStepBtn = document.getElementById('prevStep');
    const nextStepBtn = document.getElementById('nextStep');
    const destWarehouseRadio = document.getElementById('destWarehouse');
    const destCustomRadio = document.getElementById('destCustom');
    const warehouseSection = document.getElementById('warehouseSection');
    const customAddressSection = document.getElementById('customAddressSection');
    const warehouseContainer = document.getElementById('warehouseSelectContainer');
    const destinationCountry = document.getElementById('destinationCountry');
    const ratesContainer = document.getElementById('calculatedRates');

    let currentStep = 1;
    let selectedPackages = [];
    let currentAction = '';
    let currentOrigin = null;
    let currentRates = [];
    let availableWarehouses = [];
    let currentDestination = null;



    // Selection management
    function getSelectedPackages() {
      return Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
    }

    function updateSelectionUI() {
      const count = getSelectedPackages().length;
      selectionCount.textContent = `${count} package${count !== 1 ? 's' : ''} selected`;
      bulkCard.style.display = count > 0 ? 'block' : 'none';
      // selectAll.indeterminate = count > 0 && count < checkboxes.length;
      // selectAll.checked = count === checkboxes.length;
    }

    // selectAll?.addEventListener('change', () => {
    //   checkboxes.forEach(cb => cb.checked = selectAll.checked);
    //   updateSelectionUI();
    // });

    checkboxes.forEach(cb => cb.addEventListener('change', updateSelectionUI));

    // Bulk action click handler
    bulkButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        selectedPackages = getSelectedPackages();
        if (selectedPackages.length === 0) {
          alert('Please select at least one package');
          return;
        }

        currentAction = btn.dataset.action || '';
        const action = btn.dataset.action;

        // Reset wizard
        currentStep = 1;
        showStep(currentStep);
        if (action === 'combine') {
          openCombineModal(selectedPackages, btn.textContent.trim());


        } else {
          if (currentAction === 'ship') {
            window.selectedPackageIdsForShipping = selectedPackages;
            modalTitle.textContent = 'Shipping Configuration';
            modalSubtitle.textContent = `Configure shipping for ${selectedPackages.length} package${selectedPackages.length !== 1 ? 's' : ''}`;

            // Load warehouses
            loadWarehouses();
            // load categories
            loadCategories();

          } else {
            modalTitle.textContent = btn.textContent.trim();
            modalSubtitle.textContent = `Confirm action for ${selectedPackages.length} package${selectedPackages.length !== 1 ? 's' : ''}`;
            modalMessage.textContent = `You are about to ${btn.textContent.trim().toLowerCase()} ${selectedPackages.length} package${selectedPackages.length !== 1 ? 's' : ''}.`;
          }
          if (modal) modal.show();
        }

      });
    });

    // Wizard navigation
    function showStep(step) {
      // Hide all steps
      stepDestination.style.display = 'none';
      stepRates.style.display = 'none';
      nextStepBtn.style.display = 'inline-block';
      prevStepBtn.style.display = 'none';

      // Show current step
      if (step === 1) {
        stepDestination.style.display = 'block';
        nextStepBtn.textContent = 'Calculate Rates';
        prevStepBtn.style.display = 'none';
      } else if (step === 2) {
        stepRates.style.display = 'block';
        nextStepBtn.style.display = 'none';
        prevStepBtn.style.display = 'inline-block';
        calculateRates();
      }
    }

    nextStepBtn.addEventListener('click', () => {
      if (currentStep === 1) {
        // Validate destination selection
        if (destWarehouseRadio.checked) {
          const warehouseSelect = document.getElementById('destWarehouseSelect');
          if (!warehouseSelect || !warehouseSelect.value) {
            Swal.fire({
              icon: 'error',
              title: 'Oops!',
              text: 'Please select a destination warehouse',
              confirmButtonColor: '#ff6600'
            });

            return;
          }
        } else {
          // Validate custom address form
          const form = document.getElementById('customAddressForm');
          if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
          }
        }

        currentStep = 2;
        showStep(currentStep);
      }
    });

    prevStepBtn.addEventListener('click', () => {
      currentStep = 1;
      showStep(currentStep);
    });

    // Destination type toggle
    destWarehouseRadio.addEventListener('change', () => {
      warehouseSection.style.display = 'block';
      customAddressSection.style.display = 'none';
    });

    destCustomRadio.addEventListener('change', () => {
      warehouseSection.style.display = 'none';
      customAddressSection.style.display = 'block';
    });

    // Load warehouses
    function loadWarehouses() {
      warehouseContainer.innerHTML = '<div class="loading-placeholder"><i class="fas fa-spinner fa-spin me-2"></i>Loading warehouses...</div>';

      fetch('<?= base_url('packages/shipping-data') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            package_ids: selectedPackages
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.error) {
            warehouseContainer.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            return;
          }
          console.log('whs', data);
          currentOrigin = data.currentWarehouse;
          availableWarehouses = data.availableWarehouses || [];
          const totals = data.packageTotals;
          window.packageTotals = totals;

          if (availableWarehouses.length === 0) {
            warehouseContainer.innerHTML = '<div class="alert alert-warning">No warehouses available.</div>';
            return;
          }

          let html = '<select class="form-select" id="destWarehouseSelect" style="padding: 0.75rem 1rem; border-radius: 8px;">';
          availableWarehouses.forEach(w => {
            html += `<option value="${w.id}" ${w.id === currentOrigin.id ? 'style="display:none;"' : ''}>
            ${w.country} - ${w.address_line_1}, ${w.city}, ${w.state}, ${w.postal_code}
          </option>`;
          });
          html += '</select>';
          warehouseContainer.innerHTML = html;

          let desHtml = `<i class="fi fi-${currentOrigin.code.toLowerCase()}"></i> <b>${currentOrigin.country}</b> Warehouse`;

          destinationCountry.innerHTML = desHtml;
        })
        .catch(e => {
          warehouseContainer.innerHTML = '<div class="alert alert-danger">Failed to load warehouses</div>';
          console.error(e);
        });
    }

    // Calculate shipping rates
    function calculateRates() {
      ratesContainer.innerHTML = '<div class="loading-placeholder"><i class="fas fa-spinner fa-spin me-2"></i>Calculating shipping rates...</div>';

      let dest;
      if (destWarehouseRadio.checked) {
        const warehouseSelect = document.getElementById('destWarehouseSelect');
        const selectedWarehouseId = warehouseSelect.value;

        // Find the selected warehouse from availableWarehouses
        const selectedWarehouse = availableWarehouses.find(w => w.id == selectedWarehouseId);

        if (!selectedWarehouse) {
          ratesContainer.innerHTML = '<div class="alert alert-danger">Invalid warehouse selection</div>';
          return;
        }

        dest = {
          line_1: selectedWarehouse.address_line_1,
          city: selectedWarehouse.city,
          state: selectedWarehouse.state,
          postal_code: selectedWarehouse.postal_code,
          country: selectedWarehouse.code
        };

      } else {
        dest = {
          line_1: document.getElementById('dest_line_1').value,
          city: document.getElementById('dest_city').value,
          state: document.getElementById('dest_state').value,
          postal_code: document.getElementById('dest_postal_code').value,
          country: document.getElementById('dest_country').value
        };
      }

      // Store current destination for booking
      currentDestination = dest;

      const totals = window.packageTotals;
      const categorySelect = document.getElementById('categorySelect');
      const selectedCategory = categorySelect.value;
      const hsCode = categorySelect.options[categorySelect.selectedIndex].dataset.hsCode;

      fetch('<?= base_url('packages/getRates') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            origin_line_1: currentOrigin.address_line_1,
            origin_city: currentOrigin.city,
            origin_state: currentOrigin.state,
            origin_postal_code: currentOrigin.postal_code,
            origin_country: currentOrigin.code,
            dest_line_1: dest.line_1,
            dest_city: dest.city,
            dest_state: dest.state,
            dest_postal_code: dest.postal_code,
            dest_country: dest.country,
            weight: totals.weight,
            category: selectedCategory,
            hs_code: hsCode,
            length: totals.length,
            width: totals.width,
            height: totals.height
          })
        })
        .then(r => r.json())
        .then(response => {
          const rates = response?.data?.rates || [];
          currentRates = rates;

          if (!rates.length) {
            // No rates available: show destination info and book button
            let destInfoHtml = `
                    <div class="card shadow-sm mt-3 mb-3 border-0" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-left: 4px solid #ff6600 !important;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        <h6 class="mb-0 fw-bold text-dark">No Shipping Rates Available</h6>
                                    </div>
                                    <div class="address-section mt-3">
                                        <small class="text-muted fw-semibold">Destination Address:</small>
                                        <p class="mb-0 mt-1 text-dark">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            ${dest.line_1}<br>
                                            ${dest.city}, ${dest.state || ''} ${dest.postal_code}<br>
                                            ${dest.country}
                                        </p>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            We're unable to calculate shipping rates for this destination for now, please book and we will send the avaiable courier and prices later.
                                        </small>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <button class="btn btn-primary book-no-rate-btn d-flex align-items-center">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        Book
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            ratesContainer.innerHTML = destInfoHtml;

            // Add event listener for booking with no rate
            document.querySelector('.book-no-rate-btn').addEventListener('click', function() {
              bookShipping(null, true); // pass true to indicate no rates
            });

            return;
          }


          let html = '';
          let availableServices = rates.length;

          // Logo base path
          let logoPath = '/logos/'; // Adjust as needed, e.g. your CDN or assets path

          rates.forEach(rate => {
            let logoFile = 'default.png';
            let courierIcon = '';
            const name = (rate.courier_service?.umbrella_name || '').trim();

            switch (name) {
              case 'DHL':
                logoFile = 'dhl.svg';
                break;
              case 'UPS':
                logoFile = 'ups.svg';
                break;
              case 'Aramex':
                logoFile = 'aramex.svg';
                break;
              case 'FlatExportRate':
                logoFile = 'flatexportrate.svg';
                break;
              case 'SFExpress':
                logoFile = 'sf-express.svg';
                break;
              case 'Asendia':
                logoFile = 'asendia.svg';
                break;
              case 'Passport':
                logoFile = 'passport.svg';
                break;
              case 'FedEx':
                logoFile = 'fedex.svg';
                break;
              case 'USPS':
                logoFile = 'usps.svg';
                break;
              case 'Sendle':
                logoFile = 'sendle.svg';
                break;
              case 'Purolator':
                logoFile = 'purolator.svg';
                break;
              case 'Canada Post':
                logoFile = 'canada-post.svg';
                break;
              case 'Canpar':
                logoFile = 'canpar.svg';
                break;
              case 'StarTrack':
                logoFile = 'star-track.png';
                break;
              case 'CouriersPlease':
                logoFile = 'couriers-please.svg';
                break;
              case 'AlliedExpress':
                logoFile = 'alliedexpress.svg';
                break;
              case 'TNT':
                logoFile = 'tnt.svg';
                break;
              case 'Quantium':
                logoFile = 'quantium.svg';
                break;
              case 'Toll':
                logoFile = 'toll.svg';
                break;
              case 'HKPost':
                logoFile = 'hong-kong-post.svg';
                break;
              case 'APG':
                logoFile = 'apg.svg';
                break;
              case 'Hubbed':
                logoFile = 'hubbed.svg';
                break;
              default:
                courierIcon = `
                  <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="#2b6cb0" stroke-width="1.5">
                    <rect x="2" y="7" width="20" height="12" rx="2" fill="#e6f2ff"/>
                    <path d="M12 3v4"/>
                    <path d="M7 7l5 4 5-4"/>
                  </svg>`;
            }

            if (!courierIcon)
              courierIcon = `<img class="rounded-3 me-2" src="${logoPath + logoFile}" alt="${name}" width="auto" height="40">`;

            const chargeWithTax = rate.total_charge * 1.15;
            const trackingRating = rate.tracking_rating || 0;

            // Tracking dots
            let trackingIcons = '';
            for (let i = 0; i < 5; i++) {
              trackingIcons += `<span style="color:${i < trackingRating ? '#00c853' : '#ccc'};">●</span>`;
            }

            // Handover options
            let serviceOptions = '-';
            if (rate.available_handover_options && rate.available_handover_options.length > 0) {
              serviceOptions = rate.available_handover_options.map(opt => {
                let icon = opt.includes('pickup') ? '🏠' : '📦';
                let label = opt.replace(/_/g, ' ');
                return `<div>${icon} ${label.charAt(0).toUpperCase() + label.slice(1)}</div>`;
              }).join('');
            }

            html += `
              <div class="card shadow-sm mb-3 p-3 rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    ${courierIcon}
                    <div>
                      <h6 class="mb-0 fw-bold">${rate.courier_service?.umbrella_name || '-'}</h6>
                      <small class="text-muted">${rate.courier_service?.name || '-'}</small>
                    </div>
                  </div>
                  <div class="text-end">
                    <h5 class="mb-0 fw-bold">${rate.currency} ${chargeWithTax.toFixed(2)}</h5>
                  </div>
                </div>

                <hr class="my-2">

                <div class="row align-items-center text-muted small">
                  <div class="col-md-2 col-6">
                    <strong>Delivery Time:</strong><br>
                    ${rate.min_delivery_time} - ${rate.max_delivery_time} days
                  </div>
                  <div class="col-md-1 col-6">
                    <strong>Tracking:</strong><br>${trackingIcons}
                  </div>
                  <div class="col-md-2 col-6">
                    <strong>Description:</strong><br>${rate.full_description || '—'}
                  </div>
                  <div class="col-md-2 col-6">
                    <strong>Import Tax & Duty:</strong><br>
                    Tax: ${rate.estimated_import_tax || 0}, Duty: ${rate.estimated_import_duty || 0}
                  </div>
                  <div class="col-md-2 col-6">
                    <strong>Rating:</strong><br>${trackingRating}/5 ⭐
                  </div>
                  <div class="col-md-2 col-6">
                    <strong>Service Options:</strong><br>${serviceOptions}
                  </div>
                  <div class="col-md-1 col-12 text-end mt-2 mt-md-0">
                    <button class="btn btn-sm btn-primary book-btn" 
                      data-rate='${JSON.stringify(rate).replace(/'/g, "\\'")}'>
                      Book
                    </button>
                  </div>
                </div>
              </div>
            `;
          });

          const availableHeader = `
            <div class="row text-center py-3">
              <h4>Available Services (${availableServices})</h4>
            </div>
          `;

          ratesContainer.innerHTML = availableHeader + html;

          // Event listeners
          document.querySelectorAll('.book-btn').forEach(btn => {
            btn.addEventListener('click', function() {
              const rate = JSON.parse(this.dataset.rate);
              bookShipping(rate);
            });
          });
        })
        .catch(e => {
          let logoPath = `<a href="https://wa.me/+96892656567"><img src="<?= base_url('logos/WhatsAppButtonGreenSmall.svg') ?>"/></a>`;
          ratesContainer.innerHTML = `<div class="alert alert-danger">Failed to calculate rates. Please try again.${logoPath}</div>`;
          console.error(e);
        });
    }
    // get categories
    async function loadCategories() {
      const categorySelect = document.getElementById('categorySelect');
      if (!categorySelect) return;

      categorySelect.innerHTML = '<option>Loading...</option>';

      try {
        const response = await fetch('<?= base_url('packages/shipping-categories') ?>');
        const data = await response.json();

        if (data.success && data.categories.length) {
          categorySelect.innerHTML = data.categories
            .map(cat => `
          <option 
            value="${cat.slug || cat.name}" 
            data-hs-code="${cat.hs_code || ''}">
            ${cat.name}
          </option>
        `)
            .join('');
        } else {
          categorySelect.innerHTML = '<option>No categories available</option>';
        }
      } catch (err) {
        console.error('Error loading categories:', err);
        categorySelect.innerHTML = '<option>Error loading categories</option>';
      }
    }



    // Book shipping function
    function bookShipping(rate = null, noRate = false) {

      const totals = window.packageTotals;

      // Get the selected package IDs for shipping
      const packageIds = window.selectedPackageIdsForShipping || selectedPackages;

      if (!packageIds || packageIds.length === 0) {
        Swal.fire({
          icon: 'error',
          title: 'No Packages Selected',
          text: 'Please select packages to ship.',
          confirmButtonColor: '#ff6600'
        });
        return;
      }
      // Get the selected category from the dropdown
      const categorySelect = document.getElementById('categorySelect');
      let selectedCategory = 'general';
      let hsCode = '';

      if (categorySelect && categorySelect.value) {
        selectedCategory = categorySelect.value;
        hsCode = categorySelect.options[categorySelect.selectedIndex]?.dataset.hsCode || '';
      } else {
        // Fallback: show warning if no category selected
        Swal.fire({
          icon: 'warning',
          title: 'Category Not Selected',
          text: 'Please select a category before booking.',
          confirmButtonColor: '#ff6600'
        });
        return;
      }

      // let = package_ids;
      const bookingData = new URLSearchParams();
      packageIds.forEach(id => {
        bookingData.append('package_ids[]', id);
      });
      // Origin address
      bookingData.append('origin_line_1', currentOrigin.address_line_1 || '');
      bookingData.append('origin_city', currentOrigin.city || '');
      bookingData.append('origin_state', currentOrigin.state || '');
      bookingData.append('origin_postal_code', currentOrigin.postal_code || '');
      bookingData.append('origin_country', currentOrigin.code || '');

      // Destination address
      bookingData.append('dest_line_1', currentDestination.line_1 || '');
      bookingData.append('dest_city', currentDestination.city || '');
      bookingData.append('dest_state', currentDestination.state || '');
      bookingData.append('dest_postal_code', currentDestination.postal_code || '');
      bookingData.append('dest_country', currentDestination.country || '');

      // Parcel details
      bookingData.append('weight', totals.weight || '');
      bookingData.append('length', totals.length || '');
      bookingData.append('width', totals.width || '');
      bookingData.append('height', totals.height || '');
      bookingData.append('category', selectedCategory);

      if (noRate) {
        // No rate booking
        bookingData.append('set_rate', 1);
        bookingData.append('total_charge', 0);
        bookingData.append('courier_name', '');
        bookingData.append('service_name', '');
        bookingData.append('delivery_time', '');
        bookingData.append('currency', '');
        bookingData.append('description', 'No rates available!');


      } else {

        // Booking with rate
        bookingData.append('set_rate', 0);
        bookingData.append('total_charge', rate.total_charge || 0);
        bookingData.append('courier_name', rate.courier_service?.umbrella_name || '');
        bookingData.append('service_name', rate.courier_service?.name || '');
        bookingData.append('delivery_time', `${rate.min_delivery_time} - ${rate.max_delivery_time} days`);
        bookingData.append('currency', rate.currency || '');
        bookingData.append('description', rate.full_description || '');
      }

      // Show loading state
      const bookBtn = noRate ?
        document.querySelector('.book-no-rate-btn') :
        event.target;
      const originalText = bookBtn.innerHTML;
      bookBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Booking...';
      bookBtn.disabled = true;

      fetch('<?= base_url('shipping/book') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: bookingData
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Booking Confirmed!',
              html: `✅ Your booking has been confirmed.<br><b>Booking ID:</b> ${data.booking_id}`,
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didClose: () => {
                let redirectUrl = '';
                if (data.role === 'customer') {
                  redirectUrl = "<?= site_url('customer/shipping/details/') ?>" + data.booking_id;
                } else {
                  redirectUrl = "<?= site_url('shipping/details/') ?>" + data.booking_id;
                }
                window.location.href = redirectUrl;
              }
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Booking Failed',
              html: data.message || 'Something went wrong while booking.',
              confirmButtonColor: '#d33'
            });
          }
        })
        .catch(error => {
          console.error('Booking error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            html: 'An unexpected error occurred while booking.',
            confirmButtonColor: '#d33'
          });
          bookBtn.innerHTML = originalText;
          bookBtn.disabled = false;
        });
    }




    // Form validation
    const customAddressForm = document.getElementById('customAddressForm');
    customAddressForm.addEventListener('submit', function(e) {
      e.preventDefault();
      if (!this.checkValidity()) {
        e.stopPropagation();
        this.classList.add('was-validated');
      }
    }, false);

    // Initialize countries dropdown for custom address
    function initializeCountriesDropdown() {
      const countrySelect = document.getElementById('dest_country');
      if (countrySelect) {
        // This would typically be populated from your countries JSON
        // For now, we'll keep the existing PHP-generated options
      }
    }

    initializeCountriesDropdown();
  });
</script>