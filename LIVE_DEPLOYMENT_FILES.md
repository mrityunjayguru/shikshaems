# 🚀 Live Server Pe Upload Karne Wali Files

## ✅ Mandatory Files (Zaruri - Ye Upload Karna Hai)

### 1. Core Service Files
```
app/Services/SimpleTripTrackingService.php
```
**Kya Karta Hai:** GPS data process karta hai, stops calculate karta hai, ETA calculate karta hai

---

### 2. Event Files
```
app/Events/TripLocationUpdate.php
```
**Kya Karta Hai:** WebSocket broadcasting ke liye event (optional, agar WebSocket use kar rahe ho)

---

### 3. Command Files
```
app/Console/Commands/TraccarListener.php
```
**Kya Karta Hai:** Traccar se GPS data receive karta hai (MODIFIED - updated version)

---

### 4. Controller Files
```
app/Http/Controllers/Api/TrasportationApiController.php
```
**Kya Karta Hai:** Live tracking API endpoint (MODIFIED - updated getLiveTracking method)

---

### 5. Migration Files
```
app/Console/Commands/MigrateTraccarFields.php
```
**Kya Karta Hai:** School databases mein `tracking` column add karta hai

---

### 6. School Settings Controller
```
app/Http/Controllers/SchoolSettingsController.php
```
**Kya Karta Hai:** Traccar phone number save karta hai (MODIFIED)

---

### 7. View Files
```
resources/views/school-settings/third-party-apis.blade.php
```
**Kya Karta Hai:** Traccar phone number input field (MODIFIED)

---

### 8. Language Files
```
resources/lang/en.json
```
**Kya Karta Hai:** Traccar translations (MODIFIED - add new translations)

---

### 9. Config Files
```
config/broadcasting.php
```
**Kya Karta Hai:** Broadcasting configuration (MODIFIED - WebSocket settings added)

---

## 📋 Complete Upload Checklist

### Step 1: Backup Current Files
Live server pe pehle backup le lein:
```bash
# SSH pe login karein
cd /www/wwwroot/shikshaems.com

# Backup folder banayein
mkdir -p backups/live-tracking-$(date +%Y%m%d)

# Current files ka backup lein
cp app/Services/SimpleTripTrackingService.php backups/live-tracking-$(date +%Y%m%d)/ 2>/dev/null || true
cp app/Console/Commands/TraccarListener.php backups/live-tracking-$(date +%Y%m%d)/
cp app/Http/Controllers/Api/TrasportationApiController.php backups/live-tracking-$(date +%Y%m%d)/
cp app/Http/Controllers/SchoolSettingsController.php backups/live-tracking-$(date +%Y%m%d)/
cp resources/views/school-settings/third-party-apis.blade.php backups/live-tracking-$(date +%Y%m%d)/
cp config/broadcasting.php backups/live-tracking-$(date +%Y%m%d)/
```

---

### Step 2: Upload Files

#### Option A: Using FTP/SFTP (FileZilla, WinSCP)
```
Local Path → Server Path

app/Services/SimpleTripTrackingService.php
→ /www/wwwroot/shikshaems.com/app/Services/SimpleTripTrackingService.php

app/Events/TripLocationUpdate.php
→ /www/wwwroot/shikshaems.com/app/Events/TripLocationUpdate.php

app/Console/Commands/TraccarListener.php
→ /www/wwwroot/shikshaems.com/app/Console/Commands/TraccarListener.php

app/Console/Commands/MigrateTraccarFields.php
→ /www/wwwroot/shikshaems.com/app/Console/Commands/MigrateTraccarFields.php

app/Http/Controllers/Api/TrasportationApiController.php
→ /www/wwwroot/shikshaems.com/app/Http/Controllers/Api/TrasportationApiController.php

app/Http/Controllers/SchoolSettingsController.php
→ /www/wwwroot/shikshaems.com/app/Http/Controllers/SchoolSettingsController.php

resources/views/school-settings/third-party-apis.blade.php
→ /www/wwwroot/shikshaems.com/resources/views/school-settings/third-party-apis.blade.php

resources/lang/en.json
→ /www/wwwroot/shikshaems.com/resources/lang/en.json

config/broadcasting.php
→ /www/wwwroot/shikshaems.com/config/broadcasting.php
```

