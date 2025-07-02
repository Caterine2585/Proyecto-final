<?php
include 'conexion.php';
$sql = "SELECT Nombre_Producto, SUM(Stock_Minimo) AS stock_total FROM Productos GROUP BY Nombre_Producto";
$result = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="../css/productos.css">
</head>

<body>
    <div class="d-flex" style="min-height: 100vh;">
        <!-- SIDEBAR -->
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-primary text-white">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32">
                    <use xlink:href="#bootstrap"></use>
                </svg>
                <span class="fs-4">TECNICELL RM</span>
            </a>
            <hr>
            <div class="menu-barra-lateral">
                <div class="seccion-menu">
                    <div class="elemento-menu activo">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </div>
                    <a href="../html/Compra.html" class="elemento-menu">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Compras</span>
                    </a>
                    <div class="elemento-menu">
                        <i class="fas fa-truck"></i>
                        <span>Recibidos</span>
                    </div>
                    <a href="../html/Devoluciones.html" class="elemento-menu">
                        <i class="fas fa-undo"></i>
                        <span>Devoluciones</span>
                    </a>
                    <div class="elemento-menu">
                        <i class="fas fa-cube"></i>
                        <span>Stocks</span>
                    </div>
                    <div class="elemento-menu">
                        <i class="fas fa-chart-line"></i>
                        <span>Ventas</span>
                    </div>
                </div>
                <div class="seccion-menu">
                    <div class="titulo-menu">Mantenimiento</div>
                    <div class="elemento-menu">
                        <i class="fas fa-users"></i>
                        <span>Proveedores</span>
                    </div>
                    <div class="elemento-menu">
                        <i class="fas fa-boxes"></i>
                        <span>Productos</span>
                    </div>
                    <div class="elemento-menu">
                        <i class="fas fa-user-friends"></i>
                        <span>Usuarios</span>
                    </div>
                    <div class="elemento-menu">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-grow-1">
            <!-- NAVBAR -->
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Sistema gestión de inventarios</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <!-- Puedes agregar aquí más links si lo necesitas -->
                    </div>
                    <div class="dropdown ms-auto">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                            <strong>mdo</strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="#">New project...</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- TARJETAS DINÁMICAS -->
            <div class="container mt-4">
              <div class="row justify-content-center">
                <?php while($row = $result->fetch_assoc()): ?>
                  <div class="col-md-3 mb-3">
                    <div class="card text-center" style="width: 18rem;">
                      <img src="https://via.placeholder.com/400x200?text=<?php echo urlencode($row['Nombre_Producto']); ?>"
                           class="card-img-top"
                           alt="<?php echo htmlspecialchars($row['Nombre_Producto']); ?>">
                      <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['Nombre_Producto']); ?></h5>
                        <p class="card-text fs-1 fw-bold">
                          <?php echo $row['stock_total']; ?>
                        </p>
                        <p class="card-text text-muted">Unidades en stock</p>
                        <a href="#" class="btn btn-primary">Ver detalles</a>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>