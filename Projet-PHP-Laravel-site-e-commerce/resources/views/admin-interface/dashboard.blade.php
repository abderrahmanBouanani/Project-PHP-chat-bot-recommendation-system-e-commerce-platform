@extends('admin_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
<div class="main-content">
      <h1 class="h3 mb-4">Tableau de Bord</h1>

      <div class="row g-3">
        <!-- Carte Clients -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
          <div class="stats-card h-100">
            <div class="card-body d-flex flex-column">
              <h6 class="text-muted mb-3">Total Clients</h6>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 id="totalClients" class="mb-0">{{ $stats['clients'] }}</h3>
                <div class="stats-icon-container">
                  <i class="bi bi-people fs-1"></i>
                </div>
              </div>
              <div class="mt-auto">
                <a href="{{ url('/admin_utilisateur')}}?type=client" class="see-more d-inline-flex align-items-center">
                  Voir plus <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Carte Vendeurs -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
          <div class="stats-card h-100">
            <div class="card-body d-flex flex-column">
              <h6 class="text-muted mb-3">Total Vendeurs</h6>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 id="totalVendors" class="mb-0">{{ $stats['vendeurs'] }}</h3>
                <div class="stats-icon-container">
                  <i class="bi bi-person-workspace fs-1"></i>
                </div>
              </div>
              <div class="mt-auto">
                <a href="{{ url('/admin_utilisateur')}}?type=Vendeur" class="see-more d-inline-flex align-items-center">
                  Voir plus <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Carte Livreurs -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
          <div class="stats-card h-100">
            <div class="card-body d-flex flex-column">
              <h6 class="text-muted mb-3">Total Livreurs</h6>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 id="totalLivreurs" class="mb-0">{{ $stats['livreurs'] }}</h3>
                <div class="stats-icon-container">
                  <i class="bi bi-truck fs-1"></i>
                </div>
              </div>
              <div class="mt-auto">
                <a href="{{ url('/admin_utilisateur')}}?type=livreur" class="see-more d-inline-flex align-items-center">
                  Voir plus <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Carte Produits -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
          <div class="stats-card h-100">
            <div class="card-body d-flex flex-column">
              <h6 class="text-muted mb-3">Total Produits</h6>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 id="totalProducts" class="mb-0">{{ $stats['produits'] }}</h3>
                <div class="stats-icon-container">
                  <i class="bi bi-box-seam fs-1"></i>
                </div>
              </div>
              <div class="mt-auto">
                <a href="{{ url('/admin_produit') }}" class="see-more d-inline-flex align-items-center">
                  Voir plus <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Carte Commandes -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
          <div class="stats-card h-100">
            <div class="card-body d-flex flex-column">
              <h6 class="text-muted mb-3">Total Commandes</h6>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 id="totalOrders" class="mb-0">{{ $stats['commandes'] }}</h3>
                <div class="stats-icon-container">
                  <i class="bi bi-cart fs-1"></i>
                </div>
              </div>
              <div class="mt-auto">
                <a href="{{ url('/admin_commande') }}" class="see-more d-inline-flex align-items-center">
                  Voir plus <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <!-- Bar Chart for Daily Orders -->
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Commandes par Jour</h5>
            </div>
            <div class="card-body">
              <div id="ordersChart" style="height: 300px"></div>
            </div>
          </div>
        </div>

        <!-- Line Chart for Revenue -->
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Revenus</h5>
            </div>
            <div class="card-body">
              <div id="revenueChart" style="height: 300px"></div>
            </div>
          </div>
        </div>

        <!-- Product Categories Distribution -->
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Distribution des Catégories</h5>
            </div>
            <div class="card-body">
              <div id="categoriesChart" style="height: 300px"></div>
            </div>
          </div>
        </div>

        <!-- User Statistics -->
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Statistiques Utilisateurs</h5>
            </div>
            <div class="card-body">
              <div id="userStatsChart" style="height: 300px"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="card mt-4">
        <div
          class="card-header d-flex justify-content-between align-items-center"
        >
          <h5 class="mb-0">Dernières Commandes</h5>
          <a href="{{url('/admin_commande')}}" class="see-more"
            >Voir toutes les commandes</a
          >
        </div>
        <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th><i class="bi bi-calendar3 me-2"></i>Date</th>
                    <th><i class="bi bi-person me-2"></i>Client</th>
                    <th><i class="bi bi-box-seam me-2"></i>Produits</th>
                    <th class="text-end"><i class="bi bi-currency-dollar me-2"></i>Total</th>
                  </tr>
                </thead>
                <tbody id="recentOrders">
                  <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                      <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Chargement...</span>
                      </div>
                      Chargement des commandes...
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        initializeCharts();
        loadRecentOrders();
      });

      function initializeCharts() {
        // Obtenir les données depuis l'API
        fetch('/api/admin/dashboard/chart-data')
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(response => {
            if (response.success && response.data) {
              renderOrdersChart(response.data.orders);
              renderRevenueChart(response.data.monthly_sales);
              renderCategoriesChart(response.data.category_distribution);
              renderUserStatsChart([
                { name: 'Clients', data: [{{ $stats['clients'] }}] },
                { name: 'Vendeurs', data: [{{ $stats['vendeurs'] }}] },
                { name: 'Livreurs', data: [{{ $stats['livreurs'] }}] }
              ]);
            } else {
              throw new Error('Invalid data format');
            }
          })
          .catch(error => {
            console.error('Error loading chart data:', error);
            // En cas d'erreur, initialiser les graphiques avec des données de test
            initializeChartsWithTestData();
          });
      }

      function loadRecentOrders() {
        fetch('/api/admin/dashboard/recent-orders')
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(orders => {
            const recentOrdersContainer = document.getElementById("recentOrders");
            
            if (!Array.isArray(orders) || orders.length === 0) {
              recentOrdersContainer.innerHTML = `
                <tr>
                  <td colspan="4" class="text-center">Aucune commande disponible</td>
                </tr>
              `;
            } else {
              recentOrdersContainer.innerHTML = orders
                .map(order => `
                  <tr class="align-middle">
                    <td>
                      <div class="d-flex flex-column">
                        <span class="fw-medium">${formatDate(new Date(order.created_at))}</span>
                        <small class="text-muted">${new Date(order.created_at).toLocaleTimeString()}</small>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                          <i class="bi bi-person-fill text-muted"></i>
                        </div>
                        <span>${order.client ? order.client.nom + ' ' + order.client.prenom : 'Client inconnu'}</span>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex flex-wrap gap-1">
                        ${order.produits && order.produits.length > 0 
                          ? order.produits.map(p => 
                              `<span class="badge bg-light text-dark border">${p.nom} (x${p.pivot.quantite})</span>`
                            ).join(' ')
                          : '<span class="text-muted">Aucun produit</span>'
                        }
                      </div>
                    </td>
                    <td class="text-end fw-bold">
                      ${order.total ? parseFloat(order.total).toFixed(2) : '0.00'} DH
                    </td>
                  </tr>`
                ).join('')
                .join("");
            }
          })
          .catch(error => {
            console.error('Error loading recent orders:', error);
            // En cas d'erreur, afficher un message et utiliser des données de test
            const recentOrdersContainer = document.getElementById("recentOrders");
            recentOrdersContainer.innerHTML = `
              <tr>
                <td>Aujourd'hui</td>
                <td>Client Test</td>
                <td>Produit Test</td>
                <td>0.00 DH</td>
              </tr>
            `;
          });
      }

      // Initialisation des graphiques avec des données de test (si l'API échoue)
      function initializeChartsWithTestData() {
        // Données de test pour les graphiques
        const days = ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"];
        const dailyOrders = [5, 7, 3, 8, 10, 6, 4];
        const dailyRevenue = [2500, 3200, 1800, 4100, 5000, 2900, 2000];
        const categoryCount = {
          'Ordinateurs': 8,
          'Écrans': 6,
          'Montres': 4,
          'Chaises': 5,
          'Claviers': 3
        };
        const userStats = [
          { name: 'Clients', data: [{{ $stats['clients'] }}] },
          { name: 'Vendeurs', data: [{{ $stats['vendeurs'] }}] },
          { name: 'Livreurs', data: [{{ $stats['livreurs'] }}] }
        ];

        renderOrdersChart({ labels: days, data: dailyOrders });
        renderRevenueChart({ labels: days, data: dailyRevenue });
        renderCategoriesChart(categoryCount);
        renderUserStatsChart(userStats);
      }

      function renderOrdersChart(orderData) {
        const ordersChartOptions = {
          series: [
            {
              name: "Commandes",
              data: orderData.data,
            },
          ],
          chart: {
            type: "bar",
            height: 300,
            toolbar: { show: false },
          },
          colors: [
            getComputedStyle(document.documentElement).getPropertyValue(
              "--chart-color-1"
            ) || "#3b5d50",
          ],
          plotOptions: {
            bar: { borderRadius: 4 }
          },
          xaxis: {
            categories: orderData.labels,
          },
        };
        new ApexCharts(
          document.querySelector("#ordersChart"),
          ordersChartOptions
        ).render();
      }

      function renderRevenueChart(revenueData) {
        const revenueChartOptions = {
          series: [
            {
              name: "Revenus",
              data: revenueData.data,
            },
          ],
          chart: {
            type: "line",
            height: 300,
            toolbar: { show: false },
          },
          colors: [
            getComputedStyle(document.documentElement).getPropertyValue(
              "--chart-color-2"
            ) || "#f9bf29",
          ],
          stroke: { curve: "smooth" },
          xaxis: {
            categories: revenueData.labels,
          },
          yaxis: {
            labels: {
              formatter: (value) => value + " DH",
            },
          },
        };
        new ApexCharts(
          document.querySelector("#revenueChart"),
          revenueChartOptions
        ).render();
      }

      function renderCategoriesChart(categoryData) {
        const categoriesChart = new ApexCharts(document.querySelector("#categoriesChart"), {
          series: categoryData.data,
          chart: {
            type: "donut",
            height: 300
          },
          labels: categoryData.labels,
          title: {
            text: "Distribution des Catégories",
            align: "center"
          },
          legend: {
            position: "bottom"
          },
          colors: [
            getComputedStyle(document.documentElement).getPropertyValue(
              "--chart-color-1"
            ) || "#3b5d50",
            getComputedStyle(document.documentElement).getPropertyValue(
              "--chart-color-2"
            ) || "#f9bf29",
            getComputedStyle(document.documentElement).getPropertyValue(
              "--chart-color-3"
            ) || "#6c757d",
            getComputedStyle(document.documentElement).getPropertyValue(
              "--chart-color-4"
            ) || "#20c997",
            getComputedStyle(document.documentElement).getPropertyValue(
              "--chart-color-5"
            ) || "#fd7e14",
          ]
        });

        categoriesChart.render();
      }

      function renderUserStatsChart(userStats) {
        const userStatsChartOptions = {
          series: userStats,
          chart: {
            type: "bar",
            height: 300,
            toolbar: { show: false },
          },
          colors: [
            getComputedStyle(document.documentElement).getPropertyValue(
              "--chart-color-1"
            ) || "#3b5d50",
            getComputedStyle(document.documentElement).getPropertyValue(
              "--chart-color-2"
            ) || "#f9bf29",
            getComputedStyle(document.documentElement).getPropertyValue(
              "--chart-color-3"
            ) || "#6c757d",
          ],
          plotOptions: {
            bar: {
              horizontal: true,
              dataLabels: {
                enabled: false // Désactive l'affichage des étiquettes de données
              },
            },
          },
          dataLabels: {
            enabled: false // Désactive les étiquettes de données globalement
          },
          xaxis: {
            categories: ["Utilisateurs"],
          },
        };
        new ApexCharts(
          document.querySelector("#userStatsChart"),
          userStatsChartOptions
        ).render();
      }

      function formatDate(date) {
        const days = [
          "Dimanche",
          "Lundi",
          "Mardi",
          "Mercredi",
          "Jeudi",
          "Vendredi",
          "Samedi",
        ];
        return days[date.getDay()];
      }
    </script>
@endsection <!-- Ici finit le contenu spécifique à cette page -->
