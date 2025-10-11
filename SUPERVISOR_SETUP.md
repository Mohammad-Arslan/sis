# Supervisor Configuration for Laravel Queue & Reverb

This guide explains how to set up Supervisor to manage Laravel queue workers and Reverb WebSocket server.

## 📋 Table of Contents

1. [Quick Setup](#quick-setup)
2. [Manual Setup](#manual-setup)
3. [Configuration Details](#configuration-details)
4. [Management Commands](#management-commands)
5. [Troubleshooting](#troubleshooting)

---

## 🚀 Quick Setup

### Option 1: Automated Setup (Recommended)

Run the provided setup script:

```bash
sudo bash /var/www/html/laravel/sis/setup-supervisor.sh
```

This script will:
- Install Supervisor (if not already installed)
- Copy configuration files to `/etc/supervisor/conf.d/`
- Create necessary log directories
- Start the queue worker and Reverb server
- Display status

---

## 🔧 Manual Setup

### Step 1: Install Supervisor

```bash
sudo apt update
sudo apt install -y supervisor
```

### Step 2: Enable Supervisor Service

```bash
sudo systemctl enable supervisor
sudo systemctl start supervisor
```

### Step 3: Copy Configuration Files

```bash
# Copy Laravel Queue Worker config
sudo cp /var/www/html/laravel/sis/supervisor-laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# Copy Laravel Reverb config
sudo cp /var/www/html/laravel/sis/supervisor-laravel-reverb.conf /etc/supervisor/conf.d/laravel-reverb.conf
```

### Step 4: Create Log Directories

```bash
sudo mkdir -p /var/www/html/laravel/sis/storage/logs
sudo chown -R www-data:www-data /var/www/html/laravel/sis/storage/logs
sudo chmod -R 775 /var/www/html/laravel/sis/storage/logs
```

### Step 5: Reload and Start

```bash
# Reload Supervisor configuration
sudo supervisorctl reread
sudo supervisorctl update

# Start the programs
sudo supervisorctl start laravel-worker:*
sudo supervisorctl start laravel-reverb:*

# Check status
sudo supervisorctl status
```

---

## ⚙️ Configuration Details

### Laravel Queue Worker (`laravel-worker`)

**File:** `/etc/supervisor/conf.d/laravel-worker.conf`

**Configuration:**
- **Processes:** 2 workers running in parallel
- **Queues:** `imports`, `default`
- **Max Time:** 3600 seconds (1 hour)
- **Tries:** 3 attempts before failing
- **Auto-restart:** Yes
- **User:** www-data
- **Log:** `/var/www/html/laravel/sis/storage/logs/worker.log`

**Key Features:**
- Handles employee imports on the `imports` queue
- Processes other jobs on the `default` queue
- Automatically restarts if it crashes
- Graceful shutdown with 1-hour timeout

### Laravel Reverb (`laravel-reverb`)

**File:** `/etc/supervisor/conf.d/laravel-reverb.conf`

**Configuration:**
- **Processes:** 1 Reverb server
- **Host:** 0.0.0.0 (accessible from all interfaces)
- **Port:** 8080
- **Auto-restart:** Yes
- **User:** www-data
- **Log:** `/var/www/html/laravel/sis/storage/logs/reverb.log`

**Key Features:**
- WebSocket server for real-time updates
- Broadcasts employee import progress
- Automatically restarts if it crashes

---

## 📊 Management Commands

### Check Status

```bash
sudo supervisorctl status
```

**Expected Output:**
```
laravel-reverb:laravel-reverb   RUNNING   pid 12345, uptime 0:05:30
laravel-worker:laravel-worker_00 RUNNING   pid 12346, uptime 0:05:30
laravel-worker:laravel-worker_01 RUNNING   pid 12347, uptime 0:05:30
```

### Start All Programs

```bash
sudo supervisorctl start all
```

### Stop All Programs

```bash
sudo supervisorctl stop all
```

### Restart All Programs

```bash
sudo supervisorctl restart all
```

### Restart Specific Program

```bash
# Restart queue workers
sudo supervisorctl restart laravel-worker:*

# Restart Reverb
sudo supervisorctl restart laravel-reverb:*
```

### Reload Configuration

After modifying configuration files:

```bash
sudo supervisorctl reread
sudo supervisorctl update
```

### View Logs

```bash
# Queue worker logs
tail -f /var/www/html/laravel/sis/storage/logs/worker.log

# Reverb logs
tail -f /var/www/html/laravel/sis/storage/logs/reverb.log

# Supervisor logs
tail -f /var/log/supervisor/supervisord.log
```

### Interactive Mode

```bash
sudo supervisorctl
```

Then use commands without `sudo supervisorctl` prefix:
```
supervisor> status
supervisor> restart all
supervisor> tail -f laravel-worker
supervisor> quit
```

---

## 🔍 Troubleshooting

### Issue: Programs not starting

**Check logs:**
```bash
sudo tail -f /var/log/supervisor/supervisord.log
```

**Common causes:**
- Incorrect file paths in configuration
- Permission issues
- PHP not in PATH for www-data user

**Solution:**
```bash
# Verify PHP path
which php

# Update configuration if needed
sudo nano /etc/supervisor/conf.d/laravel-worker.conf

# Reload
sudo supervisorctl reread
sudo supervisorctl update
```

### Issue: Queue worker stops processing

**Restart the worker:**
```bash
sudo supervisorctl restart laravel-worker:*
```

**Check for errors:**
```bash
tail -100 /var/www/html/laravel/sis/storage/logs/worker.log
```

### Issue: Reverb WebSocket not connecting

**Check if Reverb is running:**
```bash
sudo supervisorctl status laravel-reverb
```

**Check port availability:**
```bash
sudo netstat -tulpn | grep 8080
```

**Restart Reverb:**
```bash
sudo supervisorctl restart laravel-reverb:*
```

### Issue: Permission denied errors

**Fix permissions:**
```bash
sudo chown -R www-data:www-data /var/www/html/laravel/sis/storage
sudo chmod -R 775 /var/www/html/laravel/sis/storage
```

### Issue: Programs in FATAL state

**Check configuration:**
```bash
sudo supervisorctl tail laravel-worker stderr
sudo supervisorctl tail laravel-reverb stderr
```

**Common fixes:**
```bash
# Clear failed state
sudo supervisorctl clear laravel-worker:*
sudo supervisorctl clear laravel-reverb:*

# Restart
sudo supervisorctl restart all
```

---

## 🔄 Updating Configuration

When you need to change configuration (e.g., add more workers, change queues):

1. **Edit the configuration file:**
   ```bash
   sudo nano /etc/supervisor/conf.d/laravel-worker.conf
   ```

2. **Reload Supervisor:**
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   ```

3. **Restart the program:**
   ```bash
   sudo supervisorctl restart laravel-worker:*
   ```

---

## 📈 Monitoring

### Real-time Monitoring

```bash
# Watch all processes
watch -n 1 'sudo supervisorctl status'

# Monitor logs in real-time
sudo tail -f /var/www/html/laravel/sis/storage/logs/worker.log \
             /var/www/html/laravel/sis/storage/logs/reverb.log
```

### Check Resource Usage

```bash
# CPU and Memory usage
ps aux | grep -E 'queue:work|reverb:start'

# Detailed process info
top -p $(pgrep -d',' -f 'queue:work|reverb:start')
```

---

## 🎯 Best Practices

1. **Monitor Logs Regularly**
   - Check worker logs for failed jobs
   - Monitor Reverb logs for connection issues

2. **Adjust Worker Count**
   - Increase `numprocs` in worker config for high load
   - Monitor server resources before adding workers

3. **Set Up Alerts**
   - Configure monitoring tools (e.g., Monit, Nagios)
   - Set up email alerts for FATAL states

4. **Regular Restarts**
   - Schedule periodic restarts to prevent memory leaks
   - Use cron: `0 3 * * * supervisorctl restart laravel-worker:*`

5. **Backup Configurations**
   - Keep configuration files in version control
   - Document any custom changes

---

## 📞 Support

If you encounter issues:

1. Check the logs first
2. Verify configuration files
3. Ensure all services are running
4. Check Laravel logs: `storage/logs/laravel.log`
5. Review Redis connection: `redis-cli ping`

---

## ✅ Verification Checklist

After setup, verify:

- [ ] Supervisor is running: `sudo systemctl status supervisor`
- [ ] Workers are running: `sudo supervisorctl status`
- [ ] Logs are being created in `storage/logs/`
- [ ] Queue jobs are processing: Test an import
- [ ] Reverb is accessible: Check port 8080
- [ ] WebSocket connections work: Test real-time progress
- [ ] Auto-restart works: Kill a process and check if it restarts

---

**Last Updated:** 2025-10-09
**Version:** 1.0.0

