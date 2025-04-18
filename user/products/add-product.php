<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // First check if SKU already exists
        $checkSku = $pdo->prepare("SELECT COUNT(*) FROM products WHERE sku = ?");
        $checkSku->execute([$_POST['sku']]);
        $skuExists = $checkSku->fetchColumn();

        if ($skuExists) {
            $error = "This SKU already exists. Please use a unique SKU.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (
                product_name, sku, category, brand, regular_price, 
                sale_price, tax_rate, stock_quantity, low_stock_alert, description
            ) VALUES (
                :product_name, :sku, :category, :brand, :regular_price,
                :sale_price, :tax_rate, :stock_quantity, :low_stock_alert, :description
            )");

            $stmt->execute([
                'product_name' => $_POST['productName'],
                'sku' => $_POST['sku'],
                'category' => $_POST['category'],
                'brand' => $_POST['brand'],
                'regular_price' => $_POST['regularPrice'],
                'sale_price' => $_POST['salePrice'],
                'tax_rate' => $_POST['taxRate'],
                'stock_quantity' => $_POST['stockQuantity'],
                'low_stock_alert' => $_POST['lowStockAlert'],
                'description' => $_POST['description']
            ]);

            $success = "Product added successfully!";
        }
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - E-commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
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
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Add New Product</h1>
                <?php if (isset($success)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <?php echo $success; ?>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
            <form id="addProductForm" class="space-y-6" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

<!-- Basic Information -->
                    <div class="border-b pb-6">
                        <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                                <input type="text" name="productName" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                                <input type="text" name="sku" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select name="category" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Category</option>
                                    <option value="electronics">Electronics</option>
                                    <option value="clothing">Clothing</option>
                                    <option value="books">Books</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                                <input type="text" name="brand"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="border-b pb-6">
                        <h2 class="text-xl font-semibold mb-4">Pricing</h2>
                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price</label>
                                <input type="number" name="regularPrice" step="0.01" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                                <input type="number" name="salePrice" step="0.01"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                                <input type="number" name="taxRate" step="0.01"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Inventory -->
                    <div class="border-b pb-6">
                        <h2 class="text-xl font-semibold mb-4">Inventory</h2>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                                <input type="number" name="stockQuantity" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Low Stock Alert</label>
                                <input type="number" name="lowStockAlert"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="border-b pb-6">
                        <h2 class="text-xl font-semibold mb-4">Description</h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Description</label>
                            <textarea name="description" rows="4"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="border-b pb-6">
                        <h2 class="text-xl font-semibold mb-4">Product Images</h2>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-500 mb-4"></i>
                                    <p class="mb-2 text-sm text-gray-500">Click to upload or drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 800x400px)</p>
                                </div>
                                <input type="file" class="hidden" multiple accept="image/*">
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="window.location.href='products-list.html'"
                            class="px-6 py-2 border rounded-lg hover:bg-gray-100">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Save Product
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
