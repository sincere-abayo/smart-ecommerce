<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - E-commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<?php
require_once '../config/db.php';

// Fetch all products from database
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to determine status badge
function getStatusBadge($quantity, $lowStockAlert) {
    if ($quantity <= 0) {
        return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Out of Stock</span>';
    } elseif ($quantity <= $lowStockAlert) {
        return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Low Stock</span>';
    } else {
        return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>';
    }
}
// Handle Delete Request
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $deleteMessage = "Product deleted successfully";
    } catch(PDOException $e) {
        $deleteError = "Error deleting product: " . $e->getMessage();
    }
}
?>

<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 h-screen w-64 bg-white shadow-lg">
            <div class="p-4">
                <h1 class="text-2xl font-bold text-gray-800">E-commerce</h1>
            </div>
            <nav class="mt-4">
                <ul class="space-y-2">
                    <li>
                        <a href="../index.html" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-home mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="../products/products-list.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-box mr-3"></i>
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="../orders/orders-list.html" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-shopping-cart mr-3"></i>
                            Orders
                        </a>
                    </li>
                    <li>
                        <a href="../customers/customers-list.html" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-users mr-3"></i>
                            Customers
                        </a>
                    </li>
                    <li>
                        <a href="../payments/payments-list.html" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-credit-card mr-3"></i>
                            Payments
                        </a>
                    </li>
                    <li>
                        <a href="../shipping/shipping-list.html" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-truck mr-3"></i>
                            Delivery
                        </a>
                    </li>
                    <li>
                        <a href="../reports/sales-reports.html" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-chart-bar mr-3"></i>
                            Reports
                        </a>
                    </li>
                </ul>
            </nav>
            
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Products</h1>
                <!-- Add this after the main heading -->
<?php if (isset($deleteMessage)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <?php echo $deleteMessage; ?>
    </div>
<?php endif; ?>

<?php if (isset($deleteError)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <?php echo $deleteError; ?>
    </div>
<?php endif; ?>
<?php if (isset($_GET['updated']) === true): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
    Product Updatd weell
    </div>
<?php endif; ?>



                <div class="flex space-x-4">
                    <a href="add-product.php"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Add New Product
                    </a>
                    <a onclick="exportProducts()" 
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                        Export Products
                    </a>
                    <a href="stock-management.html" 
                        class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                        Stock Management
                    </a>
                    <a href="stock-movement.html" 
                        class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                        View Movements
                    </a>
                </div>
            </div>
            

            <!-- Filters and Search -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="grid grid-cols-4 gap-4">
                    <input type="text" placeholder="Search products..." 
                        class="px-4 py-2 border rounded-lg">
                    <select class="px-4 py-2 border rounded-lg">
                        <option value="">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="clothing">Clothing</option>
                        <option value="books">Books</option>
                    </select>
                    <select class="px-4 py-2 border rounded-lg">
                        <option value="">All Status</option>
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="low_stock">Low Stock</option>
                    </select>
                    <button onclick="applyFilters()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Apply Filters
                    </button>
                </div>
            </div>

            <!-- Product Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody" class="bg-white divide-y divide-gray-200">
    <?php foreach ($products as $product): ?>
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                <?php echo htmlspecialchars($product['product_name']); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <?php echo htmlspecialchars($product['sku']); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <?php echo htmlspecialchars($product['category']); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                $<?php echo number_format($product['regular_price'], 2); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <?php echo htmlspecialchars($product['stock_quantity']); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <?php echo getStatusBadge($product['stock_quantity'], $product['low_stock_alert']); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <a href="edit-product.php?id=<?php echo $product['id']; ?>" 
                   class="text-indigo-600 hover:text-indigo-900">Edit</a>
                <a href="?action=delete&id=<?php echo $product['id']; ?>" 
                   class="text-red-600 hover:text-red-900 ml-4"
                   onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

                    
                </table>
            </div>
        </main>
    </div>

 

    <script src="../assets/js/products/product-list.js"></script>
</body>
</html>
