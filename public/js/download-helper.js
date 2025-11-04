class DownloadHelper {
  /**
   * Download current page content
   */
  static async downloadPage(options = {}) {
    const config = {
      filename:
        options.filename ||
        `document_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}`,
      format: options.format || 'html',
      title: options.title || document.title,
      includeStyles: options.includeStyles !== false,
      ...options,
    }

    try {
      // Get the main content area
      const contentElement =
        options.contentElement ||
        document.querySelector('.container, main, .content') ||
        document.body

      // Clone the element to avoid modifying the original
      const contentClone = contentElement.cloneNode(true)

      // Remove unwanted elements
      this.cleanContentForDownload(contentClone)

      // Create the HTML content
      const htmlContent = this.generateDownloadHTML(contentClone, config)

      // Trigger download
      this.triggerDownload(htmlContent, config.filename, config.format)

      return true
    } catch (error) {
      console.error('Download failed:', error)
      this.showError('Download failed. Please try again.')
      return false
    }
  }

  /**
   * Clean content for download
   */
  static cleanContentForDownload(element) {
    // Remove interactive elements
    const elementsToRemove = element.querySelectorAll(`
            button, 
            .btn, 
            .nav-pills,
            .action-buttons,
            .map-placeholder,
            [onclick],
            [data-bs-toggle],
            .tab-content > .tab-pane:not(.active)
        `)

    elementsToRemove.forEach((el) => el.remove())

    // Show all tab panes for download
    const allTabPanes = element.querySelectorAll('.tab-pane')
    allTabPanes.forEach((pane) => {
      pane.classList.add('show', 'active')
      pane.style.display = 'block'
    })

    // Remove scrollable nav
    const scrollableNav = element.querySelector('.nav-scrollable')
    if (scrollableNav) {
      const navPills = scrollableNav.querySelector('.nav-pills')
      if (navPills) {
        // Convert to simple list for download
        const simpleList = document.createElement('div')
        simpleList.className = 'warehouse-list'
        Array.from(navPills.children).forEach((tab) => {
          const button = tab.querySelector('button')
          if (button) {
            const country = button.textContent.trim()
            const listItem = document.createElement('div')
            listItem.className = 'warehouse-item mb-2'
            listItem.textContent = country
            simpleList.appendChild(listItem)
          }
        })
        scrollableNav.replaceWith(simpleList)
      }
    }
  }

  /**
   * Generate complete HTML document for download
   */
  static generateDownloadHTML(content, config) {
    return `
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${config.title} - Download</title>
    
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/auth_style.css'); ?>">
    <style>
        ${config.includeStyles ? this.getPrintableStyles() : ''}
        .download-header {
            border-bottom: 2px solid #4E148C;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        .download-footer {
            border-top: 1px solid #ddd;
            padding-top: 1rem;
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #666;
        }
        .warehouse-list {
            margin-bottom: 2rem;
        }
        .warehouse-item {
            padding: 0.5rem;
            border-left: 3px solid #4E148C;
            background-color: #f8f9fa;
        }
        
        .badge bg-shippex-primary{
        color:#FFF;
        }
    </style>
</head>
<body>
    <div class="download-header">
        <h1>${config.title}</h1>
        <p class="text-muted">Generated on: ${new Date().toLocaleString()}</p>
    </div>
    
    ${content.outerHTML}
    
    <div class="download-footer">
        <p>© ${new Date().getFullYear()} Shippex CO. All rights reserved.</p>
        <p>Downloaded from: ${window.location.href}</p>
    </div>
</body>
</html>`
  }

  /**
   * Get styles for printable/downloadable content
   */
  static getPrintableStyles() {
    return `
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px; 
        }
        .card { 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            break-inside: avoid; 
        }
        .card-header { 
            background: #4E148C !important; 
            color: white !important; 
            padding: 1rem; 
            font-weight: bold; 
        }
        .card-body { 
            padding: 1.5rem; 
        }
            .noprint{
            display:none !important;
            }
        .info-item { 
            display: flex; 
            align-items: flex-start; 
            margin-bottom: 1rem; 
        }
        .icon-circle { 
            width: 40px; 
            height: 40px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-right: 12px; 
            background: #F0E6FF !important; 
            color: #4E148C !important; 
        }
        .text-shippex-primary { 
            color: #4E148C !important; 
        }
            .d-flex{
            display:flex;}
        .badge { 
            background: #F0E6FF !important; 
            color: #4E148C !important; 
            padding: 0.25rem 0.5rem; 
            border-radius: 4px; 
            font-size: 0.8rem; 
        }
            .badge bg-shippex-primary{
            color:#FFF;
            }
        @media print {
            .btn, .action-buttons,.noprint { display: none !important; }
        }
            
        :root {
  --primary-color: #4d148c;
  --secondary-color: #ff6600;
  --shippex-light: #f0e6ff;
  --shippex-accent: #e74c3c;
  --shippex-light: #ecf0f1;
  --shippex-success: #2ecc71;
}

body {
  background-color: #f8f9fa;
}

/* Sidebar Styling */
.sidebar {
  min-height: 100vh;
  background: linear-gradient(180deg, var(--primary-color) 0%, #3a0d6b 100%);
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
  transition: all 0.3s;
}
.url.d-none{
  display:block !important;
}
.sidebar a {
  color: #adb5bd;
  text-decoration: none;
}

.sidebar .nav-link {
  color: rgba(255, 255, 255, 0.8);
  padding: 0.75rem 1.5rem;
  margin: 0.25rem 0;
  border-radius: 0.25rem;
  transition: all 0.2s;
}

.sidebar a:hover,
.sidebar .nav-link:hover,
.sidebar .nav-link.active {
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
}

.sidebar .nav-link.active {
  border-left: 3px solid var(--secondary-color);
  /* background-color: rgba(255, 255, 255, 0.1);; */
}

.sidebar .nav-link i {
  width: 20px;
  text-align: center;
  margin-right: 10px;
}

.sidebar .collapse .nav-link {
  padding-left: 1.5rem;
}

.sidebar-header {
  padding: 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  margin-bottom: 1rem;
}

/* Main Content */
.content {
  padding: 0.5rem 0;
}

/* Navbar */
.navbar {
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  padding: 1rem 1.5rem;
}

.navbar-brand {
  font-weight: bold;
  color: var(--primary-color);
}

/* Background Colors */
.bg-shippex-purple {
  background-color: var(--primary-color) !important;
}

.bg-shippex-orange {
  background-color: var(--secondary-color) !important;
}

/* Text Colors */
.text-shippex-purple {
  color: var(--primary-color) !important;
}

/* Buttons */
.btn-shippex {
  background-color: var(--primary-color);
  color: white;
  border: none;
}

.btn-shippex:hover {
  background-color: #3a0d6b;
  color: white;
}

.btn-shippex-orange {
  background-color: var(--secondary-color);
  color: white;
  border-color: var(--secondary-color);
}

.btn-shippex-orange:hover {
  background-color: #e05c00;
  border-color: #e05c00;
  color: white;
}

.btn-outline-shippex-purple {
  color: var(--primary-color);
  border-color: var(--primary-color);
}

.btn-outline-shippex-purple:hover {
  background-color: var(--primary-color);
  color: white;
}

.btn-outline-shippex-orange {
  color: var(--secondary-color);
  border-color: var(--secondary-color);
}

.btn-outline-shippex-orange:hover {
  background-color: var(--secondary-color);
  color: white;
}

/* Borders */
.border-shippex-orange {
  border: 2px solid var(--secondary-color) !important;
}

/* Cards */
.card {
  border: none;
  border-radius: 0.5rem;
  box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s;
}

.card:hover {
  transform: translateY(-3px);
}

.card-header {
  background-color: var(--primary-color);
  color: white;
  border-radius: 0.5rem 0.5rem 0 0 !important;
}

.address-card {
  transition: all 0.3s ease;
  border-radius: 8px;
}

.address-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.table-header {
  background-color: var(--primary-color);
  color: white;
  border-radius: 10px 10px 0 0;
}

.table th {
  border: none;
  padding: 1rem 0.75rem;
  font-weight: 500;
}

.table td {
  padding: 1rem 0.75rem;
  vertical-align: middle;
  border-color: #f1f1f1;
}

.status-badge {
  padding: 0.35em 0.65em;
  border-radius: 20px;
  font-size: 0.75em;
  font-weight: 600;
}

.btn-shippex {
  background-color: var(--secondary-color);
  color: white;
  border: none;
  border-radius: 6px;
  padding: 0.5rem 1rem;
  transition: all 0.3s;
}

.btn-shippex:hover {
  background-color: var(--primary-color);
  transform: translateY(-2px);
}

.btn-action {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  border-radius: 4px;
}

@media (max-width: 768px) {
  .table-responsive {
    border-radius: 10px;
    overflow: hidden;
  }

  .stats-card {
    margin-bottom: 1rem;
  }
}

.shippex-header {
  background: var(--primary-color);
  color: white;
  padding: 1.5rem 0;
  margin-bottom: 2rem;
  border-radius: 0 0 10px 10px;
}

.card {
  border: none;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  margin-bottom: 1.5rem;
}

.card-header {
  background-color: var(--primary-color);
  color: white;
  border-radius: 10px 10px 0 0 !important;
  padding: 1rem 1.5rem;
  font-weight: 600;
}

.info-badge {
  padding: 0.5em 1em;
  border-radius: 20px;
  font-size: 0.9em;
  font-weight: 600;
}

.address-card {
  transition: all 0.3s;
}

.address-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.detail-item {
  padding: 0.75rem 0;
  border-bottom: 1px solid #f1f1f1;
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-label {
  font-weight: 600;
  color: var(--primary-color);
}

.tracking-progress {
  height: 8px;
  border-radius: 4px;
}

.status-badge {
  padding: 0.5em 1em;
  border-radius: 20px;
  font-size: 0.9em;
  font-weight: 600;
}

.shippex-btn {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
  color: white;
  padding: 10px 25px;
  font-weight: 600;
}
.shippex-btn:hover {
  background-color: var(--secondary-color);
}
.timeline {
  position: relative;
  padding-left: 2rem;
  margin: 2rem 0;
}

.timeline::before {
  content: '';
  position: absolute;
  left: 5px;
  top: 5px;
  height: 100%;
  width: 2px;
  background-color: var(--secondary-color);
}

.timeline-item {
  position: relative;
  margin-bottom: 1.5rem;
}

.timeline-item::before {
  content: '';
  position: absolute;
  left: -2rem;
  top: 5px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background-color: var(--secondary-color);
  border: 2px solid white;
  box-shadow: 0 0 0 2px var(--secondary-color);
}

.timeline-item.completed::before {
  background-color: var(--shippex-success);
  box-shadow: 0 0 0 2px var(--shippex-success);
}

.map-container {
  height: 200px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--secondary-color);
  font-weight: 600;
}

@media (max-width: 768px) {
  .card {
    margin-bottom: 1rem;
  }
}
.status-management {
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.status-header {
  color: var(--primary-color);
  border-bottom: 2px solid var(--shippex-light);
  padding-bottom: 0.75rem;
  margin-bottom: 1.5rem;
  font-weight: 600;
}

.status-badge {
  padding: 0.5em 1em;
  border-radius: 20px;
  font-size: 0.85em;
  font-weight: 600;
}

.status-pending {
  background-color: #fff3cd;
  color: #856404;
}

.status-canceled {
  background-color: #f8d7da;
  color: #721c24;
}

.status-accepted {
  background-color: #d1ecf1;
  color: #0c5460;
}

.status-shipping {
  background-color: #d1e7ff;
  color: #004085;
}

.status-shipped {
  background-color: #d4edda;
  color: #155724;
}

.status-delivered {
  background-color: #d1e7dd;
  color: #0f5132;
}

.status-option {
  display: flex;
  align-items: center;
  padding: 0.75rem;
  margin-bottom: 0.5rem;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
  border: 2px solid transparent;
}

.status-option:hover {
  background-color: #f8f9fa;
  transform: translateY(-2px);
}

.status-option.selected {
  border-color: var(--secondary-color);
  background-color: rgba(52, 152, 219, 0.1);
}

.status-indicator {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  margin-right: 10px;
  flex-shrink: 0;
}

.indicator-pending {
  background-color: #ffc107;
}

.indicator-canceled {
  background-color: #dc3545;
}

.indicator-accepted {
  background-color: #17a2b8;
}

.indicator-shipping {
  background-color: #007bff;
}

.indicator-shipped {
  background-color: #28a745;
}

.indicator-delivered {
  background-color: #20c997;
}

.btn-status-update {
  background-color: var(--secondary-color);
  color: white;
  border: none;
  border-radius: 6px;
  padding: 0.5rem 1.5rem;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-status-update:hover {
  background-color: var(--primary-color);
  transform: translateY(-2px);
  color: white;
}

.status-history {
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid #eee;
}

.history-item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f8f9fa;
}

.history-item:last-child {
  border-bottom: none;
}

.history-status {
  font-weight: 600;
}

.history-date {
  color: #6c757d;
  font-size: 0.9em;
}

        
        `
  }

  /**
   * Trigger the actual download
   */
  static triggerDownload(content, filename, format) {
    const blob = new Blob([content], {
      type: format === 'pdf' ? 'application/pdf' : 'text/html',
    })

    const url = URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.download = `${filename}.${format === 'pdf' ? 'pdf' : 'html'}`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(url)
  }

  /**
   * Show error message
   */
  static showError(message) {
    // You can use Toast, SweetAlert, or simple alert
    alert(message)
  }
}

// Make it available globally
window.DownloadHelper = DownloadHelper