#### Option B: Using Git (Recommended)
```bash
# Local machine pe
git add app/Services/SimpleTripTrackingService.php
git add app/Events/TripLocationUpdate.php
git add app/Console/Commands/TraccarListener.php
git add app/Console/Commands/MigrateTraccarFields.php
git add app/Http/Controllers/Api/TrasportationApiController.php
git add app/Http/Controllers/SchoolSettingsController.php
git add resources/views/school-settings/third-party-apis.blade.php
git add resources/lang/en.json
git add config/broadcasting.php

git commit -m "Add live GPS tracking feature"
git push origin main

# Live server pe
cd /www/wwwroot/shikshaems.com
git pull origin main
```

---

### Step 3: Set Permissions
```bash
# SSH pe
cd /www/wwwroot/shikshaems.com

# Storage folder permissions
chmod -R 775 storage/app
chown -R www-data:www-data storage/app

# Create websocket cache directory
mkdir -p storage/app/websocket
chmod -R 775 storage/app/websocket
chown -R www-data:www-data storage/app/websocket
```

---

### Step 4: Run Migrations
```bash
cd /www/wwwroot/shikshaems.com

# Add tracking column to all school databases
php artisan migrate:traccar-fields
```

Expected output:
```
🚀 Starting Traccar fields migration for all schools...
📍 Processing School: School Name (ID: 1)
✅ Added 'tracking' column to route_vehicle_histories
✅ Added Traccar fields to schools table
...
🎉 Traccar fields migration completed!
```

---

### Step 5: Clear Cache
```bash
cd /www/wwwroot/shikshaems.com

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimize (optional)
php artisan optimize
```

---

### Step 6: Start Traccar Listener
```bash
cd /www/wwwroot/shikshaems.com

# Test run first
php artisan traccar:listen
```

Agar sab theek hai to Supervisor se permanent run karein (next section mein)

---

## 🔧 Supervisor Configuration (Production)

### Create Supervisor Config
```bash
sudo nano /etc/supervisor/conf.d/traccar-listener.conf
```

Add this content:
```ini
[program:traccar-listener]
process_name=%(program_name)s
command=php /www/wwwroot/shikshaems.com/artisan traccar:listen
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/www/wwwroot/shikshaems.com/storage/logs/traccar-listener.log
stopwaitsecs=3600
```

### Start Supervisor
```bash
# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update

# Start traccar listener
sudo supervisorctl start traccar-listener

# Check status
sudo supervisorctl status traccar-listener
```

Expected output:
```
traccar-listener                 RUNNING   pid 12345, uptime 0:00:10
```

---

## 📝 Environment Variables (.env)

Live server ke `.env` file mein ye add karein:
```env
# Traccar Configuration
TRACCAR_SOCKET_URL=wss://trackback.trackroutepro.com/api/socket

# WebSocket Configuration (optional - agar WebSocket use kar rahe ho)
WEBSOCKET_URL=ws://your-server-ip:8090
BROADCAST_DRIVER=log

# Or if using real Pusher
# BROADCAST_DRIVER=pusher
# PUSHER_APP_ID=your_app_id
# PUSHER_APP_KEY=your_app_key
# PUSHER_APP_SECRET=your_app_secret
# PUSHER_HOST=your-server-ip
# PUSHER_PORT=6001
# PUSHER_SCHEME=https
```

---

## ✅ Verification Steps

### 1. Check Files Uploaded
```bash
cd /www/wwwroot/shikshaems.com

# Check if files exist
ls -la app/Services/SimpleTripTrackingService.php
ls -la app/Console/Commands/TraccarListener.php
ls -la app/Http/Controllers/Api/TrasportationApiController.php
```

### 2. Check Permissions
```bash
ls -la storage/app/websocket/
# Should show: drwxrwxr-x www-data www-data
```

### 3. Check Migration
```bash
# Check in any school database
mysql -u root -p

USE school_database_name;
SHOW COLUMNS FROM route_vehicle_histories LIKE 'tracking';
# Should show: tracking | tinyint(1) | YES | | 0 |

USE shiksha_ems;
SHOW COLUMNS FROM schools LIKE 'traccar_phone';
# Should show: traccar_phone | varchar(255) | YES | | NULL |
```

