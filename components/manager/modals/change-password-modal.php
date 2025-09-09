<!-- Change Password Modal Component -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-key"></i> Change Password</h2>
            <span class="close" onclick="closeModal('changePasswordModal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="changePasswordForm" class="modal-form">
                <div class="security-notice">
                    <div class="notice-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="notice-content">
                        <h4>Security Requirements</h4>
                        <ul>
                            <li>Password must be at least 8 characters long</li>
                            <li>Include at least one uppercase letter</li>
                            <li>Include at least one lowercase letter</li>
                            <li>Include at least one number</li>
                            <li>Include at least one special character</li>
                        </ul>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> Current Password *
                        </label>
                        <div class="password-input-container">
                            <input type="password" name="currentPassword" class="form-input" placeholder="Enter current password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('currentPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-key"></i> New Password *
                        </label>
                        <div class="password-input-container">
                            <input type="password" name="newPassword" class="form-input" placeholder="Enter new password" 
                                   required onkeyup="checkPasswordStrength(this.value)">
                            <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength" style="display: none;">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Password strength</div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-check-circle"></i> Confirm New Password *
                        </label>
                        <div class="password-input-container">
                            <input type="password" name="confirmPassword" class="form-input" placeholder="Confirm new password" 
                                   required onkeyup="checkPasswordMatch()">
                            <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-match" id="passwordMatch" style="display: none;">
                            <span class="match-indicator" id="matchIndicator"></span>
                        </div>
                    </div>
                </div>

                <!-- Password Requirements Checklist -->
                <div class="password-requirements" id="passwordRequirements">
                    <h4>Password Requirements:</h4>
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
