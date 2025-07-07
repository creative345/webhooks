@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
    * { 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
    }
    
    body { 
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
        background: #f8fafc; 
        color: #1a1a1a; 
        line-height: 1.5; 
    }
    
    /* Override Materialize CSS nav styling */
    nav {
        background-color: transparent !important;
    }
    
    /* Layout with Sidebar */
    .layout {
        display: flex;
        min-height: 100vh;
        background: #f8fafc;
    }
    
    .sidebar {
        width: 250px;
        background: #1f2937;
        color: #ffffff;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid #374151;
    }
    
    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
    }
    
    .sidebar-nav {
        flex: 1;
        padding: 1rem 0;
    }
    
    .nav-item {
        display: block;
        padding: 0.75rem 1.5rem;
        color: #d1d5db;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    
    .nav-item:hover,
    .nav-item.active {
        background: #374151;
        color: #ffffff;
        border-left-color: #6366f1;
    }
    
    .nav-item i {
        width: 20px;
        margin-right: 0.75rem;
    }
    
    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    
    .container {
        max-width: 100%;
        margin: 0 auto;
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
        width: 100%;
    }
    
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: #6b7280;
        font-size: 1rem;
    }
    
    /* Filter Pills Styling */
    .filter-pills {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-pill {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .filter-pill:hover {
        border-color: #d1d5db;
        background: #f9fafb;
    }
    
    .filter-pill.active {
        background: #1f2937;
        color: #ffffff;
        border-color: #1f2937;
    }
    
    .filter-pill.has-active-filters {
        background: #3b82f6;
        color: #ffffff;
        border-color: #3b82f6;
        position: relative;
    }
    
    .filter-pill.has-active-filters::after {
        content: attr(data-count);
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.75rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
    }
    
    .filter-pill .fa-chevron-down {
        font-size: 0.75rem;
        transition: transform 0.2s ease;
    }
    
    .filter-pill.active .fa-chevron-down {
        transform: rotate(180deg);
    }
    
    /* Dropdown Menus */
    .filter-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        min-width: 200px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        padding: 0.75rem;
        display: none;
    }
    
    .filter-dropdown.show {
        display: block;
    }
    
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.875rem;
        color: #374151;
        transition: background 0.15s ease;
    }
    
    .dropdown-item:hover {
        background: #f3f4f6;
    }
    
    .dropdown-item input[type="checkbox"] {
        margin: 0;
        accent-color: #1f2937;
    }
    
    .dropdown-item {
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
        background: #f3f4f6;
    }
    
    .dropdown-item.active {
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 500;
    }
    
    .dropdown-item.active input[type="checkbox"] {
        accent-color: #3b82f6;
    }
    
    /* Search Bar */
    .search-container {
        position: relative;
        margin-bottom: 1.5rem;
        max-width: 400px;
    }
    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        background: #ffffff;
        color: #1a1a1a;
        outline: none;
        transition: border-color 0.2s ease;
    }
    .search-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 0.875rem;
    }
    
    /* Table Container */
    .table-container {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    /* Table Styling */
    .payments-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }
    
    .payments-table th {
        background: #f9fafb;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    
    .payments-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
        color: #1a1a1a;
    }
    
    .payments-table tbody tr:hover {
        background: #f8fafc;
    }
    
    .payments-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    
    .status-succeeded {
        background: #dcfce7;
        color: #166534;
    }
    
    .status-failed {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #d97706;
    }
    
    .status-refunded {
        background: #e0e7ff;
        color: #4338ca;
    }
    
    .status-cancelled {
        background: #f3f4f6;
        color: #6b7280;
    }
    
    /* Payment Method Icons */
    .payment-method {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .payment-icon {
        width: 24px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .payment-icon.visa {
        background: #1a1f71;
        color: white;
        border-radius: 2px;
        font-size: 0.6rem;
        font-weight: bold;
    }
    
    .payment-icon.mastercard {
        background: #eb001b;
        color: white;
        border-radius: 2px;
        font-size: 0.6rem;
        font-weight: bold;
    }
    
    .payment-icon.paypal {
        color: #0070ba;
        font-size: 1rem;
    }
    
    /* Amount & Status Cell Styling */
    .amount-status-cell {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        width: 100%;
    }
    
    .amount-row {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-shrink: 0;
    }
    
    .amount {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.875rem;
    }
    
    .currency {
        color: #6b7280;
        font-weight: 400;
        font-size: 0.75rem;
    }
    
    .status-row {
        flex-shrink: 0;
    }
    
    /* Customer Email */
    .customer-email {
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    /* Date Styling */
    .date {
        color: #6b7280;
        font-size: 0.875rem;
        white-space: nowrap;
    }
    
    /* Action Buttons */
    .action-btn {
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: all 0.15s ease;
    }
    
    .action-btn:hover {
        background: #f3f4f6;
        color: #1a1a1a;
    }
    
    /* Checkbox Styling */
    input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #1f2937;
        cursor: pointer;
    }
    
    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .pagination-info {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .pagination-controls {
        display: flex;
        gap: 0.25rem;
        align-items: center;
        overflow-x: auto;
        max-width: 100%;
        padding: 0.25rem;
    }
    
    .pagination-controls::-webkit-scrollbar {
        height: 4px;
    }
    
    .pagination-controls::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }
    
    .pagination-controls::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }
    
    .pagination-controls::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    
    .pagination-btn {
        padding: 0.5rem 0.75rem;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #374151;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.15s ease;
        white-space: nowrap;
        min-width: 40px;
        text-align: center;
        flex-shrink: 0;
    }
    
    .pagination-btn:hover:not(:disabled) {
        background: #f3f4f6;
        border-color: #d1d5db;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .pagination-btn:disabled:hover {
        transform: none;
        box-shadow: none;
    }
    
    .pagination-btn.active {
        background: #1f2937;
        color: #ffffff;
        border-color: #1f2937;
        box-shadow: 0 2px 4px rgba(31, 41, 55, 0.2);
    }
    
    .pagination-btn.active:hover {
        background: #111827;
        border-color: #111827;
    }
    
    .pagination-ellipsis {
        padding: 0.5rem 0.25rem;
        color: #6b7280;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    /* Loading State */
    .table-loader {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 3rem;
        color: #6b7280;
        background: rgba(255, 255, 255, 0.9);
        position: relative;
    }

    .spinner {
        width: 30px;
        height: 30px;
        border: 3px solid #e5e7eb;
        border-top: 3px solid #6366f1;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 0.75rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Filter Loading State */
    .filter-loading {
        opacity: 0.6;
        pointer-events: none;
        position: relative;
    }
    
    .filter-loading::after {
        content: '';
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 2px solid #e5e7eb;
        border-top: 2px solid #6366f1;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    /* Clear Filters Button */
    .clear-filters {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        color: #6b7280;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-left: auto;
    }
    
    .clear-filters:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }
        
        .container {
            padding: 1rem;
        }
        
        .filter-pills {
            gap: 0.5rem;
        }
        
        .filter-pill {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }
        
        .payments-table {
            font-size: 0.75rem;
        }
        
        .payments-table th,
        .payments-table td {
            padding: 0.75rem 0.5rem;
        }
        
        .pagination-container {
            flex-direction: column;
            gap: 1rem;
        }
        
        .amount-status-cell {
            gap: 0.5rem;
            flex-wrap: nowrap;
        }
        
        .amount {
            font-size: 0.75rem;
        }
        
        .currency {
            font-size: 0.65rem;
        }
        
        .status-badge {
            font-size: 0.65rem;
            padding: 0.15rem 0.5rem;
        }
    }
</style>

<div class="layout">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
        <div class="sidebar-logo">
                <img src="/CR-LOGO.png" alt="Logo" width="32" height="32">
                <span>Payment CRM</span>
        </div>
    </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">
                <i class="fa fa-dashboard"></i>
                Dashboard
            </a>
            <a href="#" class="nav-item">
                <i class="fa fa-credit-card"></i>
                Payments
            </a>
            <a href="#" class="nav-item">
                <i class="fa fa-users"></i>
                Customers
            </a>
            <a href="#" class="nav-item">
                <i class="fa fa-chart-bar"></i>
                Analytics
            </a>
            <a href="#" class="nav-item">
                <i class="fa fa-cog"></i>
                Settings
            </a>
        </nav>
                </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Payments</h1>
                <p class="page-subtitle">Manage and track your payment transactions</p>
                </div>
                
    <!-- Filter Pills -->
    <div class="filter-pills">
        <div class="filter-pill" data-filter="date">
            <i class="fa fa-calendar"></i>
                                    Date and time
                                    <i class="fa fa-chevron-down"></i>
            <div class="filter-dropdown" id="date-dropdown">
                <div class="dropdown-item">
                    <input type="checkbox" value="today" id="date-today">
                    <label for="date-today">Today</label>
                                        </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="yesterday" id="date-yesterday">
                    <label for="date-yesterday">Yesterday</label>
                                        </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="last-7-days" id="date-week">
                    <label for="date-week">Last 7 days</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="last-30-days" id="date-month">
                    <label for="date-month">Last 30 days</label>
                </div>
                <div class="dropdown-item">
                    <label>From:</label>
                    <input type="date" id="custom-from-date" style="width: 100%; margin-top: 0.25rem; padding: 0.25rem; border: 1px solid #e5e7eb; border-radius: 4px;">
                </div>
                <div class="dropdown-item">
                    <label>To:</label>
                    <input type="date" id="custom-to-date" style="width: 100%; margin-top: 0.25rem; padding: 0.25rem; border: 1px solid #e5e7eb; border-radius: 4px;">
                </div>
            </div>
        </div>
        
        <div class="filter-pill" data-filter="status">
            <i class="fa fa-check-circle"></i>
                                    Status
                                    <i class="fa fa-chevron-down"></i>
            <div class="filter-dropdown" id="status-dropdown">
                <div class="dropdown-item">
                    <input type="checkbox" value="succeeded" id="status-succeeded">
                    <label for="status-succeeded">Succeeded</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="failed" id="status-failed">
                    <label for="status-failed">Failed</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="pending" id="status-pending">
                    <label for="status-pending">Pending</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="refunded" id="status-refunded">
                    <label for="status-refunded">Refunded</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="cancelled" id="status-cancelled">
                    <label for="status-cancelled">Cancelled</label>
                </div>
            </div>
        </div>
        
        <div class="filter-pill" data-filter="payment-method">
            <i class="fa fa-credit-card"></i>
                                    Payment method
                                    <i class="fa fa-chevron-down"></i>
            <div class="filter-dropdown" id="payment-method-dropdown">
                <div class="dropdown-item">
                    <input type="checkbox" value="card" id="method-card">
                    <label for="method-card">Card</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="paypal" id="method-paypal">
                    <label for="method-paypal">PayPal</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="visa" id="method-visa">
                    <label for="method-visa">Visa</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="mastercard" id="method-mastercard">
                    <label for="method-mastercard">Mastercard</label>
                </div>
            </div>
        </div>
        
        <div class="filter-pill" data-filter="gateway">
            <i class="fa fa-server"></i>
            Gateway
                                    <i class="fa fa-chevron-down"></i>
            <div class="filter-dropdown" id="gateway-dropdown">
                <div class="dropdown-item">
                    <input type="checkbox" value="stripe" id="gateway-stripe">
                    <label for="gateway-stripe">Stripe</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="paypal" id="gateway-paypal">
                    <label for="gateway-paypal">PayPal</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="square" id="gateway-square">
                    <label for="gateway-square">Square</label>
                </div>
            </div>
        </div>
        
        <div class="filter-pill" data-filter="more">
            <i class="fa fa-filter"></i>
            More filters
                                    <i class="fa fa-chevron-down"></i>
            <div class="filter-dropdown" id="more-dropdown">
                <div class="dropdown-item">
                    <input type="checkbox" value="test" id="more-test">
                    <label for="more-test">Test payments</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="live" id="more-live">
                    <label for="more-live">Live payments</label>
                </div>
                <div class="dropdown-item">
                    <input type="checkbox" value="disputed" id="more-disputed">
                    <label for="more-disputed">Disputed</label>
                </div>
            </div>
        </div>
        
        <button class="clear-filters" id="clearFilters">
            <i class="fa fa-times"></i>
            Clear all
                                </button>
    </div>
    
    <!-- Search Bar -->
    <div class="search-container">
        <div class="search-icon">
            <i class="fa fa-search"></i>
                        </div>
        <input type="text" class="search-input" id="searchBox" placeholder="Search payments...">
                    </div>
    
    <!-- Table Container -->
    <div class="table-container">
        <table class="payments-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllRows"></th>
                                <th>Amount & Status</th>
                                <th>Payment method</th>
                                <th>Gateway</th>
                                <th>Description</th>
                                <th>Customer</th>
                                <th>Date</th>
                    <th>Refunded date</th>
                                <th>Decline reason</th>
                    <th></th>
                            </tr>
                        </thead>
                        <tbody id="paymentsTbody">
                <!-- Dynamic content will be loaded here -->
                        </tbody>
                    </table>
        
        <!-- Pagination -->
        <div class="pagination-container" id="paginationContainer">
            <div class="pagination-info" id="paginationInfo"></div>
            <div class="pagination-controls" id="paginationControls"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let activeFilters = {};
let currentPage = 1;
let paginationData = {};

// Initialize dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    initializeSearch();
    initializeFilterStates();
    fetchPayments(1);
});

