<!-- Add Stock Modal Component -->
<div id="addStockModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-boxes"></i> Add Stock</h2>
            <span class="close" onclick="closeModal('addStockModal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addStockForm" class="modal-form">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-search"></i> Search Product *
                        </label>
                        <div class="search-container">
                            <input type="text" name="productSearch" class="form-input" placeholder="Type to search products..." 
                                   autocomplete="off" onkeyup="searchProducts(this.value)">
                            <div class="search-dropdown" id="productSearchDropdown" style="display: none;">
                                <!-- Product search results will be populated here -->
                            </div>
                        </div>
                        <input type="hidden" name="selectedProductId" id="selectedProductId">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-plus"></i> Quantity to Add *
                        </label>
                        <input type="number" name="quantityToAdd" class="form-input" placeholder="0" min="1" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-dollar-sign"></i> Cost per Unit
                        </label>
                        <input type="number" name="costPerUnit" class="form-input" placeholder="0.00" step="0.01" min="0">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-truck"></i> Supplier
                        </label>
                        <input type="text" name="supplier" class="form-input" placeholder="Supplier name (optional)">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calendar"></i> Purchase Date
                        </label>
                        <input type="date" name="purchaseDate" class="form-input" value="">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-exclamation-triangle"></i> Expiry Date
                        </label>
                        <input type="date" name="expiryDate" class="form-input">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-sticky-note"></i> Notes
                        </label>
                        <textarea name="notes" class="form-textarea" placeholder="Additional notes (optional)" rows="2"></textarea>
                    </div>
                </div>

                <!-- Product Info Display -->
                <div class="product-info-display" id="productInfoDisplay" style="display: none;">
                    <div class="product-info-card">
                        <div class="product-info-header">
                            <h4>Selected Product</h4>
                        </div>
                        <div class="product-info-content">
                            <div class="product-info-item">
                                <span class="info-label">Product:</span>
                                <span class="info-value" id="displayProductName">-</span>
                            </div>
                            <div class="product-info-item">
                                <span class="info-label">Brand:</span>
                                <span class="info-value" id="displayBrand">-</span>
                            </div>
                            <div class="product-info-item">
                                <span class="info-label">Current Stock:</span>
                                <span class="info-value" id="displayCurrentStock">-</span>
                            </div>
                            <div class="product-info-item">
                                <span class="info-label">Price:</span>
                                <span class="info-value" id="displayPrice">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('addStockModal')">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="submit" form="addStockForm" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Stock
            </button>
        </div>
    </div>
</div>