### 4. Check Traccar Listener
```bash
# Check if running
ps aux | grep traccar:listen

# Check logs
tail -f storage/logs/laravel.log | grep Traccar
```

### 5. Test API
```bash
# Start a trip first, then:
curl -X GET "https://shikshaems.com/api/driver-helpr/trip/live-tracking?trip_id=9" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🐛 Troubleshooting

### Problem: Files upload nahi ho rahi
**Solution:**
```bash
# Check permissions
ls -la /www/wwwroot/shikshaems.com/app/Services/

# Fix permissions
sudo chown -R www-data:www-data /www/wwwroot/shikshaems.com
sudo chmod -R 755 /www/wwwroot/shikshaems.com
```

### Problem: Migration fail ho raha hai
**Solution:**
```bash
# Check command exists
php artisan list | grep migrate:traccar

# Run with verbose
php artisan migrate:traccar-fields -v
```

### Problem: Traccar listener start nahi ho raha
**Solution:**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check schools table
>>> App\Models\School::whereNotNull('traccar_phone')->count();
```

### Problem: API response null aa raha hai
**Solution:**
```bash
# Check file cache
ls -la storage/app/websocket/

# Check if listener is running
ps aux | grep traccar:listen

# Check if trip is active
mysql -u root -p
USE school_database;
SELECT * FROM route_vehicle_histories WHERE tracking = 1;
```

---

## 📊 Files Summary

| File | Type | Status | Size |
|------|------|--------|------|
| SimpleTripTrackingService.php | NEW | Must Upload | ~8 KB |
| TripLocationUpdate.php | NEW | Must Upload | ~1 KB |
| TraccarListener.php | MODIFIED | Must Upload | ~15 KB |
| MigrateTraccarFields.php | NEW | Must Upload | ~5 KB |
| TrasportationApiController.php | MODIFIED | Must Upload | ~80 KB |
| SchoolSettingsController.php | MODIFIED | Must Upload | ~30 KB |
| third-party-apis.blade.php | MODIFIED | Must Upload | ~10 KB |
| en.json | MODIFIED | Must Upload | ~50 KB |
| broadcasting.php | MODIFIED | Must Upload | ~3 KB |

**Total: 9 files**

---

## 🎯 Deployment Checklist

- [ ] Backup current files
- [ ] Upload all 9 files
- [ ] Set storage permissions (775)
- [ ] Create websocket directory
- [ ] Run migration command
- [ ] Clear all caches
- [ ] Update .env file
- [ ] Test Traccar listener manually
- [ ] Setup Supervisor config
- [ ] Start Supervisor service
- [ ] Verify listener is running
- [ ] Test trip start API
- [ ] Test live tracking API
- [ ] Check logs for errors
- [ ] Test from mobile app

---

## 🚀 Quick Deployment Script

Save this as `deploy-live-tracking.sh`:

```bash
#!/bin/bash

echo "🚀 Deploying Live Tracking Feature..."

# Backup
echo "📦 Creating backup..."
mkdir -p backups/live-tracking-$(date +%Y%m%d)
cp app/Console/Commands/TraccarListener.php backups/live-tracking-$(date +%Y%m%d)/ 2>/dev/null || true
cp app/Http/Controllers/Api/TrasportationApiController.php backups/live-tracking-$(date +%Y%m%d)/ 2>/dev/null || true

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage/app
mkdir -p storage/app/websocket
chmod -R 775 storage/app/websocket

# Run migration
echo "🗄️  Running migration..."
php artisan migrate:traccar-fields

# Clear cache
echo "🧹 Clearing cache..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Test listener
echo "🧪 Testing Traccar listener..."
timeout 10 php artisan traccar:listen || true

echo "✅ Deployment complete!"
echo ""
echo "Next steps:"
echo "1. Setup Supervisor: sudo nano /etc/supervisor/conf.d/traccar-listener.conf"
echo "2. Start service: sudo supervisorctl start traccar-listener"
echo "3. Test API: GET /api/driver-helpr/trip/live-tracking?trip_id=X"
```

Run on server:
```bash
chmod +x deploy-live-tracking.sh
./deploy-live-tracking.sh
```

---

## ✨ Done!

Upload ye 9 files aur deployment script run kar dein. Sab kuch automatically setup ho jayega! 🎉

**Need help?** Check logs: `tail -f storage/logs/laravel.log`
