@extends('livreur_base')

@push('styles')
    <style>
        .text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }
        .modal-header {
            border-bottom: 1px solid #e9ecef;
        }
        .modal-footer {
            border-top: 1px solid #e9ecef;
        }
        .table-hover > tbody > tr:hover {
            --bs-table-accent-bg: rgba(0, 0, 0, 0.02);
        }
        .btn-outline-success {
            border-color: #28a745;
            color: #28a745;
            transition: all 0.3s ease;
        }
        .btn-outline-success:hover {
            background-color: #28a745;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border-radius: 10px;
        }
        .card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            color: #495057;
        }
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 6px;
        }
        .btn-group .btn {
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            margin: 0 2px;
        }
        .btn-group .btn i {
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
<div class="main-content">
    <h1 class="h3 mb-4"><i class="fas fa-truck me-2"></i>Livraisons disponibles</h1>

    @include('livreur-interface.partials.liste_commandes', ['commandes' => $commandes, 'type' => 'disponibles'])
</div>
@endsection 