function initializeFilterStates() {
    // Set initial active states for any pre-checked checkboxes
    document.querySelectorAll('.filter-dropdown input[type="checkbox"]:checked').forEach(checkbox => {
        const dropdownItem = checkbox.closest('.dropdown-item');
        if (dropdownItem) {
            dropdownItem.classList.add('active');
        }
    });
    
    // Update filter pill visual states
    updateFilterPillsVisual();
}

function initializeFilters() {
    const filterPills = document.querySelectorAll('.filter-pill');
    
    filterPills.forEach(pill => {
        pill.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Don't toggle if clicking on checkbox or inside dropdown
            if (e.target.type === 'checkbox' || e.target.closest('.filter-dropdown')) {
                return;
            }
            
            const dropdown = this.querySelector('.filter-dropdown');
            const isActive = this.classList.contains('active');
            
            // Close all other dropdowns
            document.querySelectorAll('.filter-pill').forEach(p => {
                if (p !== this) {
                    p.classList.remove('active');
                    const d = p.querySelector('.filter-dropdown');
                    if (d) d.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            if (!isActive) {
                this.classList.add('active');
                if (dropdown) dropdown.classList.add('show');
            } else {
                this.classList.remove('active');
                if (dropdown) dropdown.classList.remove('show');
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        // Don't close if clicking on checkbox or label
        if (e.target.type === 'checkbox' || e.target.tagName === 'LABEL') {
            return;
        }
        
        // Don't close if clicking inside a dropdown
        if (e.target.closest('.filter-dropdown')) {
            return;
        }
        
        document.querySelectorAll('.filter-pill').forEach(p => {
            p.classList.remove('active');
            const d = p.querySelector('.filter-dropdown');
            if (d) d.classList.remove('show');
        });
    });
    
    // Handle filter changes with immediate AJAX
    document.querySelectorAll('.filter-dropdown input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function(e) {
            e.stopPropagation(); // Prevent dropdown from closing
            
            console.log('Checkbox changed:', this.value, 'checked:', this.checked); // Debug log
            
            // Update visual state immediately
            const dropdownItem = this.closest('.dropdown-item');
            if (this.checked) {
                dropdownItem.classList.add('active');
            } else {
                dropdownItem.classList.remove('active');
            }
            
            // Add loading state to the filter pill
            const filterPill = this.closest('.filter-pill');
            filterPill.classList.add('filter-loading');
            
            // Update filters immediately
            updateActiveFilters();
            updateFilterPillsVisual();
            
            // Apply filters with a small delay to ensure DOM updates
            setTimeout(() => {
                applyFiltersWithLoader().finally(() => {
                    filterPill.classList.remove('filter-loading');
                });
            }, 50);
        });
    });
    
    // Also handle label clicks to prevent dropdown closing
    document.querySelectorAll('.filter-dropdown label').forEach(label => {
        label.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent dropdown from closing
        });
    });
    
    // Handle custom date inputs with debounce
    let dateTimeout;
    document.getElementById('custom-from-date').addEventListener('change', function() {
        clearTimeout(dateTimeout);
        dateTimeout = setTimeout(() => {
            updateActiveFilters();
            updateFilterPillsVisual();
            applyFiltersWithLoader();
        }, 300);
    });
    
    document.getElementById('custom-to-date').addEventListener('change', function() {
        clearTimeout(dateTimeout);
        dateTimeout = setTimeout(() => {
            updateActiveFilters();
            updateFilterPillsVisual();
            applyFiltersWithLoader();
        }, 300);
    });
    
    // Clear filters button with loader
    document.getElementById('clearFilters').addEventListener('click', function() {
        this.classList.add('filter-loading');
        clearAllFilters().finally(() => {
            this.classList.remove('filter-loading');
        });
    });
}

