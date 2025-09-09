<?php
session_start();
require_once 'includes/db.php';

// ✅ Check login + address
if (isset($_GET['action']) && $_GET['action'] === 'checkLogin') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['loggedIn' => false]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $result = $conn->query("SELECT name, house_no, street, city, state, country, pincode, mobile 
                            FROM users WHERE id = $user_id LIMIT 1");
    $user = $result->fetch_assoc();

    $addressComplete = !empty($user['house_no']) && !empty($user['city']) && !empty($user['pincode']);

    echo json_encode([
        'loggedIn' => true,
        'addressComplete' => $addressComplete,
        'user' => $user
    ]);
    exit;
}

// ✅ Save cart into session
if (isset($_GET['action']) && $_GET['action'] === 'saveCart') {
    $cartData = json_decode(file_get_contents("php://input"), true);
    $_SESSION['cart'] = $cartData;
    echo json_encode(['success' => true]);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glass Calculator - UK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .tab-active {
            background-color: #2563eb;
            color: white;
        }
        .shape-option {
            transition: all 0.3s ease;
        }
        .shape-option:hover {
            transform: scale(1.05);
        }
        .shape-selected {
            border: 3px solid #2563eb;
            background-color: #eff6ff;
        }
        .price-display {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 16px;
        }
        @media (min-width: 640px) {
            .form-section {
                padding: 24px;
                margin-bottom: 20px;
            }
        }
        .input-field {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            transition: border-color 0.3s ease;
        }
        .input-field:focus {
            border-color: #2563eb;
            outline: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }
        .error-message {
            color: #dc2626;
            font-size: 14px;
            margin-top: 5px;
        }
        .cart-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .cart-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border-radius: 8px;
            padding: 4px;
        }
        .quantity-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: #2563eb;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.2s ease;
        }
        .quantity-btn:hover {
            background: #1d4ed8;
            transform: scale(1.1);
        }
        .quantity-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        .quantity-display {
            min-width: 40px;
            text-align: center;
            font-weight: 600;
            color: #374151;
        }
        .cart-header {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            text-align: center;
        }
        .cart-empty {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }
        .cart-empty svg {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            opacity: 0.5;
        }
        .delivery-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .delivery-modal-content {
            background: white;
            border-radius: 12px;
            padding: 16px;
            max-width: 600px;
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        @media (min-width: 640px) {
            .delivery-modal-content {
                padding: 24px;
                width: 90%;
                max-height: 80vh;
            }
        }
        .delivery-area {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .delivery-area:hover {
            border-color: #2563eb;
            background-color: #eff6ff;
        }
        .delivery-area.selected {
            border-color: #2563eb;
            background-color: #eff6ff;
        }
        .delivery-counties {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 8px;
            margin-top: 12px;
        }
        .delivery-county {
            background: #f8fafc;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
        }
        .success-popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            z-index: 1001;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.4s ease;
            max-width: 350px;
        }
        .success-popup.show {
            transform: translateX(0);
            opacity: 1;
        }
        .success-popup .popup-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .success-popup .popup-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }
        .success-popup .popup-text {
            flex: 1;
        }
        .success-popup .popup-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }
        .success-popup .popup-message {
            font-size: 14px;
            opacity: 0.9;
        }
        .pattern-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .pattern-modal-content {
            background: white;
            border-radius: 12px;
            padding: 16px;
            max-width: 800px;
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        @media (min-width: 640px) {
            .pattern-modal-content {
                padding: 24px;
                width: 90%;
                max-height: 80vh;
            }
        }
        .pattern-option {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        .pattern-option:hover {
            border-color: #2563eb;
            background-color: #eff6ff;
        }
        .pattern-option.selected {
            border-color: #2563eb;
            background-color: #eff6ff;
        }
        .pattern-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin: 0 auto 12px;
            display: block;
        }
        .glass-config-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin: 0 auto 12px;
            display: block;
        }
        .file-item {
            display: flex;
            align-items: center;
            justify-content: between;
            padding: 8px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .file-item:last-child {
            margin-bottom: 0;
        }
        .file-icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
            flex-shrink: 0;
        }
        .file-info {
            flex: 1;
            min-width: 0;
        }
        .file-name {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .file-size {
            font-size: 12px;
            color: #6b7280;
        }
        .file-remove {
            width: 20px;
            height: 20px;
            color: #ef4444;
            cursor: pointer;
            flex-shrink: 0;
            margin-left: 8px;
        }
        .file-remove:hover {
            color: #dc2626;
        }
        .upload-area {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .upload-area:hover {
            background-color: #f9fafb;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 max-w-7xl">
        <header class="text-center mb-6 lg:mb-8">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2">Glass Calculator</h1>
            <p class="text-sm sm:text-base text-gray-600">Professional glazing solutions for the UK market</p>
        </header>

        <!-- Tab Navigation -->
        <div class="flex justify-center mb-6 lg:mb-8">
            <div class="bg-white rounded-lg p-1 sm:p-2 shadow-lg w-full max-w-4xl">
                <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 justify-center">
                    <a href="/Sealed Units/index.php" class="tab-btn px-3 sm:px-6 py-3 sm:py-4 rounded-lg font-semibold transition-all duration-300 text-sm sm:text-base flex flex-col items-center gap-2">
                        <img src="https://cdn-icons-png.flaticon.com/512/1946/1946436.png" alt="home" class="w-8 h-8 sm:w-10 sm:h-10">
                        <span>Back To Home</span>
                    </a>
                    <button id="singleTab" class="tab-btn tab-active px-3 sm:px-6 py-3 sm:py-4 rounded-lg font-semibold transition-all duration-300 text-sm sm:text-base flex flex-col items-center gap-2">
                        <img src="https://cdn-icons-png.flaticon.com/512/2593/2593549.png" alt="Single Unit" class="w-8 h-8 sm:w-10 sm:h-10">
                        <span>Single Unit</span>
                    </button>
                    <button id="doubleTab" class="tab-btn px-3 sm:px-6 py-3 sm:py-4 rounded-lg font-semibold transition-all duration-300 text-sm sm:text-base flex flex-col items-center gap-2">
                        <img src="https://cdn-icons-png.flaticon.com/512/2593/2593551.png" alt="Double Glazed" class="w-8 h-8 sm:w-10 sm:h-10">
                        <span>Double Glazed</span>
                    </button>
                    <button id="tripleTab" class="tab-btn px-3 sm:px-6 py-3 sm:py-4 rounded-lg font-semibold transition-all duration-300 text-sm sm:text-base flex flex-col items-center gap-2">
                        <img src="https://cdn-icons-png.flaticon.com/512/2593/2593553.png" alt="Triple Glazed" class="w-8 h-8 sm:w-10 sm:h-10">
                        <span>Triple Glazed</span>
                    </button>
                </div>
            </div>
        </div>



        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
            <!-- Calculator Form -->
            <div class="xl:col-span-2">
                <!-- Single Unit Tab -->
                <div id="singleUnit" class="tab-content">
                    <!-- Step 1: Glass Configuration -->
                    <div class="form-section">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">1</div>
                                <h3 class="text-xl font-semibold text-gray-800">Glass Configuration</h3>
                            </div>
                            <img src="https://cdn-icons-png.flaticon.com/512/2593/2593549.png" alt="Single Unit Glass" class="w-12 h-12 opacity-60">
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Glass Type</label>
                            <select id="singleGlassType" class="input-field w-full">
                                <option value="">Select</option>
                                <option value="clear-tgh" data-price="93.30">Clear Glass Tgh [4/6/8/10 mm]</option>
                                <option value="low-iron-tgh" data-price="122.49">Low Iron Glass Tgh [4/6/8/10 mm]</option>
                                <option value="satin-tgh" data-price="123.00">Satin Glass Tgh [4/6/8/10 mm]</option>
                                <option value="tinted-tgh" data-price="186.80">Tinted Glass Tgh [6/10 mm]</option>
                                <option value="black-painted-tgh" data-price="192.30">Black Painted Tgh Glass [4/6/8/10 mm]</option>
                                <option value="white-painted-tgh" data-price="192.30">White Painted Tgh Glass [4/6/8/10 mm]</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Polished Glass (P.A.R)</label>
                            <div class="flex gap-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="singlePolished" value="yes" data-price="30" class="mr-2" checked disabled>
                                    <span class="text-gray-500">Yes (+30%)</span>
                                </label>
                                <label class="flex items-center cursor-not-allowed">
                                    <input type="radio" name="singlePolished" value="no" data-price="0" class="mr-2" disabled>
                                    <span class="text-gray-500">No</span>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Required for single panel glass</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Toughened Glass</label>
                            <div class="flex gap-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="singleToughened" value="yes" data-price="30" class="mr-2" checked disabled>
                                    <span class="text-gray-500">Yes (+30%)</span>
                                </label>
                                <label class="flex items-center cursor-not-allowed">
                                    <input type="radio" name="singleToughened" value="no" data-price="0" class="mr-2" disabled>
                                    <span class="text-gray-500">No</span>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Required for single panel glass</p>
                        </div>
                    </div>

                    <!-- Step 2: Shape Selection -->
                    <div class="form-section">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">2</div>
                            <h3 class="text-xl font-semibold text-gray-800">Shape Selection</h3>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Select Shape *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="single-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="square" data-price="0" data-tab="single">
                                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=120&h=120&fit=crop&crop=center" alt="Square Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Square</p>
                                    <p class="text-xs text-gray-500">+0% price modifier</p>
                                </div>
                                <div class="single-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="triangle" data-price="15" data-tab="single">
                                    <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=120&h=120&fit=crop&crop=center" alt="Triangle Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Right Angle Triangle</p>
                                    <p class="text-xs text-gray-500">+15% price modifier</p>
                                </div>
                                <div class="single-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="rake" data-price="20" data-tab="single">
                                    <img src="https://images.unsplash.com/photo-1565814329452-e1efa11c5b89?w=120&h=120&fit=crop&crop=center" alt="Rake Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Rake</p>
                                    <p class="text-xs text-gray-500">+20% price modifier</p>
                                </div>
                                <div class="single-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="arched" data-price="25" data-tab="single">
                                    <img src="https://images.unsplash.com/photo-1572021335469-31706a17aaef?w=120&h=120&fit=crop&crop=center" alt="Arched Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Arched-Top</p>
                                    <p class="text-xs text-gray-500">+25% price modifier</p>
                                </div>
                                <div class="single-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="circle" data-price="20" data-tab="single">
                                    <img src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=120&h=120&fit=crop&crop=center" alt="Circle Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Circle</p>
                                    <p class="text-xs text-gray-500">+20% price modifier</p>
                                </div>
                                <div class="single-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="other" data-price="30" data-tab="single">
                                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?w=120&h=120&fit=crop&crop=center" alt="Other Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Other</p>
                                    <p class="text-xs text-gray-500">+30% price modifier</p>
                                </div>
                            </div>
                            <input type="hidden" id="singleShape" required>
                            <div id="singleShapeError" class="error-message hidden">Please select a shape</div>
                        </div>

                        <!-- Shape Summary (shown after selection) -->
                        <div id="singleShapeSummary" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-blue-800 mb-2">Selected Shape Configuration</h4>
                                    <p class="text-sm text-blue-700"><strong>Shape:</strong> <span id="singleSelectedShapeName">-</span></p>
                                    <p class="text-sm text-blue-700"><strong>Dimensions:</strong> <span id="singleSelectedDimensions">-</span></p>
                                    <p class="text-sm text-blue-700"><strong>Area:</strong> <span id="singleSelectedArea">-</span></p>
                                    <p class="text-sm text-blue-700" id="singleSelectedReference" style="display: none;"><strong>Reference:</strong> <span id="singleSelectedReferenceText">-</span></p>
                                    <p class="text-sm text-blue-700"><strong>Weight:</strong> <span id="singleSelectedWeight">-</span></p>
                                </div>
                                <button type="button" id="singleEditShape" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                            </div>
                        </div>

                        <!-- Hidden form fields for validation -->
                        <input type="hidden" id="singleWidth" required>
                        <input type="hidden" id="singleHeight" required>
                        <input type="hidden" id="singleReference">
                        <input type="hidden" id="singleCustomShape">
                        <div id="singleWidthError" class="error-message hidden">Width must be between 100-2800mm</div>
                        <div id="singleHeightError" class="error-message hidden">Height must be between 100-2800mm</div>
                        <div id="singleCustomShapeError" class="error-message hidden">Please describe the custom shape</div>
                        
                        <!-- Area display for calculations -->
                        <div class="hidden">
                            <span id="singleArea">0.000 m²</span>
                        </div>
                    </div>

                    <!-- Step 3: Optional Extras -->
                    <div class="form-section">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">3</div>
                            <h3 class="text-xl font-semibold text-gray-800">Optional Extras</h3>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Features</label>
                            <select id="singleExtras" class="input-field w-full">
                                <option value="none" data-price="0">None</option>
                                <option value="drill-30x30" data-price="0">4 x 30/30 Drill Holes</option>
                                <option value="drill-50x50" data-price="0">4 x 50/50 Drill Holes</option>
                                <option value="drill-60mm" data-price="0">1 x 60mm Hole to Centre</option>
                            </select>
                            
                            <!-- Georgian Bars Options -->
                            <div id="singleGeorgianOptions" class="mt-4 hidden">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Georgian Bars Size</label>
                                    <select id="singleGeorgianSize" class="input-field w-full">
                                        <option value="">Select</option>
                                        <option value="18mm-white">18mm White</option>
                                        <option value="25mm-white">25mm White</option>
                                        <option value="18mm-white-single">18mm White Single</option>
                                        <option value="25mm-white-single">25mm White Single</option>
                                        <option value="18mm-woodgrain">18mm Woodgrain</option>
                                        <option value="25mm-woodgrain">25mm Woodgrain</option>
                                        <option value="18mm-brown">18mm Brown</option>
                                        <option value="25mm-brown">25mm Brown</option>
                                        <option value="8mm-gold">8mm Gold</option>
                                        <option value="8mm-chrome">8mm Chrome</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Duplex Grid Options -->
                            <div id="singleDuplexOptions" class="mt-4 hidden">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Duplex Grid Size</label>
                                    <select id="singleDuplexSize" class="input-field w-full">
                                        <option value="">Select</option>
                                        <option value="18mm-integral">18mm Integral</option>
                                        <option value="18mm-single-integral">18mm Single Integral</option>
                                        <option value="24mm-integral">24mm Integral</option>
                                        <option value="24mm-single-integral">24mm Single Integral</option>
                                        <option value="39mm-integral">39mm Integral</option>
                                        <option value="39mm-single-integral">39mm Single Integral</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Pet Flap/Vent Hole</label>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-800">Add Pet Flap/Vent Hole</p>
                                    <p class="text-sm text-gray-600">+£50 additional cost</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="singlePetFlap" data-price="50" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Double Glazed Tab -->
                <div id="doubleGlazed" class="tab-content hidden">
                    <!-- Step 1: Glass Configuration -->
                    <div class="form-section">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">1</div>
                                <h3 class="text-xl font-semibold text-gray-800">Glass Configuration</h3>
                            </div>
                            <img src="https://cdn-icons-png.flaticon.com/512/2593/2593551.png" alt="Double Glazed Glass" class="w-12 h-12 opacity-60">
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-6">
                            <p class="text-sm text-blue-800 font-medium">Argon gas filled as standard</p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">External Glass</label>
                                <select id="doubleOuterGlass" class="input-field w-full">
                                    <option value="">Select</option>
                                    <option value="4mm-clear" data-price="39.75">4mm Clear</option>
                                    <option value="4mm-pattern" data-price="45.05">4mm Pattern</option>
                                    <option value="4mm-satin" data-price="99.60">4mm Satin</option>
                                    <option value="6mm-clear" data-price="65.25">6mm Clear</option>
                                    <option value="6.4mm-clear-laminate" data-price="95.00">6.4mm Clear Laminate</option>
                                    <option value="6.8mm-acoustic" data-price="111.30">6.8mm Acoustic</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Internal Glass</label>
                                <select id="doubleInnerGlass" class="input-field w-full">
                                    <option value="">Select</option>
                                    <option value="4mm-clear" data-price="39.75">4mm Clear</option>
                                    <option value="4mm-planitherm" data-price="45.05">4mm Planitherm</option>
                                    <option value="4mm-planitherm-1" data-price="65.40">4mm Planitherm 1.0</option>
                                    <option value="6mm-clear" data-price="65.25">6mm Clear</option>
                                    <option value="6mm-planitherm" data-price="89.70">6mm Planitherm</option>
                                    <option value="6mm-planitherm-laminate" data-price="106.00">6mm Planitherm Laminate</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Spacer Bar</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Width</label>
                                    <select id="doubleSpacerWidth" class="input-field w-full">
                                        <option value="">Select</option>
                                        <option value="6" data-price="0">6mm</option>
                                        <option value="8" data-price="0">8mm</option>
                                        <option value="10" data-price="0">10mm</option>
                                        <option value="12" data-price="0">12mm</option>
                                        <option value="14" data-price="0">14mm</option>
                                        <option value="16" data-price="0">16mm</option>
                                        <option value="18" data-price="0">18mm</option>
                                        <option value="20" data-price="0">20mm</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Colour</label>
                                    <select id="doubleSpacerType" class="input-field w-full">
                                        <option value="silver" data-price="0">Silver</option>
                                        <option value="gold" data-price="0">Gold</option>
                                        <option value="warm-edge-white" data-price="0">Warm Edge White</option>
                                        <option value="warm-edge-grey" data-price="0">Warm Edge Grey</option>
                                        <option value="warm-edge-black" data-price="0">Warm Edge Black</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Toughened Glass *</label>
                            <div class="flex gap-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="doubleToughened" value="yes" data-price="30" class="mr-2">
                                    <span>Yes (+30%)</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="doubleToughened" value="no" data-price="0" class="mr-2">
                                    <span>No</span>
                                </label>
                            </div>
                            <div id="doubleToughenedError" class="error-message hidden">Please select toughened glass option</div>
                        </div>
                    </div>

                    <!-- Step 2: Shape Selection -->
                    <div class="form-section">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">2</div>
                            <h3 class="text-xl font-semibold text-gray-800">Shape Selection</h3>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Select Shape *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="double-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="square" data-price="0" data-tab="double">
                                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=120&h=120&fit=crop&crop=center" alt="Square Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Square</p>
                                    <p class="text-xs text-gray-500">+0% price modifier</p>
                                </div>
                                <div class="double-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="triangle" data-price="15" data-tab="double">
                                    <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=120&h=120&fit=crop&crop=center" alt="Triangle Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Right Angle Triangle</p>
                                    <p class="text-xs text-gray-500">+15% price modifier</p>
                                </div>
                                <div class="double-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="rake" data-price="20" data-tab="double">
                                    <img src="https://images.unsplash.com/photo-1565814329452-e1efa11c5b89?w=120&h=120&fit=crop&crop=center" alt="Rake Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Rake</p>
                                    <p class="text-xs text-gray-500">+20% price modifier</p>
                                </div>
                                <div class="double-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="arched" data-price="25" data-tab="double">
                                    <img src="https://images.unsplash.com/photo-1572021335469-31706a17aaef?w=120&h=120&fit=crop&crop=center" alt="Arched Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Arched-Top</p>
                                    <p class="text-xs text-gray-500">+25% price modifier</p>
                                </div>
                                <div class="double-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="circle" data-price="20" data-tab="double">
                                    <img src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=120&h=120&fit=crop&crop=center" alt="Circle Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Circle</p>
                                    <p class="text-xs text-gray-500">+20% price modifier</p>
                                </div>
                                <div class="double-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="other" data-price="30" data-tab="double">
                                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?w=120&h=120&fit=crop&crop=center" alt="Other Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Other</p>
                                    <p class="text-xs text-gray-500">+30% price modifier</p>
                                </div>
                            </div>
                            <input type="hidden" id="doubleShape" required>
                            <div id="doubleShapeError" class="error-message hidden">Please select a shape</div>
                        </div>

                        <!-- Shape Summary (shown after selection) -->
                        <div id="doubleShapeSummary" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-blue-800 mb-2">Selected Shape Configuration</h4>
                                    <p class="text-sm text-blue-700"><strong>Shape:</strong> <span id="doubleSelectedShapeName">-</span></p>
                                    <p class="text-sm text-blue-700"><strong>Dimensions:</strong> <span id="doubleSelectedDimensions">-</span></p>
                                    <p class="text-sm text-blue-700"><strong>Area:</strong> <span id="doubleSelectedArea">-</span></p>
                                    <p class="text-sm text-blue-700" id="doubleSelectedReference" style="display: none;"><strong>Reference:</strong> <span id="doubleSelectedReferenceText">-</span></p>
                                    <p class="text-sm text-blue-700"><strong>Weight:</strong> <span id="doubleSelectedWeight">-</span></p>
                                
                                </div>
                                <button type="button" id="doubleEditShape" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                            </div>
                        </div>

                        <!-- Hidden form fields for validation -->
                        <input type="hidden" id="doubleWidth" required>
                        <input type="hidden" id="doubleHeight" required>
                        <input type="hidden" id="doubleReference">
                        <input type="hidden" id="doubleCustomShape">
                        <div id="doubleWidthError" class="error-message hidden">Width must be between 100-2800mm</div>
                        <div id="doubleHeightError" class="error-message hidden">Height must be between 100-2800mm</div>
                        <div id="doubleCustomShapeError" class="error-message hidden">Please describe the custom shape</div>
                        
                        <!-- Area display for calculations -->
                        <div class="hidden">
                            <span id="doubleArea">0.000 m²</span>
                        </div>
                    </div>

                    <!-- Step 3: Optional Extras -->
                    <div class="form-section">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">3</div>
                            <h3 class="text-xl font-semibold text-gray-800">Optional Extras</h3>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Features</label>
                            <select id="doubleExtras" class="input-field w-full">
                                <option value="none" data-price="0">None</option>
                                <option value="georgian" data-price="57.50">18mm White Georgian</option>
                                <option value="lead-square" data-price="62.50">9mm Square Lead</option>
                                <option value="lead-diamond" data-price="70">9mm Diamond Lead</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Pet Flap/Vent Hole</label>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-800">Add Pet Flap/Vent Hole</p>
                                    <p class="text-sm text-gray-600">+£50 additional cost</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="doublePetFlap" data-price="50" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Triple Glazed Tab -->
                <div id="tripleGlazed" class="tab-content hidden">
                    <!-- Step 1: Glass Configuration -->
                    <div class="form-section">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">1</div>
                                <h3 class="text-xl font-semibold text-gray-800">Glass Configuration</h3>
                            </div>
                            <img src="https://cdn-icons-png.flaticon.com/512/2593/2593553.png" alt="Triple Glazed Glass" class="w-12 h-12 opacity-60">
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-6">
                            <p class="text-sm text-blue-800 font-medium">Argon gas filled as standard</p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">External Glass</label>
                                <select id="tripleOuterGlass" class="input-field w-full">
                                    <option value="">Select</option>
                                    <option value="4mm-clear" data-price="39.75">4mm Clear</option>
                                    <option value="4mm-pattern" data-price="45.05">4mm Pattern</option>
                                    <option value="4mm-satin" data-price="99.60">4mm Satin</option>
                                    <option value="6mm-clear" data-price="65.25">6mm Clear</option>
                                    <option value="6.4mm-clear-laminate" data-price="95.00">6.4mm Clear Laminate</option>
                                    <option value="6.8mm-acoustic" data-price="111.30">6.8mm Acoustic</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Centre Glass</label>
                                <select id="tripleCentreGlass" class="input-field w-full">
                                    <option value="">Select</option>
                                    <option value="4mm-clear" data-price="39.75">4mm Clear</option>
                                    <option value="4mm-planitherm" data-price="45.05">4mm Planitherm</option>
                                    <option value="4mm-planitherm-1" data-price="65.40">4mm Planitherm 1.0</option>
                                    <option value="6mm-clear" data-price="65.25">6mm Clear</option>
                                    <option value="6mm-planitherm" data-price="89.70">6mm Planitherm</option>
                                    <option value="6mm-planitherm-laminate" data-price="106.00">6mm Planitherm Laminate</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Internal Glass</label>
                                <select id="tripleInnerGlass" class="input-field w-full">
                                    <option value="">Select</option>
                                    <option value="4mm-clear" data-price="39.75">4mm Clear</option>
                                    <option value="4mm-planitherm" data-price="45.05">4mm Planitherm</option>
                                    <option value="4mm-planitherm-1" data-price="65.40">4mm Planitherm 1.0</option>
                                    <option value="6mm-clear" data-price="65.25">6mm Clear</option>
                                    <option value="6mm-planitherm" data-price="89.70">6mm Planitherm</option>
                                    <option value="6mm-planitherm-laminate" data-price="106.00">6mm Planitherm Laminate</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Spacer Bars</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Spacer Bar 1 Width</label>
                                    <select id="tripleSpacer1Width" class="input-field w-full">
                                        <option value="">Select</option>
                                        <option value="6" data-price="0">6mm</option>
                                        <option value="8" data-price="0">8mm</option>
                                        <option value="10" data-price="0">10mm</option>
                                        <option value="12" data-price="0">12mm</option>
                                        <option value="14" data-price="0">14mm</option>
                                        <option value="16" data-price="0">16mm</option>
                                        <option value="18" data-price="0">18mm</option>
                                        <option value="20" data-price="0">20mm</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Spacer Bar 2 Width</label>
                                    <select id="tripleSpacer2Width" class="input-field w-full">
                                        <option value="">Select</option>
                                        <option value="6" data-price="0">6mm</option>
                                        <option value="8" data-price="0">8mm</option>
                                        <option value="10" data-price="0">10mm</option>
                                        <option value="12" data-price="0">12mm</option>
                                        <option value="14" data-price="0">14mm</option>
                                        <option value="16" data-price="0">16mm</option>
                                        <option value="18" data-price="0">18mm</option>
                                        <option value="20" data-price="0">20mm</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Spacer Bar Colour</label>
                                    <select id="tripleSpacerType" class="input-field w-full">
                                        <option value="silver" data-price="0">Silver</option>
                                        <option value="gold" data-price="0">Gold</option>
                                        <option value="warm-edge-white" data-price="0">Warm Edge White</option>
                                        <option value="warm-edge-grey" data-price="0">Warm Edge Grey</option>
                                        <option value="warm-edge-black" data-price="0">Warm Edge Black</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Toughened Glass *</label>
                            <div class="flex gap-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="tripleToughened" value="yes" data-price="30" class="mr-2">
                                    <span>Yes (+30%)</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="tripleToughened" value="no" data-price="0" class="mr-2">
                                    <span>No</span>
                                </label>
                            </div>
                            <div id="tripleToughenedError" class="error-message hidden">Please select toughened glass option</div>
                        </div>
                    </div>

                    <!-- Step 2: Shape Selection -->
                    <div class="form-section">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">2</div>
                            <h3 class="text-xl font-semibold text-gray-800">Shape Selection</h3>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Select Shape *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="triple-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="square" data-price="0" data-tab="triple">
                                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=120&h=120&fit=crop&crop=center" alt="Square Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Square</p>
                                    <p class="text-xs text-gray-500">+0% price modifier</p>
                                </div>
                                <div class="triple-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="triangle" data-price="15" data-tab="triple">
                                    <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=120&h=120&fit=crop&crop=center" alt="Triangle Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Right Angle Triangle</p>
                                    <p class="text-xs text-gray-500">+15% price modifier</p>
                                </div>
                                <div class="triple-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="rake" data-price="20" data-tab="triple">
                                    <img src="https://images.unsplash.com/photo-1565814329452-e1efa11c5b89?w=120&h=120&fit=crop&crop=center" alt="Rake Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Rake</p>
                                    <p class="text-xs text-gray-500">+20% price modifier</p>
                                </div>
                                <div class="triple-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="arched" data-price="25" data-tab="triple">
                                    <img src="https://images.unsplash.com/photo-1572021335469-31706a17aaef?w=120&h=120&fit=crop&crop=center" alt="Arched Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Arched-Top</p>
                                    <p class="text-xs text-gray-500">+25% price modifier</p>
                                </div>
                                <div class="triple-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="circle" data-price="20" data-tab="triple">
                                    <img src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=120&h=120&fit=crop&crop=center" alt="Circle Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Circle</p>
                                    <p class="text-xs text-gray-500">+20% price modifier</p>
                                </div>
                                <div class="triple-shape-option cursor-pointer p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all" data-shape="other" data-price="30" data-tab="triple">
                                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?w=120&h=120&fit=crop&crop=center" alt="Other Shape" class="w-20 h-20 object-cover rounded-lg mx-auto mb-3">
                                    <p class="text-sm font-medium">Other</p>
                                    <p class="text-xs text-gray-500">+30% price modifier</p>
                                </div>
                            </div>
                            <input type="hidden" id="tripleShape" required>
                            <div id="tripleShapeError" class="error-message hidden">Please select a shape</div>
                        </div>

                        <!-- Shape Summary (shown after selection) -->
                        <div id="tripleShapeSummary" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-blue-800 mb-2">Selected Shape Configuration</h4>
                                    <p class="text-sm text-blue-700"><strong>Shape:</strong> <span id="tripleSelectedShapeName">-</span></p>
                                    <p class="text-sm text-blue-700"><strong>Dimensions:</strong> <span id="tripleSelectedDimensions">-</span></p>
                                    <p class="text-sm text-blue-700"><strong>Area:</strong> <span id="tripleSelectedArea">-</span></p>
                                    <p class="text-sm text-blue-700" id="tripleSelectedReference" style="display: none;"><strong>Reference:</strong> <span id="tripleSelectedReferenceText">-</span></p>
                                    <p class="text-sm text-blue-700"><strong>Weight:</strong> <span id="tripleSelectedWeight">-</span></p>
                                </div>
                                <button type="button" id="tripleEditShape" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                            </div>
                        </div>

                        <!-- Hidden form fields for validation -->
                        <input type="hidden" id="tripleWidth" required>
                        <input type="hidden" id="tripleHeight" required>
                        <input type="hidden" id="tripleReference">
                        <input type="hidden" id="tripleCustomShape">
                        <div id="tripleWidthError" class="error-message hidden">Width must be between 100-2800mm</div>
                        <div id="tripleHeightError" class="error-message hidden">Height must be between 100-2800mm</div>
                        <div id="tripleCustomShapeError" class="error-message hidden">Please describe the custom shape</div>
                        
                        <!-- Area display for calculations -->
                        <div class="hidden">
                            <span id="tripleArea">0.000 m²</span>
                        </div>
                    </div>

                    <!-- Step 3: Optional Extras -->
                    <div class="form-section">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">3</div>
                            <h3 class="text-xl font-semibold text-gray-800">Optional Extras</h3>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Features</label>
                            <select id="tripleExtras" class="input-field w-full">
                                <option value="none" data-price="0">None</option>
                                <option value="georgian" data-price="57.50">18mm White Georgian</option>
                                <option value="lead-square" data-price="62.50">9mm Square Lead</option>
                                <option value="lead-diamond" data-price="70">9mm Diamond Lead</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Pet Flap/Vent Hole</label>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-800">Add Pet Flap/Vent Hole</p>
                                    <p class="text-sm text-gray-600">+£50 additional cost</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="triplePetFlap" data-price="50" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-8">
                    <button id="addToCart" class="btn-primary text-lg px-8 py-4">Add to Cart</button>
                </div>
            </div>

            <!-- Price Display & Cart -->
            <div class="xl:col-span-1">
                <div class="price-display mb-6">
                    <h3 class="text-2xl font-bold mb-4">Current Price</h3>
                    <div class="space-y-2">
                        <div class="text-3xl font-bold">£<span id="currentPrice">0.00</span></div>
                        <div class="text-sm opacity-90">
                            <p>Price excludes VAT</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="cart-header">
                        <h3 class="text-xl font-bold">Shopping Cart</h3>
                        <p class="text-sm opacity-90 mt-1">Review your glass orders</p>
                    </div>
                    <div class="px-6 pb-6">
                        <div id="cartItems">
                            <div class="cart-empty">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                                <p class="text-lg font-medium">Your cart is empty</p>
                                <p class="text-sm mt-2">Add some glass units to get started</p>
                            </div>
                        </div>
                        <div id="cartSummary" class="hidden border-t pt-6 mt-6">
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span>Subtotal:</span>
                                    <span>£<span id="cartSubtotal">0.00</span></span>
                                </div>
                                <div id="cartShippingRow" class="flex justify-between text-sm hidden">
                                    <span id="cartShippingLabel">Shipping:</span>
                                    <span>£<span id="cartShipping">0.00</span></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>VAT (20%):</span>
                                    <span>£<span id="cartVAT">0.00</span></span>
                                </div>
                                <div class="flex justify-between text-lg font-bold border-t pt-3">
                                    <span>Total:</span>
                                    <span class="text-blue-600">£<span id="cartTotal">0.00</span></span>
                                </div>
                            </div>
                            <button id="proceedToBuy" class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                                Proceed to Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Area Selection Modal -->
    <div id="deliveryModal" class="delivery-modal hidden">
        <div class="delivery-modal-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Select Delivery Area</h2>
                <button id="closeDeliveryModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
            </div>
            
            <div class="space-y-4">
                <div class="delivery-area" data-area="1" data-charge="37.50">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-semibold text-gray-800">Delivery Area 1</h3>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">£37.50 + VAT</p>
                            <p class="text-sm text-gray-500">per delivery</p>
                        </div>
                    </div>
                    <div class="delivery-counties">
                        <div class="delivery-county">Bedfordshire</div>
                        <div class="delivery-county">Berkshire</div>
                        <div class="delivery-county">Buckinghamshire</div>
                        <div class="delivery-county">Cambridgeshire</div>
                        <div class="delivery-county">Essex</div>
                        <div class="delivery-county">Hampshire</div>
                        <div class="delivery-county">Hertfordshire</div>
                        <div class="delivery-county">Kent</div>
                        <div class="delivery-county">Middlesex</div>
                        <div class="delivery-county">Oxfordshire</div>
                        <div class="delivery-county">Norfolk</div>
                        <div class="delivery-county">Suffolk</div>
                        <div class="delivery-county">Surrey</div>
                        <div class="delivery-county">Sussex</div>
                        <div class="delivery-county">Wiltshire</div>
                    </div>
                </div>
                
                <div class="delivery-area" data-area="2" data-charge="67.50">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-semibold text-gray-800">Delivery Area 2</h3>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">£67.50 + VAT</p>
                            <p class="text-sm text-gray-500">per delivery</p>
                            <p class="text-xs text-green-600 mt-1">£37.50 + VAT for orders over £2000</p>
                        </div>
                    </div>
                    <div class="delivery-counties">
                        <div class="delivery-county">Derbyshire</div>
                        <div class="delivery-county">Dorset</div>
                        <div class="delivery-county">Gloucestershire</div>
                        <div class="delivery-county">Herefordshire</div>
                        <div class="delivery-county">Leicestershire</div>
                        <div class="delivery-county">Lincolnshire</div>
                        <div class="delivery-county">Nottinghamshire</div>
                        <div class="delivery-county">Rutland</div>
                        <div class="delivery-county">Shropshire</div>
                        <div class="delivery-county">Somerset</div>
                        <div class="delivery-county">Staffordshire</div>
                        <div class="delivery-county">Warwickshire</div>
                        <div class="delivery-county">Worcestershire</div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-4 mt-8">
                <button id="cancelDelivery" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button id="confirmDelivery" class="btn-primary" disabled>
                    Confirm & Add to Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Glass Configuration Modal -->
    <div id="glassConfigModal" class="pattern-modal hidden">
        <div class="pattern-modal-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Glass Configuration</h2>
                <button id="closeGlassConfigModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
            </div>
            
            <!-- Thickness Selection -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Select Thickness</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4" id="thicknessOptions">
                    <!-- Thickness options will be populated dynamically -->
                </div>
            </div>
            
            <!-- Tinted Glass Color Selection (only for tinted glass) -->
            <div id="tintedColorSection" class="mb-6 hidden">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Select Tinted Glass Color</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="pattern-option tinted-color-option" data-color="grey">
                        <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?w=150&h=150&fit=crop&crop=center" 
                             alt="Grey Tinted Glass" class="glass-config-image">
                        <h4 class="text-md font-semibold text-gray-800">Grey</h4>
                        <p class="text-sm text-gray-600 mt-2">Grey tinted glass</p>
                    </div>
                    <div class="pattern-option tinted-color-option" data-color="bronze">
                        <img src="https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=150&h=150&fit=crop&crop=center" 
                             alt="Bronze Tinted Glass" class="glass-config-image">
                        <h4 class="text-md font-semibold text-gray-800">Bronze</h4>
                        <p class="text-sm text-gray-600 mt-2">Bronze tinted glass</p>
                    </div>
                </div>
            </div>
            
            <!-- Corners Selection -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Select Corners</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="pattern-option corner-option" data-corner="straight">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=150&h=150&fit=crop&crop=center" 
                             alt="Straight Corners" class="glass-config-image">
                        <h4 class="text-md font-semibold text-gray-800">Straight</h4>
                        <p class="text-sm text-gray-600 mt-2">90° straight corners</p>
                    </div>
                    <div class="pattern-option corner-option" data-corner="dubbed">
                        <img src="https://images.unsplash.com/photo-1565814329452-e1efa11c5b89?w=150&h=150&fit=crop&crop=center" 
                             alt="Dubbed Corners" class="glass-config-image">
                        <h4 class="text-md font-semibold text-gray-800">Dubbed</h4>
                        <p class="text-sm text-gray-600 mt-2">Slightly rounded corners</p>
                    </div>
                    <div class="pattern-option corner-option" data-corner="radius">
                        <img src="https://images.unsplash.com/photo-1572021335469-31706a17aaef?w=150&h=150&fit=crop&crop=center" 
                             alt="Radius Corners" class="glass-config-image">
                        <h4 class="text-md font-semibold text-gray-800">Radius</h4>
                        <p class="text-sm text-gray-600 mt-2">Rounded radius corners</p>
                    </div>
                    <div class="pattern-option corner-option" data-corner="clipped">
                        <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=150&h=150&fit=crop&crop=center" 
                             alt="Clipped Corners" class="glass-config-image">
                        <h4 class="text-md font-semibold text-gray-800">Clipped</h4>
                        <p class="text-sm text-gray-600 mt-2">Angled clipped corners</p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-4 mt-8">
                <button id="cancelGlassConfig" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button id="confirmGlassConfig" class="btn-primary" disabled>
                    Confirm Configuration
                </button>
            </div>
        </div>
    </div>

    <!-- Pattern Selection Modal -->
    <div id="patternModal" class="pattern-modal hidden">
        <div class="pattern-modal-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Select Pattern Type</h2>
                <button id="closePatternModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                <div class="pattern-option" data-pattern="flemish">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/flemish-min.png?mh=500" 
                         alt="Flemish Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Flemish</h3>
                    <p class="text-sm text-gray-600 mt-2">Classic textured pattern with subtle geometric design</p>
                </div>
                
                <div class="pattern-option" data-pattern="minster">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/minster-min.png?mh=500" 
                         alt="Minster Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Minster</h3>
                    <p class="text-sm text-gray-600 mt-2">Traditional cathedral-style textured pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="warwick">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/warwick-min.png?mh=500" 
                         alt="Warwick Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Warwick</h3>
                    <p class="text-sm text-gray-600 mt-2">Elegant textured glass with refined pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="chantilly">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/chantilly-min.png?mh=500" 
                         alt="Chantilly Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Chantilly</h3>
                    <p class="text-sm text-gray-600 mt-2">Delicate lace-like textured pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="reeded">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/reeded-min.png?mh=500" 
                         alt="Reeded Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Reeded</h3>
                    <p class="text-sm text-gray-600 mt-2">Linear vertical ridged pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="digital">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/digital-min.png?mh=500" 
                         alt="Digital Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Digital</h3>
                    <p class="text-sm text-gray-600 mt-2">Modern pixelated textured design</p>
                </div>
                
                <div class="pattern-option" data-pattern="taffeta">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/taffeta-min.png?mh=500" 
                         alt="Taffeta Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Taffeta</h3>
                    <p class="text-sm text-gray-600 mt-2">Silk-like woven texture pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="oak">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/oak-min.png?mh=500" 
                         alt="Oak Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Oak</h3>
                    <p class="text-sm text-gray-600 mt-2">Natural wood grain inspired texture</p>
                </div>
                
                <div class="pattern-option" data-pattern="florielle">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/contora-min.png?mh=500" 
                         alt="Florielle Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Florielle</h3>
                    <p class="text-sm text-gray-600 mt-2">Floral-inspired decorative pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="mayflower">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/mayflower-min.png?mh=500" 
                         alt="MayFlower Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">MayFlower</h3>
                    <p class="text-sm text-gray-600 mt-2">Botanical textured glass design</p>
                </div>
                
                <div class="pattern-option" data-pattern="pelerine">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/pereline-min.png?mh=500" 
                         alt="Pelerine Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Pelerine</h3>
                    <p class="text-sm text-gray-600 mt-2">Sophisticated curved line pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="everglade">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/everglade-min.png?mh=500" 
                         alt="Everglade Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Everglade</h3>
                    <p class="text-sm text-gray-600 mt-2">Nature-inspired organic texture</p>
                </div>
                
                <div class="pattern-option" data-pattern="cassini">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/cassini-min.png?mh=500" 
                         alt="Cassini Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Cassini</h3>
                    <p class="text-sm text-gray-600 mt-2">Geometric circular pattern design</p>
                </div>
                
                <div class="pattern-option" data-pattern="tribal">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/tribal-min.png?mh=500" 
                         alt="Tribal Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Tribal</h3>
                    <p class="text-sm text-gray-600 mt-2">Bold ethnic-inspired pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="sycamore">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/sycamore-min.png?mh=500" 
                         alt="Sycamore Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Sycamore</h3>
                    <p class="text-sm text-gray-600 mt-2">Wood grain textured pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="autumn">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/autumn-min.png?mh=500" 
                         alt="Autumn Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Autumn</h3>
                    <p class="text-sm text-gray-600 mt-2">Seasonal leaf-inspired texture</p>
                </div>
                
                <div class="pattern-option" data-pattern="arctic">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/arctic-min.png?mh=500" 
                         alt="Arctic Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Arctic</h3>
                    <p class="text-sm text-gray-600 mt-2">Ice crystal inspired pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="stippolyte">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/stippolyte-min.png?mh=500" 
                         alt="Stippolyte Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Stippolyte</h3>
                    <p class="text-sm text-gray-600 mt-2">Fine stippled texture pattern</p>
                </div>
                
                <div class="pattern-option" data-pattern="cotswold">
                    <img src="https://www.pilkington.com/-/media/pilkington/site-content/_base/texture-glass-images/cotswold-min2.png?mh=500" 
                         alt="Cotswold Pattern" class="pattern-image">
                    <h3 class="text-lg font-semibold text-gray-800">Cotswold</h3>
                    <p class="text-sm text-gray-600 mt-2">Traditional countryside texture</p>
                </div>
            </div>
            
            <div class="flex justify-end gap-4 mt-8">
                <button id="cancelPattern" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button id="confirmPattern" class="btn-primary" disabled>
                    Confirm Selection
                </button>
            </div>
        </div>
    </div>

    <!-- Shape Dimensions Modal -->
    <div id="shapeDimensionsModal" class="pattern-modal hidden">
        <div class="pattern-modal-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Shape Configuration</h2>
                <button id="closeShapeDimensionsModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
            </div>
            
            <!-- Selected Shape Display -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-4">
                    <img id="modalShapeImage" src="" alt="Selected Shape" class="w-16 h-16 object-cover rounded-lg">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-800" id="modalShapeName">Shape Name</h3>
                        <p class="text-sm text-blue-600" id="modalShapePrice">Price modifier</p>
                    </div>
                </div>
            </div>
            
            <!-- Custom Shape Input (only for "Other") -->
            <div id="modalCustomShapeContainer" class="mb-6 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Describe the custom shape *</label>
                <input type="text" id="modalCustomShape" class="input-field w-full" placeholder="e.g., Hexagonal, L-shaped, Curved bottom">
                <div id="modalCustomShapeError" class="error-message hidden">Please describe the custom shape</div>
            </div>
            
            <!-- Unit Dimensions -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Unit Dimensions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Width (mm) *</label>
                        <input type="number" id="modalWidth" class="input-field w-full" min="100" max="2800" required>
                        <div id="modalWidthError" class="error-message hidden">Width must be between 100-2800mm</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Height (mm) *</label>
                        <input type="number" id="modalHeight" class="input-field w-full" min="100" max="2800" required>
                        <div id="modalHeightError" class="error-message hidden">Height must be between 100-2800mm</div>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference</label>
                    <input type="text" id="modalReference" class="input-field w-full" placeholder="Optional reference">
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Area: <span id="modalArea" class="font-semibold">0.000 m²</span></p>
                </div>
            </div>
            
            <!-- Drawing Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Drawing/Specification</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                    <input type="file" id="modalDrawingFile" accept=".png,.jpg,.jpeg,.pdf,.docx" class="hidden" multiple>
                    <div class="upload-area" onclick="document.getElementById('modalDrawingFile').click()">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="text-sm text-gray-600 mb-2">
                            <span class="font-medium text-blue-600 hover:text-blue-500 cursor-pointer">Click to upload</span> or drag and drop
                        </p>
                        <p class="text-xs text-gray-500">PNG, JPG, PDF, DOCX up to 10MB each</p>
                    </div>
                    <div id="modalFileList" class="mt-4 text-left hidden"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Upload technical drawings, sketches, or specifications to help us understand your requirements</p>
            </div>
            
            <div class="flex justify-end gap-4 mt-8">
                <button id="cancelShapeDimensions" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button id="confirmShapeDimensions" class="btn-primary">
                    Confirm Configuration
                </button>
            </div>
        </div>
    </div>

    <!-- Success Popup -->
    <div id="successPopup" class="success-popup">
        <div class="popup-content">
            <svg class="popup-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div class="popup-text">
                <div class="popup-title">Success!</div>
                <div class="popup-message">Item added to cart successfully</div>
            </div>
        </div>
    </div>


<!-- Checkout Modal -->

<div id="checkoutModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
  <div class="bg-white w-full max-w-[500px] p-6 rounded-xl shadow-2xl relative">
    <h3 class="text-2xl font-bold text-gray-800 mb-6">Confirm Your Order</h3>

    <h4 class="mt-6 mb-3 text-lg font-semibold text-gray-700">Delivery Address</h4>
    <div id="checkoutAddress" class="bg-gray-50 p-4 rounded-lg text-gray-600 min-h-[60px]"></div>
    <hr class="my-4 border-gray-200">

    <h4 class="mt-6 mb-3 text-lg font-semibold text-gray-700">Total Amount To Be Paid</h4>
    <div id="checkoutProducts" class="bg-gray-50 p-4 rounded-lg min-h-[80px]"></div>
        <hr class="my-4 border-gray-200">
    <div class="flex gap-3 mt-6">
      <button id="confirmPurchase" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">Confirm Purchase</button>
      <button onclick="closeCheckoutModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold px-6 py-3 rounded-lg transition duration-200">Cancel</button>
    </div>
  </div>
</div>


</div>

    <script src="glass.js"></script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'972b324300d5b540',t:'MTc1NTc5MDQ4NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
