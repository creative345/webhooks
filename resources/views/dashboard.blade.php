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
        position: relative;
        overflow: visible;
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
        #paginationContainer { flex-direction: column !important; align-items: center !important; gap: 1rem !important; padding: 1rem !important; }
        #paginationInfo { order: 2; }
        #paginationControls { order: 1; justify-content: center !important; flex-wrap: nowrap !important; overflow-x: auto; max-width: 100%; }
        .pagination-btn { padding: 0.4rem 0.7rem; font-size: 0.85rem; min-width: 35px; }
    }
    /* Search box styling */
    #searchBox:hover { border-color: #111; }
    #searchBox:focus { border-color: #111; box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.1); }
    
    /* Clear button styling */
    #clearFilters:hover { background: #e9ecef; border-color: #111; color: #111; }
    #clearFilters:active { transform: translateY(1px); }
    
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

    /* Beautiful Filter Menu Styles */
    .filter-menu-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        position: relative;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }
    
    .filter-menu {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 0.8rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-menu-item {
        position: relative;
    }
    
    .filter-menu-btn {
        display: flex;
        align-items: center;
        gap: 0.4em;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        color: #475569;
        border-radius: 50px;
        padding: 0.8em 1.5em;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
        position: relative;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        backdrop-filter: blur(10px);
    }
    
    .filter-menu-btn:hover {
        border-color: #6366f1;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.25);
        transform: translateY(-1px);
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
    }
    
    .filter-menu-btn.active {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-color: #6366f1;
        color: #ffffff;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
        transform: translateY(-2px);
    }
    
    .filter-menu-btn .fa-chevron-down {
        margin-left: 0.4em;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.8em;
        opacity: 0.7;
    }
    
    .filter-menu-btn:hover .fa-chevron-down {
        opacity: 1;
    }
    
    .filter-menu-btn.active .fa-chevron-down {
        transform: rotate(180deg);
        opacity: 1;
    }
    
    .filter-submenu {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        min-width: 280px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.06);
        z-index: 9999;
        padding: 1.2rem;
        display: none;
        list-style: none;
        margin: 0;
        max-height: 350px;
        overflow-y: auto;
        backdrop-filter: blur(20px);
        animation: slideDown 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Beautiful scrollbar for submenus */
    .filter-submenu::-webkit-scrollbar {
        width: 6px;
    }
    
    .filter-submenu::-webkit-scrollbar-track {
        background: rgba(226, 232, 240, 0.5);
        border-radius: 3px;
    }
    
    .filter-submenu::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 3px;
    }
    
    .filter-submenu::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .filter-submenu.open {
        display: block;
    }
    
    .filter-submenu li {
        margin: 0;
        padding: 0;
    }
    
    .filter-submenu li:not(.apply-item) {
        margin-bottom: 0.5em;
    }
    
    .filter-submenu label {
        display: flex;
        align-items: center;
        gap: 0.8em;
        font-size: 0.95rem;
        padding: 0.6em 0.8em;
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: 8px;
        color: #475569;
        font-weight: 500;
    }
    
    .filter-submenu label:hover {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        transform: translateX(4px);
    }
    
    .filter-submenu input[type=checkbox] {
        accent-color: #6366f1;
        transform: scale(1.5);
        margin-right: 0.8em;
        border-radius: 6px;
        border: 2px solid #e2e8f0;
        width: 18px;
        height: 18px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .filter-submenu input[type=checkbox]:hover {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }
    
    .filter-submenu input[type=checkbox]:checked {
        border-color: #6366f1;
        background-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.3);
    }
    
    .filter-submenu input[type=checkbox]:checked + span {
        font-weight: 700;
    color: #6366f1;
    background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
    padding-left: 35px;
    border-radius: 6px;
    }
    
    .filter-actions {
        display: flex;
        align-items: center;
        gap: 1.2rem;
    }
    
    .table-actions {
        display: flex;
        gap: 0.8rem;
    }

    .apply-item {
        margin-top: 1rem !important;
        padding-top: 1rem;
        border-top: 1px solid rgba(226, 232, 240, 0.8);
    }
    
    .apply-btn {
        width: 100%;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 0.8em 1.2em;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .apply-btn:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        transform: translateY(-1px);
    }
    
    .apply-btn:active {
        transform: translateY(0);
    }
    .clear-filters-link {
        color: #6366f1;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
        border: 2px solid #6366f1;
        border-radius: 12px;
        padding: 0.7em 1.2em;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .clear-filters-link:hover {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
        transform: translateY(-1px);
    }
    
    .table-action-btn {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.7em 1.2em;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #475569;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table-action-btn:hover {
        border-color: #6366f1;
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
        color: #6366f1;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.2);
        transform: translateY(-1px);
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
                    <!-- Search Bar -->
                    <div style="display: flex; align-items: center; gap: 1.2rem; background: #fff; border-radius: 18px; box-shadow: 0 2px 12px 0 rgba(30, 41, 59, 0.06); padding: 1.1rem 1.5rem; margin-bottom: 2rem;">
                        <div class="search-group">
                            <span class="search-icon"><i class="fa fa-search"></i></span>
                            <input type="text" id="searchBox" class="search-input" placeholder="Search payments, customer, amount, status...">
                        </div>
                    </div>
                    <!-- Filter Count -->
                    
                </div>
                
                <div class="payments-table-wrapper">
                    <div class="filter-menu-container">
                        <ul class="filter-menu">
                            <li class="filter-menu-item">
                                <button class="filter-menu-btn" data-dropdown="date">
                                    Date and time
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="filter-submenu" id="dropdown-date">
                                    <li><label><input type="checkbox" value="today"><span>Today</span></label></li>
                                    <li><label><input type="checkbox" value="yesterday"><span>Yesterday</span></label></li>
                                    <li><label><input type="checkbox" value="last-7-days"><span>Last 7 days</span></label></li>
                                    <li><label><input type="checkbox" value="last-30-days"><span>Last 30 days</span></label></li>
                                    <li><label><input type="checkbox" value="last-90-days"><span>Last 90 days</span></label></li>
                                    <li class="apply-item"><button class="apply-btn" onclick="applyDropdownFilter('date')">Apply</button></li>
                                </ul>
                            </li>
                            
                            <!-- Custom Date Range Filter -->
                            <li class="filter-menu-item">
                                <button class="filter-menu-btn" data-dropdown="custom-date">
                                    Custom Date Range
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="filter-submenu" id="dropdown-custom-date">
                                    <li>
                                        <div style="margin-bottom: 1rem;">
                                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">From Date:</label>
                                            <input type="date" id="from-date" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.9rem;">
                                        </div>
                                    </li>
                                    <li>
                                        <div style="margin-bottom: 1rem;">
                                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">To Date:</label>
                                            <input type="date" id="to-date" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.9rem;">
                                        </div>
                                    </li>
                                    <li class="apply-item">
                                        <button class="apply-btn" onclick="applyCustomDateFilter()">Apply Date Range</button>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="filter-menu-item">
                                <button class="filter-menu-btn" data-dropdown="status">
                                    Status
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="filter-submenu" id="dropdown-status">
                                    <li><label><input type="checkbox" value="completed"><span>Completed</span></label></li>
                                    <li><label><input type="checkbox" value="failed"><span>Failed</span></label></li>
                                    <li><label><input type="checkbox" value="refunded"><span>Refunded</span></label></li>
                                    <li><label><input type="checkbox" value="pending"><span>Pending</span></label></li>
                                    <li class="apply-item"><button class="apply-btn" onclick="applyDropdownFilter('status')">Apply</button></li>
                                </ul>
                            </li>
                            
                            <li class="filter-menu-item">
                                <button class="filter-menu-btn" data-dropdown="payment">
                                    Payment method
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="filter-submenu" id="dropdown-payment">
                                    <li><label><input type="checkbox" value="card"><span>Card</span></label></li>
                                    <li><label><input type="checkbox" value="paypal"><span>PayPal</span></label></li>
                                    <li class="apply-item"><button class="apply-btn" onclick="applyDropdownFilter('payment')">Apply</button></li>
                                </ul>
                            </li>
                            
                            <li class="filter-menu-item">
                                <button class="filter-menu-btn" data-dropdown="more">
                                    More filters
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="filter-submenu" id="dropdown-more">
                                    <li><label><input type="checkbox" value="test"><span>Test payments</span></label></li>
                                    <li><label><input type="checkbox" value="live"><span>Live payments</span></label></li>
                                    <li><label><input type="checkbox" value="disputed"><span>Disputed</span></label></li>
                                    <li><label><input type="checkbox" value="refunded"><span>Refunded</span></label></li>
                                    <li class="apply-item"><button class="apply-btn" onclick="applyDropdownFilter('more')">Apply</button></li>
                                </ul>
                            </li>

                            <!-- ADD FILTER FOR PAYMENT GATEWAY -->
                            <li class="filter-menu-item">
                                <button class="filter-menu-btn" data-dropdown="gateway">
                                    Payment gateway
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="filter-submenu" id="dropdown-gateway">
                                    <li><label><input type="checkbox" value="stripe"><span>Stripe</span></label></li>
                                    <li><label><input type="checkbox" value="paypal"><span>PayPal</span></label></li>
                                    <li><label><input type="checkbox" value="square"><span>Square</span></label></li>
                                    <li class="apply-item"><button class="apply-btn" onclick="applyDropdownFilter('gateway')">Apply</button></li>
                                </ul>
                            </li>
                            


                        </ul>
                        
                        <div class="filter-actions">
                            <button id="clearFilters" class="clear-btn"><i class="fa fa-times"></i> Clear All</button>
                            
                        </div>
                    </div>
                    <table class="payments-table" id="paymentsTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllRows"></th>
                                <th>Amount</th>
                                <th>Payment method</th>
                                <th>Gateway</th>
                                <th>Description</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Refunded date</th>
                                <th>Status</th>
                                <th>Decline reason</th>
                                <th></th> {{-- Actions --}}
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
    const paginationContainer = document.getElementById('paginationContainer');
    
    tbody.innerHTML = `
        <tr>
            <td colspan="10" class="table-loader">
                <div class="spinner"></div>
                <div class="loading-text">Loading webhook payments...</div>
            </td>
        </tr>
    `;
    
    // Hide pagination during loading
    paginationContainer.style.opacity = '0.5';
    paginationContainer.style.pointerEvents = 'none';
}

// Function to view payment details
function viewPayment(eventId) {
    // You can implement a modal or redirect to a detail page
    console.log('Viewing payment with event ID:', eventId);
    alert('Viewing payment details for event: ' + eventId);
    // TODO: Implement payment detail view
}

// Render payments in Stripe-style data structure
function renderPayments(payments) {
    const tbody = document.getElementById('paymentsTbody');
    tbody.innerHTML = '';
    if (!payments.length) {
        tbody.innerHTML = '<tr><td colspan="10" style="text-align:center;color:#888;font-weight:600;">No payments found.</td></tr>';
        return;
    }
    function getStatusBadge(status) {
        let color = '#bbb', bg = '#f5f5f5', text = status;
        switch(status.toLowerCase()) {
            case 'succeeded': color = '#22c55e'; bg = '#e7fbe9'; text = 'Succeeded ✓'; break;
            case 'failed': color = '#ef4444'; bg = '#fde7e7'; text = 'Failed ✗'; break;
            case 'refunded': color = '#6366f1'; bg = '#e7e9fd'; text = 'Refunded'; break;
            case 'disputed': color = '#f59e42'; bg = '#fff7e6'; text = 'Disputed'; break;
            case 'cancelled': color = '#64748b'; bg = '#f1f5f9'; text = 'Cancelled'; break;
            case 'uncaptured': color = '#a3a3a3'; bg = '#f3f4f6'; text = 'Uncaptured'; break;
            default: color = '#bbb'; bg = '#f5f5f5'; text = status.charAt(0).toUpperCase() + status.slice(1);
        }
        return `<span style="display:inline-block;padding:0.3em 0.9em;border-radius:8px;font-size:0.95em;font-weight:600;color:${color};background:${bg};border:1px solid ${color};min-width:80px;text-align:center;">${text}</span>`;
    }
    function getPaymentMethodDisplay(method) {
        if (!method) return '—';
        let brand = '', last4 = '', icon = '', label = '';
        // Example formats: 'visa:5023', 'mastercard:1048', 'paypal', 'amex:1234', 'stripe', etc.
        if (method.includes(':')) {
            [brand, last4] = method.split(':');
            brand = brand.trim().toLowerCase();
            last4 = last4.trim();
        } else {
            brand = method.trim().toLowerCase();
        }
        switch (brand) {
            case 'visa':
                icon = '<i class="fa-brands fa-cc-visa" style="color:#2563eb;"></i>';
                label = 'Visa';
                break;
            case 'mastercard':
                icon = '<i class="fa-brands fa-cc-mastercard" style="color:#f59e42;"></i>';
                label = 'Mastercard';
                break;
            case 'amex':
            case 'american express':
                icon = '<i class="fa-brands fa-cc-amex" style="color:#2d6cdf;"></i>';
                label = 'Amex';
                break;
            case 'paypal':
                icon = '<i class="fa-brands fa-cc-paypal" style="color:#0070ba;"></i>';
                label = 'PayPal';
                break;
            case 'stripe':
                icon = '<i class="fa-brands fa-stripe" style="color:#635bff;"></i>';
                label = 'Stripe';
                break;
            case 'square':
                icon = '<i class="fa-brands fa-cc-discover" style="color:#3e4348;"></i>';
                label = 'Square';
                break;
            default:
                icon = '<i class="fa fa-credit-card" style="color:#64748b;"></i>';
                label = brand.charAt(0).toUpperCase() + brand.slice(1);
        }
        let last4Display = last4 ? `•••• ${last4}` : '';
        return `${icon} <span style='margin-left:0.3em;'>${last4Display || label}</span>`;
    }
    function getGatewayDisplay(provider) {
        if (!provider) return '—';
        let icon = '', label = provider.charAt(0).toUpperCase() + provider.slice(1);
        switch (provider.toLowerCase()) {
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
        return `${icon} <span style='margin-left:0.3em;'>${label}</span>`;
    }
    payments.forEach(function(p, idx) {
        // Placeholders for missing data
        let amount = `$${parseFloat(p.amount).toFixed(2)}`;
        let currency = p.currency || 'USD';
        let paymentMethod = p.payment_method || '—';
        let paymentMethodDisplay = getPaymentMethodDisplay(paymentMethod);
        let gatewayDisplay = getGatewayDisplay(p.provider);
        let description = p.event_type ? (p.event_type.toLowerCase().includes('subscription') ? 'Subscription update' : 'Subscription creation') : '—';
        let customer = p.customer_email || p.customer_id || '—';
        let date = p.received_at ? new Date(p.received_at).toLocaleString('en-US', { day:'2-digit', month:'short', hour:'2-digit', minute:'2-digit', hour12:true }) : '—';
        let refundedDate = p.status && p.status.toLowerCase() === 'refunded' ? date : '—';
        let statusBadge = getStatusBadge(p.status);
        let declineReason = p.decline_reason || '—'; // Placeholder for decline reason
        tbody.innerHTML += `<tr>
            <td><input type="checkbox" class="row-checkbox"></td>
            <td>${amount} <span style="color:#888;font-size:0.95em;">${currency}</span></td>
            <td>${paymentMethodDisplay}</td>
            <td>${gatewayDisplay}</td>
            <td>${description}</td>
            <td>${customer}</td>
            <td>${date}</td>
            <td>${refundedDate}</td>
            <td>${statusBadge}</td>
            <td>${declineReason}</td>
        </tr>`;
    });
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
    console.log('fetchPayments called with page:', page); // Debug log
    currentPage = page;
    
    // Show loading state
    showTableLoader();
    
    // Get current filter values
    const searchTerm = document.getElementById('searchBox').value.toLowerCase().trim();
    
    console.log('Current filters:', { searchTerm, activeFilters }); // Debug log
    
    // Build URL with parameters
    let url = '/payments/ajax';
    let params = [];
    params.push('page=' + page);
    params.push('per_page=5');
    
    // Only add search parameter if there's actually a search term
    if (searchTerm) {
        params.push('search=' + encodeURIComponent(searchTerm));
    }
    
    // Add dropdown filters only if they have values
    if (typeof activeFilters !== 'undefined') {
        Object.keys(activeFilters).forEach(filterType => {
            if (activeFilters[filterType] && activeFilters[filterType].length > 0) {
                console.log(`Adding ${filterType} filter:`, activeFilters[filterType]); // Debug log
                
                if (filterType === 'custom-date') {
                    // Handle custom date range
                    const dateRange = activeFilters[filterType][0];
                    if (dateRange.from) {
                        params.push(`custom_from_date=${encodeURIComponent(dateRange.from)}`);
                    }
                    if (dateRange.to) {
                        params.push(`custom_to_date=${encodeURIComponent(dateRange.to)}`);
                    }
                } else {
                    // Handle regular array filters
                    activeFilters[filterType].forEach(value => {
                        params.push(`${filterType}[]=${encodeURIComponent(value)}`);
                    });
                }
            }
        });
    }
    
    if (params.length > 0) url += '?' + params.join('&');
    
    console.log('Making AJAX request to:', url); // Debug log
    
    fetch(url)
        .then(res => {
            console.log('Response received:', res.status); // Debug log
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Data received:', data); // Debug log
            paginationData = data;
            renderPayments(data.data);
            renderPagination(data);
        })
        .catch(error => {
            console.error('Error fetching payments:', error);
            // Show error state
            const tbody = document.getElementById('paymentsTbody');
            const paginationContainer = document.getElementById('paginationContainer');
            
            tbody.innerHTML = '<tr><td colspan="10" style="text-align:center;color:#e74c3c;font-weight:600;padding:2rem;"><i class="fa fa-exclamation-triangle" style="margin-right:0.5rem;"></i>Error loading webhook payments. Please try again.</td></tr>';
            
            // Restore pagination container
            paginationContainer.style.opacity = '1';
            paginationContainer.style.pointerEvents = 'auto';
            
            // Clear pagination controls and info
            document.getElementById('paginationControls').innerHTML = '';
            document.getElementById('paginationInfo').textContent = '';
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
    console.log('Applying filters with dropdown filters:', activeFilters); // Debug log
    fetchPayments(1);
}

// Search box event listener with debounce for better performance
let searchTimeout;

// Clear all filters button
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing filters...'); // Debug log
    
    // Add search box event listener
    const searchBox = document.getElementById('searchBox');
    if (searchBox) {
        searchBox.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyFilters();
            }, 300); // 300ms debounce
        });
    } else {
        console.error('Search box not found!');
    }
    
    // Add clear filters button event listener with robust implementation
    const clearFiltersBtn = document.getElementById('clearFilters');
    if (clearFiltersBtn) {
        console.log('Clear filters button found, adding event listener...'); // Debug log
        clearFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Clear filters button clicked!'); // Debug log
            
            // Add visual feedback
            const originalText = clearFiltersBtn.innerHTML;
            clearFiltersBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Clearing...';
            clearFiltersBtn.disabled = true;
            
            try {
                // Step 1: Clear search box
                const searchBox = document.getElementById('searchBox');
                if (searchBox) {
                    searchBox.value = '';
                    console.log('Search box cleared');
                }
                
                // Step 2: Reset global activeFilters
                if (typeof activeFilters !== 'undefined') {
                    activeFilters = {
                        status: [],
                        payment: [],
                        gateway: [],
                        date: [],
                        'custom-date': [],
                        more: [],
                        paymentgateway: []
                    };
                    console.log('Active filters reset:', activeFilters);
                }
                
                // Step 3: Uncheck all checkboxes in dropdown menus
                const checkboxes = document.querySelectorAll('.filter-submenu input[type=checkbox]');
                console.log('Found', checkboxes.length, 'checkboxes to uncheck');
                checkboxes.forEach(cb => {
                    cb.checked = false;
                });
                
                // Clear custom date inputs
                const fromDateInput = document.getElementById('from-date');
                const toDateInput = document.getElementById('to-date');
                if (fromDateInput) fromDateInput.value = '';
                if (toDateInput) toDateInput.value = '';
                
                // Step 4: Reset button appearances
                const filterButtons = document.querySelectorAll('.filter-menu-btn');
                filterButtons.forEach(btn => {
                    btn.classList.remove('active');
                    const badge = btn.querySelector('.filter-count');
                    if (badge) badge.remove();
                });
                
                // Step 5: Close any open dropdowns
                const submenus = document.querySelectorAll('.filter-submenu');
                submenus.forEach(submenu => {
                    submenu.classList.remove('open');
                });
                
                // Step 6: Show loading state
                showTableLoader();
                
                console.log('All filters cleared, fetching fresh data...');
                
                // Step 7: Fetch fresh data from database
                fetchPayments(1);
                
            } catch (error) {
                console.error('Error in clear filters:', error);
            } finally {
                // Restore button after a short delay
                setTimeout(() => {
                    clearFiltersBtn.innerHTML = originalText;
                    clearFiltersBtn.disabled = false;
                }, 1000);
            }
        });
    } else {
        console.error('Clear filters button not found!');
    }
    
    // Load filter options first, then fetch payments
    loadFilterOptions();
    
    // Initial fetch will happen after filters are loaded
    // fetchPayments(1); // This will be called after filters are loaded
});

