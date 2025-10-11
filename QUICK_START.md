# 🚀 Quick Start Guide - Employee Import with Real-Time Progress

## One-Time Setup

### 1. Install Supervisor (Run once)

```bash
sudo bash /var/www/html/laravel/sis/setup-supervisor.sh
```

This will:
- ✅ Install Supervisor
- ✅ Configure Laravel Queue Workers (2 processes)
- ✅ Configure Laravel Reverb WebSocket Server
- ✅ Start all services automatically

---

## Daily Usage

### Check if Services are Running

```bash
sudo supervisorctl status
```

**Expected Output:**
```
laravel-reverb:laravel-reverb   RUNNING   pid 12345, uptime 0:05:30
laravel-worker:laravel-worker_00 RUNNING   pid 12346, uptime 0:05:30
laravel-worker:laravel-worker_01 RUNNING   pid 12347, uptime 0:05:30
```

### Restart Services (if needed)

```bash
# Restart everything
sudo supervisorctl restart all

# Or restart individually
sudo supervisorctl restart laravel-worker:*
sudo supervisorctl restart laravel-reverb:*
```

---

## Testing Employee Import

### 1. Access Import Page

Navigate to: `http://your-domain/employees/import`

### 2. Upload File

- Click "Download Template" to get the Excel template
- Fill in employee data
- Upload the file
- Click "Import Employees"

### 3. Watch Real-Time Progress

You'll see:
- 📊 Progress bar with percentage
- 📈 Live statistics (Processed, Imported, Skipped, Errors)
- ✅ Status updates in real-time
- 🎉 Completion notification

---

## Common Commands

### View Logs

```bash
# Queue worker logs
tail -f /var/www/html/laravel/sis/storage/logs/worker.log

# Reverb WebSocket logs
tail -f /var/www/html/laravel/sis/storage/logs/reverb.log

# Laravel application logs
tail -f /var/www/html/laravel/sis/storage/logs/laravel.log

# Employee import logs
tail -f /var/www/html/laravel/sis/storage/logs/employee_import.log
```

### Check Queue Status

```bash
# Check Redis queue
redis-cli LLEN queues:imports

# Check failed jobs
php artisan queue:failed
```

### Manual Testing (without Supervisor)

```bash
# Terminal 1: Start Reverb
php artisan reverb:start

# Terminal 2: Start Queue Worker
php artisan queue:work redis --queue=imports --tries=3

# Terminal 3: Start Web Server (if needed)
php artisan serve
```

---

## Troubleshooting

### Issue: Import not starting

**Check:**
1. Is Supervisor running? `sudo supervisorctl status`
2. Is Redis running? `redis-cli ping` (should return "PONG")
3. Check logs: `tail -f storage/logs/worker.log`

**Fix:**
```bash
sudo supervisorctl restart all
```

### Issue: No real-time updates

**Check:**
1. Is Reverb running? `sudo supervisorctl status laravel-reverb`
2. Is port 8080 open? `sudo netstat -tulpn | grep 8080`
3. Check browser console for WebSocket errors

**Fix:**
```bash
sudo supervisorctl restart laravel-reverb:*
```

### Issue: Jobs stuck in queue

**Check:**
```bash
redis-cli LLEN queues:imports
php artisan queue:failed
```

**Fix:**
```bash
# Retry failed jobs
php artisan queue:retry all

# Restart workers
sudo supervisorctl restart laravel-worker:*
```

---

## Configuration Files

| File | Purpose | Location |
|------|---------|----------|
| `supervisor-laravel-worker.conf` | Queue worker config | `/etc/supervisor/conf.d/` |
| `supervisor-laravel-reverb.conf` | Reverb config | `/etc/supervisor/conf.d/` |
| `.env` | Environment variables | `/var/www/html/laravel/sis/` |
| `config/queue.php` | Queue configuration | `/var/www/html/laravel/sis/config/` |
| `config/broadcasting.php` | Broadcasting config | `/var/www/html/laravel/sis/config/` |

---

## Environment Variables

Ensure these are set in `.env`:

```env
# Queue Configuration
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Broadcasting Configuration
BROADCAST_DRIVER=reverb

# Reverb Configuration
REVERB_APP_ID=843499
REVERB_APP_KEY=xdcmmqyr0puossnqsvqc
REVERB_APP_SECRET=nik4kekewfpywisykiqc
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http
```

---

## Performance Tips

### For Large Imports (10,000+ employees)

1. **Increase worker count:**
   ```bash
   sudo nano /etc/supervisor/conf.d/laravel-worker.conf
   # Change: numprocs=2 to numprocs=4
   sudo supervisorctl reread && sudo supervisorctl update
   ```

2. **Monitor resources:**
   ```bash
   htop
   # Watch CPU and memory usage
   ```

3. **Optimize Redis:**
   ```bash
   # Check Redis memory
   redis-cli INFO memory
   ```

---

## Security Checklist

- [ ] Reverb is behind firewall (only accessible from app server)
- [ ] Redis is not exposed to public internet
- [ ] Supervisor logs are rotated
- [ ] File upload size limits are set
- [ ] Only authenticated users can import

---

## Support

For detailed documentation, see:
- 📖 [SUPERVISOR_SETUP.md](./SUPERVISOR_SETUP.md) - Complete Supervisor guide
- 📖 [Laravel Queue Documentation](https://laravel.com/docs/12.x/queues)
- 📖 [Laravel Reverb Documentation](https://laravel.com/docs/12.x/reverb)

---

**Quick Reference Card**

| Action | Command |
|--------|---------|
| Check status | `sudo supervisorctl status` |
| Restart all | `sudo supervisorctl restart all` |
| View logs | `tail -f storage/logs/worker.log` |
| Test import | Visit `/employees/import` |
| Check queue | `redis-cli LLEN queues:imports` |

---

**Last Updated:** 2025-10-09