function initializeSearch() {
    const searchBox = document.getElementById('searchBox');
    let searchTimeout;
    
    searchBox.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchContainer = this.closest('.search-container');
        
        searchTimeout = setTimeout(() => {
            searchContainer.classList.add('filter-loading');
            applyFiltersWithLoader().finally(() => {
                searchContainer.classList.remove('filter-loading');
            });
        }, 300);
    });
}

function updateActiveFilters() {
    console.log('Updating active filters...'); // Debug log
    activeFilters = {};
    
    // Get checked filters
    document.querySelectorAll('.filter-dropdown input[type="checkbox"]:checked').forEach(checkbox => {
        const filterType = checkbox.closest('.filter-dropdown').id.replace('-dropdown', '');
        if (!activeFilters[filterType]) {
            activeFilters[filterType] = [];
        }
        activeFilters[filterType].push(checkbox.value);
        console.log(`Added filter: ${filterType} = ${checkbox.value}`); // Debug log
    });
    
    // Handle custom date range
    const fromDate = document.getElementById('custom-from-date').value;
    const toDate = document.getElementById('custom-to-date').value;
    
    if (fromDate || toDate) {
        activeFilters['custom-date'] = [{
            from: fromDate,
            to: toDate
        }];
        console.log('Added custom date filter:', activeFilters['custom-date']); // Debug log
    }
    
    console.log('Final active filters:', activeFilters); // Debug log
}