// Load filter options from database
function loadFilterOptions() {
    // Since we removed the gateway tabs and status pills, we can directly fetch payments
    console.log('Loading filter options...'); // Debug log
    
    // Fetch initial payments directly
    console.log('Calling fetchPayments(1) for initial load...'); // Debug log
    fetchPayments(1);
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing charts...'); // Debug log
    
    // Payment Trends Chart
    const paymentCtx = document.getElementById('paymentChart');
    if (paymentCtx) {
        console.log('Payment chart found, initializing...'); // Debug log
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
    } else {
        console.log('Payment chart not found'); // Debug log
    }
    
    // Gateway Distribution Chart
    const gatewayCtx = document.getElementById('gatewayChart');
    if (gatewayCtx) {
        console.log('Gateway chart found, initializing...'); // Debug log
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
    } else {
        console.log('Gateway chart not found'); // Debug log
    }
});

// Menu dropdown logic: only one open at a time
const menuButtons = document.querySelectorAll('.filter-menu-btn');
const submenus = {
    status: document.getElementById('dropdown-status'),
    payment: document.getElementById('dropdown-payment'),
    gateway: document.getElementById('dropdown-gateway'),
    date: document.getElementById('dropdown-date'),
    'custom-date': document.getElementById('dropdown-custom-date'),
    more: document.getElementById('dropdown-more')
};
let openSubmenu = null;

