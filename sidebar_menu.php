<?php
// Prevent direct access to this file
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  header('location: 404.html'); // Redirect to 404 error page if accessed directly
  exit;
}

// Get the active module from the URL parameter
$activeModule = $_GET['module'] ?? '';

// Define all menu items, with subitems easily added as arrays within each section
$menuItems = [
  'Dashboard' => [
    'icon' => 'fas fa-home',
    'module' => 'dashboard',
    'label' => 'Dashboard',
  ],
  'Master' => [
    'icon' => 'fas fa-clone',
    'label' => 'Barang',
    'subitems' => [
      ['module' => 'barang', 'label' => 'Data Barang'],
      ['module' => 'jenis', 'label' => 'Jenis Barang'],
      ['module' => 'lokasi', 'label' => 'Lokasi Barang'],
    ]
  ],
  // New "Transaksi Barang" section with "Barang Masuk" and "Barang Keluar" as subitems
  'Stock Transfer' => [
    'icon' => 'fas fa-exchange-alt', // You can choose a different icon here if needed
    'label' => 'Transaksi Barang',
    'subitems' => [
      //['module' => 'barang_masuk', 'label' => 'Barang Masuk'],
      ['module' => 'permintaan_barang', 'label' => 'Permintaan Barang'],
      ['module' => 'perpindahan_barang', 'label' => 'Perpindahan Barang'],
    ]
  ],

  'Sales' => [
    'icon' => 'fas fa-shopping-cart', 
    'label' => 'Penjualan',
    'subitems' => [
      //['module' => 'barang_masuk', 'label' => 'Barang Masuk'],
      ['module' => 'data_penjualan', 'label' => 'Data Penjualan'],
    ]
  ],

  'Pengaturan' => [
    'icon' => 'fas fa-user',
    'module' => 'user',
    'label' => 'Manajemen User',
  ],
];

?>

<!-- Begin rendering the navigation menu -->
<ul class="nav">
  <?php foreach ($menuItems as $section => $item): ?>
    <?php if (isset($item['subitems'])): ?>
      <!-- Render section title for items with submenus -->
      <li class="nav-section">
        <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
        <h4 class="text-section"><?= $section ?></h4>
      </li>
      
      <!-- Render main item for the section with subitems, and check for active status -->
      <li class="nav-item <?= in_array($activeModule, array_column($item['subitems'], 'module')) ? 'active submenu' : '' ?>">
        <a data-toggle="collapse" href="#<?= strtolower(str_replace(' ', '_', $section)) ?>">
          <i class="<?= $item['icon'] ?>"></i>
          <p><?= $item['label'] ?></p>
          <span class="caret"></span>
        </a>
        
        <!-- Subitems collapse container -->
        <div class="collapse <?= in_array($activeModule, array_column($item['subitems'], 'module')) ? 'show' : '' ?>" id="<?= strtolower(str_replace(' ', '_', $section)) ?>">
          <ul class="nav nav-collapse">
            <?php foreach ($item['subitems'] as $subitem): ?>
              <li class="<?= $activeModule === $subitem['module'] ? 'active' : '' ?>">
                <a href="?module=<?= $subitem['module'] ?>">
                  <span class="sub-item"><?= $subitem['label'] ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </li>
      
    <?php else: ?>
      <!-- Render sections without subitems -->
      <li class="nav-section">
        <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
        <h4 class="text-section"><?= $section ?></h4>
      </li>
      
      <!-- Render single-level menu items, checking for active status -->
      <li class="nav-item <?= $activeModule === $item['module'] ? 'active' : '' ?>">
        <a href="?module=<?= $item['module'] ?>">
          <i class="<?= $item['icon'] ?>"></i>
          <p><?= $item['label'] ?></p>
        </a>
      </li>
      
    <?php endif; ?>
  <?php endforeach; ?>
</ul>