function updateFilterPillsVisual() {
    // Update each filter pill to show active state and count
    document.querySelectorAll('.filter-pill').forEach(pill => {
        const filterType = pill.dataset.filter;
        const dropdown = pill.querySelector('.filter-dropdown');
        
        if (!dropdown) return;
        
        // Count active filters for this pill
        const activeCount = dropdown.querySelectorAll('input[type="checkbox"]:checked').length;
        
        // Handle custom date separately
        if (filterType === 'date') {
            const fromDate = document.getElementById('custom-from-date').value;
            const toDate = document.getElementById('custom-to-date').value;
            const customDateActive = fromDate || toDate;
            const totalActive = activeCount + (customDateActive ? 1 : 0);
            
            if (totalActive > 0) {
                pill.classList.add('has-active-filters');
                pill.setAttribute('data-count', totalActive);
            } else {
                pill.classList.remove('has-active-filters');
                pill.removeAttribute('data-count');
            }
        } else {
            // For other filters
            if (activeCount > 0) {
                pill.classList.add('has-active-filters');
                pill.setAttribute('data-count', activeCount);
            } else {
                pill.classList.remove('has-active-filters');
                pill.removeAttribute('data-count');
            }
        }
    });
}

function clearAllFilters() {
    return new Promise((resolve) => {
        // Uncheck all checkboxes and remove active states
        document.querySelectorAll('.filter-dropdown input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
            const dropdownItem = checkbox.closest('.dropdown-item');
            if (dropdownItem) {
                dropdownItem.classList.remove('active');
            }
        });
        
        // Clear date inputs
        document.getElementById('custom-from-date').value = '';
        document.getElementById('custom-to-date').value = '';
        
        // Clear search
        document.getElementById('searchBox').value = '';
        
        // Reset filters
        activeFilters = {};
        updateFilterPillsVisual();
        
        // Reload data
        fetchPayments(1).then(resolve);
    });
}

