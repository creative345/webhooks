@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #fff; color: #111; line-height: 1.6; }
    .layout { display: flex; flex-direction: row; min-height: 100vh; width: 100%; background: #f8fafc; }
    .sidebar { width: 240px; min-width: 200px; max-width: 280px; background: #111; color: #fff; flex-shrink: 0; height: 100vh; position: sticky; top: 0; left: 0; z-index: 10; display: flex; flex-direction: column; justify-content: space-between; transition: width 0.2s; }
    .sidebar-logo { display: flex; align-items: center; gap: 0.7rem; font-size: 1.4rem; font-weight: 700; padding: 2rem 1.5rem 1.2rem 1.5rem; width: 100%; }
    .sidebar-title { font-size: 1.1rem; font-weight: 700; letter-spacing: 1px; }
    .sidebar-links { list-style: none; padding: 0; margin: 0; width: 100%; flex: 1; }
    .sidebar-links li { width: 100%; }
    .sidebar-links a { display: flex; align-items: center; gap: 0.8rem; color: #fff; text-decoration: none; font-size: 1.08rem; font-weight: 500; padding: 1rem 1.5rem; border-left: 4px solid transparent; transition: background 0.15s, border-color 0.15s, color 0.15s; }
    .sidebar-links a.active, .sidebar-links a:hover { background: #222; border-left: 4px solid #fff; color: #fff; }
    .sidebar-logout { margin-top: auto; width: 100%; }
    .sidebar-toggle { display: none; }
    .main-area { flex: 1 1 0%; display: flex; flex-direction: column; min-width: 0; background: #f8fafc; }
    .dashboard-content { flex: 1 1 0%; padding: 2.5rem 2.5rem 2rem 2.5rem; width: 100%; margin: 0 auto; background: #fff; border-radius: 18px; box-shadow: 0 4px 24px 0 rgba(0,0,0,0.04); }
    .payments-card { background: #fff; border-radius: 18px; box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08); padding: 0 0 2rem 0; margin-bottom: 2.5rem; max-width: 100%; }
    .payments-card-header { display: flex; align-items: center; gap: 0.7rem; font-size: 1.3rem; font-weight: 700; padding: 2rem 2rem 0.5rem 2rem; }
    .payments-card-title { font-size: 1.2rem; font-weight: 700; letter-spacing: 1px; }
    .payments-table-wrapper {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,0.06);
        margin-top: 0;
        border: 1px solid #e5e5e5;
    }
    .payments-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 1.05rem; background: #fff; border-radius: 16px; overflow: hidden; margin-bottom: 0; }
    .payments-table th { position: sticky; top: 0; z-index: 2; background: #f5f5f5; color: #111; font-weight: 800; font-size: 1.08rem; border-bottom: 2px solid #e5e5e5; padding: 1.1rem 0.9rem; letter-spacing: 0.5px; text-align: left; border-top: 1px solid #e5e5e5; }
    .payments-table td { padding: 1.1rem 0.9rem; border-bottom: 1px solid #f1f1f1; background: #fff; vertical-align: middle; }
    .payments-table tbody tr:nth-child(even) td { background: #fafafa; }
    .payments-table tbody tr:hover td { background: #f5f5f5; transition: background 0.18s; }
    .status-badge { padding: 0.45rem 1.1rem; border-radius: 999px; font-size: 0.97rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; color: #fff; background: #111; box-shadow: 0 1px 4px 0 rgba(0,0,0,0.08); border: none; }
    .status-paid { background: #111; color: #fff; }
    .status-due, .status-overdue { background: #fff; color: #111; border: 1.5px solid #111; }
    .status-unpaid { background: #222; color: #fff; }
    .status-upcoming, .status-pending { background: #e5e5e5; color: #111; }
    .payments-table td .status-badge { margin-right: 0.3rem; }
    .payments-table td:last-child { min-width: 120px; }
    .payments-table td button.status-badge { margin-right: 0.3rem; margin-bottom: 0.2rem; cursor: pointer; border: none; outline: none; transition: background 0.15s, color 0.15s; }
    .payments-table td button.status-badge:hover { background: #222; color: #fff; }
    @media (max-width: 900px) { .dashboard-content, .payments-table-wrapper { padding: 0.5rem; } .payments-card-header { padding: 1rem 1rem 0.5rem 1rem; } }
    @media (max-width: 700px) { 
        .payments-table th, .payments-table td { padding: 0.7rem 0.4rem; font-size: 0.95rem; } 
        .payments-table-wrapper { padding: 0 0.2rem; }
        #searchBox { max-width: 100%; }
        .payments-card > div:first-of-type { flex-direction: column; align-items: stretch !important; gap: 1rem !important; }
        .payments-card > div:first-of-type > div { flex-direction: column; align-items: stretch !important; }
        #clearFilters { margin-top: 1rem; align-self: flex-start; }
        .nav-tabs .nav-link { padding: 0.7rem 1rem !important; font-size: 0.95rem !important; }
        .nav-tabs { overflow-x: auto; flex-wrap: nowrap; }
        .nav-tabs .nav-item { flex-shrink: 0; }
        #paginationContainer { flex-direction: column !important; align-items: center !important; gap: 1rem !important; padding: 1rem !important; }
        #paginationInfo { order: 2; }
        #paginationControls { order: 1; justify-content: center !important; flex-wrap: nowrap !important; overflow-x: auto; max-width: 100%; }
        .pagination-btn { padding: 0.4rem 0.7rem; font-size: 0.85rem; min-width: 35px; }
    }
    /* Filter styling */
    #gatewayFilter:hover, #searchBox:hover { border-color: #111; }
    #gatewayFilter:focus, #searchBox:focus { border-color: #111; box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.1); }
    
    /* Clear button styling */
    #clearFilters:hover { background: #e9ecef; border-color: #111; color: #111; }
    #clearFilters:active { transform: translateY(1px); }
    
    /* Tab styling */
    .nav-tabs { 
        border-bottom: 2px solid #e5e5e5 !important; 
        display: flex !important; 
        flex-direction: row !important; 
        flex-wrap: nowrap !important; 
        list-style: none !important; 
        margin: 0 !important; 
        padding: 0 !important; 
    }
    .nav-tabs .nav-item { 
        display: inline-block !important; 
        margin: 0 !important; 
    }
    .nav-tabs .nav-link { 
        border: none !important; 
        border-bottom: 3px solid transparent !important; 
        background: none !important; 
        display: inline-block !important; 
        white-space: nowrap !important; 
    }
    .nav-tabs .nav-link.active { 
        color: #111 !important; 
        border-bottom-color: #111 !important; 
        background: none !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    .nav-tabs .nav-link:hover { 
        color: #111 !important; 
        border-bottom-color: #ccc !important; 
    }
    
    /* Pagination styling */
    #paginationContainer {
        width: 100% !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        flex-wrap: nowrap !important;
        transition: opacity 0.3s ease !important;
    }
    #paginationControls {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        overflow-x: auto !important;
        max-width: 100% !important;
        scroll-behavior: smooth !important;
    }
    #paginationControls::-webkit-scrollbar {
        height: 4px;
    }
    #paginationControls::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }
    #paginationControls::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 2px;
    }
    #paginationControls::-webkit-scrollbar-thumb:hover {
        background: #999;
    }
    
    /* Loading spinner styles */
    .table-loader {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 3rem 1rem;
        text-align: center;
    }
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #e5e5e5;
        border-top: 4px solid #111;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .loading-text {
        color: #666;
        font-size: 1rem;
        font-weight: 500;
    }
    .pagination-btn {
        padding: 0.5rem 1rem;
        border: 1px solid #e5e5e5;
        background: #fff;
        color: #666;
        cursor: pointer;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
        outline: none;
        white-space: nowrap;
        flex-shrink: 0;
        min-width: 40px;
    }
    .pagination-btn:hover {
        background: #f8f9fa;
        border-color: #111;
        color: #111;
    }
    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .pagination-btn:disabled:hover {
        background: #fff;
        border-color: #e5e5e5;
        color: #666;
    }
    .pagination-btn.active {
        background: #111;
        color: #fff;
        border-color: #111;
    }
    .pagination-btn.active:hover {
        background: #222;
        border-color: #222;
    }
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 2px 12px 0 rgba(30, 41, 59, 0.06);
        padding: 1.1rem 1.5rem;
        margin-bottom: 2rem;
        flex-wrap: nowrap;
        scrollbar-width: thin;
    }
    .search-group {
        position: relative;
        flex: 1 1 260px;
        min-width: 220px;
        max-width: 350px;
    }
    .search-input {
        width: 100%;
        padding: 0.7rem 1.2rem 0.7rem 2.5rem;
        border-radius: 999px;
        border: 2px solid #e5e5e5;
        font-size: 1.05rem;
        background: #fafbfc;
        color: #111;
        outline: none;
        transition: border-color 0.2s;
        box-shadow: none;
    }
    .search-input:focus {
        border-color: #6366f1;
        background: #fff;
    }
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        font-size: 1.1rem;
        pointer-events: none;
    }
    .gateway-tabs-wrapper {
        flex: 1 1 320px;
        min-width: 220px;
        overflow-x: auto;
    }
    .gateway-tabs {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: nowrap;
        overflow-x: auto;
    }
    .gateway-tab {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.3rem;
        border-radius: 999px;
        border: none;
        background: #f3f4f6;
        color: #444;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.18s, color 0.18s;
        outline: none;
        box-shadow: none;
        white-space: nowrap;
    }
    .gateway-tab.active, .gateway-tab:focus {
        background: #111;
        color: #fff;
    }
    .gateway-tab:hover {
        background: #e5e5e5;
        color: #111;
    }

    .clear-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.5rem;
        border-radius: 999px;
        border: 2px solid #e5e5e5;
        background: #f8f9fa;
        color: #666;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        outline: none;
        box-shadow: none;
        margin-left: auto;
    }
    .clear-btn:hover {
        background: #e5e5e5;
        color: #111;
        border-color: #111;
    }
    @media (max-width: 900px) {
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
            padding: 1rem 0.7rem;
            flex-wrap: wrap;
            overflow-x: auto;
        }
        .gateway-tabs-wrapper, .search-group, .status-filter-group {
            min-width: 0;
            max-width: 100%;
            flex: 1 1 100%;
        }
        .status-pills-container {
            justify-content: flex-start;
            overflow-x: auto;
            flex-wrap: nowrap;
            scrollbar-width: thin;
            scrollbar-color: #ccc #f1f1f1;
        }
        .status-pills-container::-webkit-scrollbar {
            height: 4px;
        }
        .status-pills-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }
        .status-pills-container::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 2px;
        }
        .clear-btn {
            width: 100%;
            margin-left: 0;
        }
    }
    .filter-bar, .search-group, .gateway-tabs-wrapper, .gateway-tab {
        position: relative;
        z-index: 1;
    }
    
    /* Status Filter Pills Styling */
    .status-filter-group {
        flex: 1 1 100%;
        min-width: 300px;
        margin-top: 1rem;
    }
    .status-filter-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        font-size: 1rem;
        color: #444;
        margin-bottom: 0.8rem;
    }
    .status-filter-label i {
        color: #666;
    }
    .status-pills-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.7rem;
        align-items: center;
    }
    .status-pill {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        border: 2px solid #e5e5e5;
        background: #fff;
        color: #666;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s ease;
        outline: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        white-space: nowrap;
        position: relative;
    }
    .status-pill:hover {
        background: #f8f9fa;
        border-color: #111;
        color: #111;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .status-pill.active {
        background: #111;
        color: #fff;
        border-color: #111;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .status-pill.active:hover {
        background: #222;
        border-color: #222;
        color: #fff;
    }
    .status-pill i {
        font-size: 0.9rem;
        opacity: 0.8;
    }
    .status-pill.active i {
        opacity: 1;
    }
    
    /* Status specific colors */
    .status-pill[data-status="paid"].active {
        background: linear-gradient(135deg, #10b981, #059669);
        border-color: #10b981;
    }
    .status-pill[data-status="pending"].active {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-color: #f59e0b;
    }
    .status-pill[data-status="due"].active {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-color: #ef4444;
    }
    .status-pill[data-status="unpaid"].active {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        border-color: #8b5cf6;
    }
    .status-pill[data-status="failed"].active {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        border-color: #dc2626;
    }
    .status-pill[data-status="upcoming"].active {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border-color: #3b82f6;
    }
</style>

<div class="layout">
    <div class="sidebar" id="sidebarNav">
        <div class="sidebar-logo">
            <img src="/CR-LOGO.png" alt="CRM Logo" class="logo-img" width="38" height="38" />
            <span class="sidebar-title">Payment CRM</span>
        </div>
        <ul class="sidebar-links">
            <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="#"><i class="fa fa-table"></i> Payments</a></li>
            <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
            <li class="sidebar-logout"><a href="#"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
        <button class="sidebar-toggle" id="sidebarToggle"><i class="fa fa-bars"></i></button>
    </div>
    <div class="main-area">

        <div class="dashboard-content">
            

            <div class="payments-card">
                <div class="payments-card-header">
                    <i class="fa fa-table"></i>
                    <span class="payments-card-title">All Payments</span>
                </div>
                <div style="padding: 2rem 2rem 1rem 2rem;">
                    <!-- Unified Search & Filter Bar -->
                    <div class="filter-bar">
                        <div class="search-group">
                            <span class="search-icon"><i class="fa fa-search"></i></span>
                            <input type="text" id="searchBox" class="search-input" placeholder="Search payments, customer, amount, status...">
                        </div>
                        <div class="gateway-tabs-wrapper">
                            <ul class="gateway-tabs" id="gatewayTabs" role="tablist">
                                <li><button class="gateway-tab active" id="all-tab" data-gateway="" type="button"><i class="fa fa-table"></i> All</button></li>
                                <li><button class="gateway-tab" id="stripe-tab" data-gateway="stripe" type="button"><i class="fa-brands fa-stripe"></i> Stripe</button></li>
                                <li><button class="gateway-tab" id="paypal-tab" data-gateway="paypal" type="button"><i class="fa-brands fa-paypal"></i> PayPal</button></li>
                                <li><button class="gateway-tab" id="square-tab" data-gateway="square" type="button"><i class="fas fa-square"></i> Square</button></li>
                            </ul>
                        </div>

                        
                        <button id="clearFilters" class="clear-btn"><i class="fa fa-times"></i> Clear All</button>
                    </div>
                    <div class="status-filter-group">
                            <div class="status-filter-label">
                                <i class="fa fa-filter"></i>
                                <span>Status Filter:</span>
                            </div>
                            <div class="status-pills-container">
                                <button class="status-pill active" data-status="" type="button">
                                    <i class="fa fa-list"></i>
                                    All Status
                                </button>
                                <button class="status-pill" data-status="paidp" type="button">
                                    <i class="fa fa-check-circle"></i>
                                    Paidp
                                </button>
                                <button class="status-pill" data-status="pending" type="button">
                                    <i class="fa fa-clock"></i>
                                    Pending
                                </button>
                                <button class="status-pill" data-status="due" type="button">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    Due
                                </button>
                                <button class="status-pill" data-status="unpaid" type="button">
                                    <i class="fa fa-times-circle"></i>
                                    Unpaid
                                </button>
                                <button class="status-pill" data-status="failed" type="button">
                                    <i class="fa fa-ban"></i>
                                    Failed
                                </button>
                                <button class="status-pill" data-status="upcoming" type="button">
                                    <i class="fa fa-calendar"></i>
                                    Upcoming
                                </button>
                            </div>
                        </div>
                    <!-- Filter Count -->
                    <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                        <span id="filterCount" style="font-size: 0.95rem; color: #666; font-weight: 500; margin-left: auto;"></span>
                    </div>
                </div>
                <div class="payments-table-wrapper">
                    <table class="payments-table" id="paymentsTable">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Gateway</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="paymentsTbody">
                            <!-- Payments will be loaded here by AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls -->
                <div id="paginationContainer" style="padding: 1.5rem 2rem; border-top: 1px solid #e5e5e5; display: flex; justify-content: space-between; align-items: center; width: 100%; min-height: 60px;">
                    <div id="paginationInfo" style="color: #666; font-size: 0.95rem; font-weight: 500; flex-shrink: 0;"></div>
                    <div id="paginationControls" style="display: flex; gap: 0.5rem; align-items: center; justify-content: center; flex: 1; max-width: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTableLoader() {
    const tbody = document.getElementById('paymentsTbody');
    const filterCount = document.getElementById('filterCount');
    const paginationContainer = document.getElementById('paginationContainer');
    
    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="table-loader">
                <div class="spinner"></div>
                <div class="loading-text">Loading payments...</div>
            </td>
        </tr>
    `;
    filterCount.textContent = '';
    
    // Hide pagination during loading
    paginationContainer.style.opacity = '0.5';
    paginationContainer.style.pointerEvents = 'none';
}

function renderPayments(payments) {
    const tbody = document.getElementById('paymentsTbody');
    const filterCount = document.getElementById('filterCount');
    tbody.innerHTML = '';
    
    if (!payments.length) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#888;font-weight:600;">No payments found.</td></tr>';
        filterCount.textContent = '(0 payments)';
        return;
    }
    
    function getGatewayIcon(gateway) {
        switch(gateway.toLowerCase()) {
            case 'stripe': return '<i class="fa-brands fa-stripe" style="color: #635bff;"></i>';
            case 'paypal': return '<i class="fa-brands fa-paypal" style="color: #0070ba;"></i>';
            case 'square': return '<i class="fas fa-square" style="color: #3e4348;"></i>';
            default: return '<i class="fa fa-money" style="color: #28a745;"></i>';
        }
    }
    
    payments.forEach(function(p) {
        let badgeClass = 'status-badge status-' + (p.status === 'overdue' ? 'due' : p.status);
        let actions = `<button class="status-badge status-paid" title="View">View</button>`;
        let gatewayIcon = getGatewayIcon(p.gateway);
        tbody.innerHTML += `<tr>
            <td>${p.customer_name}</td>
            <td style="text-transform:capitalize;"><span style="margin-right:0.8rem; display:inline-block; width:16px;">${gatewayIcon}</span>${p.gateway}</td>
            <td>$${parseFloat(p.amount).toFixed(2)}</td>
            <td><span class="${badgeClass}">${p.status.charAt(0).toUpperCase() + p.status.slice(1)}</span></td>
            <td>${p.date}</td>
            <td>${actions}</td>
        </tr>`;
    });
    
    // Update filter count using pagination data
    if (paginationData && paginationData.total !== undefined) {
        filterCount.textContent = `(${paginationData.total} payment${paginationData.total !== 1 ? 's' : ''})`;
    } else {
        filterCount.textContent = `(${payments.length} payment${payments.length !== 1 ? 's' : ''})`;
    }
}
let currentPage = 1;
let currentFilters = {
    search: '',
    gateway: '',
    status: ''
};
let paginationData = {};

// Fetch payments with pagination and filters
function fetchPayments(page = 1) {
    currentPage = page;
    
    // Show loading state
    showTableLoader();
    
    // Get current filter values
    const searchTerm = document.getElementById('searchBox').value.toLowerCase().trim();
    const activeTab = document.querySelector('.gateway-tab.active');
    const gatewayFilter = activeTab ? activeTab.getAttribute('data-gateway').toLowerCase() : '';
    const activeStatusPill = document.querySelector('.status-pill.active');
    const statusFilter = activeStatusPill ? activeStatusPill.getAttribute('data-status').toLowerCase() : '';
    
    // Update current filters
    currentFilters = {
        search: searchTerm,
        gateway: gatewayFilter,
        status: statusFilter
    };
    
    // Build URL with parameters
    let url = '/payments/ajax';
    let params = [];
    params.push('page=' + page);
    params.push('per_page=5');
    if (searchTerm) params.push('search=' + encodeURIComponent(searchTerm));
    if (gatewayFilter) params.push('gateway=' + gatewayFilter);
    if (statusFilter) params.push('status=' + statusFilter);
    if (params.length > 0) url += '?' + params.join('&');
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            paginationData = data;
            renderPayments(data.data);
            renderPagination(data);
        })
        .catch(error => {
            console.error('Error fetching payments:', error);
            // Show error state
            const tbody = document.getElementById('paymentsTbody');
            const paginationContainer = document.getElementById('paginationContainer');
            const filterCount = document.getElementById('filterCount');
            
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#e74c3c;font-weight:600;padding:2rem;"><i class="fa fa-exclamation-triangle" style="margin-right:0.5rem;"></i>Error loading payments. Please try again.</td></tr>';
            
            // Restore pagination container
            paginationContainer.style.opacity = '1';
            paginationContainer.style.pointerEvents = 'auto';
            
            // Clear pagination controls and info
            document.getElementById('paginationControls').innerHTML = '';
            document.getElementById('paginationInfo').textContent = '';
            filterCount.textContent = '';
        });
}

// Render pagination controls
function renderPagination(data) {
    const paginationInfo = document.getElementById('paginationInfo');
    const paginationControls = document.getElementById('paginationControls');
    const paginationContainer = document.getElementById('paginationContainer');
    
    // Restore pagination container
    paginationContainer.style.opacity = '1';
    paginationContainer.style.pointerEvents = 'auto';
    
    // Update info
    if (data.total > 0) {
        paginationInfo.textContent = `Showing ${data.from} to ${data.to} of ${data.total} entries`;
    } else {
        paginationInfo.textContent = 'No entries found';
    }
    
    // Clear existing controls
    paginationControls.innerHTML = '';
    
    if (data.last_page <= 1) return; // No pagination needed
    
    // Previous button
    const prevBtn = document.createElement('button');
    prevBtn.className = 'pagination-btn';
    prevBtn.innerHTML = '<i class="fa fa-chevron-left"></i>';
    prevBtn.disabled = data.current_page === 1;
    prevBtn.onclick = () => fetchPayments(data.current_page - 1);
    paginationControls.appendChild(prevBtn);
    
    // Page number buttons
    for (let i = 1; i <= data.last_page; i++) {
        if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
            const pageBtn = document.createElement('button');
            pageBtn.className = 'pagination-btn' + (i === data.current_page ? ' active' : '');
            pageBtn.textContent = i;
            pageBtn.onclick = () => fetchPayments(i);
            paginationControls.appendChild(pageBtn);
        } else if (i === data.current_page - 2 || i === data.current_page + 2) {
            const dots = document.createElement('span');
            dots.textContent = '...';
            dots.style.padding = '0.5rem 0.25rem';
            dots.style.color = '#666';
            dots.style.display = 'inline-block';
            dots.style.whiteSpace = 'nowrap';
            dots.style.flexShrink = '0';
            paginationControls.appendChild(dots);
        }
    }
    
    // Next button
    const nextBtn = document.createElement('button');
    nextBtn.className = 'pagination-btn';
    nextBtn.innerHTML = '<i class="fa fa-chevron-right"></i>';
    nextBtn.disabled = data.current_page === data.last_page;
    nextBtn.onclick = () => fetchPayments(data.current_page + 1);
    paginationControls.appendChild(nextBtn);
}

// Apply filters and reset to page 1
function applyFilters() {
    fetchPayments(1);
}

// Tab filter event listeners
document.querySelectorAll('.gateway-tab').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        // Remove active class from all tabs
        document.querySelectorAll('.gateway-tab').forEach(t => {
            t.classList.remove('active');
            t.setAttribute('aria-selected', 'false');
        });
        // Add active class to clicked tab
        this.classList.add('active');
        this.setAttribute('aria-selected', 'true');
        // Apply filters
        applyFilters();
    });
});



// Status pill filter event listeners
document.querySelectorAll('.status-pill').forEach(pill => {
    pill.addEventListener('click', function(e) {
        e.preventDefault();
        // Remove active class from all status pills
        document.querySelectorAll('.status-pill').forEach(p => {
            p.classList.remove('active');
        });
        // Add active class to clicked pill
        this.classList.add('active');
        // Apply filters
        applyFilters();
    });
});

// Search box event listener with debounce for better performance
let searchTimeout;
document.getElementById('searchBox').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300); // 300ms debounce
});

// Clear all filters button
document.getElementById('clearFilters').addEventListener('click', function() {
    document.getElementById('searchBox').value = '';
    // Reset gateway tabs to "All"
    document.querySelectorAll('.gateway-tab').forEach(t => {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
    });
    document.getElementById('all-tab').classList.add('active');
    document.getElementById('all-tab').setAttribute('aria-selected', 'true');
    // Reset status pills to "All Status"
    document.querySelectorAll('.status-pill').forEach(p => {
        p.classList.remove('active');
    });
    document.querySelector('.status-pill[data-status=""]').classList.add('active');
    applyFilters();
});

document.addEventListener('DOMContentLoaded', function() {
    fetchPayments(1);
});

document.addEventListener('DOMContentLoaded', function() {
    // Payment Trends Chart
    const paymentCtx = document.getElementById('paymentChart');
    if (paymentCtx) {
        new Chart(paymentCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Stripe',
                    data: [800, 920, 1050, 980, 1100, 1045],
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.15)',
                    tension: 0.5,
                    fill: true,
                    pointBackgroundColor: '#6366f1',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 3
                }, {
                    label: 'PayPal',
                    data: [600, 750, 890, 1020, 1150, 1220],
                    borderColor: '#1e40af',
                    backgroundColor: 'rgba(30, 64, 175, 0.15)',
                    tension: 0.5,
                    fill: true,
                    pointBackgroundColor: '#1e40af',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 3
                }, {
                    label: 'Square',
                    data: [1200, 1350, 1500, 1800, 2100, 2235],
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.15)',
                    tension: 0.5,
                    fill: true,
                    pointBackgroundColor: '#059669',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 600 },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 18,
                            font: { size: 14, weight: 'bold' },
                            color: '#2d3a4a',
                            padding: 18
                        }
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#2d3a4a',
                        bodyColor: '#2d3a4a',
                        borderColor: '#e0e7ff',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 2500,
                        min: 0,
                        grid: { color: '#e0e7ff' },
                        ticks: {
                            callback: function(value) { return '$' + value; },
                            stepSize: 500,
                            color: '#64748b',
                            font: { size: 13 }
                        }
                    },
                    x: {
                        grid: { color: '#e0e7ff' },
                        ticks: {
                            maxTicksLimit: 6,
                            color: '#64748b',
                            font: { size: 13 }
                        }
                    }
                }
            }
        });
    }

    // Gateway Distribution Chart
    const gatewayCtx = document.getElementById('gatewayChart');
    if (gatewayCtx) {
        new Chart(gatewayCtx, {
            type: 'doughnut',
            data: {
                labels: ['Stripe', 'PayPal', 'Square'],
                datasets: [{
                    data: [1045, 1220, 2235],
                    backgroundColor: [
                        'rgba(99,102,241,0.85)',
                        'rgba(30,64,175,0.85)',
                        'rgba(5,150,105,0.85)'
                    ],
                    borderWidth: 6,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                animation: { duration: 600 },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 18,
                            font: { size: 14, weight: 'bold' },
                            color: '#2d3a4a',
                            padding: 18
                        }
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#2d3a4a',
                        bodyColor: '#2d3a4a',
                        borderColor: '#e0e7ff',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true
                    }
                }
            }
        });
    }

});
</script>
@endsection
