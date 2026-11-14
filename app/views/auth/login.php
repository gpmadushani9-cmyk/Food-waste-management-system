<div class="auth">
  <div class="card">
    <div class="text-center mb-xl">
      <div style="font-size: 3rem; margin-bottom: 1rem;">🍽️</div>
      <h1>Welcome Back</h1>
      <p class="text-muted">Sign in to your Food Waste Management account</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert--danger">
        <strong>Error:</strong> <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" class="form">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
      
      <div class="form__group">
        <label for="email">Email Address</label>
        <input type="email" 
               id="email" 
               name="email" 
               required 
               placeholder="Enter your email address"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      </div>
      
      <div class="form__group">
        <label for="password">Password</label>
        <input type="password" 
               id="password" 
               name="password" 
               required 
               placeholder="Enter your password" />
      </div>
      
      <button class="btn btn--primary btn--lg" type="submit" style="width: 100%;">
        <span>🔑</span> Sign In
      </button>
    </form>

    <div class="text-center" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
      <p class="text-muted">Don't have an account? 
        <a href="index.php?r=auth/register" class="text-primary font-weight-500">Create one here</a>
      </p>
    </div>
  </div>
  
  <div class="text-center" style="margin-top: 2rem;">
    <small class="text-muted">
      🔒 Your data is secure and encrypted<br>
      Need help? Contact support
    </small>
  </div>
</div>
