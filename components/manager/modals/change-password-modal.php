<!-- Change Password Modal Component -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-key"></i> Change Password</h2>
        </div>
        <div class="modal-body">
            <form id="changePasswordForm" class="modal-form">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> Current Password *
                        </label>
                        <div class="password-input-container">
                            <input type="password" name="currentPassword" id="currentPassword" class="form-input" placeholder="Enter current password" required>
                            <button type="button" class="password-toggle" data-target="currentPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-key"></i> New Password *
                        </label>
                        <div class="password-input-container">
                            <input type="password" name="newPassword" id="newPassword" class="form-input" placeholder="Enter new password" required>
                            <button type="button" class="password-toggle" data-target="newPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div class="password-strength" id="passwordStrength" style="display: none;">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Password strength</div>
                        </div>
                        
                        <!-- Live Requirements Check -->
                        <div class="password-requirements-live" id="passwordRequirements" style="display: none;">
                            <div class="requirements-header">
                                <small class="requirements-hint">
                                    <i class="fas fa-info-circle"></i>
                                    Password requirements:
                                </small>
                            </div>
                            <div class="requirement-list">
                                <div class="requirement-item" id="req-length">
                                    <i class="fas fa-times requirement-icon"></i>
                                    <span>At least 8 characters</span>
                                </div>
                                <div class="requirement-item" id="req-uppercase">
                                    <i class="fas fa-times requirement-icon"></i>
                                    <span>One uppercase letter</span>
                                </div>
                                <div class="requirement-item" id="req-lowercase">
                                    <i class="fas fa-times requirement-icon"></i>
                                    <span>One lowercase letter</span>
                                </div>
                                <div class="requirement-item" id="req-number">
                                    <i class="fas fa-times requirement-icon"></i>
                                    <span>One number</span>
                                </div>
                                <div class="requirement-item" id="req-special">
                                    <i class="fas fa-times requirement-icon"></i>
                                    <span>One special character</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-check-circle"></i> Confirm New Password *
                        </label>
                        <div class="password-input-container">
                            <input type="password" name="confirmPassword" id="confirmPassword" class="form-input" placeholder="Confirm new password" required>
                            <button type="button" class="password-toggle" data-target="confirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-match" id="passwordMatch" style="display: none;">
                            <span class="match-indicator" id="matchIndicator"></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('changePasswordModal')">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="submit" form="changePasswordForm" class="btn btn-primary" id="changePasswordBtn" disabled>
                <i class="fas fa-key"></i> Change Password
            </button>
        </div>
    </div>
</div>
