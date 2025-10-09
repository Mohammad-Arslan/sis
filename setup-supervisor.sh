#!/bin/bash

# Laravel Queue & Reverb Supervisor Setup Script
# This script sets up Supervisor to manage Laravel queue workers and Reverb WebSocket server

echo "=========================================="
echo "Laravel Queue & Reverb Supervisor Setup"
echo "=========================================="
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo "❌ Please run this script as root or with sudo"
    echo "Usage: sudo bash setup-supervisor.sh"
    exit 1
fi

echo "✓ Running with root privileges"
echo ""

# Install Supervisor if not already installed
echo "📦 Checking Supervisor installation..."
if ! command -v supervisorctl &> /dev/null; then
    echo "Installing Supervisor..."
    apt update
    apt install -y supervisor
    echo "✓ Supervisor installed successfully"
else
    echo "✓ Supervisor is already installed"
fi
echo ""

# Enable and start Supervisor service
echo "🚀 Enabling Supervisor service..."
systemctl enable supervisor
systemctl start supervisor
echo "✓ Supervisor service enabled and started"
echo ""

# Copy configuration files
echo "📝 Installing Supervisor configuration files..."

# Laravel Queue Worker
if [ -f "/var/www/html/laravel/sis/supervisor-laravel-worker.conf" ]; then
    cp /var/www/html/laravel/sis/supervisor-laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
    echo "✓ Laravel Queue Worker configuration installed"
else
    echo "❌ supervisor-laravel-worker.conf not found"
    exit 1
fi

# Laravel Reverb
if [ -f "/var/www/html/laravel/sis/supervisor-laravel-reverb.conf" ]; then
    cp /var/www/html/laravel/sis/supervisor-laravel-reverb.conf /etc/supervisor/conf.d/laravel-reverb.conf
    echo "✓ Laravel Reverb configuration installed"
else
    echo "❌ supervisor-laravel-reverb.conf not found"
    exit 1
fi
echo ""

# Create log directory if it doesn't exist
echo "📁 Setting up log directories..."
mkdir -p /var/www/html/laravel/sis/storage/logs
chown -R www-data:www-data /var/www/html/laravel/sis/storage/logs
chmod -R 775 /var/www/html/laravel/sis/storage/logs
echo "✓ Log directories configured"
echo ""

# Reload Supervisor configuration
echo "🔄 Reloading Supervisor configuration..."
supervisorctl reread
supervisorctl update
echo "✓ Supervisor configuration reloaded"
echo ""

# Start the programs
echo "▶️  Starting Laravel Queue Worker and Reverb..."
supervisorctl start laravel-worker:*
supervisorctl start laravel-reverb:*
echo ""

# Check status
echo "📊 Current status:"
echo "===================="
supervisorctl status
echo ""

echo "=========================================="
echo "✅ Setup completed successfully!"
echo "=========================================="
echo ""
echo "📋 Useful commands:"
echo "  - Check status:    sudo supervisorctl status"
echo "  - Start all:       sudo supervisorctl start all"
echo "  - Stop all:        sudo supervisorctl stop all"
echo "  - Restart all:     sudo supervisorctl restart all"
echo "  - Reload config:   sudo supervisorctl reread && sudo supervisorctl update"
echo "  - View logs:       tail -f /var/www/html/laravel/sis/storage/logs/worker.log"
echo "  - View logs:       tail -f /var/www/html/laravel/sis/storage/logs/reverb.log"
echo ""
echo "🎉 Your Laravel Queue and Reverb are now running in the background!"