menuButtons.forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const type = button.getAttribute('data-dropdown');
        
        // Close any open submenu
        Object.values(submenus).forEach(s => s.classList.remove('open'));
        menuButtons.forEach(b => b.classList.remove('active'));
        
        if (submenus[type]) {
            if (openSubmenu === type) {
                openSubmenu = null;
            } else {
                submenus[type].classList.add('open');
                button.classList.add('active');
                openSubmenu = type;
                
                // Restore checkbox states when opening submenu
                restoreCheckboxStates(type);
                
                console.log('Opening submenu:', type);
            }
        } else {
            openSubmenu = null;
        }
    });
});

// Close submenus when clicking outside
document.addEventListener('click', function(e) {
    if (![...menuButtons].some(b => b.contains(e.target)) && !Object.values(submenus).some(s => s.contains(e.target))) {
        Object.values(submenus).forEach(s => s.classList.remove('open'));
        menuButtons.forEach(b => b.classList.remove('active'));
        openSubmenu = null;
    }
});
// Global filter state
let activeFilters = {
    status: [],
    payment: [],
    gateway: [],
    date: [],
    'custom-date': [],
    more: [],
    paymentgateway: []
};

function applyDropdownFilter(type) {
    console.log('applyDropdownFilter called for type:', type); // Debug log
    
    // Collect checked values for this filter type
    const submenu = document.getElementById(`dropdown-${type}`);
    if (!submenu) {
        console.error(`Dropdown submenu not found for type: ${type}`);
        return;
    }
    
    const checkedBoxes = submenu.querySelectorAll('input[type=checkbox]:checked');
    activeFilters[type] = Array.from(checkedBoxes).map(cb => cb.value);
    
    console.log('Applied filters for', type, ':', activeFilters[type]);
    console.log('All active filters:', activeFilters);
    
    // Update button appearance to show active filters
    updateButtonAppearance(type);
    
    // Close submenu
    Object.values(submenus).forEach(s => s.classList.remove('open'));
    menuButtons.forEach(b => b.classList.remove('active'));
    openSubmenu = null;
    
    // Trigger AJAX filter
    console.log('Triggering applyFilters()...'); // Debug log
    applyFilters();
}

