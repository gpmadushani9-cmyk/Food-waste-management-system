<div class="auth">
  <div class="card">
    <div class="text-center mb-xl">
      <div style="font-size: 3rem; margin-bottom: 1rem;">🎆</div>
      <h1>Create Account</h1>
      <p class="text-muted">Join the Food Waste Management platform</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert--danger">
        <strong>Error:</strong> <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
      <div class="alert alert--success">
        <strong>Success:</strong> <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <form method="post" class="form">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">
      
      <div class="form__group">
        <label for="name">Full Name</label>
        <input type="text" 
               id="name" 
               name="name" 
               required 
               placeholder="Enter your full name"
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" />
      </div>
      
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
               placeholder="Create a secure password"
               minlength="6" />
        <small class="text-muted">Password must be at least 6 characters long</small>
      </div>
      
      <button class="btn btn--primary btn--lg" type="submit" style="width: 100%;">
        <span>✨</span> Create Account
      </button>
    </form>

    <div class="text-center" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
      <p class="text-muted">Already have an account? 
        <a href="index.php?r=auth/login" class="text-primary font-weight-500">Sign in here</a>
      </p>
    </div>
  </div>
  
  <div class="text-center" style="margin-top: 2rem;">
    <small class="text-muted">
      🔒 By creating an account, you agree to our terms of service<br>
      The first registered user becomes the admin automatically
    </small>
  </div>
</div>