function applyFilters() {
    return fetchPayments(1);
}

function applyFiltersWithLoader() {
    return fetchPayments(1);
}

function showTableLoader() {
    const tbody = document.getElementById('paymentsTbody');
    const paginationContainer = document.getElementById('paginationContainer');
    
    tbody.innerHTML = `
        <tr>
            <td colspan="10" class="table-loader">
                <div class="spinner"></div>
                Loading payments...
            </td>
        </tr>
    `;
    
    paginationContainer.style.opacity = '0.5';
    paginationContainer.style.pointerEvents = 'none';
}

function fetchPayments(page = 1) {
    return new Promise((resolve, reject) => {
        currentPage = page;
        showTableLoader();
        
        const searchTerm = document.getElementById('searchBox').value.toLowerCase().trim();
        
        let url = '/payments/ajax';
        let params = [];
        params.push('page=' + page);
        params.push('per_page=5');
        
        if (searchTerm) {
            params.push('search=' + encodeURIComponent(searchTerm));
            console.log('Added search term:', searchTerm); // Debug log
        }
        
        if (typeof activeFilters !== 'undefined') {
            Object.keys(activeFilters).forEach(filterType => {
                if (activeFilters[filterType] && activeFilters[filterType].length > 0) {
                    if (filterType === 'custom-date') {
                        const dateRange = activeFilters[filterType][0];
                        if (dateRange.from) {
                            params.push('from_date=' + encodeURIComponent(dateRange.from));
                            console.log('Added from_date:', dateRange.from); // Debug log
                        }
                        if (dateRange.to) {
                            params.push('to_date=' + encodeURIComponent(dateRange.to));
                            console.log('Added to_date:', dateRange.to); // Debug log
                        }
                    } else {
                        activeFilters[filterType].forEach(value => {
                            params.push(`${filterType}[]=${encodeURIComponent(value)}`);
                            console.log(`Added filter param: ${filterType}[] = ${value}`); // Debug log
                        });
                    }
                }
            });
        }
        
        if (params.length > 0) url += '?' + params.join('&');
        
        console.log('Final AJAX URL:', url); // Debug log
        
        fetch(url)
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                paginationData = data;
                renderPayments(data.data);
                renderPagination(data);
                resolve(data);
            })
            .catch(error => {
                console.error('Error fetching payments:', error);
                const tbody = document.getElementById('paymentsTbody');
                const paginationContainer = document.getElementById('paginationContainer');
                
                tbody.innerHTML = '<tr><td colspan="10" style="text-align:center;color:#ef4444;padding:2rem;"><i class="fa fa-exclamation-triangle" style="margin-right:0.5rem;"></i>Error loading payments. Please try again.</td></tr>';
                
                paginationContainer.style.opacity = '1';
                paginationContainer.style.pointerEvents = 'auto';
                
                document.getElementById('paginationControls').innerHTML = '';
                document.getElementById('paginationInfo').textContent = '';
                
                reject(error);
            });
    });
}

