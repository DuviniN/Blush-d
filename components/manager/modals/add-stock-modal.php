<!-- Add Stock Modal Component -->
<div id="addStockModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-boxes"></i> Add Stock</h2>
        </div>
        <div class="modal-body">
            <form id="addStockForm" class="modal-form">
                <!-- Simple Product Display -->
                <div class="simple-product-display">
                    <label class="form-label">Product:</label>
                    <div class="product-name-display" id="displayProductName">No product selected</div>
                </div>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-plus"></i> Quantity to Add *
                        </label>
                        <input type="number" name="quantityToAdd" class="form-input" placeholder="0" min="1" required>
                    </div>
                </div>

                <input type="hidden" name="selectedProductId" id="selectedProductId">
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