function applyCustomDateFilter() {
    console.log('applyCustomDateFilter called'); // Debug log
    
    const fromDate = document.getElementById('from-date').value;
    const toDate = document.getElementById('to-date').value;
    
    if (!fromDate && !toDate) {
        console.log('No date range selected');
        activeFilters['custom-date'] = [];
    } else {
        activeFilters['custom-date'] = [{
            from: fromDate,
            to: toDate
        }];
        console.log('Custom date range applied:', { fromDate, toDate });
    }
    
    console.log('All active filters:', activeFilters);
    
    // Update button appearance to show active filters
    updateButtonAppearance('custom-date');
    
    // Close submenu
    Object.values(submenus).forEach(s => s.classList.remove('open'));
    menuButtons.forEach(b => b.classList.remove('active'));
    openSubmenu = null;
    
    // Trigger AJAX filter
    console.log('Triggering applyFilters()...'); // Debug log
    applyFilters();
}

function restoreCheckboxStates(type) {
    const dropdown = document.getElementById(`dropdown-${type}`);
    if (!dropdown) return;
    
    // Get all checkboxes in this dropdown
    const checkboxes = dropdown.querySelectorAll('input[type=checkbox]');
    
    // Restore checked state based on activeFilters
    checkboxes.forEach(checkbox => {
        if (activeFilters[type].includes(checkbox.value)) {
            checkbox.checked = true;
        } else {
            checkbox.checked = false;
        }
    });
}

