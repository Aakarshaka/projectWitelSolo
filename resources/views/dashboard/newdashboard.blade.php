@extends('layouts.layout')

@section('title', 'DASHBOARD')

@section('content')
<!-- Header Section -->
<div class="dash-header">
    <div class="header-left">
        <h1 class="dash-title">DASHBOARD</h1>
    </div>
</div>

<!-- Dashboard Content -->
<div class="dash-container">
    <div class="dashboard-content">
        <div class="dashboard-card">
            <div class="welcome-section">
                <h2>Halo {{ auth()->user()->name ?? 'User' }}, selamat datang di Giat Core</h2>
                <p class="welcome-subtitle">Akses sistem BPP melalui dashboard utama</p>
            </div>
            
            <div class="card-divider"></div>
            
            <div class="card-body">
                <div class="card-header">
                    <div class="bpp-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>BPP Dashboard</h3>
                    <p>Akses dashboard utama sistem BPP</p>
                </div>
                
                <a href="http://10.60.170.171/bpp-dashboard/" class="btn btn-primary" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    Buka BPP Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Header Styles */
.dash-header {
    background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
    color: white;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 0;
    position: relative;
    z-index: 10;
    margin-left: 80px; /* Sesuaikan dengan lebar sidebar */
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.dash-title {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    letter-spacing: 1px;
}

/* Dashboard Content */
.dash-container {
    padding: 40px;
    min-height: calc(100vh - 120px);
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 80px; /* Sesuaikan dengan lebar sidebar */
}

.dashboard-content {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width: 600px;
}

.dashboard-card {
    background: #fff;
    border-radius: 20px;
    padding: 0;
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: 100%;
    overflow: hidden;
    border: 1px solid rgba(139, 21, 56, 0.1);
}

.dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 60px rgba(139, 21, 56, 0.15);
}

.welcome-section {
    text-align: center;
    padding: 40px 40px 20px 40px;
    background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
    color: white;
    position: relative;
}

.welcome-section::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 20px solid transparent;
    border-right: 20px solid transparent;
    border-top: 10px solid #4a0e4e;
}

.welcome-section h2 {
    font-size: 1.8rem;
    margin: 0 0 15px 0;
    font-weight: 600;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.welcome-subtitle {
    font-size: 1rem;
    margin: 0;
    opacity: 0.9;
    font-weight: 300;
}

.card-divider {
    height: 20px;
    background: white;
}

.card-body {
    padding: 30px 40px 40px 40px;
    text-align: center;
}

.card-header {
    margin-bottom: 30px;
}

.bpp-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
    border-radius: 50%;
    margin: 0 auto 20px auto;
    box-shadow: 0 4px 20px rgba(139, 21, 56, 0.3);
}

.bpp-icon i {
    font-size: 2rem;
    color: white;
}

.card-header h3 {
    color: #2c3e50;
    font-size: 1.8rem;
    margin-bottom: 15px;
    font-weight: 600;
}

.card-header p {
    color: #7f8c8d;
    font-size: 1rem;
    margin: 0;
    line-height: 1.5;
}

.btn {
    display: inline-block;
    padding: 18px 40px;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1.1rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.btn-primary {
    background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
    color: white;
    box-shadow: 0 4px 20px rgba(139, 21, 56, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #6d1028 0%, #3a0c3e 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(139, 21, 56, 0.4);
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-primary:hover::before {
    left: 100%;
}

.btn i {
    margin-right: 12px;
    font-size: 1rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .dash-header {
        padding: 20px 25px;
        margin-left: 80px;
    }
    
    .dash-container {
        padding: 30px 25px;
        margin-left: 80px;
    }
}

@media (max-width: 768px) {
    .dash-header {
        padding: 15px 20px;
        margin-left: 0; /* Hilangkan margin di mobile */
    }
    
    .dash-title {
        font-size: 1.6rem;
    }
    
    .dash-container {
        padding: 20px 15px;
        margin-left: 0; /* Hilangkan margin di mobile */
    }
    
    .dashboard-card {
        border-radius: 15px;
    }
    
    .welcome-section {
        padding: 30px 25px 15px 25px;
    }
    
    .welcome-section h2 {
        font-size: 1.5rem;
    }
    
    .welcome-subtitle {
        font-size: 0.9rem;
    }
    
    .card-body {
        padding: 25px 25px 30px 25px;
    }
    
    .card-header h3 {
        font-size: 1.5rem;
    }
    
    .bpp-icon {
        width: 70px;
        height: 70px;
    }
    
    .bpp-icon i {
        font-size: 1.8rem;
    }
    
    .btn {
        padding: 16px 35px;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .dash-header {
        padding: 15px;
        margin-left: 0;
    }
    
    .dash-title {
        font-size: 1.4rem;
    }
    
    .dash-container {
        padding: 15px 10px;
        margin-left: 0;
    }
    
    .welcome-section {
        padding: 25px 20px 15px 20px;
    }
    
    .welcome-section h2 {
        font-size: 1.3rem;
    }
    
    .card-body {
        padding: 20px 20px 25px 20px;
    }
    
    .card-header h3 {
        font-size: 1.3rem;
    }
    
    .card-header p {
        font-size: 0.9rem;
    }
    
    .bpp-icon {
        width: 60px;
        height: 60px;
        margin-bottom: 15px;
    }
    
    .bpp-icon i {
        font-size: 1.6rem;
    }
    
    .btn {
        padding: 14px 30px;
        font-size: 0.95rem;
        width: 100%;
    }
}

/* Pastikan layout tidak terpotong sidebar */
@media (min-width: 769px) {
    .dash-container {
        margin-left: 80px;
        width: calc(100% - 80px);
    }
    
    .dash-header {
        margin-left: 80px;
        width: calc(100% - 80px);
    }
}

/* Animation untuk card saat load */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dashboard-card {
    animation: fadeInUp 0.6s ease-out;
}
</style>
@endsection