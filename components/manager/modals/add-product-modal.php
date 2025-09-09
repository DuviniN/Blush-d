<!-- Add Product Modal Component -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-plus-circle"></i> Add New Product</h2>
            <span class="close" onclick="closeModal('addProductModal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addProductForm" class="modal-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-tag"></i> Product Name *
                        </label>
                        <input type="text" name="productName" class="form-input" placeholder="Enter product name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-building"></i> Brand *
                        </label>
                        <select name="brand" class="form-input" required>
                            <option value="">Select Brand</option>
                            <option value="Clinique">Clinique</option>
                            <option value="Estee Lauder">Estée Lauder</option>
                            <option value="L'Oreal">L'Oréal</option>
                            <option value="Maybelline">Maybelline</option>
                            <option value="Revlon">Revlon</option>
                            <option value="Sephora">Sephora</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-layer-group"></i> Category *
                        </label>
                        <select name="category" class="form-input" required>
                            <option value="">Select Category</option>
                            <option value="Skincare">Skincare</option>
                            <option value="Makeup">Makeup</option>
                            <option value="Fragrance">Fragrance</option>
                            <option value="Hair Care">Hair Care</option>
                            <option value="Body Care">Body Care</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-palette"></i> Type
                        </label>
                        <input type="text" name="type" class="form-input" placeholder="e.g., Foundation, Lipstick, Serum">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-dollar-sign"></i> Price *
                        </label>
                        <input type="number" name="price" class="form-input" placeholder="0.00" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-boxes"></i> Initial Stock *
                        </label>
                        <input type="number" name="stock" class="form-input" placeholder="0" min="0" required>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-align-left"></i> Description
                        </label>
                        <textarea name="description" class="form-textarea" placeholder="Product description (optional)" rows="3"></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-image"></i> Product Image
                        </label>
                        <div class="file-upload-area">
                            <input type="file" name="productImage" class="file-input" accept="image/*" id="productImageInput">
                            <label for="productImageInput" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload image or drag and drop</span>
                                <small>PNG, JPG, GIF up to 10MB</small>
                            </label>
                            <div class="file-preview" id="productImagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                                <button type="button" class="remove-file" onclick="removeImage('productImageInput', 'productImagePreview')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('addProductModal')">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="submit" form="addProductForm" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>
    </div>
</div>