function updateButtonAppearance(type) {
    const button = document.querySelector(`[data-dropdown="${type}"]`);
    const filterCount = activeFilters[type].length;
    
    if (filterCount > 0) {
        button.classList.add('active');
        
        // Add count badge
        let badge = button.querySelector('.filter-count');
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'filter-count';
            badge.style.cssText = 'background:#6366f1;color:#fff;border-radius:50%;padding:0.1em 0.4em;font-size:0.8em;margin-left:0.3em;min-width:1.2em;text-align:center;';
            button.appendChild(badge);
        }
        badge.textContent = filterCount;
    } else {
        button.classList.remove('active');
        
        // Remove count badge
        const badge = button.querySelector('.filter-count');
        if (badge) badge.remove();
    }
}

function clearAllFilters() {
    console.log('clearAllFilters called - starting to clear all filters...'); // Debug log
    
    // Clear all filter states
    activeFilters = {
        status: [],
        payment: [],
        date: [],
        more: [],
        paymentgateway: []
    };
    
    console.log('Active filters reset:', activeFilters); // Debug log
    
    // Uncheck all checkboxes
    const checkboxes = document.querySelectorAll('.filter-submenu input[type=checkbox]');
    console.log('Found', checkboxes.length, 'checkboxes to uncheck'); // Debug log
    checkboxes.forEach(cb => cb.checked = false);
    
    // Reset button appearances
    Object.keys(activeFilters).forEach(type => updateButtonAppearance(type));
    
    // Clear search box
    const searchBox = document.getElementById('searchBox');
    if (searchBox) {
        searchBox.value = '';
        console.log('Search box cleared'); // Debug log
    }
    
    // Close any open dropdowns
    Object.values(submenus).forEach(s => s.classList.remove('open'));
    menuButtons.forEach(b => b.classList.remove('active'));
    openSubmenu = null;
    
    console.log('All filters cleared, calling applyFilters()...'); // Debug log
    
    // Trigger AJAX reload
    applyFilters();
    
    console.log('clearAllFilters completed'); // Debug log
}
</script>
@endsection