function renderPayments(payments) {
    const tbody = document.getElementById('paymentsTbody');
    tbody.innerHTML = '';
    
    if (!payments.length) {
        tbody.innerHTML = '<tr><td colspan="10" style="text-align:center;color:#6b7280;padding:2rem;">No payments found.</td></tr>';
        return;
    }
    
    payments.forEach(function(payment) {
        const amount = `$${parseFloat(payment.amount || 0).toFixed(2)}`;
        const currency = payment.currency || 'USD';
        const paymentMethod = getPaymentMethodDisplay(payment.payment_method);
        const gateway = getGatewayDisplay(payment.provider);
        const description = payment.event_type ? 
            (payment.event_type.toLowerCase().includes('subscription') ? 'Subscription update' : 'Subscription creation') : 
            '—';
        const customer = payment.customer_email || payment.customer_id || '—';
        const date = payment.received_at ? 
            new Date(payment.received_at).toLocaleString('en-US', { 
                day: '2-digit', 
                month: 'short', 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: true 
            }) : '—';
        const status = getStatusBadge(payment.status);
        const refundedDate = payment.status && payment.status.toLowerCase() === 'refunded' ? date : '—';
        const declineReason = payment.decline_reason || '—';
        
        tbody.innerHTML += `
            <tr>
                <td><input type="checkbox" class="row-checkbox"></td>
                <td>
                    <div class="amount-status-cell">
                        <div class="amount-row">
                            <span class="amount">${amount}</span>
                            <span class="currency">${currency}</span>
                        </div>
                        <div class="status-row">
                            ${status}
                        </div>
                    </div>
                </td>
                <td>${paymentMethod}</td>
                <td>${gateway}</td>
                <td>${description}</td>
                <td class="customer-email">${customer}</td>
                <td class="date">${date}</td>
                <td class="date">${refundedDate}</td>
                <td class="date">${declineReason}</td>
                <td>
                    <button class="action-btn">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}

function getPaymentMethodDisplay(method) {
    if (!method) return '—';
    
    let brand = '', last4 = '';
    
    if (method.includes(':')) {
        [brand, last4] = method.split(':');
        brand = brand.trim().toLowerCase();
        last4 = last4.trim();
    } else {
        brand = method.trim().toLowerCase();
    }
    
    let icon = '';
    let label = '';
    
    switch (brand) {
        case 'visa':
            icon = '<div class="payment-icon visa">VISA</div>';
            label = last4 ? `•••• ${last4}` : 'Visa';
            break;
        case 'mastercard':
            icon = '<div class="payment-icon mastercard">MC</div>';
            label = last4 ? `•••• ${last4}` : 'Mastercard';
            break;
        case 'paypal':
            icon = '<div class="payment-icon paypal"><i class="fa-brands fa-paypal"></i></div>';
            label = 'PayPal';
            break;
        default:
            icon = '<i class="fa fa-credit-card" style="color:#6b7280;"></i>';
            label = brand.charAt(0).toUpperCase() + brand.slice(1);
    }
    
    return `<div class="payment-method">${icon} <span>${label}</span></div>`;
}

function getGatewayDisplay(gateway) {
    if (!gateway) return '—';
    
    let icon = '';
    let label = gateway.charAt(0).toUpperCase() + gateway.slice(1);
    
    switch (gateway.toLowerCase()) {
        case 'stripe':
            icon = '<i class="fa-brands fa-stripe" style="color:#635bff;"></i>';
            label = 'Stripe';
            break;
        case 'paypal':
            icon = '<i class="fa-brands fa-paypal" style="color:#0070ba;"></i>';
            label = 'PayPal';
            break;
        case 'square':
            icon = '<i class="fas fa-square" style="color:#3e4348;"></i>';
            label = 'Square';
            break;
        default:
            icon = '<i class="fa fa-money" style="color:#28a745;"></i>';
    }
    
    return `<div class="payment-method">${icon} <span>${label}</span></div>`;
}

function getStatusBadge(status) {
    if (!status) return '—';
    
    // Map database status values to display values
    let displayStatus = status;
    let statusClass = '';
    
    switch (status.toLowerCase()) {
        case 'completed':
            displayStatus = 'Succeeded';
            statusClass = 'status-succeeded';
            break;
        case 'succeeded':
            displayStatus = 'Succeeded';
            statusClass = 'status-succeeded';
            break;
        case 'failed':
            displayStatus = 'Failed';
            statusClass = 'status-failed';
            break;
        case 'pending':
            displayStatus = 'Pending';
            statusClass = 'status-pending';
            break;
        case 'refunded':
            displayStatus = 'Refunded';
            statusClass = 'status-refunded';
            break;
        case 'cancelled':
            displayStatus = 'Cancelled';
            statusClass = 'status-cancelled';
            break;
        default:
            displayStatus = status.charAt(0).toUpperCase() + status.slice(1);
            statusClass = `status-${status.toLowerCase()}`;
    }
    
    return `<span class="status-badge ${statusClass}">${displayStatus}</span>`;
}

function renderPagination(data) {
    const paginationInfo = document.getElementById('paginationInfo');
    const paginationControls = document.getElementById('paginationControls');
    const paginationContainer = document.getElementById('paginationContainer');
    
    paginationContainer.style.opacity = '1';
    paginationContainer.style.pointerEvents = 'auto';
    
    if (data.total > 0) {
        paginationInfo.innerHTML = `
            <span>Showing <strong>${data.from}</strong> to <strong>${data.to}</strong> of <strong>${data.total}</strong> entries</span>
        `;
    } else {
        paginationInfo.textContent = 'No entries found';
    }
    
    paginationControls.innerHTML = '';
    
    if (data.last_page <= 1) return;
    
    // First button
    const firstBtn = document.createElement('button');
    firstBtn.className = 'pagination-btn';
    firstBtn.innerHTML = '<i class="fa fa-angle-double-left"></i>';
    firstBtn.disabled = data.current_page === 1;
    firstBtn.title = 'First page';
    firstBtn.addEventListener('click', () => {
        firstBtn.disabled = true;
        fetchPayments(1).finally(() => {
            firstBtn.disabled = data.current_page === 1;
        });
    });
    paginationControls.appendChild(firstBtn);
    
    // Previous button
    const prevBtn = document.createElement('button');
    prevBtn.className = 'pagination-btn';
    prevBtn.innerHTML = '<i class="fa fa-chevron-left"></i>';
    prevBtn.disabled = data.current_page === 1;
    prevBtn.title = 'Previous page';
    prevBtn.addEventListener('click', () => {
        prevBtn.disabled = true;
        fetchPayments(data.current_page - 1).finally(() => {
            prevBtn.disabled = data.current_page === 1;
        });
    });
    paginationControls.appendChild(prevBtn);
    
    // Page numbers with better range
    const maxVisiblePages = 5;
    let startPage, endPage;
    
    if (data.last_page <= maxVisiblePages) {
        startPage = 1;
        endPage = data.last_page;
    } else {
        const maxPagesBeforeCurrentPage = Math.floor(maxVisiblePages / 2);
        const maxPagesAfterCurrentPage = Math.ceil(maxVisiblePages / 2) - 1;
        
        if (data.current_page <= maxPagesBeforeCurrentPage) {
            startPage = 1;
            endPage = maxVisiblePages;
        } else if (data.current_page + maxPagesAfterCurrentPage >= data.last_page) {
            startPage = data.last_page - maxVisiblePages + 1;
            endPage = data.last_page;
        } else {
            startPage = data.current_page - maxPagesBeforeCurrentPage;
            endPage = data.current_page + maxPagesAfterCurrentPage;
        }
    }
    
    // Add ellipsis before if needed
    if (startPage > 1) {
        const firstPageBtn = document.createElement('button');
        firstPageBtn.className = 'pagination-btn';
        firstPageBtn.textContent = '1';
        firstPageBtn.addEventListener('click', () => fetchPayments(1));
        paginationControls.appendChild(firstPageBtn);
        
        if (startPage > 2) {
            const dots = document.createElement('span');
            dots.className = 'pagination-ellipsis';
            dots.textContent = '...';
            paginationControls.appendChild(dots);
        }
    }
    
    // Page number buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `pagination-btn ${i === data.current_page ? 'active' : ''}`;
        pageBtn.textContent = i;
        pageBtn.addEventListener('click', () => {
            if (i !== data.current_page) {
                pageBtn.disabled = true;
                fetchPayments(i).finally(() => {
                    pageBtn.disabled = false;
                });
            }
        });
        paginationControls.appendChild(pageBtn);
    }
    
    // Add ellipsis after if needed
    if (endPage < data.last_page) {
        if (endPage < data.last_page - 1) {
            const dots = document.createElement('span');
            dots.className = 'pagination-ellipsis';
            dots.textContent = '...';
            paginationControls.appendChild(dots);
        }
        
        const lastPageBtn = document.createElement('button');
        lastPageBtn.className = 'pagination-btn';
        lastPageBtn.textContent = data.last_page;
        lastPageBtn.addEventListener('click', () => fetchPayments(data.last_page));
        paginationControls.appendChild(lastPageBtn);
    }
    
    // Next button
    const nextBtn = document.createElement('button');
    nextBtn.className = 'pagination-btn';
    nextBtn.innerHTML = '<i class="fa fa-chevron-right"></i>';
    nextBtn.disabled = data.current_page === data.last_page;
    nextBtn.title = 'Next page';
    nextBtn.addEventListener('click', () => {
        nextBtn.disabled = true;
        fetchPayments(data.current_page + 1).finally(() => {
            nextBtn.disabled = data.current_page === data.last_page;
        });
    });
    paginationControls.appendChild(nextBtn);
    
    // Last button
    const lastBtn = document.createElement('button');
    lastBtn.className = 'pagination-btn';
    lastBtn.innerHTML = '<i class="fa fa-angle-double-right"></i>';
    lastBtn.disabled = data.current_page === data.last_page;
    lastBtn.title = 'Last page';
    lastBtn.addEventListener('click', () => {
        lastBtn.disabled = true;
        fetchPayments(data.last_page).finally(() => {
            lastBtn.disabled = data.current_page === data.last_page;
        });
    });
    paginationControls.appendChild(lastBtn);
}
</script>
@endsection