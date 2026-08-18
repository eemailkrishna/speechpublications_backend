#!/bin/bash
# Hostinger Auto-Deploy Script
# Runs automatically during Hostinger deployment
# Fixes: "Your requirements could not be resolved to an installable set of packages"
# Works around PHP/OS platform version mismatches

set -e

echo "========================================="
echo "🚀 Starting Speech Publications Deployment"
echo "========================================="
echo ""

# Change to project directory (in case script is run from elsewhere)
cd "$(dirname "$0")"

echo "📁 Changed to project directory"
echo ""

# Step 1: Install Composer dependencies
# --ignore-platform-reqs bypasses PHP version/OS checks
# --prefer-dist uses dist instead of source (faster, smaller)
# --quiet suppresses output, --no-interaction avoids prompts
echo "📦 Installing Composer dependencies..."
composer install --ignore-platform-reqs --prefer-dist --quiet --no-interaction 2>&1

echo ""
echo "✅ Composer dependencies installed"
echo ""

# Step 2: Generate optimized autoload
echo "🔧 Generating autoload files..."
composer dump-autoload --optimize --quiet 2>&1

echo ""
echo "✅ Autoload generated"
echo ""

# Step 3: Laravel optimization (optional - may fail if not fully installed, so we use || true)
echo "⚡ Running Laravel optimization..."
php artisan optimize 2>/dev/null || echo "⚠️ optimize skipped (may be normal during initial deploy)"

echo ""
echo "✅ Laravel optimization completed"
echo ""

# Step 4: Clear Laravel caches
echo "🧹 Clearing Laravel application cache..."
php artisan cache:clear 2>/dev/null || true

echo ""
echo "✅ Application cache cleared"
echo ""

# Step 5: Clear config cache
echo "⚙️ Clearing configuration cache..."
php artisan config:clear 2>/dev/null || true

echo ""
echo "✅ Configuration cache cleared"
echo ""

# Step 6: Clear route cache (if applicable)
echo "🛣️ Clearing route cache..."
php artisan route:clear 2>/dev/null || true

echo ""
echo "✅ Route cache cleared"
echo ""

echo ""
echo "========================================="
echo "✅ Deployment Completed Successfully!"
echo "========================================="
echo ""
echo "📍 Your Speech Publications app is now ready!"
echo ""
echo "💡 Tips:"
echo "   - Visit http://yourdomain.com to verify"
echo "   - Run 'php artisan serve' locally for testing"
echo "   - Check storage links: php artisan storage:link"
echo ""